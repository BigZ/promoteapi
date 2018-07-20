<?php

use Dredd\Hooks;

/**
 * ignore body and headers validation if the response code is 204.
 */
Hooks::beforeAll(function (&$transactions) {
    foreach ($transactions as $transaction) {
        if (204 === $transaction->expected->statusCode) {
            $transaction->expected->body = null;
            $transaction->expected->headers = null;
        }
    }
});
