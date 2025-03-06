<?php

namespace Modules\Reservation\Repositories\Api\V1;

use Illuminate\Http\JsonResponse;
use Modules\Auth\Enums\GuardEnum;
use Modules\Book\Enums\BookVersionStatusEnum;
use Modules\Branch\Interfaces\Api\V1\BranchRepositoryInterface;
use Modules\Reservation\Enums\ReservationStatusEnum;
use Modules\Reservation\Interfaces\Api\V1\LoanRepositoryInterface;
use Modules\Reservation\Interfaces\Api\V1\ReservationRepositoryInterface;
use Modules\Reservation\Models\Reservation;
use Modules\Reservation\Transformers\Api\V1\ReservationResource;
use Modules\User\Enums\UserTypeEnum;
use Symfony\Component\HttpFoundation\Response;

class ReservationRepository implements ReservationRepositoryInterface
{
    public function __construct(
        protected BranchRepositoryInterface $branchRepository,
        protected LoanRepositoryInterface   $loanRepository,
    )
    {
    }

    public function reserve(array $data): JsonResponse
    {
        ['branch_id' => $branchId, 'book_version_id' => $bookVersionId] = $data;

        if (!$bookVersion = $this->branchRepository->hasBookVersion(branchId: $branchId, bookVersionId: $bookVersionId, loanable: true)) {
            return response()->error(message: trans('reservation::message.book_version_not_found_in_branch'), status: Response::HTTP_NOT_FOUND);
        }

        $user = auth(GuardEnum::SANCTUM)->user();
        if ($bookVersion->vip && $user->type !== UserTypeEnum::VIP) {
            return response()->error(message: trans('reservation::message.book_version_only_for_vip_users'), status: Response::HTTP_NOT_ACCEPTABLE);
        }

        if ($this->isReservedByUser($data, $user->id)) {
            return response()->error(message: trans('reservation::message.book_already_reserved'), status: Response::HTTP_NOT_ACCEPTABLE);
        }

        if ($bookVersion->status === BookVersionStatusEnum::AVAILABLE) {
            return $this->loanRepository->loan($data);
        }

        try {
            $reservation = Reservation::query()
                ->create([
                    'user_id'            => $user->id,
                    'branch_id'          => $branchId,
                    'book_version_id'    => $bookVersionId,
                    'user_penalty_point' => $user->penalty_points,
                    'status'             => ReservationStatusEnum::PENDING,
                ]);

            return response()->success(data: [
                'reserve' => ReservationResource::make($reservation->load(['user', 'branch', 'bookVersion.attributes']))
            ]);
        } catch (\Exception $exception) {
            report($exception);
            return response()->error(message: trans('general::message.error'), status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function isReservedByUser(array $data, int $userId): bool
    {
        ['branch_id' => $branchId, 'book_version_id' => $bookVersionId] = $data;

        return Reservation::query()
            ->where('user_id', '=', $userId)
            ->where('branch_id', '=', $branchId)
            ->where('book_version_id', '=', $bookVersionId)
            ->where('status', '=', ReservationStatusEnum::PENDING)->exists();
    }

    public function findLowestPenaltyPoint(int $branchId, int $bookVersionId, bool $lock = false): ?Reservation
    {
        return Reservation::query()
            ->where('branch_id', '=', $branchId)
            ->where('book_version_id', '=', $bookVersionId)
            ->where('status', '=', ReservationStatusEnum::PENDING)
            ->orderBy('user_penalty_point')
            ->when($lock, function ($query) {
                $query->lockForUpdate();
            })
            ->first();
    }
}
