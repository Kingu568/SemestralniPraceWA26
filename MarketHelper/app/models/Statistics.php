<?php

require_once __DIR__ . '/Database.php';

class Statistics
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getStats(int $userId, ?int $itemId = null, string $quality = 'both')
    {
        $sql = "SELECT 
                    COUNT(*) AS sale_count,
                    COALESCE(SUM(quantity_sold), 0) AS total_quantity,
                    COALESCE(SUM(total_price), 0) AS total_earned,
                    COALESCE(AVG(price_per_unit), 0) AS average_price,
                    COALESCE(MAX(price_per_unit), 0) AS highest_price,
                    COALESCE(MIN(price_per_unit), 0) AS lowest_price
                FROM sales
                WHERE sold_by = :user_id";

        $params = [
            ':user_id' => $userId
        ];

        if ($itemId !== null && $itemId > 0) {
            $sql .= " AND item_id = :item_id";
            $params[':item_id'] = $itemId;
        }

        if ($quality === 'HQ' || $quality === 'NQ') {
            $sql .= " AND quality = :quality";
            $params[':quality'] = $quality;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getSoldItemsBreakdown(int $userId)
    {
        $sql = "SELECT 
                    items.name AS item_name,
                    sales.quality,
                    SUM(sales.quantity_sold) AS total_quantity,
                    SUM(sales.total_price) AS total_earned,
                    AVG(sales.price_per_unit) AS average_price
                FROM sales
                LEFT JOIN items ON sales.item_id = items.id
                WHERE sales.sold_by = :user_id
                GROUP BY sales.item_id, sales.quality
                ORDER BY total_earned DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':user_id' => $userId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}