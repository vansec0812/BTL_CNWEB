# Team Coding Guidelines - ProjectFinal

## 🎯 Mục Đích
Tài liệu này thiết lập các quy tắc nhất quán cho toàn bộ team (5 người) để đảm bảo code quality, khả năng bảo trì, và hợp tác hiệu quả.

---

## 1. Cấu Trúc Dự Án

### Directory Structure
```
projectFinal/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # Controllers cho routes
│   │   ├── Middleware/           # Custom middleware
│   │   └── Requests/             # Form requests validation
│   ├── Models/                   # Eloquent models
│   ├── Services/                 # Business logic
│   ├── Traits/                   # Reusable traits
│   └── Providers/                # Service providers
├── routes/
│   ├── web.php                   # Web routes
│   └── console.php               # Console commands
├── database/
│   ├── migrations/               # Database migrations
│   ├── seeders/                  # Database seeders
│   └── factories/                # Model factories
├── resources/
│   ├── views/                    # Blade templates
│   ├── css/                      # Stylesheets
│   └── js/                       # JavaScript
├── tests/
│   ├── Feature/                  # Feature tests
│   └── Unit/                     # Unit tests
└── config/                       # Configuration files
```

---

## 2. Quy Tắc Đặt Tên (Naming Conventions)

### Classes
- **Controllers**: `PascalCase` + `Controller` (e.g., `UserController`, `ProductController`)
- **Models**: `PascalCase` (e.g., `User`, `Product`, `Order`)
- **Traits**: `PascalCase` + `Trait` (e.g., `TimestampTrait`, `HasRolesTrait`)
- **Services**: `PascalCase` + `Service` (e.g., `AuthService`, `EmailService`)
- **Requests**: `PascalCase` + `Request` (e.g., `StoreUserRequest`, `UpdateProductRequest`)
- **Middleware**: `PascalCase` + `Middleware` (e.g., `RoleMiddleware`, `CheckTokenMiddleware`)

### Methods & Functions
- **Public Methods**: `camelCase` (e.g., `getUserById()`, `validateEmail()`)
- **Private Methods**: `camelCase` với prefix `_` (e.g., `_processData()`, `_validateInput()`)
- **Boolean Methods**: prefix `is`, `has`, `can` (e.g., `isActive()`, `hasPermission()`, `canDelete()`)
- **Query Methods**: prefix `get`, `find`, `fetch` (e.g., `getActiveUsers()`, `findByEmail()`)

### Variables
- **Local Variables**: `camelCase` (e.g., `$userName`, `$productCount`)
- **Constants**: `UPPER_SNAKE_CASE` (e.g., `MAX_ATTEMPTS`, `DEFAULT_ROLE`)
- **Database Columns**: `snake_case` (e.g., `user_id`, `created_at`)

### Files & Folders
- **Controllers**: `{Name}Controller.php`
- **Models**: `{Name}.php`
- **Migrations**: `YYYY_MM_DD_HHMMSS_create_{table_name}_table.php`
- **Services**: `{Name}Service.php`
- **Blade Templates**: `snake_case.blade.php`

---

## 3. PHP Code Style

### Formatting
```php
// ✅ GOOD
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
        ]);

        return User::create($validated);
    }
}

// ❌ BAD
namespace App\Http\Controllers;use App\Models\User;use Illuminate\Http\Request;
class UserController extends Controller{public function store(Request $request){$validated = $request->validate(['name' => 'required|string|max:255', 'email' => 'required|email|unique:users']);return User::create($validated);}}
```

### Indentation & Spacing
- **Indentation**: 4 spaces (NOT tabs)
- **Line Length**: Tối đa 120 ký tự
- **Blank Lines**: 1 dòng trống giữa các methods, 2 dòng trống giữa các classes

### Imports
```php
// ✅ GOOD: Sắp xếp theo thứ tự
use App\Models\User;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
```

### Method Parameters
```php
// ✅ GOOD: Rõ ràng và có type hints
public function updateUser(int $userId, Request $request): User
{
    // ...
}

// ❌ BAD: Không có type hints
public function updateUser($userId, $request)
{
    // ...
}
```

---

## 4. Laravel Best Practices

### Models
```php
// ✅ GOOD
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
```

### Controllers
```php
// ✅ GOOD: Sử dụng dependency injection
namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(private UserService $userService) {}

    public function store(StoreUserRequest $request): Response
    {
        $user = $this->userService->createUser($request->validated());
        return response()->json($user, 201);
    }
}
```

### Form Requests
```php
// ✅ GOOD
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên người dùng là bắt buộc',
            'email.unique' => 'Email này đã được đăng ký',
        ];
    }
}
```

### Service Layer
```php
// ✅ GOOD: Tách business logic khỏi controller
namespace App\Services;

use App\Models\User;

class UserService
{
    public function createUser(array $data): User
    {
        $data['password'] = bcrypt($data['password']);
        return User::create($data);
    }

    public function updateUser(User $user, array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        
        $user->update($data);
        return $user;
    }
}
```

---

## 5. Database Conventions

### Migrations
```php
// ✅ GOOD
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
            
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
```

### Naming Conventions
- **Tables**: `snake_case` (plural) - `users`, `products`, `user_roles`
- **Columns**: `snake_case` - `user_id`, `created_at`, `is_active`
- **Foreign Keys**: `{table}_id` (singular) - `user_id`, `product_id`
- **Pivot Tables**: `{table1}_{table2}` (alphabetical) - `product_user`, `role_permission`

