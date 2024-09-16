<?php

// +----------+-------------+------+-----+---------+----------------+
// | Field    | Type        | Null | Key | Default | Extra          |
// +----------+-------------+------+-----+---------+----------------+
// | tag_id   | int         | NO   | PRI | NULL    | auto_increment |
// | tag_name | varchar(50) | NO   |     | NULL    |                |
// +----------+-------------+------+-----+---------+----------------+

require_once 'Database.php';
require_once '../../config.php';
require_once 'Validator.php';
require_once 'ActivityModel.php';

class TagModel
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
  // # Tag CRUD
  // #############################

  public function getAllTags($user_id)
  {
    $stmt = $this->db->prepare('SELECT * FROM tag_table');
    $stmt->execute();
    $this->logActivity('Get all tags', $user_id);

    return $stmt->fetchAll();
  }

  public function getTagById($user_id, $id)
  {
    $stmt = $this->db->prepare('SELECT * FROM tag_table WHERE tag_id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $this->logActivity('Get tag by id', $user_id);

    return $stmt->fetch();
  }

  public function getTagByName($user_id, $name)
  {
    $stmt = $this->db->prepare('SELECT * FROM tag_table WHERE tag_name = :name');
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    $this->logActivity('Get tag by name', $user_id);

    return $stmt->fetch();
  }

  public function addTag($user_id, $tag_name)
  {
    $stmt = $this->db->prepare('INSERT INTO tag_table (tag_name) VALUES (:tag_name)');
    $stmt->bindParam(':tag_name', $tag_name);
    $stmt->execute();
    $this->logActivity('Add tag', $user_id);
  }

  public function updateTag($user_id, $id, $tag_name)
  {
    $stmt = $this->db->prepare('UPDATE tag_table SET tag_name = :tag_name WHERE tag_id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':tag_name', $tag_name);
    $stmt->execute();
    $this->logActivity('Update tag', $user_id);
  }

  public function deleteTag($user_id, $id)
  {
    $stmt = $this->db->prepare('DELETE FROM tag_table WHERE tag_id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $this->logActivity('Delete tag', $user_id);
  }

  // #############################
  // # Tag Validation
  // #############################

  public function validateTag($tag)
  {
    return (new Validator())->validateEntity($tag, 'tag');
  }
}
