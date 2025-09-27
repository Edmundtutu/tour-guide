# Tour Guide - Routing Configuration

## Overview
This document explains the routing configuration for the Uganda Tour Guide application and how to troubleshoot routing issues.

## File Structure
```
tour-guide/
├── .htaccess                 # Main URL rewriting rules
├── public/
│   ├── .htaccess           # Public directory rules
│   ├── index.php           # Main entry point
│   └── test-routing.php     # Routing test file
└── app/core/Router.php     # Router class
```

## Configuration Files

### 1. Main .htaccess (Root Directory)
```apache
# Uganda Tour Guide - .htaccess Configuration
RewriteEngine On

# Security: Block access to sensitive directories and files
RewriteRule ^(app|config|database|views)/ - [F,L]
RewriteRule ^\.(htaccess|htpasswd|env|log)$ - [F,L]

# Handle static assets (CSS, JS, images)
RewriteCond %{REQUEST_FILENAME} -f
RewriteRule ^assets/ - [L]

# Redirect root to public directory
RewriteCond %{REQUEST_URI} !^/tour-guide/public/
RewriteCond %{REQUEST_URI} !^/public/
RewriteRule ^(.*)$ public/$1 [L]

# Clean URLs - route everything through index.php
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ public/index.php [QSA,L]
```

### 2. Public .htaccess
```apache
# Public directory .htaccess
RewriteEngine On

# Handle static assets
RewriteCond %{REQUEST_FILENAME} -f
RewriteRule ^assets/ - [L]

# Route everything else through index.php
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```

## Router Class Features

### Path Processing
The Router class processes URLs in the following order:
1. Remove query string parameters
2. Remove base path if running in subdirectory
3. Remove `/public/` from path if present
4. Ensure path starts with `/`

### Debug Features
- **Route Debug**: Add `?debug=routes` to any URL to see all available routes
- **Error Debug**: Add `?debug=true` to see detailed error information
- **Similar Routes**: When a route is not found, the router suggests similar routes

### Error Handling
- **AJAX Requests**: Returns JSON error responses
- **Regular Requests**: Shows user-friendly error pages
- **Fallback**: Simple HTML error page if View class fails

## Available Routes

### Tourist Routes
- `GET /` - Home page
- `GET /home` - Home page (alias)
- `GET /hotels` - Hotel listing
- `GET /hotel-details` - Hotel details
- `GET /book` - Booking form
- `POST /book` - Process booking
- `GET /my-bookings` - User bookings
- `GET /destinations` - Destinations listing

### Authentication Routes
- `GET /login` - Login form
- `POST /login` - Process login
- `GET /register` - Registration form
- `POST /register` - Process registration
- `GET /logout` - Logout

### API Routes
- `POST /api/search` - Hotel search
- `POST /api/calculate-price` - Price calculation
- `POST /api/cancel-booking` - Cancel booking
- `GET /api/download-receipt` - Download receipt

### Host Routes
- `GET /host/dashboard` - Host dashboard
- `GET /host/hotels` - Host hotels
- `GET /host/create-hotel` - Create hotel form
- `POST /host/create-hotel` - Process hotel creation
- `GET /host/edit-hotel` - Edit hotel form
- `POST /host/edit-hotel` - Process hotel update
- `GET /host/rooms` - Room management
- `GET /host/create-room` - Create room form
- `POST /host/create-room` - Process room creation
- `GET /host/bookings` - Host bookings
- `POST /host/approve-booking` - Approve booking
- `POST /host/reject-booking` - Reject booking
- `GET /host/subscription` - Subscription info
- `GET /host/subscribe` - Subscribe form
- `POST /host/subscribe` - Process subscription

### Admin Routes
- `GET /admin/dashboard` - Admin dashboard
- `GET /admin/users` - User management
- `GET /admin/hotels` - Hotel management
- `POST /admin/approve-hotel` - Approve hotel
- `POST /admin/block-hotel` - Block hotel
- `GET /admin/subscriptions` - Subscription management
- `GET /admin/bookings` - Booking management

## Troubleshooting

### Common Issues

#### 1. "Route not found" Errors
**Symptoms**: Getting 404 errors for valid routes
**Solutions**:
- Check if .htaccess files are properly configured
- Verify Apache mod_rewrite is enabled
- Check if the route is registered in index.php
- Use `?debug=routes` to see all available routes

#### 2. Static Assets Not Loading
**Symptoms**: CSS/JS/images not loading
**Solutions**:
- Check if assets directory exists
- Verify .htaccess rules for static assets
- Check file permissions
- Test direct access to asset files

#### 3. Infinite Redirects
**Symptoms**: Browser shows "too many redirects" error
**Solutions**:
- Check .htaccess rules for conflicts
- Verify RewriteCond conditions
- Check for conflicting rules

#### 4. Path Issues
**Symptoms**: Routes not matching correctly
**Solutions**:
- Use test-routing.php to debug path processing
- Check base path configuration
- Verify URL structure

### Debug Tools

#### 1. Routing Test File
Access: `http://localhost/tour-guide/public/test-routing.php`
- Shows server information
- Displays path processing steps
- Provides test links

#### 2. Route Debug
Add `?debug=routes` to any URL to see:
- All registered routes
- Current request details
- Route matching information

#### 3. Error Debug
Add `?debug=true` to see:
- Detailed error messages
- Stack traces
- Debug information

### Testing Routes

#### Manual Testing
1. **Home Page**: `http://localhost/tour-guide/`
2. **Hotels**: `http://localhost/tour-guide/hotels`
3. **Login**: `http://localhost/tour-guide/login`
4. **Debug**: `http://localhost/tour-guide/?debug=routes`

#### Automated Testing
Use the test-routing.php file to verify:
- Path processing
- Route registration
- Static asset access
- Error handling

## Configuration for Different Environments

### Development (XAMPP)
```apache
# Base URL: http://localhost/tour-guide/
# Document Root: C:\xampp\htdocs\tour-guide\
```

### Production (Apache)
```apache
# Base URL: https://yourdomain.com/
# Document Root: /var/www/html/tour-guide/
```

### Subdirectory Installation
```apache
# Base URL: https://yourdomain.com/tour-guide/
# Document Root: /var/www/html/tour-guide/
```

## Security Considerations

### .htaccess Security
- Blocks access to sensitive directories
- Prevents direct access to configuration files
- Hides server information
- Protects against common attacks

### Router Security
- Validates all input parameters
- Prevents directory traversal
- Handles errors gracefully
- Logs security events

## Performance Optimization

### Caching
- Static assets cached for 1 month
- Compressed responses
- Optimized rewrite rules

### URL Structure
- Clean URLs without index.php
- SEO-friendly structure
- Consistent routing patterns

## Maintenance

### Regular Checks
1. Test all routes monthly
2. Verify static asset loading
3. Check error handling
4. Review security logs

### Updates
1. Update routes when adding new features
2. Test routing after server updates
3. Verify .htaccess compatibility
4. Check for new security issues

## Support

### Common Solutions
1. **Clear browser cache**
2. **Restart Apache**
3. **Check file permissions**
4. **Verify .htaccess syntax**

### Debug Steps
1. Use test-routing.php
2. Check server error logs
3. Enable debug mode
4. Verify configuration

This routing system provides a robust, secure, and maintainable foundation for the Tour Guide application.
