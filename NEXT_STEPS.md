# Next Steps for Migration - Detailed Action Plan

## Current Status Summary (as of 2026-02-12)

### ✅ Completed
- Laravel 12.51.0 installed and configured
- 16 database migrations created and run
- 8 core models created with relationships
- Dashboard controller and view created
- Comprehensive documentation (3 guides)
- Legacy CakePHP code preserved in `cakephp-legacy/`

### 🎯 Overall Progress: ~25%

---

## Phase 3: Complete Models Migration

### Priority 1: Complete Existing Model Stubs (High Priority)

#### 1. MainBranch Model
**File:** `app/Models/MainBranch.php`

**Migration:** `database/migrations/2026_02_12_023120_create_main_branches_table.php`

**Fields to add to migration:**
- `name` (string)
- `name_kh` (string, nullable)
- `code` (string, unique)
- `is_active` (boolean)
- timestamps, softDeletes

**Relationships:**
- `hasMany(Branch::class)`

---

#### 2. BusType Model
**File:** `app/Models/BusType.php`

**Migration:** `database/migrations/2026_02_12_023125_create_bus_types_table.php`

**Fields to add:**
- `name` (string)
- `code` (string, unique)
- `total_seats` (integer)
- `description` (text, nullable)
- `is_active` (boolean)
- timestamps, softDeletes

**Relationships:**
- `hasMany(Bus::class)`

---

#### 3. Bus Model
**File:** `app/Models/Bus.php`

**Migration:** `database/migrations/2026_02_12_023124_create_buses_table.php`

**Fields to add:**
- `bus_type_id` (foreignId)
- `company_id` (foreignId)
- `branch_id` (foreignId)
- `plate_number` (string, unique)
- `code` (string, unique)
- `capacity` (integer)
- `is_active` (boolean)
- timestamps, softDeletes

**Relationships:**
- `belongsTo(BusType::class)`
- `belongsTo(Company::class)`
- `belongsTo(Branch::class)`
- `hasMany(BusSchedule::class)`

---

#### 4. BusSchedule Model
**File:** Create `app/Models/BusSchedule.php`

**Migration:** `database/migrations/2026_02_12_023125_create_bus_schedules_table.php`

**Fields to add:**
- `bus_id` (foreignId)
- `t_journey_id` (foreignId)
- `departure_time` (time)
- `arrival_time` (time, nullable)
- `schedule_date` (date)
- `is_active` (boolean)
- timestamps, softDeletes

**Relationships:**
- `belongsTo(Bus::class)`
- `belongsTo(TJourney::class)`

**Command to create:**
```bash
php artisan make:model BusSchedule
```

---

#### 5. Customer Model
**File:** `app/Models/Customer.php`

**Migration:** `database/migrations/2026_02_12_023129_create_customers_table.php`

**Fields to add:**
- `first_name` (string)
- `last_name` (string)
- `phone` (string)
- `email` (string, nullable)
- `address` (text, nullable)
- `id_number` (string, nullable)
- `nationality_id` (foreignId, nullable)
- `date_of_birth` (date, nullable)
- `gender` (enum: male, female, other)
- `is_active` (boolean)
- timestamps, softDeletes

**Relationships:**
- `hasMany(TTicket::class)`

---

### Priority 2: Create Ticketing Models (Critical)

#### 6. TDestination Model
**File:** Create `app/Models/TDestination.php`

**Migration:** `database/migrations/2026_02_12_023129_create_t_destinations_table.php`

**Fields:**
- `name` (string)
- `name_kh` (string, nullable)
- `code` (string, unique)
- `province_id` (foreignId, nullable)
- `is_active` (boolean)
- timestamps, softDeletes

**Relationships:**
- `hasMany(TJourney::class, 'from_destination_id')`
- `hasMany(TJourney::class, 'to_destination_id')`

**Command:**
```bash
php artisan make:model TDestination
```

---

#### 7. TAgent Model
**File:** Create `app/Models/TAgent.php`

**Migration:** `database/migrations/2026_02_12_023129_create_t_agents_table.php`

**Fields:**
- `company_id` (foreignId)
- `name` (string)
- `code` (string, unique)
- `phone` (string)
- `email` (string, nullable)
- `address` (text, nullable)
- `commission_rate` (decimal, nullable)
- `is_active` (boolean)
- timestamps, softDeletes

**Relationships:**
- `belongsTo(Company::class)`
- `hasMany(TTicket::class)`

**Command:**
```bash
php artisan make:model TAgent
```

---

#### 8. TJourney Model
**File:** Create `app/Models/TJourney.php`

