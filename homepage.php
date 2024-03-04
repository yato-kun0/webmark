<?php 
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;

  require 'vendor/autoload.php';

  if(isset($_POST["register"])) {

    $name = $_POST["name"];
    $birthdate = $_POST["birthdate"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $mail = new PHPMailer(true);

    try{
      $mail->SMTPDebug = 0;

      $mail->isSMTP();

      $mail->Host = '';

      $mail->SMTPAuth = true;

      $mail->Username = 'markjohnga00@gmail.com';

      $mail->Password = 'walangpassword100802';

      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

      $mail->Port = 587;

      $mail->setFrom('marjohnga00@gmail.com', 'markweb');

      $mail->addAddress($email, $name);

      $mail->isHTML(true);

      $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);

      $mail->Subject = 'Email verification';
      $mail->Body = '<p>Your verification code is: <b style="font-size: 30px;">' . $verification_code;

      $mail->send();

      $encrypted_password = password_hash($password, PASSWORD_DEFAULT);

      $conn = mysqli_connect("localhost", "root", "", "markdatabase");

      $sql = "INSERT INTO users(firstname, middlename, lastname, suffix, birthdate, phone, email, password, verification_code, email_verified_at) VALUES ('$name', '$birthdate', '$phone', '$email', '$encrypted_password', '$verification_code', null)";

      mysqli_query($conn, $sql);

      header("Location: email_verification.php?email=" . $email);

      exit();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
  }


  if(isset($_POST["login"])){
    $email = $_POST["email"];
    $password =  $_POST["password"];

    $conn = mysqli_connect("localhost", "root", "", "markdatabase");

    $sql = "SELECT * FROM users WHERE = '" . $email . "'";
    $result = mysqli_query($conn, $sql);

    if(mysql_num_rows($result) == 0) {
      header("Location: homepage.php?error=Email not found");
      exit();
    }

    $user = mysqli_fetch_object($result);

    if(!password_verify($password, $user->password)) {
      header("Locaiton: homepage.php?error=Incorrect Password");
      exit();
    }

    if($user->email_verified_at == null) {
      header("Location: homepage.php?error=Please verify your account <a href='email_verification.php?email=". $email ."'>form here</a>");
      exit();
    }

    header("Location: homepage.php");
    exit();
  }

?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <nav>
    <div class="logo">MARK JOHN's WEBSITE</div>
    <div class="nav-items">
      <a href="#" id="home-link">Home</a> 
      <a href="#">About</a>
    </div>
  </nav>
  <section class="home">
    <div class="home-container">
      <div class="column-left">
        <h1>EXPERIENCE THE BEST</h1>
        <p>
          Where every journey begins. Explore, discover, and experience the world like never before. Start your adventure today!
        </p>
        <button id="getStartedBtn">Get Started</button>
      </div>
      <div class="column-right">
        <img src="/rightImage.png" alt="illustration" class="home-image"/>
      </div>
    </div>
  </section>

  <section class="register" id="registerSection">
    <div class="register-container">
      <form action="" method="POST">
        <h2>Register</h2>
        <input type="text" name="firstname" id="name" placeholder="First Name" required>
        <input type="text" name="middlename" id="name" placeholder="Middle Name" required>
        <input type="text" name="lastname" id="name" placeholder="Last Name" required>
        <input type="text" name="suffix" id="name" placeholder="Suffix">
        <input type="date" name="birthdate" id="birthdate" placeholder="Birthdate" required>
        <input type="tel"  name="phone" id="phone" placeholder="Mobile Number" required>
        <input type="email" name="email" id="email" placeholder="Email" required>
        <input type="password" name="password" id="password" placeholder="Password" required>
        <button type="submit" name="register">Register</button>
      </form>
      <p class="login-message">Already have an account? <a href="#" id="showLoginLink">Sign In</a></p>
    </div>
  </section>

  <section class="login" id="loginSection">
    <div class="login-container">
      <form action="" method="post">
        <h2>Login</h2>
        <input type="email" name="email" id="email" placeholder="Email" required>
        <input type="password" name="password" id="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
      </form>
      <p class="login-message">Don't have an account? <a href="#" id="showSignupLink">Sign up</a></p>
    </div>
  </section>

  <script src="script.js"></script>
</body>
</html>
