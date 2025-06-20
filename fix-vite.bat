@echo off
echo Fixing Vite asset loading issues...
echo.

echo [1/3] Removing stale hot file...
if exist "public\hot" (
    del "public\hot"
    echo Hot file removed successfully.
) else (
    echo No hot file found.
)

echo.
echo [2/3] Building assets for production...
npm run build

echo.
echo [3/3] Done! Your assets should now load correctly.
echo.
echo To start development server instead, run: npm run dev
pause
