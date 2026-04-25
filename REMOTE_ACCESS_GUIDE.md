# Panduan Akses Jarak Jauh Toko-LEJ (Tanpa Hosting)

Panduan ini menjelaskan cara menampilkan website Toko-LEJ ke temanmu melalui internet, meskipun kalian menggunakan WiFi yang berbeda.

## Cara Kerja

Kita menggunakan **Ngrok**, yaitu tool yang membuat "tunnel" dari internet ke komputer lokalmu. Jadi temanmu bisa mengakses web yang berjalan di XAMPP-mu melalui URL publik.

> **Keuntungan:** Database sudah di Azure Cloud, jadi tidak masalah diakses dari mana pun.

---

## Langkah 1: Daftar Akun Ngrok (GRATIS)

1. Buka browser: https://dashboard.ngrok.com/signup
2. Daftar pakai email atau Google/GitHub
3. Setelah login, masuk ke menu **"Your Authtoken"**
4. Copy authtoken-mu (contoh: `2KjI...xYz`)

---

## Langkah 2: Setup Ngrok Pertama Kali

### A. Download & Extract

File `ngrok.zip` sudah didownload di folder ini. Lakukan:

1. **Extract** `ngrok.zip` di folder `c:\xampp\htdocs\toko-lej`
2. Pastikan ada file `ngrok.exe` di folder tersebut

### B. Konfigurasi Authtoken

Buka **Command Prompt (CMD)** atau **PowerShell** di folder `c:\xampp\htdocs\toko-lej`, lalu jalankan:

```bash
ngrok.exe config add-authtoken <TOKEN_KAMU>
```

Ganti `<TOKEN_KAMU>` dengan authtoken yang tadi dicopy.

Contoh:
```bash
ngrok.exe config add-authtoken 2KjIabcdef123456xYz
```

---

## Langkah 3: Jalankan Web & Ngrok

### Pastikan XAMPP Apache Sudah Nyala

1. Buka **XAMPP Control Panel**
2. Klik **Start** pada Apache
3. Pastikan Apache berjalan (warna hijau)

### Jalankan Ngrok

Cara 1 - Pakai File Batch (Mudah):
1. Klik 2x file **`ngrok-start.bat`**

Cara 2 - Pakai Command Prompt:
```bash
cd c:\xampp\htdocs\toko-lej
ngrok.exe http http://localhost/toko-lej/public
```

Cara 3 - Pakai PowerShell:
```powershell
cd c:\xampp\htdocs\toko-lej
.\ngrok.exe http http://localhost/toko-lej/public
```

---

## Langkah 4: Bagikan URL ke Teman

Setelah Ngrok jalan, akan muncul tampilan seperti ini:

```
Session Status                online
Account                       nama-kamu (Plan: Free)
Version                       3.x.x
Region                        Asia Pacific (ap)
Latency                       -
Web Interface                 http://127.0.0.1:4040
Forwarding                    https://abc123-def.ngrok-free.app -> http://localhost/toko-lej/public
```

**URL yang dibagikan ke teman adalah yang ini:**
```
https://abc123-def.ngrok-free.app
```

Temanmu tinggal buka URL tersebut di browser HP/komputernya.

---

## Catatan Penting

| Hal | Penjelasan |
|-----|-----------|
| **URL Berubah** | Di versi gratis, URL akan berubah setiap kali Ngrok ditutup. Setelah jalan ulang, perlu bagikan URL baru. |
| **Durasi** | Ngrok free berjalan terus selama tidak ditutup. Jika komputer sleep, koneksi terputus. |
| **Session Limit** | Free plan memiliki batas bandwidth, tapi untuk demo ke teman sudah lebih dari cukup. |
| **Kecepatan** | Tergantung kecepatan upload internet-mu. |
| **Firewall** | Pastikan Windows Firewall tidak memblok XAMPP/Apache. |

---

## Troubleshooting

### 1. Teman tidak bisa akses URL
- Pastikan XAMPP Apache masih nyala
- Pastikan Ngrok masih berjalan (jangan ditutup)
- Coba buka URL di browser komputermu sendiri dulu

### 2. Muncul error "Forbidden" atau "Not Found"
- Pastikan URL diakses sampai ke folder `public` (ada `/public` di belakang)
- Jika baseURL di CodeIgniter belum update, cek file `app/Config/App.php`, pastikan `$baseURL = '';`

### 3. CSS/JS tidak muncul
- Ini karena baseURL masih `localhost`. Sudah diperbaiki dengan mengosongkan `$baseURL`.
- Jika masih bermasalah, hard refresh browser temanmu: **Ctrl+Shift+R** (PC) atau clear cache (HP).

### 4. Database error
- Database sudah di Azure cloud, seharusnya aman.
- Pastikan koneksi internetmu stabil.

---

## Alternatif Lain (Jika Ngrok tidak berhasil)

| Tool | Cara Pakai |
|------|-----------|
| **Cloudflare Tunnel** | Install `cloudflared`, gratis dan lebih stabil |
| **LocalTunnel** | `npx localtunnel --port 80` (perlu Node.js) |
| **Tailscale** | Buat jaringan pribadi antar device, gratis |

---

## Ringkasan Perintah

```bash
# 1. Setup authtoken (sekali saja)
ngrok.exe config add-authtoken <TOKEN>

# 2. Jalankan tunnel
ngrok.exe http http://localhost/toko-lej/public

# 3. Copy URL Forwarding -> https://xxxx.ngrok-free.app
# 4. Bagikan ke teman!
```

Selamat mencoba! 🚀

