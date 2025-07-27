# Multi-Tenant Setup Guide

## Overview

This PMS Hotel package follows Laravel's best practices for multi-tenant applications by **requiring manual migration execution** in tenant contexts. This approach ensures:

- ✅ **Clear separation**: Migrations only run where intended
- ✅ **Manual control**: Developers decide when and where to execute migrations
- ✅ **Conflict prevention**: Avoids accidental execution in main database
- ✅ **Standard practices**: Follows Laravel's recommended patterns for multi-tenant packages

## Installation for Multi-Tenant Applications

### 1. Install the Package

```bash
composer require kaelytechnology/pms_hotel
```

### 2. Publish Migrations to Tenant Directory

```bash
php artisan vendor:publish --tag=pms-hotel-migrations
```

This creates `database/migrations/tenant/` directory with all PMS migrations.

### 3. Execute Migrations in Tenant Context

Depending on your multi-tenancy package:

```bash
# Example with Spatie Laravel Multitenancy
php artisan tenants:artisan "migrate"

# Example with Stancl Tenancy
php artisan tenants:migrate

# Generic tenant command
php artisan tenants:run "migrate"
```

### 4. Publish Configuration (Optional)

```bash
php artisan vendor:publish --tag=pms-hotel-config
```

## Installation for Single-Tenant Applications

### 1. Install the Package

```bash
composer require kaelytechnology/pms_hotel
```

### 2. Publish Migrations to Main Directory

```bash
php artisan vendor:publish --tag=pms-hotel-migrations-main
```

### 3. Run Migrations

```bash
php artisan migrate
```

## Migration Strategy Options

### Option 1: Tenant-Only Tables (Recommended)
- Publish migrations with `--tag=pms-hotel-migrations`
- All PMS tables will be created in tenant databases only
- Best for true multi-tenant isolation

### Option 2: Main Database Only
- Publish migrations with `--tag=pms-hotel-migrations-main`
- All PMS tables will be created in the main database
- Use tenant identification columns in your application logic
- Suitable for single-tenant or shared database approaches

### Option 3: Mixed Approach (Advanced)
- Some tables in main database (e.g., global configurations)
- Some tables in tenant databases (e.g., reservations, restaurant data)
- Manually move specific migrations between directories as needed
- Requires careful planning and custom implementation

## Key Benefits of Manual Migration Approach

1. **🔒 Security**: Prevents accidental migration execution in wrong database
2. **🎯 Precision**: Developers have full control over where migrations run
3. **📋 Standards**: Follows Laravel's recommended patterns for packages
4. **🔄 Flexibility**: Works with any multi-tenancy solution
5. **🛡️ Safety**: No automatic behavior that could cause data issues

## Troubleshooting

### Issue: Tables Not Found in Tenant Database
- **Cause**: Migrations not published to tenant directory
- **Solution**: Run `php artisan vendor:publish --tag=pms-hotel-migrations`

### Issue: Need to Reset Migration State
- **Solution**: 
  ```bash
  # For tenant databases
  php artisan tenants:artisan "migrate:reset"
  php artisan tenants:artisan "migrate"
  ```

### Issue: Migrations Running in Wrong Database
- **Cause**: Using wrong publish tag or migration command
- **Solution**: Ensure you're using the correct commands for your setup

### Issue: Permission Denied During Migration
- **Cause**: Insufficient database permissions in tenant context
- **Solution**: Verify tenant database user has CREATE, ALTER, DROP permissions

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