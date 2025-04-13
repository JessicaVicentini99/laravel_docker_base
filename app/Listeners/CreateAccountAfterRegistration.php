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

    /**
     * Handle the event.
     *
     * @param Registered $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $user = $event->user;
        CreateBankAccountForUserJob::dispatch($user);
    }
}
