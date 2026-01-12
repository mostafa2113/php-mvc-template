# PHP MVC Translation Template

A lightweight, flexible PHP MVC framework with built-in multi-language support (English/Arabic) and automatic RTL/LTR layout switching.

## Features

### Core Framework Features

- **MVC Architecture**: Clean separation of concerns with Models, Views, and Controllers
- **Flexible Routing System**: Pattern-based route matching with parameter extraction; merges route params with query params automatically
- **Response Helpers**: Built-in `render()` for layouts/views, `JSONResponse()` for API endpoints, and `enableCORS()` to allow localhost origins
- **PSR-4 Autoloading**: Proper namespace-based class autoloading via Composer
- **Session Management**: Session path pre-configured to `sessions/` and started in the front controller

### Localization & Internationalization

- **Multi-Language Support**: Out-of-the-box support for English and Arabic
- **JSON-Based Translations**: Simple, maintainable translation files in `config/languages/`
- **Session-Based Language Persistence**: User language preference is saved across sessions
- **Automatic RTL/LTR Switching**: Layout direction set from the active language
- **Dynamic Language Switching**: Inline POST-based language switcher in the header (`/language/switch`)

### Developer Features

- **Helper Functions**: Convenient utility functions for URLs, assets, media, and redirects
- **Layout System**: Template inheritance with layout wrapper; `{{content}}` replaced by view output
- **View & Translation Helpers**: `render()` composes layout + view, `translate()` pulls keys from the active language file
- **JSON & CORS Helpers**: `JSONResponse($data, $status)` and `enableCORS()` for API routes
- **Environment Configuration**: Development/production environment settings with error handling
- **Database Ready**: Base `Model` with PDO connection + prepared statements; sample `User` model implements create/update/find/authentication/validation

## Installation & Setup

### Prerequisites

- PHP 7.4 or higher
- Composer
- Web server (Apache, Nginx, or PHP built-in server)

### Installation Steps

1. **Clone the repository**:

```bash
git clone https://github.com/mostafa2113/php-mvc-template.git
cd php-mvc-template
```

2. **Install dependencies**:

```bash
composer install
```

3. **Set proper permissions**:

```bash
chmod -R 0755 sessions/
chmod -R 0755 uploads/
```

4. **Configure application** (optional):

   - Edit `config/config.php` with your database and environment settings
   - Default configuration is suitable for local development

5. **Start development server**:

```bash
php -S localhost:8000 -t public
```

6. **Access the application**:
   - Open browser to `http://localhost:8000`

## Project Structure

```
php-mvc-project-template/
├── App/                          # Application code (MVC)
│   ├── Controllers/              # Request handlers
│   │   ├── HomeController.php    # Homepage controller
│   │   ├── LanguageController.php # Language switching
│   │   └── PostsController.php   # Posts display example
│   ├── Models/                   # Data models
│   │   ├── Model.php             # Base model class
│   │   └── User.php              # Example user model
│   └── Views/                    # HTML templates
│       ├── layouts/              # Layout templates
│       │   └── main.php          # Main layout wrapper
│       ├── home/                 # Home page views
│       │   └── index.php         # Homepage template
│       └── posts/                # Post-related views
│           └── view.php          # Post detail view
├── Core/                         # Framework core
│   ├── Controller.php            # Base controller class with rendering & translation
│   └── Router.php                # URL routing engine
├── config/                       # Configuration files
│   ├── autoload.php              # Autoloader initialization
│   ├── config.php                # Application configuration
│   ├── Helper.php                # Global helper functions
│   └── languages/                # Translation files
│       ├── en.json               # English translations
│       └── ar.json               # Arabic translations
├── public/                       # Web root
│   ├── index.php                 # Application entry point
│   ├── assets/                   # Static files
│   │   ├── css/                  # Stylesheets
│   │   └── js/                   # JavaScript files
│   └── uploads/                  # User-uploaded files
├── sessions/                     # Session storage
├── vendor/                       # Composer dependencies
├── composer.json                 # Project dependencies
└── README.md                     # This file
```

## Configuration

### Environment Setup

Edit `config/config.php` to configure:

```php
define('DB_HOST', 'localhost');    // Database host
define('DB_NAME', 'your_database'); // Database name
define('DB_USER', 'root');         // Database user
define('DB_PASS', '');             // Database password
define('BASE_URL', 'http://localhost:8000/'); // Application URL
define('ENVIRONMENT', 'development'); // 'development' or 'production'
```

**Environment Modes**:

- **Development**: Shows all errors and warnings
- **Production**: Hides error messages from users

### Adding Routes

Modify `public/index.php` to add new routes:

```php
$router->add('route-pattern', ['controller' => 'ControllerName', 'action' => 'methodName']);
```

**Current routes**:

```php
$router->add('', ['controller' => 'Home', 'action' => 'index']);            // Root URL
$router->add('language/switch', ['controller' => 'Language', 'action' => 'switch']); // POST language switcher
$router->add('posts', ['controller' => 'Posts', 'action' => 'index']);      // JSON API example
$router->add('posts/{id}', ['controller' => 'Posts', 'action' => 'view']);  // Dynamic segments
```

