# K-NECT Deployment Guide

This guide will help you deploy the K-NECT application on any new device or server.

## Prerequisites

Before you begin, ensure you have the following installed:

- **PHP 8.1 or higher** with the following extensions:

  - intl
  - mbstring
  - json
  - mysqlnd (for MySQL)
  - libcurl
  - zip
  - gd (for image processing)

- **Composer** (latest version) - [Download here](https://getcomposer.org/)
- **Web Server** (Apache, Nginx, or use PHP's built-in server for development)
- **MySQL or MariaDB** database server

## Quick Deployment

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/K-NECT.git
cd K-NECT
```

### 2. Run Setup Script

**For Windows:**

```cmd
setup.bat
```

**For Linux/Mac:**

```bash
chmod +x setup.sh
./setup.sh
```

### 3. Configure Environment

Edit the `.env` file with your specific settings:

```bash
# Required settings
CI_ENVIRONMENT = production  # Change to production for live sites
app.baseURL = 'https://yourdomain.com/'  # Your actual domain

# Database settings
database.default.hostname = your-db-host
database.default.database = your-db-name
database.default.username = your-db-user
database.default.password = your-db-password

# Generate a secure encryption key (32 characters)
encryption.key = your-32-character-secret-key-here
```

### 4. Set Up Database

1. Create a new MySQL database
2. Import the schema:
   ```bash
   mysql -u your-username -p your-database-name < DATABASE/k-nect.sql
   ```

### 5. Configure Web Server

**Apache (.htaccess is already included):**

- Point your virtual host document root to `/path/to/K-NECT/public`
- Ensure `mod_rewrite` is enabled

**Nginx:**

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/K-NECT/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

**For Development:**

```bash
php spark serve
```

## Manual Deployment (Step by Step)

If you prefer manual setup or the script doesn't work:

### 1. Install Dependencies

```bash
composer install --no-dev --optimize-autoloader
```

### 2. Environment Setup

```bash
cp .env.example .env
# Edit .env file with your settings
```

### 3. Directory Permissions (Linux/Mac)

```bash
chmod -R 755 writable/
chown -R www-data:www-data writable/  # For Apache
# or
chown -R nginx:nginx writable/        # For Nginx
```

### 4. Database Migration

```bash
# Import the database schema
mysql -u username -p database_name < DATABASE/k-nect.sql
```

## Production Deployment Checklist

- [ ] Set `CI_ENVIRONMENT = production` in `.env`
- [ ] Configure proper `app.baseURL` in `.env`
- [ ] Set secure `encryption.key` in `.env`
- [ ] Configure database credentials in `.env`
- [ ] Set proper file permissions on `writable/` directory
- [ ] Configure web server to point to `public/` folder
- [ ] Enable HTTPS (recommended)
- [ ] Set up regular database backups
- [ ] Configure error logging
- [ ] Test all application features

## Updating Dependencies

To update dependencies on an existing installation:

```bash
composer update
```

## Troubleshooting

### Common Issues

1. **Composer install fails:**

   - Ensure PHP and required extensions are installed
   - Check if you have enough memory: `php -d memory_limit=512M /path/to/composer install`

2. **Permission errors:**

   - Ensure `writable/` directory is writable by web server
   - Check file ownership and permissions

3. **Database connection fails:**

   - Verify database credentials in `.env`
   - Ensure database server is running
   - Check if database exists

4. **404 errors:**
   - Ensure web server document root points to `public/` folder
   - Check if URL rewriting is enabled

### Getting Help

- Check the application logs in `writable/logs/`
- Review web server error logs
- Ensure all dependencies are properly installed with `composer validate`

## Security Notes

- Never commit `.env` file to version control
- Always use HTTPS in production
- Keep dependencies updated
- Regular security audits with `composer audit`
- Set proper file permissions
- Use strong database passwords
- Keep the `app/` directory outside the web root (only `public/` should be accessible)

## File Structure After Deployment

```
K-NECT/
├── app/                 # Application source code
├── public/             # Web server document root (ONLY this should be web-accessible)
├── writable/           # Cache, logs, sessions (must be writable)
├── vendor/             # Composer dependencies (auto-generated)
├── .env                # Environment configuration (not in git)
├── composer.json       # Dependency definitions
└── composer.lock       # Locked dependency versions
```

This structure ensures proper security and functionality across all environments.
