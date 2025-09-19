<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'classloader.php';

if (!$userObj->isLoggedIn()) {
    header("Location: login.php");
    exit();
}

if (!$userObj->isAdmin()) {
    header("Location: ../writer/index.php");
    exit();
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Admin Dashboard - School News</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

  <style>
    :root {
      --primary: #1ABC9C; /* teal main */
      --secondary: #16A085; /* darker teal */
      --light: #F9F9FB;
      --dark: #333333;
    }

    body {
      font-family: "Nunito", sans-serif;
      background-color: var(--light);
      color: var(--dark);
    }

    .navbar {
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .page-header {
      text-align: center;
      margin: 30px 0;
      color: var(--primary);
      font-weight: 800;
      position: relative;
    }

    .page-header::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 4px;
      border-radius: 2px;
    }

    .welcome-user {
      color: var(--primary);
      font-weight: 700;
    }

    .dashboard-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }

    .create-article-card {
      background-color: white;
      border-radius: 15px;
      padding: 25px;
      margin-bottom: 30px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      border-left: 5px solid var(--primary);
    }

    .card-title {
      color: var(--primary);
      font-weight: 700;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
    }

    .card-title i {
      margin-right: 10px;
      font-size: 1.5rem;
    }

    .form-control {
      border-radius: 12px;
      padding: 15px;
      border: 2px solid #e1e5eb;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 0.2rem rgba(26, 188, 156, 0.25);
    }

    .btn-primary {
      background: var(--primary);
      border: none;
      border-radius: 12px;
      padding: 12px 25px;
      font-weight: 700;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(26, 188, 156, 0.4);
    }

    .btn-primary:hover {
      background: var(--secondary);
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(22, 160, 133, 0.5);
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

    .article-card.admin-article {
      border-left: 5px solid var(--secondary);
    }

    .article-card.writer-article {
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

    .admin-badge {
      background-color: var(--secondary);
      color: white;
      padding: 5px 12px;
      border-radius: 15px;
      font-size: 0.8rem;
      font-weight: 700;
      display: inline-block;
      margin-bottom: 15px;
    }

    .writer-badge {
      background-color: var(--primary);
      color: white;
      padding: 5px 12px;
      border-radius: 15px;
      font-size: 0.8rem;
      font-weight: 700;
      display: inline-block;
      margin-bottom: 15px;
    }

    .article-image {
      border-radius: 10px;
      margin-bottom: 15px;
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

    .custom-file-label {
      border-radius: 12px;
      padding: 15px;
      border: 2px solid #e1e5eb;
    }

    .custom-file-input:focus~.custom-file-label {
      border-color: var(--primary);
      box-shadow: 0 0 0 0.2rem rgba(26, 188, 156, 0.25);
    }

    @media (max-width: 768px) {
      .dashboard-container {
        padding: 15px;
      }

      .create-article-card {
        padding: 20px;
      }
    }
  </style>
</head>

<body>
  <?php include 'includes/navbar.php'; ?>

  <div class="dashboard-container">
    <h1 class="page-header">Admin Dashboard</h1>

    <!-- Create Article Form -->
    <div class="create-article-card">
      <h3 class="card-title"><i class="fas fa-plus-circle"></i> Create New Article</h3>
      <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
          <input type="text" class="form-control" name="title" placeholder="Article Title" required>
        </div>
        <div class="form-group">
          <textarea name="description" class="form-control" placeholder="Write your article content here..." rows="5" required></textarea>
        </div>
        <div class="form-group">
          <div class="custom-file">
            <input type="file" class="custom-file-input" id="article_image" name="article_image">
            <label class="custom-file-label" for="article_image">Choose article image</label>
          </div>
        </div>
        <button type="submit" class="btn btn-primary float-right" name="insertArticleBtn">
          <i class="fas fa-paper-plane mr-2"></i> Submit Article
        </button>
        <div class="clearfix"></div>
      </form>
    </div>

   

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    $(document).ready(function() {
      $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
      });
    });
  </script>
</body>

</html>
