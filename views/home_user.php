<?php
require_once '../model/db_connect.php';

session_start();

$conn = (new DATABASE())->getConnection();

$articles = [];
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$keyword_filter = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 3;
$offset = ($page - 1) * $limit;

$query = "SELECT article.id , description, article.image, date_creation, title, name FROM article 
          JOIN catagugry ON catagugry.id = article.catagugry_id
          JOIN users on users.id = article.user_id
          WHERE statu = 'accepted'";

if ($category_filter) {
    $query .= " AND catagugry.name = :category";
}

if ($keyword_filter) {
    $query .= " AND (article.title LIKE :keyword OR article.description LIKE :keyword)";
}

$query .= " ORDER BY date_creation DESC LIMIT :limit OFFSET :offset";

$stmt = $conn->prepare($query);

if ($category_filter) {
    $stmt->bindParam(':category', $category_filter, PDO::PARAM_STR);
}
if ($keyword_filter) {
    $keyword_param = "%" . $keyword_filter . "%";
    $stmt->bindParam(':keyword', $keyword_param, PDO::PARAM_STR);
}


$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();

if ($stmt && $stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $articles[] = $row;
    }
} else {
    echo "No articles found.";
}

$categories = [];
$category_query = "SELECT DISTINCT name FROM catagugry";
$category_stmt = $conn->query($category_query);
if ($category_stmt && $category_stmt->rowCount() > 0) {
    while ($category = $category_stmt->fetch(PDO::FETCH_ASSOC)) {
        $categories[] = $category['name'];
}
}

// Pagination logic
$total_query = "SELECT COUNT(*) FROM article 
                JOIN catagugry ON catagugry.id = article.catagugry_id 
                WHERE statu = 'accepted'";

if ($category_filter) {
    $total_query .= " AND catagugry.name = :category";
}

if ($keyword_filter) {
    $total_query .= " AND (article.title LIKE :keyword OR article.description LIKE :keyword)";
}



$total_stmt = $conn->prepare($total_query);
if ($category_filter) {
    $total_stmt->bindParam(':category', $category_filter, PDO::PARAM_STR);
}
if ($keyword_filter) {
    $total_stmt->bindParam(':keyword', $keyword_param, PDO::PARAM_STR);
}


$total_stmt->execute();
$total_articles = $total_stmt->fetchColumn();
$total_pages = ceil($total_articles / $limit);

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
    <a href="../views/visteur_profile.php" class="text-white bg-red-600 px-4 py-2 rounded hover:bg-red-700">PROFILE</a>
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

            <!-- Advanced Search Form -->
            <div class="text-center mb-6">
                <form method="GET" action="" class="flex justify-center space-x-4">
                    <div>
                        <label for="keyword" class="text-lg font-medium text-gray-700">Search by Keyword:</label>
                        <input type="text" name="keyword" id="keyword" value="<?= htmlspecialchars($keyword_filter) ?>" class="ml-4 p-2 border rounded" placeholder="Search articles...">
                    </div>

                 

                    <button type="submit" class="ml-4 p-2 bg-blue-500 text-white rounded hover:bg-blue-600">Search</button>
                </form>
            </div>

            <!-- Sort by Category -->
            <div class="text-center mb-6">
                <form method="GET" action="" class="flex justify-center space-x-4">
                    <label for="category" class="text-lg font-medium text-gray-700">Sort by Category:</label>
                    <select name="category" id="category" class="ml-4 p-2 border rounded">
                        <option value="">-- Select Category --</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= htmlspecialchars($category) ?>" <?= $category === $category_filter ? 'selected' : '' ?>><?= htmlspecialchars($category) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="ml-4 p-2 bg-blue-500 text-white rounded hover:bg-blue-600">Sort</button>
                </form>
            </div>

            <!-- Articles List -->
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                <?php foreach ($articles as $article): ?>
                <article class="relative bg-white shadow-md rounded-md overflow-hidden">
                    <img class="object-cover w-full h-52" src="<?= htmlspecialchars($article['image']) ?: 'https://source.unsplash.com/200x200/?fashion' ?>" alt="Article Image">

                    <div class="p-4">
                    <form action="../views/article-details_v2.php" method="GET">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($article['id']); ?>">
                     <button type="submit"  class="ml-4 p-2 bg-blue-500 text-white rounded hover:bg-blue-600">articledetails</button>
                    </form>
                        <p class="text-sm text-gray-500"><?= date("F j, Y", strtotime($article['date_creation'])) ?></p>
                        <h1     class="text-gray-900 font-semibold text-xl mt-2 mb-3"><?= htmlspecialchars($article['title']) ?></h1>
                        <p class="text-gray-700 text-md"><?= htmlspecialchars($article['description']) ?></p>
                    </div>

                    <div class="absolute top-4 right-4 bg-white bg-opacity-80 py-1 px-3 rounded-md text-xs">
                        <p class="text-gray-700"><?= htmlspecialchars($article['name']) ?></p>
                    </div>


                    <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-lg font-semibold mb-4">Tag</h2>
            <div class="flex flex-wrap gap-2">
                <!-- Display Tags -->
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

            <!-- Pagination Links -->
            <div class="flex justify-center space-x-2 mt-6">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>&keyword=<?= urlencode($keyword_filter) ?>&author=<?= urlencode($author_filter) ?>&category=<?= urlencode($category_filter) ?>" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Previous</a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?= $i ?>&keyword=<?= urlencode($keyword_filter) ?>&author=<?= urlencode($author_filter) ?>&category=<?= urlencode($category_filter) ?>" class="px-4 py-2 bg-gray-300 text-black rounded hover:bg-gray-400 <?= $i === $page ? 'bg-blue-500 text-white' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?= $page + 1 ?>&keyword=<?= urlencode($keyword_filter) ?>&author=<?= urlencode($author_filter) ?>&category=<?= urlencode($category_filter) ?>" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Next</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center p-4">
        <p>&copy; 2025 Article BLOG. All rights reserved.</p>
    </footer>

</body>
</html>
