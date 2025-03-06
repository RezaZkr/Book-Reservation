<?php

namespace Modules\Reservation\Repositories\Api\V1;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Auth\Enums\GuardEnum;
use Modules\Book\Enums\BookVersionStatusEnum;
use Modules\Branch\Interfaces\Api\V1\BranchRepositoryInterface;
use Modules\Reservation\Enums\LoanStatusEnum;
use Modules\Reservation\Enums\ReservationStatusEnum;
use Modules\Reservation\Events\BookLoanEvent;
use Modules\Reservation\Events\BookLoanReturnEvent;
use Modules\Reservation\Interfaces\Api\V1\LoanRepositoryInterface;
use Modules\Reservation\Models\Loan;
use Modules\Reservation\Models\Reservation;
use Modules\Reservation\Services\Api\V1\ReservationCacheService;
use Modules\Reservation\Transformers\Api\V1\LoanResource;
use Modules\User\Enums\UserTypeEnum;
use Modules\User\Interfaces\Api\V1\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class LoanRepository implements LoanRepositoryInterface
{
    public function __construct(
        protected BranchRepositoryInterface $branchRepository,
    )
    {
    }

    public function loan(array $data): JsonResponse
    {
        ['branch_id' => $branchId, 'book_version_id' => $bookVersionId] = $data;

        if (ReservationCacheService::hasBookVersionInLoanList(branchId: $branchId, bookVersionId: $bookVersionId)) {
            return response()->error(message: trans('reservation::message.book_version_in_loan_try_reserve'), status: Response::HTTP_NOT_ACCEPTABLE);
        }

        if (!$bookVersion = $this->branchRepository->hasBookVersion(branchId: $branchId, bookVersionId: $bookVersionId, loanable: true)) {
            return response()->error(message: trans('reservation::message.book_version_not_found_in_branch'), status: Response::HTTP_NOT_FOUND);
        }

        $user = auth(GuardEnum::SANCTUM)->user();
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

            $bookVersion->status = BookVersionStatusEnum::LOAN;
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

            BookLoanEvent::dispatch($loan);

            return response()->success(data: [
                'loan' => LoanResource::make($loan->load(['user', 'branch', 'bookVersion.attributes']))
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

        if (!$bookVersion = $loan->bookVersion()->lockForUpdate()->first()) {
            return response()->error();
        }

        $bookVersion->status = BookVersionStatusEnum::AVAILABLE;
        $bookVersion->save();

        //todo add job to update loans status that are more than 100 days past their expiration_date
        $loan->status = LoanStatusEnum::RETURNED;
        $loan->receive_status = $receiveStatus;
        $loan->return_date = now();
        $loan->save();


        ReservationCacheService::removeBookVersionFromLoanList($loan->branch_id, $loan->book_version_id);

        BookLoanReturnEvent::dispatch($loan);

        $userRepository = app(UserRepositoryInterface::class);
        $message = trans('reservation::message.book_version_loan_returned');
        if ($penaltyPoints = $userRepository->updatePenaltyPointOnBookReturn($loan->id)) {
            $message = trans('reservation::message.book_version_loan_returned_with_penalty', [
                'points' => $penaltyPoints
            ]);
        }

        return response()->success(data: ['message' => $message]);
    }

    public function getDelayedLoans(): ?Collection
    {
        return Loan::query()
            ->where('status', '=', LoanStatusEnum::ACTIVE)
            ->whereNull(['receive_status', 'return_date'])
            ->where('expiration_date', '<', now())
            ->get();
    }

    public function findById(int $id): ?Loan
    {
        return Loan::query()->where('id', '=', $id)->first();
    }

    public function loanByReservationRequest(Reservation $reservation): void
    {
        if (!$bookVersion = $this->branchRepository->hasBookVersion(branchId: $reservation->branch_id, bookVersionId: $reservation->book_version_id, loanable: true)) {
            throw new \Exception(message: trans('reservation::message.book_version_not_found_in_branch'));
        }

        $reservation->loadMissing('user');
        $user = $reservation->user;
        if ($bookVersion->vip && $user->type !== UserTypeEnum::VIP) {
            throw new \Exception(message: trans('reservation::message.book_version_only_for_vip_users'));
        }

        if ($bookVersion->status === BookVersionStatusEnum::LOAN) {
            ReservationCacheService::addBookVersionToLoanList(branchId: $reservation->branch_id, bookVersionId: $reservation->book_version_id);
            throw new \Exception(message: trans('reservation::message.book_version_in_loan_try_reserve'));
        }

        try {

            DB::beginTransaction();

            $bookVersion->status = BookVersionStatusEnum::LOAN;
            $bookVersion->save();

            $loan = Loan::query()
                ->create([
                    'user_id'         => $user->id,
                    'branch_id'       => $reservation->branch_id,
                    'book_version_id' => $reservation->book_version_id,
                    'status'          => LoanStatusEnum::ACTIVE,
                    'loan_date'       => now(),
                    'expiration_date' => now()->addDays(Loan::LOAN_EXPIRE__IN_DAYS),
                    'give_status'     => $bookVersion->condition,
                ]);

            $reservation->status = ReservationStatusEnum::ACCEPTED;
            $reservation->save();

            ReservationCacheService::addBookVersionToLoanList($reservation->branch_id, $reservation->book_version_id);

            DB::commit();

            BookLoanEvent::dispatch($loan);

        } catch (\Exception $exception) {
            DB::rollBack();
            throw new $exception;
        }

    }
}
