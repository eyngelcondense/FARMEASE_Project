<?php

namespace App\Events;

use CodeIgniter\Events\Events;
use CodeIgniter\Shield\Entities\User;

Events::on('register', function (User $user) {
    // Add new users to the "client" group automatically
    $user->addGroup('client');
});
