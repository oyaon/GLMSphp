# Development Guide

## Project Structure
```
GLMSphp/
├── config/             # Configuration files
├── docs/              # Documentation
├── public/            # Public files and entry point
├── src/               # Source code
│   ├── Controllers/   # Controller classes
│   ├── Models/        # Model classes
│   ├── Services/      # Business logic
│   └── Utils/         # Utility functions
├── tests/             # Test files
│   ├── Unit/         # Unit tests
│   └── Integration/  # Integration tests
├── vendor/            # Composer dependencies
└── storage/           # Application storage
    ├── cache/        # Cache files
    ├── logs/         # Log files
    └── uploads/      # Uploaded files
```

## Coding Standards

### PHP Code Style
- Follow PSR-12 coding standards
- Use type hints and return type declarations
- Document all classes, methods, and properties
- Keep methods small and focused
- Use meaningful variable and method names

Example:
```php
<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Book;
use App\Exceptions\BookNotFoundException;

class BookService
{
    /**
     * Find a book by its ID.
     *
     * @param int $id The book ID
     * @return Book
     * @throws BookNotFoundException
     */
    public function findById(int $id): Book
    {
        $book = Book::find($id);
        
        if (!$book) {
            throw new BookNotFoundException("Book with ID {$id} not found");
        }
        
        return $book;
    }
}
```

### Database
- Use prepared statements for all queries
- Follow naming conventions:
  - Tables: plural, snake_case
  - Columns: snake_case
  - Foreign keys: singular_table_name_id
- Include timestamps (created_at, updated_at)
- Use appropriate data types and constraints

### Testing
- Write tests for all new features
- Follow AAA pattern (Arrange, Act, Assert)
- Use meaningful test names
- Mock external dependencies

Example:
```php
<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use App\Services\BookService;
use App\Models\Book;

class BookServiceTest extends TestCase
{
    public function testFindByIdReturnsBookWhenExists(): void
    {
        // Arrange
        $book = new Book(['id' => 1, 'title' => 'Test Book']);
        
        // Act
        $result = $this->bookService->findById(1);
        
        // Assert
        $this->assertEquals($book->id, $result->id);
        $this->assertEquals($book->title, $result->title);
    }
}
```

### Git Workflow
1. Create feature branch from main
2. Make changes and commit with meaningful messages
3. Write tests for new features
4. Run code quality checks
5. Create pull request
6. Get code review
7. Merge to main

### Commit Messages
Follow conventional commits format:
```
<type>(<scope>): <description>

[optional body]

[optional footer]
```

Types:
- feat: New feature
- fix: Bug fix
- docs: Documentation
- style: Formatting
- refactor: Code restructuring
- test: Adding tests
- chore: Maintenance

### Security Guidelines
1. Never commit sensitive data
2. Use environment variables for configuration
3. Validate and sanitize all user input
4. Implement proper access control
5. Use prepared statements for queries
6. Follow OWASP security guidelines

### Performance Guidelines
1. Use caching where appropriate
2. Optimize database queries
3. Minimize HTTP requests
4. Use lazy loading for relationships
5. Implement pagination for large datasets

### Documentation
1. Keep README up to date
2. Document API endpoints
3. Add inline code comments
4. Update changelog
5. Document configuration options 