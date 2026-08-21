# 🎂 Online Cake Shop

A web-based cake ordering platform built for **SENG 21253 – Web Application Development (Practical)**, University of Kelaniya, Faculty of Science, Bachelor of Science Honors in Software Engineering — Academic Year 2024/2025, Semester I.

## 📋 Project Overview

The Online Cake Shop allows customers to browse cake designs, personalize orders with custom messages, and securely check out — while giving store administrators a backend dashboard to manage the catalog and track incoming orders.

## 👥 System Actors

| Role | Description |
|------|-------------|
| **Customer** | Browses cakes and places orders |
| **Administrator** | Manages the cake catalog and monitors orders |

## ✨ Features

### Customer
- **Catalog Viewing** — Homepage listing available cakes (name, flavor, weight/size, price, image)
- **Search & Filter** — Search by name or filter by occasion/category (Birthday, Wedding, Anniversary, etc.)
- **Order Customization** — Add a custom message to be written on the cake
- **Checkout & Payment** — Select a cake, enter details, and complete the purchase

### Administrator
- **Authentication** — Secure login to access the admin dashboard
- **Inventory Management (CRUD)** — Create, read, update, and delete cakes in the catalog
- **Order Management** — View incoming orders, payment statuses, and custom message requests

## 🛠️ Tech Stack

| Layer | Technology |
|-------|-----------|
| Front-End | HTML, CSS, Vanilla JavaScript |
| Back-End | PHP |
| Database | MySQL |

**Constraints:** Built from scratch — no front-end frameworks (Bootstrap, Tailwind, React) or back-end frameworks (Laravel). AI tools may be used to assist with code generation, but the team must fully understand the final application.

## 🗂️ Project Structure

```
online-cake-shop/
├── public/               # Front-end assets
│   ├── index.html        # Catalog / homepage
│   ├── css/
│   ├── js/
│   └── images/
├── admin/                 # Admin dashboard pages
├── includes/               # PHP includes (db connection, auth, helpers)
├── api/                     # PHP endpoints for orders, cakes, auth
├── database/
│   └── schema.sql           # Database schema
└── README.md
```

## 🗄️ Database Schema (overview)

- **cakes** — id, name, flavor, weight/size, price, image, category
- **orders** — id, customer details, cake_id, custom_message, payment_status, created_at
- **admins** — id, username, password_hash

*(Update this section with your actual schema and an ER diagram once implemented.)*

## 🚀 Getting Started

### Prerequisites
- PHP 8+
- MySQL / MariaDB
- A local server environment (XAMPP, WAMP, or MAMP)

### Setup
1. Clone the repository
   ```bash
   git clone <repository-url>
   cd online-cake-shop
   ```
2. Import the database schema
   ```bash
   mysql -u root -p < database/schema.sql
   ```
3. Configure database credentials in `includes/db.php`
4. Start your local server and place the project in the server's root directory (e.g., `htdocs` for XAMPP)
5. Visit `http://localhost/online-cake-shop` in your browser

## 👨‍💻 Team

| Name | Role | Contributions |
|------|------|----------------|
| | Team Leader | |
| | | |
| | | |

## 🎤 Presentation Checklist

- [ ] System architecture — database schema and tables
- [ ] Live demo — admin adds a new cake
- [ ] Live demo — customer buys the cake (custom message + payment flow)
- [ ] AI usage discussion — tools used and challenges resolved

## 📄 License

This project was developed for academic purposes as part of the SENG 21253 module.
