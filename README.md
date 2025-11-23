# 💰 Expense Tracker System

A comprehensive web-based expense tracking application built with PHP and MySQL. Track your daily expenses, visualize spending patterns with interactive charts, and generate detailed monthly reports.

![PHP](https://img.shields.io/badge/PHP-7.4+-blue)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-orange)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple)
![Chart.js](https://img.shields.io/badge/Chart.js-4.0-green)

## ✨ Features

### 🔐 Authentication System
- **User Registration** - Create new accounts with username, email, and password
- **Secure Login** - Password hashing using PHP's password_hash()
- **Session Management** - Secure user sessions with logout functionality

### 💸 Expense Management (CRUD)
- **Add Expenses** - Record expenses with category, amount, date, and description
- **Edit Expenses** - Update existing expense records
- **Delete Expenses** - Remove unwanted entries with confirmation
- **View Expenses** - Browse all expenses in an organized table format

### 📊 Data Visualization
- **Pie Chart** - Category-wise spending breakdown for the current month
- **Line Chart** - 6-month spending trend analysis
- **Interactive Charts** - Built with Chart.js for smooth animations

### 📈 Monthly Reports
- **Filter by Month** - View expenses for any selected month
- **Filter by Category** - Focus on specific expense categories
- **Category Breakdown** - Detailed statistics showing:
  - Total amount per category
  - Number of transactions
  - Percentage of total spending
  - Visual progress bars
- **Expense Details** - Complete list of all transactions

### 📥 CSV Export
- **Download Reports** - Export expense data to CSV format
- **Excel Compatible** - UTF-8 BOM encoding for proper Excel import
- **Filtered Exports** - Export only filtered data (by month/category)

### 🎨 User Interface
- **Responsive Design** - Bootstrap 5 framework for mobile-friendly interface
- **Modern UI** - Clean and intuitive dashboard
- **Icons** - Bootstrap Icons for visual enhancement
- **Stats Cards** - Quick overview of spending metrics

## 🛠️ Tech Stack

- **Backend:** PHP 7.4+
- **Database:** MySQL 5.7+
- **Frontend:** HTML5, CSS3, JavaScript (ES6)
- **CSS Framework:** Bootstrap 5.3
- **Charts:** Chart.js 4.0
- **Icons:** Bootstrap Icons
- **Server:** Apache (XAMPP/WAMP/LAMP)

## 📋 Prerequisites

Before you begin, ensure you have the following installed:
- XAMPP/WAMP/LAMP/MAMP (PHP 7.4+ & MySQL 5.7+)
- Web browser (Chrome, Firefox, Safari, or Edge)
- Text editor (VS Code, Sublime Text, etc.) - optional

## 🚀 Installation & Setup

### Step 1: Clone or Download the Project

```bash
# Clone the repository
git clone https://github.com/yourusername/expense-tracker-system.git

# Or download and extract the ZIP file
```

### Step 2: Move to XAMPP Directory

```bash
# Move the project folder to your XAMPP htdocs directory
# Windows: C:\xampp\htdocs\expense_tracker_system
# Linux: /opt/lampp/htdocs/expense_tracker_system
# Mac: /Applications/XAMPP/htdocs/expense_tracker_system
```

### Step 3: Start Apache & MySQL

1. Open XAMPP Control Panel
2. Start **Apache** server
3. Start **MySQL** database

### Step 4: Create Database

1. Open your browser and go to: `http://localhost/phpmyadmin`
2. Click on **"New"** in the left sidebar
3. Enter database name: `expense_tracker`
4. Click **"Create"**

### Step 5: Import Database Schema

**Option A: Using phpMyAdmin**
1. Select the `expense_tracker` database
2. Click on the **"Import"** tab
3. Click **"Choose File"** and select `database.sql`
4. Click **"Go"** to import

**Option B: Using SQL Tab**
1. Select the `expense_tracker` database
2. Click on the **"SQL"** tab
3. Copy the contents of `database.sql`
4. Paste into the SQL query box
5. Click **"Go"** to execute

### Step 6: Configure Database Connection

Open `config.php` and verify the database credentials:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // Leave empty for XAMPP default
define('DB_NAME', 'expense_tracker');
```

### Step 7: Access the Application

Open your browser and navigate to:
```
http://localhost/expense_tracker_system/
```

## 👤 Default Categories

The system comes pre-loaded with 8 expense categories:

| Icon | Category | Color |
|------|----------|-------|
| 🍔 | Food & Dining | Red |
| 🚗 | Transportation | Blue |
| 🛒 | Shopping | Green |
| 🎬 | Entertainment | Yellow |
| 💡 | Bills & Utilities | Cyan |
| ⚕️ | Healthcare | Pink |
| 📚 | Education | Purple |
| 📋 | Others | Gray |

## 📁 Project Structure

```
expense_tracker_system/
│
├── assets/
│   ├── css/
│   │   └── style.css          # Custom CSS styles
│   └── js/
│       └── script.js          # Custom JavaScript
│
├── includes/
│   ├── header.php             # Header template with navbar
│   └── footer.php             # Footer template
│
├── config.php                 # Database configuration
├── database.sql               # Database schema and initial data
│
├── login.php                  # User login page
├── register.php               # User registration page
├── logout.php                 # Logout handler
│
├── index.php                  # Dashboard with charts
├── add_expense.php            # Add new expense
├── edit_expense.php           # Edit existing expense
├── delete_expense.php         # Delete expense handler
│
├── reports.php                # Monthly reports with filters
├── export_csv.php             # CSV export handler
│
└── README.md                  # This file
```

## 🎯 Usage Guide

### 1. Register a New Account
- Navigate to the registration page
- Enter username, email, and password (min 6 characters)
- Click "Register"

### 2. Login
- Enter your username and password
- Click "Login" to access the dashboard

### 3. Add an Expense
- Click "Add Expense" in the navbar or dashboard
- Select a category
- Enter the amount
- Choose the date
- Add a description (optional)
- Click "Save Expense"

### 4. View Dashboard
- See current month's total spending
- View pie chart of category-wise expenses
- Check 6-month spending trend
- Browse recent expenses

### 5. Generate Reports
- Click "Reports" in the navbar
- Select a month using the date picker
- Filter by category (optional)
- View detailed breakdown and statistics
- Download CSV report

### 6. Export Data
- Go to Reports page
- Apply desired filters
- Click "Download CSV" button
- Open the file in Excel or any spreadsheet application

## 🔒 Security Features

- **Password Hashing** - Uses PHP's `password_hash()` with bcrypt
- **SQL Injection Prevention** - Prepared statements with parameterized queries
- **XSS Protection** - Input sanitization using `htmlspecialchars()`
- **Session Security** - Secure session management
- **CSRF Protection** - Form tokens (can be enhanced further)
- **Input Validation** - Client-side and server-side validation

## 🎨 Customization

### Change Database Credentials
Edit `config.php`:
```php
define('DB_HOST', 'your_host');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'your_database');
```

### Add Custom Categories
Insert into the `categories` table:
```sql
INSERT INTO categories (name, icon, color) 
VALUES ('Travel', '✈️', '#9c27b0');
```

### Modify Chart Colors
Edit the chart configuration in `index.php` or `reports.php`

## 🐛 Troubleshooting

### Database Connection Error
- Verify MySQL is running in XAMPP
- Check database credentials in `config.php`
- Ensure database exists in phpMyAdmin

### Blank Page/White Screen
- Enable error reporting in `php.ini`:
  ```ini
  display_errors = On
  error_reporting = E_ALL
  ```
- Check Apache error logs in XAMPP

### Charts Not Displaying
- Clear browser cache
- Check browser console for JavaScript errors
- Verify Chart.js CDN is loading

### CSV Export Not Working
- Check file permissions
- Verify PHP headers are not already sent
- Ensure no whitespace before `<?php` tags

## 📊 Database Schema

### Users Table
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- username (VARCHAR(50), UNIQUE)
- email (VARCHAR(100), UNIQUE)
- password (VARCHAR(255))
- created_at (TIMESTAMP)
```

### Categories Table
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- name (VARCHAR(50))
- icon (VARCHAR(50))
- color (VARCHAR(20))
```

### Expenses Table
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- user_id (INT, FOREIGN KEY)
- category_id (INT, FOREIGN KEY)
- amount (DECIMAL(10,2))
- description (TEXT)
- expense_date (DATE)
- created_at (TIMESTAMP)
```

## 🚀 Future Enhancements

- [ ] Budget management and alerts
- [ ] Recurring expenses
- [ ] Multiple currency support
- [ ] Email notifications
- [ ] Advanced analytics and insights
- [ ] Mobile app (React Native/Flutter)
- [ ] API for third-party integrations
- [ ] Dark mode theme
- [ ] Multi-language support
- [ ] Income tracking

## 📝 License

This project is open source and available under the [MIT License](LICENSE).

## 👨‍💻 Author

**Your Name**
- GitHub: [@yourusername](https://github.com/yourusername)
- Email: your.email@example.com

## 🙏 Acknowledgments

- Bootstrap team for the amazing CSS framework
- Chart.js team for the charting library
- Bootstrap Icons for the icon set
- PHP and MySQL communities

## 📞 Support

If you encounter any issues or have questions:
1. Check the troubleshooting section
2. Search existing GitHub issues
3. Create a new issue with detailed information
4. Contact the author

## ⭐ Show Your Support

Give a ⭐️ if this project helped you!

---

**Happy Expense Tracking! 💰📊**
