@echo off
echo ======================================
echo K-NECT Firebase Functions Deploy
echo ======================================

where firebase >nul 2>nul
if %errorlevel% neq 0 (
  echo ERROR: Firebase CLI not found. Install with: npm install -g firebase-tools
  pause
  exit /b 1
)

echo Logging into Firebase (if needed)...
firebase login
if %errorlevel% neq 0 (
  echo ERROR: Firebase login failed.
  pause
  exit /b 1
)

echo Deploying only functions...
cd /d %~dp0
firebase deploy --only functions
if %errorlevel% neq 0 (
  echo ERROR: Firebase functions deployment failed.
  pause
  exit /b 1
)

echo Deployment complete.
pause
