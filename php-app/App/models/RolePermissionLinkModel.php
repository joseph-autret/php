<?php

// +---------------+------+------+-----+---------+-------+
// | Field         | Type | Null | Key | Default | Extra |
// +---------------+------+------+-----+---------+-------+
// | role_id       | int  | NO   | PRI | NULL    |       |
// | permission_id | int  | NO   | PRI | NULL    |       |
// +---------------+------+------+-----+---------+-------+

require_once 'Database.php';
require_once '../../config.php';
require_once 'Validator.php';
require_once 'ActivityModel.php';

class RolePermissionLinkModel
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
  // # RolePermissionLink CRUD
  // #############################

  public function getAllRolePermission($user_id)
  {
    $stmt = $this->db->prepare('SELECT * FROM role_permission$');
    $stmt->execute();
    $this->logActivity('Get all role_permission_links', $user_id);

    return $stmt->fetchAll();
  }

  public function getRolePermissionByRoleId($user_id, $role_id, $permission_id)
  {
    $stmt = $this->db->prepare('SELECT * FROM role_permission$ WHERE role_id = :role_id AND permission_id = :permission_id');
    $stmt->bindParam(':role_id', $role_id);
    $stmt->bindParam(':permission_id', $permission_id);
    $stmt->execute();
    $this->logActivity('Get role_permission$ by role_id', $user_id);

    return $stmt->fetch();
  }

  public function getRolePermissionByPermissionId($user_id, $role_id, $permission_id)
  {
    $stmt = $this->db->prepare('SELECT * FROM role_permission$ WHERE role_id = :role_id AND permission_id = :permission_id');
    $stmt->bindParam(':role_id', $role_id);
    $stmt->bindParam(':permission_id', $permission_id);
    $stmt->execute();
    $this->logActivity('Get role_permission_link by permission_id', $user_id);

    return $stmt->fetch();
  }

  public function addPermissionForRole($user_id, $role_id, $permission_id)
  {
    $stmt = $this->db->prepare('INSERT INTO role_permission$ (role_id, permission_id) VALUES (:role_id, :permission_id)');
    $stmt->bindParam(':role_id', $role_id);
    $stmt->bindParam(':permission_id', $permission_id);
    $stmt->execute();
    $this->logActivity('Add role_permission_link', $user_id);
  }

  public function deletePermissionForRole($user_id, $role_id, $permission_id)
  {
    $stmt = $this->db->prepare('DELETE FROM role_permission$ WHERE role_id = :role_id AND permission_id = :permission_id');
    $stmt->bindParam(':role_id', $role_id);
    $stmt->bindParam(':permission_id', $permission_id);
    $stmt->execute();
    $this->logActivity('Delete role_permission_link', $user_id);
  }

  // #############################
  // # RolePermissionLink Validation
  // #############################

  public function validateRolePermissionLink($role_permission)
  {
    return (new Validator())->validateEntity($role_permission, 'role_permission');
  }
}
