<?php
namespace FOBMS\Tests;

use PHPUnit\Framework\TestCase;
use FOBMS\Database;

class DatabaseTest extends TestCase
{
    private $db;

    protected function setUp(): void
    {
        $this->db = Database::getInstance();
    }

    public function testDatabaseConnection()
    {
        $this->assertNotNull($this->db);
    }

    public function testInsertAndFetch()
    {
        // Test data
        $data = [
            'name' => 'Test Book',
            'description' => 'Test Description',
            'quantity' => 5
        ];

        // Insert test data
        $result = $this->db->insert('all_books', $data);
        $this->assertNotFalse($result);

        // Fetch the inserted data
        $id = $this->db->lastInsertId();
        $book = $this->db->fetch("SELECT * FROM all_books WHERE id = ?", [$id]);

        // Assertions
        $this->assertEquals($data['name'], $book['name']);
        $this->assertEquals($data['description'], $book['description']);
        $this->assertEquals($data['quantity'], $book['quantity']);

        // Clean up
        $this->db->delete('all_books', 'id = ?', [$id]);
    }

    public function testUpdate()
    {
        // Insert test data
        $data = [
            'name' => 'Test Book',
            'description' => 'Test Description',
            'quantity' => 5
        ];
        $this->db->insert('all_books', $data);
        $id = $this->db->lastInsertId();

        // Update data
        $updateData = [
            'name' => 'Updated Book',
            'quantity' => 10
        ];
        $result = $this->db->update('all_books', $updateData, 'id = ?', [$id]);
        $this->assertNotFalse($result);

        // Verify update
        $book = $this->db->fetch("SELECT * FROM all_books WHERE id = ?", [$id]);
        $this->assertEquals($updateData['name'], $book['name']);
        $this->assertEquals($updateData['quantity'], $book['quantity']);

        // Clean up
        $this->db->delete('all_books', 'id = ?', [$id]);
    }

    public function testTransaction()
    {
        $this->db->beginTransaction();

        try {
            // Insert first book
            $data1 = [
                'name' => 'Book 1',
                'description' => 'Description 1',
                'quantity' => 5
            ];
            $this->db->insert('all_books', $data1);

            // Insert second book
            $data2 = [
                'name' => 'Book 2',
                'description' => 'Description 2',
                'quantity' => 10
            ];
            $this->db->insert('all_books', $data2);

            $this->db->commit();
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->db->rollback();
            $this->fail('Transaction failed: ' . $e->getMessage());
        }

        // Clean up
        $this->db->delete('all_books', 'name IN (?, ?)', ['Book 1', 'Book 2']);
    }

    public function testFetchAll()
    {
        // Insert multiple test records
        $books = [
            ['name' => 'Book 1', 'description' => 'Desc 1', 'quantity' => 5],
            ['name' => 'Book 2', 'description' => 'Desc 2', 'quantity' => 10],
            ['name' => 'Book 3', 'description' => 'Desc 3', 'quantity' => 15]
        ];

        foreach ($books as $book) {
            $this->db->insert('all_books', $book);
        }

        // Fetch all books
        $result = $this->db->fetchAll("SELECT * FROM all_books WHERE name LIKE ?", ['Book%']);
        $this->assertCount(3, $result);

        // Clean up
        $this->db->delete('all_books', 'name LIKE ?', ['Book%']);
    }
} 