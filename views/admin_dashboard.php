<?php
require_once '../model/db_connect.php';
require_once '../model/admin.php';

session_start();

$A = new Admin($conn);
$conn = (new DATABASE())->getConnection();


if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'){


    $categories = [];
    $articles_by_category = [];
    
    $aq = "SELECT id, name FROM catagugry";
    $stmt = $conn->query($aq);
    if ($stmt && $stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories[] = $row;
            $articles_by_category[$row['id']] = [];
        }
    }    
    /*************************************************tags function */
    
    $Tags = [];
    $article_by_Tags = [];
    
    $tg = "SELECT id,tagsname FROM tags";
    
    $stmt2 = $conn->query($tg);
    if ($stmt2 && $stmt2->rowCount() > 0) {
        while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
            $Tags[] = $row;
            $articles_by_Tags[$row['id']] = [];
        }
    }
    
    
    if (isset($_POST['createTag'])) {
        $tagName = $_POST['tagName'];
        $A = new Admin($conn);
        $create = $A->createTags($tagName);
        header('Location: ' . $_SERVER['PHP_SELF']);
    }
    
    if (isset($_POST['removetag'])) {
        $TagsId = $_POST['tagId'];
        $A = new Admin($conn);
        $create = $A->removeTags($TagsId);
        header('Location: ' . $_SERVER['PHP_SELF']);
    }
    
    
    if (isset($_POST['modifytags'])) {
        $categoryId = $_POST['tagId'];
        $newCategoryName = $_POST['newtagName'];
        $A = new Admin($conn);
        $modify = $A->modifyTags($newCategoryName, $categoryId);
        header('Location: ' . $_SERVER['PHP_SELF']);
    }
    
    
    /****************************** catagogty function and article */
    $article_query = "SELECT id, title, catagugry_id, statu FROM article";
    $article_stmt = $conn->query($article_query);
    if ($article_stmt && $article_stmt->rowCount() > 0) {
        while ($article = $article_stmt->fetch(PDO::FETCH_ASSOC)) {
            $articles_by_category[$article['catagugry_id']][] = $article; 
        }
    }
    
    if (isset($_POST['createCategory'])) {
        $categoryName = $_POST['categoryName'];
        $A = new Admin($conn);
        $create = $A->createCategory($categoryName);
        header('Location: ' . $_SERVER['PHP_SELF']);
    }
    
    if (isset($_POST['modifyCategory'])) {
        $categoryId = $_POST['categoryId'];
        $newCategoryName = $_POST['newCategoryName'];
        $A = new Admin($conn);
        $modify = $A->modifyCategory($newCategoryName, $categoryId);
        header('Location: ' . $_SERVER['PHP_SELF']);
    }
    
    if (isset($_POST['removeCategory'])) {
        $categoryId = $_POST['categoryId'];
        $remove = $A->removeCategory($categoryId);
        header('Location: ' . $_SERVER['PHP_SELF']);
    
    }
    
    if (isset($_POST['acceptArticle'])) {
        $articleId = $_POST['articleId'];
        if (!empty($articleId)) {
            $accept = $A->acceptArticle($articleId);
    
        }
    }
    
    if (isset($_POST['rejectArticle'])) {
        $articleId = $_POST['articleId'];
        if (!empty($articleId)) {
            $reject = $A->rejectArticle($articleId);
        }
    }

}else {
    header('Location: 401.php');
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <main class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-red-800 to-green-900 text-white">
            <div class="p-6">
                <div class="flex items-center space-x-3">
                    <h1 class="text-2xl font-bold">ADMIN DASHBOARD</h1>
                </div>
            </div>

            <nav class="mt-6">
                <div class="px-6 py-3">
                    <p class="text-xs uppercase text-purple-300">Menu Principal</p>
                </div>
                <a href="../views/list_user_profile.php" class="flex items-center px-6 py-3 hover:bg-red-700 transition-colors duration-200">
                    <i class="fas fa-users mr-3"></i>
                    Gestion des utilisateurs
                </a>
                <a href="../views/commentaires.php" class="flex items-center px-6 py-3 hover:bg-red-700 transition-colors duration-200">
                    <i class="fas fa-newspaper mr-3"></i>
                    Gestion des commentaires
                </a>
                <a href="../views/logout.php" class="flex items-center px-6 py-3 hover:bg-red-700 transition-colors duration-200">
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
                        <h2 class="text-xl font-semibold text-gray-800">Admin Dashboard</h2>
                    </div>
                </div>
            </nav>

            <!-- Dashboard -->
            <div class="p-10 flex flex-wrap gap-8 bg-gray-200">
                <!-- Create Category -->
                <div class="w-full md:w-1/3">
                    <div class="bg-white shadow-md p-6 rounded-md">
                        <h3 class="text-xl font-semibold mb-4">Create Category</h3>
                        <form method="POST">
                            <label for="categoryName" class="block text-gray-700">Category Name</label>
                            <input type="text" name="categoryName" id="categoryName" class="w-full p-2 border border-gray-300 rounded-md" required>
                            <button type="submit" name="createCategory" class="mt-4 w-full py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Create Category</button>
                        </form>
                    </div>
                </div>

                <!-- Modify Category -->
                <div class="w-full md:w-1/3">
                    <div class="bg-white shadow-md p-6 rounded-md">
                        <h3 class="text-xl font-semibold mb-4">Modify Category</h3>
                        <form method="POST">
                            <label for="categoryId" class="block text-gray-700">Category ID</label>
                            <input type="number" name="categoryId" id="categoryId" class="w-full p-2 border border-gray-300 rounded-md" required>
                            <label for="newCategoryName" class="block text-gray-700 mt-4">New Category Name</label>
                            <input type="text" name="newCategoryName" id="newCategoryName" class="w-full p-2 border border-gray-300 rounded-md" required>
                            <button type="submit" name="modifyCategory" class="mt-4 w-full py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">Modify Category</button>
                        </form>
                    </div>
                </div>

                <!-- Remove Category -->
                <div class="w-full md:w-1/3">
                    <div class="bg-white shadow-md p-6 rounded-md">
                        <h3 class="text-xl font-semibold mb-4">Remove Category</h3>
                        <form method="POST">
                            <label for="categoryId" class="block text-gray-700">Category ID</label>
                            <input type="number" name="categoryId" id="categoryId" class="w-full p-2 border border-gray-300 rounded-md" required>
                            <button type="submit" name="removeCategory" class="mt-4 w-full py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Remove Category</button>
                        </form>
                    </div>
                </div>

                <!-- Manage Articles -->
                <div class="w-full md:w-1/3">
                    <div class="bg-white shadow-md p-6 rounded-md">
                        <h3 class="text-xl font-semibold mb-4">Manage Articles</h3>
                        <form method="POST">
                            <label for="articleId" class="block text-gray-700">Select Article</label>
                            <select name="articleId" id="articleId" class="w-full p-2 border border-gray-300 rounded-md" required>
                                <option value=""> select  article</option>
                                <?php
                                foreach ($articles_by_category as $category_id => $articles) {
                                    foreach ($articles as $article) {
                                        echo '<option value="' . $article['id'] . '">' . htmlspecialchars($article['title']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <div class="flex space-x-4 mt-4">
                                <button type="submit" name="acceptArticle" class="py-2 px-5 bg-green-600 text-white rounded-md hover:bg-green-700 w-full">Accept Article</button>
                                <button type="submit" name="rejectArticle" class="py-2 px-5 bg-red-600 text-white rounded-md hover:bg-red-700 w-full">Reject Article</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Manage Articles by Category -->
            <!-- <div class="w-full">
                <div class="bg-white shadow-md p-6 rounded-md">
                    <h3 class="text-xl font-semibold mb-4">Articles by Category</h3>
                    <?php foreach ($categories as $category): ?>
                        <div class="mb-6">
                            <div class="overflow-x-auto p-4 bg-white rounded-lg shadow-md">
                                <table class="table-auto w-full border-collapse border border-gray-200">
                                    <thead class="bg-orange-700 text-white">
                                        <tr>
                                            <th class="px-4 py-2 border border-gray-300"><?= htmlspecialchars($category['name']) ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="3">
                                                <ul class="list-disc pl-5">
                                                    <?php if (isset($articles_by_category[$category['id']])): ?>
                                                        <?php foreach ($articles_by_category[$category['id']] as $article): ?>
                                                            <li class="flex justify-between">
                                                                <span><?= htmlspecialchars($article['title']) ?></span>
                                                                <span class="text-xs text-gray-500">ID: <?= htmlspecialchars($article['id']) ?></span>
                                                                <span class="text-xs text-gray-500"><?= htmlspecialchars($article['statu']) ?></span>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <li>no articles in this category.</li>
                                                    <?php endif; ?>
                                                </ul>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div> -->

            <!-- Categories Table -->
            <div class="overflow-x-auto p-4 bg-white rounded-lg shadow-md">
                <table class="table-auto w-full border-collapse border border-gray-200">
                    <thead class="bg-orange-700 text-white">
                        <tr>
                            <th class="px-4 py-2 border border-gray-300">ID Catagugry</th>
                            <th class="px-4 py-2 border border-gray-300">Catagugry Name</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($categories as $category): ?>
                        <tr class="odd:bg-purple-50 even:bg-purple-100 hover:bg-purple-200">
                            <td class="px-4 py-2 border border-gray-300"><?= htmlspecialchars($category['id']) ?></td>
                            <td class="px-4 py-2 border border-gray-300"><?= htmlspecialchars($category['name']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>



    




            

            <div class="p-10 flex flex-wrap gap-8 bg-gray-200">
                <!-- Create tag -->
                <div class="w-full md:w-1/3">
                    <div class="bg-white shadow-md p-6 rounded-md">
                        <h3 class="text-xl font-semibold mb-4">Create TAGS</h3>
                        <form method="POST">
                            <label for="categoryName" class="block text-gray-700">TAG Name</label>
                            <input type="text" name="tagName" id="categoryName" class="w-full p-2 border border-gray-300 rounded-md" required>
                            <button type="submit" name="createTag" class="mt-4 w-full py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Create TAG</button>
                        </form>
                    </div>
                </div>

                <!-- Modify tag -->
                <div class="w-full md:w-1/3">
                    <div class="bg-white shadow-md p-6 rounded-md">
                        <h3 class="text-xl font-semibold mb-4">Modify TAG</h3>
                        <form method="POST">
                            <label for="tagId" class="block text-gray-700">tag ID</label>
                            <input type="number" name="tagId" id="categoryId" class="w-full p-2 border border-gray-300 rounded-md" required>
                            <label for="newtagName" class="block text-gray-700 mt-4">New tag Name</label>
                            <input type="text" name="newtagName" id="newCategoryName" class="w-full p-2 border border-gray-300 rounded-md" required>
                            <button type="submit" name="modifyTags" class="mt-4 w-full py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">Modify tag</button>
                        </form>
                    </div>
                </div>

                <!-- Remove tag -->
                <div class="w-full md:w-1/3">
                    <div class="bg-white shadow-md p-6 rounded-md">
                        <h3 class="text-xl font-semibold mb-4">Remove tag</h3>
                        <form method="POST">
                            <label for="categoryId" class="block text-gray-700">tag ID</label>
                            <input type="number" name="tagId" id="categoryId" class="w-full p-2 border border-gray-300 rounded-md" required>
                            <button type="submit" name="removetag" class="mt-4 w-full py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Remove tag</button>
                        </form>
                    </div>
                </div>
              
                


 <!-- tags Table -->
 <div class="overflow-x-auto p-4 bg-white rounded-lg shadow-md">
                <table class="table-auto w-full border-collapse border border-gray-200">
                    <thead class="bg-orange-700 text-white">
                        <tr>
                            <th class="px-4 py-2 border border-gray-300">ID TAGS</th>
                            <th class="px-4 py-2 border border-gray-300">TAGS Name</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($Tags as $TAG): ?>
                        <tr class="odd:bg-purple-50 even:bg-purple-100 hover:bg-purple-200">
                            <td class="px-4 py-2 border border-gray-300"><?= htmlspecialchars($TAG['id']) ?></td>
                            <td class="px-4 py-2 border border-gray-300"><?= htmlspecialchars($TAG['tagsname']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>






        </section>
    </main>
</body>
</html>
