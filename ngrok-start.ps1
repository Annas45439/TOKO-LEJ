# Script PowerShell untuk menjalankan Ngrok
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "  Toko-LEJ Remote Access via Ngrok" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Pastikan XAMPP Apache sudah RUNNING!" -ForegroundColor Yellow
Write-Host ""

$ngrokPath = Join-Path $PSScriptRoot "ngrok.exe"

if (-Not (Test-Path $ngrokPath)) {
    Write-Host "[ERROR] ngrok.exe tidak ditemukan di folder ini." -ForegroundColor Red
    Write-Host "Silakan download dan extract ngrok.zip terlebih dahulu." -ForegroundColor Red
    Read-Host "Tekan Enter untuk keluar"
    exit 1
}

Write-Host "Menjalankan Ngrok tunnel ke http://localhost/toko-lej/public" -ForegroundColor Green
Write-Host "URL publik akan muncul di bawah..." -ForegroundColor Green
Write-Host ""

& $ngrokPath http http://localhost/toko-lej/public

Read-Host "Tekan Enter untuk keluar"

