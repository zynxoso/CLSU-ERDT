# Redis and Laravel Fortify Setup Guide

This guide provides instructions for configuring Redis caching and Laravel Fortify authentication in the CLSU-ERDT PRISM system.

## Redis Configuration

Add the following configuration to your `.env` file:

```
# Redis Configuration
CACHE_STORE=redis
REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_DB=0
REDIS_CACHE_DB=1
```

## Laravel Fortify Configuration

Add the following configuration to your `.env` file:

```
# Fortify Configuration
FORTIFY_HOME=/dashboard
```

## Installing Redis on Windows

1. Download and install Redis for Windows from [https://github.com/microsoftarchive/redis/releases](https://github.com/microsoftarchive/redis/releases)
2. Start the Redis server by running `redis-server` from the installation directory
3. Verify Redis is running by executing `redis-cli ping` (should return "PONG")

## Testing Redis Connection

You can test the Redis connection by running the following command:

```
php artisan tinker
```

Then in the Tinker console:

```php
Redis::connection()->ping();
```

If successful, it should return "PONG".

## Fortify Features

Laravel Fortify provides the following authentication features:

1. User registration
2. Login
3. Password reset
4. Email verification
5. Two-factor authentication
6. Profile information updates
7. Password updates

These features are configured in the `config/fortify.php` file.

## Cache Implementation

To use Redis for caching in your application, you can use the Laravel Cache facade:

```php
use Illuminate\Support\Facades\Cache;

// Store an item in the cache for 10 minutes
Cache::put('key', 'value', 600);

// Retrieve an item from the cache
$value = Cache::get('key');

// Store an item in the cache indefinitely
Cache::forever('key', 'value');

// Remove an item from the cache
Cache::forget('key');
```

## Performance Benefits

Using Redis for caching provides several performance benefits:

1. Faster response times
2. Reduced database load
3. Improved scalability
4. Better handling of concurrent requests
