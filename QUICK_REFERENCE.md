# CakePHP to Laravel Migration - Quick Reference

## Command Reference

### CakePHP Commands (Legacy)
```bash
# Run in cakephp-legacy directory
cake/console/cake bake
cake/console/cake schema
```

### Laravel Commands (Current)
```bash
# Create new migration
php artisan make:migration create_table_name

# Create new model
php artisan make:model ModelName

# Create controller
php artisan make:controller ControllerName

# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Create seeder
php artisan make:seeder SeederName

# Run seeders
php artisan db:seed

# Start server
php artisan serve

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# List routes
php artisan route:list

# Run tests
php artisan test
```

## Framework Comparison

### File Organization

| Aspect | CakePHP 1.3 | Laravel 12 |
|--------|-------------|-----------|
| Controllers | `app/controllers/` | `app/Http/Controllers/` |
| Models | `app/models/` | `app/Models/` |
| Views | `app/views/` | `resources/views/` |
| Config | `app/config/` | `config/` |
| Public Assets | `app/webroot/` | `public/` |
| Database Migrations | `app/config/schema/` | `database/migrations/` |

### Naming Conventions

| CakePHP 1.3 | Laravel 12 |
|-------------|-----------|
| `UsersController` | `UserController` |
| `User` model → `users` table | `User` model → `users` table |
| `user.ctp` | `user.blade.php` |
| `$hasMany`, `$belongsTo` | `hasMany()`, `belongsTo()` |

### Common Conversions

#### Models

**CakePHP:**
```php
class User extends AppModel {
    var $name = 'User';
    var $belongsTo = array(
        'Group' => array(
            'className' => 'Group',
            'foreignKey' => 'group_id'
        )
    );
}
```

**Laravel:**
```php
class User extends Model {
    protected $fillable = ['name', 'email', 'group_id'];
    
    public function group() {
        return $this->belongsTo(Group::class);
    }
}
```

#### Controllers

**CakePHP:**
```php
class UsersController extends AppController {
    function index() {
        $users = $this->User->find('all');
        $this->set('users', $users);
    }
}
```

**Laravel:**
```php
class UserController extends Controller {
    public function index() {
        $users = User::all();
        return view('users.index', compact('users'));
    }
}
```

#### Views

**CakePHP (.ctp):**
```php
<?php foreach($users as $user): ?>
    <div><?php echo $user['User']['name']; ?></div>
<?php endforeach; ?>
```

**Laravel (.blade.php):**
```blade
@foreach($users as $user)
    <div>{{ $user->name }}</div>
@endforeach
```

#### Routing

**CakePHP:**
```php
// app/config/routes.php
Router::connect('/users/profile/*', array('controller' => 'users', 'action' => 'profile'));
```

**Laravel:**
```php
// routes/web.php
Route::get('/users/profile/{id}', [UserController::class, 'profile']);
```

## Database Relationships

### CakePHP Associations → Laravel Relationships

| CakePHP | Laravel | Example |
|---------|---------|---------|
| `$hasOne` | `hasOne()` | User hasOne Profile |
| `$hasMany` | `hasMany()` | Company hasMany Branches |
| `$belongsTo` | `belongsTo()` | User belongsTo Group |
| `$hasAndBelongsToMany` | `belongsToMany()` | User belongsToMany Roles |

### Example Migration

**CakePHP Model:**
```php
var $belongsTo = array(
    'Company' => array(
        'className' => 'Company',
        'foreignKey' => 'company_id'
    )
);
```

**Laravel Model:**
```php
public function company()
{
    return $this->belongsTo(Company::class);
}
```

## Authentication

### CakePHP Auth Component
```php
var $components = array('Auth');

function beforeFilter() {
    $this->Auth->allow('login', 'logout');
}
```

### Laravel Auth
```php
// In routes/web.php
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});

// In Controller
use Illuminate\Support\Facades\Auth;

public function login(Request $request) {
    if (Auth::attempt($credentials)) {
        return redirect()->intended('dashboard');
    }
}
```

