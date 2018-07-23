<?php

use Dredd\Hooks;

/**
 * Use static binary content for file upload
 * we better think about an upload configuration for the test env that doesn't really upload files.
 * then force $transaction->request->body to like '42' and
 * $transaction->request->headers->{'Content-type'} to 'image/*'
 */
Hooks::before(
    '/artists/{artist}/picture > Upload a new artist picture. > 200 > application/json',
    function (&$transaction) {
        $transaction->skip = true;
    }
);
