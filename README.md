# terjemah-ci4

Aplikasi editor terjemah kitab-kitab salaf yang sudah dimigrasikan ke CodeIgniter 4.

## Jalankan Proyek

1. Install dependency:

```bash
php composer.phar install
```

2. Jalankan server lokal:

```bash
php spark serve
```

3. Atau arahkan web server ke folder `public`.

## Konfigurasi Penting

- Base URL: `app/Config/App.php`
- Database: `app/Config/Database.php`
- Session: `app/Config/Session.php`

## Catatan

- Endpoint lama CI3 dipetakan ke route CI4 di `app/Config/Routes.php`.
- Aset publik ada di `public/assets`.
