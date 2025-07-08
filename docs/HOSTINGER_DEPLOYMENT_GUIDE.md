# CLSU-ERDT Deployment Guide for Hostinger Business Plan

## Prerequisites

- Hostinger Business Plan account
- Git installed on your local machine
- Composer installed on your local machine
- Node.js and npm installed on your local machine
- Access to Hostinger control panel
- Domain configured in Hostinger (if using custom domain)

## Step 1: Prepare Your Local Project

1. Build production assets:
   ```bash
   npm run build
   ```

2. Create production-ready `.env`:
   ```bash
   cp .env .env.production
   ```
   Edit `.env.production`:
   - Set `APP_ENV=production`
   - Set `APP_DEBUG=false`
   - Update `APP_URL` to your domain
   - Remove any development-specific settings

3. Generate production app key:
   ```bash
   php artisan key:generate --env=production
   ```

## Step 2: Hostinger Setup

1. Log in to Hostinger Control Panel
2. Create a new MySQL database:
   - Go to "Databases" → "MySQL Databases"
   - Create new database (e.g., `clsu_erdt`)
   - Create database user
   - Assign user to database with all privileges
   - Save database credentials for later

3. Enable SSH Access (if needed):
   - Go to "Advanced" → "SSH Access"
   - Generate or upload SSH key
   - Save SSH credentials

## Step 3: Upload Project Files

### Option 1: Using File Manager

1. Compress your project:
   ```bash
   # Exclude unnecessary files
   zip -r clsu-erdt.zip . -x "node_modules/*" "vendor/*" ".git/*" "storage/logs/*" "storage/framework/cache/*"
   ```

2. Upload via File Manager:
   - Go to Hostinger File Manager
   - Navigate to your domain's public_html folder
   - Upload and extract clsu-erdt.zip
   - Set proper permissions:
     ```bash
     chmod -R 755 public_html
     chmod -R 777 storage
     chmod -R 777 bootstrap/cache
     ```

### Option 2: Using Git (Recommended)

1. Add Hostinger as remote:
   ```bash
   git remote add production ssh://u123456789@153.92.xxx.xxx/home/u123456789/public_html
   ```

2. Push to production:
   ```bash
   git push production main
   ```

## Step 4: Configure Environment

1. Update `.env` file on Hostinger:
   ```
   APP_NAME="CLSU ERDT"
   APP_ENV=production
   APP_KEY=your-generated-key
   APP_DEBUG=false
   APP_URL=https://your-domain.com

   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password

   MAIL_MAILER=smtp
   MAIL_HOST=smtp.hostinger.com
   MAIL_PORT=465
   MAIL_USERNAME=your-email@your-domain.com
   MAIL_PASSWORD=your-email-password
   MAIL_ENCRYPTION=ssl
   MAIL_FROM_ADDRESS=your-email@your-domain.com
   MAIL_FROM_NAME="${APP_NAME}"
   ```

2. Configure PHP settings:
   - Go to Hostinger PHP Configuration
   - Set PHP version to 8.1 or higher
   - Increase memory_limit to 256M
   - Increase upload_max_filesize to 64M
   - Increase post_max_size to 64M
   - Enable required PHP extensions:
     - BCMath
     - Ctype
     - JSON
     - Mbstring
     - OpenSSL
     - PDO
     - Tokenizer
     - XML
     - Fileinfo

## Step 5: Install Dependencies

Connect via SSH and run:
```bash
cd public_html
composer install --no-dev --optimize-autoloader
```

## Step 6: Database Setup

1. Run migrations:
   ```bash
   php artisan migrate --force
   ```

2. Seed database:
   ```bash
   php artisan db:seed --force
   ```

## Step 7: Configure Web Server

1. Update public directory:
   - Move contents of `public` to `public_html`
   - Update `index.php` paths if needed

2. Configure URL Rewriting:
   Create/update `.htaccess` in public_html:
   ```apache
   <IfModule mod_rewrite.c>
       RewriteEngine On
       RewriteBase /
       RewriteRule ^index\.php$ - [L]
       RewriteCond %{REQUEST_FILENAME} !-f
       RewriteCond %{REQUEST_FILENAME} !-d
       RewriteRule . /index.php [L]
   </IfModule>
   ```

## Step 8: Cache Configuration

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Step 9: Set Up SSL

1. Go to Hostinger SSL section
2. Enable Let's Encrypt SSL
3. Force HTTPS in `.htaccess`:
   ```apache
   RewriteEngine On
   RewriteCond %{HTTPS} off
   RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
   ```

## Step 10: Final Checks

1. Test the application:
   - Visit your domain
   - Test login functionality
   - Check file uploads
   - Verify email sending
   - Test database operations

2. Monitor for errors:
   - Check storage/logs/laravel.log
   - Monitor server error logs
   - Set up error reporting

## Troubleshooting

### Common Issues

1. **500 Server Error**
   - Check storage permissions
   - Verify .env configuration
   - Check PHP version compatibility
   - Review error logs

2. **Database Connection Issues**
   - Verify database credentials
   - Check database host configuration
   - Ensure proper privileges

3. **File Upload Issues**
   - Check directory permissions
   - Verify PHP upload limits
   - Check disk space

### Performance Optimization

1. Enable OPcache in PHP settings
2. Configure browser caching in .htaccess
3. Enable Gzip compression
4. Use CDN for assets if needed

## Maintenance

1. Regular backups:
   ```bash
   # Database backup
   php artisan backup:run
   ```

2. Update dependencies:
   ```bash
   composer update --no-dev
   npm update
   ```

3. Monitor disk space and database size

4. Regular security updates:
   ```bash
   composer audit
   npm audit
   ```

## Security Considerations

1. Keep sensitive files outside public_html
2. Use strong passwords
3. Regular security audits
4. Keep all software updated
5. Monitor access logs
6. Configure firewall rules

## Support

For hosting-specific issues, contact Hostinger support:
- Support portal: https://www.hostinger.com/support
- 24/7 Live chat
- Knowledge base 
