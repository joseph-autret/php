<?php

// +--------------------+-------------+------+-----+-------------------+-------------------+
// | Field              | Type        | Null | Key | Default           | Extra             |
// +--------------------+-------------+------+-----+-------------------+-------------------+
// | activity_id        | int         | NO   | PRI | NULL              | auto_increment    |
// | activity_type      | varchar(50) | NO   |     | NULL              |                   |
// | activity_timestamp | datetime    | NO   |     | CURRENT_TIMESTAMP | DEFAULT_GENERATED |
// | user_id            | int         | NO   | MUL | NULL              |                   |
// +--------------------+-------------+------+-----+-------------------+-------------------+

require_once 'Database.php';
require_once 'config.php';
require_once 'Validator.php';

class ActivityModel
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

  // #############################
  // # Activity CRUD
  // #############################

  public function getAllActivities()
  {
    $stmt = $this->db->prepare('SELECT * FROM activity_table');
    $stmt->execute();

    return $stmt->fetchAll();
  }

  public function getActivitiesByUserId($id)
  {
    $stmt = $this->db->prepare('SELECT * FROM activity_table WHERE user_id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    return $stmt->fetchAll();
  }

  public function getActivityById($id)
  {
    $stmt = $this->db->prepare('SELECT * FROM activity_table WHERE activity_id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    return $stmt->fetch();
  }

  public function getActivityByType($activity_type)
  {
    $stmt = $this->db->prepare('SELECT * FROM activity_table WHERE activity_type = :activity_type');
    $stmt->bindParam(':activity_type', $activity_type);
    $stmt->execute();

    return $stmt->fetchAll();
  }

  public function addActivity($activity_type, $user_id)
  {
    if ($user_id == 1) {
      $ip_address = $_SERVER['REMOTE_ADDR'];
      $activity_type += ' + IP: ' + $ip_address;
    }
    $stmt = $this->db->prepare('INSERT INTO activity_table (activity_type, user_id) VALUES (:activity_type, :user_id)');
    $stmt->bindParam(':activity_type', $activity_type);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $this->__destruct();
  }

  // #############################
  // # Activity CRUD (Unused for log preservation)
  // #############################

  private function deleteActivity($id)
  {
    $stmt = $this->db->prepare('DELETE FROM activity_table WHERE activity_id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
  }

  private function updateActivity($id, $activity_type, $user_id)
  {
    $stmt = $this->db->prepare('UPDATE activity_table SET activity_type = :activity_type, user_id = :user_id WHERE activity_id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':activity_type', $activity_type);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
  }

  // #############################
  // # Activity Validation
  // #############################

  public function validateActivity($activity)
  {
    return (new Validator())->validateEntity($activity, 'activity');
  }
}
