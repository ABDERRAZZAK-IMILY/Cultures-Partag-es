<?php
require_once 'USER.php';
require_once 'db_connect.php';

class Visteur extends User {
    public function register($firstName, $lastName, $email, $password, $role) {
        if (!$this->isValidEmail($email) || $this->emailExists($email)) {
            return false;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        $query = "INSERT INTO users (firstName, lastName, email, password, role) 
                  VALUES (:firstName, :lastName, :email, :password, :role)";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':firstName' => htmlspecialchars($firstName),
            ':lastName' => htmlspecialchars($lastName),
            ':email' => $email,
            ':password' => $hashedPassword,
            ':role' => $role
        ]);
    }

    private function isValidEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    private function emailExists($email) {
        return $this->getUserByEmail($email) !== false;
    }
}
?>
