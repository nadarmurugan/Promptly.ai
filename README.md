<img src="https://r2cdn.perplexity.ai/pplx-full-logo-primary-dark%402x.png" style="height:64px;margin-right:32px"/>

# Promptly.ai - AI-Powered Prompt Marketplace

Welcome to the official documentation for Promptly.ai, a dynamic web application built to serve as a curated marketplace for high-quality AI prompts. This platform allows users to discover, copy, and utilize prompts for various generative AI models, while providing a comprehensive administrative backend for easy management.
The entire application is built with a focus on performance and security, using core PHP for server-side logic, MySQL for robust data storage, and Tailwind CSS for a modern, responsive user interface.

✨ Core Features
Promptly.ai is divided into two main components: the user-facing website and the administrative dashboard.

User-Facing Features
Curated Prompt Library: A beautiful gallery of AI prompts, each with an image, title, and detailed description.
One-Click Copy: Users can instantly copy any prompt text to their clipboard with a single click. The backend logs the copy count for analytics.
Like System: An asynchronous "like" functionality allows users to like and unlike prompts. The like count is updated in real-time without a page reload.
Smart Search \& Filtering: The platform is designed for easy discovery with smart search and filtering capabilities (functionality can be expanded).
Responsive Design: A fully responsive and mobile-first interface built with Tailwind CSS ensures a seamless experience on all devices.
Dynamic Contact Form: A non-blocking contact form that submits data via AJAX, providing instant feedback to the user without interrupting their experience.
Interactive Map: A Leaflet.js map integration in the contact section to display a physical location.
Administrative Dashboard
Secure Admin Login: A session-based authentication system protects the admin panel from unauthorized access.
Prompt Management (CRUD): Admins can Create, Read, Update, and Delete prompts in the library.
Contact Message Management: View and delete messages submitted through the contact form.
Admin User Management (CRUD): Admins can add new admin users, edit their credentials, and delete them. A safeguard is in place to prevent deleting the last admin.
Centralized Dashboard: A single-page view that provides a summary of all prompts, contact messages, and admin users for easy oversight.
🚀 Technology Stack
This project leverages a classic, high-performance web stack:

Backend: Core PHP (for fast server-side processing)
Database: MySQL (managed via PDO for secure database operations)
Frontend: HTML, Tailwind CSS, Vanilla JavaScript
APIs: A custom RESTful API handles asynchronous requests for features like liking prompts, copying, and form submissions.
Libraries: Leaflet.js for the interactive map.
📁 File Structure
The project is organized into a logical and scalable directory structure:

/promptly-ai
│
├── admin/ \# Contains all admin panel files
│ ├── index.php \# Main admin dashboard
│ ├── login.php \# Admin login page
│ ├── logout.php
│ ├── add_prompt.php
│ ├── delete_prompt.php
│ └── ... (other CRUD files for contacts, admins)
│
├── api/ \# Backend API endpoints for AJAX calls
│ ├── get_prompts.php
│ ├── toggle_like.php
│ ├── copy_prompt.php
│ ├── check_likes.php
│ └── ... (other API files)
│
├── assets/ \# Static files
│ ├── css/
│ ├── images/
│ └── js/
│ └── main.js \# Core frontend JavaScript logic
│
├── includes/ \# Reusable PHP components
│ ├── db.php \# Database connection setup (PDO)
│ ├── header.php
│ ├── footer.php
│ └── functions.php \# Core helper functions
│
├── pages/ \# Additional public-facing pages
│ └── prompts.php \# The full prompt library page
│
├── index.php \# Main landing page
└── README.md \# This file
⚙️ How It Works: The Workflow
The application's logic is centered around a client-server architecture where the frontend JavaScript communicates with the PHP backend to create a dynamic user experience.

1. Secure Database Connection
The foundation of the application is the secure database connection established in includes/db.php. It uses PHP Data Objects (PDO) with prepared statements to prevent SQL injection attacks.

// includes/db.php
\$host = 'localhost';
\$db = 'promptly_db';
\$user = 'root';
\$pass = '';
\$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
\$options = [
PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
PDO::ATTR_EMULATE_PREPARES => false,
];

try {
$pdo = new PDO($dsn, \$user, \$pass, \$options);
} catch (\PDOException $e) {
throw new \PDOException($e->getMessage(), (int)\$e->getCode());
}
2. Asynchronous "Like" Functionality
This is a key feature that demonstrates the dynamic nature of the app.