**Migration:** `database/migrations/2026_02_12_023133_create_t_journeys_table.php`

**Fields:**
- `company_id` (foreignId)
- `from_destination_id` (foreignId)
- `to_destination_id` (foreignId)
- `code` (string, unique)
- `distance_km` (decimal, nullable)
- `duration_hours` (decimal, nullable)
- `base_price` (decimal)
- `is_active` (boolean)
- timestamps, softDeletes

**Relationships:**
- `belongsTo(Company::class)`
- `belongsTo(TDestination::class, 'from_destination_id')`
- `belongsTo(TDestination::class, 'to_destination_id')`
- `hasMany(TTicket::class)`
- `hasMany(BusSchedule::class)`

**Command:**
```bash
php artisan make:model TJourney
```

---

#### 9. TTicket Model (MOST IMPORTANT)
**File:** Create `app/Models/TTicket.php`

**Migration:** `database/migrations/2026_02_12_023133_create_t_tickets_table.php`

**Fields:**
- `ticket_number` (string, unique)
- `company_id` (foreignId)
- `branch_id` (foreignId)
- `t_journey_id` (foreignId)
- `bus_schedule_id` (foreignId, nullable)
- `customer_id` (foreignId, nullable)
- `t_agent_id` (foreignId, nullable)
- `seat_number` (string)
- `departure_date` (date)
- `departure_time` (time)
- `price` (decimal)
- `discount` (decimal, default 0)
- `total_amount` (decimal)
- `status` (enum: booked, confirmed, cancelled, completed)
- `payment_status` (enum: pending, paid, refunded)
- `created_by` (foreignId - references users)
- timestamps, softDeletes

**Relationships:**
- `belongsTo(Company::class)`
- `belongsTo(Branch::class)`
- `belongsTo(TJourney::class)`
- `belongsTo(BusSchedule::class)`
- `belongsTo(Customer::class)`
- `belongsTo(TAgent::class)`
- `belongsTo(User::class, 'created_by')`

**Command:**
```bash
php artisan make:model TTicket
```

---

#### 10. TSeatControl Model
**File:** Create `app/Models/TSeatControl.php`

**Migration:** `database/migrations/2026_02_12_023133_create_t_seat_controls_table.php`

**Fields:**
- `bus_schedule_id` (foreignId)
- `seat_number` (string)
- `status` (enum: available, booked, locked)
- `t_ticket_id` (foreignId, nullable)
- `locked_until` (datetime, nullable)
- timestamps

**Relationships:**
- `belongsTo(BusSchedule::class)`
- `belongsTo(TTicket::class)`

**Command:**
```bash
php artisan make:model TSeatControl
```

---

### Priority 3: Create Supporting Models

#### 11-28. Remaining Models

Additional models to create (refer to `cakephp-legacy/app/models/`):

1. **Province** - Location hierarchy
2. **District** - Location hierarchy
3. **Commune** - Location hierarchy
4. **Village** - Location hierarchy
5. **TBoardingPoint** - Pick-up points
6. **TDropOff** - Drop-off points
7. **TJourneyPriceDefault** - Default pricing
8. **TJourneyPricePeriod** - Seasonal pricing
9. **TJourneyAgentPrice** - Agent-specific pricing
10. **ExchangeRate** - Currency rates
11. **Payment** - Payment records
12. **Expense** - Expense tracking
13. **GeneralLedger** - Accounting
14. **ChartAccount** - Chart of accounts
15. **VatSetting** - VAT configuration
16. **PromotionPackage** - Promotions
17. **Discount** - Discounts
18. **TBoat** - Boat transport (if applicable)

---

## Phase 4: Controllers Migration

### Step-by-Step Process for Each Controller

#### Template for Converting Controllers

**1. Analyze CakePHP Controller**
```bash
# View the original controller
cat cakephp-legacy/app/controllers/controller_name_controller.php
```

**2. Create Laravel Controller**
```bash
php artisan make:controller ControllerName
```

**3. Convert Actions**

**CakePHP Example:**
```php
function index() {
    $users = $this->User->find('all');
    $this->set('users', $users);
}
```

**Laravel Conversion:**
```php
public function index() {
    $users = User::all();
    return view('users.index', compact('users'));
}
```

---

### Priority Controllers to Migrate

#### 1. AuthController (Create New)
**Purpose:** Handle login/logout/registration

**Actions to implement:**
- `showLoginForm()` - Display login page
- `login()` - Process login
- `logout()` - Process logout
- `showRegistrationForm()` - Display registration
- `register()` - Process registration

