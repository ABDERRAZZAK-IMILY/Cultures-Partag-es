<?php

require_once 'visteur.php';
require_once 'db_connect.php';


class Auteur extends Visteur {


    public function createArticle($categoryId , $date_res , $content , $image,$title){

        $conn = (new DATABASE())->getConnection();

        
        $stmt2 = $conn->prepare("INSERT INTO article (user_id, catagugry_id, date_creation, description, image, title) VALUES (?, ?, ?, ?, ?, ?)");

        if ($stmt2->execute([$_SESSION['user_id'], $categoryId, $date_res, $content , $image , $title])) {
            $message = 'article created successfully!';
        } else {
            $message = 'Error: ' . implode(', ', $stmt2->errorInfo());
        }



    }

   public function modifyArticle($newTitle , $newCategoryId ,$newContent ,$articleId){
    $conn = (new DATABASE())->getConnection();


    $stmt = $conn->prepare("UPDATE article SET title = ?, catagugry_id = ?, description = ? WHERE id = ?");
    if ($stmt->execute([$newTitle, $newCategoryId, $newContent, $articleId])) {
        $message = 'Article updated successfully!';
    } else {
        $message = 'Error: ' . implode(', ', $stmt->errorInfo());
    }



   }
   public function removeAricle($articleId){
    $conn = (new DATABASE())->getConnection();

    $stmt = $conn->prepare("DELETE FROM article WHERE id = ?");
    if ($stmt->execute([$articleId])) {
        $message = 'Article removed successfully!';
    } else {
        $message = 'Error: ' . implode(', ', $stmt->errorInfo());
    }

   }


   public function Tagadd($articleid , $tagid){

    $conn = (new DATABASE())->getConnection();


 
    if (isset($articleid) && !empty($articleid) && isset($tagid) && !empty($tagid)) {
        $addtagsid = "INSERT INTO article_tags (id_article, id_tags) VALUES (?, ?)";
        $stmt3 = $conn->prepare($addtagsid);

        if ($stmt3->execute([$articleid, $tagid])) {
            echo 'tag id added successfully!';
        } else {
            echo 'error: unable to add tag to article.';
        }
    }
    
}



}
?>
