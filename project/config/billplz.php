<?php
/**
 * PayPal Setting & API Credentials
 * Created by Raza Mehdi <srmk@outlook.com>.
 */

return [
    'key' => env('BILLPLZ_API_KEY'),
    'version' => env('BILLPLZ_VERSION', 'v4'),
    'x-signature' => env('BILLPLZ_X_SIGNATURE'),
    'sandbox' => env('BILLPLZ_SANDBOX', false),
];
