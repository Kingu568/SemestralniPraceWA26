<?php

require_once __DIR__ . '/Database.php';

class Comment
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getByItemId($itemId)
    {
        $sql = "SELECT comments.*, users.username, users.nickname
                FROM comments
                LEFT JOIN users ON comments.user_id = users.id
                WHERE comments.item_id = :item_id
                ORDER BY comments.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':item_id' => $itemId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM comments WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($itemId, $userId, $content)
    {
        $sql = "INSERT INTO comments (item_id, user_id, content)
                VALUES (:item_id, :user_id, :content)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':item_id' => $itemId,
            ':user_id' => $userId,
            ':content' => $content
        ]);
    }

    public function update($id, $content)
    {
        $sql = "UPDATE comments
                SET content = :content
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':content' => $content
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM comments WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }
}