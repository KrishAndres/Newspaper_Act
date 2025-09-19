<?php
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}
require_once '../classloader.php';

if (isset($_POST['insertNewUserBtn'])) {
	$username = htmlspecialchars(trim($_POST['username']));
	$email = htmlspecialchars(trim($_POST['email']));
	$password = trim($_POST['password']);
	$confirm_password = trim($_POST['confirm_password']);

	if (!empty($username) && !empty($email) && !empty($password) && !empty($confirm_password)) {

		if ($password == $confirm_password) {

			if (!$userObj->usernameExists($username)) {

				if ($userObj->registerUser($username, $email, $password)) {
					$_SESSION['message'] = "Registration successful! Please login.";
					$_SESSION['status'] = '200';
					header("Location: ../login.php");
					exit;
				} else {
					$_SESSION['message'] = "An error occurred with the query!";
					$_SESSION['status'] = '400';
					header("Location: ../register.php");
					exit;
				}
			} else {
				$_SESSION['message'] = $username . " as username is already taken";
				$_SESSION['status'] = '400';
				header("Location: ../register.php");
				exit;
			}
		} else {
			$_SESSION['message'] = "Please make sure both passwords are equal";
			$_SESSION['status'] = '400';
			header("Location: ../register.php");
			exit;
		}
	} else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register.php");
		exit;
	}
}

if (isset($_POST['loginUserBtn'])) {
	$email = trim($_POST['email']);
	$password = trim($_POST['password']);

	if (!empty($email) && !empty($password)) {

		if ($userObj->loginUser($email, $password)) {
			$_SESSION['message'] = "Login successful!";
			$_SESSION['status'] = '200';
			header("Location: ../index.php");
			exit;
		} else {
			$_SESSION['message'] = "Email/password invalid";
			$_SESSION['status'] = "400";
			header("Location: ../login.php");
			exit;
		}
	} else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../login.php");
		exit;
	}
}

if (isset($_GET['logoutUserBtn'])) {
	$userObj->logout();
	$_SESSION['message'] = "You have been logged out successfully";
	$_SESSION['status'] = '200';
	header("Location: ../index.php");
	exit;
}

if (isset($_POST['insertArticleBtn'])) {
	$title = htmlspecialchars(trim($_POST['title']));
	$description = htmlspecialchars(trim($_POST['description']));
	$author_id = $_SESSION['user_id'];
	$image_path = null;

	// Handle image upload
	if (isset($_FILES['article_image']) && $_FILES['article_image']['error'] == 0) {
		$target_dir = "../uploads/";
		$image_name = uniqid() . "_" . basename($_FILES["article_image"]["name"]);
		$target_file = $target_dir . $image_name;
		$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

		// Check if image file is a actual image or fake image
		$check = getimagesize($_FILES["article_image"]["tmp_name"]);
		if ($check !== false) {
			// Allow certain file formats
			if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
				$_SESSION['message'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
				$_SESSION['status'] = '400';
				header("Location: ../index.php");
				exit;
			}
			if (move_uploaded_file($_FILES["article_image"]["tmp_name"], $target_file)) {
				$image_path = $image_name;
			} else {
				$_SESSION['message'] = "Sorry, there was an error uploading your file.";
				$_SESSION['status'] = '400';
				header("Location: ../index.php");
				exit;
			}
		} else {
			$_SESSION['message'] = "File is not an image.";
			$_SESSION['status'] = '400';
			header("Location: ../index.php");
			exit;
		}
	}

	if (!empty($title) && !empty($description)) {
		if ($articleObj->createArticle($title, $description, $author_id, $image_path)) {
			header("Location: ../index.php");
			exit;
		} else {
			echo "Failed to insert article into database.";
			exit;
		}
	} else {
		echo "Title or description is empty.";
		exit;
	}
}

