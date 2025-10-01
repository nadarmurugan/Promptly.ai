# Promptly.ai

![Promptly.ai Banner](assets/images/shield-promptly-ai.png)

A comprehensive, user-friendly platform for discovering, sharing, and managing AI prompts with powerful admin tools and a seamless user experience.

---

## Table of Contents

- [Overview](#overview)
- [System Architecture](#system-architecture)
- [User Experience Flow](#user-experience-flow)
- [Technical Implementation](#technical-implementation)
  - [Asynchronous Like System](#asynchronous-like-system)
- [Installation & Quick Start](#installation--quick-start)
- [Project Structure](#project-structure)
- [Admin Panel Features](#admin-panel-features)
- [API Endpoints](#api-endpoints)
- [Database Schema](#database-schema)
- [Future Roadmap](#future-roadmap)
- [Contributing](#contributing)
- [License](#license)

---

## Overview

Promptly.ai is a curated marketplace for high-quality AI prompts. Built with performance and security in mind, it offers:

- Clean, responsive interface using Tailwind CSS
- Asynchronous like system and instant clipboard copying
- Secure admin panel with full CRUD operations
- Mobile-first design and interactive map integration

---

## System Architecture

Three-tier separation of concerns:

![System Architecture](assets/images/architecture_final.png)

1. **Presentation Layer**: HTML5, Tailwind CSS, JavaScript
2. **Application Layer**: Core PHP, RESTful APIs, Session Management
3. **Data Layer**: MySQL with PDO

---

## User Experience Flow

Illustration of the main user journey:

![User Experience Flow](assets/images/user_flow.png)

1. Landing Page → Browse Prompts
2. View Prompt Details → Like/Unlike → Copy to Clipboard
3. Contact Support → Submit Form → Admin Review

---

## Technical Implementation

### Asynchronous Like System

![Asynchronous Like System](assets/images/like_system_flow.png)

- **Client-Side**: JS event listeners trigger AJAX
- **Server-Side**: `toggle_like.php` updates likes in MySQL via PDO
- **Feedback**: JSON response updates UI with new count and toast

```javascript
// Example client-side handler
likeButton.addEventListener('click', () => {
  fetch('/api/toggle_like.php', { method: 'POST', body: JSON.stringify({ id: promptId }) })
    .then(res => res.json())
    .then(data => {
      countElem.textContent = data.newCount;
      showToastNotification('Liked!', true);
    })
    .catch(() => showToastNotification('Error liking prompt.', false));
});
```

---

## Installation & Quick Start

**Prerequisites**: PHP 7.4+, MySQL 5.7+, Apache/Nginx

1. Clone repository:
    ```bash
    git clone https://github.com/nadarmurugan/Promptly.ai.git
    cd promptly-ai
    ```
2. Database setup:
    ```sql
    CREATE DATABASE promptly_ai;
    USE promptly_ai;
    -- Run migrations or let the app auto-create tables on first load
    ```
3. Configure database in `includes/db.php`:
    ```php
    $host = 'localhost';
    $dbname = 'promptly_ai';
    $username = 'your_username';
    $password = 'your_password';
    ```
4. Launch app:
   - Place in web root (e.g., `htdocs`)
   - Visit `http://localhost/promptly-ai`
   - Create admin at `/admin/login.php`

---

## Project Structure

```text
promptly-ai/
├── admin/             # Admin panel (CRUD, user mgmt, contact)
├── api/               # RESTful endpoints (/get_prompts, /toggle_like, etc.)
├── assets/            # CSS, JS, images
│   └── images/        # Diagrams & UI assets
├── includes/          # Shared PHP components (db, header, footer)
└── index.php          # Main landing page
```

---

## Admin Panel Features

Manage content and system settings:

![Admin Panel Features](assets/images/admin_panel_features.png)

- **Dashboard**: Real-time metrics, recent activity
- **Prompt Management**: Add/Edit/Delete prompts, view analytics
- **User Management**: Admin roles & permissions
- **Contact Management**: View/respond submissions
- **Media Upload**: Image validation & storage
- **Security**: SQL injection protection, XSS prevention, session security

---

## API Endpoints

| Endpoint                  | Method | Description                       |
|---------------------------|--------|-----------------------------------|
| `/api/get_prompts.php`    | GET    | Retrieve list of prompts          |
| `/api/toggle_like.php`    | POST   | Like/unlike a prompt              |
| `/api/copy_prompt.php`    | POST   | Track copy-to-clipboard action    |
| `/api/contact_submit.php` | POST   | Submit contact form inquiries     |

---

## Database Schema

```sql
CREATE TABLE prompts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  image VARCHAR(255),
  likes_count INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

Additional tables: `admins`, `prompt_likes`, `prompt_copies`, `contacts`.

---

## Future Roadmap

- Advanced search/filtering
- User profiles & personalization
- Analytics dashboard
- Third-party AI integrations
- Native mobile apps (iOS/Android)

---

## Contributing

1. Fork the repo
2. Create a feature branch
3. Commit changes
4. Push & open a Pull Request

---

## License

This project is licensed under the MIT License.
