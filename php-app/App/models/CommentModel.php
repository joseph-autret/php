<?php

// +---------------------+---------------+------+-----+-------------------+-------------------+
// | Field               | Type          | Null | Key | Default           | Extra             |
// +---------------------+---------------+------+-----+-------------------+-------------------+
// | comment_id          | int           | NO   | PRI | NULL              | auto_increment    |
// | comment_content     | varchar(1024) | NO   |     | NULL              |                   |
// | comment_upvote      | int           | NO   |     | NULL              |                   |
// | comment_downvote    | int           | NO   |     | NULL              |                   |
// | comment_publication | datetime      | NO   |     | CURRENT_TIMESTAMP | DEFAULT_GENERATED |
// | comment_is_deleted  | tinyint(1)    | NO   |     | NULL              |                   |
// | parent_comment_id   | int           | YES  |     | NULL              |                   |
// | user_id             | int           | NO   | MUL | NULL              |                   |
// | article_id          | int           | NO   | MUL | NULL              |                   |
// +---------------------+---------------+------+-----+-------------------+-------------------+

require_once 'Database.php';
require_once '../../config.php';
require_once 'Validator.php';
require_once 'ActivityModel.php';

class CommentModel
{
  private $db;

  public function __construct()
  {
    $this->db = Database::getInstance()->getConnection();
  }

  public function __destruct()
  {
    $this->db = null;
  }

  private function logActivity($activity_type, $user_id)
  {
    (new ActivityModel())->addActivity($activity_type, $user_id);
  }

  // #############################
  // # Comment CRUD
  // #############################

  public function getAllComments($user_id)
  {
    $stmt = $this->db->prepare('SELECT * FROM comment_table');
    $stmt->execute();
    $this->logActivity('Get all comments', $user_id);

    return $stmt->fetchAll();
  }

  public function getCommentById($user_id, $id)
  {
    $stmt = $this->db->prepare('SELECT * FROM comment_table WHERE comment_id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $this->logActivity('Get comment by id', $user_id);

    return $stmt->fetch();
  }

  public function getCommentByArticleId($user_id, $article_id)
  {
    $stmt = $this->db->prepare('SELECT * FROM comment_table WHERE article_id = :article_id');
    $stmt->bindParam(':article_id', $article_id);
    $stmt->execute();
    $this->logActivity('Get comment by article id', $user_id);

    return $stmt->fetchAll();
  }

  public function addComment($user_id, $article_id, $comment_content, $parent_comment_id)
  {
    $stmt = $this->db->prepare('INSERT INTO comment_table (comment_content, user_id, article_id, parent_comment_id) VALUES (:comment_content, :user_id, :article_id, :parent_comment_id)');
    $stmt->bindParam(':comment_content', $comment_content);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':article_id', $article_id);
    $stmt->bindParam(':parent_comment_id', $parent_comment_id);
    $stmt->execute();
    $this->logActivity('Add comment', $user_id);
  }

  public function updateComment($user_id, $id, $comment_content)
  {
    $stmt = $this->db->prepare('UPDATE comment_table SET comment_content = :comment_content WHERE comment_id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':comment_content', $comment_content);
    $stmt->execute();
    $this->logActivity('Update comment', $user_id);
  }

  public function deleteComment($user_id, $id)
  {
    $stmt = $this->db->prepare('UPDATE comment_table SET comment_is_deleted = 1 WHERE comment_id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $this->logActivity('Delete comment', $user_id);
  }

  // #############################
  // # Comment Voting
  // #############################

  public function upvoteComment($user_id, $id)
  {
    $stmt = $this->db->prepare('UPDATE comment_table SET comment_upvote = comment_upvote + 1 WHERE comment_id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $this->logActivity('Upvote comment', $user_id);
  }

  public function downvoteComment($user_id, $id)
  {
    $stmt = $this->db->prepare('UPDATE comment_table SET comment_downvote = comment_downvote + 1 WHERE comment_id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $this->logActivity('Downvote comment', $user_id);
  }

  // #############################
  // # Comment Validation
  // #############################

  public function validateComment($comment)
  {
    return (new Validator())->validateEntity($comment, 'comment');
  }
}