Route params and query params are merged automatically before invoking the controller action.

### Adding Translations

1. Add translation keys to `config/languages/en.json`, e.g.:

```json
{
  "welcome": "Welcome to our application",
  "home_description": "This is a simple PHP MVC framework template."
}
```

2. Add the same keys to `config/languages/ar.json` with Arabic translations.

## Usage Guide

### Creating a New Controller

1. **Create controller file** in `App/Controllers/AboutController.php`:

```php
<?php

namespace App\Controllers;

use Core\Controller;

class AboutController extends Controller
{
    public function index()
    {
        $this->render('about/index', [
            "title" => $this->translate('about_page'),
            "company_name" => "Your Company"
        ]);
    }
}
```

2. **Add route** in `public/index.php`:

```php
$router->add('about', ['controller' => 'About', 'action' => 'index']);
```

3. **Create view** in `App/Views/about/index.php`:

```php
<h1><?= $title ?></h1>
<p><?= $company_name ?></p>
```

### Creating a New Model

- Extend `App\Models\Model` to gain the shared PDO connection and `query()` helper.
- Example: add a `Post` model with table-specific queries.

### Using the Built-in `User` Model

`App/Models/User.php` ships with common operations:

- `User::create($data)`: hashes password, timestamps, inserts, and returns the created user.
- `$user->update($data)`: updates fields (rehashes password when provided).
- `User::find($id)`, `User::findByEmail($email)`, `User::findByUsername($username)`
- `User::authenticate($username, $password)`
- `User::validate($data)`: lightweight input validation (username/email/password/full_name).

### Using Translations in Views

In any view file, use the `translate()` method inherited from `Controller`:

```php
<h2><?= $this->translate('welcome') ?></h2>
<p><?= $this->translate('home_description') ?></p>
```

### Using Helper Functions

Available globally via `config/Helper.php`:

```php
url('posts/5');              // http://localhost:8000/posts/5
asset('css/style.css');      // http://localhost:8000/assets/css/style.css
media('profile-pic.jpg');    // http://localhost:8000/uploads/profile-pic.jpg
redirect('posts');           // Redirects to /posts
```

### Building API Endpoints

Use `enableCORS()` and `JSONResponse()` inside controllers for API routes. Example from `PostsController`:

```php
public function index()
{
    $this->enableCORS();
    $this->JSONResponse([
        ['id' => 1, 'title' => 'First Post'],
        ['id' => 2, 'title' => 'Second Post']
    ]);
}
```

Allowed origins default to `http://localhost:*`.

### Language Switching

- Header form posts to `/language/switch` with `lang=en|ar` and saves the choice in the session.
- To switch manually inside code: `$_SESSION['lang'] = 'ar'; // or 'en'`.

## Database Integration (Optional)

The framework includes database configuration constants in `config/config.php`. You can extend the `Model` class in `App/Models/Model.php` to add:

- Query builder methods
- PDO integration
- Database abstraction layer

## Best Practices

1. **Keep Controllers Thin**: Move business logic to Models
2. **DRY Principle**: Use helper functions and base classes to avoid repetition
3. **Translate Everything**: Use translation keys for all user-facing text
4. **Secure Routes**: Add validation and authentication in controllers as needed
5. **Asset Management**: Place all CSS/JS in `public/assets/` and use the `asset()` helper
6. **Session Security**: Always validate session data before using it

## Troubleshooting

### 404 Page Not Found

- Check that routes are defined in `public/index.php`
- Ensure controller and method names match (StudlyCase for controller names)
- Verify the URL is correct

### Language Not Switching

- Ensure `sessions/` directory has write permissions
- Check that session_start() is called in `public/index.php`
- Verify language codes in form match those in JSON files

### Missing Translations

- Check that translation keys exist in both `en.json` and `ar.json`
- Verify JSON file syntax is valid (use JSON validator)
- Ensure language file path is correct

### Autoloader Issues

- Run `composer install` to regenerate vendor files
- Ensure namespace in class files matches directory structure

## Development Tips

### Enable Debug Mode

Set `ENVIRONMENT` to 'development' in `config/config.php` to see detailed error messages.

### View Page Source

Check generated HTML to verify RTL/LTR and language attributes are correct.

### Test Routes

- `http://localhost:8000/` — Home page (uses translations in the view)
- `http://localhost:8000/posts` — JSON API example (CORS-enabled)
- `http://localhost:8000/posts/1` — Post detail view (shows route param rendering)

## Future Enhancements

Potential features to extend this template:

- Database ORM/Query Builder
- Authentication & Authorization system
- Form validation framework
- API route handling
- Middleware support
- Email sending capabilities
- File upload handling
- Caching system
- Testing framework integration

## License

MIT License - See LICENSE file for details

## Support

For issues, questions, or contributions, please visit the project repository.
