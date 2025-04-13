<?php

namespace App\Http\Controllers;

use App\Http\Resources\AccountDetailsResource;
use App\Services\AccountService;

class AccountController extends Controller
{
    private $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function details()
    {
        $account = $this->accountService->getByUser();
        return new AccountDetailsResource($account);
    }

}