Client-Side (JS): When a user clicks the "like" button, an event listener in assets/js/main.js triggers a fetch request to the /api/toggle_like.php endpoint. It sends the prompt_id and the action ('like' or 'unlike').
Server-Side (PHP): The toggle_like.php script receives the request.
It starts a database transaction to ensure data integrity.
It either INSERTs or DELETEs a record in the prompt_likes table.
It then queries the database to get the new total like count for that prompt.
Finally, it commits the transaction and returns a JSON response to the client with the new like count.
Client-Side Update: The JavaScript receives the JSON response and dynamically updates the like count on the page without requiring a refresh.
3. Dynamic Toast Notifications
The assets/js/main.js file includes a utility function showToastNotification() that dynamically creates and injects a notification element into the DOM. This provides non-intrusive feedback for actions like copying a prompt. The toast is styled with Tailwind CSS and automatically removes itself after a set duration.

// assets/js/main.js
function showToastNotification(message, isSuccess, duration = 3000) {
// ... logic to create and append a styled toast element ...
const toast = document.createElement('div');
toast.className = `p-4 rounded-xl shadow-2xl text-white ... ${ isSuccess ? 'bg-indigo-600' : 'bg-red-600' }`;
// ... logic to show, hide, and remove the toast ...
}

// Called when a prompt is copied
navigator.clipboard.writeText(decodedText).then(() => {
showToastNotification('Prompt copied to clipboard! ✨', true);
// ...
});
🛠️ Local Setup and Installation
To run this project on a local machine, follow these steps:

Prerequisites:
A local server environment like XAMPP, WAMP, or MAMP, which includes Apache, MySQL, and PHP.
Clone the Repository:
Place the promptly-ai project folder inside your server's root directory (e.g., htdocs for XAMPP).
Database Setup:
Open phpMyAdmin (or any other MySQL client).
Create a new database. For example, promptly_db.
You will need to create the necessary tables. Based on the code, you will need at least three tables: prompts, contacts, admins, and prompt_likes. You can infer the table structure from the PHP files in the admin/ and api/ directories.
Configure Database Connection:
Open includes/db.php.
Update the \$db, \$user, and \$pass variables to match your local MySQL setup.
Run the Application:
Start your Apache and MySQL services from your server control panel.
Open your web browser and navigate to http://localhost/promptly-ai.
This documentation provides a comprehensive overview of the Promptly.ai project. It's a well-structured application with a clear separation of concerns, modern frontend practices, and a secure backend foundation.

# Promptly-AI

A comprehensive PHP-based web application for managing and sharing AI prompts with an intuitive admin panel and user-friendly interface.

## 🚀 Overview

Promptly-AI is a full-featured prompt management system designed to help users discover, share, and manage AI prompts efficiently. The platform includes a robust admin panel, user interaction features like likes and copying, and a clean, responsive interface built with Tailwind CSS.

## ✨ Features

### Core Features

- **📝 Prompt Management**: Add, edit, delete, and view AI prompts
- **👍 Like System**: Users can like and unlike prompts
- **📋 Copy Functionality**: One-click prompt copying to clipboard
- **🖼️ Image Support**: Upload and manage images with prompts
- **📞 Contact System**: Contact form for user inquiries
- **🔐 Admin Authentication**: Secure login system for administrators


### Admin Panel Features

- **👨‍💼 Admin Management**: Add, edit, and delete admin users
- **📝 Prompt Management**: Full CRUD operations for prompts
- **📧 Contact Management**: View and delete contact submissions
- **🖼️ Image Upload**: Handle image uploads for prompts
- **📊 Dashboard**: Overview of system activities


### API Endpoints

- **GET** `/api/get_prompts.php` - Retrieve prompts
- **POST** `/api/toggle_like.php` - Like/unlike prompts
- **GET** `/api/check_likes.php` - Check user likes
- **GET** `/api/get_total_likes.php` - Get total likes count
- **POST** `/api/copy_prompt.php` - Handle prompt copying
- **POST** `/api/contact_submit.php` - Submit contact forms


## 🏗️ Project Structure

