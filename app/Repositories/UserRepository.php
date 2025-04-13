<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    /**
     * @param $data
     * @return mixed
     */
    public function store($data)
    {
        return User::create($data);
    }

}
