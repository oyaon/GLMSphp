<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', $_ENV['APP_DEBUG'] ?? false);

// Start session
session_start();

// Basic routing
$request = $_SERVER['REQUEST_URI'];
$basePath = dirname($_SERVER['SCRIPT_NAME']);

// Remove base path from request
$request = str_replace($basePath, '', $request);

// Simple router
switch ($request) {
    case '/':
        require __DIR__ . '/../src/views/home.php';
        break;
    case '/about':
        require __DIR__ . '/../src/views/about.php';
        break;
    default:
        http_response_code(404);
        require __DIR__ . '/../src/views/404.php';
        break;
} 