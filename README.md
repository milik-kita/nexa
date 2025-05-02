
````markdown
# Nexa PHP MVC Framework

Welcome to **Nexa**, a lightweight and clean MVC framework for building PHP applications. This project includes tools to initialize the framework, serve it locally, handle database migrations, and generate components.

## 📦 Requirements

- PHP >= 8.0
- Composer
- MySQL (or other PDO-compatible DB)
- Git (optional)

## 🚀 Getting Started

### 1. Clone the Repository

```bash
git clone https://github.com/milik-kita/nexa.git
cd nexa
````

### 2. Install Dependencies

```bash
composer install
```

### 3. Configure Database

Update the database settings in:

```php
/config/database.php
```

### 4. Run the Application

Use `cmd.php` to serve the app locally and auto-run migrations:

```bash
php cmd.php serve
```

You can also customize host and port:

```bash
php cmd.php serve --host=0.0.0.0 --port=3000
```

To skip auto-migrations:

```bash
php cmd.php serve --skip-migrate.php
```

Visit: [http://localhost:8000](http://localhost:8000)

## ⚙️ Available Commands

```bash
php cmd.php [command] [options]
```

### Commands:

| Command       | Description                                         |
| ------------- | --------------------------------------------------- |
| `serve`       | Start the development server (auto runs migrations) |
| `migrate.php` | Run migrations manually                             |
| `init`        | Initialize framework structure and base files       |
| `make`        | Generate components (controller, model, migration)  |
| `help`        | Show help instructions                              |

### Options:

| Option               | Description                                            |
| -------------------- | ------------------------------------------------------ |
| `--host=HOSTNAME`    | Set custom host (default: `localhost`)                 |
| `--port=PORT`        | Set custom port (default: `8000`)                      |
| `--skip-migrate.php` | Skip migrations during `serve`                         |
| `--name=NAME`        | Name of the component (for `make` command)             |
| `--type=TYPE`        | Type of component (`controller`, `model`, `migration`) |

### Examples

```bash
php cmd.php init
php cmd.php make --name=UserController --type=controller
php cmd.php migrate.php
```

## 🛠 Folder Structure

```
app/                → MVC controllers, models, and views
config/             → App and DB configuration
database/Models/    → SQL migration files
public/             → Public web root (index.php)
routes/             → Application routes
vendor/             → Composer packages
cmd.php             → Command manager script
```

## 🧪 Example Migration

SQL files placed in `/database/Models` will be run during migrations:

```sql
/* Create users table */
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255),
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## 🙌 Contributing

Contributions are welcome! Fork the repo and create a pull request.

---

Made with ❤️ by [Kita](https://github.com/milik-kita)

```