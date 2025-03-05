<?php

namespace Modules\Reservation\Repositories\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Auth\Enums\GuardEnum;
use Modules\Book\Enums\BookVersionConditionEnum;
use Modules\Book\Enums\BookVersionStatusEnum;
use Modules\Branch\Interfaces\Api\V1\BranchRepositoryInterface;
use Modules\Reservation\Enums\LoanStatusEnum;
use Modules\Reservation\Interfaces\Api\V1\LoanRepositoryInterface;
use Modules\Reservation\Models\Loan;
use Modules\Reservation\Services\Api\V1\ReservationCacheService;
use Modules\Reservation\Transformers\Api\V1\LoanResource;
use Modules\User\Enums\UserTypeEnum;
use Modules\User\Interfaces\Api\V1\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class LoanRepository implements LoanRepositoryInterface
{
    public function __construct(
        protected BranchRepositoryInterface $branchRepository,
        protected UserRepositoryInterface   $userRepository,
    )
    {
    }

    public function loan(array $data): JsonResponse
    {
        $user = auth(GuardEnum::SANCTUM)->user();
        if ($user->restricted) {
            return response()->error(message: trans('reservation::message.user_restricted'), status: Response::HTTP_FORBIDDEN);
        }

        ['branch_id' => $branchId, 'book_version_id' => $bookVersionId] = $data;

        if (ReservationCacheService::hasBookVersionInLoanList(branchId: $branchId, bookVersionId: $bookVersionId)) {
            return response()->error(message: trans('reservation::message.book_version_in_loan_try_reserve'), status: Response::HTTP_NOT_ACCEPTABLE);
        }

        if (!$bookVersion = $this->branchRepository->hasBookVersion(branchId: $branchId, bookVersionId: $bookVersionId, loanable: true)) {
            return response()->error(message: trans('reservation::message.book_version_not_found_in_branch'), status: Response::HTTP_NOT_FOUND);
        }

        if ($bookVersion->vip && $user->type !== UserTypeEnum::VIP) {
            return response()->error(message: trans('reservation::message.book_version_only_for_vip_users'), status: Response::HTTP_NOT_ACCEPTABLE);
        }

        if ($bookVersion->status === BookVersionStatusEnum::LOAN) {
            Log::critical('Loan Error', [
                'branchId'      => $branchId,
                'bookVersionId' => $bookVersionId,
            ]);
            ReservationCacheService::addBookVersionToLoanList(branchId: $branchId, bookVersionId: $bookVersionId);
            return response()->error(message: trans('reservation::message.book_version_in_loan_try_reserve'), status: Response::HTTP_NOT_ACCEPTABLE);
        }

        try {

            DB::beginTransaction();

            $bookVersion->status = BookVersionStatusEnum::AVAILABLE;
            $bookVersion->save();

            $loan = Loan::query()
                ->create([
                    'user_id'         => $user->id,
                    'branch_id'       => $branchId,
                    'book_version_id' => $bookVersionId,
                    'status'          => LoanStatusEnum::ACTIVE,
                    'loan_date'       => now(),
                    'expiration_date' => now()->addDays(Loan::LOAN_EXPIRE__IN_DAYS),
                    'give_status'     => $bookVersion->condition,
                ]);

            ReservationCacheService::addBookVersionToLoanList($branchId, $bookVersionId);

            DB::commit();

            return response()->success(data: [
                'loan' => LoanResource::make($loan->load(['user', 'branch', 'bookVersion']))
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            report($exception);
            return response()->error(message: trans('general::message.error'), status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function return(array $data): JsonResponse
    {
        $user = auth(GuardEnum::SANCTUM)->user();
        ['loan_id' => $loanId, 'receive_status' => $receiveStatus] = $data;

        $loan = Loan::query()
            ->where('id', '=', $loanId)
            ->where('user_id', '=', $user->id)
            ->whereIn('status', [LoanStatusEnum::ACTIVE, LoanStatusEnum::LOST])
            ->lockForUpdate()
            ->first();

        if (!$loan) {
            return response()->error();
        }

        $status = LoanStatusEnum::RETURNED_ON_TIME;
        if ($loan->expiration_date->lessThan(now())) {
            $status = LoanStatusEnum::LATE_RETURN;
        }

        //todo add job to update loans status that are more than 100 days past their expiration_date
        $loan->status = $status;
        $loan->receive_status = $receiveStatus;
        $loan->return_date = now();
        $loan->save();

        ReservationCacheService::removeBookVersionFromLoanList($loan->branch_id, $loan->book_version_id);

        $message = trans('reservation::message.book_version_loan_returned');
        if ($penaltyPoints = $this->userRepository->updatePenaltyPoint($loan->id)) {
            $message = trans('reservation::message.book_version_loan_returned_with_penalty', [
                'points' => $penaltyPoints
            ]);
        }

        return response()->success(data: ['message' => $message]);
    }
}
