# K-NECT Application

A CodeIgniter 4-based web application for event management and community organization.

## What is CodeIgniter?

CodeIgniter is a PHP full-stack web framework that is light, fast, flexible and secure.
More information can be found at the [official site](https://codeigniter.com).

This repository holds a composer-installable app starter.
It has been built from the
[development repository](https://github.com/codeigniter4/CodeIgniter4).

You can read the [user guide](https://codeigniter.com/user_guide/)
corresponding to the latest version of the framework.

## Quick Setup for New Devices

### Prerequisites

- PHP 8.1 or higher
- Composer (latest version)
- Web server (Apache/Nginx)
- MySQL/MariaDB database

### Installation Steps

#### Option 1: Automated Setup (Recommended)

**For Windows:**

```cmd
setup.bat
```

**For Linux/Mac:**

```bash
chmod +x setup.sh
./setup.sh
```

#### Option 2: Manual Setup

1. **Clone the repository:**

   ```bash
   git clone <your-repository-url>
   cd K-NECT
   ```

2. **Install PHP dependencies:**

   ```bash
   composer install
   ```

3. **Set up environment configuration:**

   ```bash
   # Copy the environment template
   cp .env.example .env

   # Edit .env file with your database and application settings
   ```

4. **Configure your web server:**

   - Point your web server document root to the `public/` folder
   - Ensure URL rewriting is enabled

5. **Set up the database:**

   - Create a new database
   - Import the database schema from `DATABASE/k-nect.sql`
   - Update database credentials in `.env` file

6. **Set file permissions (Linux/Mac only):**
   ```bash
   # Make writable directories writable
   chmod -R 755 writable/
   ```

### Development Server

For development purposes, you can use CodeIgniter's built-in server:

```bash
php spark serve
```

This will start a development server at `http://localhost:8080`

## Dependencies Included

This project includes the following main dependencies:

- **CodeIgniter 4 Framework** - Core framework
- **DomPDF** - PDF generation
- **PhpSpreadsheet** - Excel file handling
- **PhpWord** - Word document generation
- **Google API Client** - Google Calendar integration
- **Guzzle HTTP** - HTTP client library

## Development Dependencies

For development and testing:

- **PHPUnit** - Testing framework
- **Faker** - Test data generation
- **VfsStream** - Virtual file system for testing

## Important Change with index.php

`index.php` is no longer in the root of the project! It has been moved inside the _public_ folder,
for better security and separation of components.

This means that you should configure your web server to "point" to your project's _public_ folder, and
not to the project root. A better practice would be to configure a virtual host to point there. A poor practice would be to point your web server to the project root and expect to enter _public/..._, as the rest of your logic and the
framework are exposed.

**Please** read the user guide for a better explanation of how CI4 works!

## Repository Management

We use GitHub issues, in our main repository, to track **BUGS** and to track approved **DEVELOPMENT** work packages.
We use our [forum](http://forum.codeigniter.com) to provide SUPPORT and to discuss
FEATURE REQUESTS.

This repository is a "distribution" one, built by our release preparation script.
Problems with it can be raised on our forum, or as issues in the main repository.

## Server Requirements

PHP version 8.1 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

> [!WARNING]
>
> - The end of life date for PHP 7.4 was November 28, 2022.
> - The end of life date for PHP 8.0 was November 26, 2023.
> - If you are still using PHP 7.4 or 8.0, you should upgrade immediately.
> - The end of life date for PHP 8.1 will be December 31, 2025.

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) if you plan to use MySQL
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library
