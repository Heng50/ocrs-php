# Admin Navigation Component

This directory contains reusable navigation components for the OCRS admin section.

## Files

- `admin_nav.php` - Main navigation component with header and sidebar
- `admin_nav_end.php` - Closing component that includes footer
- `README.md` - This documentation file

## Usage

### Basic Implementation

To use the navigation component in any admin page, replace the existing navigation code with these two lines:

```php
<?php
    session_start();
    require_once '../header.php';
    // Add any other required files here
    require_once 'components/admin_nav.php';
?>

<!-- Your page content goes here -->

<?php require_once 'components/admin_nav_end.php'; ?>
```

### Example: Before (Old Way)

```php
<?php
    session_start();
    require_once '../header.php';
?>

<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <!-- ... header content ... -->
    </div>

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-sm border-r border-gray-200">
            <!-- ... sidebar navigation ... -->
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <!-- Your page content -->
        </div>
    </div>
</div>

<?php require_once '../footer.php'; ?>
```

### Example: After (New Way)

```php
<?php
    session_start();
    require_once '../header.php';
    require_once 'components/admin_nav.php';
?>

<!-- Your page content goes here -->

<?php require_once 'components/admin_nav_end.php'; ?>
```

## Features

### Automatic Active State Detection

The navigation component automatically detects the current page and highlights the active menu item. It uses the current filename to determine which menu item should be highlighted.

### Complete Navigation Menu

The component includes all admin navigation items:
- Dashboard
- Semester Management
- Category Management
- Programme Management
- Course Management
- Class Sessions
- Enrollment Management
- Student Management
- Student Approval
- Grades Management
- Reports

### Consistent Styling

All navigation items use consistent styling with:
- Hover effects with gold background (`#FFD700`)
- SVG icons for each menu item
- Smooth transitions
- Responsive design

## Benefits

1. **DRY Principle**: No more code duplication across admin pages
2. **Consistency**: All admin pages will have identical navigation
3. **Maintainability**: Changes to navigation only need to be made in one place
4. **Active State**: Automatic highlighting of current page
5. **Clean Code**: Significantly reduces the amount of HTML in each admin page

## Migration Guide

To migrate existing admin pages to use the component:

1. Remove the entire navigation HTML structure (header, sidebar, main content wrapper)
2. Add `require_once 'components/admin_nav.php';` after your other includes
3. Add `require_once 'components/admin_nav_end.php';` at the end of the file
4. Keep only your page-specific content between these two includes

## Files Updated

The following files have been updated to use the new navigation component:
- `admin/dashboard.php`
- `admin/student.php`

You can apply the same pattern to all other admin files for consistency. 