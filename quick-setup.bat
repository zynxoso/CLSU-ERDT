@echo off
echo Checking CLSU-ERDT system setup...

REM Check if manifest.json exists
if exist "public\build\.vite\manifest.json" (
    echo ✓ System ready - manifest.json found
    echo ✓ You can start your system without running 'npm run dev'
    goto :end
)

echo ! Missing manifest.json - building assets...
echo.

REM Build assets
call npm run build
if errorlevel 1 (
    echo ✗ Build failed - you may need to run 'npm install' first
    pause
    exit /b 1
)

echo.
echo ✓ Assets built successfully!
echo ✓ System is now ready to use

:end
echo.
echo You can now start your Laravel server with: php artisan serve
echo.