if (isset($_POST['editArticleBtn'])) {
	$title = htmlspecialchars(trim($_POST['title']));
	$description = htmlspecialchars(trim($_POST['description']));
	$article_id = $_POST['article_id'];
	$image_path = null;

	// Handle image upload for edit
	if (isset($_FILES['article_image']) && $_FILES['article_image']['error'] == 0) {
		$target_dir = "../uploads/";
		$image_name = uniqid() . "_" . basename($_FILES["article_image"]["name"]);
		$target_file = $target_dir . $image_name;
		$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

		$check = getimagesize($_FILES["article_image"]["tmp_name"]);
		if ($check !== false) {
			if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
				$_SESSION['message'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
				$_SESSION['status'] = '400';
				header("Location: ../articles_submitted.php");
				exit;
			}
			if (move_uploaded_file($_FILES["article_image"]["tmp_name"], $target_file)) {
				$image_path = $image_name;
			} else {
				$_SESSION['message'] = "Sorry, there was an error uploading your file.";
				$_SESSION['status'] = '400';
				header("Location: ../articles_submitted.php");
				exit;
			}
		} else {
			$_SESSION['message'] = "File is not an image.";
			$_SESSION['status'] = '400';
			header("Location: ../articles_submitted.php");
			exit;
		}
	}

	if (!empty($title) && !empty($description)) {
		$result = $articleObj->updateArticle($article_id, $title, $description, $image_path);
		if ($result > 0) {
			$_SESSION['message'] = "Article updated successfully!";
			$_SESSION['status'] = '200';
			header("Location: ../articles_submitted.php");
			exit;
		} else {
			$_SESSION['message'] = "Failed to update article. Please try again.";
			$_SESSION['status'] = '400';
			header("Location: ../articles_submitted.php");
			exit;
		}
	} else {
		$_SESSION['message'] = "Please fill in both title and content fields";
		$_SESSION['status'] = '400';
		header("Location: ../articles_submitted.php");
		exit;
	}
}

if (isset($_POST['deleteArticleBtn'])) {
	$article_id = $_POST['article_id'];
	$result = $articleObj->deleteArticle($article_id);

	if ($result > 0) {
		echo "1"; // Success
	} else {
		echo "0"; // Failure
	}
	exit;
}

if (isset($_POST['requestEditBtn'])) {
    // DEBUG: Log what's being submitted
    error_log("REQUEST EDIT FORM DATA: " . print_r($_POST, true));
    
    // Validate article_id
    if (!isset($_POST['article_id']) || empty($_POST['article_id']) || !is_numeric($_POST['article_id'])) {
        $_SESSION['message'] = "Invalid article selection.";
        $_SESSION['status'] = '400';
        header("Location: ../index.php");
        exit;
    }
    
    $article_id = (int)$_POST['article_id'];
    $request_message = htmlspecialchars(trim($_POST['request_message']));
    $requester_id = $_SESSION['user_id'];
    $article_title = $_POST['article_title'];

    // Additional validation: check if article exists
    $article = $articleObj->getArticles($article_id);
    if (!$article) {
        $_SESSION['message'] = "Article not found.";
        $_SESSION['status'] = '400';
        header("Location: ../index.php");
        exit;
    }

    if (!empty($request_message)) {
        // DEBUG: Before calling requestEdit
        error_log("Calling requestEdit with: article_id=$article_id, requester_id=$requester_id");
        
        $request_id = $articleObj->requestEdit($article_id, $requester_id, $request_message);
		if ($request_id !== false) {
            
            // Notify the author of the article about the edit request
            $message = "A writer has requested an edit for your article '" . $article['title'] . "'.";
            $articleObj->createNotification($article['author_id'], $message, 'edit_request', $article_id);
            
            $_SESSION['message'] = "Edit request submitted successfully! Waiting for author's approval.";
            $_SESSION['status'] = '200';
            header("Location: ../index.php");
            exit;
        } else {
            // DEBUG: After failed requestEdit
            error_log("requestEdit returned FALSE");
            
            $_SESSION['message'] = "Failed to submit edit request. Please try again.";
            $_SESSION['status'] = '400';
            header("Location: ../index.php");
            exit;
        }
    } else {
        $_SESSION['message'] = "Please provide a message for the edit request.";
        $_SESSION['status'] = '400';
        header("Location: ../index.php");
        exit;
    }
}
// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    // This is an AJAX request, don't redirect, just output response
    exit;
}