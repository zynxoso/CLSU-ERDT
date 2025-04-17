# Hosting on Laravel Cloud: Step-by-Step Guide

## Introduction
Laravel Cloud is the official platform-as-a-service offering from Laravel, providing a seamless deployment experience for Laravel applications. This guide walks through the process of hosting your Laravel application on Laravel Cloud.

## Prerequisites
- A Laravel application ready for deployment
- Git repository (GitHub, GitLab, or Bitbucket)
- Composer installed globally on your local machine

## Steps

### 1. Sign up for Laravel Cloud
- Visit [Laravel Cloud](https://laravel.com/cloud)
- Create an account or sign in with your existing Laravel account
- Select a subscription plan that fits your needs

### 2. Install Laravel Forge CLI
The Forge CLI provides command-line tools for managing your Laravel Cloud deployments:

```bash
composer global require laravel/forge-cli
```

### 3. Authenticate with Forge CLI
Login to authenticate your CLI with your Laravel Cloud account:

```bash
forge login
```

### 4. Connect Your Git Provider
- Navigate to the Laravel Cloud dashboard
- Go to "Settings" > "Source Control"
- Connect your GitHub, GitLab, or Bitbucket account
- Grant the necessary permissions to allow Laravel Cloud to access your repositories

### 5. Create a New Laravel Cloud Site
Create a new site using the Forge CLI:

```bash
forge cloud:create
```

Follow the interactive prompts to:
- Select your repository
- Choose the branch to deploy
- Configure your site name
- Set PHP version and other options

### 6. Configure Environment Variables
- In the Laravel Cloud dashboard, navigate to your site
- Find the "Environment" section
- Add all necessary environment variables (.env values)
- Don't forget to set:
  - APP_KEY
  - Database credentials
  - Mail settings
  - Any other service keys your application requires

### 7. Push Your Code
Ensure your code is pushed to the connected Git repository:

```bash
git add .
git commit -m "Ready for Laravel Cloud deployment"
git push origin main
```

### 8. Deploy Your Application
Deploy using the Laravel Cloud dashboard or via CLI:

```bash
forge cloud:deploy your-site-name
```

The deployment process will:
- Clone your repository
- Install dependencies
- Build assets
- Run migrations (if configured)
- Set up the web server

### 9. Configure Custom Domain
- In the Laravel Cloud dashboard, go to your site's "Domains" section
- Add your custom domain (e.g., yourdomain.com)
- Update your domain's DNS settings to point to Laravel Cloud:
  - Add an A record pointing to your Laravel Cloud IP
  - Or use CNAME if provided by Laravel Cloud

### 10. Set Up SSL Certificate
- In the "SSL" section of your site dashboard
- Click "Add Certificate"
- Choose Let's Encrypt for free automatic SSL
- Or upload your own custom certificate

### 11. Monitor Your Application
- Use the "Metrics" section to monitor application performance
- Set up notification preferences for deployment events and server issues
- Review logs in the "Logs" section

### 12. Scale Your Application (Optional)
- Adjust resources as needed in the "Resources" section
- Configure auto-scaling based on traffic patterns
- Add database replicas if needed

## Project-Specific Deployment: CLSU-ERDT Scholar Management System

### Project Overview
The CLSU-ERDT Scholar Management System is a comprehensive web application that manages scholar documents and fund disbursements. It includes multiple modules for authentication, scholar management, fund requests, document management, manuscript submissions, and reporting.

### Pre-deployment Checklist
Before deploying to Laravel Cloud, ensure:

1. All dependencies are properly listed in `composer.json` and `package.json`
2. MySQL 8.x compatibility is confirmed (Laravel Cloud supports MySQL 8)
3. Redis is properly configured in your application for caching and queues
4. File storage is configured to use Laravel Cloud's storage

### Database Migration Strategy
Run the following during deployment to set up the database:

```bash
php artisan migrate --seed
```

This will create and populate these essential tables:
- users
- scholar_profiles
- fund_requests
- request_types
- documents
- disbursements
- manuscripts
- review_comments
- audit_logs
- notifications

### Environment Configuration
Configure these specific environment variables for the CLSU-ERDT system:

```
APP_NAME="CLSU-ERDT Scholar Management"
APP_URL=https://your-cloud-domain.com

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=clsu_erdt
DB_USERNAME=[provided by Laravel Cloud]
DB_PASSWORD=[provided by Laravel Cloud]

SESSION_DRIVER=database
QUEUE_CONNECTION=redis
CACHE_STORE=redis

REDIS_CLIENT=phpredis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=[your mail host]
MAIL_PORT=587
MAIL_USERNAME=[your mail username]
MAIL_PASSWORD=[your mail password]
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="erdt@clsu.edu.ph"
MAIL_FROM_NAME="${APP_NAME}"
```

### Storage Configuration
Set up storage links for document uploads:

```bash
php artisan storage:link
```

Configure Laravel Cloud Storage for scholar documents and manuscripts:

```php
// config/filesystems.php
'disks' => [
    // ...
    'documents' => [
        'driver' => 's3',
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION'),
        'bucket' => env('AWS_BUCKET'),
        'url' => env('AWS_URL'),
        'endpoint' => env('AWS_ENDPOINT'),
        'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
        'throw' => false,
    ],
]
```

### Scheduled Tasks
Configure Laravel Cloud to run the scheduler for reporting and notifications:

```bash
* * * * * cd /home/forge/your-site && php artisan schedule:run >> /dev/null 2>&1
```

The CLSU-ERDT system uses these scheduled tasks:
- Daily database backups
- Weekly scholar status reports
- Monthly fund disbursement summaries

### Post-Deployment Verification
After deployment, verify:

1. Authentication system works for both admin and scholar roles
2. Document uploads function correctly
3. Fund request workflow processes requests properly
4. Reporting module generates expected reports
5. Email notifications are being sent

### Scaling Considerations
For the CLSU-ERDT system, consider:

- Increasing database resources during peak registration periods
- Adding more workers for queue processing during fund disbursement cycles
- Setting up caching for manuscript access

## Useful Commands

```bash
# List all your sites
forge cloud:list

# View site details
forge cloud:show your-site-name

# Restart PHP process
forge cloud:php-restart your-site-name

# View logs
forge cloud:logs your-site-name
```

## Troubleshooting

If you encounter issues during deployment:
1. Check deployment logs in the Laravel Cloud dashboard
2. Verify your environment variables are set correctly
3. Ensure your application runs locally without errors
4. Check that your database connections are configured properly
5. Verify your Git repository has the latest code

## Resources
- [Laravel Cloud Documentation](https://laravel.com/docs/cloud)
- [Laravel Forge CLI Documentation](https://forge.laravel.com/docs/1.0/cli.html)
- [Laravel Deployment Best Practices](https://laravel.com/docs/deployment) 
