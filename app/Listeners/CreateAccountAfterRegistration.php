<?php

namespace App\Listeners;

use App\Jobs\CreateBankAccountForUserJob;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateAccountAfterRegistration implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }


    public function handle(Registered $event)
    {
        $user = $event->user;
        CreateBankAccountForUserJob::dispatch($user);
    }
}
