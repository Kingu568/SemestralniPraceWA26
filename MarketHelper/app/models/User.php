<?php

require_once __DIR__ . '/Database.php';

class User
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function register(
        string $username,
        string $email,
        string $password,
        ?string $nickname = null
    ): bool {
        if ($this->findByEmail($email)) {
            return false;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, email, password, nickname)
                VALUES (:username, :email, :password, :nickname)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':nickname' => $nickname ?: null
        ]);
    }

    public function findByEmail(string $email)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById(int $id)
    {
        $sql = "SELECT id, username, email, nickname, is_admin, created_at
                FROM users
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getAll()
    {
        $sql = "SELECT id, username, email, nickname, is_admin, created_at
                FROM users
                ORDER BY id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteNonAdmin(int $id)
    {
        $sql = "DELETE FROM users
                WHERE id = :id
                AND is_admin = 0";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }
}