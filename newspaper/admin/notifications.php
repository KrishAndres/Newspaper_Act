<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
require_once 'classloader.php';

if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
}
?>
<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <style>
    :root {
      --primary: #1ABC9C;
      --secondary: #F5A623;
      --accent: #7ED321;
      --light: #F9F9FB;
      --dark: #333333;
    }
    
    body {
      font-family: "Nunito", sans-serif;
      background-color: #f5f7fa;
      color: var(--dark);
    }
    
    .navbar {
      background: linear-gradient(135deg, var(--primary) 0%, #355E3B 100%);
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
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
    
    .notification-container {
      max-width: 800px;
      margin: 0 auto;
    }
    
    .notification-card {
      border: none;
      border-radius: 15px;
      overflow: hidden;
      transition: all 0.3s ease;
      margin-bottom: 20px;
      box-shadow: 0 5px 15px rgba(0, 0, 0,  ͢0.08);
      background-color: white;
    }
    
    .notification-card.unread {
      border-left: 5px solid var(--accent);
      background-color: #e9f7ef;
    }
    
    .notification-card.read {
      border-left: 5px solid #ddd;
      opacity: 0.9;
    }
    
    .notification-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }
    
    .card-body {
      padding: 20px;
    }
    
    .notification-icon {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 15px;
      flex-shrink: 0;
    }
    
    .icon-deletion {
      background-color: rgba(244, 67, 54, 0.2);
      color: #f44336;
    }
    
    .icon-edit {
      background-color: rgba(33, 150, 243, 0.2);
      color: #2196f3;
    }
    
    .icon-accepted {
      background-color: rgba(76, 175, 80, 0.2);
      color: #4caf50;
    }
    
    .icon-rejected {
      background-color: rgba(244, 67, 54, 0.2);
      color: #f44336;
    }
    
    .icon-general {
      background-color: rgba(156, 39, 176, 0.2);
      color: #9c27b0;
    }
    
    .notification-title {
      font-weight: 700;
      margin-bottom: 5px;
      color: var(--dark);
    }
    
    .notification-message {
      color: #555;
      margin-bottom: 10px;
    }
    
    .notification-time {
      color: #888;
      font-size: 0.85rem;
    }
    
    .mark-as-read-btn {
      background: linear-gradient(135deg, var(--primary) 0%, #2575fc 100%);
      color: white;
      border: none;
      border-radius: 20px;
      padding: 8px 20px;
      font-weight: 600;
      transition: all 0.3s ease;
      box-shadow: 0 4px 10px rgba(74, 144, 226, 0.3);
    }
    
    .mark-as-read-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 15px rgba(74, 144, 226, 0.4);
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
    
    .notification-badge {
      background-color: var(--secondary);
      color: white;
      border-radius: 50%;
      padding: 3px 8px;
      font-size: 0.8rem;
      margin-left: 5px;
    }
    
    .filter-buttons {
      display: flex;
      justify-content: center;
      margin-bottom: 30px;
      gap: 10px;
    }
    
    .filter-btn {
      background-color: white;
      border: 2px solid #e1e5eb;
      border-radius: 20px;
      padding: 8px 20px;
      font-weight: 600;
      color: #555;
      transition: all 0.3s ease;
    }
    
    .filter-btn.active, .filter-btn:hover {
      background: linear-gradient(135deg, var(--primary) 0%, #2575fc 100%);
      color: white;
      border-color: var(--primary);
    }
  </style>
  <title>Notifications - School News</title>
</head>

<body>
  <?php include 'includes/navbar.php'; ?>
  
  <div class="container-fluid py-4">
    <h1 class="page-header">Your Notifications</h1>
    
    <div class="filter-buttons">
      <button class="filter-btn active" data-filter="all">All Notifications</button>
      <button class="filter-btn" data-filter="unread">Unread</button>
      <button class="filter-btn" data-filter="read">Read</button>
    </div>
    
    <div class="notification-container">
      <?php $notifications = $articleObj->getNotificationsByUserID($_SESSION['user_id']); ?>
      <?php if (empty($notifications)) { ?>
        <div class="empty-state">
          <h4>No notifications yet</h4>
        </div>
      <?php } else { ?>
        <?php foreach ($notifications as $notification) { 
          $icon_class = "icon-general";
          if ($notification['type'] == 'deletion') {
            $icon_class = "icon-deletion";
          } elseif ($notification['type'] == 'edit_request') {
            $icon_class = "icon-edit";
          } elseif ($notification['type'] == 'edit_request_accepted') {
            $icon_class = "icon-accepted";
          } elseif ($notification['type'] == 'edit_request_rejected') {
            $icon_class = "icon-rejected";
          }
        ?>
          <div class="notification-card <?php echo ($notification['is_read'] == 0) ? 'unread' : 'read'; ?>" data-notification-id="<?php echo $notification['notification_id']; ?>" data-read-status="<?php echo $notification['is_read']; ?>">
            <div class="card-body">
              <div class="d-flex align-items-start">
                <div class="notification-icon <?php echo $icon_class; ?>">
                  <?php
                  if ($notification['type'] == 'deletion') {
                    echo '<i class="fas fa-trash-alt"></i>';
                  } elseif ($notification['type'] == 'edit_request') {
                    echo '<i class="fas fa-edit"></i>';
                  } elseif ($notification['type'] == 'edit_request_accepted') {
                    echo '<i class="fas fa-check-circle"></i>';
                  } elseif ($notification['type'] == 'edit_request_rejected') {
                    echo '<i class="fas fa-times-circle"></i>';
                  } else {
                    echo '<i class="fas fa-bell"></i>';
                  }
                  ?>
                </div>
                <div class="flex-grow-1">
                  <h5 class="notification-title">
                    <?php
                    if ($notification['type'] == 'deletion') {
                      echo 'Article Deleted';
                    } elseif ($notification['type'] == 'edit_request') {
                      echo 'Edit Request';
                    } elseif ($notification['type'] == 'edit_request_accepted') {
                      echo 'Edit Request Accepted';
                    } elseif ($notification['type'] == 'edit_request_rejected') {
                      echo 'Edit Request Rejected';
                    } else {
                      echo 'Notification';
                    }
                    ?>
                    <?php if ($notification['is_read'] == 0) { ?>
                      <span class="notification-badge">New</span>
                    <?php } ?>
                  </h5>
                  <p class="notification-message"><?php echo $notification['message']; ?></p>
                  <small class="notification-time"><i class="far fa-clock mr-1"></i><?php echo $notification['created_at']; ?></small>
                </div>
                <?php if ($notification['is_read'] == 0) { ?>
                  <button class="btn mark-as-read-btn">Mark as Read</button>
                <?php } ?>
              </div>
            </div>
          </div>
        <?php } ?>
      <?php } ?>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      $('.mark-as-read-btn').on('click', function() {
        var notificationCard = $(this).closest('.notification-card');
        var notificationId = notificationCard.data('notification-id');
        var button = $(this);

        $.ajax({
          type: "POST",
          url: "core/handleForms.php",
          data: {
            notification_id: notificationId,
            markNotificationAsRead: 1
          },
          success: function(data) {
            if (data == "1") {
              notificationCard.removeClass('unread').addClass('read');
              notificationCard.attr('data-read-status', '1');
              button.remove();
              notificationCard.find('.notification-badge').remove();
              
              // Update notification count in navbar if exists
              var notificationCount = parseInt($('.notification-count').text()) || 0;
              if (notificationCount > 0) {
                $('.notification-count').text(notificationCount - 1);
                if (notificationCount - 1 === 0) {
                  $('.notification-count').hide();
                }
              }
            } else {
              alert("Failed to mark notification as read.");
            }
          }
        });
      });
      
      // Filter buttons functionality
      $('.filter-btn').on('click', function() {
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        
        var filter = $(this).data('filter');
        
        $('.notification-card').each(function() {
          var readStatus = $(this).data('read-status');
          
          if (filter === 'all') {
            $(this).show();
          } else if (filter === 'unread' && readStatus == 0) {
            $(this).show();
          } else if (filter === 'read' && readStatus == 1) {
            $(this).show();
          } else {
            $(this).hide();
          }
        });
      });
    });
  </script>
</body>

</html>