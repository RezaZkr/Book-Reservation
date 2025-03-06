<?php

namespace Modules\User\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Auth\Enums\GuardEnum;
use Modules\User\Models\PenaltyRule;
use Symfony\Component\HttpFoundation\Response;

class RestrictedUserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$user = $request->user(GuardEnum::SANCTUM)) {
            return response()->json(status: Response::HTTP_UNAUTHORIZED);
        }

        if ($user->restricted || ($user->penalty_points > PenaltyRule::MAXIMUM_PENALTY_POINT)) {
            return response()->error(message: trans('reservation::message.user_restricted'), status: Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
