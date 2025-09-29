# Digital Dairy Management System (DDMS)

## Project Overview

The Digital Dairy Management System (DDMS) is a comprehensive web-based application designed to streamline dairy farm operations, product management, sales, and customer interactions. It supports multiple user roles including administrators, farmers, and customers, providing tools for efficient dairy supply chain management. The system handles milk collection tracking, inventory management, order processing, and reporting, with a modern, responsive interface built using PHP and MySQL.

Key goals:
- Automate milk collection and payment processes for farmers.
- Enable product cataloging and e-commerce for customers.
- Provide analytics and dashboards for administrators.
- Ensure secure user authentication and data management.

The project is inspired by modern dairy solutions like MILMA, focusing on real-time tracking, quality assurance, and user-friendly interfaces.

## Features

### Core Features
- **User Authentication**: Secure login for admins, farmers, and customers with role-based access.
- **Admin Dashboard**: Overview with key metrics (farmers, deliveries, milk collected, revenue), charts (monthly trends, distribution), recent activity, and quick actions.
- **Farmer Management**: Add, edit, view, and delete farmer records; track milk collections and payments.
- **Milk Collection**: Record daily collections with quantity, date, and time-of-day categorization (morning, evening, night).
- **Product Management**: Add, manage, and display dairy products (milk, curd, ice cream, ghee) with images and categories.
- **Sales and Inventory**: Track sales, generate reports (sales, payments, inventory), handle stock updates.
- **Delivery Management**: Schedule and track deliveries with status updates.
- **Customer Portal**: Browse products, add to cart, checkout, order tracking, profile management.
- **Reports**: Milk collection, sales, payment, and inventory reports with filtering.
- **Notifications**: User notifications for orders and updates.

### User-Specific Features
- **Administrators**: Full access to manage all resources, view analytics.
- **Farmers**: Record milk submissions, view payment history, personal dashboard.
- **Customers**: Product browsing, cart management, order placement, delivery tracking.

### Additional Features
- UUID-based identifiers for data consistency.
- Responsive design with Bootstrap for mobile access.
- Chart visualizations using Chart.js.
- File uploads for product images.
- Password reset and registration.

## Tech Stack

- **Backend**: PHP (with MySQLi for database interactions)
- **Database**: MySQL (tables for users, collections, products, sales, etc.)
- **Frontend**: HTML5, CSS3 (custom styles + Bootstrap 5), JavaScript (vanilla + Chart.js)
- **Icons/Fonts**: FontAwesome, Google Fonts (Poppins)
- **Security**: Session management, MD5 hashing (note: upgrade to stronger hashing like bcrypt in production), SQL escaping.
- **Other**: XAMPP for local development (Apache, MySQL).

## Installation and Setup

### Prerequisites
- XAMPP (or equivalent: Apache, MySQL, PHP 7+)
- Web browser (Chrome, Firefox recommended)

### Steps
1. **Clone/Download Project**:
   - Place the project files in `c:/xampp/htdocs/digital_dairy` (or your htdocs directory).

