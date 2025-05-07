<?php
namespace FOBMS;

class SpecialOffer {
    private function getDb() {
        return Database::getInstance();
    }

    public function create($data) {
        try {
            $db = $this->getDb();
            $db->beginTransaction();

            // Validate input data
            $this->validateOfferData($data);

            // Check if coupon code already exists
            $sql = "SELECT COUNT(*) as count FROM special_offers WHERE coupon_code = ?";
            $db->query($sql);
            $db->bind(null, $data['coupon_code']);
            $result = $db->fetch();

            if ($result['count'] > 0) {
                throw new \Exception("Coupon code '{$data['coupon_code']}' already exists");
            }

            // Insert offer
            $insertData = [
                'title' => $data['title'],
                'description' => $data['description'],
                'discount_percent' => $data['discount_percent'],
                'coupon_code' => $data['coupon_code'],
                'usage_limit' => $data['usage_limit'] ?: null,
                'min_books' => $data['min_books'],
                'category_restriction' => $data['category_restriction'] ?: null,
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'is_active' => isset($data['is_active']) ? 1 : 0
            ];

            if ($db->insert('special_offers', $insertData)) {
                $id = $db->lastInsertId();
                $db->commit();
                error_log("New offer added: {$data['title']}");
                return $id;
            }

            $db->rollback();
            throw new \Exception("Failed to insert offer into database");
        } catch (\Exception $e) {
            if (isset($db)) {
                $db->rollback();
            }
            error_log("Error creating offer: " . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

    public function update($id, $data) {
        try {
            $db = $this->getDb();
            $db->beginTransaction();

            // Check if offer exists
            $sql = "SELECT * FROM special_offers WHERE id = ?";
            $db->query($sql);
            $db->bind(null, $id);
            $existing = $db->fetch();

            if (!$existing) {
                throw new \Exception("Offer with ID $id not found");
            }

            // Validate input data
            $this->validateOfferData($data);

            // Check if coupon code already exists (except for this offer)
            $sql = "SELECT COUNT(*) as count FROM special_offers WHERE coupon_code = ? AND id != ?";
            $db->query($sql);
            $db->bind(null, $data['coupon_code']);
            $db->bind(null, $id);
            $result = $db->fetch();

            if ($result['count'] > 0) {
                throw new \Exception("Coupon code '{$data['coupon_code']}' already exists");
            }

            // Update offer
            $updateData = [
                'title' => $data['title'],
                'description' => $data['description'],
                'discount_percent' => $data['discount_percent'],
                'coupon_code' => $data['coupon_code'],
                'usage_limit' => $data['usage_limit'] ?: null,
                'min_books' => $data['min_books'],
                'category_restriction' => $data['category_restriction'] ?: null,
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'is_active' => isset($data['is_active']) ? 1 : 0
            ];

            if ($db->update('special_offers', $updateData, 'id = ?', [$id])) {
                $db->commit();
                error_log("Offer updated: ID {$id}");
                return true;
            }

            $db->rollback();
            throw new \Exception("Failed to update offer in database");
        } catch (\Exception $e) {
            if (isset($db)) {
                $db->rollback();
            }
            error_log("Error updating offer: " . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

    public function delete($id) {
        try {
            $db = $this->getDb();
            $db->beginTransaction();

            // Check if offer exists
            $sql = "SELECT * FROM special_offers WHERE id = ?";
            $db->query($sql);
            $db->bind(null, $id);
            $existing = $db->fetch();

            if (!$existing) {
                throw new \Exception("Offer with ID $id not found");
            }

            // Check if offer is currently in use
            $sql = "SELECT COUNT(*) as count FROM borrowings WHERE coupon_code = ?";
            $db->query($sql);
            $db->bind(null, $existing['coupon_code']);
            $result = $db->fetch();

            if ($result['count'] > 0) {
                throw new \Exception("Cannot delete offer that is currently in use");
            }

            // Delete offer
            if ($db->delete('special_offers', 'id = ?', [$id])) {
                $db->commit();
                error_log("Offer deleted: ID {$id}");
                return true;
            }

            $db->rollback();
            throw new \Exception("Failed to delete offer from database");
        } catch (\Exception $e) {
            if (isset($db)) {
                $db->rollback();
            }
            error_log("Error deleting offer: " . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

    public function getById($id) {
        try {
            $db = $this->getDb();
            $sql = "SELECT * FROM special_offers WHERE id = ?";
            $db->query($sql);
            $db->bind(null, $id);
            return $db->fetch();
        } catch (\Exception $e) {
            error_log("Error fetching offer: " . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

    public function getAll($filters = []) {
        try {
            $db = $this->getDb();
            $sql = "SELECT * FROM special_offers WHERE 1=1";
            $params = [];

            if (!empty($filters['title'])) {
                $sql .= " AND title LIKE ?";
                $params[] = "%{$filters['title']}%";
            }
            if (!empty($filters['category'])) {
                $sql .= " AND category_restriction = ?";
                $params[] = $filters['category'];
            }
            if (isset($filters['is_active'])) {
                $sql .= " AND is_active = ?";
                $params[] = $filters['is_active'] ? 1 : 0;
            }
            if (!empty($filters['start_date'])) {
                $sql .= " AND start_date >= ?";
                $params[] = $filters['start_date'];
            }
            if (!empty($filters['end_date'])) {
                $sql .= " AND end_date <= ?";
                $params[] = $filters['end_date'];
            }

            $sql .= " ORDER BY created_at DESC";

            $db->query($sql);
            foreach ($params as $param) {
                $db->bind(null, $param);
            }
            return $db->fetchAll();
        } catch (\Exception $e) {
            error_log("Error fetching offers: " . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

    public function validateCoupon($code, $bookCount, $category = null) {
        try {
            $db = $this->getDb();
            $sql = "SELECT * FROM special_offers WHERE coupon_code = ? AND is_active = 1";
            $db->query($sql);
            $db->bind(null, $code);
            $offer = $db->fetch();

            if (!$offer) {
                return ['valid' => false, 'message' => 'Invalid coupon code'];
            }

            $now = date('Y-m-d');
            if ($now < $offer['start_date'] || $now > $offer['end_date']) {
                return ['valid' => false, 'message' => 'Coupon code has expired'];
            }

            if ($offer['usage_limit'] !== null && $offer['usage_count'] >= $offer['usage_limit']) {
                return ['valid' => false, 'message' => 'Coupon code has reached its usage limit'];
            }

            if ($offer['min_books'] > $bookCount) {
                return ['valid' => false, 'message' => "Minimum {$offer['min_books']} books required"];
            }

            if ($offer['category_restriction'] !== null && $category !== null && $offer['category_restriction'] !== $category) {
                return ['valid' => false, 'message' => "Coupon code is only valid for {$offer['category_restriction']} books"];
            }

            return [
                'valid' => true,
                'discount_percent' => $offer['discount_percent'],
                'message' => 'Coupon code is valid'
            ];
        } catch (\Exception $e) {
            error_log("Error validating coupon: " . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

    public function incrementUsage($id) {
        try {
            $db = $this->getDb();
            $sql = "UPDATE special_offers SET usage_count = usage_count + 1 WHERE id = ?";
            $db->query($sql);
            $db->bind(null, $id);
            return $db->execute();
        } catch (\Exception $e) {
            error_log("Error incrementing usage count: " . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

    private function validateOfferData($data) {
        $required = ['title', 'description', 'discount_percent', 'coupon_code', 'start_date', 'end_date'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new \Exception("Missing required field: $field");
            }
        }

        if (!is_numeric($data['discount_percent']) || $data['discount_percent'] <= 0 || $data['discount_percent'] > 100) {
            throw new \Exception("Invalid discount percentage");
        }

        if (!empty($data['usage_limit']) && (!is_numeric($data['usage_limit']) || $data['usage_limit'] <= 0)) {
            throw new \Exception("Invalid usage limit");
        }

        if (!empty($data['min_books']) && (!is_numeric($data['min_books']) || $data['min_books'] <= 0)) {
            throw new \Exception("Invalid minimum books requirement");
        }

        if (strtotime($data['start_date']) > strtotime($data['end_date'])) {
            throw new \Exception("Start date must be before end date");
        }
    }
} 