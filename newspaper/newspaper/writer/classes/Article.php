<?php

require_once 'Database.php';
require_once 'User.php';
/**
 * Class for handling Article-related operations.
 * Inherits CRUD methods from the Database class.
 */
class Article extends Database
{
    /**
     * Creates a new article.
     * @param string $title The article title.
     * @param string $content The article content.
     * @param int $author_id The ID of the author.
     * @param string|null $image_path The path to the article image.
     * @return int The ID of the newly created article.
     */
    public function createArticle($title, $content, $author_id, $image_path = null)
    {
        $sql = "INSERT INTO articles (title, content, author_id, is_active, image_path) VALUES (?, ?, ?, 0, ?)";
        $this->executeNonQuery($sql, [$title, $content, $author_id, $image_path]);
        return $this->lastInsertId();
    }

    /**
     * Retrieves articles from the database.
     * @param int|null $id The article ID to retrieve, or null for all articles.
     * @return array
     */
    public function getArticles($id = null)
    {
        if ($id) {
            $sql = "SELECT articles.*, school_publication_users.username, school_publication_users.is_admin FROM articles JOIN school_publication_users ON articles.author_id = school_publication_users.user_id WHERE article_id = ?";
            return $this->executeQuerySingle($sql, [$id]);
        }
        $sql = "SELECT articles.*, school_publication_users.username, school_publication_users.is_admin FROM articles JOIN school_publication_users ON articles.author_id = school_publication_users.user_id ORDER BY articles.created_at DESC";
        return $this->executeQuery($sql);
    }

    public function getActiveArticles($id = null)
    {
        if ($id) {
            $sql = "SELECT articles.*, school_publication_users.username, school_publication_users.is_admin FROM articles JOIN school_publication_users ON articles.author_id = school_publication_users.user_id WHERE article_id = ?";
            return $this->executeQuerySingle($sql, [$id]);
        }
        $sql = "SELECT articles.*, school_publication_users.username, school_publication_users.is_admin FROM articles 
                JOIN school_publication_users ON 
                articles.author_id = school_publication_users.user_id 
                WHERE is_active = 1 ORDER BY articles.created_at DESC";

        return $this->executeQuery($sql);
    }

    public function getArticlesByUserID($user_id)
    {
        $sql = "SELECT articles.*, school_publication_users.username, school_publication_users.is_admin FROM articles 
                JOIN school_publication_users ON 
                articles.author_id = school_publication_users.user_id
                WHERE author_id = ? ORDER BY articles.created_at DESC";
        return $this->executeQuery($sql, [$user_id]);
    }

    /**
     * Updates an article.
     * @param int $id The article ID to update.
     * @param string $title The new title.
     * @param string $content The new content.
     * @param string|null $image_path The new image path.
     * @return int The number of affected rows.
     */
    public function updateArticle($id, $title, $content, $image_path = null)
    {
        if ($image_path) {
            $sql = "UPDATE articles SET title = ?, content = ?, image_path = ? WHERE article_id = ?";
            return $this->executeNonQuery($sql, [$title, $content, $image_path, $id]);
        } else {
            $sql = "UPDATE articles SET title = ?, content = ? WHERE article_id = ?";
            return $this->executeNonQuery($sql, [$title, $content, $id]);
        }
    }

    /**
     * Toggles the visibility (is_active status) of an article.
     * This operation is restricted to admin users only.
     * @param int $id The article ID to update.
     * @param bool $is_active The new visibility status.
     * @return int The number of affected rows.
     */
    public function updateArticleVisibility($id, $is_active)
    {
        $sql = "UPDATE articles SET is_active = ? WHERE article_id = ?";
        return $this->executeNonQuery($sql, [(int)$is_active, $id]);
    }


    /**
     * Deletes an article.
     * @param int $id The article ID to delete.
     * @return int The number of affected rows.
     */
    public function deleteArticle($id)
    {
        $sql = "DELETE FROM articles WHERE article_id = ?";
        return $this->executeNonQuery($sql, [$id]);
    }

    /**
     * Requests an edit for an article.
     * @param int $article_id The ID of the article to request an edit for.
     * @param int $requester_id The ID of the user requesting the edit.
     * @param string $request_message The message for the edit request.
     * @return int The ID of the newly created edit request.
     */
    public function requestEdit($article_id, $requester_id, $request_message)
    {
        $sql = "INSERT INTO edit_requests (article_id, requester_id, request_message, status) VALUES (?, ?, ?, 'pending')";
        $this->executeNonQuery($sql, [$article_id, $requester_id, $request_message]);
        return $this->lastInsertId();
    }

