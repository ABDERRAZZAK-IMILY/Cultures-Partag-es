<?php
require_once '../model/db_connect.php';

require_once '../model/admin.php';

session_start();

$conn = (new DATABASE())->getConnection();

$categories = [];
$aq = "SELECT id, name FROM catagugry";
$stmt = $conn->query($aq);
if ($stmt && $stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $categories[] = $row;
    }
}

if (isset($_POST['createCategory'])) {
 
    $categoryName = $_POST['categoryName'];

    $A = new Admin($conn);
    $create = $A ->createCategory($categoryName);
}

if (isset($_POST['modifyCategory'])) {
    $categoryId = $_POST['categoryId'];
    $newCategoryName = $_POST['newCategoryName'];
    $stmt = $conn->prepare("UPDATE catagugry SET name = ? WHERE id = ?");
    if ($stmt->execute([$newCategoryName, $categoryId])) {
        echo 'Category updated successfully!';
    } else {
        echo 'Error occurred while updating the category!';
    }
}

if (isset($_POST['removeCategory'])) {
    $categoryId = $_POST['categoryId'];
    $stmt = $conn->prepare("DELETE FROM catagugry WHERE id = ?");
    if ($stmt->execute([$categoryId])) {
        echo 'Category removed successfully!';
    } else {
        echo 'Error occurred while removing the category!';
    }
}

if (isset($_POST['acceptArticle'])) {
    $articleId = $_POST['articleId'];
    $stmt = $conn->prepare("UPDATE article SET statu = 'accepted' WHERE id = ?");
    if ($stmt->execute([$articleId])) {
        echo 'Article accepted!';
    } else {
        echo 'Error occurred while accepting the article!';
    }
}

if (isset($_POST['rejectArticle'])) {
    $articleId = $_POST['articleId'];
    $stmt = $conn->prepare("UPDATE article SET statu = 'rejected' WHERE id = ?");
    if ($stmt->execute([$articleId])) {
        echo 'Article rejected!';
    } else {
        echo 'Error occurred while rejecting the article!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">


<nav class="bg-white border-gray-200 dark:bg-gray-900">
    <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl p-4">
        <a href="https://flowbite.com" class="flex items-center space-x-3 rtl:space-x-reverse">
            <img src="https://flowbite.com/docs/images/logo.svg" class="h-8" alt="Flowbite Logo" />
            <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">WELCON ADMIN</span>
        </a>
        <div class="flex items-center space-x-6 rtl:space-x-reverse">
            <a href="../views/logout.php" class="text-sm  text-blue-600 dark:text-blue-500 hover:underline">Logout</a>
        </div>
    </div>
</nav>
<nav class="bg-gray-50 dark:bg-gray-700">
    <div class="max-w-screen-xl px-4 py-3 mx-auto">
        <div class="flex items-center">
            <ul class="flex flex-row font-medium mt-0 space-x-8 rtl:space-x-reverse text-sm">
                <li>
                    <a href="#" class="text-gray-900 dark:text-white hover:underline" aria-current="page">Home</a>
                </li>
                <li>
                    <a href="../views/list_user_profile.php" class="text-gray-900 dark:text-white hover:underline">Gestion des utilisateurs</a>
                </li>
                <li>
                    <a href="#" class="text-gray-900 dark:text-white hover:underline">Gestion des catégories</a>
                </li>
                <li>
                    <a href="#" class="text-gray-900 dark:text-white hover:underline">Gestion des articles</a>
                </li>
            </ul>
        </div>
    </div>
</nav>



    <div class="min-h-screen flex flex-col items-center justify-center py-12 px-6 sm:px-10">
        <header class="mb-8 text-center">
            <h1 class="text-3xl font-semibold text-gray-800">Admin Dashboard</h1>
        </header>

        <div class="w-full max-w-3xl bg-white shadow-md rounded-lg p-8">

            <!-- Add New Category -->
            <div class="mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Create New Category</h2>
                <form action="#" method="POST">
                    <div class="space-y-4">
                        <div>
                            <label for="categoryName" class="block text-gray-700">Category Name</label>
                            <input type="text" id="categoryName" name="categoryName" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <button type="submit" name="createCategory" class="w-full py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none">Create Category</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modify Category -->
            <div class="mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Modify Category</h2>
                <form action="#" method="POST">
                    <div class="space-y-4">
                        <div>
                            <label for="categoryId" class="block text-gray-700">Category ID</label>
                            <input type="text" id="categoryId" name="categoryId" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label for="newCategoryName" class="block text-gray-700">New Category Name</label>
                            <input type="text" id="newCategoryName" name="newCategoryName" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <button type="submit" name="modifyCategory" class="w-full py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 focus:outline-none">Modify Category</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Remove Category -->
            <div class="mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Remove Category</h2>
                <form action="#" method="POST">
                    <div class="space-y-4">
                        <div>
                            <label for="categoryId" class="block text-gray-700">Category ID</label>
                            <input type="text" id="categoryId" name="categoryId" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <button type="submit" name="removeCategory" class="w-full py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none">Remove Category</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Manage Articles -->
            <div class="mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Manage Articles</h2>
                <form action="#" method="POST">
                    <div class="space-y-4">
                        <div>
                            <label for="articleId" class="block text-gray-700">Article ID</label>
                            <input type="text" id="articleId" name="articleId" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <button type="submit" name="acceptArticle" class="w-full py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none">Accept Article</button>
                        </div>
                        <div>
                            <button type="submit" name="rejectArticle" class="w-full py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none">Reject Article</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>

</body>
</html>
