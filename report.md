# Peerly — Student Community Forum

## 📖 Project Overview
**Peerly** is a premium, highly interactive online community platform designed exclusively for students. It serves as a centralized hub where students can ask academic questions, share study resources, and connect with peers worldwide. Built with a focus on a modern, dark-themed, glassmorphism UI, Peerly provides a sleek and engaging user experience similar to platforms like StackOverflow or Reddit, but tailored for the academic community.

---

## ✨ Key Features

### 1. Robust Discussion System (Q&A)
*   **Rich Posts:** Users can create detailed posts with titles, rich text content, and associate them with specific academic tags and categories (forums).
*   **Voting System:** A Reddit-style upvote/downvote system for both posts and comments helps surface the most helpful answers.
*   **Status Indicators:** Posts can be marked as **Resolved** (when a student gets their answer) or **Pinned** by administrators for important announcements.

### 2. User Profiles & Reputation
*   **Custom Profiles:** Users have dedicated profile pages showcasing their bio, university, major, graduation year, and social links (GitHub, LinkedIn).
*   **Cloud Avatars:** Integrated with Supabase (S3 compatible) for seamless profile picture uploads with a Google-style interactive hover UI.
*   **Reputation System:** Users earn reputation points through community engagement, unlocking badges (e.g., *Newcomer, Contributor, Legend*).

### 3. Advanced Admin Dashboard & Security
*   **Role Management:** Segregated access for Students and Administrators.
*   **Admin Onboarding:** A secure, token-based invitation system that emails prospective admins a unique 48-hour link to set up their credentials.
*   **Platform Moderation:** Admins have a dedicated dashboard to view platform statistics, delete inappropriate posts/comments, ban users, and pin important threads.

### 4. Search & Organization
*   **Global Search:** Users can search discussions and topics efficiently.
*   **Categorization:** Content is strictly organized into broad Forums (e.g., Computer Science, Mathematics) and specific Tags (e.g., `laravel`, `algorithms`).
*   **Filtering & Sorting:** Feeds can be sorted by *Trending, Latest, Top,* and *Unanswered*.

---

## 🛠️ Technology Stack

*   **Backend Framework:** Laravel 11 (PHP)
*   **Frontend UI:** Blade Templates, Custom Vanilla CSS (Dark-mode first, Glassmorphism), Phosphor Icons
*   **Database:** PostgreSQL (Hosted on Supabase)
*   **Object Storage:** AWS S3 Protocol (Supabase Storage for media/avatars)
*   **Email Delivery:** SendGrid API
*   **Deployment:** Dockerized and hosted on Render

---

## 📂 Project Structure

```text
Peerly/
├── app/
│   ├── Http/Controllers/    # Application logic (Posts, Profiles, Admin Dashboard, Auth)
│   ├── Mail/                # Mailable classes for transactional emails (Invites)
│   └── Models/              # Eloquent Database Models (User, Post, Comment, Vote)
├── database/
│   ├── migrations/          # DB Schema definitions
│   └── seeders/             # Dummy data generation for testing
├── public/
│   └── images/              # Static assets (Logos, placeholders)
├── resources/
│   ├── css/                 # Custom Design System (app.css)
│   └── views/               # Blade templates
│       ├── admin/           # Admin dashboard and user management views
│       ├── auth/            # Login, Registration, Password Reset views
│       ├── emails/          # HTML Email templates
│       ├── layouts/         # Master layouts (app, guest, admin)
│       ├── posts/           # Forum discussion views
│       └── profile/         # User profile and settings views
├── routes/
│   └── web.php              # URL Route definitions
├── .env                     # Environment configuration (DB, S3, Mail credentials)
└── Dockerfile               # Containerization instructions for Render deployment
```

---

## 📸 User Interface & Screenshots

*(Please replace the placeholder image paths with the actual screenshots of your application)*

### 1. Home Feed (Trending Discussions)
> Displays the glassmorphism hero banner, sorting tabs, and the list of active discussions.
> 
> ![Home Feed Screenshot](./screenshots/home_feed.png)

### 2. Discussion Detail & Comments
> Shows a full post, tags, upvote/downvote buttons, and the nested comment thread.
> 
> ![Discussion Screenshot](./screenshots/discussion.png)

### 3. User Profile & Settings
> Shows the user's avatar, reputation badge, university details, and the custom image upload interface.
> 
> ![User Profile Screenshot](./screenshots/profile.png)

### 4. Admin Dashboard
> Showcases the platform statistics, manage users list, and the "Pending Invite" system for new administrators.
> 
> ![Admin Dashboard Screenshot](./screenshots/admin_dashboard.png)

### 5. Secure Onboarding Flow
> The beautiful setup screen new administrators see when clicking their email invite link.
> 
> ![Onboarding Screenshot](./screenshots/onboarding.png)

---

## 🚀 Setup & Installation (Local Development)

1.  **Clone the repository and install dependencies:**
    ```bash
    composer install
    npm install
    ```
2.  **Environment Setup:**
    Duplicate `.env.example` to `.env` and populate your Supabase (Database & Storage) and SendGrid credentials.
3.  **Run Migrations & Seed the Database:**
    ```bash
    php artisan migrate:fresh --seed
    ```
4.  **Start the servers:**
    ```bash
    php artisan serve
    npm run dev
    ```
