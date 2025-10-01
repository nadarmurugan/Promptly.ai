Promptly.ai - Your AI-Powered Prompt Marketplace 🚀
Welcome to Promptly.ai, a dynamic and beautifully crafted marketplace for AI prompts. This platform was born from a passion for creativity and the transformative power of AI. Our mission is to build a vibrant community where users can discover, share, and utilize high-quality AI prompts, making generative AI more accessible and inspiring for everyone.

✨ Project Philosophy
We believe that a great prompt is the key to unlocking the full potential of AI. Promptly.ai is designed to be:

User-Centric: Providing a seamless and enjoyable experience for discovering content.

Community-Driven: Built with the vision of growing through user contributions and feedback.

Open & Accessible: Ensuring the platform is easy to set up, use, and contribute to.

🌟 Core Features
For Users (Frontend)
🎨 Stunning Prompt Gallery: A visually appealing gallery showcasing a curated collection of AI prompts.

✂️ One-Click Copy: Seamlessly copy any prompt with a single click and get instant feedback.

❤️ Interactive Like System: "Like" your favorite prompts and see the like-count update in real-time.

🔍 Smart Search & Filtering: Easily find the prompts you're looking for with our intuitive search and filter options.

📱 Fully Responsive Design: Enjoy a flawless experience on any device, thanks to the mobile-first design built with Tailwind CSS.

✉️ AJAX-Powered Contact Form: A non-intrusive contact form that submits your queries without interrupting your browsing session.

🗺️ Interactive Map: A Leaflet.js map integration in the contact section to display a physical location.

For Admins (Backend)
🔒 Secure Admin Login: A robust, session-based authentication system to protect the admin dashboard.

✍️ Full Prompt Management (CRUD): Create, Read, Update, and Delete prompts with ease.

📬 Contact Message Management: View and manage all user-submitted messages.

👥 Admin User Management (CRUD): Add, edit, and delete admin users.

📊 Centralized Dashboard: A comprehensive overview of all platform activity in a single-page interface.

🛠️ Technology Stack
Backend: Core PHP

Database: MySQL with PDO for secure connections

Frontend: HTML, Tailwind CSS, Vanilla JavaScript

APIs: Custom RESTful API

Libraries: Leaflet.js

🗺️ Future Roadmap
This is just the beginning! We have exciting plans for the future of Promptly.ai. Here's a glimpse of what's on the horizon:

User Accounts: Allow users to create profiles, save their favorite prompts, and track their activity.

Community Submissions: Enable users to submit their own high-quality prompts for review and inclusion.

Advanced Categorization: Implement a robust tagging and category system for even better prompt discovery.

Public API: Launch a public API for developers to integrate Promptly.ai into their own applications.

Theme Customization: Introduce themes like Dark Mode for a personalized viewing experience.

⚙️ How It Works: Application Architecture
Here's a high-level overview of the application's architecture:

+-----------------+      +-----------------+      +-----------------+
|   User Browser  |----->|   Web Server    |----->|    Database     |
| (HTML/CSS/JS)   |      |  (PHP Engine)   |      |     (MySQL)     |
+-----------------+      +-----------------+      +-----------------+
        |                      ^
        |                      |
        +----------------------+
            (AJAX Requests)
📁 File Structure
/promptly-ai
├── admin/              # Admin dashboard files
├── api/                # API endpoints for dynamic actions
├── assets/             # CSS, JavaScript, and image files
├── includes/           # Reusable PHP files (database connection, etc.)
└── ... and other files
🚀 Getting Started: Installation Guide
Follow these steps to get a local copy of Promptly.ai up and running.

Prerequisites
A web server (e.g., Apache, Nginx)

PHP 7.4 or higher

MySQL or MariaDB

1. Clone the Repository
Bash

git clone https://github.com/nadarmurugan/Promptly.ai.git
cd Promptly.ai
2. Database Setup
Create a new database in your MySQL server (e.g., promptly_ai).

Import the database.sql file or manually create the tables using the SQL schema provided in the repository.

3. Configure Database Connection
Open includes/db.php and update the database credentials:

PHP

<?php
$host = 'localhost';
$dbname = 'promptly_ai';
$username = 'your_username';
$password = 'your_password';

// ... connection logic
?>
4. Set Up Your Admin Account
Navigate to /admin/login.php in your browser.

Register a new admin account or manually insert one into the admins table.

🙌 Join the Journey: Contributing
We believe in the power of community to build something amazing. Your contributions are what will make this project thrive. Whether you're fixing a bug, proposing a new feature, or improving documentation, every bit of help is greatly appreciated!

Fork the Project

Create your Feature Branch (git checkout -b feature/AmazingFeature)

Commit your Changes (git commit -m 'Add some AmazingFeature')

Push to the Branch (git push origin feature/AmazingFeature)

Open a Pull Request

Let's build the future of AI prompts together!

👨‍💻 Project Author
This project was crafted with ❤️ by Jeyamurugan Nadar.

GitHub: nadarmurugan

Email: murugannadar077@gmail.com

Feel free to reach out with any questions, feedback, or collaboration ideas!
