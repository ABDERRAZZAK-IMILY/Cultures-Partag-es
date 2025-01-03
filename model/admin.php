<?php

require_once 'USER.php';
require_once 'db_connect.php';




class Admin extends User {
public function createCategory($categoryName){

    session_start();

    $conn = (new DATABASE())->getConnection();

    $stmt = $conn->prepare("INSERT INTO catagugry (name) VALUES (?)");
    if ($stmt->execute([$categoryName])) {
        echo "<script>
        window.onload = function() {
            Swal.fire({
                title: 'Success!',
                text: 'catagory added!',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        }
      </script>"; 
        } else {
        echo "<script>
        window.onload = function() {
            Swal.fire({
                title: 'Error!',
                text: 'failed to added',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
      </script>";    }
}

public function modifyCategory($newCategoryName , $categoryId){
    $conn = (new DATABASE())->getConnection();

    $stmt = $conn->prepare("UPDATE catagugry SET name = ? WHERE id = ?");
    if ($stmt->execute([$newCategoryName, $categoryId])) {
        $message = 'Category updated successfully!';
    } else {
        echo "<script>
        window.onload = function() {
            Swal.fire({
                title: 'Error!',
                text: 'failed to updated',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
      </script>";    }

}

 public function removeCategory($categoryId){
    $conn = (new DATABASE())->getConnection();

    $stmt = $conn->prepare("DELETE FROM catagugry WHERE id = ?");
    if ($stmt->execute([$categoryId])) {
        echo "<script>
        window.onload = function() {
            Swal.fire({
                title: 'Success!',
                text: 'removed',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        }
      </script>"; 
        } else {
        $message = 'Error occurred while removing the category!';
    }



 }

 public function acceptArticle($articleId){
    $conn = (new DATABASE())->getConnection();

    $stmt = $conn->prepare("UPDATE article SET statu = 'accepted' WHERE id = ?");
    if ($stmt->execute([$articleId])) {
        
        echo "<script>
        window.onload = function() {
            Swal.fire({
                title: 'Success!',
                text: 'article accepted.',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        }
      </script>";  
    } else {
        echo "<script>
        window.onload = function() {
            Swal.fire({
                title: 'Error!',
                text: 'failed to accpted',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
      </script>";
    }

 }


 public function rejectArticle($articleId){
    $conn = (new DATABASE())->getConnection();

    $stmt = $conn->prepare("UPDATE article SET statu = 'rejected' WHERE id = ?");
    if ($stmt->execute([$articleId])) {
        echo "<script>
        window.onload = function() {
            Swal.fire({
                title: 'Success!',
                text: 'article rejected',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        }
      </script>";    
     } else {
        echo "<script>
        window.onload = function() {
            Swal.fire({
                title: 'Error!',
                text: 'failed to accpted',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
      </script>";    }


 }

}
?>