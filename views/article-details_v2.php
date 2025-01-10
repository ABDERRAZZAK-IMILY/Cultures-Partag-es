<?php
require_once '../model/db_connect.php';

session_start();

if (isset($_GET['id'])) {
    $article_id = $_GET['id'];

    $conn = (new DATABASE())->getConnection();

    $query = "SELECT a.id, a.title, a.description, a.image, a.date_creation, c.name
              FROM article a
              JOIN catagugry c ON a.catagugry_id = c.id
              JOIN users u ON a.user_id = u.id
              WHERE a.id = :article_id AND a.statu = 'accepted'"; 
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $article = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "Article not found or not accepted.";
        exit;
    }

    if (isset($_POST['likedarticle'])) {
        $articleid = $article['id'];
        $userid = $_SESSION['user_id'];
        
        $c = new DATABASE();
        $conn = $c->getConnection();

        $querylike = "INSERT INTO likes (user_id, article_id) VALUES ($userid, $articleid)";
        $ex = $conn->prepare($querylike);

        if ($ex->execute()) {
            echo "<script>
                    window.onload = function() {
                        Swal.fire({
                            title: 'Liked!',
                            text: 'Article liked on your profile!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    }
                  </script>";
        } else {
            echo 'Error liking article.';
        }
    }

    if (isset($_POST['comment_submit'])) {
        $articleid = $article['id'];
        $userid = $_SESSION['user_id'];
        $c = new DATABASE();
        $conn = $c->getConnection();

        $comment = $_POST['comment'];

        $querycomment = "INSERT INTO comments (user_id, article_id, content) VALUES ($userid, $articleid, :comment)";
        $exp = $conn->prepare($querycomment);
        $exp->bindParam(':comment', $comment, PDO::PARAM_STR);

        if ($exp->execute()) {
            header("Location: {$_SERVER['PHP_SELF']}?id=$articleid");
            exit;
        } else {
            echo 'Error submitting comment.';
        }
    }

    $stmtComments = $conn->prepare("SELECT * FROM comments JOIN users ON comments.user_id = users.id WHERE article_id = :article_id");
    $stmtComments->bindParam(':article_id', $article_id, PDO::PARAM_INT);
    $stmtComments->execute();
    $comments = $stmtComments->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Invalid article ID.";
    exit;
}
?>

<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Article Details</title>
    <link rel="stylesheet" href="sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
</head>
<body class="bg-gray-100 font-roboto">
    <div class="max-w-4xl mx-auto p-4">
        <article class="bg-white p-6 rounded-lg shadow-md">
            <h1 class="text-3xl font-bold mb-4">
                <?php echo htmlspecialchars($article['title']); ?>
            </h1>
            <a href="#" class="py-2 text-green-700 inline-flex items-center justify-center mb-2">
                <?php echo htmlspecialchars($article['name']); ?>
            </a>

            <img alt="Article Image" class="w-full h-auto mb-4 rounded-lg" height="400" src="<?php echo htmlspecialchars($article['image']); ?>" width="800"/>
            <p class="text-gray-700 mb-6">
                <?php echo nl2br(htmlspecialchars($article['description'])); ?>
            </p>

            <div class="bg-white p-6 rounded-lg shadow-lg mb-20">
                <h2 class="text-lg font-semibold mb-4">Tags</h2>
                <div class="flex flex-wrap gap-2">
                    <?php
                    $articleTagsQuery = "SELECT tagsname FROM article_tags JOIN tags ON article_tags.id_tags = tags.id WHERE id_article = :articleid";
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

            <form action="" method="POST">
                <button type="submit" name="likedarticle" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    <i class="fas fa-thumbs-up"></i> Add to liked articles
                </button>
            </form>
        </article>

        <div class="bg-gray-100 p-6">
            <h2 class="text-lg font-bold mb-4">Comments</h2>
            <div class="flex flex-col space-y-4">
                <?php if (count($comments) > 0): ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="bg-white p-4 rounded-lg shadow-md">
                            <h3 class="text-lg font-bold"><?php echo htmlspecialchars($comment['lastName']); ?></h3>
                            <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No comments yet.</p>
                <?php endif; ?>

                <!-- Comment Form -->
                <form class="bg-white p-4 rounded-lg shadow-md" method="POST">
                    <h3 class="text-lg font-bold mb-2">Add a comment</h3>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2" for="comment">
                            Comment
                        </label>
                        <textarea
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="comment" rows="3" name="comment" placeholder="Enter your comment"></textarea>
                    </div>
                    <button
                        class="bg-cyan-500 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        name="comment_submit" type="submit">
                        Submit
                    </button>
                </form>
            </div>
        </div>
    </div>
</body> 
</html>
