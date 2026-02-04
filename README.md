# Employee Welfare Portal

**PayMarket Pro** is an integrated Employee Welfare Portal that combines payroll management and a marketplace system into a single, unified platform. Built with vanilla PHP and Tailwind CSS, this system enables organizations to process employee payments and provide an internal marketplace where employees can spend their earnings.

---

## 🎯 Overview

This portal provides two main modules:

### 1. **Payroll Module**

- **Admin Features:**
  - Manage employee accounts
  - Log work hours and process payroll
  - Update hourly rates
  - Create new user accounts
  - View comprehensive employee statistics

- **Employee Features:**
  - View current wallet balance
  - Access payslip history with detailed breakdowns
  - Print professional payslips (print-ready formatting)
  - Track earnings, taxes, and net pay

### 2. **Marketplace Module**

- **Admin Features:**
  - Add and manage products
  - Set pricing and manage inventory
  - View product catalog

- **Employee Features:**
  - Browse available products
  - Add items to shopping cart
  - Purchase products using wallet balance
  - View order confirmation and history

---

## 🚀 Features

### Core Features

- **Self-Healing Authentication:** System automatically creates emergency admin/user accounts when needed
- **Role-Based Access Control:** Separate admin and employee interfaces
- **Wallet System:** Integrated balance management with automatic payroll deposits
- **Transaction Engine:** Real-time balance deduction during marketplace purchases
- **Print-Ready Payslips:** Professional payslip view with CSS print formatting (hides navigation/footer)
- **JSON File Storage:** No database required - all data stored in simple JSON files
- **Responsive Design:** Mobile-friendly interface using Tailwind CSS
- **Nigerian Naira (₦) Currency:** All financial transactions displayed in Naira

### Security Features

- Session-based authentication
- Password hashing with bcrypt
- Role verification on protected pages
- Input sanitization and validation
- Emergency login credentials for system recovery

---

## 📋 System Requirements

- **Web Server:** Apache (XAMPP, WAMP, MAMP, or similar)
- **PHP Version:** 8.0 or higher
- **Browser:** Modern browser with JavaScript enabled
- **Storage:** Minimal - JSON files only

---

## 📦 Installation

### Step 1: Extract Files

1. Download and extract the project ZIP file
2. Copy the `payroll-and-marketplace` folder to your web server's document root:
   - **XAMPP:** `C:\xampp\htdocs\`
   - **WAMP:** `C:\wamp64\www\`
   - **MAMP:** `/Applications/MAMP/htdocs/`
   - or equivalent for your setup

### Step 2: Run Setup Script

1. Start your Apache server (through XAMPP/WAMP/MAMP control panel)
2. Open your browser and navigate to:
   ```
   http://localhost/payroll-and-marketplace/setup.php
   ```
3. Click the **"Initialize Database"** button
4. Wait for the success message confirming data files were created

### Step 3: Access the Portal

Once setup is complete, you can access the portal at:

```
http://localhost/payroll-and-marketplace/
```

---

## 🔐 Login Credentials

### Emergency Logins

The system includes **self-healing authentication** that automatically creates these accounts if data files are missing or corrupted:

**Administrator Account:**

- Email: `admin@portal.com`
- Password: `admin123`
- Role: Admin (full access to all features)

**Standard Employee Account:**

- Email: `user@portal.com`
- Password: `user123`
- Role: Employee (payroll and marketplace access)

> **💡 Important:** These emergency logins are hardcoded into the system and will auto-create user accounts on first login if they don't exist in the database. This ensures you always have access to the system even if data files are deleted or corrupted.

### Default Seeded Accounts

After running `setup.php`, the following demo accounts are also available:

- **Admin:** `admin@company.com` / `password123`
- **Employee:** `john@company.com` / `password123`

---

## 📁 Project Structure

```
payroll-and-marketplace/
├── index.php                    # Landing page with team information
├── setup.php                    # Database initialization script
├── reset_data.php              # Data reset utility (use with caution)
├── README.md                   # This file
│
├── auth/                       # Authentication module
│   ├── login.php              # Login with self-healing auth
│   └── logout.php             # Session destruction
│
├── includes/                   # Shared components
│   ├── config.php             # Application constants
│   ├── session.php            # Authentication helpers
│   ├── functions.php          # Utility functions
│   ├── header.php             # Navigation bar
│   └── footer.php             # Page footer
│
├── data/                       # JSON data storage
│   ├── users.json             # User accounts and balances
│   ├── products.json          # Marketplace inventory
│   ├── time_entries.json      # Payroll records
│   └── orders.json            # Purchase history
│
├── payroll/                    # Payroll module
│   ├── index.php              # Module router
│   ├── admin/                 # Admin interfaces
│   │   ├── dashboard.php      # Employee management
│   │   ├── time_entry.php     # Hours logging & balance update
│   │   ├── edit_rate.php      # Hourly rate management
│   │   └── add_user.php       # User creation form
│   └── employee/              # Employee interfaces
│       ├── dashboard.php      # Balance & payslip history
│       └── payslip.php        # Individual printable payslip
│
└── marketplace/                # Marketplace module
    ├── index.php              # Module router
    ├── admin/                 # Admin interfaces
    │   ├── manage_products.php
    │   └── add_product.php
    └── shop/                  # Employee interfaces
        ├── products.php       # Product catalog
        ├── add_to_cart.php   # Cart handler
        ├── cart.php          # Shopping cart
        ├── checkout.php      # Transaction engine
        └── success.php       # Order confirmation
