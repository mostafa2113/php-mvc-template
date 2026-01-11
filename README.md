# PHP MVC Translation Template

A lightweight, flexible PHP MVC framework with built-in multi-language support (English/Arabic) and automatic RTL/LTR layout switching.

## Features

### Core Framework Features

- **MVC Architecture**: Clean separation of concerns with Models, Views, and Controllers
- **Flexible Routing System**: Pattern-based route matching with parameter extraction
- **PSR-4 Autoloading**: Proper namespace-based class autoloading via Composer
- **Session Management**: Built-in session handling for state persistence

### Localization & Internationalization

- **Multi-Language Support**: Out-of-the-box support for English and Arabic
- **JSON-Based Translations**: Simple, maintainable translation files
- **Session-Based Language Persistence**: User language preference is saved across sessions
- **Automatic RTL/LTR Switching**: HTML direction attribute automatically set based on language
- **Dynamic Language Switching**: Inline language switcher in the header

### Developer Features

- **Helper Functions**: Convenient utility functions for URLs, assets, and media
- **Layout System**: Template inheritance with layout wrapper
- **View Rendering**: Automatic view compilation with data extraction
- **Environment Configuration**: Development/production environment settings with error handling
- **Database Ready**: Pre-configured database connection constants (can be extended with ORM/Query Builder)

## Installation & Setup

### Prerequisites

- PHP 7.4 or higher
- Composer
- Web server (Apache, Nginx, or PHP built-in server)

### Installation Steps

1. **Clone the repository**:

```bash
git clone https://github.com/your-repo/php-mvc-project-template.git
cd php-mvc-project-template
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

**Route Pattern Examples**:

```php
$router->add('', ['controller' => 'Home', 'action' => 'index']);           // Root URL
$router->add('posts/{id}', ['controller' => 'Posts', 'action' => 'view']); // Dynamic segments
$router->add('language/switch', ['controller' => 'Language', 'action' => 'switch']);
```

### Adding Translations

1. **Add translation keys** to `config/languages/en.json`:

```json
{
  "home_title": "Welcome Home",
  "nav_about": "About Us",
  "footer_copyright": "All rights reserved"
}
```

2. **Add same keys** to `config/languages/ar.json` with Arabic translations:

```json
{
  "home_title": "أهلا بك في الصفحة الرئيسية",
  "nav_about": "عن موقعنا",
  "footer_copyright": "جميع الحقوق محفوظة"
}
```

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

Create `App/Models/Post.php`:

```php
<?php

namespace App\Models;

class Post extends Model
{
    protected $table = 'posts';

    public function getAllPosts()
    {
        // Add database queries here
    }
}
```

### Using Translations in Views

In any view file, use the `translate()` method inherited from Controller:

```php
<h1><?= $this->translate('welcome_title') ?></h1>
<p><?= $this->translate('welcome_message') ?></p>
```

### Using Helper Functions

Global helper functions available throughout the application:

```php
// Generate full URL
echo url('posts/5');                    // http://localhost:8000/posts/5

// Asset URL (CSS, JS, images)
echo asset('css/style.css');            // http://localhost:8000/assets/css/style.css
echo asset('js/app.js');

// Media/Upload URL
echo media('profile-pic.jpg');          // http://localhost:8000/uploads/profile-pic.jpg

// Redirect to another page
redirect('posts/5');                    // Redirects to /posts/5
```

### Language Switching

The language switcher is automatically included in the header. Users can click English or العربية buttons to switch languages. The selection is saved in the session and persists across page visits.

To manually switch language in a controller:

```php
$_SESSION['lang'] = 'ar'; // or 'en'
```

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

Use browser address bar to test routes and parameters:

- `http://localhost:8000/` - Home page
- `http://localhost:8000/posts/1` - Single post view

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
