@echo off
echo ======================================
echo K-NECT Application Setup Script
echo ======================================
echo.

REM Check if composer is installed
where composer >nul 2>nul
if %errorlevel% neq 0 (
    echo ERROR: Composer is not installed or not in PATH
    echo Please install Composer from https://getcomposer.org/
    pause
    exit /b 1
)

echo 1. Installing PHP dependencies...
composer install --no-dev --optimize-autoloader
if %errorlevel% neq 0 (
    echo ERROR: Failed to install dependencies
    pause
    exit /b 1
)

echo.
echo 2. Setting up environment configuration...
if not exist ".env" (
    if exist ".env.example" (
        copy ".env.example" ".env"
        echo Environment file created from template.
        echo IMPORTANT: Please edit .env file with your database and application settings!
    ) else (
        echo WARNING: .env.example file not found. You'll need to create .env manually.
    )
) else (
    echo Environment file already exists.
)

echo.
echo 3. Setting up writable permissions...
if exist "writable" (
    echo Writable directory permissions are being set...
    REM Windows doesn't need chmod, but we ensure the directories exist
    if not exist "writable\cache" mkdir "writable\cache"
    if not exist "writable\logs" mkdir "writable\logs"
    if not exist "writable\session" mkdir "writable\session"
    if not exist "writable\uploads" mkdir "writable\uploads"
    if not exist "writable\debugbar" mkdir "writable\debugbar"
)

echo.
echo ======================================
echo Setup Complete!
echo ======================================
echo.
echo Next steps:
echo 1. Edit .env file with your database settings
echo 2. Create your database and import DATABASE/k-nect.sql
echo 3. Configure your web server to point to the 'public' folder
echo 4. Access your application through your web server
echo.
echo For development, you can use PHP's built-in server:
echo php spark serve
echo.
pause