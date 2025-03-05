<?php

namespace Modules\Branch\Interfaces\Api\V1;

use Modules\Book\Models\BookVersion;

interface BranchRepositoryInterface
{
    public function hasBookVersion(int $branchId, int $bookVersionId,bool $loanable=false): ?BookVersion;
}
