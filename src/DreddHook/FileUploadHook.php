<?php

use Dredd\Hooks;

/**
 * Use static binary content for file upload
 */
Hooks::before(
    '/artists/{artist}/picture > Upload a new artist picture. > 200 > application/json',
    function (&$transaction) {
        // $transaction->request->body = "42";
        // $transaction->request->headers->{'Content-type'} = 'image/*';
        // we better think about an upload configuration for the test env that doesn't really upload files.
        $transaction->skip = true;
    }
);
