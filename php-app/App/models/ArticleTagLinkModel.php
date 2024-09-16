<?php

// +------------+------+------+-----+---------+-------+
// | Field      | Type | Null | Key | Default | Extra |
// +------------+------+------+-----+---------+-------+
// | article_id | int  | NO   | PRI | NULL    |       |
// | tag_id     | int  | NO   | PRI | NULL    |       |
// +------------+------+------+-----+---------+-------+

require_once 'Database.php';
require_once '../../config.php';
require_once 'Validator.php';
require_once 'ActivityModel.php';

class ArticleTagLinkModel
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
  // # ArticleTagLink CRUD
  // #############################

  public function getAllArticleTag($user_id)
  {
    $stmt = $this->db->prepare('SELECT * FROM article_tag');
    $stmt->execute();
    $this->logActivity('Get all article_tags', $user_id);

    return $stmt->fetchAll();
  }

  public function getArticleTagByArticleId($user_id, $article_id)
  {
    $stmt = $this->db->prepare('SELECT * FROM article_tag WHERE article_id = :article_id');
    $stmt->bindParam(':article_id', $article_id);
    $stmt->execute();
    $this->logActivity('Get article_tag by article_id', $user_id);

    return $stmt->fetchAll();
  }

  public function getArticleTagByTagId($user_id, $tag_id)
  {
    $stmt = $this->db->prepare('SELECT * FROM article_tag WHERE tag_id = :tag_id');
    $stmt->bindParam(':tag_id', $tag_id);
    $stmt->execute();
    $this->logActivity('Get article_tag by tag_id', $user_id);

    return $stmt->fetchAll();
  }

  public function addTagToArticle($user_id, $article_id, $tag_id)
  {
    $stmt = $this->db->prepare('INSERT INTO article_tag (article_id, tag_id) VALUES (:article_id, :tag_id)');
    $stmt->bindParam(':article_id', $article_id);
    $stmt->bindParam(':tag_id', $tag_id);
    $stmt->execute();
    $this->logActivity('Add tag to article', $user_id);
  }

  public function removeTagFromArticle($user_id, $article_id, $tag_id)
  {
    $stmt = $this->db->prepare('DELETE FROM article_tag WHERE article_id = :article_id AND tag_id = :tag_id');
    $stmt->bindParam(':article_id', $article_id);
    $stmt->bindParam(':tag_id', $tag_id);
    $stmt->execute();
    $this->logActivity('Remove tag from article', $user_id);
  }

  public function removeAllTagsFromArticle($user_id, $article_id)
  {
    $stmt = $this->db->prepare('DELETE FROM article_tag WHERE article_id = :article_id');
    $stmt->bindParam(':article_id', $article_id);
    $stmt->execute();
    $this->logActivity('Remove all tags from article', $user_id);
  }

  // #############################
  // # ArticleTagLink Validation
  // #############################

  public function validateArticleTagLink($article_tag)
  {
    return (new Validator())->validateEntity($article_tag, 'article_tag');
  }
}
