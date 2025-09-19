<?php
session_start();
require_once 'classloader.php';

if (!$userObj->isLoggedIn()) {
    header("Location: login.php");
    exit;
}

if ($userObj->isAdmin()) {
    header("Location: ../admin/index.php");
    exit;
}

$shared_articles = $articleObj->getSharedArticlesForUser($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Shared Articles - School News</title>

<!-- Bootstrap & FontAwesome -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

<style>
:root {
    --primary: #1ABC9C;
    --secondary: #F5A623;
    --accent: #1ABC9C;
    --dark: #333;
    --light: #f4f6f9;
}

body {
    font-family: 'Nunito', sans-serif;
    background: var(--light);
    color: var(--dark);
    margin: 0;
}

.navbar {
    background: linear-gradient(135deg, var(--primary), var(--accent));
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.page-header {
    text-align: center;
    margin: 40px 0 10px;
    color: var(--primary);
    font-weight: 800;
}

.page-header::after {
    content: "";
    position: absolute;
    bottom: -8px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 4px;
    background: var(--secondary);
    border-radius: 2px;
}

.shared-container {
    max-width: 900px;
    margin: auto;
    padding: 20px;
}

.shared-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    margin-bottom: 25px;
    border-left: 5px solid var(--accent);
    transition: all 0.3s ease;
}

.shared-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.12);
}

.card-body {
    padding: 25px;
}

.article-title {
    font-weight: 700;
    color: var(--dark);
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.article-title i {
    color: var(--accent);
    margin-right: 10px;
}

.article-meta {
    font-size: 0.9rem;
    color: #6c757d;
    margin-bottom: 15px;
}

.article-author {
    font-weight: 600;
    color: var(--primary);
}

.article-date {
    margin-left: 15px;
}

.article-image {
    width: 100%;
    max-height: 250px;
    object-fit: cover;
    border-radius: 10px;
    margin-bottom: 15px;
}

.shared-badge {
    background: var(--accent);
    color: #fff;
    font-weight: 700;
    font-size: 0.8rem;
    border-radius: 15px;
    padding: 5px 12px;
    display: inline-block;
    margin-bottom: 15px;
}

.edit-form {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    margin-top: 20px;
    border-left: 4px solid var(--primary);
}

.edit-header {
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 15px;
    display: flex;
    align-items: center;
}

.edit-header i {
    margin-right: 10px;
}

.form-control {
    border-radius: 12px;
    padding: 15px;
    border: 2px solid #e1e5eb;
}

.form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 0.2rem rgba(74,144,226,0.25);
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary), var(--accent));
    border: none;
    font-weight: 700;
    border-radius: 12px;
    padding: 12px 25px;
    box-shadow: 0 4px 15px rgba(74,144,226,0.4);
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(74,144,226,0.5);
}

.custom-file-label {
    border-radius: 12px;
    padding: 15px;
    border: 2px solid #e1e5eb;
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.empty-state i {
    font-size: 4rem;
    color: #ddd;
    margin-bottom: 20px;
}

.empty-state h4 {
    color: #888;
    font-weight: 600;
}

@media (max-width: 768px) {
    .shared-container {
        padding: 15px;
    }
}
</style>
</head>

<body>
<?php include 'includes/navbar.php'; ?>

<div class="shared-container">
    <h1 class="page-header">Shared Articles</h1>
    <p class="text-center lead">Articles shared with you for collaboration and editing</p>

    <?php if (empty($shared_articles)) { ?>
        <div class="empty-state">
            <i class=""></i>
            <h4>No articles shared yet</h4>
        </div>
    <?php } else { ?>
        <div class="row">
            <?php foreach ($shared_articles as $article) { ?>
                <div class="col-12">
                    <div class="shared-card">
                        <div class="card-body">
                            <span class="shared-badge">
                                <i class=""></i>Shared for Collaboration
                            </span>

                            <h4 class="article-title">
                                <i class="fas fa-file-alt"></i>
                                <?php echo htmlspecialchars($article['title']); ?>
                            </h4>

                            <?php if (!empty($article['image_path'])) { ?>
                                <img src="../uploads/<?php echo htmlspecialchars($article['image_path']); ?>" class="article-image img-fluid" alt="Article Image">
                            <?php } ?>

                            <div class="article-meta">
                                <span class="article-author"><i class="fas fa-user-edit mr-1"></i>Author: <?php echo htmlspecialchars($article['author_username']); ?></span>
                                <span class="article-date"><i class="far fa-clock mr-1"></i>Shared on: <?php echo htmlspecialchars($article['shared_at']); ?></span>
                            </div>

                            <p><?php echo htmlspecialchars($article['content']); ?></p>

                            <div class="edit-form">
                                <h4 class="edit-header"><i class="fas fa-edit"></i> Edit Article</h4>
                                <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($article['title']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-control" name="description" rows="5"><?php echo htmlspecialchars($article['content']); ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="article_image_<?php echo $article['article_id']; ?>" name="article_image">
                                            <label class="custom-file-label" for="article_image_<?php echo $article['article_id']; ?>">Change article image</label>
                                        </div>
                                        <?php if (!empty($article['image_path'])) { ?>
                                            <small class="form-text text-muted">Current image: <?php echo htmlspecialchars($article['image_path']); ?></small>
                                        <?php } ?>
                                    </div>
                                    <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>">
                                    <button type="submit" class="btn btn-primary float-right" name="editArticleBtn"><i class="fas fa-save mr-2"></i> Save Changes</button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function(){
    $('.custom-file-input').on('change', function(){
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
});
</script>

</body>
</html>