```

---

## 🎨 Key Workflows

### Admin: Process Payroll

1. Login as admin
2. Navigate to **Payroll Dashboard**
3. Click **"Log Hours"** for an employee
4. Enter hours worked, month, and year
5. Submit - system automatically:
   - Calculates gross pay (hours × rate)
   - Deducts 10% tax
   - Adds net pay to employee wallet balance
   - Creates payslip record

### Employee: Make a Purchase

1. Login as employee
2. View wallet balance on **Payroll Dashboard**
3. Click **"Shop in Marketplace"**
4. Browse products and click **"Add to Cart"**
5. View **Shopping Cart**
6. Click **"Proceed to Checkout"**
7. System verifies sufficient balance and processes:
   - Deducts total from wallet
   - Decrements product stock
   - Creates order record
   - Shows confirmation

### Employee: Print Payslip

1. Login as employee
2. View **Payroll Dashboard**
3. Find desired payslip in history table
4. Click **"View/Print"** action link
5. Review detailed payslip breakdown
6. Click **"Print Payslip"** button
7. Use browser print dialog (Ctrl+P / Cmd+P)
   - Navigation and footer automatically hidden
   - Only payslip content appears on printed page

---

## 🛠️ Troubleshooting

### Issue: "Cannot connect to database" or "File not found"

**Solution:** Run the setup script again:

```
http://localhost/payroll-and-marketplace/setup.php
```

### Issue: Login credentials not working

**Solution:** Use the **Emergency Logins**:

- Admin: `admin@portal.com` / `admin123`
- Employee: `user@portal.com` / `user123`

These accounts are hardcoded and will self-create on first login.

### Issue: Data files are corrupted or deleted

**Solution:**

1. Navigate to `reset_data.php` in your browser
2. Click **"Reset Data"** button to wipe and re-seed all JSON files
3. **Warning:** This deletes all existing users, products, payslips, and orders

### Issue: Page styling is broken

**Solution:**

- Ensure internet connection is active (Tailwind CSS loads from CDN)
- Check browser console for blocked CDN resources
- Try clearing browser cache (Ctrl+Shift+Delete)

### Issue: Employee balance not updating after payroll

**Solution:**

- Verify the time entry was successfully submitted
- Check `data/users.json` - balance field should reflect new amount
- Ensure JSON file has write permissions (chmod 664 on Linux/Mac)

### Issue: Cart items disappear after logout

**Solution:** This is expected behavior - cart is session-based and clears on logout for security

### Issue: Product stock goes negative

**Solution:**

- Run `reset_data.php` to restore default inventory
- Future purchases will be blocked if stock reaches zero

---

## 🔧 Configuration

### Changing Application Constants

Edit `includes/config.php`:

```php
define('APP_NAME', 'PayMarket Pro');        // Application name
define('BASE_URL', '/payroll-and-marketplace'); // Installation directory
define('TAX_RATE', 0.10);                   // Tax rate (10%)
```

### Modifying Emergency Login Credentials

Edit `auth/login.php`, find the `$hardcoded_credentials` array:

```php
$hardcoded_credentials = [
    'admin@portal.com' => [
        'password' => 'admin123',    // Change admin password
        'name' => 'System Admin',
        'role' => 'admin',
        // ...
    ],
    // ...
];
```

> **⚠️ Security Note:** In production, remove or change these hardcoded credentials and use stronger passwords.

---

## 👥 Development Team

This project was developed by:

1. **Olanrewaju Yusuf Damola** (19/2382)
2. **Damilola Sofowora** (11/2490)
3. **Blessing Joseph Akinade** (19/2287)
4. **Adediran Adeola Beatrice** (19/2341)
5. **Opeyemi Dorcas Elizabeth** (19/2327)
6. **Tolulope Mustapha** (18/2315)
7. **Ekpah Matthew Ushie** (18/2302)
8. **Elusanya Ewaoluwa Rachel** (19/2297)
9. **Olusegun Daniel Opeyemi** (19/2293)
10. **Bamidele Oluwaseun Emmanuella** (19/2339)
11. **Olalekan Lateef Bisola** (18/2337)

---

## 📄 License

This is a student project developed for educational purposes. Free to use and modify.

---

## 🆘 Support

For issues or questions:

- Review this README thoroughly
- Check the **Troubleshooting** section above
- Use **Emergency Logins** if locked out
- Run `reset_data.php` to restore default state

---

## 🔄 Data Management

### Backup Your Data

To backup current data, copy these files:

- `data/users.json`
- `data/products.json`
- `data/time_entries.json`
- `data/orders.json`

### Reset to Default State

Run `reset_data.php` to wipe all data and restore factory defaults (includes warning prompt).

---

**Built with ❤️ using Vanilla PHP & Tailwind CSS**
