# Absen Digital Codeigniter 3
 
Absen digital menggunakan Codeigniter 3 ini merupakan sebuah project saya yang telah dibuat pada saat saya memiliki waktu luang mungkin aplikasi ini tidak 100% complete dan masih ada
terjadinya bug pada aplikasi ini tetapi saya tetap berusaha untuk memperbaiki celah tersebut sebisa mungkin.

## Attention: Migrating From Codeigniter 3 To Codeigniter 4 (Plan)

Untuk saat ini sedang ada rencana untuk migrasi dari versi codeigniter lama ke
model yang terbaru saat ini kemungkinan akan dibuat nanti. (Stay Tuned)

## Fitur

Fitur Yang Tersedia Pada Aplikasi Ini:
> - ~~Absen Scan Barcode (Instant Absen [Beta])~~ [Removed]
> - Remember Me
> - Custom Setting Aplikasi
> - Informasi Pada Saat Absen
> - Sistem Login
> - Export Absen (Support PDF & Excel)
> - Absensi Dengan Maps (Beta)


## Issues
- Terdapat sedikit bug pada absensi
- Berhubung untuk fitur scan barcode menggunakan javascript dari komponen WebRTC API maka server diharuskan untuk menggunakan
yang bernama SSL dengan mengharuskan web server berjalan menggunakan protokol HTTPS bukan HTTP berdasarkan new chrome security policy
maka dengan itu fitur scan barcode akan ditiadakan.

## Server Requirement

> - PHP 7.4.8
> - Nginx 1.19.1 Or Apache 2.4.46
> - MariaDB 10.4.13

## Login Account (Default)

> - Username: admin
> - Password: 12345678

## Setting Database
Untuk menyesuaikan pengaturan pada database anda silakan dibuka:
> absendigital/application/config/database.php

Silakan ubah beberapa config ini saja untuk disesuaikan dengan pengaturan database anda:
```
'hostname' => 'localhost', |Ubah kolom hostname ini dengan url hostname database anda
	'username' => 'root', |Ubah kolom username ini jika berbeda
	'password' => '', |Ubah kolom password ini jika database anda mempunyai password
	'database' => 'absensi_online', |default (jika ada kesamaan nama pada nama database ini dengan yang hasil import silakan diubah)
```

## Demo / Screenshot
![Login Page](https://github.com/sandyh90/Codeigniter3-absen-digital/blob/master/images-demo/Screenshot_2021-02-03%20Login%20Absensi.png)
![Front Page](https://github.com/sandyh90/Codeigniter3-absen-digital/blob/master/images-demo/Screenshot_2020-09-12%20Absensi%20Online.png)

Ingin mencoba aplikasi web ini silakan kunjungi

[Demo Web](http://demo.nerosky.rf.gd/absendigitalci3/)

## Alasan Memakai Folder Public

Mengapa saya pindahkan untuk file index.php ke folder public dengan alasan untuk keamanan pada data sistem aplikasi ini, mungkin ini tidak begitu efektif tetapi ini sangat berguna untuk menghindari hal - hal yang tidak diinginkan dan juga
ini bukan cara yang paling akurat menurut saya 

Note: jika anda ingin tidak memakai folder public anda bisa pindahkan semua isi didalam folder public ke folder root aplikasi dan jangan lupa untuk mengganti path filenya dan cari confignya seperti ini pada file **index.php**.

```
/*
 *---------------------------------------------------------------
 * SYSTEM DIRECTORY NAME
 *---------------------------------------------------------------
 *
 * This variable must contain the name of your "system" directory.
 * Set the path if it is not in the same directory as this file.
 */
	$system_path = 'system'; <-- Sebelumnya ../system

/*
 *---------------------------------------------------------------
 * APPLICATION DIRECTORY NAME
 *---------------------------------------------------------------
 *
 * If you want this front controller to use a different "application"
 * directory than the default one you can set its name here. The directory
 * can also be renamed or relocated anywhere on your server. If you do,
 * use an absolute (full) server path.
 * For more info please see the user guide:
 *
 * https://codeigniter.com/userguide3/general/managing_apps.html
 *
 * NO TRAILING SLASH!
 */
	$application_folder = 'application'; <-- Sebelumnya ../application
```

## Change Log
### 9-13-2020
- Export dengan metode excel
- Mengubah bahasa pada datepicker bagian bulan

### 9-15-2020
- Penambahan fitur absensi dengan mengunakan lokasi
- Perbaikan beberapa bug yang telah ditemukan

### 10-05-2020
- Perbaikan bug pada edit user dibagian show password dan keterangan upload
- Penambahan aksi pada list absen bagian pegawai
- Perbaikan pada layout export excel
- Perbaikan beberapa bug yang telah ditemukan 
- Perubahan pada layout halaman setting user

### 11-21-2020
- Penambahan fitur untuk keterangan absen pada saat absen
- Perbaikan lokasi tidak terdeteksi pada fitur instant absen

### 2-3-2021
- Scan barcode pada fitur absensi dihapuskan karena adanya kebijakan baru tentang kebijakan keamanan pada web browser
- Perbaikan pada folder view dikarenakan terjadinya error 404 not found pada saat hosting
- Perubahan layout pada halaman login
- Fitur status Online / Offline pada aplikasi di hapuskan sementara
- Perbaikan untuk fitur upload gambar dan qr code dikarena path penyimpanan masih metode static
