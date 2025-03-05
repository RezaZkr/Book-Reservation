<?php

namespace Modules\General\Providers;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class JsonResponseProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $response = app(ResponseFactory::class);

        $response->macro('success', function (string $message = null, $data = null, int $status = ResponseAlias::HTTP_OK) use ($response) {

            $responseData = [
                'message'     => $message ?? trans('general::message.success'),
                'status_code' => $status,
            ];

            if ($data) {
                $responseData['data'] = $data;
            }

            return $response->json($responseData, $status);

        });

        $response->macro('error', function (string $message = null, array $errors = [], int $status = ResponseAlias::HTTP_INTERNAL_SERVER_ERROR) use ($response) {

            $responseData = [
                'message'     => $message ?? trans('general::message.error'),
                'status_code' => $status,
            ];

            if (count($errors)) {
                $flattenErrors = [];
                array_walk_recursive($errors, function ($error) use (&$flattenErrors) {
                    $flattenErrors[] = $error;
                });
                $responseData['errors'] = $flattenErrors;
            }

            return $response->json($responseData, $status);

        });
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }
}
