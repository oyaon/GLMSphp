# GLMSphp Documentation

## Overview
GLMSphp is a Library Management System built with PHP. It provides features for managing books, users, borrowing, and administrative functions.

## Table of Contents
1. [Installation](#installation)
2. [Configuration](#configuration)
3. [Database Schema](#database-schema)
4. [API Documentation](#api-documentation)
5. [Security](#security)
6. [Testing](#testing)
7. [Deployment](#deployment)

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Composer
- Apache/Nginx web server

### Setup Steps
1. Clone the repository:
```bash
git clone https://github.com/oyaon/GLMSphp.git
cd GLMSphp
```

2. Run the development setup script:
```bash
chmod +x setup-dev.sh
./setup-dev.sh
```

3. Configure your web server to point to the `public` directory

4. Create and configure your `.env` file:
```bash
cp .env.example .env
```

## Configuration

### Environment Variables
- `DB_HOST`: Database host (default: localhost)
- `DB_NAME`: Database name (default: bms)
- `DB_USER`: Database user
- `DB_PASS`: Database password
- `APP_URL`: Application URL
- `APP_ENV`: Environment (development/production)

### Security Settings
- CSRF Protection enabled
- Session security configured
- Password hashing with bcrypt
- File upload restrictions

## Database Schema

### Tables
1. `users`
   - id (PRIMARY KEY)
   - username
   - email
   - password
   - role
   - created_at
   - updated_at

2. `books`
   - id (PRIMARY KEY)
   - title
   - author
   - isbn
   - quantity
   - status
   - created_at
   - updated_at

3. `borrows`
   - id (PRIMARY KEY)
   - user_id (FOREIGN KEY)
   - book_id (FOREIGN KEY)
   - borrow_date
   - return_date
   - status

## API Documentation

### Authentication
- POST `/login`
- POST `/logout`
- POST `/register`

### Books
- GET `/books` - List all books
- GET `/books/{id}` - Get book details
- POST `/books` - Add new book
- PUT `/books/{id}` - Update book
- DELETE `/books/{id}` - Delete book

### Users
- GET `/users` - List all users
- GET `/users/{id}` - Get user details
- POST `/users` - Add new user
- PUT `/users/{id}` - Update user
- DELETE `/users/{id}` - Delete user

## Security

### Implemented Security Measures
1. Password Hashing
2. CSRF Protection
3. SQL Injection Prevention
4. XSS Protection
5. File Upload Validation
6. Session Security

### Best Practices
1. Always use prepared statements
2. Validate and sanitize user input
3. Implement proper access control
4. Regular security updates
5. Error logging and monitoring

## Testing

### Running Tests
```bash
./vendor/bin/phpunit
```

### Test Categories
1. Unit Tests
2. Integration Tests
3. Database Tests
4. Security Tests

## Deployment

### Production Setup
1. Set environment to production
2. Configure web server
3. Set up SSL certificate
4. Configure error logging
5. Set up backup system

### Maintenance
1. Regular updates
2. Database backups
3. Log rotation
4. Performance monitoring 