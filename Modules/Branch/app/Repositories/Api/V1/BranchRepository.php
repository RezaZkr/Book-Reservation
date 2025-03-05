<?php

namespace Modules\Branch\Repositories\Api\V1;

use Modules\Book\Enums\BookVersionConditionEnum;
use Modules\Book\Enums\BookVersionStatusEnum;
use Modules\Book\Models\BookVersion;
use Modules\Branch\Interfaces\Api\V1\BranchRepositoryInterface;

class BranchRepository implements BranchRepositoryInterface
{
    public function hasBookVersion(int $branchId, int $bookVersionId, bool $loanable = false): ?BookVersion
    {
        return BookVersion::query()
            ->where('id', '=', $bookVersionId)
            ->where('branch_id', '=', $branchId)
            ->when($loanable, function ($query) {
                $query->whereIn('condition', [BookVersionConditionEnum::NEW, BookVersionConditionEnum::MODERATELY_USED]);
                $query->whereIn('status', [BookVersionStatusEnum::AVAILABLE, BookVersionStatusEnum::LOAN]);
                $query->lockForUpdate();
            })->first();
    }

}
