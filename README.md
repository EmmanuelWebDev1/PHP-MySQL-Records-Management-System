# RecordFlow — PHP/MySQL Records Management System

A full-stack CRUD application for maintaining a simple people/contact directory. Built with PHP, MySQL, HTML, CSS, and vanilla JavaScript.

## Features
- Secure login session (demo admin account)
- Add, edit, view, search, and delete records
- Search by name, email, phone, department, or notes
- Pagination for larger record lists
- Prepared SQL statements (PDO)
- CSRF tokens for create, edit, and delete forms
- Server-side input validation and HTML output escaping
- Responsive dashboard and record forms
- Demo seed records

## Requirements
- PHP 8.0+ with PDO MySQL enabled
- MySQL 5.7+ or MariaDB
- XAMPP, WAMP, MAMP, or a PHP/MySQL hosting account

## Setup using XAMPP (Windows)
1. Install and open XAMPP. Start **Apache** and **MySQL**.
2. Extract this project into `C:\\xampp\\htdocs\\php-mysql-records-management`.
3. Open `http://localhost/phpmyadmin`.
4. Choose **Import**, select `database/schema.sql`, and run the import. The SQL creates the `records_management` database and demo tables.
5. Copy `config.example.php` to `config.php`.
6. Open `config.php` and check your MySQL settings. The default XAMPP setup usually uses `root` with an empty password; your setup may differ.
7. Visit `http://localhost/php-mysql-records-management/`.
8. Sign in with the demo credentials below.

## Demo login
- Username: `admin`
- Password: `password`

**Important:** This is a demonstration login. Change the password hash before hosting this system publicly. The seed hash is a common demo password and must not be used in production.

To generate a secure password hash, run:
```bash
php -r "echo password_hash('YOUR_NEW_PASSWORD', PASSWORD_DEFAULT), PHP_EOL;"
```
Then update the `users.password_hash` value for `admin` in MySQL with the generated hash.

## Project structure
```text
php-mysql-records-management/
├── assets/
│   ├── app.js
│   └── style.css
├── database/
│   └── schema.sql
├── includes/
│   ├── bootstrap.php
│   ├── footer.php
│   ├── header.php
│   └── record-form.php
├── config.example.php
├── create.php
├── delete.php
├── edit.php
├── index.php
├── login.php
├── logout.php
└── README.md
```

`config.php` is intentionally not included in the ZIP because database credentials are environment-specific. Copy `config.example.php` to `config.php` before running.

## Notes
- This project is a starter application, not a fully audited production system. Add rate limiting, secure deployment settings, backups, and a password-change workflow before public use.
- The app uses PHP sessions and PDO. Ensure `pdo_mysql` is enabled in your PHP configuration.