## Validation

### CakePHP
```php
var $validate = array(
    'username' => array(
        'rule' => 'notEmpty',
        'message' => 'Username is required'
    )
);
```

### Laravel
```php
$request->validate([
    'username' => 'required|string|max:255',
    'email' => 'required|email|unique:users',
]);
```

## Helpers & Utilities

### CakePHP Helpers
- `$html->link()` → Blade: `<a href="{{ route('name') }}">Link</a>`
- `$form->create()` → Blade: `<form method="POST" action="{{ route('name') }}">`
- `$this->element()` → Blade: `@include('partials.name')`

### Laravel Blade Directives
- `@if`, `@else`, `@endif`
- `@foreach`, `@endforeach`
- `@for`, `@endfor`
- `@while`, `@endwhile`
- `@include('view.name')`
- `@extends('layout')`
- `@section`, `@endsection`
- `@yield('section')`
- `{{ $variable }}` - Escaped output
- `{!! $html !!}` - Raw output

## Common Tasks

### Get All Records
**CakePHP:** `$this->Model->find('all')`  
**Laravel:** `Model::all()`

### Get One Record
**CakePHP:** `$this->Model->find('first', conditions)`  
**Laravel:** `Model::where('id', 1)->first()` or `Model::find(1)`

### Save Record
**CakePHP:** `$this->Model->save($data)`  
**Laravel:** `Model::create($data)` or `$model->save()`

### Delete Record
**CakePHP:** `$this->Model->delete($id)`  
**Laravel:** `Model::destroy($id)` or `$model->delete()`

### Pagination
**CakePHP:** `$this->paginate('Model')`  
**Laravel:** `Model::paginate(15)`

### Flash Messages
**CakePHP:** `$this->Session->setFlash('Message')`  
**Laravel:** `session()->flash('message', 'Message')` or `redirect()->with('message', 'Message')`

## Migration Checklist for Each Feature

- [ ] Identify CakePHP controller and actions
- [ ] Create Laravel controller
- [ ] Convert model to Eloquent
- [ ] Create migration for database table
- [ ] Convert views to Blade templates
- [ ] Set up routes
- [ ] Test functionality
- [ ] Port validation rules
- [ ] Handle authentication/authorization
- [ ] Migrate helper methods
- [ ] Update assets and forms

## Tips & Best Practices

1. **Start Small**: Migrate one feature at a time
2. **Test Often**: Run tests after each migration step
3. **Keep Legacy Code**: Don't delete CakePHP code until Laravel version works
4. **Use Eloquent**: Take advantage of Laravel's powerful ORM
5. **Follow Laravel Conventions**: Use Laravel's naming and structure conventions
6. **Middleware**: Use middleware for authentication and authorization
7. **Service Providers**: Use service providers for configuration
8. **Request Validation**: Use Form Requests for complex validation
9. **Resource Controllers**: Use resource controllers for CRUD operations
10. **Blade Components**: Create reusable Blade components

## Common Issues & Solutions

### Issue: Foreign Key Constraints
**Solution**: Run migrations in correct order - parent tables before child tables

### Issue: Mass Assignment Protection
**Solution**: Add fields to `$fillable` array in models

### Issue: Authentication Not Working
**Solution**: Ensure middleware is applied and sessions are configured

### Issue: Views Not Found
**Solution**: Check blade file names and paths match controller return statements

### Issue: Routes Not Working
**Solution**: Run `php artisan route:clear` and check route definitions

## Resources

- [Laravel Documentation](https://laravel.com/docs/12.x)
- [CakePHP 1.3 Documentation](https://book.cakephp.org/1.3/en/)
- [Laravel Best Practices](https://github.com/alexeymezenin/laravel-best-practices)
- [Laracasts](https://laracasts.com) - Video tutorials

---

*Last Updated: 2026-02-12*
