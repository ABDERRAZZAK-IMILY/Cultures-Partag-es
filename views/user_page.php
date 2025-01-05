<?php
require_once '../model/db_connect.php';

require_once '../model/USER.php';

session_start();


$conn = (new DATABASE())->getConnection();

$articles = [];
$query = "SELECT description, image, date_creation, title, name FROM article join catagugry on catagugry.id = article.catagugry_id WHERE statu = 'accepted'";
$stmt = $conn->query($query);

if ($stmt && $stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $articles[] = $row;
    }
} else {
    echo "no article found.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Articles</title>
</head>
<body class="bg-gray-100">

    <!-- Header -->
    <header class="bg-gray-800 p-4 text-white flex justify-between items-center">
        <h1 class="text-xl font-bold">Article BLOG</h1>
        <a href="logout.php" class="text-white bg-red-600 px-4 py-2 rounded hover:bg-red-700">Logout</a>
    </header>

    <!-- Main Content -->
    <section class="py-6 sm:py-12 dark:bg-gray-100 dark:text-gray-800">
        <div class="container p-6 mx-auto space-y-8">
            <div class="space-y-2 text-center">
                <h2 class="text-3xl font-bold">ARTICLES</h2>
                <p class="font-serif text-sm dark:text-gray-600">BEST BLOG EVER</p>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                <?php foreach ($articles as $article): ?>
                <article class="relative bg-white shadow-md rounded-md overflow-hidden">
                    <img class="object-cover w-full h-52" src="<?= htmlspecialchars($article['image']) ?: 'https://source.unsplash.com/200x200/?fashion' ?>" alt="Article Image">

                    <div class="p-4">
                        <p class="text-sm text-gray-500"><?= date("F j, Y", strtotime($article['date_creation'])) ?></p>
                        <h1 class="text-gray-900 font-semibold text-xl mt-2 mb-3"><?= htmlspecialchars($article['title']) ?></h1>
                        <p class="text-gray-700 text-md"><?= htmlspecialchars($article['description']) ?></p>
                    </div>

                    <div class="absolute top-4 right-4 bg-white bg-opacity-80 py-1 px-3 rounded-md text-xs">
                        <p class="text-gray-700"><?= htmlspecialchars($article['name']) ?></p>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center p-4">
        <p>&copy; 2025 Article BLOG. All rights reserved.</p>
    </footer>

</body>
</html>
