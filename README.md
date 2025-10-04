# Digital Dairy Management System (DDMS)

A comprehensive web-based dairy management system built with PHP and MySQL that streamlines dairy operations from farm to consumer. The system manages farmers, milk collection, product inventory, customer orders, deliveries, and provides detailed reporting capabilities.

## 🌟 Features

### For Farmers
- **Profile Management**: Complete farmer registration with contact details, farm size, and banking information
- **Milk Collection Tracking**: Daily milk collection records with quality metrics (fat content, temperature)
- **Payment Management**: Automated payment calculations and transparent transaction history
- **Farm Analytics**: Detailed insights into farm performance and productivity trends
- **Digital Records**: Secure storage of all farming activities and transactions

### For Customers
- **Easy Online Ordering**: Browse and order fresh dairy products with just a few clicks
- **Product Catalog**: Comprehensive catalog of dairy products (milk, curd, ghee, ice cream, etc.)
- **Delivery Tracking**: Real-time tracking of orders from processing to delivery
- **Quality Assurance**: Fresh, pure dairy products delivered straight from local farms
- **Order History**: Complete history of past orders and transactions

### For Administrators
- **Farmer Management**: Add, edit, view, and manage all registered farmers
- **Product Management**: Complete inventory management with image uploads
- **Sales Reporting**: Advanced reporting with date filtering and export capabilities (Excel, PDF, Print)
- **Delivery Management**: Track and manage delivery operations
- **Customer Management**: Manage customer accounts and orders
- **Analytics Dashboard**: Real-time insights and system statistics

## 🛠 Technology Stack

- **Backend**: PHP 8.0+
- **Database**: MySQL 8.0+ / MariaDB
- **Frontend**: HTML5, CSS3, JavaScript
- **UI Framework**: Bootstrap 5.3
- **Icons**: Font Awesome 6.4
- **Data Tables**: DataTables with export functionality
- **Charts**: Chart.js for analytics
- **Server**: Apache/Nginx with PHP support

## 📋 Prerequisites

- PHP 8.0 or higher
- MySQL 8.0 or MariaDB 10.4+
- Apache/Nginx web server
- XAMPP/WAMP/MAMP (recommended for local development)
- Modern web browser with JavaScript enabled

## 🚀 Installation

### 1. Environment Setup

