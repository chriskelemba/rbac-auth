# STL RBAC Auth Package

A simple Role-Based Access Control (RBAC) package for Laravel.  
Provides user, role, and permission management with policies.

## Installation

1. Require the package via Composer (for local development, use path repository):
```bash
composer require stl/rbac-auth
```

2. Seed the database with default roles and permissions:
```bash
php artisan rbac:seed
```