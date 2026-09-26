# Point-of-Sale (POS) Database

**Course:** IT0049 (Web System Technologies)  
**Assessment:** Technical Formative Assessment 3 (TFA3)  
**Framework:** CodeIgniter 4 (v4.7.4)  
**Hosted Application:** [https://tfa3.freedev.app](https://tfa3.freedev.app)  
**GitHub Repository:** [https://github.com/aniciete/TFA3](https://github.com/aniciete/TFA3)  

---

## 1. Project Overview

This project provides the Point-of-Sale (POS) application foundation backed by a relational MySQL database. CodeIgniter 4 Models wrap the database tables and leverage Query Builder methods (`findAll()`, `orderBy()`) to retrieve data cleanly without writing raw SQL.

### Required Pages and Route Table

| URL Route | Controller Action | Model Used | View Template | Purpose |
| :--- | :--- | :--- | :--- | :--- |
| `/` | `App\Controllers\Pages::home` | N/A | `pages/home.php` | POS application landing page with architectural overview and quick links |
| `/about` | `App\Controllers\Pages::about` | N/A | `pages/about.php` | Operational procedure and database model architecture overview |
| `/customers` | `App\Controllers\Customers::index` | `CustomerModel` | `customers/index.php` | Customer accounts directory listing full name, email, phone, and record count |
| `/users` | `App\Controllers\Users::index` | `UserModel` | `users/index.php` | Staff user accounts directory listing username, full name, and created date |

---

## 2. Technical Stack & Prerequisites

- **Language:** PHP 8.2 or higher (tested on PHP 8.5)
- **Required PHP Extensions:** `intl`, `mbstring`, `json`, `curl`, `mysqli`, `pdo_sqlite` (for PHPUnit)
- **Package Manager:** Composer 2.x
- **Framework:** CodeIgniter 4.7.4
- **Database:** MySQL 8.x / MariaDB 10.x (Local & Production), SQLite3 (in-memory for automated tests)

---

## 3. Database Schema & Setup

The database schema and sample records are defined in [`database/tfa3_pos.sql`](database/tfa3_pos.sql):

### 3.1 Schemas

```sql
CREATE TABLE customers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  phone VARCHAR(20),
  created_at DATETIME NOT NULL
);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  full_name VARCHAR(100) NOT NULL,
  created_at DATETIME NOT NULL
);
```

### 3.2 Importing the Database

To create and populate the `tfa3_pos` database locally using the MySQL CLI:

```bash
mysql -u root -p < database/tfa3_pos.sql
```

Or via phpMyAdmin / GUI:
1. Open phpMyAdmin.
2. Click **Import**.
3. Choose `database/tfa3_pos.sql`.
4. Click **Go**.

---

## 4. Local Development Setup

### 4.1 Clone the Repository
```bash
git clone https://github.com/aniciete/TFA3.git
cd TFA3
```

### 4.2 Install Dependencies
```bash
composer install
```

### 4.3 Configure Environment File
Copy the example environment template to `.env`:
```bash
cp env .env
```

Ensure the following variables are configured in `.env` for your local MySQL server:
```ini
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost:8080/'
app.indexPage = ''

database.default.hostname = localhost
database.default.database = tfa3_pos
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.DBPrefix = 
database.default.port = 3306
```

### 4.4 Start the Local Development Server
```bash
php spark serve
```
Access the application locally at: **[http://localhost:8080](http://localhost:8080)**

---

## 5. Architectural & Data Design

### 5.1 Models and Query Builder
In accordance with POS specifications:
- `App\Models\CustomerModel`:
  - Maps to table `customers`.
  - Primary key: `id`.
  - Return type: `array`.
  - Allowed fields: `full_name`, `email`, `phone`, `created_at`.
- `App\Models\UserModel`:
  - Maps to table `users`.
  - Primary key: `id`.
  - Return type: `array`.
  - Allowed fields: `username`, `full_name`, `created_at`.

### 5.2 Controllers
Controllers instantiate models and fetch data using Query Builder methods without raw SQL:
- `Customers::index()`:
  ```php
  $customers = (new CustomerModel())->orderBy('id', 'ASC')->findAll();
  ```
- `Users::index()`:
  ```php
  $users = (new UserModel())->orderBy('id', 'ASC')->findAll();
  ```

### 5.3 Views & Sanitization
Views receive `$customers` and `$users` arrays, iterating over records using PHP `foreach` and sanitizing all output with `esc()` to prevent XSS.

---

## 6. Running Automated Tests

Automated testing uses CodeIgniter's in-memory SQLite test connection (`:memory:`), isolating test runs from local MySQL:

```bash
vendor/bin/phpunit
```

The test suite validates:
- System and path health (`HealthTest`).
- Model data retrieval, primary keys, schema field constraints, and absence of deprecated role columns (`ModelsTest`).
- HTTP 200 responses across all four routes with database-backed record rendering (`PosFoundationsTest`).
- Strict 404 routing enforcement when auto-routing is disabled.

---

## 7. InfinityFree Deployment Procedure

This repository includes an automated packaging script for InfinityFree Apache hosting:

1. Generate a lean production build archive:
   ```bash
   ./build-infinityfree-zip.sh
   ```
2. In the InfinityFree **Control Panel (VistaPanel)**:
   - Create a MySQL database (e.g. `if0_XXXXXX_tfa3`).
   - Open **phpMyAdmin** for the new database and import `database/tfa3_pos.sql`.
   - Ensure the PHP version is set to **PHP 8.2** or **PHP 8.3**.
3. Open the **Online File Manager** and navigate into `htdocs/` (or `tfa3.freedev.app/htdocs/`).
4. Delete any default placeholder files (`index2.html` or `default.php`).
5. Upload `tfa3-infinityfree.zip` and select **Extract**.
6. Edit the extracted `.env` file with your VistaPanel MySQL host, username, database, and password:
   ```ini
   database.default.hostname = sqlXXX.infinityfree.com
   database.default.database = if0_XXXXXX_tfa3
   database.default.username = if0_XXXXXX
   database.default.password = your_vpanel_password
   ```
7. Verify the live site at: **[https://tfa3.freedev.app](https://tfa3.freedev.app)**.
