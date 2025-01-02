<?php
// Include database connection
require_once '../model/db_connect.php';
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
    $stmt = $conn->prepare("INSERT INTO catagugry (name) VALUES (?)");
    if ($stmt->execute([$categoryName])) {
        $message = 'Category added successfully!';
    } else {
        $message = 'Error occurred while adding the category!';
    }
}

if (isset($_POST['modifyCategory'])) {
    $categoryId = $_POST['categoryId'];
    $newCategoryName = $_POST['newCategoryName'];
    $stmt = $conn->prepare("UPDATE catagugry SET name = ? WHERE id = ?");
    if ($stmt->execute([$newCategoryName, $categoryId])) {
        $message = 'Category updated successfully!';
    } else {
        $message = 'Error occurred while updating the category!';
    }
}

if (isset($_POST['removeCategory'])) {
    $categoryId = $_POST['categoryId'];
    $stmt = $conn->prepare("DELETE FROM catagugry WHERE id = ?");
    if ($stmt->execute([$categoryId])) {
        $message = 'Category removed successfully!';
    } else {
        $message = 'Error occurred while removing the category!';
    }
}

if (isset($_POST['acceptArticle'])) {
    $articleId = $_POST['articleId'];
    $stmt = $conn->prepare("UPDATE article SET statu = 'accepted' WHERE id = ?");
    if ($stmt->execute([$articleId])) {
        $message = 'Article accepted!';
    } else {
        $message = 'Error occurred while accepting the article!';
    }
}

if (isset($_POST['rejectArticle'])) {
    $articleId = $_POST['articleId'];
    $stmt = $conn->prepare("UPDATE article SET statu = 'rejected' WHERE id = ?");
    if ($stmt->execute([$articleId])) {
        $message = 'Article rejected!';
    } else {
        $message = 'Error occurred while rejecting the article!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
                <a href="#" class="flex items-center px-6 py-3 hover:bg-red-700 transition-colors duration-200">
                    <i class="fas fa-users mr-3"></i>
                    Gestion des utilisateurs
                </a>
                <a href="#" class="flex items-center px-6 py-3 hover:bg-red-700 transition-colors duration-200">
                    <i class="fas fa-clipboard-list mr-3"></i>
                    Gestion des catégories
                </a>
                <a href="#" class="flex items-center px-6 py-3 hover:bg-red-700 transition-colors duration-200">
                    <i class="fas fa-newspaper mr-3"></i>
                    Gestion des articles
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
                            <label for="articleId" class="block text-gray-700">Article ID</label>
                            <input type="number" name="articleId" id="articleId" class="w-full p-2 border border-gray-300 rounded-md" required>
                            <div class="flex space-x-4">
                                <button type="submit" name="acceptArticle" class="mt-4 py-2 px-5 bg-green-600 text-white rounded-md hover:bg-green-700 w-full">Accept Article</button>
                                <button type="submit" name="rejectArticle" class="mt-4 py-2 px-5 bg-red-600 text-white rounded-md hover:bg-red-700 w-full">Reject Article</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
