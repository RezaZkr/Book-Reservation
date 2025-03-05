<?php

namespace Modules\Auth\Interfaces\Api\V1;

use Illuminate\Http\JsonResponse;

interface AuthRepositoryInterface
{
    public function login(array $credentials): JsonResponse;

    public function me(): JsonResponse;
    public function logout(): JsonResponse;
}
