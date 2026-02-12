# CakePHP to Laravel Migration Guide

## Overview

This document outlines the migration process from **CakePHP 1.3** to **Laravel 12.51.0** for the Olongpich Transport bus ticketing and management system.

## Migration Status

### ✅ Phase 1: Setup & Preparation (COMPLETED)
- Laravel 12.51.0 installed
- Environment configured
- Legacy CakePHP files preserved in `cakephp-legacy/` directory
- Database connection configured

### 🔄 Phase 2: Database Migration (IN PROGRESS)
- Need to analyze and migrate database schema
- Create Laravel migrations
- Set up Eloquent models

### ⏳ Remaining Phases
- Phase 3: Models Migration
- Phase 4: Controllers Migration  
- Phase 5: Views Migration
- Phase 6: Assets & Configuration
- Phase 7: Testing & Validation

## Technology Stack

### Original Stack (CakePHP)
- PHP 5.x
- CakePHP 1.3.x
- jQuery 1.7
- MySQL

### New Stack (Laravel)
- PHP 8.3.6
- Laravel 12.51.0 (Latest)
- Modern PHP practices
- Eloquent ORM
- Blade templating

## Key Components to Migrate

### Controllers (69 files)
The following controllers need to be migrated:

#### Core Business Controllers
- `t_tickets_controller.php` (138KB) → **Main ticketing system**
- `reports_controller.php` (91KB) → Report generation
- `general_ledgers_controller.php` (75KB) → Accounting
- `t_journeys_controller.php` (74KB) → Journey management
- `schedules_controller.php` (42KB) → Schedule management
- `users_controller.php` (39KB) → User authentication & management
- And 63 more controllers...

### Models (28 files)
Core models to migrate:
- `t_ticket.php` → Ticket transactions
- `t_journey.php` → Journey/route definitions
- `t_agent.php` → Agent/partner data
- `customer.php` → Customer information
- `bus.php` → Bus fleet
- And 23 more models...

### Views (74 directories)
Key view directories:
- `reports/` (122 files)
- `general_ledgers/` (43 files)
- `t_tickets/` (24 files)
- `payments/` (21 files)
- And 70 more directories...

## Migration Strategy

### Step-by-Step Approach

1. **Database Schema Migration**
   - Export existing database schema
   - Create Laravel migrations for all tables
   - Migrate relationships and foreign keys

2. **Model Migration**
   - Convert CakePHP models to Eloquent models
   - Implement relationships (hasMany, belongsTo, etc.)
   - Port custom model methods

3. **Controller Migration**
   - Convert controllers to Laravel controller syntax
   - Implement proper routing
   - Migrate authentication and authorization logic

4. **View Migration**
   - Convert `.ctp` views to Blade templates
   - Migrate layouts and partials
   - Update asset references

5. **Configuration & Services**
   - Migrate configuration files
   - Set up service providers
   - Implement middleware

## Database Configuration

### CakePHP Configuration
```php
'driver' => 'mysql',
'host' => 'localhost',
'login' => 'admin',
'database' => 'olp_express'
```

### Laravel Configuration (.env)
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=olp_express
DB_USERNAME=admin
```

## Key Features to Preserve

- 🎫 Ticketing system (booking, sales, seat control)
- 📊 Reporting (sales, financial reports)
- 🚌 Fleet management (buses, routes, schedules)
- 👥 User management (authentication, roles, agents)
- 💰 Financial (payments, expenses, VAT)
- 🌐 Multi-location support
- 📱 Multi-platform (web, mobile, POS)
- 🌍 Multi-language (EN/KH)

## Important Notes

### Preserved Files
The original CakePHP application is preserved in the `cakephp-legacy/` directory for reference during migration.

### Version Differences
- CakePHP 1.3 is a legacy framework (2011)
- Laravel 12 uses modern PHP 8.3 features
- Significant architectural differences require careful migration

### Breaking Changes
- CakePHP's inflection and naming conventions differ from Laravel
- Authentication systems are completely different
- View syntax changes from `.ctp` to `.blade.php`
- Route definitions are handled differently

## Next Steps

1. Analyze database schema in detail
2. Create initial migrations for core tables
3. Begin model migration starting with core models
4. Set up authentication system
5. Migrate one controller at a time, starting with authentication

## Resources

- Laravel Documentation: https://laravel.com/docs/12.x
- CakePHP Legacy Docs: https://book.cakephp.org/1.3/en/
- Migration Tool Ideas: Laravel Shift, custom migration scripts

## Timeline

This is a significant migration project. Estimated phases:
- Database & Models: 2-3 days
- Controllers: 5-7 days
- Views & Assets: 3-5 days
- Testing & Validation: 2-3 days

---

*Last Updated: 2026-02-12*
