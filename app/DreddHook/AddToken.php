<?php

use Dredd\Hooks;

/**
 * Login in as admin for every request
 */
Hooks::beforeAll(function(&$transactions) {
    foreach ($transactions as $transaction) {

        $requestHeader = (array)$transaction->request->headers;
        $requestHeader['X-AUTH-TOKEN'] = '123';

        $transaction->request->headers = $requestHeader;
    }
});
