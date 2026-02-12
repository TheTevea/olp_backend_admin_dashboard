# Olongpich Transport - Project Structure Documentation

## Overview

This is a **CakePHP 1.3** web application for **Olongpich Transport**, a bus/transport ticketing and management system. The application follows the **MVC (Model-View-Controller)** architectural pattern.

---

## Root Directory Structure

```
/var/www/html/myphpapp/
├── README                    # CakePHP framework documentation
├── index.php                 # Main entry point (2KB)
├── app/                      # Application code (3,323 files)
├── cake/                     # CakePHP core framework (693 files)
├── crontab/                  # Scheduled task configurations (2 files)
├── plugins/                  # CakePHP plugins
└── vendors/                  # Third-party libraries
```

---

## Application Directory (`app/`)

The main application code resides here.

### Core Files

| File                 | Description                                             |
| -------------------- | ------------------------------------------------------- |
| `app_controller.php` | Base controller with shared methods, menu configuration |
| `app_model.php`      | Base model with shared database methods                 |
| `index.php`          | Security redirect file                                  |

---

### Configuration (`app/config/`)

| File            | Description                          |
| --------------- | ------------------------------------ |
| `acl.ini.php`   | Access Control List configuration    |
| `bootstrap.php` | Application bootstrap/initialization |
| `core.php`      | Core CakePHP settings                |
| `database.php`  | Database connection settings         |
| `routes.php`    | URL routing configuration            |
| `schema/`       | Database schema files                |

---

### Controllers (`app/controllers/`)

> **69 controller files** handling business logic

#### Core Business Controllers

| Controller                       | Size  | Description                                            |
| -------------------------------- | ----- | ------------------------------------------------------ |
| `t_tickets_controller.php`       | 138KB | **Main ticketing system** - booking, sales, management |
| `reports_controller.php`         | 91KB  | **Report generation** - sales, financial reports       |
| `general_ledgers_controller.php` | 75KB  | **Accounting** - general ledger management             |
| `t_journeys_controller.php`      | 74KB  | **Journey management** - routes, schedules             |
| `schedules_controller.php`       | 42KB  | **Schedule management**                                |
| `users_controller.php`           | 39KB  | **User authentication & management**                   |
| `t_agents_controller.php`        | 30KB  | **Agent/partner management**                           |
| `bus_schedules_controller.php`   | 23KB  | **Bus scheduling**                                     |
| `expenses_controller.php`        | 22KB  | **Expense tracking**                                   |

#### Setting/Configuration Controllers

| Controller                                | Description             |
| ----------------------------------------- | ----------------------- |
| `t_destinations_controller.php`           | Destination management  |
| `t_transportation_types_controller.php`   | Transportation types    |
| `t_boarding_points_controller.php`        | Boarding points         |
| `t_departure_times_controller.php`        | Departure times         |
| `t_journey_price_periods_controller.php`  | Price period settings   |
| `t_journey_price_defaults_controller.php` | Default pricing         |
| `exchange_rates_controller.php`           | Currency exchange rates |
| `vat_settings_controller.php`             | VAT configuration       |

#### Location Controllers

| Controller                 | Description         |
| -------------------------- | ------------------- |
| `provinces_controller.php` | Province management |
| `districts_controller.php` | District management |
| `communes_controller.php`  | Commune management  |
| `villages_controller.php`  | Village management  |
| `streets_controller.php`   | Street management   |

#### Fleet Management

| Controller                 | Description               |
| -------------------------- | ------------------------- |
| `buses_controller.php`     | Bus fleet management      |
| `bus_types_controller.php` | Bus type configurations   |
| `t_boats_controller.php`   | Boat transport management |

#### Promotion & Payments

| Controller                                | Description         |
| ----------------------------------------- | ------------------- |
| `promotion_packages_controller.php`       | Promotion packages  |
| `promotion_apply_packages_controller.php` | Apply promotions    |
| `payments_controller.php`                 | Payment processing  |
| `discounts_controller.php`                | Discount management |

