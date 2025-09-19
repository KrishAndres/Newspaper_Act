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
  <title>Admin Login - School News</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

  <style>
    :root {
      --primary: #1ABC9C;
      --secondary: #16A085;
      --accent: #7ED321;
      --light: #F9F9FB;
      --dark: #333333;
    }

    body {
      font-family: "Nunito", sans-serif;
      background: linear-gradient(135deg, #1ABC9C 0%, #bbf5e9ff 100%);
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
      max-width: 1000px;
      width: 100%;
      display: flex;
      flex-wrap: wrap;
    }

    .login-left {
      background: var(--primary);
      color: white;
      padding: 40px;
      flex: 1 1 40%;
      display: flex;
      flex-direction: column;
      justify-content: center;
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

    .login-left h2 {
      font-weight: 800;
      font-size: 2rem;
      margin-bottom: 15px;
    }

    .login-left p {
      font-size: 1rem;
      line-height: 1.6;
    }

    .login-right {
      padding: 40px;
      flex: 1 1 60%;
    }

    .school-logo {
      text-align: center;
      margin-bottom: 30px;
    }

    .school-logo i {
      font-size: 3.5rem;
      color: var(--primary);
      background-color: rgba(26, 188, 156, 0.1);
      padding: 20px;
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
      margin-bottom: 25px;
      position: relative;
    }

    .form-group label {
      font-weight: 600;
      margin-bottom: 8px;
      color: #495057;
      display: flex;
      align-items: center;
    }

    .form-group label i {
      margin-right: 10px;
      color: var(--primary);
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
      box-shadow: 0 0 0 0.2rem rgba(26, 188, 156, 0.25);
    }

    .input-icon {
      position: absolute;
      left: 15px;
      top: 42px;
      color: #6c757d;
    }

    .btn-login {
      background: var(--primary);
      color: white;
      border: none;
      padding: 15px 30px;
      border-radius: 12px;
      font-weight: 700;
      font-size: 1.1rem;
      width: 100%;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(26, 188, 156, 0.4);
    }

    .btn-login:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(26, 188, 156, 0.5);
    }

    .alert-box {
      padding: 15px;
      border-radius: 12px;
      margin-bottom: 25px;
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

    .login-links {
      text-align: center;
      margin-top: 20px;
    }

    .login-links a {
      color: var(--primary);
      font-weight: 700;
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

      .welcome-title,
      .welcome-subtitle {
        text-align: center;
      }
    }
  </style>
</head>

<body>
  <div class="login-container">
    <div class="login-right">
      <div class="school-logo">
      </div>

      <h1 class="welcome-title">Admin Login</h1>
      <p class="welcome-subtitle">Sign in to your administrator account</p>

      <?php
      if (isset($_SESSION['message']) && isset($_SESSION['status'])) {
        if ($_SESSION['status'] == "200") {
          echo '<div class="alert-box alert-success">';
          echo '<i class="fas fa-check-circle"></i> ' . $_SESSION['message'];
          echo '</div>';
        } else {
          echo '<div class="alert-box alert-error">';
          echo '<i class="fas fa-exclamation-circle"></i> ' . $_SESSION['message'];
          echo '</div>';
        }
        unset($_SESSION['message']);
        unset($_SESSION['status']);
      }
      ?>

      <form action="core/handleForms.php" method="POST">
        <div class="form-group">
          <label for="email"><i class="fas fa-envelope"></i> Email Address</label>
          <div class="position-relative">
            <i class=""></i>
            <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
          </div>
        </div>

        <div class="form-group">
          <label for="password"><i class="fas fa-lock"></i> Password</label>
          <div class="position-relative">
            <i class=""></i>
            <input type="password" class="form-control" name="password" placeholder="Enter your password" required>
          </div>
        </div>

        <button type="submit" class="btn btn-login" name="loginUserBtn">
          <i class="fas fa-sign-in-alt mr-2"></i> Login
        </button>

        <div class="login-links">
          <p class="mt-3">Don't have an account? <a href="register.php">Register as Admin</a></p>
          <p>Go back <a href="../index.php">Home</a></p>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
