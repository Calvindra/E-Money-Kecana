# Dokumentasi API Kecana

Dokumentasi ini menjelaskan cara mengakses layanan API Kecana. Pengembangan API dimaksudkan untuk penyediaan akses integrasi data uang elektronik dan layanan dari Kecana.

1. Calvindra Laksmono Kumoro (5027201020)
2. Damarhafni Rahmannabel Nadim P (5027201026)
3. Kevin Oktoaria (5027201046)

| Data     | Deskripsi  |
| ------------- | ------------- |
| `Register`        | API untuk membuat user baru pada Website Kecana.|
| `Login`         | API untuk user agar dapat login dan mengakses berbagai fitur pada Website Kecana.|
| `Me`         | API untuk mendapatkan data user yang sedang login.|
| `Topup`         | API untuk mengisi saldo uang elektronik sebagai alat pembayaran transaksi pada Website Kecana.|
| `Transfer`         | API untuk memindahkan saldo uang elektronik dari satu user ke user lain pada Website Kecana.|

# Daftar Isi
* [Daftar Isi](https://github.com/2022-IT-Pemrograman-Integratif/e-money-kelompok-3#daftar-isi)
* [Register](https://github.com/2022-IT-Pemrograman-Integratif/e-money-kelompok-3#register)
  - [Autentikasi Register](https://github.com/2022-IT-Pemrograman-Integratif/e-money-kelompok-3#autentikasi-register)
  - [Parameter Register](https://github.com/2022-IT-Pemrograman-Integratif/e-money-kelompok-3#parameter-register)
  - [Contoh Register](https://github.com/2022-IT-Pemrograman-Integratif/e-money-kelompok-3#contoh-register)
  - [Response Register](https://github.com/2022-IT-Pemrograman-Integratif/e-money-kelompok-3#response-register)
* [Login](https://github.com/2022-IT-Pemrograman-Integratif/e-money-kelompok-3#login)
  - [Autentikasi Login](https://github.com/2022-IT-Pemrograman-Integratif/e-money-kelompok-3#autentikasi-login)
  - [Parameter Login](https://github.com/2022-IT-Pemrograman-Integratif/e-money-kelompok-3#parameter-login)
  - [Contoh Login](https://github.com/2022-IT-Pemrograman-Integratif/e-money-kelompok-3#contoh-login)
  - [Response Login](https://github.com/2022-IT-Pemrograman-Integratif/e-money-kelompok-3#response-login)
* [Me](https://github.com/2022-IT-Pemrograman-Integratif/e-money-kelompok-3#me-get-user)
  - [Autentikasi Me](https://github.com/2022-IT-Pemrograman-Integratif/e-money-kelompok-3#autentikasi-me-get-user)
  - [Parameter Me](https://github.com/2022-IT-Pemrograman-Integratif/e-money-kelompok-3#parameter-me-get-user)
  - [Contoh Me](https://github.com/2022-IT-Pemrograman-Integratif/e-money-kelompok-3#contoh-me-get-user)
  - [Response Me](https://github.com/2022-IT-Pemrograman-Integratif/e-money-kelompok-3#response-me-get-user)
* [Topup](https://github.com/2022-IT-Pemrograman-Integratif/e-money-kelompok-3#topup)
  - [Autentikasi Topup](https://github.com/2022-IT-Pemrograman-Integratif/e-money-kelompok-3#autentikasi-topup)
  - [Parameter Topup](https://github.com/2022-IT-Pemrograman-Integratif/e-money-kelompok-3#parameter-topup)
  - [Contoh Topup](https://github.com/2022-IT-Pemrograman-Integratif/e-money-kelompok-3#contoh-topup)
  - [Response Topup](https://github.com/2022-IT-Pemrograman-Integratif/e-money-kelompok-3#response-topup)
* [Transfer](https://github.com/2022-IT-Pemrograman-Integratif/e-money-kelompok-3#transfer)
  - [Autentikasi Transfer](https://github.com/2022-IT-Pemrograman-Integratif/e-money-kelompok-3#autentikasi-transfer)
  - [Parameter Transfer](https://github.com/2022-IT-Pemrograman-Integratif/e-money-kelompok-3#parameter-transfer)
  - [Contoh Transfer](https://github.com/2022-IT-Pemrograman-Integratif/e-money-kelompok-3#contoh-transfer)
  - [Response Transfer](https://github.com/2022-IT-Pemrograman-Integratif/e-money-kelompok-3#response-transfer)


# Register
Alamat URL
https://kecana.herokuapp.com/register

## Autentikasi Register
Tidak menggunakan autentikasi tertentu.

## Parameter Register

| Parameter | Deskripsi  | Wajib |
| ------------- | ------------- | ------------- | 
| `email`        | Berupa karakter huruf dan angka sebagai email pengguna website.| Ya |
| `password`         | Berupa karakter huruf dan angka sebagai kata sandi yang digunakan untuk login oleh pengguna website.| Ya |
| `confpassword`         | Berupa karakter huruf dan angka sebagai konfirmasi kata sandi yang digunakan untuk login oleh pengguna website.| Ya |
| `nohp`         | Berupa karakter angka sebagai nomor telepon yang digunakan untuk login oleh pengguna website.| Ya |

## Contoh Register
`POST` https://kecana.herokuapp.com/register

## Response Register

### Response Register Sukses
![response_register_sukses](imgdocument/registersukses.jpg)
![response_sql_register_sukses](imgdocument/SQLRegisterSukses.jpg)

### Response Register Gagal
![response_register_gagal](imgdocument/registergagal.jpg)

# Login
Alamat URL
https://kecana.herokuapp.com/login

## Autentikasi Login
Tidak menggunakan autentikasi tertentu.

## Parameter Login

| Parameter | Deskripsi  | Wajib |
| ------------- | ------------- | ------------- | 
| `email`        | Berupa karakter huruf dan angka sebagai email pengguna website.| Ya |
| `password`         | Berupa karakter huruf dan angka sebagai kata sandi yang digunakan untuk login oleh pengguna website.| Ya |

## Contoh Login
`POST` https://kecana.herokuapp.com/login

## Response Login

### Response Login Sukses
![response_login_sukses](imgdocument/loginsukses.jpg)

### Response Login Gagal
![response_login_gagal1](imgdocument/loginnoemail.jpg)
![response_login_gagal2](imgdocument/loginnoemail2.jpg)
![response_login_gagal3](imgdocument/loginsalahpassword.jpg)

# Me (Get User)
Alamat URL
https://kecana.herokuapp.com/me

## Autentikasi Me (Get User)
Menggunakan autentikasi JWT.

## Parameter Me (Get User)

| Parameter | Deskripsi  | Wajib |
| ------------- | ------------- | ------------- | 
| `-`        | Tidak menggunakan parameter tertentu dikarenakan menggunakan token JWT | - |


## Contoh Me (Get User)
`POST` https://kecana.herokuapp.com/me

## Response Me (Get User)

### Response Me (Get User) Sukses
![image](https://user-images.githubusercontent.com/90258307/167573981-43f76c53-d6e0-4d3a-9bd8-5e66ed77804b.png)

# Topup
Alamat URL
https://kecana.herokuapp.com/topup

## Autentikasi Topup
Menggunakan autentikasi JWT.

## Parameter Topup

| Parameter | Deskripsi  | Wajib |
| ------------- | ------------- | ------------- | 
| `id`        | Berupa karakter angka sebagai identifier pengguna website.| Ya |
| `saldo`         | Berupa karakter angka sebagai nominal saldo yang digunakan untuk topup oleh pengguna website.| Ya |

## Contoh Topup
`POST` https://kecana.herokuapp.com/login

## Response Topup

### Response Topup Sukses
![response_topup_sukses](imgdocument/topupsukses.jpg)
![response_topup_sukses2](imgdocument/topupsukses2.jpg)

# Transfer
Alamat URL
https://kecana.herokuapp.com/transfer

## Autentikasi Transfer
Menggunakan autentikasi JWT.

## Parameter Transfer

| Parameter | Deskripsi  | Wajib |
| ------------- | ------------- | ------------- | 
| `id`        | Berupa karakter angka sebagai identifier pengirim saldo dan pengguna website.| Ya |
| `saldo`         | Berupa karakter angka sebagai nominal saldo yang digunakan untuk topup oleh pengguna website.| Ya |
| `saldo`         | Berupa karakter angka sebagai nomor telepon yang digunakan untuk target transfer oleh pengguna website.| Ya |

## Contoh Transfer
`POST` https://kecana.herokuapp.com/transfer

## Response Transfer

### Response Transfer Sukses
![response_transfer_sukses](imgdocument/transfersukses.jpg)
![response_sql_transfer_sukses](imgdocument/sqltransfersukses.jpg)

### Response Transfer Gagal
![response_transfer_sukses](imgdocument/transfergagal.jpg)
