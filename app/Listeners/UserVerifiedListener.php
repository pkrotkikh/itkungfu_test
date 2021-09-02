<?php

namespace App\Listeners;

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserVerifiedListener
{
    protected $user;

    /**
     * Create the event listener.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $userRole = Role::where(['name' => 'user'])->first();
        $this->user->assignRole($userRole);
    }
}
