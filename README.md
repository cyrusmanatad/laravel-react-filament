# Unahco CMS

This is a web application built for Unahco, designed for order management and reporting. It features a modern frontend built with React and Inertia.js, and a powerful backend powered by Laravel. The application includes a comprehensive admin panel built with Filament for managing application data.

## Tech Stack

- **Backend:** Laravel 12, PHP 8.2
- **Admin Panel:** Filament 4
- **Frontend:** React, Inertia.js, TypeScript
- **Styling:** Tailwind CSS, Shadcn UI
- **Build Tool:** Vite
- **Database:** SQL (unspecified, but compatible with Laravel)

## Features

- **Order Management:** Create, view, and manage orders.
- **Reporting:** View various reports with data synced from Oracle BI.
- **User Authentication:** Secure user registration and login.
- **Profile Management:** Users can manage their profile and password.
- **Admin Panel:** A full-featured admin panel at `/admin` for data management.

## Prerequisites

- PHP >= 8.2
- Composer
- Node.js & npm
- A database server supported by Laravel (e.g., MySQL, PostgreSQL, SQLite).

## Installation

1.  **Clone the repository:**
    ```bash
    git clone <repository-url>
    cd filament
    ```

2.  **Install PHP dependencies:**
    ```bash
    composer install
    ```

3.  **Install JavaScript dependencies:**
    ```bash
    npm install
    ```

4.  **Set up your environment:**
    - Copy the `.env.example` file to `.env`:
      ```bash
      cp .env.example .env
      ```
    - Generate an application key:
      ```bash
      php artisan key:generate
      ```
    - Configure your database credentials and other environment variables in the `.env` file.

5.  **Run database migrations:**
    ```bash
    php artisan migrate
    ```

## Running the Application

To run the application in a local development environment, you can use the provided composer script which will start the PHP server, Vite development server, and queue worker concurrently.

```bash
composer run dev
```

- The application will be available at `http://localhost:8000` (or the address provided by `php artisan serve`).
- The Filament admin panel is available at `/admin`.

## Running Tests

To run the application's test suite, use the following command:

```bash
composer run test
```

This will execute the Pest test suite.

## Building for Production

To build the frontend assets for production, run:

```bash
npm run build
```
