<?php

require_once __DIR__ . '/Database.php';

class Item
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAll()
    {
        $sql = "SELECT items.*, users.username AS created_by_username
                FROM items
                LEFT JOIN users ON items.created_by = users.id
                ORDER BY items.name ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getById($id)
    {
        $sql = "SELECT items.*, users.username AS created_by_username
                FROM items
                LEFT JOIN users ON items.created_by = users.id
                WHERE items.id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($name, $category, $description, ?int $createdBy = null)
    {
        $sql = "INSERT INTO items (name, category, description, created_by)
                VALUES (:name, :category, :description, :created_by)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':name' => $name,
            ':category' => $category !== '' ? $category : null,
            ':description' => $description !== '' ? $description : null,
            ':created_by' => $createdBy
        ]);
    }
    public function delete($id)
    {
        $sql = "DELETE FROM items WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }
    public function update($id, $name, $category, $description)
    {
        $sql = "UPDATE items
                SET name = :name,
                    category = :category,
                    description = :description
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':category' => $category !== '' ? $category : 'Other',
            ':description' => $description !== '' ? $description : null
        ]);
    }
}