# GLMSphp (Gobindaganj Library Management System)

A comprehensive library management system built with PHP.

## Features

- Book Management
- User Management
- Borrowing System
- Fine Management
- Admin Dashboard
- Search Functionality

## Requirements

- PHP 8.0 or higher
- MySQL 5.7 or higher
- Composer
- Web Server (Apache/Nginx)

## Installation

1. Clone the repository:
```bash
git clone https://github.com/oyaon/GLMSphp.git
cd GLMSphp
```

2. Install dependencies:
```bash
composer install
```

3. Configure your environment:
```bash
cp .env.example .env
# Edit .env with your database credentials
```

4. Set up the database:
- Create a new MySQL database
- Import the database schema from `database/schema.sql`
- Update the database credentials in `.env`

5. Configure your web server:
- Point your web server's document root to the `public` directory
- Ensure the web server has write permissions to the `storage` directory

## Project Structure

```
GLMSphp/
├── public/         # Publicly accessible files
│   └── index.php   # Entry point
├── src/            # Application source code
│   ├── views/      # View templates
│   ├── models/     # Database models
│   └── controllers/# Application controllers
├── config/         # Configuration files
├── database/       # Database migrations and seeds
├── resources/      # Assets (CSS, JS, images)
├── storage/        # Application storage
└── tests/          # Test files
```

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details. 