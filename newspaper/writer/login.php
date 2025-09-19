<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Writer Login - School News</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root {
      --primary: #4A90E2;
      --secondary: #F5A623;
      --accent: #7ED321;
      --light: #F9F9FB;
      --dark: #333333;
    }

    body {
      font-family: "Nunito", sans-serif;
      background: linear-gradient(135deg, #3162e9ff 0%, #ced7e8ff 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .login-container {
      background-color: white;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
      max-width: 900px;
      width: 100%;
      display: flex;
      flex-wrap: wrap;
    }

    .login-left,
    .login-right {
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .login-left {
      background: linear-gradient(135deg, var(--primary) 0%, #2575fc 100%);
      color: white;
      flex: 1 1 40%;
      padding: 40px;
      position: relative;
      overflow: hidden;
    }

    .login-left::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('https://www.transparenttextures.com/patterns/absurdity.png');
      opacity: 0.05;
    }

    .login-right {
      flex: 1 1 60%;
      padding: 50px 40px;
      background-color: white;
    }

    .school-logo {
      text-align: center;
      margin-bottom: 25px;
    }

    .school-logo i {
      font-size: 3rem;
      color: var(--primary);
      background-color: rgba(74, 144, 226, 0.1);
      padding: 18px;
      border-radius: 50%;
    }

    .welcome-title {
      font-weight: 800;
      color: var(--primary);
      margin-bottom: 10px;
      font-size: 2rem;
      text-align: center;
    }

    .welcome-subtitle {
      color: #6c757d;
      margin-bottom: 30px;
      text-align: center;
    }

    .form-group {
      margin-bottom: 20px;
      position: relative;
    }

    .form-control {
      padding: 15px 15px 15px 45px;
      border-radius: 12px;
      border: 2px solid #e1e5eb;
      font-size: 1rem;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
    }

    .input-icon {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #6c757d;
      font-size: 1.1rem;
      pointer-events: none;
    }

    .btn-login {
      background: linear-gradient(135deg, var(--primary) 0%, #2575fc 100%);
      color: white;
      border: none;
      padding: 15px;
      border-radius: 12px;
      font-weight: 700;
      font-size: 1.1rem;
      width: 100%;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(74, 144, 226, 0.4);
      margin-top: 10px;
    }

    .btn-login:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(74, 144, 226, 0.5);
    }

    .alert-box {
      padding: 12px 15px;
      border-radius: 12px;
      margin-bottom: 20px;
      font-weight: 600;
      display: flex;
      align-items: center;
    }

    .alert-success {
      background-color: rgba(126, 211, 33, 0.2);
      color: #4caf50;
      border: 2px solid #4caf50;
    }

    .alert-error {
      background-color: rgba(244, 67, 54, 0.2);
      color: #f44336;
      border: 2px solid #f44336;
    }

    .alert-box i {
      margin-right: 10px;
      font-size: 1.2rem;
    }

    .mascot {
      text-align: center;
      margin-top: 25px;
    }

    .mascot img {
      max-width: 120px;
    }

    .feature-list {
      list-style-type: none;
      padding: 0;
      margin-top: 25px;
    }

    .feature-list li {
      margin-bottom: 15px;
      display: flex;
      align-items: center;
      font-weight: 500;
    }

    .feature-list i {
      background-color: rgba(255, 255, 255, 0.2);
      padding: 10px;
      border-radius: 50%;
      margin-right: 12px;
      font-size: 1.2rem;
      width: 35px;
      height: 35px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-links {
      text-align: center;
      margin-top: 20px;
      font-size: 0.95rem;
    }

    @media (max-width: 768px) {
      .login-container {
        flex-direction: column;
      }

      .login-left,
      .login-right {
        flex: 1 1 100%;
        padding: 30px 20px;
      }

      .mascot img {
        max-width: 100px;
      }
    }
  </style>
</head>

<body>
  <div class="login-container">
    <!-- Right Form Section -->
    <div class="login-right">
      <div class="school-logo">
      </div>

      <h1 class="welcome-title">Writer Login</h1>
      <p class="welcome-subtitle">Sign in to your writer account</p>

      <?php
      if (isset($_SESSION['message']) && isset($_SESSION['status'])) {
        $class = $_SESSION['status'] == "200" ? 'alert-success' : 'alert-error';
        $icon = $_SESSION['status'] == "200" ? 'fa-check-circle' : 'fa-exclamation-circle';
        echo '<div class="alert-box ' . $class . '">';
        echo '<i class="fas ' . $icon . '"></i> ' . $_SESSION['message'];
        echo '</div>';
        unset($_SESSION['message']);
        unset($_SESSION['status']);
      }
      ?>

      <form action="core/handleForms.php" method="POST">
        <div class="form-group position-relative">
          <input type="email" class="form-control" name="email" placeholder="Email address" required>
          <i class="fas fa-envelope input-icon"></i>
        </div>

        <div class="form-group position-relative">
          <input type="password" class="form-control" name="password" placeholder="Password" required>
          <i class="fas fa-lock input-icon"></i>
        </div>

        <button type="submit" class="btn btn-login" name="loginUserBtn">
          <i class="fas fa-sign-in-alt mr-2"></i> Login
        </button>

        <div class="login-links">
          <p>Don't have an account yet? <a href="register.php" class="text-primary font-weight-bold">Register here!</a></p>
          <p>Go back <a href="../index.php" class="text-primary font-weight-bold">Home</a></p>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
