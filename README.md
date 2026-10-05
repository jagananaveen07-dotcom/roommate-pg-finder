# Roommate & PG Finder

A full-stack web application designed to help students and freshers find affordable PG accommodations and compatible roommates across cities such as Bangalore, Hyderabad, Chennai, and Visakhapatnam.

## 📌 Project Overview

**Roommate & PG Finder** provides separate user and admin modules for discovering PG accommodations, managing bookings, saving favourite properties, finding roommates, and managing roommate requests.

The project was developed as a college full-stack web development project using PHP and MySQL.

## 📸 Screenshots

### Home Page

![Home Page](Images/home.png)

### User Dashboard & Search

![User Dashboard](Images/dashboard.png)

### Admin Panel

![Admin Panel](Images/admin.png)

> Add the actual screenshots to `Images/` if they are not already present.

## 🚀 Features

### User Module

* **Authentication:** User registration and login with password hashing.
* **Email OTP Verification:** Six-digit OTP verification using PHPMailer.
* **Password Recovery:** OTP-based password reset workflow.
* **PG Discovery:** Search and view available PG accommodations.
* **PG Details:** View rent, location, sharing type, rating, availability, and images.
* **Saved PGs:** Save favourite PGs for later.
* **Bookings:** Book PGs, prevent duplicate bookings, and view booking history.
* **Booking Cancellation:** Users can cancel their own bookings.
* **Roommate Matching:** Create roommate profiles and search for suitable roommates.
* **Roommate Requests:** Send, accept, and reject roommate requests.
* **Account Management:** Update personal information and password.

### Admin Module

* **Admin Authentication:** Dedicated admin login and authorisation.
* **Admin Dashboard:** Overview of PG and platform statistics.
* **PG Management:** Add, edit, and delete PG listings.
* **User Management:** View and manage registered users.
* **Booking Management:** View user bookings.
* **Roommate Management:** View and manage roommate requests.

## 🛠️ Technologies Used

### Frontend

* HTML5
* CSS3
* JavaScript

### Backend

* PHP

### Database

* MySQL

### Development Environment

* XAMPP
* Apache
* MySQL
* phpMyAdmin

### Email

* PHPMailer
* Gmail SMTP

## 🔒 Security Features

The project includes several basic security practices:

* Password hashing using `password_hash()` and verification using `password_verify()`.
* Prepared statements for database operations.
* Session-based authentication.
* Server-side user and admin authorisation checks.
* CSRF protection for important state-changing actions.
* POST requests for destructive actions.
* Output escaping using `htmlspecialchars()`.
* Database credentials and SMTP credentials can be supplied through environment variables or local configuration.

## 📂 Project Structure

```text
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
│   ├── style.css
│   └── home.css
│
├── database/
│   └── roommate.db.sql
│
├── includes/
│   ├── PHPMailer/
│   ├── db.php
│   ├── csrf.php
│   ├── mail_config.php
│   └── mailer.php
│
├── Images/
│
├── home.html
├── index.php
├── login.php
├── register.php
├── verify-otp.php
├── forgot-password.php
├── reset-password.php
├── dashboard.php
├── search.php
├── pg_details.php
├── book_pg.php
├── booking-histor
```
