<?php

require_once 'USER.php';
require_once 'db_connect.php';

class Admin extends User {

    public function createCategory($categoryName) {
        session_start();

        $conn = (new DATABASE())->getConnection();
        $stmt = $conn->prepare("INSERT INTO catagugry (name) VALUES (?)");

        if ($stmt->execute([$categoryName])) {
            echo "<script>
            window.onload = function() {
                Swal.fire({
                    title: 'Success!',
                    text: 'Category added!',
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
                    text: 'Failed to add category',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
            </script>";
        }
    }

    public function modifyCategory($newCategoryName, $categoryId) {
        $conn = (new DATABASE())->getConnection();
        $stmt = $conn->prepare("UPDATE catagugry SET name = ? WHERE id = ?");

        if ($stmt->execute([$newCategoryName, $categoryId])) {
            echo "<script>
            window.onload = function() {
                Swal.fire({
                    title: 'Success!',
                    text: 'Category updated successfully!',
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
                    text: 'Failed to update category',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
            </script>";
        }
    }

    public function removeCategory($categoryId) {
        $conn = (new DATABASE())->getConnection();
        $stmt = $conn->prepare("DELETE FROM catagugry WHERE id = ?");

        if ($stmt->execute([$categoryId])) {
            echo "<script>
            window.onload = function() {
                Swal.fire({
                    title: 'Success!',
                    text: 'Category removed!',
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
                    text: 'Error occurred while removing the category',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
            </script>";
        }
    }

    public function acceptArticle($articleId) {
        $conn = (new DATABASE())->getConnection();
        $stmt = $conn->prepare("UPDATE article SET statu = 'accepted' WHERE id = ?");

        if ($stmt->execute([$articleId])) {
            echo "<script>
            window.onload = function() {
                Swal.fire({
                    title: 'Success!',
                    text: 'Article accepted.',
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
                    text: 'Failed to accept article',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
            </script>";
        }
    }

    public function rejectArticle($articleId) {
        $conn = (new DATABASE())->getConnection();
        $stmt = $conn->prepare("UPDATE article SET statu = 'rejected' WHERE id = ?");

        if ($stmt->execute([$articleId])) {
            echo "<script>
            window.onload = function() {
                Swal.fire({
                    title: 'Success!',
                    text: 'Article rejected.',
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
                    text: 'Failed to reject article',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
            </script>";
        }
    }

    public function createTags($tagsName) {
      

        $conn = (new DATABASE())->getConnection();
        $stmt = $conn->prepare("INSERT INTO tags (tagsname) VALUES (?)");

        if ($stmt->execute([$tagsName])) {
            echo "<script>
            window.onload = function() {
                Swal.fire({
                    title: 'Success!',
                    text: 'Tags added!',
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
                    text: 'Failed to add tags',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
            </script>";
        }
    }

    public function modifyTags($newTagsName, $tagsId) {
        $conn = (new DATABASE())->getConnection();
        $stmt = $conn->prepare("UPDATE tags SET tagsname = ? WHERE id = ?");

        if ($stmt->execute([$newTagsName, $tagsId])) {
            echo "<script>
            window.onload = function() {
                Swal.fire({
                    title: 'Success!',
                    text: 'Tag updated successfully!',
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
                    text: 'Failed to update tag',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
            </script>";
        }
    }

    public function removeTags($tagsId) {
        $conn = (new DATABASE())->getConnection();
        $stmt = $conn->prepare("DELETE FROM tags WHERE id = ?");

        if ($stmt->execute([$tagsId])) {
            echo "<script>
            window.onload = function() {
                Swal.fire({
                    title: 'Success!',
                    text: 'Tag removed!',
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
                    text: 'Error occurred while removing the tag',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
            </script>";
        }
    }




    public function BannUsers($userId){

        $conn = (new DATABASE())->getConnection();


        $updateQuery = "UPDATE users SET status = 'banned' WHERE id = :id";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bindParam(':id', $userId, PDO::PARAM_INT);
    if ($updateStmt->execute()) {
        echo "<script>
        window.onload = function() {
            Swal.fire({
                title: 'Success!',
                text: 'User has been banned',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        }
      </script>"; 

        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Failed to ban user.";
    }


    
    }
}
?>
