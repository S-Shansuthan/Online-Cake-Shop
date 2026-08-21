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
| Front-End | HTML, CSS,  JavaScript |
| Back-End | PHP |
| Database | MySQL |

**Constraints:** Built from scratch — no front-end frameworks (Bootstrap, Tailwind, React) or back-end frameworks (Laravel). AI tools may be used to assist with code generation, but the team must fully understand the final application.

## 📄 License

This project was developed for academic purposes as part of the SENG 21253 module.
online-cake-shop/
├── index.php                    # Homepage / Catalog
├── cake.php                     # Single cake detail page
├── cart.php                     # Shopping cart
├── checkout.php                 # Checkout & payment form
├── order-success.php            # Order confirmation page

├── admin/
   ├── login.php                # Admin login
   ├── logout.php               # Admin logout
   ├── dashboard.php            # Admin dashboard
   ├── cakes.php                # View all cakes
   ├── cake-add.php             # Add new cake
   ├── cake-edit.php            # Edit/update cake
   ├── cake-delete.php          # Delete cake (POST handler)
   ├── orders.php               # View all orders
   └── order-detail.php        # View single order detail

├── api/
   ├── search.php               # AJAX search handler
   ├── cart-add.php             # AJAX add to cart
   ├── cart-remove.php          # AJAX remove from cart
   └── order-place.php         # Place order handler

├── includes/
   ├── db.php                   # PDO database connection
   ├── auth.php                 # Session / auth helpers
   ├── functions.php            # Shared utility functions
   ├── header.php               # Site header (customer)
   └── footer.php               # Site footer

├── css/
   ├── style.css                # Customer-facing styles
   └── admin.css                # Admin panel styles

├── js/
   ├── main.js                  # General UI interactions
   ├── search.js                # Live search / filter logic
   ├── cart.js                  # Cart management (localStorage)
   └── admin.js                 # Admin UI helpers

├── uploads/
   └── cakes/                   # Uploaded cake images

└── sql/
    └── schema.sql               # DB schema + seed data
