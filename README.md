# Roommate & PG Finder

A full-stack web application that helps students and freshers find affordable PG accommodations and compatible roommates.

## Project Overview

Roommate & PG Finder is designed for students and freshers who move to cities such as Bangalore, Hyderabad, Chennai, and Visakhapatnam and need an easy way to find PGs and roommates.

The application provides separate user and admin modules for managing PG listings, bookings, saved PGs, roommate profiles, and roommate requests.

## Features

### User Features

- User registration and login
- Secure password hashing
- Search available PGs
- View PG details
- Save favourite PGs
- Remove saved PGs
- Book available PGs
- Prevent duplicate bookings
- View booking history
- Cancel bookings
- Create roommate profile
- Search for roommates
- Send roommate requests
- Accept or reject roommate requests
- Update account details

### Admin Features

- Secure admin login
- Admin dashboard
- View PG statistics
- Add PG listings
- Edit PG listings
- Delete PG listings
- Manage registered users
- View bookings
- View roommate requests

## Technologies Used

- HTML5
- CSS3
- JavaScript
- PHP
- MySQL
- XAMPP
- phpMyAdmin

## Security Features

- Password hashing using `password_hash()`
- Password verification using `password_verify()`
- Prepared statements for database operations
- Session-based authentication
- Admin authorization checks
- User ownership checks
- Output escaping using `htmlspecialchars()`

## Project Structure

```text
roommate-project/
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
│
├── database/
│   └── roommate_db.sql
│
├── includes/
│   └── db.php
│
├── js/
│   └── script.js
│
├── Images/
│
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