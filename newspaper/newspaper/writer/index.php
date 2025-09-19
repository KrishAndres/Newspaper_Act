<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'classloader.php';

if (!$userObj->isLoggedIn()) {
    header("Location: login.php");
}

if ($userObj->isAdmin()) {
    header("Location: ../admin/index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Writer Dashboard - School News</title>

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

<style>
:root {
    --primary: #1ABC9C;
    --secondary: #F5A623;
    --accent: #7ED321;
    --light: #F9F9FB;
    --dark: #333333;
}

body {
    font-family: 'Nunito', sans-serif;
    background-color: #f4f6f9;
    color: var(--dark);
}

.navbar {
    background-color: var(--primary);
}

.navbar-brand {
    font-weight: 800;
    font-size: 1.6rem;
}

.dashboard-header {
    text-align: center;
    margin: 40px 0 20px 0;
}

.dashboard-header h1 {
    font-weight: 800;
    color: var(--primary);
    margin-bottom: 10px;
}

.dashboard-header p {
    color: #555;
    font-size: 1.1rem;
}

.stats-panel {
    display: flex;
    justify-content: space-around;
    margin-bottom: 30px;
    flex-wrap: wrap;
}

.stats-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    width: 220px;
    margin: 10px;
    text-align: center;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    transition: transform 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.12);
}

.stats-card i {
    font-size: 2.5rem;
    color: var(--primary);
    margin-bottom: 10px;
}

.stats-card h3 {
    font-weight: 700;
    margin-bottom: 5px;
}

.stats-card p {
    font-weight: 500;
    color: #666;
}

.create-article-card {
    background: white;
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 40px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

.create-article-card h3 {
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 20px;
}

.form-control, .custom-file-label {
    border-radius: 12px;
}

.btn-primary {
    background: var(--primary);
    border: none;
    border-radius: 12px;
    padding: 12px 25px;
    font-weight: 700;
}

.btn-primary:hover {
    background: #357abd;
}

.article-list .article-card {
    background: white;
    border-radius: 15px;
    margin-bottom: 25px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    transition: transform 0.3s ease;
}

.article-list .article-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.12);
}

.article-card .card-body {
    padding: 25px;
}

.article-card .article-title {
    font-weight: 700;
    font-size: 1.3rem;
    margin-bottom: 10px;
    color: var(--primary);
}

.article-meta {
    color: #777;
    font-size: 0.9rem;
    margin-bottom: 15px;
}

.article-meta .article-author {
    font-weight: 600;
    color: var(--primary);
}

.article-image {
    width: 100%;  
    height: auto;
    border-radius: 10px;
    margin-bottom: 15px;
    object-fit: cover;
}

.admin-badge {
    background-color: var(--secondary);
    color: white;
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 700;
    display: inline-block;
    margin-bottom: 10px;
}
</style>
</head>

<body>

<?php include 'includes/navbar.php'; ?>

<div class="container">
    <div class="dashboard-header">
        <h1>Writer Dashboard</h1>
        <p>Welcome, <span class="font-weight-bold"><?php echo $_SESSION['username']; ?></span>.</p>
    </div>
    <!-- Create Article -->
    <div class="create-article-card">
        <h3><i class="fas fa-plus-circle mr-2"></i>Create New Article</h3>
        <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <input type="text" class="form-control" name="title" placeholder="Article Title" required>
            </div>
            <div class="form-group">
                <textarea class="form-control" name="description" placeholder="Write your article content..." rows="5" required></textarea>
            </div>
            <div class="form-group">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="article_image" name="article_image">
                    <label class="custom-file-label" for="article_image">Choose article image</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary float-right" name="insertArticleBtn"><i class="fas fa-paper-plane mr-1"></i>Publish Article</button>
            <div class="clearfix"></div>
        </form>
    </div>

        <!-- Articles List -->
    <h3 class="card-title mt-5"><i class="fas fa-newspaper"></i> All Articles</h3>
    
    <?php $articles = $articleObj->getActiveArticles(); ?>
    <?php if (empty($articles)) { ?>
      <div class="empty-state">
        <i class="fas fa-newspaper"></i>
        <h4>No articles yet</h4>
        <p>Be the first to share a story!</p>
      </div>
    <?php } else { ?>
      <div class="row">
        <?php foreach ($articles as $article) { ?>
          <div class="col-md-12">
            <div class="article-card <?php echo ($article['is_admin'] == 1) ? 'admin-article' : 'writer-article'; ?>">
              <div class="card-body">
                <?php if ($article['is_admin'] == 1) { ?>
                  <span class="admin-badge"><i class="fas fa-star mr-1"></i>Message From Admin</span>
                <?php } ?>
                
                <h4 class="article-title"><?php echo $article['title']; ?></h4>
                
                <?php if ($article['image_path']) { ?>
                  <img src="../uploads/<?php echo $article['image_path']; ?>" class="article-image img-fluid" alt="Article Image">
                <?php } ?>
                
                <div class="article-meta">
                  <span class="article-author"><i class="fas fa-user mr-1"></i><?php echo $article['username']; ?></span>
                  <span class="article-date ml-3"><i class="far fa-clock mr-1"></i><?php echo $article['created_at']; ?></span>
                </div>
                
                <p class="card-text"><?php echo $article['content']; ?></p>
                
                <?php if ($article['author_id'] != $_SESSION['user_id']) { ?>
                  <button type="button" class="btn btn-info float-right request-edit-btn" data-toggle="modal" data-target="#requestEditModal" data-article-id="<?php echo $article['article_id']; ?>" data-article-title="<?php echo $article['title']; ?>">
                    <i class="fas fa-edit mr-1"></i> Request Edit
                  </button>
                <?php } ?>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    <?php } ?>
  </div>

  <!-- Request Edit Modal -->
  <div class="modal fade" id="requestEditModal" tabindex="-1" aria-labelledby="requestEditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="requestEditModalLabel">Request Edit for Article: <span id="modalArticleTitle"></span></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="core/handleForms.php" method="POST">
          <div class="modal-body">
            <input type="hidden" name="article_id" id="modalArticleId">
            <input type="hidden" name="article_title" id="modalArticleTitleHidden">
            <div class="form-group">
              <label for="request_message"><i class="fas fa-comment-dots mr-1"></i>Your Request Message:</label>
              <textarea class="form-control" name="request_message" id="request_message" rows="4" placeholder="Explain what changes you'd like to suggest..." required></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" name="requestEditBtn" class="btn btn-primary">
              <i class="fas fa-paper-plane mr-1"></i> Submit Request
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>



<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
</script>
</body>
</html>
