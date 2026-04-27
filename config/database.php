<?php

use Illuminate\Support\Str;

$sqliteDatabase = env('DB_DATABASE', database_path('database.sqlite'));
$mysqlSslCaAttribute = class_exists('Pdo\\Mysql') && defined('Pdo\\Mysql::ATTR_SSL_CA')
    ? constant('Pdo\\Mysql::ATTR_SSL_CA')
    : PDO::MYSQL_ATTR_SSL_CA;

if (
    env('DB_CONNECTION', 'sqlite') === 'sqlite'
    && is_string($sqliteDatabase)
    && $sqliteDatabase !== ':memory:'
    && ! str_starts_with($sqliteDatabase, '/')
    && ! preg_match('/^[A-Z]:[\/\\\\]/i', $sqliteDatabase)
) {
    $sqliteDatabase = base_path($sqliteDatabase);
}

return [
    'default' => env('DB_CONNECTION', 'sqlite'),
    'connections' => [
        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DB_URL'),
            'database' => $sqliteDatabase,
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
            'busy_timeout' => null,
            'journal_mode' => null,
            'synchronous' => null,
        ],
        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DB_URL', env('MYSQL_URL')),
            'host' => env('DB_HOST', env('MYSQLHOST', '127.0.0.1')),
            'port' => env('DB_PORT', env('MYSQLPORT', '3306')),
            'database' => env('DB_DATABASE', env('MYSQLDATABASE', 'laravel')),
            'username' => env('DB_USERNAME', env('MYSQLUSER', 'root')),
            'password' => env('DB_PASSWORD', env('MYSQLPASSWORD', '')),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                $mysqlSslCaAttribute => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
    ],
    'migrations' => [
        'table' => 'migrations',
        'update_date_on_publish' => true,
    ],
    'redis' => [
        'client' => env('REDIS_CLIENT', 'phpredis'),
        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
        ],
        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],
    ],
];
