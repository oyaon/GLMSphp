# API Documentation

## Authentication

### Login
```http
POST /api/login
Content-Type: application/json

{
    "email": "user@example.com",
    "password": "password123"
}
```

**Response**
```json
{
    "status": "success",
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "user@example.com",
        "role": "user"
    }
}
```

### Register
```http
POST /api/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "user@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

## Books

### List Books
```http
GET /api/books
Authorization: Bearer {token}
```

**Query Parameters**
- `page`: Page number (default: 1)
- `per_page`: Items per page (default: 10)
- `search`: Search term
- `sort`: Sort field (title, author, created_at)
- `order`: Sort order (asc, desc)

### Get Book Details
```http
GET /api/books/{id}
Authorization: Bearer {token}
```

### Add Book
```http
POST /api/books
Authorization: Bearer {token}
Content-Type: application/json

{
    "title": "Book Title",
    "author": "Author Name",
    "isbn": "978-3-16-148410-0",
    "quantity": 10,
    "description": "Book description"
}
```

### Update Book
```http
PUT /api/books/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "title": "Updated Title",
    "quantity": 15
}
```

### Delete Book
```http
DELETE /api/books/{id}
Authorization: Bearer {token}
```

## Borrowing

### Borrow Book
```http
POST /api/borrows
Authorization: Bearer {token}
Content-Type: application/json

{
    "book_id": 1,
    "borrow_date": "2024-03-15",
    "return_date": "2024-04-15"
}
```

### Return Book
```http
PUT /api/borrows/{id}/return
Authorization: Bearer {token}
```

### List Borrowed Books
```http
GET /api/borrows
Authorization: Bearer {token}
```

## Error Responses

### Validation Error
```json
{
    "status": "error",
    "message": "Validation failed",
    "errors": {
        "email": ["The email field is required"],
        "password": ["The password must be at least 8 characters"]
    }
}
```

### Authentication Error
```json
{
    "status": "error",
    "message": "Unauthorized"
}
```

### Not Found Error
```json
{
    "status": "error",
    "message": "Resource not found"
}
``` 