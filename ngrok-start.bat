@echo off
echo ==========================================
echo  Toko-LEJ Remote Access via Ngrok
echo ==========================================
echo.
echo Pastikan XAMPP Apache sudah RUNNING!
echo.

REM Check if ngrok.exe exists in current folder
if not exist "ngrok.exe" (
    echo [ERROR] ngrok.exe tidak ditemukan di folder ini.
    echo Silakan download dan extract ngrok.zip terlebih dahulu.
    pause
    exit /b 1
)

echo Menjalankan Ngrok tunnel ke http://localhost/toko-lej/public
echo URL publik akan muncul di bawah...
echo.

ngrok.exe http http://localhost/toko-lej/public

pause

