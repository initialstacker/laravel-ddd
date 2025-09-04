<?php

return [
    'connection' => [
        'driver' => 'pdo_pgsql',
        'host' => env(key: 'DB_HOST'),
        'port' => env(key: 'DB_PORT'),
        'dbname' => env(key: 'DB_DATABASE'),
        'user' => env(key: 'DB_USERNAME'),
        'password' => env(key: 'DB_PASSWORD'),
        'options' => [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        ],
    ],
    'redis_ssl_options' => [
        'ssl' => [
            'cafile' => env(key: 'REDIS_CAFILE'),
            'local_cert' => env(key: 'REDIS_CLIENT_CERT'),
            'local_pk' => env(key: 'REDIS_CLIENT_KEY'),
            'verify_peer' => filter_var(
                value: env(key: 'REDIS_VERIFY_PEER'),
                options: FILTER_VALIDATE_BOOLEAN
            ),
            'verify_peer_name' => filter_var(
                value: env(key: 'REDIS_VERIFY_PEER_NAME'),
                options: FILTER_VALIDATE_BOOLEAN
            ),
        ],
    ],
    'metadata_dirs' => [
        app_path(path: 'Entities'),
    ],
    'custom_types' => [],
    'redis_url' => env(key: 'REDIS_URL'),
    'dev_mode' => env(key: 'APP_ENV') === 'dev',
];
