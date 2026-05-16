# Peerly

Peerly is a modern, feature-rich student community platform designed for university students to connect, discuss academics, and share resources. Built with a powerful technology stack, it provides a seamless and aesthetic user experience.

## Features
- **Student Forums**: Categorized discussions for Academics, Programming, Career, and General topics.
- **Robust Authentication**: Secure sign-up/login with email verification (via SendGrid).
- **Reputation System**: Users earn reputation points through upvotes and unlock badges (Legend, Expert, Active, etc.).
- **Rich Profiles**: Customizable user profiles with avatars, bios, GitHub/LinkedIn links, and university details.
- **Cloud Storage Integration**: Profile pictures and post/comment attachments are securely uploaded to a Supabase S3 bucket.
- **Supabase PostgreSQL Database**: Scalable cloud database powering all the core interactions.
- **Advanced Commenting**: Nested comment replies with a Reddit-style threading system and the ability to mark replies as "Solutions".
- **Dark/Light Mode**: Full CSS variable-based dynamic theming with modern Zinc & Purple accents.
- **Dockerized**: Easy to deploy with a provided Dockerfile.

## Tech Stack
- **Framework**: Laravel 11
- **Database**: PostgreSQL (Supabase with Connection Pooler)
- **Storage**: AWS S3 API (Supabase Storage)
- **Email Delivery**: SendGrid SMTP
- **Frontend**: Blade Components + Vanilla CSS (Custom Design System) + Phosphor Icons
- **Containerization**: Docker

## Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM
- Docker (optional, for containerized deployment)

## Local Setup

1. **Clone the repository:**
   ```bash
   git clone https://github.com/mirzasayzz/peerly.git
   cd peerly
   ```

2. **Install PHP and Node dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Note: Update the `.env` file with your specific Supabase PostgreSQL, Supabase S3, and SendGrid credentials.*

4. **Run Database Migrations & Seed:**
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Compile Frontend Assets & Run Server:**
   ```bash
   npm run build
   php artisan serve
   ```

## Docker Deployment

To build and run the application using Docker:

1. Build the Docker image:
   ```bash
   docker build -t peerly-app .
   ```

2. Run the container:
   ```bash
   docker run -p 8000:8000 --env-file .env peerly-app
   ```

## License
Open-source under the [MIT license](https://opensource.org/licenses/MIT).
