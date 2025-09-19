<?php
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
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N"
        crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Quicksand:wght@400;600&display=swap"
        rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

    <style>
        :root {
            --primary: #1ABC9C;
            --secondary: #E74C3C;
            --accent: #3498DB;
            --light: #F4F6F7;
            --dark: #2C3E50;
        }

        body {
            font-family: "Nunito", sans-serif;
            background-color: var(--light);
            color: var(--dark);
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary) 0%, #34495E 100%);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }

        .page-header {
            text-align: center;
            margin: 35px 0;
            color: var(--primary);
            font-weight: 900;
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
            max-width: 1100px;
            margin: 0 auto;
            padding: 25px;
        }

        .create-article-card {
            background-color: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            border-left: 5px solid var(--secondary);
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
            box-shadow: 0 0 0 0.2rem rgba(44, 62, 80, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, #1F2D3D 100%);
            border: none;
            border-radius: 12px;
            padding: 12px 25px;
            font-weight: 700;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(44, 62, 80, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(44, 62, 80, 0.5);
        }

        .article-card {
            background-color: white;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            margin-bottom: 30px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
            cursor: pointer;
        }

        .article-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
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
            max-height: 250px;
            object-fit: cover;
            width: 100%;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
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
            box-shadow: 0 0 0 0.2rem rgba(44, 62, 80, 0.25);
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
        <h1 class="page-header">Articles</h1>

        <!-- Articles List -->
        <h3 class="card-title mt-5"><i class="fas fa-newspaper"></i> All Articles</h3>

        <?php $articles = $articleObj->getActiveArticles(); ?>
        <?php if (empty($articles)) { ?>
            <div class="empty-state">
                <i class="fas fa-newspaper"></i>
                <h4>No articles yet</h4>
                <p>When articles are published, they'll appear here.</p>
            </div>
        <?php } else { ?>
            <div class="row">
                <?php foreach ($articles as $article) { ?>
                    <div class="col-md-6">
                        <div class="article-card <?php echo ($article['is_admin'] == 1) ? 'admin-article' : 'writer-article'; ?>">
                            <div class="card-body">
                                <?php if ($article['is_admin'] == 1) { ?>
                                    <span class="admin-badge"><i class="fas fa-star mr-1"></i>Admin Message</span>
                                <?php } else { ?>
                                    <span class="writer-badge"><i class="fas fa-pencil-alt mr-1"></i>Writer Article</span>
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

                                <?php if ($article['is_admin'] != 1) { ?>
                                    <div class="d-flex justify-content-end mt-3">
                                        <button class="btn btn-sm btn-outline-primary mr-2"><i class="fas fa-edit mr-1"></i>Edit</button>
                                        <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash-alt mr-1"></i>Delete</button>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct"
        crossorigin="anonymous"></script>

    <script>
        $(document).ready(function () {
            $('.custom-file-input').on('change', function () {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName);
            });
        });
    </script>
</body>

</html>
