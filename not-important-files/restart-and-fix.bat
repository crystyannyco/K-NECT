@echo off
echo =========================================================
echo   RESTARTING K-NECT SERVER WITH FIXED CONFIGURATION
echo =========================================================
echo.
echo Stopping any running PHP processes...
taskkill /F /IM php.exe 2>nul
timeout /t 2 /nobreak >nul
echo.
echo Clearing cache...
if exist writable\cache\*.* del /Q writable\cache\*.* 2>nul
if exist writable\session\ci_session* del /Q writable\session\ci_session* 2>nul
echo.
echo Starting server with FIXED CSRF configuration...
echo   - tokenRandomize = false (CI 4.6.3 bug workaround)
echo   - regenerate     = false (multi-step form fix)
echo   - redirect       = false (show actual errors)
echo.
echo =========================================================
echo Server will start in 2 seconds...
echo After it starts, press Ctrl+F5 in your browser to reload
echo =========================================================
timeout /t 2 /nobreak >nul
echo.
php spark serve
