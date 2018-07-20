<?php

use Dredd\Hooks;
use App\DataFixtures\ORM\LoadUserData as UserFixtures;

/**
 * Login in as admin for every request
 */
Hooks::beforeAll(function (&$transactions) {
    foreach ($transactions as $transaction) {
        $requestHeader = (array) $transaction->request->headers;
        $requestHeader['X-AUTH-TOKEN'] = UserFixtures::USER_ADMIN['apiKey'];

        $transaction->request->headers = $requestHeader;
    }
});
