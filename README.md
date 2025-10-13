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

3. API Routes
```bash
# Authentication
POST   /api/login                                    # User login
GET    /api/me                                       # Get authenticated user
POST   /api/logout                                   # User logout

# Users
GET    /api/users                                    # List all users
POST   /api/users                                    # Create user
GET    /api/users/{user}                             # Get user
PUT    /api/users/{user}                             # Update user
DELETE /api/users/{user}                             # Delete user
GET    /api/users/{user}/roles                       # Get user roles
POST   /api/users/{user}/roles/{role}                # Assign role to user
DELETE /api/users/{user}/roles/{role}                # Revoke role from user

# Roles
GET    /api/roles                                    # List all roles
POST   /api/roles                                    # Create role
GET    /api/roles/{role}                             # Get role
PUT    /api/roles/{role}                             # Update role
DELETE /api/roles/{role}                             # Delete role
GET    /api/roles/{role}/permissions                 # Get role permissions
POST   /api/roles/{role}/permissions/{permission}    # Assign permission to role
DELETE /api/roles/{role}/permissions/{permission}    # Revoke permission from role

# Permissions
GET    /api/permissions                              # List all permissions
POST   /api/permissions                              # Create permission
GET    /api/permissions/{permission}                 # Get permission
PUT    /api/permissions/{permission}                 # Update permission
DELETE /api/permissions/{permission}                 # Delete permission
```