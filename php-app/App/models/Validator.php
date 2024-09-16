<?php

class Validator
{
  private static $errors = [];

  public static function addError($field, $message)
  {
    self::$errors[$field] = $message;
  }

  public static function getErrors()
  {
    return json_encode(self::$errors);
  }

  public static function validateEntity($entity, $type)
  {
    $validationRules = [
      'user' => [
        'user_id' => 'validateId',
        'user_firstname' => 'validateName',
        'user_lastname' => 'validateName',
        'user_username' => 'validateUsername',
        'user_password' => 'validatePassword',
        'user_email' => 'validateEmail'
      ],
      'role' => [
        'role_id' => 'validateId',
        'role_name' => 'validateString'
      ],
      'permission' => [
        'permission_id' => 'validateId',
        'permission_name' => 'validateString'
      ],
      'article' => [
        'article_id' => 'validateId',
        'article_name' => 'validateString',
        'article_image' => 'validateString',
        'article_content' => 'validateText',
        'article_publication' => 'validateDateTime',
        'article_submission' => 'validateDateTime',
        'article_is_deleted' => 'validateBoolean',
        'article_is_published' => 'validateBoolean'
      ],
      'comment' => [
        'comment_id' => 'validateId',
        'comment_content' => 'validateText',
        'comment_publication' => 'validateDateTime',
        'comment_is_deleted' => 'validateBoolean',
        'parent_comment_id' => 'validateId',
        'user_id' => 'validateId',
        'article_id' => 'validateId'
      ],
      'activity' => [
        'activity_id' => 'validateId',
        'activity_type' => 'validateString',
        'activity_date' => 'validateDateTime',
        'user_id' => 'validateId'
      ],
      'tag' => [
        'tag_id' => 'validateId',
        'tag_name' => 'validateString'
      ],
      'tag_article_link' => [
        'tag_id' => 'validateId',
        'article_id' => 'validateId'
      ],
      'role_permission_link' => [
        'role_id' => 'validateId',
        'permission_id' => 'validateId'
      ]
    ];

    self::$errors = [];

    if (!isset($validationRules[$type])) {
      self::$errors[] = "Invalid entity type: $type.";

      return false;
    }

    foreach ($validationRules[$type] as $field => $validationMethod) {
      if (!self::$validationMethod($entity[$field])) {
        self::$errors[] = "Validation failed for $field.";
      }
    }

    return empty(self::$errors);
  }

  // #############################
  // # Validation
  // #############################

  public static function validateUsername($username)
  {
    if (!preg_match('/^[a-zA-Z0-9]{2,32}$/', $username)) {
      self::addError('username', 'Invalid username');

      return false;
    }

    return true;
  }

  public static function validatePassword($password)
  {
    if (!preg_match('/^.{12,64}$/', $password)) {
      self::addError('password', 'Invalid password');

      return false;
    }
    if (!preg_match('/[A-Z]/', $password)) {
      self::addError('password', 'Password must contain at least one uppercase letter');

      return false;
    }
    if (!preg_match('/[0-9]/', $password)) {
      self::addError('password', 'Password must contain at least one number');

      return false;
    }
    if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
      self::addError('password', 'Password must contain at least one special character');

      return false;
    }

    return true;
  }

  public static function validateEmail($email)
  {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      self::addError('email', 'Invalid email');

      return false;
    }

    return true;
  }

  public static function validateName($name)
  {
    if (!preg_match('/^[a-zA-Z]{1,32}$/', $name)) {
      self::addError('name', 'Invalid name');

      return false;
    }

    return true;
  }

  public static function validateId($id)
  {
    if (!preg_match('/^[0-9]{1,16}$/', $id)) {
      self::addError('id', 'Invalid id');

      return false;
    }

    return true;
  }

  public static function validateString($string)
  {
    if (!preg_match('/^[a-zA-Z0-9]{1,255}$/', $string)) {
      self::addError('string', 'Invalid string');

      return false;
    }

    return true;
  }

  public static function validateText($text)
  {
    if (!preg_match('/^[a-zA-Z0-9]{1,65535}$/', $text)) {
      self::addError('text', 'Invalid text');

      return false;
    }

    return true;
  }

  public static function validateBoolean($boolean)
  {
    if (!preg_match('/^(0|1)$/', $boolean)) {
      self::addError('boolean', 'Invalid boolean');

      return false;
    }

    return true;
  }

  public static function validateInt($int)
  {
    if (!preg_match('/^[0-9]{1,16}$/', $int)) {
      self::addError('int', 'Invalid int');

      return false;
    }

    return true;
  }

  public static function validateFloat($float)
  {
    if (!preg_match('/^[0-9]{1,16}\.[0-9]{1,16}$/', $float)) {
      self::addError('float', 'Invalid float');

      return false;
    }

    return true;
  }

  public static function validateDate($date)
  {
    if (!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $date)) {
      self::addError('date', 'Invalid date');

      return false;
    }

    return true;
  }

  public static function validateTime($time)
  {
    if (!preg_match('/^[0-9]{2}:[0-9]{2}:[0-9]{2}$/', $time)) {
      self::addError('time', 'Invalid time');

      return false;
    }

    return true;
  }

  public static function validateDateTime($datetime)
  {
    if (!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/', $datetime)) {
      self::addError('datetime', 'Invalid datetime');

      return false;
    }

    return true;
  }
}
