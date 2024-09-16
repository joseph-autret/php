<?php

// +-----------------+----------------+------+-----+-------------------+-------------------+
// | Field           | Type           | Null | Key | Default           | Extra             |
// +-----------------+----------------+------+-----+-------------------+-------------------+
// | user_id         | int            | NO   | PRI | NULL              | auto_increment    |
// | user_firstname  | varchar(50)    | YES  |     | NULL              |                   |
// | user_lastname   | varchar(50)    | YES  |     | NULL              |                   |
// | user_username   | varchar(50)    | NO   | UNI | NULL              |                   |
// | user_password   | varbinary(256) | NO   |     | NULL              |                   |
// | user_email      | varchar(50)    | NO   |     | NULL              |                   |
// | user_creation   | datetime       | NO   |     | CURRENT_TIMESTAMP | DEFAULT_GENERATED |
// | user_is_deleted | tinyint(1)     | NO   |     | NULL              |                   |
// | user_image      | varchar(256)   | YES  |     | NULL              |                   |
// | role_id         | int            | NO   | MUL | NULL              |                   |
// +-----------------+----------------+------+-----+-------------------+-------------------+

require_once 'Database.php';
require_once '../../config.php';
require_once 'Validator.php';
require_once 'ActivityModel.php';

class UserModel
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
  // # User CRUD
  // #############################

  public function getAllUsers($user_id)
  {
    $stmt = $this->db->prepare('SELECT * FROM user_table');
    $stmt->execute();
    $this->logActivity('Get all users', $user_id);

    return $stmt->fetchAll();
  }

  public function getAllUserLight($user_id)
  {
    $stmt = $this->db->prepare('SELECT user_id, user_username, user_email, user_creation, role_id FROM user_table');
    $stmt->execute();
    $this->logActivity('Get all users', $user_id);

    return $stmt->fetchAll();
  }

  public function getUserById($user_id, $id)
  {
    $stmt = $this->db->prepare('SELECT * FROM user_table WHERE user_id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $this->logActivity('Get all users', $user_id);

    return $stmt->fetch();
  }

  public function getUserByUsername($user_id, $username)
  {
    $stmt = $this->db->prepare('SELECT * FROM user_table WHERE user_username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $this->logActivity('Get all users', $user_id);

    return $stmt->fetch();
  }

  public function getUserByEmail($user_id, $email)
  {
    $stmt = $this->db->prepare('SELECT * FROM user_table WHERE user_email = :email');
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $this->logActivity('Get all users', $user_id);

    return $stmt->fetch();
  }

  public function createUser($user_id, $user)
  {
    $errors = $this->validateUser($user);
    if (!empty($errors)) {
      return $errors;
    }

    $stmt = $this->db->prepare('INSERT INTO user_table (firstname, lastname, username, password, email) VALUES (:firstname, :lastname, :username, :password, :email)');
    $stmt->bindParam(':firstname', $user['firstname']);
    $stmt->bindParam(':lastname', $user['lastname']);
    $stmt->bindParam(':username', $user['username']);
    $passwordHash = password_hash($user['password'], PASSWORD_BCRYPT);
    $stmt->bindParam(':password', $passwordHash);
    $stmt->bindParam(':email', $user['email']);
    $stmt->execute();
    $this->logActivity('Get all users', $user_id);

    return $this->getUserById($user_id, $this->db->lastInsertId());
  }

  public function updateUser($user_id, $user)
  {
    $errors = $this->validateUser($user);
    if (!empty($errors)) {
      return $errors;
    }

    $stmt = $this->db->prepare('UPDATE user_table SET user_firstname = :firstname, user_lastname = :lastname, user_username = :username, user_password = :password, user_email = :email WHERE user_id = :id');
    $stmt->bindParam(':id', $user['id']);
    $stmt->bindParam(':firstname', $user['firstname']);
    $stmt->bindParam(':lastname', $user['lastname']);
    $stmt->bindParam(':username', $user['username']);
    $stmt->bindParam(':password', $user['password']);
    $stmt->bindParam(':email', $user['email']);
    $stmt->execute();
    $this->logActivity('Get all users', $user_id);

    return $this->getUserById($user_id, $user['id']);
  }

  public function hardDeleteUser($user_id, $id)
  {
    $user = $this->getUserById($user_id, $id);
    if (!$user) {
      return false;
    }

    $stmt = $this->db->prepare('DELETE FROM user_table WHERE user_id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $this->logActivity('Get all users', $user_id);

    return $stmt->rowCount();
  }

  public function softDeleteUser($user_id, $id)
  {
    $stmt = $this->db->prepare('UPDATE user_table SET user_is_deleted = 1 WHERE user_id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $this->logActivity('Get all users', $user_id);

    return $this->getUserById($user_id, $id);
  }

  // #############################
  // # User Validation
  // #############################

  public function validateUser($user)
  {
    return (new Validator())->validateEntity($user, 'user');
  }
}
