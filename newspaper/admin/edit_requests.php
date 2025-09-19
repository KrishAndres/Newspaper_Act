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
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Edit Requests - School News</title>

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
    
    .requests-container {
      max-width: 900px;
      margin: 0 auto;
      padding: 20px;
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
    
    .request-card {
      background-color: white;
      border-radius: 15px;
      overflow: hidden;
      transition: all 0.3s ease;
      margin-bottom: 25px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
      border-left: 5px solid var(--secondary);
    }
    
    .request-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
    }
    
    .card-body {
      padding: 25px;
    }
    
    .request-title {
      color: var(--dark);
      font-weight: 700;
      margin-bottom: 15px;
      display: flex;
      align-items: center;
    }
    
    .request-title i {
      color: var(--secondary);
      margin-right: 10px;
      font-size: 1.5rem;
    }
    
    .request-meta {
      color: #6c757d;
      font-size: 0.9rem;
      margin-bottom: 15px;
    }
    
    .request-author {
      color: var(--primary);
      font-weight: 700;
    }
    
    .request-date {
      color: #6c757d;
    }
    
    .request-message {
      background-color: #f8f9fa;
      border-radius: 10px;
      padding: 15px;
      margin: 15px 0;
      border-left: 3px solid var(--primary);
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
    
    .status-accepted {
      background-color: var(--accent);
      color: white;
    }
    
    .status-rejected {
      background-color: #f44336;
      color: white;
    }
    
    .action-buttons {
      display: flex;
      gap: 10px;
      margin-top: 20px;
    }
    
    .btn-success {
      background: linear-gradient(135deg, var(--accent) 0%, #5cb85c 100%);
      border: none;
      border-radius: 12px;
      padding: 8px 20px;
      font-weight: 600;
      transition: all 0.3s ease;
      box-shadow: 0 4px 10px rgba(92, 184, 92, 0.3);
    }
    
    .btn-success:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 15px rgba(92, 184, 92, 0.4);
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
    
    .mascot-corner {
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 100;
    }
    
    .mascot-corner img {
      width: 120px;
      transition: transform 0.3s ease;
    }
    
    .mascot-corner img:hover {
      transform: scale(1.05);
    }
    
    @media (max-width: 768px) {
      .requests-container {
        padding: 15px;
      }
      
      .action-buttons {
        flex-direction: column;
      }
    }
  </style>
</head>

<body>
  <?php include 'includes/navbar.php'; ?>
  
  <div class="requests-container">
    <h1 class="page-header">Edit Requests</h1>
    
    <!-- Message Display -->
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
    
    <?php 
    $edit_requests = $articleObj->getEditRequests(); 
    
    if (empty($edit_requests)) { 
    ?>
        <div class="empty-state">
            <h4>No edit requests found</h4>
        </div>
    <?php } else { ?>
      <div class="row">
        <?php foreach ($edit_requests as $request) { ?>
          <div class="col-md-12">
            <div class="request-card">
              <div class="card-body">
                <h4 class="request-title">
                  <i class="fas fa-edit"></i>
                  Edit Request for: "<?php echo $request['article_title']; ?>"
                </h4>
                
                <div class="request-meta">
                  <span class="request-author"><i class="fas fa-user mr-1"></i><?php echo $request['requester_username']; ?></span>
                  <span class="request-date ml-3"><i class="far fa-clock mr-1"></i><?php echo $request['created_at']; ?></span>
                </div>
                
                <div class="request-message">
                  <strong><i class="fas fa-comment-dots mr-2"></i>Message:</strong>
                  <p class="mb-0 mt-2"><?php echo $request['request_message']; ?></p>
                </div>
                
                <div class="status-badge <?php echo 'status-' . $request['status']; ?>">
                  <i class="fas fa-<?php echo $request['status'] == 'pending' ? 'clock' : ($request['status'] == 'accepted' ? 'check-circle' : 'times-circle'); ?> mr-1"></i>
                  <?php echo ucfirst($request['status']); ?>
                </div>
                
                <?php if ($request['status'] == 'pending') { ?>
                  <div class="action-buttons">
                    <form action="core/handleForms.php" method="POST" class="d-inline">
                      <input type="hidden" name="request_id" value="<?php echo $request['request_id']; ?>">
                      <input type="hidden" name="article_id" value="<?php echo $request['article_id']; ?>">
                      <input type="hidden" name="requester_id" value="<?php echo $request['requester_id']; ?>">
                      <input type="hidden" name="article_title" value="<?php echo $request['article_title']; ?>">
                      <button type="submit" name="acceptEditRequestBtn" class="btn btn-success">
                        <i class="fas fa-check mr-1"></i> Accept
                      </button>
                    </form>
                    <form action="core/handleForms.php" method="POST" class="d-inline">
                      <input type="hidden" name="request_id" value="<?php echo $request['request_id']; ?>">
                      <input type="hidden" name="article_id" value="<?php echo $request['article_id']; ?>">
                      <input type="hidden" name="requester_id" value="<?php echo $request['requester_id']; ?>">
                      <input type="hidden" name="article_title" value="<?php echo $request['article_title']; ?>">
                      <button type="submit" name="rejectEditRequestBtn" class="btn btn-danger">
                        <i class="fas fa-times mr-1"></i> Reject
                      </button>
                    </form>
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
</body>

</html>