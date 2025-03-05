<?php

namespace Modules\Auth\Repositories\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Enums\GuardEnum;
use Modules\Auth\Interfaces\Api\V1\AuthRepositoryInterface;
use Modules\User\Models\User;
use Modules\User\Transformers\Api\V1\UserResource;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthRepository implements AuthRepositoryInterface
{
    public function login(array $credentials): JsonResponse
    {
        $user = User::query()->where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->error(message: trans('auth::message.credentials_not_matched'), status: ResponseAlias::HTTP_UNAUTHORIZED);
        }

        $user->tokens()->delete();
        $sanctumExpireMinutes = config('sanctum.expiration', 60);
        $token = $user->createToken(name: 'v1', expiresAt: now()->addMinutes($sanctumExpireMinutes));

        return response()->success(data: [
            'auth' => [
                'access_token' => $token->plainTextToken,
                'ttl'          => $sanctumExpireMinutes * 60,//sec
                'expires_at'   => $token->accessToken->expires_at->format('Y-m-d H:i:s'),
            ],
            'user' => UserResource::make($user),
        ]);
    }

    public function me(): JsonResponse
    {
        return response()->success(data: [
            'user' => UserResource::make(auth(GuardEnum::SANCTUM)->user()),
        ]);
    }

    public function logout(): JsonResponse
    {
        auth(GuardEnum::SANCTUM)->user()->tokens()->delete();
        return response()->success();
    }
}
