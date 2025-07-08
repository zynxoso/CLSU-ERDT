@echo off
echo ============================================
echo   CLSU-ERDT Asset Builder
echo ============================================
echo.

echo [1/4] Checking for existing build...
if exist "public\build\.vite\manifest.json" (
    echo ✓ Build manifest found
    echo.
    choice /c YN /m "Rebuild assets anyway? (Y/N)"
    if errorlevel 2 (
        echo Build skipped. Existing assets will be used.
        goto :end
    )
)

echo [2/4] Cleaning previous build...
if exist "public\build" (
    rmdir /s /q "public\build" 2>nul
    echo ✓ Previous build cleaned
) else (
    echo ✓ No previous build found
)

echo.
echo [3/4] Installing dependencies...
call npm install
if errorlevel 1 (
    echo ✗ Failed to install dependencies
    pause
    exit /b 1
)
echo ✓ Dependencies installed

echo.
echo [4/4] Building production assets...
call npm run build
if errorlevel 1 (
    echo ✗ Build failed
    pause
    exit /b 1
)

echo.
echo ============================================
echo ✓ Build completed successfully!
echo ✓ Manifest file created at: public\build\.vite\manifest.json
echo ✓ Your system is now ready to use without 'npm run dev'
echo ============================================

:end
echo.
pause
