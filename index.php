<?php
session_start();
include_once "./database/config.php";
include_once "./md5.php";
$md5 = new MD5();

$validate = '';
$signup_validate = '';

$_SESSION['active'] = '';

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
        $md5->setString($password);
        // $validate = $md5->encrypt();
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
  <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@200;300;400;500;600;700;800;900;1000&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="./assets/css/style.css">
  <title>Login</title>
</head>

<body>

  <div class="main">
    <div class="body">
      <h3 class="title">
        Đăng nhập/Đăng ký
      </h3>
      <div class="container">

        <div class="container-left content <?php if (empty($_SESSION['active'])) {
          echo "active";
        } else {
          echo "";
        } ?>">
          <div class="container-left-content-login">
            <div class="container-left-form">
              <div class="container-left-form-title">
                <h2 class="h2-heading">Đăng nhập</h2>
              </div>
              <div class="container-content-login-other">
                <span>
                  <a href="#" class="container-content-login-other-border">
                    <i class="fa fa-facebook"></i>
                  </a>
                  <a href="#" class="container-content-login-other-border">
                    <i class="fa fa-google-plus"></i>
                  </a>
                  <a href="#" class="container-content-login-other-border">
                    <i class="fa fa-linkedin"></i>
                  </a>
                </span>
                <div class="container-content-login-other-title">
                  <p class="paragraph">or use your account</p>
                </div>
              </div>
              <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" class="form">
                <div class="form-container">
                  <div class="form-container-input">
                    <input type="email" name="email" id="email" autocomplete="email"
                      value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>" placeholder="Email">
                  </div>
                </div>
                <div class="form-container">
                  <div class="form-container-input">
                    <input type="password" name="password" id="password"
                      value="<?= isset($_POST['password']) ? $_POST['password'] : '' ?>" placeholder="Password"
                      autocomplete="current-password">
                  </div>
                </div>
                <p class="form-validate">
                  <?= $validate ?>
                </p>
                <div class="form-remember">
                  <a href="#">Forgot your password?</a>
                </div>
                <div class="form-submit">
                  <input type="submit" name="submit_login" class="submit-button" value="Sign in">
                </div>
              </form>
            </div>
          </div>
          <div class="container-content">
            <h2 class="h2-heading">Welcome Back!</h2>
            <p class="paragraph">To keep connected with us please login with your personal info</p>
            <button type="button" name="button-signin" class="container-content-button">Sign In</button>
          </div>
        </div>
        <div class="container-right content <?php if (!empty($_SESSION['active'])) {
          echo "active";
        } else {
          echo "";
        } ?> ">
          <div class="container-content">
            <h2 class="h2-heading">Hello, Friend!</h2>
            <p class="paragraph">Enter your personal details and start journey with us</p>
            <button type="button" name="button-signup" class="container-content-button">Sign Up</button>
          </div>
          <div class="container-right-content-signup">
            <div class="container-right-content-signup-form">
              <div class="container-right-content-signup-forn-title">
                <h2 class="h2-heading">Đăng ký</h2>
              </div>
              <div class="container-content-login-other">
                <span>
                  <a href="#" class="container-content-login-other-border">
                    <i class="fa fa-facebook"></i>
                  </a>
                  <a href="#" class="container-content-login-other-border">
                    <i class="fa fa-google-plus"></i>
                  </a>
                  <a href="#" class="container-content-login-other-border">
                    <i class="fa fa-linkedin"></i>
                  </a>
                </span>
                <div class="container-content-login-other-title">
                  <p class="paragraph">or use your email for registration</p>
                </div>
              </div>
              <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" class="signup-form">
                <div class="form-container">
                  <div class="form-container-input">
                    <input type="text" name="username" id="username" autocomplete="username"
                      value="<?= isset($_POST['username']) ? $_POST['username'] : '' ?>" placeholder="UserName">
                  </div>
                </div>
                <div class="form-container">
                  <div class="form-container-input">
                    <input type="email" name="signup-email" id="signup-email" autocomplete="email"
                      value="<?= isset($_POST['signup-email']) ? $_POST['signup-email'] : '' ?>" placeholder="Email">
                  </div>
                </div>
                <div class="form-container">
                  <div class="form-container-input">
                    <input type="password" name="signup-password" id="signup-password"
                      value="<?= isset($_POST['signup-password']) ? $_POST['signup-password'] : '' ?>"
                      placeholder="Password" autocomplete="current-password">
                  </div>
                </div>
                <p class="form-validate">
                  <?= $signup_validate ?>
                </p>
                <div class="form-submit">
                  <input type="submit" name="submit_signup" class="submit-button" value="Sign Up">
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="./assets/js/main.js"></script>
</body>

</html>