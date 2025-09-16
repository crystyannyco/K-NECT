#!/bin/bash

echo "======================================"
echo "K-NECT Application Setup Script"
echo "======================================"
echo

# Check if composer is installed
if ! command -v composer &> /dev/null; then
    echo "ERROR: Composer is not installed or not in PATH"
    echo "Please install Composer from https://getcomposer.org/"
    exit 1
fi

echo "1. Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader
if [ $? -ne 0 ]; then
    echo "ERROR: Failed to install dependencies"
    exit 1
fi

echo
echo "2. Setting up environment configuration..."
if [ ! -f ".env" ]; then
    if [ -f ".env.example" ]; then
        cp ".env.example" ".env"
        echo "Environment file created from template."
        echo "IMPORTANT: Please edit .env file with your database and application settings!"
    else
        echo "WARNING: .env.example file not found. You'll need to create .env manually."
    fi
else
    echo "Environment file already exists."
fi

echo
echo "3. Setting up writable permissions..."
if [ -d "writable" ]; then
    echo "Setting writable directory permissions..."
    chmod -R 755 writable/
    
    # Ensure required directories exist
    mkdir -p writable/cache writable/logs writable/session writable/uploads writable/debugbar
fi

echo
echo "======================================"
echo "Setup Complete!"
echo "======================================"
echo
echo "Next steps:"
echo "1. Edit .env file with your database settings"
echo "2. Create your database and import DATABASE/k-nect.sql"
echo "3. Configure your web server to point to the 'public' folder"
echo "4. Access your application through your web server"
echo
echo "For development, you can use PHP's built-in server:"
echo "php spark serve"
echo