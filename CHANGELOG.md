# Changelog

## [1.0.1] - 2025-10-09

### Bugfixes
- Fixed permission checks now properly restricting unauthorized access
- Improved policy enforcement to prevent unauthorized operations

## [1.0.0] - 2025-10-09

### Added
- Initial release of `stl/rbac-auth`.
- Complete RBAC system with:
  - User, Role, and Permission management.
  - Assign/revoke roles and permissions.
  - Default seeders for users, roles, and permissions.
- Authentication endpoints:
  - Login, Logout, Fetch current user.
- Policy-based permission checks for models.
- Laravel 12 and Sanctum support.