2. **Database Setup**:
   - Start XAMPP (Apache and MySQL).
   - Open phpMyAdmin (http://localhost/phpmyadmin).
   - Create a new database named `digital_dairy` (or update `includes/config.php`).
   - Import the SQL schema:
     - Run `create_order_tables.sql` for orders-related tables.
     - The main schema is in `digital_dairy (3).sql` – import this for core tables (farmers, customers, milk_collection, etc.).
     - Additional setup: Run `setup_order_tables.php` if needed for order tables.
   - Update database credentials in `includes/config.php`:
     ```
     $host = "localhost"; // or your host
     $user = "root"; // default XAMPP user
     $pass = ""; // default no password
     $dbname = "digital_dairy";
     ```

3. **UUID Helper**:
   - The project uses UUIDs for unique identifiers. Ensure `includes/uuid_helper.php` is functional (uses `uniqid()` or similar).

4. **File Permissions**:
   - Ensure `uploads/` directory is writable for product images.

5. **Run the Application**:
   - Access via http://localhost/digital_dairy/index.php (login page).
   - Default login: Create users via registration or admin panel (no defaults; register first).

6. **Testing**:
   - Run `test_uuid_functionality.php` or similar scripts for data integrity.
   - Lint PHP: Run `lint_php.bat` for code quality checks.

### Troubleshooting
- Database connection errors: Check `includes/config.php`.
- UUID issues: Run migration scripts like `update_uuids.php`, `fix_uuid_consistency.php`.
- Missing tables: Import SQL dumps and run setup scripts (e.g., `add_image_column.php`, `alter_uuid_columns.php`).

## Database Schema

The database uses MySQL with the following key tables (inferred from code; run `DESCRIBE table_name;` in phpMyAdmin for details):

### Core Tables
- **farmers**: id, uuid, name, username, email, phone, address, password, created_at.
- **tblcustomer**: id, customer_name, email, password, phone, address.
- **milk_collection**: id, farmer_uuid, quantity (liters), date, time_of_day (inferred), fat_content, etc.
- **tbldelivery**: id, uuid, farmer_uuid, quantity, delivery_date, status.
- **tblproduct**: id, product_name, category, price, stock_quantity, image_path.
- **tblsales**: id, product_id, customer_id, quantity, total_amount, sales_date.
- **orders** (from create_order_tables.sql): orders (id, customer_id, total, status), order_items (order_id, product_id, quantity, price).

### Relationships
- Farmers → Milk Collections (one-to-many via uuid).
- Customers → Orders/Sales (one-to-many).
- Products → Sales/Orders (many-to-many via items).
- UUIDs ensure cross-table consistency (e.g., farmer_uuid in collections/deliveries).

For full schema, import `digital_dairy (3).sql` and inspect.

## User Roles and Flows

### Administrator
- Login: Via index.php (select 'Admin' if separate; code shows farmer/customer, admin likely via dashboard.php check).
- Flow: Login → Dashboard (stats/charts) → Manage Farmers/Collections/Deliveries/Products/Sales → Reports.
- Permissions: CRUD on all resources.

### Farmer
- Registration/Login: Via index.php (select 'Farmer', use email/username).
- Flow: Login → user_dashboard.php → Record Milk (user_milk_record.php) → View Payments (user_payment.php) → Profile/Logout.
- Features: Submit collections, view history.

### Customer
- Registration: customer_reg.php.
- Login: index.php (select 'Customer', email).
- Flow: Login → home_page.php (browse products) → Add to Cart (cart.php) → Checkout (cart_checkout.php, process_checkout.php) → Order Details (order_details.php) → Profile (user_profile.php).
- Features: Shop milk/curd/icecream/ghee, track orders.

### Authentication Flow
- Sessions: $_SESSION['user_type'], $_SESSION['email'], etc.
- Password: MD5 hashed (insecure; update to password_hash()).
- Logout: logout.php, user_logout.php.

## File Structure

```
digital_dairy/
├── index.php                  # Main login page
├── dashboard.php              # Admin dashboard with stats/charts
├── home_page.php              # Customer home/products page
├── customer_reg.php           # Customer registration
├── login.php                  # Alternative login?
├── includes/
│   ├── config.php             # DB connection
│   ├── header.php             # Common header
│   ├── footer.php             # Common footer
│   ├── sidebar.php            # Admin sidebar
│   ├── user_sidebar.php       # User sidebar
│   └── uuid_helper.php        # UUID generation
├── admin/                     # Admin-specific pages (if populated)
├── chart/                     # Chart JS files
│   └── script.js
├── uploads/                   # Product images
│   └── products/
├── style.css                  # Main styles
├── user_style.css             # User-specific styles
├── SQL Files/
│   ├── digital_dairy (3).sql  # Main DB dump
│   └── create_order_tables.sql # Orders schema
├── Management Pages/
│   ├── add_farmer.php         # Add farmer
│   ├── manage_farmer.php      # List/edit farmers
│   ├── add_collection.php     # Add milk collection
│   ├── manage_collection.php  # Manage collections
│   ├── add_product.php        # Add product
│   ├── manage_product.php     # Manage products
│   ├── add_sales.php          # Add sale
│   ├── manage_sales.php       # Manage sales
│   └── ... (similar for delivery)
├── User Pages/
│   ├── user_dashboard.php     # Farmer dashboard
│   ├── user_milk_record.php   # Record milk
│   ├── cart.php               # Shopping cart
│   ├── cart_checkout.php      # Checkout
│   ├── process_checkout.php   # Process order
│   └── order_details.php      # View orders
├── Reports/
│   ├── milk_collection_report.php
│   ├── sales_report.php
│   ├── payment_report.php
│   └── inventory_report.php
├── Utilities/
│   ├── forgot-password.php    # Password reset
│   ├── profile.php            # User profile
│   └── TODO.md                # Pending tasks
└── Scripts/
    ├── setup_order_tables.php # DB setup
    ├── update_uuids.php       # Migrations
    └── lint_php.bat           # Code linting
```

## Usage

1. **Start Server**: Run XAMPP, access http://localhost/digital_dairy/.
2. **Admin Setup**: Register or insert admin user in DB (extend login for admin role).
3. **Daily Operations**:
   - Admin: Use dashboard to monitor, add/manage resources.
   - Farmer: Login, record milk via forms.
   - Customer: Browse home_page.php, add products to cart, checkout.
4. **Reports**: Access via sidebar links (e.g., milk_collection_report.php).
5. **Customization**: Update styles in style.css; add products via admin panel.

## Security Considerations
- Use prepared statements (current code uses mysqli_escape; migrate to PDO/prepared).
- Upgrade password hashing from MD5.
- Validate/sanitize all inputs.
- HTTPS in production.
- Limit file uploads to images.

## Known Issues / TODO
From TODO.md:
- Update header to MILMA-style navigation.
- Reorganize products into categories (Milk, Curd, Ghee, Ice Cream).
- Enhance responsive design and product cards.
- Additional migrations for UUIDs and columns (run scripts like alter_uuid_columns.php).

For contributions or issues, check TODO.md or contact the developer.

## License
This project is for educational purposes. © 2025 Developed by Joyal (MACFAST).

---

For more details, refer to individual PHP files or database schema.