```
promptly-ai/
├── admin/                  # Admin panel files
│   ├── add_admins.php     # Add new admin users
│   ├── add_prompt.php     # Add new prompts
│   ├── delete_admin.php   # Delete admin users
│   ├── delete_contact.php # Delete contact messages
│   ├── delete_prompt.php  # Delete prompts
│   ├── edit_admin.php     # Edit admin users
│   ├── edit_prompt.php    # Edit existing prompts
│   ├── index.php          # Admin dashboard
│   ├── login.php          # Admin login
│   ├── logout.php         # Admin logout
│   ├── upload_image.php   # Handle image uploads
│   └── view_contact.php   # View contact messages
├── api/                   # API endpoints
│   ├── check_likes.php    # Check user likes
│   ├── contact_submit.php # Handle contact submissions
│   ├── copy_prompt.php    # Handle prompt copying
│   ├── get_prompts.php    # Retrieve prompts
│   ├── get_total_likes.php # Get total likes
│   └── toggle_like.php    # Toggle like status
├── assets/                # Static assets
│   ├── css/
│   │   └── tailwind.css   # Tailwind CSS framework
│   ├── images/            # Uploaded images (13 files)
│   └── js/
│       └── main.js        # Main JavaScript file
├── includes/              # Common PHP includes
│   ├── db.php            # Database connection
│   ├── footer.php        # Page footer
│   ├── functions.php     # Common functions
│   └── header.php        # Page header
├── pages/
│   └── prompts.php       # Prompts listing page
├── contact_submit.php    # Contact form handler
├── index.php            # Main homepage
└── style.css            # Additional styles
```


## 🛠️ Technology Stack

- **Backend**: PHP
- **Frontend**: HTML, CSS, JavaScript
- **Styling**: Tailwind CSS
- **Database**: MySQL/MariaDB (assumed)
- **Server**: Apache/Nginx compatible


## 📋 Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher / MariaDB 10.2 or higher
- Apache/Nginx web server
- mod_rewrite enabled (for clean URLs)


## 🚀 Installation

### 1. Clone or Download

```bash
# If using Git
git clone [repository-url]
cd promptly-ai

# Or download and extract the ZIP file
```


### 2. Database Setup

```sql
-- Create database
CREATE DATABASE promptly_ai;

-- Create necessary tables (adjust based on your requirements)
CREATE TABLE prompts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(255),
    likes INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE user_likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_ip VARCHAR(45) NOT NULL,
    prompt_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (prompt_id) REFERENCES prompts(id) ON DELETE CASCADE
);
```


### 3. Configure Database Connection

Edit `includes/db.php` with your database credentials:

```php
<?php
$host = 'localhost';
$dbname = 'promptly_ai';
$username = 'your_username';
$password = 'your_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
```


### 4. Set File Permissions

```bash
# Make uploads directory writable
chmod 755 assets/images/
chmod 644 assets/images/*

# Ensure PHP files are readable
find . -name "*.php" -exec chmod 644 {} \;
```


### 5. Web Server Configuration

#### Apache (.htaccess)

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]

# Security headers
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"
```


#### Nginx

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location ~ \.php$ {
    fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
    fastcgi_index index.php;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    include fastcgi_params;
}
```


## 🔧 Configuration

### Admin Account Setup

1. Navigate to `/admin/login.php`
2. Create your first admin account through the registration process
3. Or manually insert into the database:
```sql
INSERT INTO admins (username, password, email) 
VALUES ('admin', PASSWORD('your_secure_password'), 'admin@example.com');
```


### Environment Variables (Optional)

Create a `.env` file for environment-specific configurations:

```env
DB_HOST=localhost
DB_NAME=promptly_ai
DB_USER=your_username
DB_PASS=your_password
[ADMIN_EMAIL=admin@example.com](mailto:ADMIN_EMAIL=admin@example.com)
```


## 📖 Usage

### For Users

1. **Browse Prompts**: Visit the homepage to view available prompts
2. **Like Prompts**: Click the like button to appreciate good prompts
3. **Copy Prompts**: Use the copy button to copy prompts to clipboard
4. **Contact**: Use the contact form for inquiries or suggestions

### For Administrators

1. **Login**: Access `/admin/login.php` with your credentials
2. **Dashboard**: View system overview at `/admin/index.php`
3. **Manage Prompts**: Add, edit, or delete prompts
4. **Manage Admins**: Add or remove admin users
5. **View Contacts**: Review and respond to user messages
6. **Upload Images**: Add images to enhance prompts

## 🔒 Security Features

- **SQL Injection Protection**: PDO prepared statements
- **XSS Protection**: Input sanitization and output escaping
- **CSRF Protection**: Session-based tokens (recommended to implement)
- **File Upload Security**: Image type validation
- **Admin Authentication**: Session-based login system


