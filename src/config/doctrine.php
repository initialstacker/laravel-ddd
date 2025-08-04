<?php

use App\Shared\Infrastructure\Id\RoleIdType;
use App\Shared\Infrastructure\Id\PermissionIdType;
use App\Shared\Infrastructure\Id\UserIdType;
use App\Shared\Infrastructure\Slug\RoleSlugType;
use App\Shared\Infrastructure\Slug\PermissionSlugType;

return [
    'connection' => [
        'driver' => 'pdo_pgsql',
        'host' => env(key: 'DB_HOST', default: 'postgres'),
        'port' => env(key: 'DB_PORT', default: '5432'),
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
            'cafile' => env(key: 'REDIS_CAFILE', default: '/certs/ca.crt'),
            'local_cert' => env(key: 'REDIS_CLIENT_CERT', default: '/certs/client.crt'),
            'local_pk' => env(key: 'REDIS_CLIENT_KEY', default: '/certs/client.key'),
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
        app_path(path: 'Account/Domain'),
    ],
    'custom_types' => [
        RoleIdType::NAME => RoleIdType::class,
        PermissionIdType::NAME => PermissionIdType::class,
        UserIdType::NAME => UserIdType::class,
        RoleSlugType::NAME => RoleSlugType::class,
        PermissionSlugType::NAME => PermissionSlugType::class,
    ],
    'redis_url' => env(key: 'REDIS_URL'),
    'dev_mode' => env(key: 'APP_ENV') === 'dev',
];
