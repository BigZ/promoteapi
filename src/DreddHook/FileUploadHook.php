<?php

use Dredd\Hooks;
use App\DataFixtures\ORM\LoadUserData as UserFixtures;

/**
 * Use static binary content for file upload
 */
Hooks::before(
    '/artists/{artist}/picture > Upload a new artist picture. > 200 > application/json',
    function (&$transaction) {
        $transaction->request->body = "42";
    }
);
