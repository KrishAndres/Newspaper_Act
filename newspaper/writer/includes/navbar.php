<nav class="navbar navbar-expand-lg navbar-dark p-3" style="background: linear-gradient(135deg, #4A90E2 0%, #1ABC9C 100%);">
  <a class="navbar-brand d-flex align-items-center" href="index.php">
    <span>Writer Dashboard</span>
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" 
          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto align-items-lg-center">
      <li class="nav-item">
        <a class="nav-link d-flex align-items-center" href="index.php">
          <span>Home</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link d-flex align-items-center font-weight-bold" href="articles_submitted.php">
          <span>My Articles</span>
          <?php
          $user_articles = $articleObj->getArticlesByUserID($_SESSION['user_id']);
          $article_count = count($user_articles);
          if ($article_count > 0) {
              echo '<span class="badge badge-light badge-pill ml-2">' . $article_count . '</span>';
          }
          ?>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link d-flex align-items-center" href="shared_articles.php">
          <span>Shared Articles</span>
          <?php
          $shared_articles = $articleObj->getSharedArticlesForUser($_SESSION['user_id']);
          $shared_count = count($shared_articles);
          if ($shared_count > 0) {
              echo '<span class="badge badge-warning badge-pill ml-2">' . $shared_count . '</span>';
          }
          ?>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link d-flex align-items-center" href="notifications.php">
          <span>Notifications</span>
          <?php
          $unread_notifications = $articleObj->getNotificationsByUserID($_SESSION['user_id']);
          $unread_count = 0;
          foreach ($unread_notifications as $notification) {
              if ($notification['is_read'] == 0) $unread_count++;
          }
          if ($unread_count > 0) {
              echo '<span class="badge badge-danger badge-pill ml-2">' . $unread_count . '</span>';
          }
          ?>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" 
           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-user-circle mr-2"></i>
          <span><?php echo $_SESSION['username']; ?></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
          <a class="dropdown-item d-flex align-items-center" href="core/handleForms.php?logoutUserBtn=1">
            <i class="fas fa-sign-out-alt mr-2"></i>Logout
          </a>
        </div>
      </li>
    </ul>
  </div>
</nav>

<style>
.navbar {
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  font-family: 'Nunito', sans-serif;
}

.navbar-brand {
  font-weight: 800;
  font-size: 1.5rem;
}

.nav-link {
  font-weight: 600;
  padding: 0.5rem 1rem !important;
  margin: 0 5px;
  border-radius: 20px;
  transition: all 0.3s ease;
  color: white !important;
}

.nav-link:hover {
  background-color: rgba(255, 255, 255, 0.25);
  transform: translateY(-2px);
}

.font-weight-bold {
  font-weight: 800 !important;
}

.badge-pill {
  font-size: 0.7rem;
  padding: 0.25rem 0.5rem;
}

.dropdown-menu {
  border: none;
  border-radius: 15px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
  overflow: hidden;
}

.dropdown-item {
  padding: 0.75rem 1.5rem;
  transition: all 0.3s ease;
  color: var(--dark);
}

.dropdown-item:hover {
  background: linear-gradient(135deg, #4A90E2 0%, #1ABC9C 100%);
  color: white;
}

.navbar-toggler {
  border: none;
}

.navbar-toggler:focus {
  outline: none;
}

@media (max-width: 992px) {
  .navbar-nav {
    padding: 15px 0;
  }
  
  .nav-link {
    margin: 5px 0;
  }
  
  .dropdown-menu {
    margin-top: 10px;
  }
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manual dropdown toggle for better compatibility
    const dropdownToggle = document.getElementById('navbarDropdown');
    const dropdownMenu = dropdownToggle.nextElementSibling;
    
    if (dropdownToggle && dropdownMenu) {
        dropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            dropdownMenu.classList.toggle('show');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove('show');
            }
        });
    }
});
</script>