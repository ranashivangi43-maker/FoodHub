🍽️ FoodHub --- Online Food Ordering System

A role-based Online Food Ordering System built with Core PHP,
MySQL, Bootstrap, HTML, CSS, and JavaScript.

FoodHub provides separate functionality for Admin, Restaurant, and
User roles. Users can browse food, manage their cart and wishlist,
place orders, and view order history. Restaurants can manage their food
items and handle customer orders, while administrators manage
restaurants, food approval, and platform-level operations.

📌 Project Overview

FoodHub is a web-based food ordering platform designed to connect
customers with restaurants through a simple role-based system.

The application contains three main roles:

👤 User --- Browse food, manage cart/wishlist, checkout, and
track orders.

🏪 Restaurant --- Manage restaurant profile, food items, and
customer orders.

🛡️ Admin --- Manage restaurants, approve/reject food requests,
and oversee platform operations.

✨ Features

👤 User Module

User registration and login

User profile management

Browse available food items

Add food items to cart

Increase/decrease cart quantity

Remove items from cart

Wishlist management

Add wishlist items to cart

Checkout and delivery information

Cash on Delivery (COD)

Online payment flow using Razorpay integration

Order success page

View previous orders

View order details

Logout

Forgot password / password reset functionality

🏪 Restaurant Module

Restaurant owner login

Complete restaurant profile

Edit restaurant profile

Restaurant dashboard

Add food items

Upload food images

Manage food items

Edit food items

Delete food items

View pending food items

View customer orders

View complete order details

View customer and delivery information

View ordered food items and quantities

View payment information

Update order status:

Pending

Confirmed

Delivered

Cancelled

🛡️ Admin Module

Admin dashboard

Add restaurants

Manage restaurants

Activate/deactivate restaurants

View restaurant food items

View food requests

Approve food items

Reject food items

View orders

Manage the overall platform

🧑‍💻 Technology Stack

Technology            Purpose

PHP               Backend/server-side development
MySQL             Database
PDO               Database connectivity and prepared statements
HTML5             Page structure
CSS3              Custom styling
Bootstrap 5.3.3   Responsive UI
Bootstrap Icons   Interface icons
JavaScript        Client-side functionality
Razorpay          Online payment integration
XAMPP             Local development environment
Git & GitHub      Version control and source code management

👥 User Roles & Access

Role                                Main Responsibilities

Admin                           Manage restaurants, food approval,
restaurant status, and platform
orders

Restaurant                      Manage profile, food items, and
customer orders

🗂️ Project Structure

Restaurant/
│
├── admin/
│   ├── add_restaurant.php
│   ├── approve_food.php
│   ├── dashboard.php
│   ├── food_request.php
│   ├── manage_restaurant.php
│   ├── orders.php
│   └── view_restaurant_foods.php
│
├── assets/
│   ├── css/
│   │   ├── add_food.css
│   │   ├── admin.css
│   │   ├── edit_food.css
│   │   ├── manage_food.css
│   │   ├── pending_foods.css
│   │   ├── restaurant.css
│   │   ├── restaurant_dashboard.css
│   │   ├── style.css
│   │   └── user.css
│   │
│   ├── js/
│   │   └── script.js
│   │
│   └── uploads/
│       └── foods/
│
├── config/
│   └── db.php
│
├── database/
│
├── includes/
│   ├── admin_navbar.php
│   ├── footer.php
│   ├── header.php
│   ├── restaurant_navbar.php
│   ├── session.php
│   └── user_navbar.php
│
├── process/
│   ├── add_food_process.php
│   ├── add_restaurant_process.php
│   ├── cart_process.php
│   ├── complete_restaurant_profile_process.php
│   ├── decrease_quantity.php
│   ├── delete_food.php
│   ├── edit_food_process.php
│   ├── edit_profile_process.php
│   ├── food_action.php
│   ├── forgot_password.php
│   ├── increase_quantity.php
│   ├── login_process.php
│   ├── place_order.php
│   ├── register_process.php
│   ├── remove_from_cart_process.php
│   ├── reset_password.php
│   ├── toggle_restaurant.php
│   ├── update_order.php
│   ├── update_restaurant_profile.php
│   └── wishlist_process.php
│
├── restaurant/
│   ├── add_food.php
│   ├── complete_profile.php
│   ├── dashboard.php
│   ├── edit_food.php
│   ├── edit_profile.php
│   ├── logout.php
│   ├── manage_food.php
│   ├── order_details.php
│   ├── order_status.php
│   ├── orders.php
│   └── pending_foods.php
│
├── uploads/
│   ├── foods/
│   ├── profiles/
│   └── restaurants/
│
├── user/
│   ├── cart.php
│   ├── checkout.php
│   ├── dashboard.php
│   ├── edit_profile.php
│   ├── my_orders.php
│   ├── order_success.php
│   ├── profile.php
│   └── wishlist.php
│
├── .gitignore
├── index.php
└── README.md

