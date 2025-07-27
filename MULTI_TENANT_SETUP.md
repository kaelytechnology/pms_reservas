# Multi-Tenant Setup Guide

## Overview

This PMS Hotel package has been configured with **smart migration loading** to automatically support both single-tenant and multi-tenant applications:

- **If `database/migrations/tenant/` directory exists**: Migrations are loaded from there (multi-tenant mode)
- **If `database/migrations/tenant/` directory doesn't exist**: Migrations are loaded from the package (single-tenant mode)

This automatic detection makes the package work seamlessly in any environment without manual configuration.

## Installation for Multi-Tenant Applications

### 1. Install the Package

```bash
composer require kaelytechnology/pms_hotel
```

### 2. Automatic Detection

The package will automatically detect your setup:

- **Single-tenant apps**: Migrations load automatically from the package
- **Multi-tenant apps**: If you have `database/migrations/tenant/` directory, migrations load from there

### 3. For Multi-Tenant Setup

If you want tenant-specific migrations, simply publish them:

```bash
php artisan vendor:publish --tag=pms-hotel-migrations
```

This creates `database/migrations/tenant/` directory and the package will automatically use it.

### 4. For Single-Tenant Setup

No additional steps needed! The package works out of the box:

```bash
php artisan migrate
```

### 5. Publish Configuration (Optional)

```bash
php artisan vendor:publish --tag=pms-hotel-config
```

### 6. Run Tenant Migrations (Multi-tenant only)

Depending on your multi-tenancy package:

```bash
# Example with Spatie Laravel Multitenancy
php artisan tenants:artisan "migrate"

# Example with Stancl Tenancy
php artisan tenants:migrate
```

## Key Changes Made

1. **Removed `loadMigrationsFrom()`**: This prevents automatic migration execution in the main database
2. **Added tenant-specific publish path**: Migrations are published to `database/migrations/tenant/`
3. **Maintained backward compatibility**: Option to publish to main migrations directory still available

## Migration Strategy Options

### Option 1: Tenant-Only Tables
- Publish migrations with `--tag=pms-hotel-migrations`
- All PMS tables will be created in tenant databases only

### Option 2: Mixed Approach
- Some tables in main database (e.g., global configurations)
- Some tables in tenant databases (e.g., reservations, restaurant data)
- Manually move specific migrations between directories as needed

### Option 3: Main Database Only
- Publish migrations with `--tag=pms-hotel-migrations-main`
- All PMS tables will be created in the main database
- Use tenant identification columns in your application logic

## Troubleshooting

### Issue: Migrations Running in Main Database
- **Cause**: Using `loadMigrationsFrom()` in ServiceProvider
- **Solution**: This has been removed in the updated package

### Issue: Tables Not Found in Tenant Database
- **Cause**: Migrations not published to tenant directory
- **Solution**: Run `php artisan vendor:publish --tag=pms-hotel-migrations`

### Issue: Need to Reset Migration State
- **Solution**: 
  ```bash
  # For tenant databases
  php artisan tenants:artisan "migrate:reset"
  php artisan tenants:artisan "migrate --path=database/migrations/tenant"
  ```

## Compatibility

- **Spatie Laravel Multitenancy**: ✅ Compatible
- **Stancl Tenancy**: ✅ Compatible
- **Custom Multi-tenancy Solutions**: ✅ Compatible
- **Single Tenant Applications**: ✅ Compatible (use main migrations)

## Notes

- All models use the default database connection
- No tenant-specific connection configuration in models
- Multi-tenancy is handled at the database/migration level
- Existing applications can migrate by republishing migrations to appropriate directories