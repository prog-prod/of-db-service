<?php

return [
    'groups' => [
        'admin' => ['admin.*'],
    ],
    'except' => [
        'telescope',
        'debugbar.*',
        'ignition.*',
        '_ignition.*',
        'unisharp.*',
        'horizon.*',
        'admin.*',
        'adminlte.*',
        'moonshine.*',
        'private.*',
        'god',
        'verification.notice',
        'verification.verify',
        'user-password.update',
        'password.confirmation',
        'password.confirm',
        'sanctum.csrf-cookie',
        'set-telegram-webhook',
        'telegram.webhook'
    ]
];
