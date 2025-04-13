<?php

namespace App\Services;

use App\Enums\UserRoleEnum;
use App\Jobs\CreateBankAccountForUserJob;
use App\Repositories\UserRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Armazena o usuário e atribui o papel com base no CPF ou CNPJ.
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
        Log::info('testando log');
//        CreateBankAccountForUserJob::dispatch($user);

        return JWTAuth::fromUser($user);
    }

    /**
     * Atribui o papel ao usuário com base no CPF ou CNPJ.
     *
     * @param \App\Models\User $user
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
     * Realiza o login do usuário e retorna o token JWT.
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
