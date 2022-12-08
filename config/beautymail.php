<?php

return [

    // These CSS rules will be applied after the regular template CSS

    'css' => [
        '.button-content .button { background: #6d50fe }',
        'a { color: #6d50fe }',
    ],

    'colors' => [
        'highlight' => '#6d50fe',
        'button'    => '#0f2137',
    ],

    'view' => [
        'senderName'  => env('APP_NAME'),
        'reminder'    => null,
        'unsubscribe' => null,
        'address'     => null,

        'logo'        => [
            'path'   => '%PUBLIC%/images/logo/email/email-logo.png',
            'width'  => '',
            'height' => '',
        ],

        'twitter'  => null,
        'facebook' => null,
        'flickr'   => null,
    ],

];
