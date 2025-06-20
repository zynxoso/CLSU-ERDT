<?php

return [

    /*
    |--------------------------------------------------------------------------
    | DDoS Protection Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for the DDoS protection
    | mechanisms implemented in the CLSU-ERDT system.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Detection Thresholds
    |--------------------------------------------------------------------------
    |
    | These values determine when DDoS protection mechanisms are triggered.
    |
    */
    'detection' => [
        // Maximum requests per minute before flagging as suspicious
        'max_requests_per_minute' => env('DDOS_MAX_REQUESTS_PER_MINUTE', 50),

        // Maximum requests to same endpoint before flagging
        'max_endpoint_requests' => env('DDOS_MAX_ENDPOINT_REQUESTS', 20),

        // Time window for endpoint request tracking (minutes)
        'endpoint_tracking_window' => env('DDOS_ENDPOINT_TRACKING_WINDOW', 5),

        // Maximum rate limit violations before IP blocking
        'max_violations_before_block' => env('DDOS_MAX_VIOLATIONS_BEFORE_BLOCK', 10),
    ],

    /*
    |--------------------------------------------------------------------------
    | Response Actions
    |--------------------------------------------------------------------------
    |
    | Configuration for automatic responses to detected threats.
    |
    */
    'response' => [
        // Duration of IP blocks (minutes)
        'ip_block_duration' => env('DDOS_IP_BLOCK_DURATION', 60),

        // Duration of dynamic rate limiting (minutes)
        'dynamic_limit_duration' => env('DDOS_DYNAMIC_LIMIT_DURATION', 60),

        // Reduced rate limit for suspicious IPs (requests per minute)
        'suspicious_ip_limit' => env('DDOS_SUSPICIOUS_IP_LIMIT', 10),

        // Cache duration for tracking data (seconds)
        'tracking_cache_duration' => env('DDOS_TRACKING_CACHE_DURATION', 300),
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance Monitoring
    |--------------------------------------------------------------------------
    |
    | Thresholds for performance monitoring and alerting.
    |
    */
    'performance' => [
        // Slow request threshold (seconds)
        'slow_request_threshold' => env('DDOS_SLOW_REQUEST_THRESHOLD', 2.0),

        // High memory usage threshold (bytes)
        'high_memory_threshold' => env('DDOS_HIGH_MEMORY_THRESHOLD', 52428800), // 50MB

        // Enable performance headers in responses
        'enable_performance_headers' => env('DDOS_ENABLE_PERFORMANCE_HEADERS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Bot Detection
    |--------------------------------------------------------------------------
    |
    | Patterns and settings for bot detection.
    |
    */
    'bot_detection' => [
        // User agent patterns that indicate bot traffic
        'suspicious_user_agents' => [
            '/bot/i',
            '/crawler/i',
            '/spider/i',
            '/scraper/i',
            '/curl/i',
            '/wget/i',
            '/python-requests/i',
            '/java/i',
            '/go-http-client/i',
            '/httpclient/i',
        ],

        // Whether to apply stricter limits to detected bots
        'apply_bot_limits' => env('DDOS_APPLY_BOT_LIMITS', true),

        // Rate limit for detected bots (requests per minute)
        'bot_rate_limit' => env('DDOS_BOT_RATE_LIMIT', 5),
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Settings for security logging and monitoring.
    |
    */
    'logging' => [
        // Enable detailed DDoS protection logging
        'enable_detailed_logging' => env('DDOS_ENABLE_DETAILED_LOGGING', true),

        // Log level for DDoS events (debug, info, warning, error, critical)
        'log_level' => env('DDOS_LOG_LEVEL', 'warning'),

        // Log slow requests
        'log_slow_requests' => env('DDOS_LOG_SLOW_REQUESTS', true),

        // Log bot detection events
        'log_bot_detection' => env('DDOS_LOG_BOT_DETECTION', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Whitelist Configuration
    |--------------------------------------------------------------------------
    |
    | IPs and patterns that should be excluded from DDoS protection.
    |
    */
    'whitelist' => [
        // IP addresses to exclude from DDoS protection
        'ips' => [
            '127.0.0.1',
            '::1',
            // Add your admin IPs here
        ],

        // CIDR blocks to whitelist
        'cidr_blocks' => [
            // '192.168.1.0/24', // Example: local network
        ],

        // User agents to whitelist (exact matches)
        'user_agents' => [
            // 'Pingdom.com_bot_version_1.4',
            // 'UptimeRobot/2.0',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Server-Level Protection
    |--------------------------------------------------------------------------
    |
    | Settings that correspond to server-level protections.
    |
    */
    'server' => [
        // Maximum request body size (bytes)
        'max_request_size' => env('DDOS_MAX_REQUEST_SIZE', 10485760), // 10MB

        // Request timeout (seconds)
        'request_timeout' => env('DDOS_REQUEST_TIMEOUT', 30),

        // Enable security headers
        'enable_security_headers' => env('DDOS_ENABLE_SECURITY_HEADERS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Testing Mode
    |--------------------------------------------------------------------------
    |
    | Enable testing mode to reduce thresholds for easier testing.
    |
    */
    'testing' => [
        // Enable testing mode (reduces thresholds for easier testing)
        'enabled' => env('DDOS_TESTING_MODE', false),

        // Testing mode multiplier (reduces thresholds by this factor)
        'threshold_multiplier' => env('DDOS_TESTING_MULTIPLIER', 0.1),
    ],

];
