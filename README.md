# Cake Verse - Online Cake Shop

Cake Verse is a PHP and MySQL-based web application for an online cake shop. It features a complete system with an **Admin Panel** for management and a **Customer Portal** for browsing and purchasing cakes.

## Features
*   **Customer Portal**: Customers can register, log in, browse the cake catalog, view cake details, and place orders.
*   **Admin Dashboard**: Admins can log in to manage cakes, view orders, and handle shop operations.
*   **Authentication**: Unified login system handling both customer and admin sessions securely.

## Directory Structure
*   `admin/`: Contains all admin-facing pages (dashboard, management).
*   `customer/`: Contains all customer-facing pages (catalog, cake details, checkout).
*   `database/`: Contains SQL files for setting up the database schema and sample data.
*   `config/`: Contains the database connection configuration.
*   `includes/`: Contains reusable PHP scripts like authentication logic and helper functions.
*   `assets/`: Contains CSS and JavaScript files for styling and interactivity.
*   `api/`: Backend API endpoints.

## Prerequisites
To run this project, you need a local web server environment installed on your machine.
*   [XAMPP](https://www.apachefriends.org/index.html) (Recommended for Windows) or WAMP/MAMP.
*   PHP (7.4 or higher recommended)
*   MySQL / MariaDB

## Installation & Setup Guide

### 1. Project Directory Setup
1. Download or clone this repository.
2. **Important**: You must rename the extracted folder from `Online-Cake-Shop-main` (or whatever it is currently named) to **`Cake_Verse`**. The application uses hardcoded absolute paths that require this specific folder name.
3. Move the `Cake_Verse` folder into your local server's web root directory:
   * For XAMPP: `C:\xampp\htdocs\Cake_Verse`
   * For WAMP: `C:\wamp\www\Cake_Verse`

### 2. Start the Server
1. Open the XAMPP (or WAMP) Control Panel.
2. Start both the **Apache** and **MySQL** modules.

### 3. Database Setup
1. Open your web browser and navigate to phpMyAdmin: `http://localhost/phpmyadmin`
2. Create a new database and name it exactly: **`cake_shop`**
3. Select the `cake_shop` database you just created.
4. Go to the **Import** tab.
5. First, import the schema file to create the tables:
   * Browse and select: `Cake_Verse/database/schema.sql`
   * Click **Import** (or Go).
6. Next, import the seed file to populate the database with sample data (cakes, admin user, etc.):
   * Browse and select: `Cake_Verse/database/seed.sql`
   * Click **Import** (or Go).

## How to Run the Application

Once the database is set up and the files are in the correct directory, you can access the application via your web browser.

1. **Main Entry / Login Page**:
   Open your browser and navigate to:
   `http://localhost/Cake_Verse/login.php`

2. **Admin Access**:
   You can log in to the admin dashboard using the default seeded credentials:
   * **Username**: `admin`
   * **Password**: `admin123`
   * Upon logging in, you will be redirected to the admin dashboard.

3. **Customer Access**:
   * New customers can create an account by clicking "Create a Customer Account" on the login page or navigating directly to `http://localhost/Cake_Verse/register.php`.
   * Existing customers can log in using their registered email and password to access the catalog.

## Configuration (Optional)
If you need to change the database credentials (e.g., if you have a password on your MySQL root user), you can edit the configuration file:
* File path: `config/database.php`
* Update the `DB_USER` and `DB_PASS` constants as needed.
