🍽️ FoodHub

A role-based online food ordering system built with Core PHP and
MySQL.

FoodHub connects customers, restaurants, and administrators in one
web application. Customers can browse food, manage their cart and
wishlist, place orders and view order history. Restaurants can manage
food items and process customer orders, while admins manage restaurants,
food approvals and platform orders.

✨ Modules

👤 User                 🏪 Restaurant           🛡️ Admin

Register & Login        Restaurant Profile      Admin Dashboard

Browse Food             Add / Edit Food         Add Restaurants

Cart Management         Manage Food             Manage Restaurants

Wishlist                Pending Food            Activate / Deactivate
Restaurants

Checkout                Customer Orders         Food Approval

COD & Razorpay Flow     Order Details           View Orders

My Orders               Order Status Management Restaurant Food
Management

🛠️ Tech Stack

Frontend: HTML5 · CSS3 · Bootstrap 5.3.3 · JavaScript · Bootstrap
Icons
Backend: Core PHP · PDO
Database: MySQL
Payments: Razorpay
Development: XAMPP · Git · GitHub

🔄 Order Management

Customer
   │
   ▼
Browse Food
   │
   ▼
Cart / Checkout
   │
   ▼
Select Payment Method
   │
   ▼
Place Order
   │
   ▼
Restaurant Receives Order
   │
   ├── Pending
   │     ├── Confirmed ──► Delivered
   │     └── Cancelled
   │
   ▼
Customer can view order history

🗄️ Database

FoodHub currently uses 9 MySQL tables:

Table               Purpose

users             Stores user, restaurant-owner and admin accounts
restaurants       Stores restaurant details and owner relationship
foods             Stores restaurant food items
cart              Stores users' cart items
wishlist          Stores users' wishlist items
orders            Stores customer order and payment information
order_item        Stores individual items within an order
food_images       Stores food image records
password_resets   Stores password reset information

Database Relationship

users
 ├── restaurants ──► foods
 │
 ├── cart ─────────► foods
 │
 ├── wishlist ─────► foods
 │
 └── orders ───────► order_item ─────► foods

📁 Project Structure

Restaurant/
│
├── admin/                  # Admin module
├── restaurant/             # Restaurant module
├── user/                   # Customer module
│
├── process/                # Form & business logic
├── includes/               # Shared navigation/session files
├── assets/
│   ├── css/                # Application stylesheets
│   └── js/                 # JavaScript
│
├── uploads/
│   ├── foods/              # Food images
│   ├── profiles/           # Profile images
│   └── restaurants/        # Restaurant images
│
├── config/                 # Database configuration
├── database/               # Database resources
│
├── index.php               # Application entry point
├── .gitignore              # Ignored files/secrets
└── README.md

🔐 Key Implementation Details

Session-based authentication

Separate functionality for Admin, Restaurant and User roles

PDO prepared statements for database operations

Role-specific dashboards and navigation

Food approval workflow

Cart and wishlist management

Order creation and order-item storage

Restaurant-specific order access

Restaurant order status management

Customer delivery information

COD and Razorpay payment flow

Password reset functionality

Image upload handling

Sensitive configuration excluded through .gitignore

💳 Payment Flow

Cash on Delivery

Checkout → COD → Order Created → Order Success

Razorpay

Checkout → Razorpay Payment → Payment Details
          → Order Processing → Order Created

Security: Payment keys, SMTP credentials, database credentials and
other secrets should never be committed to the repository.

⚙️ Local Setup

Requirements

XAMPP

Apache

MySQL

PHP

Git

Installation

1. Clone the repository

git clone https://github.com/ranashivangi43-maker/FoodHub.git

2. Place the project

C:\xampp\htdocs\PHP\Restaurant

3. Start XAMPP

Start Apache and MySQL.

4. Create the database

Open:

http://localhost/phpmyadmin

Create:

restaurant_project

Import the project database schema.

5. Configure the database

Update the local credentials in:

config/db.php

6. Run the application

http://localhost/PHP/Restaurant/

🧪 Main Workflows to Test

User

Register → Login → Browse Food → Cart/Wishlist → Checkout → Place Order → My Orders

Restaurant

Login → Complete Profile → Manage Food → View Orders → Order Details → Update Status

Admin

Login → Manage Restaurants → Review Food Requests → Approve/Reject Food → View Orders

🚀 Future Improvements

Customer and restaurant notifications

Live order tracking

Ratings and reviews

Advanced food search and filtering

Restaurant-wise analytics

Delivery partner module

Improved server-side payment verification

Production deployment

More detailed reporting and analytics

📌 Project Purpose

FoodHub was developed as a practical full-stack PHP project to
demonstrate:

Authentication · Role-Based Access · CRUD · MySQL Relationships · Cart
& Wishlist · Food Management · Order Processing · Payment Integration ·
File Uploads · Responsive UI

👩‍💻 Author

Shivangi Rana

GitHub: ranashivangi43-maker

📄 License

This project is developed for educational and portfolio purposes.