#### System Controllers

| Controller                       | Description          |
| -------------------------------- | -------------------- |
| `companies_controller.php`       | Company settings     |
| `branches_controller.php`        | Branch management    |
| `main_branches_controller.php`   | Main branch config   |
| `groups_controller.php`          | User groups/roles    |
| `dashboards_controller.php`      | Dashboard display    |
| `offline_servers_controller.php` | Offline sync servers |
| `sync_monitors_controller.php`   | Sync monitoring      |

#### Website Content Controllers

| Controller                         | Description        |
| ---------------------------------- | ------------------ |
| `website_banners_controller.php`   | Banner management  |
| `website_galleries_controller.php` | Gallery images     |
| `website_abouts_controller.php`    | About page content |
| `website_policies_controller.php`  | Policy pages       |

---

### Components (`app/controllers/components/`)

Reusable controller logic:

| Component           | Size | Description                                       |
| ------------------- | ---- | ------------------------------------------------- |
| `helper.php`        | 47KB | **Main helper** - utility functions, calculations |
| `agency_online.php` | 17KB | Online agency integration                         |
| `file_handler.php`  | 8KB  | File upload/processing                            |
| `address.php`       | 4KB  | Address handling                                  |
| `auto_id.php`       | 2KB  | Auto ID generation                                |
| `coa.php`           | 1KB  | Chart of accounts                                 |

---

### Models (`app/models/`)

> **28 model files** representing database entities

#### Core Business Models

| Model               | Description               |
| ------------------- | ------------------------- |
| `t_ticket.php`      | Ticket transactions       |
| `t_journey.php`     | Journey/route definitions |
| `t_agent.php`       | Agent/partner data        |
| `t_destination.php` | Destinations              |
| `customer.php`      | Customer information      |
| `bus.php`           | Bus fleet                 |
| `bus_schedule.php`  | Bus schedules             |

#### Pricing Models

| Model                         | Description           |
| ----------------------------- | --------------------- |
| `t_journey_price_default.php` | Default prices        |
| `t_journey_price_period.php`  | Seasonal pricing      |
| `t_journey_agent_price.php`   | Agent-specific prices |

#### Seat Control

| Model                        | Description          |
| ---------------------------- | -------------------- |
| `t_seat_control.php`         | Seat availability    |
| `t_seat_control_history.php` | Seat booking history |

#### Organization

| Model             | Description        |
| ----------------- | ------------------ |
| `company.php`     | Company data       |
| `branch.php`      | Branch information |
| `main_branch.php` | Main branch        |
| `group.php`       | User groups        |
| `user.php`        | Users              |

---

### Views (`app/views/`)

> **74 view directories** with template files (.ctp)

#### Key View Directories

| Directory          | Files | Description          |
| ------------------ | ----- | -------------------- |
| `reports/`         | 122   | Report templates     |
| `general_ledgers/` | 43    | Accounting views     |
| `t_tickets/`       | 24    | Ticketing views      |
| `payments/`        | 21    | Payment views        |
| `elements/`        | 23    | Reusable UI elements |
| `layouts/`         | 15    | Page layouts         |
| `t_journeys/`      | 10    | Journey management   |
| `users/`           | 10    | User management      |

#### Layouts (`app/views/layouts/`)

| Layout        | Description                    |
| ------------- | ------------------------------ |
| `default.ctp` | Main application layout (32KB) |
| `login.ctp`   | Login page layout              |
| `pos.ctp`     | Point of Sale layout           |
| `mobile.ctp`  | Mobile layout                  |
| `display.ctp` | Display screen layout          |
| `payment.ctp` | Payment pages                  |
| `ajax.ctp`    | AJAX responses                 |
| `json.ctp`    | JSON responses                 |

#### Elements (`app/views/elements/`)

Reusable UI components:

| Element          | Description        |
| ---------------- | ------------------ |
| `header.ctp`     | Page header (16KB) |
| `menu.ctp`       | Navigation menu    |
| `embed_font.ctp` | Font embedding     |
| `paging.ctp`     | Pagination         |
| `print/`         | Print templates    |
| `email/`         | Email templates    |