    /**
     * Retrieves edit requests for a given article or all edit requests.
     * @param int|null $article_id The article ID to retrieve edit requests for, or null for all.
     * @return array
     */
    public function getEditRequests($article_id = null)
    {
        if ($article_id) {
            $sql = "SELECT er.*, u.username as requester_username, a.title as article_title, a.author_id as article_author_id
                    FROM edit_requests er
                    JOIN school_publication_users u ON er.requester_id = u.user_id
                    JOIN articles a ON er.article_id = a.article_id
                    WHERE er.article_id = ? ORDER BY er.created_at DESC";
            return $this->executeQuery($sql, [$article_id]);
        }
        $sql = "SELECT er.*, u.username as requester_username, a.title as article_title, a.author_id as article_author_id
                FROM edit_requests er
                JOIN school_publication_users u ON er.requester_id = u.user_id
                JOIN articles a ON er.article_id = a.article_id
                ORDER BY er.created_at DESC";
        return $this->executeQuery($sql);
    }

    /**
     * Retrieves edit requests made by a specific user.
     * @param int $requester_id The ID of the user who made the request.
     * @return array
     */
    public function getEditRequestsByRequesterID($requester_id)
    {
        $sql = "SELECT er.*, u.username as author_username, a.title as article_title, a.author_id as article_author_id
                FROM edit_requests er
                JOIN articles a ON er.article_id = a.article_id
                JOIN school_publication_users u ON a.author_id = u.user_id
                WHERE er.requester_id = ? ORDER BY er.created_at DESC";
        return $this->executeQuery($sql, [$requester_id]);
    }

    /**
     * Updates the status of an edit request.
     * @param int $request_id The ID of the edit request.
     * @param string $status The new status ('pending', 'accepted', 'rejected').
     * @return int The number of affected rows.
     */
    public function updateEditRequestStatus($request_id, $status)
    {
        $sql = "UPDATE edit_requests SET status = ? WHERE request_id = ?";
        return $this->executeNonQuery($sql, [$status, $request_id]);
    }

    /**
     * Grants edit access to an article for a specific user.
     * @param int $article_id The ID of the article.
     * @param int $user_id The ID of the user to grant access to.
     * @return int The ID of the newly created shared article entry.
     */
    public function grantEditAccess($article_id, $user_id)
    {
        // Check if access already exists
        $checkSql = "SELECT COUNT(*) FROM shared_articles WHERE article_id = ? AND user_id = ?";
        $count = $this->executeQuerySingle($checkSql, [$article_id, $user_id]);
        if ($count['COUNT(*)'] > 0) {
            return 0; // Access already granted
        }

        $sql = "INSERT INTO shared_articles (article_id, user_id) VALUES (?, ?)";
        $this->executeNonQuery($sql, [$article_id, $user_id]);
        return $this->lastInsertId();
    }

    /**
     * Revokes edit access to an article for a specific user.
     * @param int $article_id The ID of the article.
     * @param int $user_id The ID of the user to revoke access from.
     * @return int The number of affected rows.
     */
    public function revokeEditAccess($article_id, $user_id)
    {
        $sql = "DELETE FROM shared_articles WHERE article_id = ? AND user_id = ?";
        return $this->executeNonQuery($sql, [$article_id, $user_id]);
    }

    /**
     * Retrieves articles shared with a specific user.
     * @param int $user_id The ID of the user.
     * @return array
     */
    public function getSharedArticlesForUser($user_id)
    {
        $sql = "SELECT a.*, u.username as author_username, sa.shared_at
                FROM shared_articles sa
                JOIN articles a ON sa.article_id = a.article_id
                JOIN school_publication_users u ON a.author_id = u.user_id
                WHERE sa.user_id = ? AND a.is_active = 1 ORDER BY sa.shared_at DESC";
        return $this->executeQuery($sql, [$user_id]);
    }

    /**
     * Notifies a user about an event (e.g., article deletion, edit request).
     * @param int $user_id The ID of the user to notify.
     * @param string $message The notification message.
     * @param string $type The type of notification (e.g., 'deletion', 'edit_request').
     * @param int|null $related_id The ID of the related entity (e.g., article_id, request_id).
     * @return int The ID of the newly created notification.
     */
    public function createNotification($user_id, $message, $type, $related_id = null)
    {
        $sql = "INSERT INTO notifications (user_id, message, type, related_id) VALUES (?, ?, ?, ?)";
        $this->executeNonQuery($sql, [$user_id, $message, $type, $related_id]);
        return $this->lastInsertId();
    }

    /**
     * Retrieves notifications for a specific user.
     * @param int $user_id The ID of the user.
     * @return array
     */
    public function getNotificationsByUserID($user_id)
    {
        $sql = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
        return $this->executeQuery($sql, [$user_id]);
    }

    /**
     * Marks a notification as read.
     * @param int $notification_id The ID of the notification.
     * @return int The number of affected rows.
     */
    public function markNotificationAsRead($notification_id)
    {
        $sql = "UPDATE notifications SET is_read = 1 WHERE notification_id = ?";
        return $this->executeNonQuery($sql, [$notification_id]);
    }
}
