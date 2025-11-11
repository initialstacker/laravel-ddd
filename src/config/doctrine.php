<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Database Connection
    |--------------------------------------------------------------------------
    |
    | Define your database connection parameters here. These values are pulled
    | from your environment file using the env() helper for security.
    | Customize driver, host, port, database name, username, password, and PDO options.
    | 
    | Available drivers supported by Doctrine ORM:
    | - pdo_mysql   (MySQL)
    | - pdo_pgsql   (PostgreSQL)
    | - pdo_sqlite  (SQLite)
    | - pdo_sqlsrv  (SQL Server / MSSQL)
    | - oci8        (Oracle)
    |
    | Make sure to set your DB_CONNECTION environment variable to one of these.
    |
    */
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

    /*
    |--------------------------------------------------------------------------
    | Redis SSL Options
    |--------------------------------------------------------------------------
    |
    | Configure optional SSL options for your Redis connection.
    | Uncomment and adjust below if SSL is required for Redis.
    |
    */
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

    /*
    |--------------------------------------------------------------------------
    | Metadata Directories
    |--------------------------------------------------------------------------
    |
    | Paths where Doctrine will look for metadata with attributes to map your entities.
    | Typically, this points to your application's Entities directory.
    |
    */
    'metadata_dirs' => [
        app_path(path: 'Account/Domain'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Doctrine Types
    |--------------------------------------------------------------------------
    |
    | Register any custom Doctrine types here.
    | Example here registers UUID type from ramsey/uuid package.
    |
    */
    'custom_types' => [
        [
            App\Shared\Infrastructure\Id\RoleIdType::NAME,
            App\Shared\Infrastructure\Id\RoleIdType::class
        ],
        [
            App\Shared\Infrastructure\Id\PermissionIdType::NAME,
            App\Shared\Infrastructure\Id\PermissionIdType::class
        ],
        [
            App\Shared\Infrastructure\Id\UserIdType::NAME,
            App\Shared\Infrastructure\Id\UserIdType::class
        ],
        [
            App\Shared\Infrastructure\Slug\RoleSlugType::NAME,
            App\Shared\Infrastructure\Slug\RoleSlugType::class
        ],
        [
            App\Shared\Infrastructure\Slug\PermissionSlugType::NAME,
            App\Shared\Infrastructure\Slug\PermissionSlugType::class
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Redis Connection URL
    |--------------------------------------------------------------------------
    |
    | You can specify your Redis connection as a URL.
    | For example: redis://localhost:6379
    |
    */
    'redis_url' => env(key: 'REDIS_URL'),

    /*
    |--------------------------------------------------------------------------
    | Development Mode
    |--------------------------------------------------------------------------
    |
    | When set to true, Doctrine will generate proxies and metadata dynamically.
    | Typically enabled in local or dev environments.
    |
    */
    'dev_mode' => env(key: 'APP_ENV') === 'dev',
];