**Command:**
```bash
php artisan make:controller Auth/AuthController
```

---

#### 2. UsersController
**Source:** `cakephp-legacy/app/controllers/users_controller.php` (39KB)

**Actions:**
- `index()` - List users
- `create()` - Show create form
- `store()` - Save new user
- `show($id)` - Show user details
- `edit($id)` - Show edit form
- `update($id)` - Update user
- `destroy($id)` - Delete user

**Command:**
```bash
php artisan make:controller UserController --resource
```

---

#### 3. TTicketsController (PRIORITY)
**Source:** `cakephp-legacy/app/controllers/t_tickets_controller.php` (138KB)

**This is the MAIN controller - most complex**

**Key actions:**
- Ticket booking
- Seat selection
- Payment processing
- Ticket printing
- Cancellation
- Reporting

**Command:**
```bash
php artisan make:controller TTicketController --resource
```

**Note:** This will require multiple sessions to migrate fully

---

## Phase 5: Views Migration

### Blade Conversion Process

#### 1. Create Base Layout
**File:** `resources/views/layouts/app.blade.php`

**Should include:**
- HTML structure
- Navigation menu
- Header
- Footer
- Asset includes

---

#### 2. Convert CakePHP Views to Blade

**CakePHP (.ctp):**
```php
<?php foreach($users as $user): ?>
    <div><?php echo $user['User']['name']; ?></div>
<?php endforeach; ?>
```

**Blade (.blade.php):**
```blade
@foreach($users as $user)
    <div>{{ $user->name }}</div>
@endforeach
```

---

## Quick Commands Reference

### Model Creation
```bash
# Create model
php artisan make:model ModelName

# Create model with migration
php artisan make:model ModelName -m

# Create model with controller and migration
php artisan make:model ModelName -mcr
```

### Controller Creation
```bash
# Create basic controller
php artisan make:controller ControllerName

# Create resource controller (CRUD)
php artisan make:controller ControllerName --resource

# Create API controller
php artisan make:controller Api/ControllerName --api
```

### Migration Commands
```bash
# Create migration
php artisan make:migration create_table_name

# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Fresh migration (drop all tables)
php artisan migrate:fresh
```

### Development
```bash
# Start server
php artisan serve

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# List routes
php artisan route:list
```

---

## Testing Strategy

### For Each Feature Migrated

1. **Test in CakePHP** - Understand how it works
2. **Migrate to Laravel** - Convert the code
3. **Test in Laravel** - Verify it works
4. **Compare outputs** - Ensure same behavior
5. **Write tests** - Create automated tests

### Example Test
```bash
# Create test
php artisan make:test UserTest

# Run tests
php artisan test
```

---

## Recommended Migration Order

### Week 1: Complete Models
1. Finish all 28 models
2. Test model relationships
3. Create seeders for test data

### Week 2: Core Controllers
1. AuthController (authentication)
2. DashboardController (already done ✅)
3. UserController (user management)
4. CompanyController
5. BranchController

### Week 3: Ticketing System
1. TDestinationController
2. TJourneyController
3. BusScheduleController
4. TTicketController (main - will take time)

### Week 4: Reports & Financial
1. ReportsController (91KB)
2. PaymentController
3. ExpenseController
4. GeneralLedgerController (75KB)

### Week 5: Views & Assets
1. Base layout
2. Authentication views
3. Dashboard views
4. Ticketing interface
5. Reports interface

### Week 6: Testing & Polish
1. Write tests
2. Fix bugs
3. Performance optimization
4. Documentation

---

## Important Notes

### Database Consideration
- Currently using **SQLite** for development
- Production should use **MySQL** as configured
- Remember to update `.env` for production:
  ```
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_DATABASE=olp_express
  DB_USERNAME=admin
  DB_PASSWORD=T168$123
  ```

### Legacy Code Reference
- Always refer to `cakephp-legacy/` for original implementation
- Don't delete legacy code until migration is complete and tested
- Document any changes in behavior

### Code Style
- Follow Laravel conventions
- Use PSR-12 coding standard
- Run `./vendor/bin/pint` for code formatting

---

## Helpful Resources

- Laravel Docs: https://laravel.com/docs/12.x
- Eloquent ORM: https://laravel.com/docs/12.x/eloquent
- Blade Templates: https://laravel.com/docs/12.x/blade
- Validation: https://laravel.com/docs/12.x/validation
- Testing: https://laravel.com/docs/12.x/testing

---

**Last Updated:** 2026-02-12  
**Next Session:** Complete Priority 1 models (MainBranch, BusType, Bus, BusSchedule, Customer)
