<?php

require_once 'db_connect.php';



abstract class User {


public $fristname;

public $lastname;

public $email;

public $password;

public $role;

protected $conn;



public function __construct($dbConn) {
    $this->conn = $dbConn;
}


public function login($email, $password) {




    $user = $this->getUserByEmail($email);

    if ($user && password_verify($password, $user['password'])) {
        if ($user['status'] === 'banned') {
            header('Location: 401.php');
            exit;
        }
        $this->initializeSession($user);
        return true;
    }
    return false;

    if ($user && password_verify($password, $user['password'])) {
        $this->initializeSession($user);
        return true;
    }
    return false;
}

private function initializeSession($user) {
    session_start();

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['firstname'] = $user['firstName'];
    $_SESSION['lastname'] = $user['lastName'];

    if ($user['role'] === 'auteur'){
    header('Location: auteur.php');
    }else if ($user['role'] === 'visteur'){
        header('Location: user_page.php ');
    }else if ($user['role'] === 'admin'){
        header('Location: admin_dashboard.php');
    }
}

public function getUserByEmail($email) {
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function logout() {
    session_unset();
    session_destroy();
}


}
?>
