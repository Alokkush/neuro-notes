# Online Notes Sharing System

A complete web application for students to share and access educational materials. This system allows students to upload, download, and manage educational notes while providing administrators with tools to manage users and content.

## Table of Contents
- [Features](#features)
- [Technologies Used](#technologies-used)
- [System Requirements](#system-requirements)
- [Installation](#installation)
- [Database Setup](#database-setup)
- [Default Admin Account](#default-admin-account)
- [Project Structure](#project-structure)
- [Security Features](#security-features)
- [Usage](#usage)
- [File Upload Rules](#file-upload-rules)
- [Component-Based Architecture](#component-based-architecture)
- [Contributing](#contributing)
- [License](#license)

## Features

- User Registration and Authentication
- Role-based access control (Student/Admin)
- Note upload, download, and management
- Search and filtering capabilities
- Admin panel for system management
- Responsive design using Bootstrap 5

## Technologies Used

- **Frontend**: HTML5, CSS3, Bootstrap 5, JavaScript
- **Backend**: PHP 8+
- **Database**: MySQL
- **Server**: XAMPP (Apache + MySQL)

## System Requirements

- XAMPP installed
- PHP 8.0 or higher
- MySQL 5.7 or higher

## Installation

1. Clone or download this repository to your XAMPP `htdocs` folder
2. Start Apache and MySQL services in XAMPP
3. Create a database named `online_notes_db`
4. Import the `database.sql` file into your database
5. Access the application at `http://localhost/notes-sharing`

## Database Setup

1. Open PHPMyAdmin at `http://localhost/phpmyadmin`
2. Create a new database named `online_notes_db`
3. Select the database and click on the "Import" tab
4. Choose the `database.sql` file from this project
5. Click "Go" to import the database structure and sample data

## Default Admin Account

- **Email**: admin@example.com
- **Password**: admin123

## Project Structure

```
notes-sharing/
├── assets/                     # Static assets (CSS, JS, images)
│   └── style.css              # Custom styling
├── components/                # Reusable UI components
│   ├── navbar.php             # Navigation bar component
│   └── footer.php             # Footer component
├── documentation/             # Project documentation
│   └── README.md              # This file
├── includes/                  # Configuration and utility files
│   ├── config.php             # Database configuration
│   └── security.php           # Security functions
├── user/                      # User authentication and management
│   ├── login.php              # User login
│   ├── register.php           # User registration
│   └── logout.php             # User logout
├── notes/                     # Note management functionality
│   ├── upload.php             # Note upload
│   ├── mynotes.php            # Manage user's notes
│   ├── edit_note.php          # Edit note
│   ├── delete_note.php        # Delete note
│   ├── search.php             # Search notes
│   └── download.php           # Download notes
├── admin/                     # Admin panel functionality
│   ├── admin.php              # Admin panel
│   ├── delete_user.php        # Delete user (admin)
│   └── delete_note_admin.php  # Delete note (admin)
├── uploads/                   # Uploaded files storage
├── index.php                  # Home page
├── dashboard.php              # User dashboard
└── database.sql               # Database schema
```

## Security Features

- Password hashing using PHP's `password_hash()` function
- Prepared statements to prevent SQL injection
- CSRF protection tokens
- Input validation and sanitization
- Session management
- File upload validation
- XSS prevention
- Role-based access control (Student/Admin)
- Secure file storage with unique naming
- Session timeout protection

## Usage

### For Students
1. **Registration**: Students can register with their details (name, email, course, semester)
2. **Login**: Users can login with their credentials
3. **Dashboard**: View recent notes and your uploaded notes
4. **Upload Notes**: Upload new educational materials with title, description, and subject
5. **My Notes**: Manage your uploaded notes (edit or delete)
6. **Search**: Find notes by title, description, or subject

### For Administrators
1. **Admin Login**: Access the admin panel using admin credentials
2. **User Management**: View, delete, and manage all registered users
3. **Note Management**: View and delete any uploaded notes
4. **Statistics**: View system statistics (total users, notes, downloads)

## File Upload Rules

- Allowed file types: PDF, DOC, DOCX, PPT, PPTX
- Maximum file size: 10MB
- Files are stored in the `uploads/` directory
- File names are secured to prevent conflicts

## Component-Based Architecture

This project implements a component-based architecture to improve maintainability and reduce code duplication:

- **Components Directory**: Contains reusable UI elements
  - `navbar.php`: Unified navigation across all pages
  - `footer.php`: Consistent footer across all pages

### Benefits
- Reduced code duplication across pages
- Easier maintenance (changes only need to be made in one place)
- Consistent UI across all pages
- Cleaner, more organized code

### Usage Example
```php
<?php
$dashboardPath = 'dashboard.php';
$uploadPath = 'notes/upload.php';
$mynotesPath = 'notes/mynotes.php';
$searchPath = 'notes/search.php';
$showAdmin = isAdmin();
$adminPath = 'admin/admin.php';
$logoutPath = 'user/logout.php';
?>
<?php include 'components/navbar.php'; ?>
<!-- Page content -->
<?php include 'components/footer.php'; ?>
```

## Contributing

This project is for educational purposes. Feel free to fork and improve it.

## License

This project is open source and available under the MIT License.