---

### Web Root (`app/webroot/`)

> **2,591 files** - publicly accessible files

#### Stylesheets (`webroot/css/`)

| File                   | Description                   |
| ---------------------- | ----------------------------- |
| `style.css`            | Main application styles (7KB) |
| `menu-slide-left.css`  | Slide menu styles (8KB)       |
| `filter_container.css` | Filter UI styles (8KB)        |
| `table.css`            | Table styles                  |
| `button.css`           | Button styles                 |
| `form.css`             | Form styles                   |
| `pos.css`              | POS styles                    |
| `payment.css`          | Payment page styles           |
| `login.css`            | Login page styles             |

#### JavaScript (`webroot/js/`)

| Library/File               | Description                   |
| -------------------------- | ----------------------------- |
| `jquery-1.7.min.js`        | jQuery core                   |
| `jquery-ui-1.8.14.custom/` | jQuery UI (356 files)         |
| `jquery-ui-1.10.0.custom/` | jQuery UI newer version       |
| `DataTables-1.8.1/`        | DataTables plugin (449 files) |
| `HighChart-4-2-2/`         | Charting library              |
| `harvesthq-chosen-v0.9.1/` | Select enhancement            |
| `validateEngine/`          | Form validation               |
| `timePicker/`              | Time picker                   |
| `function.js`              | Custom functions              |
| `menuSetting.js`           | Menu configuration            |

#### Other Resources

| Directory                    | Description              |
| ---------------------------- | ------------------------ |
| `img/`                       | Image files              |
| `fonts/`                     | Font files               |
| `includes/`                  | PHP includes (439 files) |
| `lang/`                      | Language files (EN/KH)   |
| `barcodegen.1d-php5.v2.2.0/` | Barcode generation       |
| `captcha/`                   | CAPTCHA images           |
| `public/`                    | Public uploads           |

---

## CakePHP Core (`cake/`)

Framework files (do not modify):

| Directory        | Description                |
| ---------------- | -------------------------- |
| `libs/`          | Core libraries (116 files) |
| `console/`       | CLI tools (91 files)       |
| `config/`        | Framework config           |
| `tests/`         | Framework tests            |
| `basics.php`     | Basic functions            |
| `bootstrap.php`  | Framework bootstrap        |
| `dispatcher.php` | Request dispatcher         |

---

## Key Features

Based on the structure, this application includes:

### 🎫 Ticketing System

- Ticket booking and sales
- Seat selection and control
- Journey management
- Multi-currency pricing

### 📊 Reporting

- Sales reports
- Financial reports
- Accounting (General Ledger)

### 🚌 Fleet Management

- Bus management
- Bus types and schedules
- Route management

### 👥 User Management

- Authentication
- Role-based access
- Agent/partner accounts

### 💰 Financial

- Payment processing
- Expense tracking
- Exchange rates
- VAT settings

### 🌐 Multi-location

- Provincial organization
- Branch management
- Multi-language (EN/KH)

### 📱 Multi-platform

- Web interface
- Mobile interface
- POS interface
- Display screens

---

## Technology Stack

| Technology | Version     | Purpose            |
| ---------- | ----------- | ------------------ |
| PHP        | 5.x         | Backend            |
| CakePHP    | 1.3.x       | MVC Framework      |
| jQuery     | 1.7         | JavaScript library |
| jQuery UI  | 1.8.14/1.10 | UI components      |
| DataTables | 1.8.1       | Table management   |
| Highcharts | 4.2.2       | Charts/graphs      |
| MySQL      | -           | Database           |

---

## File Statistics

| Category              | Count          |
| --------------------- | -------------- |
| Controllers           | 69             |
| Models                | 28             |
| View Directories      | 74             |
| CSS Files             | 17+            |
| JS Libraries          | 19 directories |
| Total App Files       | ~3,323         |
| Total Framework Files | ~693           |

---

_Generated: 2026-01-26_
