@echo off
REM Tailwind CSS Development Watcher
REM Run this script while developing to auto-rebuild CSS on changes

echo ========================================
echo Tailwind CSS Development Mode
echo ========================================
echo.
echo Watching for changes in:
echo   - app/Views/**/*.php
echo.
echo Press Ctrl+C to stop watching
echo ========================================
echo.

npm run dev
