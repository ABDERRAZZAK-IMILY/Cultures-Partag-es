<?php
require_once '../model/db_connect.php';
require_once '../model/Auteur.php';

session_start();

if (isset($_SESSION['role']) && $_SESSION['role'] === 'auteur') {
    $conn = (new DATABASE())->getConnection();
    $A = new Auteur($conn);

    $categories = [];
    $categoryQuery = "SELECT id, name FROM catagugry";
    $stmt = $conn->query($categoryQuery);



    $tags = [];

    $tagsQuery = "SELECT * FROM tags";

    $stmt2 = $conn->query($tagsQuery);

    if ($stmt2 && $stmt2->rowCount() > 0) {
        while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
            $tags[] = $row;
        }
    }


    if (isset($_POST['tageadd'])) {
        $articleid = $_POST['articleId']; 
        $tagid = $_POST['tagsname'];

      $tagadd = $A->Tagadd($articleid , $tagid);
    }


// $fetchTages = [];
// $fetchtagesquery = "SELECT tagsname, id_article FROM article_tags join tags on article_tags.id_tags = tags.id join article on article_tags.id_article = article.id where id_article = $articleid";



// $stmt4 = $conn->query($fetchtagesquery);



// if ($stmt4 && $stmt4->rowCount() > 0) {
//     while ($row = $stmt4->fetch(PDO::FETCH_ASSOC)) {
//         $fetchTages[] = $row;
//     }
// }






    if ($stmt && $stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories[] = $row;
        }
    }

    if (isset($_POST['createArticle'])) {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $categoryId = $_POST['cataguryname'];
        $date_res = date('Y-m-d');
        $image = $_POST['image'];

     $createArtile = $A->createArticle($categoryId , $date_res , $content , $image , $title);

    }

    if (isset($_POST['modifyArticle'])) {
        $articleId = $_POST['articleId'];
        $newCategoryId = $_POST['cataguryname'];
        $newTitle = $_POST['newtitle'];
        $newContent = $_POST['newcontent'];

      $modify = $A->modifyArticle($newTitle , $newCategoryId , $newContent , $articleId);

    }

    if (isset($_POST['removeArticle'])) {
        $articleId = $_POST['removeArticleId'];
        $removeArticle = $A->removeAricle($articleId);
      
    }

    $articles = [];
    $query = "SELECT article.id, article.title, article.description, article.date_creation, article.image, article.catagugry_id, catagugry.name AS category_name
              FROM article
               JOIN catagugry ON article.catagugry_id = catagugry.id";
    $stmt = $conn->query($query);

    if ($stmt && $stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $articles[] = $row;
        }
    } else {
        $message = "no accepted articles found";
    }
} else {
    die("access denied , should be sing up");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AUTEUR DASHBOARD</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <main class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-red-800 to-green-900 text-white">
            <div class="p-6">
                <div class="flex items-center space-x-3">
                    <h1 class="text-2xl font-bold">AUTEUR DASHBOARD</h1>
                </div>
            </div>

            <nav class="mt-6">
                <div class="px-6 py-3">
                    <p class="text-xs uppercase text-purple-300">Menu Principal</p>
                </div>
                <a href="#" class="flex items-center px-6 py-3 hover:bg-red-700 transition-colors duration-200">
                    <i class="fas fa-pencil-alt mr-3"></i>
                    Mes Articles
                </a>
                <a href="#" class="flex items-center px-6 py-3 hover:bg-red-700 transition-colors duration-200">
                    <i class="fas fa-plus-circle mr-3"></i>
                    Nouvel Article
                </a>
                <a href="logout.php"
                    class="flex items-center px-6 py-3 hover:bg-red-700 transition-colors duration-200">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    Déconnexion
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <section class="flex-1 overflow-x-hidden overflow-y-auto">
            <!-- Top Navigation -->
            <nav class="bg-white shadow-md">
                <div class="mx-auto px-8 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <h2 class="text-xl font-semibold text-gray-800">Dashboard</h2>
                            <p class="text-sm text-gray-600">Bienvenue,</p>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Dashboard -->
            <div class="p-10 flex items-center gap-8 flex-wrap lg:grid lg:grid-cols-2 bg-gray-200">
                <?php foreach ($articles as $article): ?>


                <article class="relative bg-white shadow-md rounded-md">


                 <form method="POST">
                 <input type="hidden" name="articleId" value="<?= $article['id'] ?>">
                            <label for="catagury" class="block text-gray-700">Tags</label>
                            <select id="tagname" name="tagsname" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <?php foreach ($tags as $tag): ?>
                                    <option  id="tagname" value="<?= $tag['id'] ?>"><?= $tag['tagsname'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button id="addtags" type="submit" name="tageadd" class="py-2 px-5 rounded-sm text-white bg-red-200 text-sm duration-500 hover:bg-red-700">add tag</button>
                                </form>
                        <!--tags added!-->
                        <div id="selectedTagsContainer">
                    

                        </div>
                    <div>
                        <img class="object-cover w-full h-52 dark:bg-gray-500" src="<?= htmlspecialchars($article['image']) ?>" alt="Article Image">
                    </div>
                    <div class="p-4">
                        <p class="text-gray-800 font-medium text-sm"><?= $article['date_creation'] ?> •</p>
                        <div class="pt-5">
                            <a href="#"><h1 class="text-gray-900 font-semibold text-xl mb-3"><?= htmlspecialchars($article['title']) ?></h1></a>
                            <p class="text-gray-700 font-medium text-md"><?= htmlspecialchars($article['description']) ?></p>
                        </div>
                        <div class="flex justify-end items-center gap-5 mt-5">
                            <button class="editButton py-2 px-5 rounded-sm text-white bg-blue-500 text-sm duration-500 hover:bg-blue-700">Modifier</button>

                            <div class="bg-gray-800 p-4 rounded bg-opacity-50 absolute top-0 left-0 w-50 z-10" id="editForm_<?= $article['id'] ?>" style="display: none;">
                                <h1 class="text-3xl font-bold text-center mb-8 text-white">EDIT article</h1>
                                <form action="" method="POST" class="space-y-4">
                                    <div class="space-y-4">
                                        <div>
                                            <label for="articleTitle" class="block text-gray-700">Article Title</label>
                                            <input type="text" id="articleTitle" name="newtitle" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= htmlspecialchars($article['title']) ?>" required>
                                        </div>
                                        <div>
                                            <label for="catagury" class="block text-gray-700">Category</label>
                                            <select id="catagury" name="cataguryname" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                                <?php foreach ($categories as $category): ?>
                                                    <option value="<?= $category['id'] ?>" <?= $category['id'] == $article['catagugry_id'] ? 'selected' : '' ?>><?= $category['name'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="articleContent" class="block text-gray-700">Article Content</label>
                                            <textarea id="articleContent" name="newcontent" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required><?= htmlspecialchars($article['description']) ?></textarea>
                                        </div>
                                        <input type="hidden" name="articleId" value="<?= $article['id'] ?>">
                                        <button type="submit" name="modifyArticle" class="py-2 px-5 rounded-sm text-white bg-blue-500 text-sm duration-500 hover:bg-blue-700">Modifier</button>
                                    </div>
                                </form>
                            </div>

                            <form action="" method="POST" class="inline-block">
                                <input type="hidden" name="removeArticleId" value="<?= $article['id'] ?>">
                                <button type="submit" name="removeArticle" class="py-2 px-5 rounded-sm text-white bg-red-500 text-sm duration-500 hover:bg-red-700">Supprimer</button>
                            </form>
                        </div>
                    </div>
                    
                    <p class="absolute top-2 right-2 bg-white bg-opacity-85 py-1 px-3 rounded-md text-xs"><?= htmlspecialchars($article['category_name']) ?></p>
                
  <!-- Display Tags -->


  <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-lg font-semibold mb-4">Tags</h2>
            <div class="flex flex-wrap gap-2">


            <div id="selectedTagsContainer" class="mt-2">
                            <?php
                            $articleTagsQuery = "SELECT tagsname 
                                                FROM article_tags 
                                                JOIN tags ON article_tags.id_tags = tags.id 
                                                WHERE id_article = :articleid";
                            $stmtTags = $conn->prepare($articleTagsQuery);
                            $stmtTags->bindParam(':articleid', $article['id'], PDO::PARAM_INT);
                            $stmtTags->execute();
                            $articleTags = $stmtTags->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            <?php foreach ($articleTags as $tag): ?>
                                <span class="bg-blue-200 hover:bg-blue-300 py-1 px-2 rounded-lg text-sm"><?= htmlspecialchars($tag['tagsname']) ?></span>
                            <?php endforeach; ?>
                        </div>
            </div>
        </div>
                        




                </article>
                <?php endforeach; ?>
            </div>



               <!-- Create New Article Form -->
               <div class="p-10 mt-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Create New Article</h2>
                <form action="" method="POST">
                    <div class="space-y-4">
                        <div>
                            <label for="articleTitle" class="block text-gray-700">Article Title</label>
                            <input type="text" id="articleTitle" name="title" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="articleTitle" class="block text-gray-700">Article image url</label>
                            <input type="text" id="articleTitle" name="image" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" >
                        </div>
                        <div>
                            <label for="catagury" class="block text-gray-700">Category</label>
                            <select id="catagury" name="cataguryname" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>    

                        <div>
                            <label for="articleContent" class="block text-gray-700">Article Content</label>
                            <textarea id="articleContent" name="content" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                        <div>
                            <button type="submit" name="createArticle" class="w-full py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none">Create Article</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </main>
</body>

<script>
    document.querySelectorAll('.editButton').forEach(function(button) {
        button.onclick = function() {
            var editFormId = 'editForm_' + this.closest('article').querySelector('input[name="articleId"]').value;
            var editForm = document.getElementById(editFormId);
            editForm.style.display = (editForm.style.display === "none") ? "block" : "none";
        };
    });
    </script>

</html>
