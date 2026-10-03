# Point-of-Sale (POS) Database & Management System

**Course:** IT0049 (Web System Technologies)  
**Assessment:** Technical Formative Assessment 4 (TFA4) — Sessions and Authentication  
**Framework:** CodeIgniter 4 (v4.7.4)  
**Hosted Application:** [https://tfa4.freedev.app](https://tfa4.freedev.app)  
**GitHub Repository:** [https://github.com/aniciete/TFA4](https://github.com/aniciete/TFA4)  

---

## 1. Project Overview

This project extends the Point-of-Sale (POS) application with session-backed staff authentication, protected account-management routes, record creation and editing workflows, robust server-side validation, and user avatar image processing. Built strictly following CodeIgniter 4 MVC architecture, database persistence uses CodeIgniter 4 Models and the Query Builder without raw SQL, and all user input is sanitized before output with `esc()`.

### Required Pages and Route Table

| URL Route | Method | Controller Action | Model Used | View Template | Purpose |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `/` | `GET` | `App\Controllers\Pages::home` | `CustomerModel`, `UserModel` | `pages/home.php` | POS application landing page with live record counters and navigation |
| `/about` | `GET` | `App\Controllers\Pages::about` | N/A | `pages/about.php` | Operational procedure and database model architecture overview |
| `/login` | `GET` | `App\Controllers\Auth::login` | N/A | `auth/login.php` | Renders the public staff login form |
| `/login` | `POST` | `App\Controllers\Auth::attempt` | `UserModel` | `auth/login.php` | Verifies the username and hashed password, then starts a session |
| `/logout` | `POST` | `App\Controllers\Auth::logout` | N/A | N/A | Destroys the session and redirects to the login page |
| `/customers` | `GET` | `App\Controllers\Customers::index` | `CustomerModel` | `customers/index.php` | Customer directory listing records with links to create and edit accounts |
| `/customers/new` | `GET` | `App\Controllers\Customers::new` | `CustomerModel` | `customers/form.php` | Renders blank form to register a new customer account |
| `/customers/new` | `POST` | `App\Controllers\Customers::create` | `CustomerModel` | `customers/form.php` | Validates and inserts new customer record; redirects with flash message |
| `/customers/edit/(:num)` | `GET` | `App\Controllers\Customers::edit` | `CustomerModel` | `customers/form.php` | Renders edit form pre-populated with customer record; 404 if missing |
| `/customers/edit/(:num)` | `POST` | `App\Controllers\Customers::update` | `CustomerModel` | `customers/form.php` | Validates and updates existing customer record; preserves `created_at` |
| `/users` | `GET` | `App\Controllers\Users::index` | `UserModel` | `users/index.php` | User directory listing accounts with avatar thumbnails, usernames, and edit links |
| `/users/new` | `GET` | `App\Controllers\Users::new` | `UserModel` | `users/form.php` | Renders blank multipart form for creating staff user account with avatar |
| `/users/new` | `POST` | `App\Controllers\Users::create` | `UserModel` | `users/form.php` | Validates credentials and profile data, processes avatar upload/resize, and inserts user account |
| `/users/edit/(:num)` | `GET` | `App\Controllers\Users::edit` | `UserModel` | `users/form.php` | Renders edit multipart form pre-populated with user data and avatar preview |
| `/users/edit/(:num)` | `POST` | `App\Controllers\Users::update` | `UserModel` | `users/form.php` | Validates, updates user record, and replaces or preserves avatar image |

---

## 2. Technical Stack & Prerequisites

- **Language:** PHP 8.2 or higher (tested on PHP 8.5)
- **Required PHP Extensions:**
  - `intl` (internationalization)
  - `mbstring` (multibyte string handling)
  - `json` (data encoding)
  - `curl` (HTTP requests)
  - `mysqli` (MySQL database connection)
  - `gd` (image processing and avatar resizing)
  - `pdo_sqlite` (for PHPUnit in-memory test database)
- **Package Manager:** Composer 2.x
- **Framework:** CodeIgniter 4.7.4
- **Database:** MySQL 8.x / MariaDB 10.x (Local & Production), SQLite3 (in-memory for automated tests)

---

## 3. Database Schema & Setup

The database schema and sample records are defined in [`database/tfa4_pos.sql`](database/tfa4_pos.sql) and mirrored in CodeIgniter database migrations in [`app/Database/Migrations/`](app/Database/Migrations/).

### 3.1 Relational Schemas

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
  password VARCHAR(255) NOT NULL,
  full_name VARCHAR(100) NOT NULL,
  avatar VARCHAR(255) NULL,
  created_at DATETIME NOT NULL
);
```

### 3.2 Importing the Database

To create and populate the `tfa4_pos` database locally using the MySQL CLI:

```bash
mysql -u root -p < database/tfa4_pos.sql
```

Or via phpMyAdmin / GUI:
1. Open phpMyAdmin.
2. Click **Import**.
3. Choose `database/tfa4_pos.sql`.
4. Click **Go**.

Alternatively, run database migrations and seeders via Spark:
```bash
php spark migrate
php spark db:seed Tfa4Seeder
```

---

## 4. Local Development Setup

### 4.1 Clone the Repository
```bash
git clone https://github.com/aniciete/TFA4.git
cd TFA4
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
database.default.database = tfa4_pos
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.DBPrefix = 
database.default.port = 3306
```

### 4.4 Avatar Storage Permissions
Ensure the avatar upload directory exists and is writable:
```bash
mkdir -p public/uploads/avatars
chmod 755 public/uploads/avatars
```

### 4.5 Start the Local Development Server
```bash
php spark serve
```
Access the application locally at: **[http://localhost:8080](http://localhost:8080)**

---

## 5. Architectural & Data Design

### 5.1 Models and Validation Rules

In accordance with CodeIgniter 4 best practices, models encapsulate table metadata, allowed fields, and validation rules:

- **`App\Models\CustomerModel`**:
  - Table: `customers`, Primary Key: `id`.
  - Allowed fields: `full_name`, `email`, `phone`, `created_at`.
  - Rules:
    - `full_name`: `required|min_length[2]|max_length[100]`
    - `email`: `required|max_length[100]|valid_email`
    - `phone`: `permit_empty|max_length[20]`

- **`App\Models\UserModel`**:
  - Table: `users`, Primary Key: `id`.
  - Allowed fields: `username`, `password`, `full_name`, `avatar`, `created_at`.
  - Rules:
    - `full_name`: `required|min_length[2]|max_length[100]`
    - `username`: `required|min_length[3]|max_length[50]|is_unique[users.username,id,{id}]`
    - `avatar`: `permit_empty|max_length[255]`
    - `password`: stored only after hashing with `password_hash()`

### 5.2 Sessions and Authentication

- Staff authenticate through `/login` with a username and password.
- The stored password hash is verified with `password_verify()`; plaintext passwords are never stored or rendered.
- Successful login regenerates the session ID and stores the authenticated user ID, username, and display name in the session.
- The custom `App\Filters\AuthFilter` is registered as the `auth` filter and applied to every customer and user account route.
- Logged-out access redirects to `/login`; `/logout` destroys the session and redirects back to the login page.
- Seeded demo accounts all use the password **`TFA4Demo!2026`**. Change this before using the application with real staff accounts.

### 5.3 Server-Side Validation & Form Handling

- **Dual-Layer Validation:** Inputs are validated at both the controller layer (providing immediate redirect back with input and field error mapping) and the model layer (preventing invalid persistence).
- **Error Feedback:** Form views render both a high-level error summary banner at the top of the card and inline contextual error messages beneath offending input fields.
- **Input Preservation:** Forms use `old('field_name', $fallback)` to preserve user-entered values upon validation rejection, preventing data loss.
- **Record Preservation:**
  - `created_at` timestamp is generated strictly on record creation (`create()`) using `date('Y-m-d H:i:s')`.
  - When updating (`update()`), existing `created_at` timestamps are never modified.

### 5.4 User Avatar Upload & Processing

- **Multipart Encoding:** Forms handling avatars declare `enctype="multipart/form-data"`.
- **Validation Constraints:**
  - Avatar upload is strictly optional on both create and edit.
  - Allowed MIME types: `image/jpg`, `image/jpeg`, `image/png`.
  - File extension check: `is_image[avatar]`.
  - Maximum upload size: `2048 KB` (2 MB).
- **Storage & Security:**
  - Files are stored with cryptographically secure random names generated via `$file->getRandomName()`.
  - Target directory: `public/uploads/avatars/`. Direct execution of uploaded scripts is prevented.
- **Image Resizing:**
  - Uploaded images are resized on the server using CodeIgniter's Image Service (`service('image')`) powered by the PHP GD extension:
    ```php
    service('image')
        ->withFile($savedFilePath)
        ->resize(256, 256, true, 'auto')
        ->save($savedFilePath);
    ```
  - Proportional aspect ratio is strictly maintained with auto-master dimension calculation.
- **Edit Behavior:**
  - Uploading a new image during edit updates the record to point to the newly resized file.
  - Submitting the edit form without selecting a new file preserves the existing avatar.
- **Fallback Placeholder:**
  - If a user has no avatar set (`avatar` is null or the file does not exist on disk), the UI renders an artisan SVG placeholder (`/assets/images/avatar-placeholder.svg`).

---

## 6. Running Automated Tests

Automated testing uses CodeIgniter's in-memory SQLite test connection (`:memory:`), isolating test runs from local MySQL:

```bash
vendor/bin/phpunit
```

The test suite covers the POS workflows and authentication requirements:
- **`HealthTest`**: Validates environment and critical framework paths.
- **`ModelsTest`**: Validates model configuration, primary keys, allowed fields, schema rules, and duplicate/unique username handling on insert and update.
- **`PosFoundationsTest`**: Validates landing page counters, about page, and static list pages.
- **`CustomerWorkflowTest`**:
  - Tests rendering of blank customer create form.
  - Tests validation failure scenarios (empty inputs, invalid emails, length limits) and input preservation via `old()`.
  - Tests successful customer creation, database persistence, and `created_at` generation.
  - Tests edit form retrieval, record pre-population, and 404 handling on missing record IDs.
  - Tests customer record update with preservation of the original `created_at` timestamp.
- **`UserWorkflowTest`**:
  - Tests rendering of blank user create form with multipart encoding.
  - Tests validation failure scenarios (missing name, missing username, duplicate username) and input preservation.
  - Tests unique username enforcement: allowing same username when updating own record, rejecting collisions with other users.
  - Tests valid avatar upload: validates saving to `public/uploads/avatars/`, verifies aspect-ratio-preserving resize to max 256×256 pixels.
  - Tests avatar validation rejections: non-image files and files exceeding 2 MB.
  - Tests avatar preservation when updating user text attributes without providing a replacement image.
  - Tests 404 handling on edit/update requests targeting non-existent user IDs.
- **`AuthWorkflowTest`**:
  - Tests login form rendering and invalid credential rejection.
  - Tests hashed-password verification, session creation, and authenticated access.
  - Tests filter redirects for logged-out customer and user routes.
  - Tests logout session destruction and redirect behavior.

---

## 7. InfinityFree Deployment Procedure

This repository includes an automated packaging script for InfinityFree Apache hosting:

1. Generate a lean production build archive:
   ```bash
   ./build-infinityfree-zip.sh
   ```
2. In the InfinityFree **Control Panel (VistaPanel)**:
   - Create the MySQL database `if0_43075015_tfa4`.
   - Open **phpMyAdmin** for the new database and import `database/tfa4_pos.sql`.
   - Ensure the PHP version is set to **PHP 8.2** or **PHP 8.3**.
3. Open the **Online File Manager** and navigate into `htdocs/` (or `tfa4.freedev.app/htdocs/`).
4. Delete any default placeholder files (`index2.html` or `default.php`).
5. Upload `tfa4-infinityfree.zip` and select **Extract**.
6. Edit the extracted `.env` file with your VistaPanel MySQL host, username, database, and password:
   ```ini
   database.default.hostname = sql105.infinityfree.com
   database.default.database = if0_43075015_tfa4
   database.default.username = if0_43075015
   database.default.password = your_vpanel_password
   ```
7. Verify permissions on `public/uploads/avatars/` to ensure the web server can store uploaded images.
8. Verify the live site at: **[https://tfa4.freedev.app](https://tfa4.freedev.app)**.
