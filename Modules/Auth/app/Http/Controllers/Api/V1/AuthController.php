<?php

namespace Modules\Auth\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Auth\Http\Requests\Api\V1\LoginRequest;
use Modules\Auth\Interfaces\Api\V1\AuthRepositoryInterface;

class AuthController extends Controller
{
    public function __construct(protected AuthRepositoryInterface $authRepository)
    {
    }

    public function login(LoginRequest $request): JsonResponse
    {
        return $this->authRepository->login($request->validationData());
    }

    public function me(): JsonResponse
    {
        return $this->authRepository->me();
    }

    public function logout(): JsonResponse
    {
        return $this->authRepository->logout();
    }

}
