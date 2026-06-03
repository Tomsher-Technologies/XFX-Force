<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'stripe' => [
        'key' => env('STRIPE_KEY', 'pk_test_51Jc9HWLm1pkHkLue2hpEe3KDPhPAOi8QFi5vICrSK7O2ij0LQKnQFNwabvJfd4hbnrq49kcRfFg0vSBmDailAa2L004NnwUTLF'),
        'secret' => env('STRIPE_SECRET', 'sk_test_51Jc9HWLm1pkHkLueF8oaGt3Z0uJOA2HEbuCZorLwgscGNIRLR7jZdIXd3l4W5NkYO5ly61nWgABkpx8L5yLOv3ga00OIseCprN'),
    ],

    'tabby' => [
        'public_key'   => env('TABBY_PUBLIC_KEY', 'pk_test_f538ac18-323b-43a5-b25d-601ad424fcf3'),
        'secret_key'   => env('TABBY_SECRET_KEY', 'sk_test_f610cd7b-a13f-4183-8ab5-b7960efd323e'),
        'merchant_code'=> env('TABBY_MERCHANT_CODE', 'PCG'),
        'api_url'      => env('TABBY_API_URL'),
    ],

];
