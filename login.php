<?php
$username = '';
if (isset($_POST['submit_login'])) {
  $_SESSION['active'] = '';

  $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
  $password = trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

  if (empty($email)) {
    $validate = "Please enter your email";
  } elseif (empty($password)) {
    $validate = "Please enter your password";
  } else {
    try {
      $sql = "select username from user where email = :email and password = :password";

      $statement = $connection->prepare($sql);
      $statement->bindParam(':email', $email);
      $statement->bindParam(':password', $password);
      $statement->execute();

      $statement->setFetchMode(PDO::FETCH_ASSOC);
      $users = $statement->fetchAll();

      foreach ($users as $user) {
        $username = $user['username'];
      }
      $count = $statement->rowCount();
      if ($count <= 0) {
        $validate = 'Invalid email or password';
      } else {
        $validate = "Hi, $username";
      }

    } catch (PDOException $e) {
      $validate = 'ERROR: ' . $e->getMessage();
    }
  }
}

if (isset($_POST['submit_signup'])) {
  $_SESSION['active'] = 'active';

  $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
  $email = trim(filter_input(INPUT_POST, 'signup-email', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
  $password = trim(filter_input(INPUT_POST, 'signup-password', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
  if (empty($username)) {
    $signup_validate = 'Please enter a username';
  } elseif (empty($email)) {
    $signup_validate = 'Please enter your email';
  } elseif (empty($password)) {
    $signup_validate = 'Please enter your password';
  } else {
    if (strlen($password) < 5) {
      $signup_validate = "Use 5 characters or more for your password";
    } else {
      try {
        $sql = 'select * from user where email = :email';
        $statement = $connection->prepare($sql);
        $statement->bindParam(':email', $email);
        $statement->execute();
        $check_account = $statement->rowCount();
        if ($check_account > 0) {
          $signup_validate = 'That email is taken. Try another.';
        } else {
          $sql = 'insert into user(email, username, password) values(:email, :username, :password)';
          $statement = $connection->prepare($sql);
          $statement->bindParam(':email', $email);
          $statement->bindParam(':username', $username);
          $statement->bindParam(':password', $password);
          $statement->execute();
          $signup_validate = "Welcome, $username";
        }
      } catch (PDOException $e) {
        $signup_validate = "ERROR: " . $e->getMessage();
      }
    }
  }
}
?>
