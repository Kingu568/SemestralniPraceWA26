<?php

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/../dto/ListingDTO.php';

class Listing
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getActive(?int $userId = null)
    {
        $sql = "SELECT listings.*, items.name AS item_name
                FROM listings
                LEFT JOIN items ON listings.item_id = items.id
                WHERE listings.status = 'active'";

        $params = [];

        if ($userId !== null) {
            $sql .= " AND listings.created_by = :user_id";
            $params[':user_id'] = $userId;
        }

        $sql .= " ORDER BY listings.id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

        public function getById($id)
    {
        $sql = "SELECT * FROM listings WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function update($id, ListingDTO $listingData, ?int $updatedBy = null)
    {
        $sql = "UPDATE listings
                SET item_id = :item_id,
                    quality = :quality,
                    quantity = :quantity,
                    price_per_unit = :price_per_unit,
                    updated_by = :updated_by
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':item_id' => $listingData->item_id,
            ':quality' => $listingData->quality,
            ':quantity' => $listingData->quantity,
            ':price_per_unit' => $listingData->price_per_unit,
            ':updated_by' => $updatedBy
        ]);
    }

    public function markAsSold($id, int $soldBy)
    {
        $listing = $this->getById($id);

        if (!$listing) {
            return false;
        }

        $totalPrice = $listing['quantity'] * $listing['price_per_unit'];

        $sql = "INSERT INTO sales (
                    listing_id, item_id, quality, quantity_sold, price_per_unit, total_price, sold_by
                ) VALUES (
                    :listing_id, :item_id, :quality, :quantity_sold, :price_per_unit, :total_price, :sold_by
                )";

        $stmt = $this->db->prepare($sql);

        $savedSale = $stmt->execute([
            ':listing_id' => $listing['id'],
            ':item_id' => $listing['item_id'],
            ':quality' => $listing['quality'],
            ':quantity_sold' => $listing['quantity'],
            ':price_per_unit' => $listing['price_per_unit'],
            ':total_price' => $totalPrice,
            ':sold_by' => $soldBy
        ]);

        if (!$savedSale) {
            return false;
        }

        return $this->delete($id);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM listings WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }
    public function create(ListingDTO $listingData, ?int $createdBy = null)
    {
        $sql = "INSERT INTO listings (
                    item_id, quality, quantity, price_per_unit, status, created_by, updated_by
                ) VALUES (
                    :item_id, :quality, :quantity, :price_per_unit, :status, :created_by, :updated_by
                )";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':item_id' => $listingData->item_id,
            ':quality' => $listingData->quality,
            ':quantity' => $listingData->quantity,
            ':price_per_unit' => $listingData->price_per_unit,
            ':status' => $listingData->status,
            ':created_by' => $createdBy,
            ':updated_by' => $createdBy
        ]);
    }
}