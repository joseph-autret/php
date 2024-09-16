-- Set the SQL mode to strict
SET
  sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- Create the database if it does not exist
CREATE DATABASE IF NOT EXISTS laniakea_db;

-- Use the database
USE laniakea_db;

-- Create the tables if they do not exist
CREATE TABLE IF NOT EXISTS role_table(
  role_id INT AUTO_INCREMENT,
  role_name VARCHAR(50) NOT NULL,
  PRIMARY KEY(role_id),
  UNIQUE(role_name)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE IF NOT EXISTS permission_table(
  permission_id INT AUTO_INCREMENT,
  permission_name VARCHAR(50) NOT NULL,
  PRIMARY KEY(permission_id),
  UNIQUE(permission_name)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE IF NOT EXISTS tag_table(
  tag_id INT AUTO_INCREMENT,
  tag_name VARCHAR(50) NOT NULL,
  PRIMARY KEY(tag_id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE IF NOT EXISTS user_table(
  user_id INT AUTO_INCREMENT,
  user_firstname VARCHAR(50),
  user_lastname VARCHAR(50),
  user_username VARCHAR(50) NOT NULL,
  user_password VARCHAR(256) NOT NULL,
  user_email VARCHAR(50) NOT NULL,
  user_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  user_is_deleted BOOLEAN NOT NULL,
  user_image VARCHAR(256),
  role_id INT NOT NULL,
  PRIMARY KEY(user_id),
  UNIQUE(user_username),
  FOREIGN KEY(role_id) REFERENCES role_table(role_id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE IF NOT EXISTS article_table(
  article_id INT AUTO_INCREMENT,
  article_name VARCHAR(50) NOT NULL,
  article_image VARCHAR(256),
  article_content TEXT NOT NULL,
  article_publication DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  article_submission DATETIME NOT NULL,
  article_is_deleted BOOLEAN NOT NULL,
  article_is_published BOOLEAN NOT NULL,
  user_id INT NOT NULL,
  PRIMARY KEY(article_id),
  FOREIGN KEY(user_id) REFERENCES user_table(user_id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE IF NOT EXISTS comment_table(
  comment_id INT AUTO_INCREMENT,
  comment_content VARCHAR(1024) NOT NULL,
  comment_upvote INT NOT NULL,
  comment_downvote INT NOT NULL,
  comment_publication DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  comment_is_deleted BOOLEAN NOT NULL,
  parent_comment_id INT,
  user_id INT NOT NULL,
  article_id INT NOT NULL,
  PRIMARY KEY(comment_id),
  FOREIGN KEY(user_id) REFERENCES user_table(user_id),
  FOREIGN KEY(article_id) REFERENCES article_table(article_id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE IF NOT EXISTS activity_table(
  activity_id INT AUTO_INCREMENT,
  activity_type VARCHAR(50) NOT NULL,
  activity_timestamp DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  user_id INT NOT NULL,
  PRIMARY KEY(activity_id),
  FOREIGN KEY(user_id) REFERENCES user_table(user_id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE IF NOT EXISTS role_permission(
  role_id INT,
  permission_id INT,
  PRIMARY KEY(role_id, permission_id),
  FOREIGN KEY(role_id) REFERENCES role_table(role_id),
  FOREIGN KEY(permission_id) REFERENCES permission_table(permission_id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE IF NOT EXISTS article_tag(
  article_id INT,
  tag_id INT,
  PRIMARY KEY(article_id, tag_id),
  FOREIGN KEY(article_id) REFERENCES article_table(article_id),
  FOREIGN KEY(tag_id) REFERENCES tag_table(tag_id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;



-- Insert the default data
INSERT IGNORE INTO role_table (role_id, role_name)
VALUES (1, 'Unlogged user'), (2, 'Viewer'), (3, 'Publisher'), (4, 'Moderator'), (5, 'Administrator');

INSERT IGNORE INTO user_table (user_id, user_firstname, user_lastname, user_username, user_password, user_email, user_is_deleted, role_id)
VALUES (0, 'Default', 'User', 'un_logged_user', 'NDI2NDBhNWYyM2E3NjEyY2NiNGU1YjBjOWRiN2NmZTczYTQ1YTkxMjVjZjMxZDE5N2RkZGUwYjBlNmM1Zjg0MzMxMzU=', 'defaultuser@example.com', 0, 1);