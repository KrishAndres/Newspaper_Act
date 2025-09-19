<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'classloader.php';

if (!$userObj->isLoggedIn()) {
    header("Location: login.php");
}

if (!$userObj->isAdmin()) {
    header("Location: ../writer/index.php");
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Article Management - School News</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

  <style>
    :root {
      --primary: #1ABC9C; /* Teal main color */
      --secondary: #16A085; /* Darker teal accent */
      --accent: #F5A623;
      --light: #F9F9FB;
      --dark: #333333;
    }

    body {
      font-family: "Nunito", sans-serif;
      background-color: var(--light);
      color: var(--dark);
      margin: 0;
      padding-top: 70px;
    }

    /* Navbar */
    .navbar {
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 1000;
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .navbar-brand {
      font-weight: 800;
      font-size: 1.7rem;
      color: #fff;
    }

    .nav-link {
      color: #fff !important;
      font-weight: 600;
    }

    .nav-link:hover {
      color: var(--accent) !important;
    }

    /* Page header */
    .page-header {
      text-align: center;
      font-weight: 800;
      margin: 30px 0;
      color: var(--primary);
      position: relative;
    }

    .page-header::after {
      content: "";
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 4px;
      border-radius: 2px;
    }

    .dashboard-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }

    .filter-tabs {
      display: flex;
      justify-content: center;
      margin-bottom: 30px;
      gap: 10px;
      flex-wrap: wrap;
    }

    .filter-tab {
      background-color: white;
      border: 2px solid #e1e5eb;
      border-radius: 20px;
      padding: 10px 25px;
      font-weight: 600;
      color: #555;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .filter-tab.active,
    .filter-tab:hover {
      background: var(--primary);
      color: white;
      border-color: var(--primary);
    }

    .article-card {
      background-color: white;
      border-radius: 15px;
      overflow: hidden;
      transition: all 0.3s ease;
      margin-bottom: 25px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }

    .article-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
    }

    .article-card.pending {
      border-left: 5px solid var(--secondary);
    }

    .article-card.active {
      border-left: 5px solid var(--primary);
    }

    .card-body {
      padding: 25px;
    }

    .article-title {
      color: var(--dark);
      font-weight: 700;
      margin-bottom: 15px;
    }

    .article-meta {
      color: #6c757d;
      font-size: 0.9rem;
      margin-bottom: 15px;
    }

    .article-author {
      color: var(--primary);
      font-weight: 700;
    }

    .article-date {
      color: #6c757d;
    }

    .article-image {
      border-radius: 10px;
      margin-bottom: 15px;
      max-height: 200px;
      object-fit: cover;
      width: 100%;
    }

    .status-badge {
      padding: 5px 12px;
      border-radius: 15px;
      font-size: 0.8rem;
      font-weight: 700;
      display: inline-block;
      margin-bottom: 15px;
      color: white;
    }

    .status-pending {
      background-color: var(--secondary);
    }

    .status-active {
      background-color: var(--primary);
    }

    .action-buttons {
      display: flex;
      justify-content: space-between;
      margin-top: 20px;
      flex-wrap: wrap;
      gap: 10px;
    }

    .btn-primary {
      background: var(--primary);
      border: none;
      border-radius: 12px;
      padding: 8px 20px;
      font-weight: 600;
      color: #fff;
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      background: var(--secondary);
    }

    .btn-danger {
      background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
      border: none;
      border-radius: 12px;
      padding: 8px 20px;
      font-weight: 600;
      transition: all 0.3s ease;
      box-shadow: 0 4px 10px rgba(244, 67, 54, 0.3);
    }

    .btn-danger:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 15px rgba(244, 67, 54, 0.4);
    }

    .status-select {
      border-radius: 12px;
      padding: 8px 15px;
      border: 2px solid #e1e5eb;
      transition: all 0.3s ease;
      font-weight: 600;
    }

    .status-select:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 0.2rem rgba(26, 188, 156, 0.25);
    }

    .empty-state {
      text-align: center;
      padding: 40px 20px;
      background-color: white;
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
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

    .update-article-form {
      background-color: #f8f9fa;
      border-radius: 12px;
      padding: 20px;
      margin-top: 20px;
      border-left: 4px solid var(--primary);
    }

    .edit-header {
      color: var(--primary);
      font-weight: 700;
      margin-bottom: 15px;
      display: flex;
      align-items: center;
    }

    .edit-header i {
      margin-right: 10px;
    }

    @media (max-width: 768px) {
      .dashboard-container {
        padding: 15px;
      }

      .filter-tabs {
        flex-direction: column;
        align-items: center;
      }

      .action-buttons {
        flex-direction: column;
        gap: 10px;
      }
    }
  </style>
</head>

<body>
  <?php include 'includes/navbar.php'; ?>

  <div class="dashboard-container">
    <h1 class="page-header">Article Management</h1>

    <!-- Filter Tabs -->
    <div class="filter-tabs">
      <div class="filter-tab active" data-filter="all">All Articles</div>
      <div class="filter-tab" data-filter="pending">Pending Approval</div>
      <div class="filter-tab" data-filter="active">Published</div>
    </div>

    <!-- Articles List -->
    <?php $articles = $articleObj->getArticles(); ?>
    <?php if (empty($articles)) { ?>
      <div class="empty-state">
        <i class=""></i>
        <h4>No articles yet</h4>
      </div>
    <?php } else { ?>
      <div class="row">
        <?php foreach ($articles as $article) { ?>
          <div class="col-md-6 article-item" data-status="<?php echo $article['is_active'] == 1 ? 'active' : 'pending'; ?>">
            <div class="article-card <?php echo $article['is_active'] == 1 ? 'active' : 'pending'; ?>" data-article-id="<?php echo $article['article_id']; ?>">
              <div class="card-body">
                <?php if ($article['is_active'] == 0) { ?>
                  <span class="status-badge status-pending"><i class="fas fa-clock mr-1"></i>PENDING APPROVAL</span>
                <?php } else { ?>
                  <span class="status-badge status-active"><i class="fas fa-check-circle mr-1"></i>PUBLISHED</span>
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

                <div class="action-buttons">
                  <form class="updateArticleStatus">
                    <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>">
                    <select name="is_active" class="status-select" article_id="<?php echo $article['article_id']; ?>">
                      <option value="0" <?php echo ($article['is_active'] == 0) ? 'selected' : ''; ?>>Pending</option>
                      <option value="1" <?php echo ($article['is_active'] == 1) ? 'selected' : ''; ?>>Publish</option>
                    </select>
                  </form>

                  <form class="deleteArticleForm">
                    <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>">
                    <button type="submit" class="btn btn-danger deleteArticleBtn">
                      <i class="fas fa-trash-alt mr-1"></i> Delete
                    </button>
                  </form>
                </div>

                <div class="update-article-form d-none">
                  <h4 class="edit-header"><i class="fas fa-edit"></i> Edit Article</h4>
                  <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                      <input type="text" class="form-control" name="title" value="<?php echo $article['title']; ?>">
                    </div>
                    <div class="form-group">
                      <textarea name="description" class="form-control" rows="5"><?php echo $article['content']; ?></textarea>
                    </div>
                    <div class="form-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="edit_article_image_<?php echo $article['article_id']; ?>" name="article_image">
                        <label class="custom-file-label" for="edit_article_image_<?php echo $article['article_id']; ?>">Change article image</label>
                      </div>
                      <?php if ($article['image_path']) { ?>
                        <small class="form-text text-muted">Current image: <?php echo $article['image_path']; ?></small>
                      <?php } ?>
                    </div>
                    <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>">
                    <button type="submit" class="btn btn-primary" name="editArticleBtn">
                      <i class="fas fa-save mr-1"></i> Save Changes
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    <?php } ?>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    $(document).ready(function() {
      // Double click to edit article
      $('.article-card').on('dblclick', function() {
        var updateArticleForm = $(this).find('.update-article-form');
        updateArticleForm.toggleClass('d-none');
        if (!updateArticleForm.hasClass('d-none')) {
          $('html, body').animate({
            scrollTop: updateArticleForm.offset().top - 100
          }, 500);
        }
      });

      // Delete article
      $('.deleteArticleForm').on('submit', function(e) {
        e.preventDefault();
        var formData = {
          article_id: $(this).find('input[name="article_id"]').val(),
          deleteArticleBtn: 1
        };
        if (confirm("Are you sure you want to delete this article?")) {
          $.post("core/handleForms.php", formData, function(data) {
            location.reload();
          });
        }
      });

      // Update status
      $('.status-select').on('change', function() {
        var formData = {
          article_id: $(this).attr('article_id'),
          status: $(this).val(),
          updateArticleVisibility: 1
        };
        $.post("core/handleForms.php", formData, function() {
          location.reload();
        });
      });

      // Filter tabs
      $('.filter-tab').on('click', function() {
        $('.filter-tab').removeClass('active');
        $(this).addClass('active');
        var filter = $(this).data('filter');
        if (filter === 'all') $('.article-item').show();
        else $('.article-item').hide().filter('[data-status="' + filter + '"]').show();
      });
    });
  </script>

  
</body>

</html>
