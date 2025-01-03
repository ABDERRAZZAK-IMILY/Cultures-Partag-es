<?php

require_once 'visteur.php';
require_once 'db_connect.php';


class Auteur extends Visteur {


    public function createArticle($categoryId , $date_res , $content , $image){
        
        $conn = (new DATABASE())->getConnection();
        
        $stmt2 = $conn->prepare("INSERT INTO article (user_id, catagugry_id, date_creation, description, image) VALUES (?, ?, ?, ?, ?)");

        if ($stmt2->execute([$_SESSION['user_id'], $categoryId, $date_res, $content , $image])) {
            $message = 'Article created successfully!';
        } else {
            $message = 'Error: ' . implode(', ', $stmt2->errorInfo());
        }
  

    }

   public function modifyArticle(){



   }
   public function removeAricle(){



   }


}
?>
