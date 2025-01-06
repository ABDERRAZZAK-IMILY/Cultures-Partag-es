<?php
require_once 'USER.php';
require_once 'db_connect.php';

class Visteur extends User {

    public function register($firstName, $lastName, $email, $password, $role, $image) {
        if (!$this->isValidEmail($email) || $this->emailExists($email)) {
            return false;
        }

        $newfilename = $this->handleImageUpload($image);
        if (!$newfilename) {
            return false;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $query = "INSERT INTO users (firstName, lastName, email, password, role, image) 
                  VALUES (:firstName, :lastName, :email, :password, :role, :image)";

        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':firstName' => htmlspecialchars($firstName),
            ':lastName' => htmlspecialchars($lastName),
            ':email' => $email,
            ':password' => $hashedPassword,
            ':role' => $role,
            ':image' => $newfilename
        ]);
    }

    private function handleImageUpload($image) {
        if (isset($image) && $image['error'] == 0) {
            $newfilename = uniqid() . "-" . $image['name'];
            $uploadDirectory = '../assest/upload/';

            if (move_uploaded_file($image['tmp_name'], $uploadDirectory . $newfilename)) {
                return $newfilename;
            }
        }

        return false;
    }

    private function isValidEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    private function emailExists($email) {
        return $this->getUserByEmail($email) !== false;
    }
}
?>
