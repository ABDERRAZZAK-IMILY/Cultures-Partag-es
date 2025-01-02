<?php
require_once '../model/db_connect.php';
session_start();

if (isset($_SESSION['role']) && $_SESSION['role'] === 'auteur') {
    $conn = (new DATABASE())->getConnection();

    // Fetch categories for the select dropdown
    $categories = [];
    $categoryQuery = "SELECT id, name FROM catagugry";
    $stmt = $conn->query($categoryQuery);

    if ($stmt && $stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories[] = $row;
        }
    }

    // Handle creating a new article
    if (isset($_POST['createArticle'])) {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $categoryId = $_POST['cataguryname'];
        $date_res = date('Y-m-d');

        $stmt2 = $conn->prepare("INSERT INTO article (user_id, catagugry_id, date_creation, description, image) VALUES (?, ?, ?, ?, '')");

        if ($stmt2->execute([$_SESSION['user_id'], $categoryId, $date_res, $content])) {
            $message = 'Article created successfully!';
        } else {
            $message = 'Error: ' . implode(', ', $stmt2->errorInfo());
        }
    }

    // Handle modifying an article
    if (isset($_POST['modifyArticle'])) {
        $articleId = $_POST['articleId'];
        $newCategoryId = $_POST['cataguryname'];
        $newTitle = $_POST['newTitle'];
        $newContent = $_POST['newContent'];

        $stmt = $conn->prepare("UPDATE article SET title = ?, catagugry_id = ?, description = ? WHERE id = ?");
        if ($stmt->execute([$newTitle, $newCategoryId, $newContent, $articleId])) {
            $message = 'Article updated successfully!';
        } else {
            $message = 'Error: ' . implode(', ', $stmt->errorInfo());
        }
    }

    // Handle deleting an article
    if (isset($_POST['removeArticle'])) {
        $articleId = $_POST['removeArticleId'];

        $stmt = $conn->prepare("DELETE FROM article WHERE id = ?");
        if ($stmt->execute([$articleId])) {
            $message = 'Article removed successfully!';
        } else {
            $message = 'Error: ' . implode(', ', $stmt->errorInfo());
        }
    }

    // Fetch accepted articles for display
    $articles = [];
    $query = "SELECT id, title, description, date_creation FROM article WHERE statu = 'accepted'";
    $stmt = $conn->query($query);

    if ($stmt && $stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $articles[] = $row;
        }
    } else {
        $message = "No accepted articles found.";
    }
} else {
    die("Access denied. You are not authorized.");
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
                <a href="login.html"
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
                    <div>
                        <img src="https://images.unsplash.com/photo-1579541671172-43429ce17aca?q=80&w=2065&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                            class="rounded-t-md" alt="Couverture de l'Article">
                    </div>
                    <div class="p-4">
                        <p class="text-gray-800 font-medium text-sm"><?= $article['date_creation'] ?> •</p>
                        <div class="pt-5">
                            <a href="#"><h1 class="text-gray-900 font-semibold text-xl mb-3"><?= htmlspecialchars($article['title']) ?></h1></a>
                            <p class="text-gray-700 font-medium text-md"><?= nl2br(htmlspecialchars($article['description'])) ?></p>
                        </div>
                        <div class="flex justify-end items-center gap-5 mt-5">
                            <form action="" method="POST" class="inline-block">
                                <input type="hidden" name="articleId" value="<?= $article['id'] ?>">
                                <button type="submit" name="modifyArticleBtn" class="py-2 px-5 rounded-sm text-white bg-blue-500 text-sm duration-500 hover:bg-blue-700">Modifier</button>
                            </form>
                            <form action="" method="POST" class="inline-block">
                                <input type="hidden" name="removeArticleId" value="<?= $article['id'] ?>">
                                <button type="submit" name="removeArticle" class="py-2 px-5 rounded-sm text-white bg-red-500 text-sm duration-500 hover:bg-red-700">Supprimer</button>
                            </form>
                        </div>
                    </div>
                    <p class="absolute top-2 right-2 bg-white bg-opacity-85 py-1 px-3 rounded-md text-xs">Peinture</p>
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
                            <button type="submit" name="createArticle" class="w-full py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none">Create Article</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </main>
</body>

</html>