1. **Install XAMPP** (recommended for Windows):
   - Download and install XAMPP from [apachefriends.org](https://www.apachefriends.org/)
   - Start Apache and MySQL services

2. **Clone or Download the Project**:
   ```bash
   # Place the project files in your web server directory
   # For XAMPP: htdocs/digital_dairy/
   # For WAMP: www/digital_dairy/
   ```

### 2. Database Setup

1. **Create Database**:
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create a new database named `digital_dairy`

2. **Import Database Schema**:
   - Select the `digital_dairy` database
   - Import the `digital_dairy (3).sql` file from the project root

3. **Update Configuration**:
   - Open `includes/config.php`
   - Verify database connection settings (should work with default XAMPP settings)

### 3. File Permissions

Ensure proper permissions for file uploads:
```bash
# For Linux/Mac
chmod 755 uploads/
chmod 755 uploads/products/

# For Windows - ensure IIS_IUSRS or appropriate user has write permissions
```

### 4. Access the Application

- Open your browser and navigate to: `http://localhost/digital_dairy/`
- Default admin credentials:
  - Email: `admin@gmail.com`
  - Password: `admin`

## 📊 Database Schema

### Core Tables

#### `farmers`
- Farmer registration and profile information
- Fields: id, uuid, name, contact, address, farm_size, email, password, aadhar, bank_account, ifsc, status

#### `milk_collection`
- Daily milk collection records
- Fields: id, date, farmer_uuid, product_type, quantity, fat, temperature, payment

#### `tblproduct`
- Product catalog and inventory
- Fields: ID, ProductName, ProductType, UnitPrice, productimage, Quantity, Description

#### `tblcustomer`
- Customer registration and profile
- Fields: id, customer_name, contact, email, address, password, status

#### `tblorders` & `tblorderitems`
- Customer order management
- Order header with customer details and totals
- Order items with product details and quantities

#### `tbldelivery`
- Delivery tracking and management
- Fields: ID, CustomerName, Contact, Address, DeliveryDate, ProductType, Quantity, VehicleNo, DriverName

#### `tblsales`
- Sales transaction records
- Fields: ID, InvoiceNumber, CustomerName, Contact, ProductName, Quantity, Price, TotalAmount, SalesDate

#### `admin`
- Administrative user accounts
- Fields: id, email, password

## 👥 User Roles & Access

### 1. Administrator
- **Login URL**: `index.php` (select "Admin Login")
- **Capabilities**:
  - Manage farmers (add, edit, view, archive)
  - Manage products (add, edit, delete, view inventory)
  - View sales reports with filtering and export
  - Manage deliveries and customer orders
  - Access system analytics and statistics

### 2. Farmer
- **Login URL**: `index.php` (select "Farmer/Customer Login")
- **Capabilities**:
  - View personal dashboard with milk collection history
  - Track payments and earnings
  - Update profile information
  - Access farm analytics and reports

### 3. Customer
- **Login URL**: `index.php` (select "Farmer/Customer Login")
- **Registration**: `customer_reg.php`
- **Capabilities**:
  - Browse product catalog
  - Place orders online
  - Track order status and delivery
  - View order history
  - Manage account profile

## 📖 Usage Guide

### Farmer Workflow
1. **Registration**: Farmers register through the admin panel or self-registration
2. **Daily Operations**: Record milk collection with quality parameters
3. **Payment Tracking**: Monitor payments and earnings
4. **Profile Management**: Update contact and banking information

### Customer Workflow
1. **Registration**: Create account with personal details
2. **Product Browsing**: Explore available dairy products
3. **Order Placement**: Add items to cart and complete purchase
4. **Delivery Tracking**: Monitor order status in real-time
5. **Order History**: Review past purchases and reorder

### Admin Workflow
1. **Farmer Management**: Add new farmers and manage existing ones
2. **Product Management**: Maintain product catalog and inventory
3. **Order Processing**: Monitor and manage customer orders
4. **Reporting**: Generate sales reports and analytics
5. **System Maintenance**: Monitor system health and performance

## 🔧 Key Modules

### Authentication System
- Secure login for all user types
- Password hashing with MD5 (consider upgrading to bcrypt)
- Session management
- Password reset functionality

### Milk Collection Module
- Daily collection entry with quality metrics
- Farmer-wise tracking
- Payment calculation based on quantity and quality
- Historical data analysis

### E-commerce Module
- Product catalog with image uploads
- Shopping cart functionality
- Order processing and management
- Payment integration (framework ready)

### Reporting System
- Date-range filtering
- Multiple export formats (Excel, PDF, Print)
- Real-time summary statistics
- Comprehensive sales analytics

## 🔒 Security Features

- SQL injection prevention with prepared statements
- XSS protection with input sanitization
- Session-based authentication
- File upload validation
- Password encryption
- Role-based access control

## 📱 Responsive Design

- Mobile-first approach with Bootstrap 5
- Responsive data tables
- Touch-friendly interfaces
- Optimized for all screen sizes

## 📈 Analytics & Reporting

- Real-time dashboard statistics
- Sales performance metrics
- Farmer productivity analysis
- Customer order trends
- Revenue tracking and forecasting

## 🐛 Troubleshooting

### Common Issues

1. **Database Connection Error**:
   - Verify MySQL service is running
   - Check database credentials in `config.php`
   - Ensure database exists and is accessible

2. **File Upload Issues**:
   - Check folder permissions for `uploads/` directory
   - Verify PHP upload settings in `php.ini`

3. **Session Issues**:
   - Clear browser cache and cookies
   - Check PHP session configuration

4. **Email Functionality**:
   - Configure SMTP settings for password reset
   - Verify mail server connectivity

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is developed for educational and demonstration purposes. Please ensure compliance with local regulations for commercial use.

## 👨‍💻 Developer Information

- **Developer**: Joyal (MACFAST)
- **Project**: Digital Dairy Management System
- **Version**: 1.0.0
- **Last Updated**: September 2025

## 📞 Support

For technical support or questions about the system:
- Check the troubleshooting section above
- Review the database schema for customization
- Ensure all prerequisites are properly installed

---

**Note**: This system is designed to modernize traditional dairy farming operations by providing digital solutions for farmers, customers, and administrators. The modular architecture allows for easy customization and expansion based on specific requirements.
