# Absen Digital Codeigniter 3
 
Absen digital menggunakan Codeigniter 3 (Legacy) ini merupakan sebuah project saya yang telah dibuat pada saat saya memiliki waktu luang mungkin aplikasi ini tidak 100% complete dan masih ada
terjadinya bug pada aplikasi ini tetapi saya tetap berusaha untuk memperbaiki celah tersebut sebisa mungkin.

## Fitur

Fitur Yang Tersedia Pada Aplikasi Ini:
> - Absen Scan Barcode (Instant Absen [Beta])
> - Remember Me
> - Custom Setting Aplikasi
> - Informasi Pada Saat Absen
> - Sistem Login
> - Export Absen (Support PDF & Excel)
> - Absensi Dengan Maps (Beta)

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
![Login Page](https://github.com/sandyh90/Codeigniter3-absen-digital/blob/master/images-demo/Screenshot_2020-09-12%20Login%20Absensi(1).png)
![Front Page](https://github.com/sandyh90/Codeigniter3-absen-digital/blob/master/images-demo/Screenshot_2020-09-12%20Absensi%20Online.png)
![Konfirmasi Absen](https://github.com/sandyh90/Codeigniter3-absen-digital/blob/master/images-demo/Screenshot_2020-09-12%20Confirm%20Instant%20Absen.png)

## Alasan Memakai Folder Public

Mengapa saya pindahkan untuk file index.php ke folder public dengan alasan untuk keamanan pada data sistem aplikasi ini, mungkin ini tidak begitu efektif tetapi ini sangat berguna untuk menghindari hal - hal yang tidak diinginkan dan juga
ini bukan cara yang paling akurat menurut saya 

## Issues
- [x] Masih terdapat bug pada edit user dibagian show password
dan keterangan upload (Done)
- Terdapat sedikit bug pada absensi

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
