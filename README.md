<p align="center">
    <img src="https://github.com/user-attachments/assets/0f373666-c7b5-43c6-a4ea-6308080d3a8d" alt="Skeleton Php">
    <p align="center">
        <a href="https://github.com/nunophp/style/actions"><img alt="GitHub Workflow Status (master)" src="https://github.com/nunophp/style/actions/workflows/tests.yml/badge.svg"></a>
        <a href="https://packagist.org/packages/nunophp/style"><img alt="Total Downloads" src="https://img.shields.io/packagist/dt/nunophp/style"></a>
        <a href="https://packagist.org/packages/nunophp/style"><img alt="Latest Version" src="https://img.shields.io/packagist/v/nunophp/style"></a>
        <a href="https://packagist.org/packages/nunophp/style"><img alt="License" src="https://img.shields.io/packagist/l/nunophp/style"></a>
    </p>
</p>

------
Nunophp Style is a Laravel package that sets up your project with Nuno Maduro’s recommended tools and configurations, including Pest, Pint, PHPStan, and Rector, all streamlined via a single Artisan command.

> **Requires [PHP 8.3+](https://php.net/releases/)**

⚡️ Install the package into your Laravel project using [Composer](https://getcomposer.org):

```bash
composer require nunophp/style --dev
```

The package’s **service provider** (Nunophp\Style\StyleServiceProvider) will be auto-discovered by Laravel.

🚀 Run the setup command to configure your project with Nuno’s recommended style:

```bash
php artisan style:setup
```

This command will:

- Prompt to install development tools (Pest, Pint, PHPStan, Rector).
- Update composer.json with modern testing scripts.
- Optionally update AppServiceProvider with recommended configurations.
- Publish configuration files (pint.json, phpstan.neon, rector.php), with overwrite prompts if they exist.

### Example Commands After Setup

🧹 Lint your codebase with Pint:

```bash
composer lint
```

✅ Refactor with Rector:

```bash
composer refacto
```

⚗️ Analyze with PHPStan:

```bash
composer test:types
```

✅ Test with Pest:

```bash
composer test:unit
```

🚀 Run the full test suite:

```bash
composer test
```

### Output Example

When you run `php artisan style:setup`, expect a polished interface:

```
╔════════════════════════════════════╗
║      Starting Nuno Style Setup     ║
╚════════════════════════════════════╝

Do you want to install required development tools? (Pest, Pint, PHPStan, Rector) [yes]:
> no

 📝 Updating composer.json...
 ✓ composer.json updated with testing scripts.

Do you want to update AppServiceProvider with recommended configurations? This will replace your existing file. [yes]:
> no

 📦 Publishing configuration files...
 ↳ Skipped pint.json (not overwritten).
 ✓ Published phpstan.neon.
 ↳ Skipped rector.php (not overwritten).

╔════════════════════════════════════╗
║    Nuno Style Setup Complete       ║
╚════════════════════════════════════╝
 🎉 Run "composer test" to verify your setup!
```

## Contributing
Contributions are welcome! Please submit pull requests to the GitHub repository.

**Nunophp Style** was created by **[Micheal Ataklt](https://www.linkedin.com/in/matakltm-code)** under the **[MIT license](https://opensource.org/licenses/MIT)**.
