<?php
require_once("config.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$output = '';

if($_POST){
    if(isset($_POST['email'])){
        $email = $_POST['email'];
        if($email == ''){
            unset($email);
        }
    }
    if(isset($_POST['password'])){
        $password = $_POST['password'];
        if($password == ''){
            unset($password);
        }
    }
    
    if(!empty($email) && !empty($password)){
        $password = password_hash($password, PASSWORD_DEFAULT);

        function getToken($len=32){
            return substr(md5(openssl_random_pseudo_bytes(20)), -$len);
        }
        $token = getToken(10);
        $insert = $conn->prepare("INSERT INTO users SET
            email=:email,
            password=:password,
            token=:token");
        $insert->execute(array(
            'email'     => $email,
            'password'  => $password,
            'token'     => $token
        ));
        
        //require("/vendor/autoload.php");
        require 'vendor/autoload.php';

        $mail = new PHPMailer(true);
        
        try {
            $mail->setFrom('rusuvictor30@gmail.com', 'User Registration');
            $mail->addAddress('metest175@gmail.com');

            $mail->isHTML(true);
            $mail->Subject = 'Confirm email';
            $mail->Body = 'Activate your email:
            <a href="http://mail.ruvictor.com/verification.php?email=' . $email . '&token=' . $token . '">Confirm email</a>';

            $mail->send();
            $output = 'Message sent!';
        } catch (Exception $e) {
            $output = $mail->ErrorInfo;
        }

    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register User</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <style>
        .mainContainer{
            display: table;
            margin: 100px auto 0;
            padding: 10px;
            border: 1px solid #ddd;
            width: 100%;
            max-width: 500px;
        }
    </style>
</head>
<body>
    <div class="mainContainer">
        <?php echo $output; ?>
        <form action="/" method="POST">
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input name="email" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input name="password" type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
            </div>
            <button type="submit" class="btn btn-primary">Register User</button>
        </form>
    </div>
</body>
</html>