<?php

// +----------------------+--------------+------+-----+-------------------+-------------------+
// | Field                | Type         | Null | Key | Default           | Extra             |
// +----------------------+--------------+------+-----+-------------------+-------------------+
// | article_id           | int          | NO   | PRI | NULL              | auto_increment    |
// | article_name         | varchar(50)  | NO   |     | NULL              |                   |
// | article_image        | varchar(256) | YES  |     | NULL              |                   |
// | article_content      | text         | NO   |     | NULL              |                   |
// | article_publication  | datetime     | NO   |     | CURRENT_TIMESTAMP | DEFAULT_GENERATED |
// | article_submission   | datetime     | NO   |     | NULL              |                   |
// | article_is_deleted   | tinyint(1)   | NO   |     | NULL              |                   |
// | article_is_published | tinyint(1)   | NO   |     | NULL              |                   |
// | user_id              | int          | NO   | MUL | NULL              |                   |
// +----------------------+--------------+------+-----+-------------------+-------------------+

require_once 'Database.php';
require_once '../../config.php';
require_once 'Validator.php';
require_once 'ActivityModel.php';

class ArticleModel
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
  // # Article CRUD
  // #############################

  public function getAllArticles($user_id)
  {
    $stmt = $this->db->prepare('SELECT * FROM article_table');
    $stmt->execute();
    $this->logActivity('Get all articles', $user_id);

    return $stmt->fetchAll();
  }

  public function getArticleById($user_id, $id)
  {
    $stmt = $this->db->prepare('SELECT * FROM article_table WHERE article_id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $this->logActivity('Get article by id', $user_id);

    return $stmt->fetch();
  }

  public function getArticleByName($user_id, $name)
  {
    $stmt = $this->db->prepare('SELECT * FROM article_table WHERE article_name = :name');
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    $this->logActivity('Get article by name', $user_id);

    return $stmt->fetch();
  }

  public function addArticle($user_id, $article_name, $article_image, $article_content, $article_publication, $article_submission, $article_is_deleted, $article_is_published)
  {
    $stmt = $this->db->prepare('INSERT INTO article_table (article_name, article_image, article_content, article_publication, article_submission, article_is_deleted, article_is_published, user_id) VALUES (:article_name, :article_image, :article_content, :article_publication, :article_submission, :article_is_deleted, :article_is_published, :user_id)');
    $stmt->bindParam(':article_name', $article_name);
    $stmt->bindParam(':article_image', $article_image);
    $stmt->bindParam(':article_content', $article_content);
    $stmt->bindParam(':article_publication', $article_publication);
    $stmt->bindParam(':article_submission', $article_submission);
    $stmt->bindParam(':article_is_deleted', $article_is_deleted);
    $stmt->bindParam(':article_is_published', $article_is_published);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $this->logActivity('Add article', $user_id);
  }

  public function updateArticle($user_id, $id, $article_name, $article_image, $article_content, $article_publication, $article_submission, $article_is_deleted, $article_is_published)
  {
    $stmt = $this->db->prepare('UPDATE article_table SET article_name = :article_name, article_image = :article_image, article_content = :article_content, article_publication = :article_publication, article_submission = :article_submission, article_is_deleted = :article_is_deleted, article_is_published = :article_is_published, user_id = :user_id WHERE article_id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':article_name', $article_name);
    $stmt->bindParam(':article_image', $article_image);
    $stmt->bindParam(':article_content', $article_content);
    $stmt->bindParam(':article_publication', $article_publication);
    $stmt->bindParam(':article_submission', $article_submission);
    $stmt->bindParam(':article_is_deleted', $article_is_deleted);
    $stmt->bindParam(':article_is_published', $article_is_published);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $this->logActivity('Update article', $user_id);
  }

  public function deleteArticle($user_id, $id)
  {
    $stmt = $this->db->prepare('DELETE FROM article_table WHERE article_id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $this->logActivity('Delete article', $user_id);
  }

  // #############################
  // # Article Validation
  // #############################

  public function validateArticle($article)
  {
    return (new Validator())->validateEntity($article, 'article');
  }
}
