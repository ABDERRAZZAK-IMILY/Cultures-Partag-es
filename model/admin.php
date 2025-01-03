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

public function modifyCategory($newCategoryName , $categoryId){
    $conn = (new DATABASE())->getConnection();

    $stmt = $conn->prepare("UPDATE catagugry SET name = ? WHERE id = ?");
    if ($stmt->execute([$newCategoryName, $categoryId])) {
        $message = 'Category updated successfully!';
    } else {
        $message = 'Error occurred while updating the category!';
    }

}

 public function removeCategory($categoryId){
    $conn = (new DATABASE())->getConnection();

    $stmt = $conn->prepare("DELETE FROM catagugry WHERE id = ?");
    if ($stmt->execute([$categoryId])) {
        $message = 'Category removed successfully!';
    } else {
        $message = 'Error occurred while removing the category!';
    }



 }

 public function acceptArticle($articleId){
    $conn = (new DATABASE())->getConnection();

    $stmt = $conn->prepare("UPDATE article SET statu = 'accepted' WHERE id = ?");
    if ($stmt->execute([$articleId])) {
        $message = 'Article accepted!';
    } else {
        $message = 'Error occurred while accepting the article!';
    }

 }


 public function rejectArticle($articleId){
    $conn = (new DATABASE())->getConnection();

    $stmt = $conn->prepare("UPDATE article SET statu = 'rejected' WHERE id = ?");
    if ($stmt->execute([$articleId])) {
        $message = 'Article rejected!';
    } else {
        $message = 'Error occurred while rejecting the article!';
    }


 }

}
?>