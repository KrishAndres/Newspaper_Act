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
					header("Location: ../login.php");
					exit;
				} else {
					$_SESSION['message'] = "An error occured with the query!";
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
			header("Location: ../index.php");
			exit;
		} else {
			$_SESSION['message'] = "Username/password invalid";
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
				header("Location: ../articles_from_students.php");
				exit;
			}
			if (move_uploaded_file($_FILES["article_image"]["tmp_name"], $target_file)) {
				$image_path = $image_name;
			} else {
				$_SESSION['message'] = "Sorry, there was an error uploading your file.";
				$_SESSION['status'] = '400';
				header("Location: ../articles_from_students.php");
				exit;
			}
		} else {
			$_SESSION['message'] = "File is not an image.";
			$_SESSION['status'] = '400';
			header("Location: ../articles_from_students.php");
			exit;
		}
	}

	if (!empty($title) && !empty($description)) {
		if ($articleObj->createArticle($title, $description, $author_id, $image_path)) {
			header("Location: ../articles_from_students.php");
			exit;
		}
	}
}

if (isset($_POST['insertAdminArticleBtn'])) {
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
		}
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
		if ($articleObj->updateArticle($article_id, $title, $description, $image_path)) {
			header("Location: ../articles_submitted.php");
			exit;
		}
	}
}

if (isset($_POST['deleteArticleBtn'])) {
	$article_id = $_POST['article_id'];
	$article = $articleObj->getArticles($article_id); // Get article details to notify author

	if ($articleObj->deleteArticle($article_id)) {
		// Notify the author of the deleted article
		if ($article && $article['author_id'] != $_SESSION['user_id']) { // Don't notify if admin deletes their own
			$message = "Your article '" . $article['title'] . "' has been deleted by an administrator.";
			$articleObj->createNotification($article['author_id'], $message, 'deletion', $article_id);
		}
		echo "1"; // Success
	} else {
		echo "0"; // Failure
	}
	exit;
}

if (isset($_POST['updateArticleVisibility'])) {
	$article_id = $_POST['article_id'];
	$status = $_POST['status'];
	echo $articleObj->updateArticleVisibility($article_id, $status);
	exit;
}

if (isset($_POST['acceptEditRequestBtn'])) {
    $request_id = $_POST['request_id'];
    $article_id = $_POST['article_id'];
    $requester_id = $_POST['requester_id'];

    if ($articleObj->updateEditRequestStatus($request_id, 'accepted')) {
        // Grant edit access to the requester
        $articleObj->grantEditAccess($article_id, $requester_id);
        // Notify the requester that their edit request was accepted
        $message = "Your edit request for article '" . $_POST['article_title'] . "' has been accepted. You now have edit access.";
        $articleObj->createNotification($requester_id, $message, 'edit_request_accepted', $article_id);
        header("Location: ../edit_requests.php");
        exit;
    } else {
        $_SESSION['message'] = "Failed to accept edit request.";
        $_SESSION['status'] = '400';
        header("Location: ../edit_requests.php");
        exit;
    }
}

if (isset($_POST['rejectEditRequestBtn'])) {
    $request_id = $_POST['request_id'];
    $article_id = $_POST['article_id'];
    $requester_id = $_POST['requester_id'];

    if ($articleObj->updateEditRequestStatus($request_id, 'rejected')) {
        // Notify the requester that their edit request was rejected
        $message = "Your edit request for article '" . $_POST['article_title'] . "' has been rejected.";
        $articleObj->createNotification($requester_id, $message, 'edit_request_rejected', $article_id);
        header("Location: ../edit_requests.php");
        exit;
    } else {
        $_SESSION['message'] = "Failed to reject edit request.";
        $_SESSION['status'] = '400';
        header("Location: ../edit_requests.php");
        exit;
    }
}

if (isset($_POST['markNotificationAsRead'])) {
    $notification_id = $_POST['notification_id'];
    if ($articleObj->markNotificationAsRead($notification_id)) {
        echo "1"; // Success
    } else {
        echo "0"; // Failure
    }
    exit;
}