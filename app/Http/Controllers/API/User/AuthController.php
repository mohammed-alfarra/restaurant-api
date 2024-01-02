<?php

namespace App\Http\Controllers\API\User;

use App\Actions\User\RegisterUser;
use App\Enums\Guards;
use App\Http\Controllers\API\BaseAuthController;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\User\RegisterUserRequest;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthController extends BaseAuthController
{
    protected string $guard = Guards::USER;

    public function __construct(
        private RegisterUser $registerUser
    ) {
        $this->middleware('auth:'.$this->guard)->only('me');
    }

    public function register(RegisterUserRequest $request): JsonResponse
    {
        $user = $this->registerUser->execute($request);

        return $this->responseCreated(trans('auth.checking_for_code'), new UserResource($user));
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (! $token = auth($this->guard)->attempt($credentials)) {
            return $this->responseUnprocessable(trans('auth.incorrect_email_password'));
        }

        if (! auth($this->guard)->user()->is_active) {
            return $this->responseConflictError(trans('auth.deactivated_account'));
        }

        if (property_exists($request, 'device_token') && null !== $request->device_token) {
            auth($this->guard)->user()
                ->updateDevice($request->device_token);
        }

        return $this->respondWithToken($token);
    }

    public function userTransformer(): JsonResource
    {
        $user = auth($this->guard)
            ->user();

        return new UserResource($user);
    }
}
