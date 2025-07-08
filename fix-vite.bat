@echo off
echo CLSU-ERDT Vite Server Manager
echo ============================

REM Kill any existing Node.js processes
echo Step 1: Cleaning up existing Node.js processes...
taskkill /F /IM node.exe >nul 2>&1
if %ERRORLEVEL% EQU 0 (
    echo [SUCCESS] Cleaned up existing Node.js processes
) else (
    echo [INFO] No existing Node.js processes found
)

REM Clear NPM cache
echo Step 2: Clearing NPM cache...
call npm cache clean --force
echo [SUCCESS] NPM cache cleared

REM Delete node_modules and package-lock.json
echo Step 3: Removing node_modules and package-lock.json...
if exist node_modules\ (
    rmdir /s /q node_modules
    echo [SUCCESS] Removed node_modules directory
)
if exist package-lock.json (
    del /f package-lock.json
    echo [SUCCESS] Removed package-lock.json
)

REM Clear Vite cache
echo Step 4: Clearing Vite cache...
if exist "node_modules/.vite" (
    rmdir /s /q "node_modules\.vite"
    echo [SUCCESS] Cleared Vite cache
)

REM Install dependencies
echo Step 5: Installing dependencies...
call npm install
if %ERRORLEVEL% EQU 0 (
    echo [SUCCESS] Dependencies installed successfully
) else (
    echo [ERROR] Failed to install dependencies
    exit /b 1
)

REM Start Vite server
echo Step 6: Starting Vite server...
start "CLSU-ERDT Vite Server" cmd /c "npm run dev"
echo [SUCCESS] Vite server started in a new window

echo.
echo ============================
echo Vite server setup complete!
echo The Vite server is running in a separate window.
echo To stop the server, close that window or press Ctrl+C in it.
echo ============================
