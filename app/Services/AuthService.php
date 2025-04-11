<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function storeUser($data)
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->userRepository->store($data);
        $token = JWTAuth::fromUser($user);
        return $token;
    }

    public function login($data)
    {
        $token = JWTAuth::attempt($data);
        return $token;
    }

}
