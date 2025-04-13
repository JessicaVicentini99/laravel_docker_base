<?php

namespace App\Services;

use App\Enums\UserRoleEnum;
use App\Models\User;
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

    /**
     * Stores the user and assigns the role based on CPF or CNPJ.
     *
     * @param array $data
     * @return string
     */
    public function storeUser(array $data): string
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->userRepository->store($data);

        $this->assignUserRole($user, $data['cpf_cnpj']);
        event(new \Illuminate\Auth\Events\Registered($user));
        return JWTAuth::fromUser($user);
    }

    /**
     * Assigns the role to the user based on CPF or CNPJ.
     *
     * @param User $user
     * @param string $cpfCnpj
     * @return void
     */
    private function assignUserRole($user, string $cpfCnpj): void
    {
        $role = strlen($cpfCnpj) === 14
            ? UserRoleEnum::merchant()->value
            : UserRoleEnum::customer()->value;

        $user->assignRole($role);
    }

    /**
     * Performs user login and returns the JWT token.
     *
     * @param array $data
     * @return string|null
     */
    public function login($data)
    {
        $token = JWTAuth::attempt($data);
        return $token;
    }

}
