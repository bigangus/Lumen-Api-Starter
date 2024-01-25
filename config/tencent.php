<?php

return [
    'endpoint' => env('TENCENT_ENDPOINT'),
    'secret_id' => env('TENCENT_SECRET_ID'),
    'secret_key' => env('TENCENT_SECRET_KEY'),

    'sms' => [
        'template_id' => env('TENCENT_SMS_TEMPLATE_ID'),
        'sign' => env('TENCENT_SMS_SIGN'),
        'app_id' => env('TENCENT_SMS_APP_ID'),
    ]
];
