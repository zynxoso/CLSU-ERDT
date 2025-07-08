# CLSU-ERDT System Deployment Analysis for Hostinger

This document provides a detailed analysis of potential issues that may arise when deploying the CLSU-ERDT system to a shared hosting environment like Hostinger. The analysis identifies specific components and code that could pose problems, along with their impacts and recommendations for mitigation.

## 1. Redis Dependency for Caching and Possibly Queues

- **Issue**: The default cache store is set to Redis in `config/cache.php`. Additionally, Redis is configured in `config/database.php` for potential use in other capacities. Hostinger's shared hosting plans often do not include Redis support by default, which could lead to errors if the application relies on Redis for caching, session management, or queue processing.
- **Impact**: Without Redis, the application may experience performance degradation or functional failures in features dependent on caching or background processing.
- **Recommendation**: Modify the cache store to use a supported driver on Hostinger, such as 'file' or 'database', by updating the `CACHE_STORE` environment variable or directly in `config/cache.php`. For queues, consider using a database driver if background processing is required, as Hostinger may not support long-running processes or queue workers.

## 2. Role-Based Database Connections

- **Issue**: The `config/database.php` file defines multiple MySQL connections for different roles (super_admin, admin, scholar) with distinct usernames. Hostinger typically provides a single database user per database in shared hosting, making this setup incompatible unless multiple users are created, which may be restricted.
- **Impact**: The application may fail to connect to the database for certain user roles, leading to authentication or data access issues.
- **Recommendation**: Consolidate database connections to use a single user with appropriate permissions. Update the environment variables (e.g., `DB_USERNAME`, `DB_PASSWORD`) to reflect this single user setup, and adjust the application logic if necessary to handle permissions within the code rather than through separate database users.

## 3. Default SQLite Configuration

- **Issue**: The default database connection in `config/database.php` is set to SQLite, which is likely used for local development. SQLite uses a file-based database, which can lead to performance issues and file permission problems in a shared hosting environment like Hostinger.
- **Impact**: Using SQLite on Hostinger could result in slow database operations or access issues due to file locks or permission restrictions.
- **Recommendation**: Switch the default connection to MySQL, which is widely supported on Hostinger. Update the `DB_CONNECTION` environment variable to 'mysql' and ensure the database credentials are correctly set for the Hostinger environment.

## 4. Hardcoded Local Development References

- **Issue**: Numerous references to 'localhost' and '127.0.0.1' were found across the codebase, including in `vite.config.js`, configuration files, and log files (e.g., `storage/logs/admin_middleware.log` showing requests to `http://127.0.0.1:8000`). These indicate assumptions of a local development environment.
- **Impact**: These hardcoded values may cause connectivity issues or incorrect URL resolutions when the application is deployed to a different domain or IP on Hostinger.
- **Recommendation**: Replace hardcoded references with environment variables or dynamically generated URLs. For instance, in `vite.config.js`, set the host dynamically based on an environment variable. Ensure that the `APP_URL` and `FRONTEND_URL` in the environment configuration are set to the production domain on Hostinger. Review and clean up log files or ensure they do not influence runtime behavior.

## 5. File and Directory Permissions for Storage and Cache

- **Issue**: Laravel applications require write permissions for directories like `storage/` and `bootstrap/cache/` for logging, caching, and session storage. Hostinger's shared hosting may have stricter permission settings or different user ownership, potentially causing access issues.
- **Impact**: Without proper permissions, the application may fail to write logs, store sessions, or cache data, leading to runtime errors or degraded performance.
- **Recommendation**: After deployment, ensure that the necessary directories have the correct permissions (typically 755 or 775 for directories and 644 or 664 for files, depending on Hostinger's setup). Use Hostinger's file manager or SSH access (if available) to set permissions. Additionally, consider using a database for sessions if file-based sessions cause issues.

## 6. Background Processes and Queue Management

- **Issue**: If the application uses Laravel's queue system for background tasks (potentially with Redis or another driver), Hostinger's shared hosting may not support long-running processes or cron jobs to run queue workers due to resource limitations.
- **Impact**: Background tasks such as sending emails or processing uploads may not execute, leading to delayed or failed operations.
- **Recommendation**: Use Hostinger's cron job feature to run the Laravel queue worker at regular intervals (e.g., every minute) with a command like `php artisan queue:work --stop-when-empty`. If cron jobs are limited, consider offloading tasks to an external service or switching to synchronous processing for critical tasks, though this may impact user experience.

## 7. PHP Version and Extensions Compatibility

- **Issue**: The project requires PHP 8.2 as per `composer.json`. While Hostinger supports recent PHP versions, certain extensions required by packages (e.g., for Redis, if used, or specific database drivers) might not be enabled by default on shared hosting plans.
- **Impact**: Missing extensions or version mismatches could prevent the application or specific features from functioning correctly.
- **Recommendation**: Verify Hostinger's PHP version and available extensions in the control panel. Select PHP 8.2 if available, and enable necessary extensions like `pdo_mysql` for database connections. If certain extensions are unavailable, consider alternative packages or configurations that do not require them.

## 8. Performance Constraints in Shared Hosting

- **Issue**: Hostinger's shared hosting environments have limited resources (CPU, memory, I/O) compared to local development or dedicated servers. The CLSU-ERDT system, with features like PDF generation (via Barryvdh's DomPDF) and spreadsheet processing (via PhpOffice's PhpSpreadsheet), may consume significant resources.
- **Impact**: High resource usage could lead to throttling, timeouts, or account suspension on Hostinger if usage exceeds shared hosting limits.
- **Recommendation**: Optimize resource-intensive operations by caching results where possible, reducing the frequency of heavy tasks, or offloading them to external services if feasible. Monitor performance post-deployment and consider upgrading to a higher plan or VPS if necessary.

## Conclusion

This analysis highlights critical areas of the CLSU-ERDT system that require attention before deployment to Hostinger. Addressing these issues through configuration adjustments, environment variable settings, and potential code modifications will help ensure a smoother transition to the shared hosting environment.
