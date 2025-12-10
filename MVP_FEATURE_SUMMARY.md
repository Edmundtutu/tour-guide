# Tour Guide - PHP Tourism Platform

A role-based tourism management system built with vanilla PHP, implementing a complete booking workflow for tourists, hosts, and administrators.

## Why This Project

This platform demonstrates a well-structured PHP MVC application with proper separation of concerns, role-based access control, and integration with interactive mapping services. It's useful for understanding how to build scalable multi-role applications without heavy frameworks, while maintaining security best practices and clean architecture.

## Requirements

- PHP 7.4+
- MySQL 5.7+
- Apache with mod_rewrite enabled
- Web server (XAMPP, WAMP, MAMP, or similar)

## Installation

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd tour-guide
   ```

2. **Configure database:**
   - Edit `config/config.php` and set your database credentials:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'tour_guide');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     ```
   - Update `BASE_URL` to match your local setup:
     ```php
     define('BASE_URL', 'http://localhost/tour-guide');
     ```

3. **Import database schema:**
   ```bash
   mysql -u root -p < database/schema.sql
   mysql -u root -p tour_guide < database/demo_data_with_coordinates.sql
   ```

4. **Set up web server:**
   - Place project in your web server's document root (e.g., `htdocs` for XAMPP)
   - Ensure `.htaccess` is enabled and `mod_rewrite` is active
   - Point your browser to `http://localhost/tour-guide/public`

## Running the Application

1. Start your Apache and MySQL services
2. Navigate to `http://localhost/tour-guide/public` in your browser
3. Log in with demo credentials:
   - **Tourist:** `tourist@demo.com` / `password`
   - **Host:** `host@demo.com` / `password`
   - **Admin:** `admin@demo.com` / `password`

## Architecture Overview

- **MVC Pattern:** Controllers (`app/controllers`), Models (`app/models`), Views (`views`)
- **Service Layer:** Business logic in `app/services`
- **Core Components:** Router, Database (PDO), Authentication in `app/core`
- **Role-Based Access:** Three user types with distinct permissions and workflows
- **Security:** CSRF protection, password hashing, prepared statements

## Project Structure

```
app/
  controllers/     # Request handlers
  services/        # Business logic layer
  models/          # Data access layer
  core/            # Framework components (Router, Database, Auth)
config/            # Configuration files
database/          # SQL schema and migrations
public/            # Entry point (index.php)
views/             # HTML templates
```

## Key Features

- Multi-role authentication system (tourist, host, admin)
- Complete booking workflow with approval system
- Interactive maps with location-based search
- Hotel and destination management
- Subscription management for hosts
- Admin dashboard with analytics

This is a production-ready MVP suitable for deployment with proper environment configuration.
