<?php
require_once '../model/db_connect.php';

session_start();

$conn = (new DATABASE())->getConnection();

$articles = [];
$query = "SELECT id, description, image, date_creation , title FROM article WHERE statu = 'accepted'";
$stmt = $conn->query($query);

if ($stmt && $stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $articles[] = $row;
    }
} else {
    echo "no articles found.";
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
<body>
    <section class="py-6 sm:py-12 dark:bg-gray-100 dark:text-gray-800">
        <div class="container p-6 mx-auto space-y-8">
            <div class="space-y-2 text-center">
                <h2 class="text-3xl font-bold">ARTICLES</h2>
                <p class="font-serif text-sm dark:text-gray-600">BEST BLOG EVER</p>
            </div>

            <!-- Display Articles -->
            <div class="grid grid-cols-1 gap-x-4 gap-y-8 md:grid-cols-2 lg:grid-cols-4">
                <?php foreach ($articles as $article): ?>
                <article class="flex flex-col dark:bg-gray-50">
                    <a href="article_details.php?id=<?= $article['id'] ?>" aria-label="<?= htmlspecialchars($article['title']) ?>">
                        <img alt="<?= htmlspecialchars($article['title']) ?>" class="object-cover w-full h-52 dark:bg-gray-500" src="<?= $article['image'] ? $article['image'] : 'https://source.unsplash.com/200x200/?fashion' ?>">
                    </a>
                    <div class="flex flex-col flex-1 p-6">
                        <a href="article_details.php?id=<?= $article['id'] ?>" class="text-xs tracking-wider uppercase hover:underline dark:text-violet-600"><?= htmlspecialchars($article['title']) ?></a>
                        <h3 class="flex-1 py-2 text-lg font-semibold leading-snug"><?= $article['description'] ?></h3>
                        <div class="flex flex-wrap justify-between pt-3 space-x-2 text-xs dark:text-gray-600">
                            <span><?= date("F j, Y", strtotime($article['date_creation'])) ?></span>
                            <span><?= rand(100, 100000) ?> views</span>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</body>
</html>