---

## 6. Testing Standards

### File Organization
```
tests/
├── Feature/
│   ├── Auth/
│   │   ├── LoginTest.php
│   │   └── RegisterTest.php
│   └── Users/
│       └── CreateUserTest.php
└── Unit/
    ├── Models/
    │   └── UserTest.php
    └── Services/
        └── UserServiceTest.php
```

### Test Structure
```php
// ✅ GOOD
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created(): void
    {
        $response = $this->post('/api/users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
    }

    public function test_validation_fails_without_email(): void
    {
        $response = $this->post('/api/users', [
            'name' => 'John Doe',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }
}
```

### Test Naming
- **Feature Tests**: `{Action}{Subject}Test` (e.g., `CreateUserTest`, `DeleteProductTest`)
- **Unit Tests**: `{Class}Test` (e.g., `UserModelTest`, `EmailServiceTest`)
- **Methods**: `test_{scenario}` (e.g., `test_user_can_login`, `test_email_is_required`)

---

## 7. Git & Version Control

### Commit Messages
```
// ✅ GOOD format: [Type] Short description

[feat] Add user authentication system
[fix] Fix password reset email link generation
[docs] Update API documentation
[refactor] Simplify user validation logic
[test] Add user creation test cases
[chore] Update dependencies

// Types:
// feat - new feature
// fix - bug fix
// docs - documentation changes
// refactor - code refactoring
// test - adding/updating tests
// chore - dependencies, config, etc.
```

### Branch Naming
```
// Format: {type}/{description}

feature/user-authentication
fix/password-reset-email
docs/api-documentation
refactor/database-queries
```

### Pull Request (PR) Process
1. Create branch từ `develop`
2. Commit changes với clear messages
3. Push branch và tạo PR
4. Yêu cầu review từ ít nhất 1 người
5. Merge sau khi approved và tests pass

---

## 8. Code Review Checklist

### Trước khi commit
- ✅ Không có syntax errors
- ✅ Theo đúng naming conventions
- ✅ Có comments/documentation nếu cần
- ✅ Không có debug code (console.log, dd(), var_dump())
- ✅ Tests được viết và pass

### Trước khi merge
- ✅ Code review bởi minimal 1 người khác
- ✅ Tests coverage ≥ 80%
- ✅ Không có conflicts
- ✅ Documentation được update

---

## 9. Common Do's and Don'ts

### ✅ DO
- Sử dụng `type hints` cho methods
- Sử dụng `dependency injection`
- Viết tests cho business logic
- Sử dụng `eloquent` thay vì raw queries
- Viết comments cho complex logic
- Commit thường xuyên với messages rõ ràng
- Sử dụng `.env` cho sensitive data

### ❌ DON'T
- Không mix business logic trong controllers
- Không hardcode values
- Không viết quá dài methods (>50 lines)
- Không bỏ qua validation
- Không commit credentials/secrets
- Không lạm dụng static methods
- Không viết code theo kiểu procedural style

---

## 10. Development Workflow

### Setup Local Environment
```bash
# Clone repository
git clone <repo-url>
cd projectFinal

# Install dependencies
composer install
npm install

# Copy .env file
cp .env.example .env

# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate

# Start development server
npm run dev
# In another terminal
php artisan serve
```

### Running Tests
```bash
# Run all tests
npm run test

# Run specific test
npm run test tests/Feature/Users/CreateUserTest.php

# Run with coverage
npm run test -- --coverage
```

### Code Quality Tools
```bash
# PHP Code Formatting (Pint)
./vendor/bin/pint

# Run PHPUnit tests
./vendor/bin/phpunit
```

---

## 11. Team Communication

### Channels
- **Code Issues**: GitHub Issues hoặc Discussions
- **Quick Questions**: Team Chat (Slack/Discord)
- **Code Review**: Pull Requests
- **Documentation**: Wiki hoặc Markdown files

### Code Review Response Time
- Cố gắng review PRs trong vòng 24 giờ
- Comment rõ ràng với suggestions nếu cần changes
- Approve hoặc request changes, không để pending

---

## 12. Tools & Extensions (VS Code)

### Recommended Extensions
- PHP Intelephense
- Laravel Blade Snippets
- Prettier - Code formatter
- ESLint
- PHPUnit
- Thunder Client / REST Client

### VS Code Settings
```json
{
  "editor.formatOnSave": true,
  "editor.tabSize": 4,
  "editor.insertSpaces": true,
  "[php]": {
    "editor.defaultFormatter": "bmewburn.vscode-intelephense-client"
  },
  "php.validate.enable": true
}
```

---

## 13. Version Information

- **Laravel**: 12.x
- **PHP**: 8.2+
- **Node.js**: 18+
- **Database**: MySQL/PostgreSQL (per config)

---

## 14. Questions & Updates

- Document mới được update liên tục
- Nếu có thắc mắc, hỏi team lead hoặc discuss trong team chat
- Mọi suggestions để improve document đều được welcome

**Last Updated**: May 2026

---

## Summary

Tuân thủ các quy tắc trên sẽ đảm bảo:
- 🎯 Code consistency across team
- 🔧 Dễ dàng maintain và refactor
- 🚀 Faster development cycles
- 🐛 Ít bugs hơn
- 👥 Better team collaboration
