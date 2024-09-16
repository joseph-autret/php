<?php

// +-----------------+-------------+------+-----+---------+----------------+
// | Field           | Type        | Null | Key | Default | Extra          |
// +-----------------+-------------+------+-----+---------+----------------+
// | permission_id   | int         | NO   | PRI | NULL    | auto_increment |
// | permission_name | varchar(50) | NO   | UNI | NULL    |                |
// +-----------------+-------------+------+-----+---------+----------------+

require_once 'Database.php';
require_once '../../config.php';
require_once 'Validator.php';
require_once 'ActivityModel.php';

class PermissionModel
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
  // # Permission CRUD
  // #############################

  public function getAllPermissions($user_id)
  {
    $stmt = $this->db->prepare('SELECT * FROM permission_table');
    $stmt->execute();
    $this->logActivity('Get all permissions', $user_id);

    return $stmt->fetchAll();
  }

  public function getPermissionById($user_id, $id)
  {
    $stmt = $this->db->prepare('SELECT * FROM permission_table WHERE permission_id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $this->logActivity('Get permission by id', $user_id);

    return $stmt->fetch();
  }

  public function getPermissionByName($user_id, $name)
  {
    $stmt = $this->db->prepare('SELECT * FROM permission_table WHERE permission_name = :name');
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    $this->logActivity('Get permission by name', $user_id);

    return $stmt->fetch();
  }

  public function addPermission($user_id, $permission_name)
  {
    $stmt = $this->db->prepare('INSERT INTO permission_table (permission_name) VALUES (:permission_name)');
    $stmt->bindParam(':permission_name', $permission_name);
    $stmt->execute();
    $this->logActivity('Add permission', $user_id);
  }

  public function updatePermission($user_id, $id, $permission_name)
  {
    $stmt = $this->db->prepare('UPDATE permission_table SET permission_name = :permission_name WHERE permission_id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':permission_name', $permission_name);
    $stmt->execute();
    $this->logActivity('Update permission', $user_id);
  }

  public function deletePermission($user_id, $id)
  {
    $stmt = $this->db->prepare('DELETE FROM permission_table WHERE permission_id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $this->logActivity('Delete permission', $user_id);
  }

  // #############################
  // # Permission Validation
  // #############################

  public function validatePermission($permission)
  {
    return (new Validator())->validateEntity($permission, 'permission');
  }
}
