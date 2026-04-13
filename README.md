# E-Learning Platform

This is a modern E-Learning platform built using the Laravel framework with a modular architecture.

## 🚀 Tech Stack & Packages

### Backend
- **Framework:** [Laravel 12.0](https://laravel.com/) (PHP ^8.3)
- **Modular Structure:** [nwidart/laravel-modules](https://nwidart.com/laravel-modules/v6/introduction) - Used to separate functionalities into independent modules.
- **Roles & Permissions:** [spatie/laravel-permission](https://spatie.be/docs/laravel-permission/) - Managing user access controls.
- **Activity Logging:** [spatie/laravel-activitylog](https://spatie.be/docs/laravel-activitylog/) - Tracking user activities and system changes.
- **REST API Docs:** [dedoc/scramble](https://scramble.dedoc.co/) - Automatic API documentation generation.
- **Interactive UI (Backend):** [Livewire 3.7](https://livewire.laravel.com/) - Building dynamic interfaces without leaving PHP.
- **Other Packages:** 
  - `creasi/laravel-nusa` (Indonesian regional data)
  - `cviebrock/eloquent-sluggable` (Generating slugs for Eloquent models)
  - `devrabiul/laravel-toaster-magic` (Toast notifications)
  - `laravel/sanctum` (API Authentication)

### Frontend
- **CSS Framework:** [Tailwind CSS 4](https://tailwindcss.com/)
- **UI Components:** [Flowbite](https://flowbite.com/)
- **Data Visualization:** [Chart.js](https://www.chartjs.org/)
- **Build Tool:** [Vite](https://vitejs.dev/)

## 📁 Project Structure

The project follows the standard Laravel directory structure with the addition of a `Modules` directory for domain-driven design logic.

```text
├── app/                  # Core Laravel application code (Models, Controllers, etc.)
├── bootstrap/            # Application bootstrap scripts
├── config/               # Application configuration files
├── database/             # Database migrations, seeders, and factories
├── lang/                 # Language files for localization
├── Modules/              # Modular application code (nwidart/laravel-modules)
│   └── [Module Name]/    # Each module contains its own Controllers, Models, Views, Routes, etc.
├── public/               # Publicly accessible files (index.php, compiled assets)
├── resources/            # Uncompiled assets (Blade templates, CSS, JS)
├── routes/               # Application route definitions (web.php, api.php, console.php)
├── storage/              # Compiled blade templates, file-based sessions, caches, and logs
├── tests/                # Automated tests (Pest / PHPUnit)
├── vendor/               # Composer dependencies
└── node_modules/         # NPM dependencies
```

## 🛠️ Getting Started

### Prerequisites
- PHP >= 8.3
- Composer
- Node.js & NPM
- Database (MySQL, PostgreSQL, or SQLite)

#### PHP Configuration (`php.ini`)
To ensure file uploads (e.g., up to 10MB) work correctly, please verify that your `php.ini` has at least the following configuration:
```ini
upload_max_filesize = 15M
post_max_size = 16M
memory_limit = 256M
```

### Installation

1. Clone the repository and navigate into the project directory:
   ```bash
   git clone <repository-url>
   cd e-learning
   ```

2. Run the automated setup script defined in `composer.json` to install dependencies, copy the `.env` file, generate an application key, migrate the database, and build frontend assets:
   ```bash
   composer setup
   ```
   *(Alternatively, you can run `composer install`, copy `.env`, generate the key, migrate, `npm install`, and `npm run build` manually).*

3. Configure your database and other environment variables in the `.env` file.

### Running the Application

To start the development server, queue worker, and Vite build wrapper concurrently, simply run:

```bash
composer dev
```

This will run:
- `php artisan serve`
- `php artisan queue:listen`
- `npm run dev`

### Testing

Run the test suite using Pest/PHPUnit:
```bash
composer test
```

## 🧩 Modular Development (Laravel Modules)

This project uses `nwidart/laravel-modules` to separate functionalities into independent modules. Below are some useful commands for working with modules.

### Modifying Existing Database Tables

If you need to add or modify columns on an existing table that has already been migrated, **do not** modify the original migration file. Instead, create a new migration specifically for that module.

For example, to add an `is_active` column to the `rencana_tenaga_kerjas` table in the `RTK` module:

```bash
php artisan module:make-migration add_is_active_to_rencana_tenaga_kerjas_table RTK
```

This will generate a new migration file inside `Modules/RTK/Database/migrations/`. You can then define your new columns in the `up()` method, drop them in the `down()` method, and apply the changes by running `php artisan migrate`.

### Common Module Commands

Here are some frequently used commands when developing within a module:

- **Create a new module:**
  ```bash
  php artisan module:make ModuleName
  ```
- **Create a controller in a module:**
  ```bash
  php artisan module:make-controller ControllerName ModuleName
  ```
- **Create a model in a module (with migration):**
  ```bash
  php artisan module:make-model ModelName ModuleName -m
  ```
- **Create a new migration in a module:**
  ```bash
  php artisan module:make-migration create_table_name_table ModuleName
  ```

For a complete list of commands, refer to the [Laravel Modules Documentation](https://nwidart.com/laravel-modules/v11/introduction).
