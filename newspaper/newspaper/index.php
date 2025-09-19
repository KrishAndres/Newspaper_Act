<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'writer/classloader.php';
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #1ABC9C;   /* Teal */
            --secondary: #F5A623; /* Yellow highlight */
            --light: #F9F9FB;     /* Background */
            --dark: #333333;      /* Text */
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light);
            color: var(--dark);
            margin-top: 70px;
        }

        /* Navbar */
        .navbar {
            backdrop-filter: blur(10px);
            background-color: var(--primary);
            color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            border-radius: 0 0 20px 20px;
        }

        .navbar-brand, .nav-link {
            color: #fff !important;
            font-weight: 600;
        }

        .nav-link:hover {
            color: var(--secondary) !important;
        }

        .badge-notification {
            background-color: var(--secondary);
            color: white;
            border-radius: 50%;
            padding: 0.25rem 0.5rem;
            font-size: 0.7rem;
            margin-left: 5px;
        }

        /* Hero Section */
        .hero-section {
            position: relative;
            height: 90vh;
            background: url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80') center/cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-card {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 50px 40px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .hero-card h1 {
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 20px;
            font-size: 2.2rem;
        }

        .hero-card p {
            font-size: 1.1rem;
            margin-bottom: 30px;
        }

        .hero-card .btn {
            background-color: var(--primary);
            color: #fff;
            border: none;
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .hero-card .btn:hover {
            background-color: #16a085;
            transform: translateY(-2px);
        }

        /* Roles Section */
        .roles-section {
            padding: 80px 0;
            background-color: var(--light);
        }

        .role-card {
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            padding: 30px 20px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .role-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.12);
        }

        .role-icon {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 15px;
        }

        .role-card h3 {
            font-weight: 700;
            margin-bottom: 15px;
        }

        .role-card p {
            font-size: 0.95rem;
        }

        .role-card .btn {
            background-color: var(--primary);
            color: #fff;
        }

        .role-card .btn:hover {
            background-color: #16a085;
        }

        /* Footer */
        /* Footer */
        footer {
            background-color: var(--dark);
            color: white;
            padding: 20px 0; /* Reduced from 40px to 20px */
            margin-top: auto; /* Keeps footer at bottom */
        }

        footer a {
            color: var(--secondary);
            transition: all 0.3s ease;
        }

        footer a:hover {
            color: var(--primary);
        }

        /* Make page take full height */
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1; /* Ensures footer stays at bottom if content is short */
        }

        /* Section Titles */
        .section-title {
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 50px;
            text-align: center;
        }

        .section-title::after {
            content: '';
            display: block;
            width: 60px;
            height: -8px;
            margin: 10px auto 0;
            border-radius: 2px;
        }

        @media (max-width: 768px) {
            .hero-card {
                padding: 40px 20px;
            }

            .roles-section {
                padding: 60px 0;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <a class="navbar-brand" href="#"><i class="fas fa-newspaper"></i> School News</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <?php if ($userObj->isLoggedIn()) { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo ($userObj->isAdmin()) ? 'admin/index.php' : 'writer/index.php'; ?>">
                            <i class="fas fa-tachometer-alt mr-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo ($userObj->isAdmin()) ? 'admin/notifications.php' : 'writer/notifications.php'; ?>">
                            <i class="fas fa-bell mr-1"></i>Notifications
                            <?php
                            $unread_notifications = $articleObj->getNotificationsByUserID($_SESSION['user_id']);
                            $unread_count = 0;
                            foreach ($unread_notifications as $notification) {
                                if ($notification['is_read'] == 0) $unread_count++;
                            }
                            if ($unread_count > 0) echo '<span class="badge badge-notification">' . $unread_count . '</span>';
                            ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo ($userObj->isAdmin()) ? 'admin/core/handleForms.php?logoutUserBtn=1' : 'writer/core/handleForms.php?logoutUserBtn=1'; ?>">
                            <i class="fas fa-sign-out-alt mr-1"></i>Logout
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </nav>

    <!-- Roles Section -->
    <section class="roles-section container">
        <h2 class="section-title">Welcome!</h2>
        <p class="lead"> Stay updated with the latest events, achievements, and stories from our school community. From academic highlights to extracurricular activities, we bring you all the news that matters—straight from campus to you.</p>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="role-card">
                    <div class="role-icon"><i class="fas fa-pen"></i></div>
                    <h3>Writer</h3>
                    <p>Write and share stories that inspire you.</p>
                    <?php if (!$userObj->isLoggedIn()) { ?>
                        <a href="writer/login.php" class="btn btn-primary mt-3">Become a Writer</a>
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="role-card">
                    <div class="role-icon"><i class="fas fa-user"></i></div>
                    <h3>Admin</h3>
                    <p>Manage content and guide your students.</p>
                    <?php if (!$userObj->isLoggedIn()) { ?>
                        <a href="admin/login.php" class="btn btn-primary mt-3">Admin Portal</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>

   
    <!-- Footer --> 
    <footer>
        <div class="container text-center">
            <p>&copy; <?php echo date('Y'); ?> School News.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
