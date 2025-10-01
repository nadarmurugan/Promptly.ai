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
├── admin/                  # Admin panel files
│   ├── add_admins.php     # Add new admin users
│   ├── add_prompt.php     # Add new prompts
│   ├── delete_admin.php   # Delete admin users
│   ├── delete_contact.php # Delete contact messages
│   ├── delete_prompt.php  # Delete prompts
│   ├── edit_admin.php     # Edit admin users
│   ├── edit_prompt.php    # Edit existing prompts
│   ├── index.php          # Admin dashboard
│   ├── login.php          # Admin login
│   ├── logout.php         # Admin logout
│   ├── upload_image.php   # Handle image uploads
│   └── view_contact.php   # View contact messages
├── api/                   # API endpoints
│   ├── check_likes.php    # Check user likes
│   ├── contact_submit.php # Handle contact submissions
│   ├── copy_prompt.php    # Handle prompt copying
│   ├── get_prompts.php    # Retrieve prompts
│   ├── get_total_likes.php # Get total likes
│   └── toggle_like.php    # Toggle like status
├── assets/                # Static assets
│   ├── css/
│   │   └── tailwind.css   # Tailwind CSS framework
│   ├── images/            # Uploaded images (13 files)
│   └── js/
│       └── main.js        # Main JavaScript file
├── includes/              # Common PHP includes
│   ├── db.php            # Database connection
│   ├── footer.php        # Page footer
│   ├── functions.php     # Common functions
│   └── header.php        # Page header
├── pages/
│   └── prompts.php       # Prompts listing page
├── contact_submit.php    # Contact form handler
├── index.php            # Main homepage
└── style.css            # Additional styles
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
ADMIN_EMAIL=admin@example.com
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
