# 🔐 CBT Platform - Role System Documentation

## Valid Roles

### 1. **Super Admin** (`super_admin`)
- **Purpose**: Manage entire platform
- **Permissions**:
  - Create/Edit/Delete schools
  - Create school admins
  - View monitoring dashboard
  - Manage subscriptions
  
- **Create By**: Direct database insertion or Super Admin panel only
- **Test Account**:
  ```
  Email: admin@cbt.test
  Password: password
  ```

### 2. **Admin Sekolah** (`admin_school`)
- **Purpose**: Manage single school
- **Permissions**:
  - Manage students
  - Create exams
  - View reports
  - Print exam cards
  
- **Create By**: Super Admin via `/super-admin/schools/{school}/admin/create`
- **Test Account**:
  ```
  Email: admin.sekolah@cbt.test
  Password: password
  School: SMA Negeri 1 Test (SMAN1TEST)
  ```

### 3. **Siswa** (Student)
- **Purpose**: Take exams
- **Permissions**:
  - View available exams
  - Take assigned exams
  - View score (if enabled)
  
- **Create By**: Admin Sekolah via `/school-admin/students`
- **Authentication**: Uses student guard (`auth:student`)
- **Test Accounts**:
  ```
  Student 1:
  - Username: budi001
  - Password: password
  - Class: X IPA 1
  
  Student 2:
  - Username: ani001
  - Password: password
  - Class: X IPA 1
  
  Student 3:
  - Username: citra001
  - Password: password
  - Class: X IPS 2
  ```

## Security Features

✅ **Role Validation**
- Role must be one of: `super_admin`, `admin_school`
- Database CHECK constraint enforces valid roles
- Model validation prevents invalid roles at application level

✅ **Public Registration Disabled**
- No public registration allowed
- Only Super Admin can create users
- Routes `/register` are disabled

✅ **Route Protection**
- Super Admin routes: `Route::middleware(['auth', 'role:super_admin'])`
- Admin Sekolah routes: `Route::middleware(['auth', 'role:admin_school', 'school.scope'])`
- Student routes: `Route::middleware(['auth:student'])`

## Creating Users in Production

### Super Admin
Only create via database seeding or direct SQL:
```bash
php artisan tinker
>>> App\Models\User::create([
    'name' => 'Super Admin Name',
    'email' => 'admin@example.com',
    'password' => Hash::make('secure_password'),
    'role' => 'super_admin'
])
```

### Admin Sekolah
Create via Super Admin Panel:
1. Login as Super Admin
2. Go to `/super-admin/schools`
3. Click school name
4. Click "Buat Admin Sekolah"
5. Fill form and submit

### Students
Create via Admin Sekolah Panel:
1. Login as Admin Sekolah
2. Go to `/school-admin/students`
3. Click "Tambah Siswa"
4. Fill form and submit

## Testing Role System

### Run Seeder
```bash
php artisan db:seed
```

This creates:
- 1 Super Admin
- 1 School
- 1 School Admin
- 3 Test Students
- 1 Test Exam with packages

### Login Test
1. Super Admin: `admin@cbt.test` / `password`
2. Admin Sekolah: `admin.sekolah@cbt.test` / `password`
3. Student: `budi001` / `password` (use Server ID: SMAN1TEST)

## Role Helper Methods

Use these methods in controllers/views:

```php
// Check role
if ($user->isSuperAdmin()) { }
if ($user->isSchoolAdmin()) { }

// Get valid roles
User::ROLES // ['super_admin', 'admin_school']
```

## Troubleshooting

**Issue**: "Invalid role" error when creating user
- **Solution**: Use only `super_admin` or `admin_school`

**Issue**: User can access wrong routes
- **Solution**: Check middleware in `bootstrap/app.php`

**Issue**: Student login fails
- **Solution**: Ensure student created via Admin Sekolah panel with valid school

---

**Last Updated**: 2026-02-26
**Platform**: CBT Platform v1.0
