<?php

use Dredd\Hooks;
use AppBundle\DataFixtures\ORM\LoadUserData as UserFixtures;

/**
 * Use fixture data to login
 */
Hooks::before('/token > Get token.', function(&$transaction) {
    $body = json_decode($transaction->request->body);
    $body->username = UserFixtures::USER_ADMIN['username'];
    $body->password = UserFixtures::PASSWORD;

    $transaction->request->body = json_encode($body);
});
