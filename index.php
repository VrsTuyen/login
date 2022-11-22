<?php
include_once "./database/config.php";

$validate = '';

if (isset($_POST['submit_login'])) {
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

  if (empty($email)) {
    $validate = "Please enter your email";
  } elseif (empty($password)) {
    $validate = "Please enter your password";
  } else {

    try {
      $sql = "select * from user where email = :email and password = :password";

      $statement = $connection->prepare($sql);
      $statement->bindParam(':email', $email);
      $statement->bindParam(':password', $password);
      $statement->execute();
      // $validate = $sql;
      $count = $statement->rowCount();
      if ($count <= 0) {
        $validate = 'Invalid email or password';
      } else {
        $validate = "Hi, $email";
      }

    } catch (PDOException $e) {
      $validate = 'ERROR: ' . $e->getMessage();
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@200;300;400;500;600;700&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="./assets/css/style.css">
  <title>Login</title>
</head>

<body>
  <div class="main">
    <div class="login">
      <div class="login-title">
        <h2 class="h2-heading">Login</h2>
        <p class="p-paragraph">Hi, Welcome back</p>
      </div>
      <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" class="login-form">
        <div class="login-form-container">
          <label for="email">Email:</label>
          <div class="login-form-container-input">
            <input type="email" name="email" id="email" autocomplete="email"
              value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>" placeholder="E.g. tuyen@email.com">
          </div>
        </div>
        <div class="login-form-container">
          <label for="password">Password:</label>
          <div class="login-form-container-input">
            <input type="password" name="password" id="password"
              value="<?= isset($_POST['password']) ? $_POST['password'] : '' ?>" autocomplete="current-password">
          </div>
        </div>
        <p class="login-form-validate">
          <?= $validate ?>
        </p>
        <div class="login-form-remember">
          <div class="login-form-remember-checkbox">
            <input type="checkbox" name="remember" id="remember">
            <label for="remember">Remember Me</label>
          </div>
          <a href="#">Forgot password</a>
        </div>
        <div class="login-form-submit">
          <input type="submit" name="submit_login" class="submit-button" value="Login">
        </div>
        <div class="login-form-create-account">
          <p class="login-form-create-account-paragraph">Not registered yet? <a href="./register.php"> Create an account
              &nearr;</a></p>
        </div>
      </form>
    </div>
  </div>

</body>

</html>