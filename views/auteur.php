<?php

require_once '../model/db_connect.php';
session_start();

if (isset($_SESSION['role']) && $_SESSION['role'] === 'auteur') {



$conn = (new DATABASE())->getConnection();

$categories = [];
$aq = "SELECT id, name FROM catagugry";
$stmt = $conn->query($aq);

if ($stmt && $stmt->rowCount() > 0) {
    while ($ro = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $categories[] = $ro;
    }
}

if (isset($_POST['createArticle'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $categoryId = $_POST['cataguryname'];
    $date_res = date('Y-m-d');

    $image = $_POST['image'];

    $stmt2 = $conn->prepare("INSERT INTO article (user_id, catagugry_id, date_creation, description, image , title) VALUES (?, ?, ?, ?, ? ,?)");
    
    if ($stmt2->execute([$_SESSION['user_id'], $categoryId, $date_res, $content, $image , $title])) {
        echo 'Article created successfully!';
    } else {
        echo 'Error: ' . implode(', ', $stmt2->errorInfo());
    }
}

if (isset($_POST['modifyArticle'])) {
    $articleId = $_POST['articleId'];
    $newCategoryId = $_POST['cataguryname'];
    $newTitle = $_POST['newTitle'];
    $newContent = $_POST['newContent'];

    $stmt = $conn->prepare("UPDATE article SET title = ?, catagugry_id = ?, description = ? WHERE id = ?");
    if ($stmt->execute([$newTitle, $newCategoryId, $newContent, $articleId])) {
        echo 'Article updated successfully!';
    } else {
        echo 'Error: ' . implode(', ', $stmt->errorInfo());
    }
}

if (isset($_POST['removeArticle'])) {
    $articleId = $_POST['removeArticleId'];

    $stmt = $conn->prepare("DELETE FROM article WHERE id = ?");
    if ($stmt->execute([$articleId])) {
        echo 'Article removed successfully!';
    } else {
        echo 'Error: ' . implode(', ', $stmt->errorInfo());
    }
}

}else {

    die("error");
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auteur Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans antialiased">

    <div class="min-h-screen flex flex-col items-center justify-center py-12 px-6 sm:px-10">

        <header class="mb-8 text-center">
            <h1 class="text-3xl font-semibold text-gray-800">Auteur Dashboard</h1>
            <p class="text-xl text-gray-600">Manage your articles</p>
        </header>

        <div class="w-full max-w-3xl bg-white shadow-md rounded-lg p-8">

            <!-- Create New Article Form -->
            <div class="mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Create New Article</h2>
                <form action="" method="POST">
                    <div class="space-y-4">
                        <div>
                            <label for="articleTitle" class="block text-gray-700">Article Title</label>
                            <input type="text" id="articleTitle" name="title" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label for="catagury" class="block text-gray-700">Category</label>
                            <select id="catagury" name="cataguryname" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="articleContent" class="block text-gray-700">Article Content</label>
                            <textarea id="articleContent" name="content" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                        </div>
                        <div>
                            <label for="image" class="block text-gray-700">image</label>
                            <input type="file" id="image" name="image" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <button type="submit" name="createArticle" class="w-full py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none">Create Article</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modify Article Form -->
            <div class="mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Modify Article</h2>
                <form action="" method="POST">
                    <div class="space-y-4">
                        <div>
                            <label for="articleId" class="block text-gray-700">Article ID</label>
                            <input type="text" id="articleId" name="articleId" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label for="catagury" class="block text-gray-700">New Category</label>
                            <select id="catagury" name="cataguryname" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="newTitle" class="block text-gray-700">New Title</label>
                            <input type="text" id="newTitle" name="newTitle" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label for="newContent" class="block text-gray-700">New Content</label>
                            <textarea id="newContent" name="newContent" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                        </div>
                        <div>
                            <button type="submit" name="modifyArticle" class="w-full py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 focus:outline-none">Modify Article</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Remove Article Form -->
            <div class="mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Remove Article</h2>
                <form action="" method="POST">
                    <div class="space-y-4">
                        <div>
                            <label for="removeArticleId" class="block text-gray-700">Article ID</label>
                            <input type="text" id="removeArticleId" name="removeArticleId" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <button type="submit" name="removeArticle" class="w-full py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none">Remove Article</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</body>

</html>
