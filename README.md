## Akane Admin<sup>v1.1</sup>

Backend / Halaman Admin siap pakai, menggunakan template AdminLTE versi lama, jadi sangat memungkinkan untuk dimodifikasi memakai template Admin lain, atau membuat sendiri.

Version: 1.1
Last Update: 2017-06-16
Author: [WebHade Creative](http://www.webhade.id)
___

### Kebutuhan Minimum

1. PHP 5.4 keatas - PHP CLI
2. MySQL / MariaDB

### Instalasi

1. clone / download repo dari Github ke htdocs ( [https://github.com/inuvalogic/akaneadmin](https://github.com/inuvalogic/akaneadmin) )
2. buat database baru dengan nama `akane_admin`
3. import db.sql ke database tadi
4. akses http://localhost/akaneadmin/
5. login dengan user & password berikut:

```sh
username: admin
password: admin
```

### Akane Console Tools

![alt text](https://github.com/inuvalogic/akaneadmin/raw/master/preview/console.png "akane console tools")

untuk mengakses Akane Console Tools ketikan perintah berikut pada console/ command prompt/ cmd, pastikan PHP CLI terinstall dengan benar.

```sh
$ php console
```

#### Generate CRUD dari existing table structure

Akane Console memiliki kemampuan mengenerate CRUD dari struktur table yang ada, ada dua mode untuk generate ini:

1. generate CRUD untuk semua table

```sh
$ php console generate:all
atau
$ php console gen:all
```

2. generate CRUD untuk 1 table

```sh
$ php console generate:single namatable
atau
$ php console gen:sin namatable
```

Menu pada sidebar akan otomatis

### Running on Live / Production Server

`WARNING! DO WITH YOUR OWN RISK!`

Untuk saat ini, `Akane Admin` tidak disarankan untuk dipakai pada Live Server, karena masih pada tahap development dan tingkat keamanannya masih jauh dibawah standar operasional.
___

## Contribute

Feel free to contribute to this project

visit our site here

[www.webhade.id](http://www.webhade.id)