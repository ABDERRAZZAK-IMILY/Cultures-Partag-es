<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once '../model/db_connect.php'; 

if (isset($_POST['article_id'])) {
    $article_id = $_POST['article_id'];

    $conn = (new DATABASE())->getConnection();

    $query = "SELECT a.title, a.description, a.image, a.date_creation, c.name
              FROM article a
              JOIN catagugry c ON a.catagugry_id = c.id
              WHERE a.id = :article_id AND a.statu = 'accepted'";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $article = $stmt->fetch(PDO::FETCH_ASSOC);

        $mpdf = new \Mpdf\Mpdf();

        $html = '
                    <img alt="Article Image" class="w-full h-auto mb-4 rounded-lg" height="400" src="' . htmlspecialchars($article['image']) . '" width="800"/>

        <h1>' . htmlspecialchars($article['title']) . '</h1>
        <h3>Catégorie: ' . htmlspecialchars($article['name']) . '</h3>
        <p><strong>Date de création:</strong> ' . $article['date_creation'] . '</p>
        <div>
            <strong>Description:</strong>
            <p>' . nl2br(htmlspecialchars($article['description'])) . '</p>
        </div>
        ';

        $mpdf->WriteHTML($html);

        $mpdf->Output('article_' . $article_id . '.pdf', 'D');
        exit;
    } else {
        echo "Article non trouvé.";
        exit;
    }
} else {
    echo "ID de l'article invalide.";
    exit;
}
?>
