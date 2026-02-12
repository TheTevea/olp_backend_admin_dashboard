# Olongpich Transport - Laravel Admin Dashboard

## Project Overview

This is a **Laravel 12** web application for **Olongpich Transport**, a bus/transport ticketing and management system. This project is a migration from **CakePHP 1.3** to **Laravel 12.51.0** (latest version).

## About This Project

This application provides:
- 🎫 **Ticketing System** - booking, sales, seat control
- 📊 **Reporting** - sales reports, financial reports  
- 🚌 **Fleet Management** - buses, routes, schedules
- 👥 **User Management** - authentication, roles, agents
- 💰 **Financial** - payments, expenses, VAT
- 🌐 **Multi-location Support**
- 📱 **Multi-platform** - web, mobile, POS
- 🌍 **Multi-language** - EN/KH

## Technology Stack

- **PHP 8.3.6**
- **Laravel 12.51.0** (Latest)
- **MySQL** Database
- **Blade** Templating Engine
- **Eloquent ORM**

## Migration Status

This project is currently in **active migration** from CakePHP 1.3 to Laravel 12. The legacy CakePHP code is preserved in the `cakephp-legacy/` directory for reference.

See [MIGRATION_GUIDE.md](MIGRATION_GUIDE.md) for detailed migration documentation.

### Current Progress
- ✅ Laravel 12.51.0 installed and configured
- ✅ Database connection configured
- ✅ Initial migrations created for core tables
- ✅ Core models created (User, Group, Company, Branch, etc.)
- 🔄 In Progress: Database schema migration
- ⏳ Pending: Controllers, Views, Assets migration

## Installation

### Requirements
- PHP >= 8.3
- Composer
- MySQL
- Node.js & NPM (for asset compilation)

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd olp_backend_admin_dashboard
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**
   ```bash
   npm install
   ```

4. **Set up environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database** 
   
   Edit `.env` file:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=olp_express
   DB_USERNAME=admin
   DB_PASSWORD=your_password
   ```

6. **Run migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed database** (optional)
   ```bash
   php artisan db:seed
   ```

8. **Start development server**
   ```bash
   php artisan serve
   ```

9. **Compile assets** (in a new terminal)
   ```bash
   npm run dev
   ```

Visit `http://localhost:8000` in your browser.

## Project Structure

```
├── app/
│   ├── Http/
│   │   ├── Controllers/     # Application controllers
│   │   └── Middleware/      # Custom middleware
│   └── Models/              # Eloquent models
├── config/                  # Configuration files
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/             # Database seeders
├── resources/
│   ├── views/               # Blade templates
│   ├── js/                  # JavaScript files
│   └── css/                 # CSS files
├── routes/
│   ├── web.php              # Web routes
│   └── api.php              # API routes
├── public/                  # Public assets
├── storage/                 # Logs, cache, uploads
├── cakephp-legacy/          # Legacy CakePHP code (reference only)
└── tests/                   # Unit and feature tests
```

## Key Features (From CakePHP)

### Controllers Being Migrated (69 total)
- `TTicketsController` (138KB) - Main ticketing system
- `ReportsController` (91KB) - Report generation
- `GeneralLedgersController` (75KB) - Accounting
- `TJourneysController` (74KB) - Journey management
- And 65 more...

### Models Being Migrated (28 total)
- User, Group, Company, Branch
- TTicket, TJourney, TAgent
- Bus, BusSchedule, BusType
- Customer
- And 18 more...

## Development

### Running Tests
```bash
php artisan test
```

### Code Style
```bash
./vendor/bin/pint
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Documentation

- [Migration Guide](MIGRATION_GUIDE.md) - Detailed migration documentation
- [Project Structure](PROJECT_STRUCTURE.md) - Original CakePHP structure reference
- [Laravel Documentation](https://laravel.com/docs/12.x)

## Database

The application uses MySQL database named `olp_express`. The database configuration is stored in the `.env` file.

## Contributing

This is an internal migration project. For contributing guidelines, please contact the development team.

## License

Proprietary - Olongpich Transport

## Support

For support, please contact the development team.

---

**Version:** Laravel 12.51.0  
**Migration Started:** February 2026  
**Original Framework:** CakePHP 1.3.x
