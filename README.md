# Roommate & PG Finder

A full-stack web application to help students and freshers find affordable PG accommodations and compatible roommates across cities like Bangalore, Hyderabad, Chennai, and Visakhapatnam.

Badges: (add CI / license badges here)

## Table of Contents
- [Project Overview](#project-overview)
- [Demo](#demo)
- [Features](#features)
- [Technologies](#technologies)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Database setup](#database-setup)
- [Configuration](#configuration)
- [Project structure](#project-structure)
- [Security](#security)
- [Contributing](#contributing)
- [License](#license)
- [Contact](#contact)

## Project Overview
Roommate & PG Finder provides separate user and admin modules for managing PG listings, bookings, saved PGs, roommate profiles, and roommate requests.

## Demo
(If you have a deployed demo, link it here.)

## Features
### User
- Register/login, secure password hashing
- Search and view PGs, save favourites
- Book PGs, cancel bookings, view booking history
- Create roommate profile, search roommates, send/accept/reject requests
- Update account details

### Admin
- Secure admin login and dashboard
- Add/edit/delete PG listings
- Manage users, view bookings and roommate requests
- View PG statistics

## Technologies
- HTML5, CSS3, JavaScript
- PHP (server-side)
- MySQL (database)
- XAMPP / phpMyAdmin (development)

## Prerequisites
- PHP 7.4+ (or your target PHP version)
- MySQL or MariaDB
- XAMPP (recommended for local dev)

## Installation
1. Clone the repo to your XAMPP `htdocs` directory:
   - Example: `C:\xampp\htdocs\roommate-pg-finder`
2. Start Apache and MySQL using XAMPP control panel.
3. Import the database:
   - Open phpMyAdmin -> create a database (e.g., `roommate_db`) -> Import `database/roommate_db.sql`.
4. Configure database connection in `includes/db.php` (update host, username, password, dbname).
5. Visit the app in the browser:
   - `http://localhost/roommate-pg-finder/home.html` (or the correct entry file)

## Database setup
- SQL file: `database/roommate_db.sql`
- Ensure you create the DB and import schema before first run.

## Configuration
- `includes/db.php` contains connection settings. Example:
  ```
  <?php
  $host = 'localhost';
  $user = 'root';
  $pass = '';
  $db   = 'roommate_db';
  $mysqli = new mysqli($host, $user, $pass, $db);
  ?>
  ```
- If you prefer, move credentials to an environment file and load them securely.

## Project structure
roommate-pg-finder/
│
├── admin/
│   ├── admin-login.php
│   ├── admin-login-check.php
│   ├── admin-dashboard.php
│   ├── add-pg.php
│   ├── save-pg.php
│   ├── edit-pg.php
│   ├── update-pg.php
│   ├── delete-pg.php
│   ├── manage-pgs.php
│   ├── manage-users.php
│   ├── view-bookings.php
│   └── view-roommate-requests.php
│
├── css/
│   └── style.css
├── database/
│   └── roommate_db.sql
├── includes/
│   └── db.php
├── js/
│   └── script.js
├── images/
├── home.html
├── login.php
├── register.php
├── dashboard.php
├── search.php
├── book_pg.php
├── booking-history.php
├── save_pg.php
├── saved_pgs.php
├── remove_saved.php
├── roommates.php
├── find-roommate.php
├── send_request.php
├── roommate-requests.php
├── accept_request.php
├── reject_request.php
├── account-settings.php
├── update_account.php
├── cancel_booking.php
└── logout.php

## Security
- Passwords hashed via `password_hash()` and verified with `password_verify()`.
- Use prepared statements to avoid SQL injection.
- Session-based authentication, admin authorization, and user ownership checks.
- Escape output with `htmlspecialchars()` where appropriate.

## Contributing
Please open issues or PRs. Include steps to reproduce and test any bug fixes.

## License
Add a license file (e.g., MIT). Example: `LICENSE` — MIT License

## Contact
Naveen Jagana— jagananaveen07@gmail.com
