<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Repositories\UserRepository;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRequest;

class AuthController extends Controller
{
    //
    private UserRepository $userRepository;
    private ApiResponse $apiResponse;

    public function __construct(UserRepository $userRepository, ApiResponse $apiResponse)
    {
        $this->userRepository = $userRepository;
        $this->apiResponse = $apiResponse;
    }

    //create user
    public function createUser(UserRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $this->userRepository->create($data);
        return $this->apiResponse->success('User created successfully');
    }

    //login
    public function login(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = $this->userRepository->findWhere('email', $request->email);
        if (!$user) {
            return $this->apiResponse->failed('User not found');
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        return $this->apiResponse->success('User logged in successfully', ['token' => $token, 'user' => $user]);
    }
}
