<?php

require_once 'USER.php';
require_once 'db_connect.php';




class Admin extends User {
public function createCategory($categoryName){

    session_start();

    $conn = (new DATABASE())->getConnection();

    $stmt = $conn->prepare("INSERT INTO catagugry (name) VALUES (?)");
    if ($stmt->execute([$categoryName])) {
        echo 'categury added successfully!';
    } else {
        echo 'error occurred while adding the categury!';
    }
}

public function modifyCategory(){



}

 public function removeCategory(){


 }

 public function acceptArticle(){



 }


 public function rejectArticle(){




    
 }

}
?>