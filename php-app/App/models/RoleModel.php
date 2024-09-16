<?php

// +-----------+-------------+------+-----+---------+----------------+
// | Field     | Type        | Null | Key | Default | Extra          |
// +-----------+-------------+------+-----+---------+----------------+
// | role_id   | int         | NO   | PRI | NULL    | auto_increment |
// | role_name | varchar(50) | NO   | UNI | NULL    |                |
// +-----------+-------------+------+-----+---------+----------------+

require_once 'Database.php';
require_once '../../config.php';
require_once 'Validator.php';
require_once 'ActivityModel.php';

class RoleModel
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
  // # Role CRUD
  // #############################

  public function getAllRoles($user_id)
  {
    $stmt = $this->db->prepare('SELECT * FROM role_table');
    $stmt->execute();
    $this->logActivity('Get all roles', $user_id);

    return $stmt->fetchAll();
  }

  public function getRoleById($user_id, $id)
  {
    $stmt = $this->db->prepare('SELECT * FROM role_table WHERE role_id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $this->logActivity('Get role by id', $user_id);

    return $stmt->fetch();
  }

  public function getRoleByName($user_id, $name)
  {
    $stmt = $this->db->prepare('SELECT * FROM role_table WHERE role_name = :name');
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    $this->logActivity('Get role by name', $user_id);

    return $stmt->fetch();
  }

  public function addRole($user_id, $role_name)
  {
    $stmt = $this->db->prepare('INSERT INTO role_table (role_name) VALUES (:role_name)');
    $stmt->bindParam(':role_name', $role_name);
    $stmt->execute();
    $this->logActivity('Add role', $user_id);
  }

  public function updateRole($user_id, $id, $role_name)
  {
    $stmt = $this->db->prepare('UPDATE role_table SET role_name = :role_name WHERE role_id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':role_name', $role_name);
    $stmt->execute();
    $this->logActivity('Update role', $user_id);
  }

  public function deleteRole($user_id, $id)
  {
    $stmt = $this->db->prepare('DELETE FROM role_table WHERE role_id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $this->logActivity('Delete role', $user_id);
  }

  // #############################
  // # Role Validation
  // #############################

  public function validateRole($role)
  {
    return (new Validator())->validateEntity($role, 'role');
  }
}