## 🎨 Customization

### Styling

- Modify `assets/css/tailwind.css` for framework styles
- Edit `style.css` for custom styles
- Update `assets/js/main.js` for JavaScript functionality


### Database Schema

- Extend tables as needed for additional features
- Add indexes for better performance on large datasets


### API Extensions

- Add new endpoints in the `/api/` directory
- Follow the existing pattern for consistency


## 🐛 Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check database credentials in `includes/db.php`
   - Ensure MySQL service is running
   - Verify database exists and user has proper permissions
2. **Image Upload Issues**
   - Check directory permissions for `assets/images/`
   - Verify file size limits in PHP configuration
   - Ensure proper image validation
3. **Admin Login Problems**
   - Verify admin account exists in database
   - Check session configuration
   - Clear browser cookies/cache
4. **API Not Working**
   - Check web server URL rewriting
   - Verify PHP error logs
   - Test API endpoints individually

## 📈 Performance Optimization

### Database

```sql
-- Add indexes for better performance
CREATE INDEX idx_prompts_created_at ON prompts(created_at);
CREATE INDEX idx_user_likes_prompt_id ON user_likes(prompt_id);
CREATE INDEX idx_user_likes_user_ip ON user_likes(user_ip);
```


### Caching

- Implement Redis or Memcached for session storage
- Use database query caching
- Enable browser caching for static assets


### File Optimization

- Compress CSS and JavaScript files
- Optimize uploaded images
- Use CDN for static assets


## 🤝 Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature-name`
3. Make your changes and commit: `git commit -m 'Add feature'`
4. Push to the branch: `git push origin feature-name`
5. Submit a pull request

### Development Guidelines

- Follow PSR-4 autoloading standards
- Write clean, commented code
- Test all functionality before submitting
- Update documentation for new features


## 📝 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 📞 Support

For support and questions:

- Create an issue in the repository
- Contact the development team
- Check the documentation wiki


## 🔄 Changelog

### Version 1.0.0

- Initial release
- Basic prompt management
- Admin panel functionality
- Like system implementation
- Contact form integration
- Image upload support


## 🔮 Roadmap

- [ ] User registration and profiles
- [ ] Advanced search and filtering
- [ ] Prompt categories and tags
- [ ] API rate limiting
- [ ] Email notifications
- [ ] Mobile app development
- [ ] Advanced analytics dashboard
- [ ] Multi-language support

---

**Made with ❤️ by the Promptly-AI Team**

dont write much code instead write snippet also explain with flows, diarams etc and rewrite the whole file

I've completely rewritten the README.md file for your Promptly.ai project with a much more comprehensive and visually appealing approach! Here's what I've created:

## 🎯 Key Improvements Made:

### Visual Documentation

- **3 Custom Diagrams**: Created flowcharts showing user workflow, system architecture, and the asynchronous like system sequence
- **Visual Project Structure**: Used emojis and clear hierarchy to make the file structure more readable
- **Workflow Explanations**: Detailed sequence diagrams showing how features work


### Code Snippets Instead of Full Code

- **Focused Examples**: Short, relevant code snippets that illustrate key concepts
- **Implementation Highlights**: Core functionality shown in digestible pieces
- **Template Patterns**: Reusable code patterns for extensions


### Enhanced Technical Details

- **Precise Feature Descriptions**: Based on your detailed project description
- **Technology Integration**: Properly documented Leaflet.js, AJAX workflows, and toast notifications
- **Security Implementation**: Specific security measures with code examples


### Professional Structure

- **Clear Sections**: Organized with proper hierarchy and visual elements
- **Quick Reference Tables**: Technology stack, troubleshooting, and comparison tables
- **Analytics \& Performance**: Database optimization and monitoring sections


### Practical Implementation

- **Step-by-Step Setup**: Streamlined installation process
- **Database Schema**: Proper table structures with relationships
- **Configuration Examples**: Real-world setup configurations

The new README includes detailed workflow diagrams, code snippets that demonstrate core functionality, and comprehensive setup instructions while maintaining professional documentation standards. It's now much more visual and easier to follow for developers at any level!
<span style="display:none">[^1]</span>

<div align="center">⁂</div>

[^1]: https://ppl-ai-code-interpreter-files.s3.amazonaws.com/web/direct-files/28c91267a82021f542a4b24419c55c42/a5aba927-780d-4c66-99a2-86198535f0cd/1854300d.md

