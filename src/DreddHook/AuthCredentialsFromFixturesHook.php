<?php

use Dredd\Hooks;
use App\DataFixtures\ORM\LoadUserData as UserFixtures;

/**
 * Use fixture data to login
 */
Hooks::before('/tokens > POST > 200 > application/json', function (&$transaction) {
    $body = json_decode($transaction->request->body);
    $body->username = UserFixtures::USER_ADMIN['username'];
    $body->password = UserFixtures::PASSWORD;

    $transaction->request->body = json_encode($body);
});
