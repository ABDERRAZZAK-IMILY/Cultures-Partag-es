<?php
require_once '../model/db_connect.php';

session_start();

if (isset($_GET['id'])) {
    $article_id = $_GET['id'];

    $conn = (new DATABASE())->getConnection();

    $query = "SELECT a.id, a.title, a. description, a.image, a.date_creation, c.name as category_name, u.name as author_name 
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
        echo "article not found or not accepted.";
        exit;
    }
} else {
    echo "Invalid article ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Article Details</title>
</head>
<body>

<div class="max-w-screen-lg mx-auto">
    <!-- Header -->
    <header class="flex items-center justify-between py-2">
      <a href="#" class="px-2 lg:px-0 font-bold">
        <?php echo htmlspecialchars($article['author_name']); ?>
      </a>
      <button class="block md:hidden px-2 text-3xl">
        <i class='bx bx-menu'></i>
      </button>
      <ul class="hidden md:inline-flex items-center">
        <li class="px-2 md:px-4">
          <a href="" class="text-green-800 font-semibold hover:text-green-600">LOGOUT</a>
        </li>
      </ul>
    </header>
    <!-- Header ends here -->

    <main class="mt-10">

      <div class="mb-4 md:mb-0 w-full mx-auto relative">
        <div class="px-4 lg:px-0">
          <h2 class="text-4xl font-semibold text-gray-800 leading-tight">
            <?php echo htmlspecialchars($article['title']); ?>
          </h2>
          <a href="#" class="py-2 text-green-700 inline-flex items-center justify-center mb-2">
            <?php echo htmlspecialchars($article['category_name']); ?>
          </a>
        </div>

        <img src="<?php echo htmlspecialchars($article['image']); ?>" class="w-full object-cover lg:rounded" style="height: 28em;" alt="Article Image"/>
      </div>

      <div class="flex flex-col lg:flex-row lg:space-x-12">

        <div class="px-4 lg:px-0 mt-12 text-gray-700 text-lg leading-relaxed w-full lg:w-3/4">
          <p class="pb-6"><?php echo nl2br(htmlspecialchars($article['description'])); ?></p>
        </div>

        <div class="w-full lg:w-1/4 m-auto mt-12 max-w-screen-sm">
          <div class="p-4 border-t border-b md:border md:rounded">
            <div class="flex py-2">
              <img src="https://randomuser.me/api/portraits/men/97.jpg" class="h-10 w-10 rounded-full mr-2 object-cover" />
              <div>
                <p class="font-semibold text-gray-700 text-sm">Author</p>
                <p class="font-semibold text-gray-600 text-xs"><?php echo htmlspecialchars($article['author_name']); ?></p>
              </div>
            </div>
            <p class="text-gray-700 py-3">
              Biography of the Author goes here. It could be a small description about the author or their history.
            </p>
          </div>
        </div>

      </div>
    </main>
    <!-- Main ends here -->

    <!-- Footer -->
    <footer class="border-t mt-12 pt-12 pb-32 px-4 lg:px-0">
      <div>
        <img src="https://images.unsplash.com/photo-1519389950473-47ba0277781c?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2100&q=80" class="h-12 w-12" alt="logo">
      </div>
      <div class="flex flex-wrap">
        <div class="w-full lg:w-2/5">
          <p class="text-gray-600 hidden lg:block mt-4 p-0 lg:pr-12">
            Boisterous he on understood attachment as entreaties ye devonshire. 
            In mile an form snug were been sell.
            Extremely ham any his departure for contained curiosity defective. 
            Way now instrument had eat diminution melancholy expression sentiments stimulated. 
          </p>
        </div>

        <div class="w-full mt-6 lg:mt-0 md:w-1/2 lg:w-1/5">
          <h6 class="font-semibold text-gray-700 mb-4">Company</h6>
          <ul>
            <li> <a href="" class="block text-gray-600 py-2">Team</a> </li>
            <li> <a href="" class="block text-gray-600 py-2">About us</a> </li>
            <li> <a href="" class="block text-gray-600 py-2">Press</a> </li>
          </ul>
        </div>

        <div class="w-full mt-6 lg:mt-0 md:w-1/2 lg:w-1/5">
          <h6 class="font-semibold text-gray-700 mb-4">Content</h6>
          <ul>
            <li> <a href="" class="block text-gray-600 py-2">Blog</a> </li>
            <li> <a href="" class="block text-gray-600 py-2">Privacy Policy</a> </li>
            <li> <a href="" class="block text-gray-600 py-2">Terms & Conditions</a> </li>
            <li> <a href="" class="block text-gray-600 py-2">Documentation</a> </li>
          </ul>
        </div>
      </div>
    </footer>
</div>

</body>
</html>
