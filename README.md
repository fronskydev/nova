<table align="center"><tr><td align="center" width="9999">
<img src="public/assets/images/logo.png" align="center" width="150" alt="Nova Logo">

<p align="center">
  <img src="https://img.shields.io/badge/version-v1.0.0-blue.svg" align="center" alt="Version Badge"/>
  <img src="https://img.shields.io/badge/license-MIT-green.svg" align="center" alt="License Badge"/>
</p>

# About Nova

Nova is a modern, lightweight PHP framework designed for building web applications with performance and scalability in mind. Powered by **Doctrine DBAL** for database support, it provides intuitive tools to make development enjoyable.

<a href="https://www.flaticon.com/free-icons/" title="icons">Icons created by Freepik - Flaticon</a>
</td></tr></table>

## Table of Contents
- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
  - [Clone the Repository](#clone-the-repository)
  - [Install Dependencies](#install-dependencies)
  - [Set Up Your Environment](#set-up-your-environment)
  - [Installing Components](#installing-components)
- [Database Integration with Doctrine DBAL](#database-integration-with-doctrine-dbal)
  - [Database Connection Setup](#database-connection-setup)
  - [Database Configuration](#database-configuration)
- [Usage](#usage)
  - [Routing](#routing)
  - [Controllers](#controllers)
  - [Middleware](#middleware)
  - [Views](#views)
  - [Mail](#mail)
    - [Mail Configuration](#mail-configuration)
  - [Models](#models)
    - [Querying Models](#querying-models)
- [License](#license)

## Features

- **Fast and Lightweight**: Optimized for performance with minimal overhead.
- **Environment Configuration**: Seamless environment management with `.env` files.
- **Emailing**: Built-in email support with customizable templates.
- **Extensible**: Easily extendable with custom components and services.
- **Database Support**: Integration with **Doctrine DBAL** for easy database access.

## Requirements

- PHP 8.3 or higher
- Composer
- MySQL (or other supported database for Doctrine DBAL)

## Installation

Follow these steps to get started with Nova.

### Clone the Repository

Run this command to clone the repository:

```bash
git clone https://github.com/fronskydev/nova.git
cd nova
```

### Install Dependencies

Install the required Composer dependencies:

```bash
composer install
```

### Set Up Your Environment

Copy the [example environment file](secure/example.env) to `.env`:

```bash
cp secure/example.env secure/.env
```

Edit the `.env` file to set your environment-specific variables such as database and mail configuration.

### Installing Components

In **Nova**, you can install components designed specifically for the platform. Below is a list of all certified components developed by **Fronsky**:

#### Status Overview
![Available](https://img.shields.io/badge/Status-Available-brightgreen)  
![In Development](https://img.shields.io/badge/Status-In%20Development-yellow)  
![Coming Soon](https://img.shields.io/badge/Status-Coming%20Soon-lightgrey)  
![Deprecated](https://img.shields.io/badge/Status-Deprecated-red)

| Component                                                         | Details                                                                                                                                   |
|-------------------------------------------------------------------|-------------------------------------------------------------------------------------------------------------------------------------------|
| [**Nova Auth**](https://github.com/fronskydev/nova-auth/releases) | ![Verified](https://img.shields.io/badge/Certified-✔️-blue) ![Available](https://img.shields.io/badge/Status-Available-brightgreen)       |

## Database Integration with Doctrine DBAL

Nova uses **Doctrine DBAL** to provide seamless database integration. Here's how you can work with the database in your application.

### Database Connection Setup

Nova uses the `Database` service to connect to your database using the credentials from the `.env` file.

### Database Configuration

Make sure to define the following database parameters in your `.env` file:

```dotenv
DB_DRIVER=pdo_mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

This allows Nova to connect to your MySQL database using **Doctrine DBAL**.

## Usage

### Routing

Define your application routes in [routes/web.php](secure/routes/web.php). Routes map HTTP requests to actions.

Example:

```php
return [
    "/" => [
        "method" => ["GET", "POST"],
        "action" => "HomeController@index"
    ],
    "/home" => [
        "method" => "GET",
        "action" => "HomeController@index",
        "middleware" => ["HomeMiddleware"]
    ],
];
```

### Controllers

Controllers contain the logic for your application. You can create controllers inside `src/Controllers`.

Example:

```php
namespace src\Controllers;

use src\Abstracts\Controller;
use src\Core\PageInfo;

class HomeController extends Controller
{
    private PageInfo $pageInfo;

    public function __construct()
    {
        $this->pageInfo = new PageInfo();
    }

    public function index(): int
    {
        $this->pageInfo->title = "Nova | Home";
        $data = ["name" => "Nova"];
        $this->render("home", $data, $this->pageInfo);
        return 200;
    }
}
```

### Middleware

Middleware provides a convenient mechanism for filtering HTTP requests entering your application. You can create middleware inside `src/Middleware`.

Example:

```php
namespace src\Middleware;

use src\Interfaces\IMiddleware;

class HomeMiddleware implements IMiddleware
{
    public function handle(): int
    {
        echo "<script>alert('HomeMiddleware');</script>";
        return 200;
    }
}
```

### Views

Nova uses simple file-based views. Store your views in [resources/views](secure/resources/views) and render them using the render() helper.

Example:

```php
// In a controller (src/Controllers/HomeController.php)
$pageInfo = new PageInfo();
$pageInfo->title = "Nova | Home";
$data = ["name" => "Nova"];
$this->render("home", $data, $pageInfo);

// In a view file (resources/views/home.php)
<h1>Welcome to Nova!</h1>
```

### Mail

Nova comes with built-in email support using PHPMailer. Email templates can be placed in the [resources/mailer](secure/resources/mailer) directory.

Example:

```php
use src\Mailer\Mailer;

$mail = new Mailer();
$result = $mail->setHtmlTemplate(MAILER_DIR . "/notification.html", [
        'title' => 'New Notification!',
        'message' => 'You have received a new notification.',
        'action_url' => 'https://example.com/notifications',
        'action_text' => 'View Notification'
    ])
    ->addSubject("Notification")
    ->addRecipient("user@example.com")
    ->send();

echo $result; // "Success" or error message
```

#### Mail Configuration

Make sure to define the following mail parameters in your `.env` file:

```dotenv
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your_email@example.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME='Noreply Nova'
MAIL_REPLY_TO_ADDRESS=support@example.com
MAIL_REPLY_TO_NAME='Support Nova'
```

### Models

Models are used to interact with your database. You can create models inside `src/Models`.

Example:

```php
namespace src\Models;

use src\Abstracts\Model;

class Users extends Model
{
    protected function getTable(): string
    {
       return "users";
    }
}
```

#### Querying Models

You can query your models using the methods provided by the `Model` class. Or you can create your own custom queries using the `query()` method.

Example:

```php
use src\Models\User;

$user = new User();
$users = $user->all(); // Get all users
$user = $user->find(1); // Get user with ID 1
$users = $user->findBy("email", "user@example.com"); // Find all users with email "user@example.com"
$user->delete(1); // Delete user with ID 1
$user->deleteBy("email", "user@example.com"); // Delete all users with email "user@example.com"
$user->create(["name" => "John Doe", "email" => "john.doe@example.com"]); // Create a new user
$id = $user->getLastInsertedId(); // Get the ID of the last inserted user
$user->update(1, ["name" => "Jane Doe"]); // Update user with ID 1
$user->updateBy("email", "john.doe@example.com", ["name" => "Jane Doe"]); // Update all users with email "john.doe@example.com"
$user->query("SELECT * FROM users WHERE email = 'john.doe@example.com'"); // Custom query
```

## License

Nova is open-source software licensed under the [MIT](LICENSE) license.
