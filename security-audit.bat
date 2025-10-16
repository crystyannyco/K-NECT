@echo off
REM Security Audit Script - Find Potential Issues
REM Run this before production deployment

echo ========================================
echo K-NECT Security Audit
echo ========================================
echo.

echo [1/5] Searching for timestamp disclosures...
echo.
findstr /S /I /C:"time()" /C:"strtotime" /C:"microtime" app\Views\*.php 2>nul
if errorlevel 1 (
    echo   ✓ No timestamp functions found in views
) else (
    echo   ⚠ Review timestamp usage above
)
echo.

echo [2/5] Searching for sensitive comments...
echo.
findstr /S /I /C:"TODO" /C:"FIXME" /C:"HACK" /C:"XXX" /C:"DEBUG" app\*.php 2>nul
if errorlevel 1 (
    echo   ✓ No development comments found
) else (
    echo   ⚠ Review and remove development comments above
)
echo.

echo [3/5] Searching for hardcoded credentials...
echo.
findstr /S /I /C:"password" /C:"api_key" /C:"secret" /C:"token" app\*.php 2>nul | findstr /I /C:"=" 2>nul
if errorlevel 1 (
    echo   ✓ No obvious hardcoded credentials
) else (
    echo   ⚠ Review potential credentials above
)
echo.

echo [4/5] Searching for console.log statements...
echo.
findstr /S /I /C:"console.log" /C:"console.debug" app\Views\*.php public\assets\js\*.js 2>nul
if errorlevel 1 (
    echo   ✓ No console logging found
) else (
    echo   ⚠ Remove console logs before production
)
echo.

echo [5/5] Checking for .env in version control...
echo.
git ls-files .env 2>nul
if errorlevel 1 (
    echo   ✓ .env not in version control
) else (
    echo   ⚠ WARNING: .env is tracked by git! Remove it:
    echo      git rm --cached .env
    echo      git commit -m "Remove .env from version control"
)
echo.

echo ========================================
echo Audit Complete
echo ========================================
echo.
echo Next Steps:
echo 1. Review any warnings above
echo 2. Fix issues before production deployment
echo 3. Run ZAP scan again after fixes
echo 4. Complete production checklist in ZAP_SECURITY_FIXES.md
echo.
pause
