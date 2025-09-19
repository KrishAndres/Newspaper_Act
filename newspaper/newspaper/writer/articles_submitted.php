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
<title>My Articles - School News</title>

<!-- Bootstrap & FontAwesome -->
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
    background: #f4f6f9;
    color: var(--dark);
}

.navbar {
    background-color: var(--primary);
}

.navbar-brand {
    font-weight: 800;
    font-size: 1.6rem;
}

.page-header {
    text-align: center;
    margin: 40px 0 10px;
    color: var(--primary);
    font-weight: 800;
}

.page-header span {
    font-weight: 800;
}

.create-article-card, .update-article-form, .article-card {
    background: #fff;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    margin-bottom: 30px;
    transition: all 0.3s ease;
}

.create-article-card:hover, .article-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.12);
}

.card-title, .edit-header {
    color: var(--primary);
    font-weight: 700;
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.card-title i, .edit-header i {
    margin-right: 10px;
    font-size: 1.5rem;
}

.form-control, .custom-file-label {
    border-radius: 12px;
    padding: 15px;
    border: 2px solid #e1e5eb;
    transition: all 0.3s ease;
}

.form-control:focus, .custom-file-input:focus ~ .custom-file-label {
    border-color: var(--primary);
    box-shadow: 0 0 0 0.2rem rgba(74,144,226,0.25);
}

.btn-primary, .btn-danger {
    border-radius: 12px;
    font-weight: 700;
    padding: 12px 25px;
    transition: all 0.3s ease;
}

.btn-primary {
    background: var(--primary);
    border: none;
}

.btn-primary:hover {
    background: #357abd;
}

.btn-danger {
    background: #ee5a52;
    border: none;
}

.btn-danger:hover {
    background: #ff6b6b;
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
}

.article-image {
    width: 100%;
    height: auto;
    border-radius: 10px;
    margin-bottom: 15px;
    object-fit: cover;
}

.status-badge {
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 700;
    display: inline-block;
    margin-bottom: 15px;
}

.status-pending {
    background-color: var(--secondary);
    color: white;
}

.status-active {
    background-color: var(--accent);
    color: white;
}

.update-article-form {
    border-left: 4px solid var(--primary);
    margin-top: 20px;
    padding-left: 15px;
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
    background-color: white;
    border-radius: 15px;
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

.double-click-hint {
    text-align: center;
    color: #6c757d;
    font-style: italic;
    margin-bottom: 20px;
}

.double-click-hint i {
    color: var(--secondary);
    margin-right: 5px;
}

/* Flex for buttons instead of float */
.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}
</style>
</head>

<body>
<?php include 'includes/navbar.php'; ?>

<div class="container">
    <h1 class="page-header">My Articles</h1>
    <!-- Articles List -->
    <?php $articles = $articleObj->getArticlesByUserID($_SESSION['user_id']); ?>
    <?php if (empty($articles)) { ?>
        <div class="text-center text-muted my-5">
            <i class=""></i>
            <h4>No articles yet</h4>
        </div>
    <?php } else { ?>
        <div class="row">
            <?php foreach ($articles as $article) { ?>
                <div class="col-md-12">
                    <div class="article-card" data-article-id="<?php echo $article['article_id']; ?>">
                        <div class="card-body">
                            <span class="status-badge <?php echo ($article['is_active'] == 1) ? 'status-active' : 'status-pending'; ?>">
                                <?php echo ($article['is_active'] == 1) ? '<i class="fas fa-check-circle mr-1"></i>PUBLISHED' : '<i class="fas fa-clock mr-1"></i>PENDING APPROVAL'; ?>
                            </span>
                            <h4 class="article-title"><?php echo $article['title']; ?></h4>
                            <?php if ($article['image_path']) { ?>
                                <img src="../uploads/<?php echo $article['image_path']; ?>" class="article-image img-fluid" alt="Article Image">
                            <?php } ?>
                            <div class="article-meta">
                                <span class="article-author"><i class="fas fa-user mr-1"></i><?php echo $article['username']; ?></span>
                                <span class="ml-3"><i class="far fa-clock mr-1"></i><?php echo $article['created_at']; ?></span>
                            </div>
                            <p><?php echo $article['content']; ?></p>

                            <div class="form-actions">
                                <form class="deleteArticleForm">
                                    <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>">
                                    <button class="btn btn-danger" type="submit"><i class="fas fa-trash-alt mr-1"></i> Delete</button>
                                </form>
                            </div>

                            <div class="update-article-form d-none">
                                <h4 class="edit-header"><i class="fas fa-edit"></i> Edit Article</h4>
                                <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="title" value="<?php echo $article['title']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-control" name="description" rows="5"><?php echo $article['content']; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="article_image">
                                            <label class="custom-file-label">Change article image</label>
                                        </div>
                                    </div>
                                    <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>">
                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-primary" name="editArticleBtn"><i class="fas fa-save mr-1"></i> Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });

    $('.article-card').on('dblclick', function() {
        $(this).find('.update-article-form').toggleClass('d-none');
        if (!$(this).find('.update-article-form').hasClass('d-none')) {
            $('html, body').animate({scrollTop: $(this).find('.update-article-form').offset().top - 100}, 500);
        }
    });

    $('.deleteArticleForm').on('submit', function(e) {
        e.preventDefault();
        if (confirm("Are you sure? This action cannot be undone.")) {
            $.post("core/handleForms.php", {article_id: $(this).find('input[name="article_id"]').val(), deleteArticleBtn: 1}, function(data){
                location.reload();
            });
        }
    });
});
</script>
</body>
</html>