🗄️ Database Design

The project currently uses the following 9 database tables:

Table               Purpose

users             Stores users, restaurant owners, and admin accounts
restaurants       Stores restaurant information and owner relationship
foods             Stores food items belonging to restaurants
cart              Stores food items added to a user's cart
wishlist          Stores food items saved by users
orders            Stores customer order information
order_item        Stores individual food items belonging to an order
food_images       Stores additional food image records
password_resets   Stores password reset information

Main Relationships

users
 ├── restaurants
 │      └── foods
 │
 ├── cart ─────────── foods
 │
 ├── wishlist ─────── foods
 │
 └── orders
        └── order_item ─── foods

Order Flow

User selects food
       ↓
Add to Cart / Buy Food
       ↓
Checkout
       ↓
Select Payment Method
       ↓
Place Order
       ↓
Order Created
       ↓
Restaurant Receives Order
       ↓
Pending
       ↓
Confirmed
       ↓
Delivered

💳 Payment Handling

FoodHub supports two payment flows:

Cash on Delivery

Checkout
   ↓
Select COD
   ↓
Place Order
   ↓
Order Created
   ↓
Order Success Page

The order is stored with a pending payment status until the payment is
collected.

Razorpay

The project also contains a Razorpay-based online payment flow.
Successful payment information is passed to the order processing logic
and the order is created with the corresponding payment status.

Note: Payment credentials must never be committed to GitHub. Keep
secrets in local configuration/environment variables and use
placeholder values when sharing the project.

🔐 Security Practices

The project uses several basic security practices:

PDO prepared statements for database queries

Session-based authentication

Role-based access control

Password reset flow

Input validation for important operations

htmlspecialchars() when displaying user-controlled data

Sensitive configuration excluded from Git using .gitignore

⚙️ Installation & Setup

1. Clone the Repository

git clone https://github.com/ranashivangi43-maker/FoodHub.git

2. Move the Project

For XAMPP, place the project inside:

C:\xampp\htdocs\PHP\Restaurant

3. Start XAMPP

Start:

Apache

MySQL

4. Create the Database

Open:

http://localhost/phpmyadmin

Create a database named:

restaurant_project

Import the database SQL file/schema provided with the project.

5. Configure Database Connection

Update the local database configuration in:

config/db.php

Use your own local MySQL credentials.

6. Open the Project

Visit:

http://localhost/PHP/Restaurant/

🔑 Configuration & Secrets

Do not upload:

Database passwords

SMTP passwords

Razorpay secret keys

API keys

Other private credentials

The repository should contain only safe configuration examples.

For deployment, configure production credentials separately from the
source code.

🧪 Testing the Application

For a complete test, verify the application role by role.

User

Register/login

Browse food

Add to cart

Update quantity

Add/remove wishlist items

Checkout

Test COD order

Test online payment flow

View order history

Restaurant

Login

Complete profile

Add food

Edit/delete food

View pending food

View customer orders

Open order details

Confirm an order

Mark confirmed order as delivered

Cancel a pending order

Admin

Login

Add/manage restaurants

Activate/deactivate restaurants

View food requests

Approve/reject food

View restaurant foods

View orders

📸 Screenshots

Recommended screenshots for the GitHub repository:

Home / User Dashboard

User Food Listing

Cart

Checkout

Order Success

My Orders

Wishlist

Restaurant Dashboard

Restaurant Food Management

Restaurant Orders

Restaurant Order Details

Admin Dashboard

Food Approval

Restaurant Management

Screenshots can be added to a future /screenshots folder and linked
here.

🚀 Future Improvements

Possible future improvements include:

Live order tracking

More detailed order status workflow

Restaurant-wise order filtering

Customer notifications

Email notifications for order updates

Restaurant ratings and reviews

Food ratings and reviews

Search and advanced filtering

Delivery partner module

Admin analytics and reports

Improved payment verification using server-side payment signature
validation

Production deployment configuration

📚 Learning Outcomes

This project provided practical experience with:

Core PHP application development

MySQL database design

CRUD operations

PDO and prepared statements

Session management

Role-based authentication

File/image uploads

Shopping cart implementation

Wishlist functionality

Order management

Payment integration

Form handling and validation

Responsive UI development

Git and GitHub workflow

👩‍💻 Author

Shivangi Rana

GitHub:
https://github.com/ranashivangi43-maker

📄 License

This project was developed for educational and portfolio purposes.