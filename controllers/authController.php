<?php
session_start();

require 'config/db.php';
require_once 'emailController.php';

$errors = array();
$username = '';
$email = '';

if(isset($_POST['signup-btn'])) {
    $username =  trim(htmlspecialchars($_POST['username']));
    $email = trim(htmlspecialchars($_POST['email']));
    $password = trim(htmlspecialchars($_POST['password']));
    $passwordConf = trim(htmlspecialchars($_POST['passwordConf']));

    if(empty($username)){
        $errors['username'] = "Login jest wymagany!";
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors['username'] = "Email jest niepoprawny!";
    }

    if(empty($email)){
        $errors['email'] = "Email jest wymagany!";
    }

    if(empty($password)){
        $errors['password'] = "Hasło jest wymagane!";
    }

    if($password !== $passwordConf) {
        $errors['password'] = 'Hasła nie są jednakowe!';
    }

    $emailQuery = "SELECT * FROM users WHERE email=? OR username=? LIMIT 1";
    $stmt = $conn-> prepare($emailQuery);
    $stmt->bindParam(1, $email, PDO::PARAM_STR);
    $stmt->bindParam(2, $username, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $userCount = $result;

    if($userCount > 0) {
        $errors['email'] = "Email/Nazwa już znajduje się w bazie!";
    }

    if( 7 < strlen($username) || strlen($username) <= 15 ) {
        $errors['username'] = "Login musi mieć od 8 do 15 znaków";
    }

    if( 7 < strlen($password) || strlen($password) <= 25 ) {
        $errors['password'] = "Hasło musi mieć od 8 do 25 znaków";
    }

    if (count($errors) === 0) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $token = bin2hex(random_bytes(50));
        $verified = false;
        $adminVerified = false;

        $sql = "INSERT INTO users (username, email, verified, token, password, adminVerified) VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn-> prepare($sql);
        $stmt->bindParam(1, $username, PDO::PARAM_STR);
        $stmt->bindParam(2, $email, PDO::PARAM_STR);
        $stmt->bindParam(3, $verified, PDO::PARAM_STR);
        $stmt->bindParam(4, $token, PDO::PARAM_STR);
        $stmt->bindParam(5, $password, PDO::PARAM_STR);
        $stmt->bindParam(6, $adminVerified, PDO::PARAM_STR);
        #$stmt->bindParam('ssbssb', $username, $email, $verified, $token, $password, $adminVerified);
        if ($stmt->execute()){
            //login user
            $user_id = $conn->insert_id;
            $_SESSION['id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['verified'] = $verified;
            $_SESSION['adminVerified'] = $adminVerified;

            sendVerificationEmail($email, $token);

            header('location: index.php');
            exit();
        } else {
            $errors['db_error'] = "Database error: filed to register";
        }
    }

}

if(isset($_POST['login-btn'])) {
    $username = trim(htmlspecialchars($_POST['username']));
    $password = trim(htmlspecialchars($_POST['password']));

    if(empty($username)){
        $errors['username'] = "Login jest wymagany!";
    }

    if(empty($password)){
        $errors['password'] = "Hasło jest wymagane!";
    }

    if(count($errors) === 0) {

        $sql = "SELECT * FROM users WHERE email=? OR username=? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1, $username, PDO::PARAM_STR);
        $stmt->bindParam(2, $username, PDO::PARAM_STR);
        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            #$user = $result->fetch_assoc();
            $user = $result;
            if(password_verify($password, $user['password'])){
                $_SESSION['id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['verified'] = $user['verified'];
                $_SESSION['adminVerified'] = $user['adminVerified'];
    
                $_SESSION['message'] = "You are now logged in";
                $_SESSION['alert-class'] = "alert-success";
                header('location: index.php');
                exit();
            } else {
                $errors['login_fail'] = "Wrong credentials";
            }
        } else {
            $_SESSION['message'] = "Database error. Login failed!";
            $_SESSION['type'] = "alert-danger";
        }  
    }
}


//logout user

if(isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['id']);
    unset($_SESSION['username']);
    unset($_SESSION['email']);
    unset($_SESSION['verified']);
    unset($_SESSION['adminVerified']);
    header('location: login.php');
    exit();
}


function verifyUser($token){
    global $conn;
    $sql = "SELECT * FROM users WHERE token='$token' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0 ){
        $user = mysqli_fetch_assoc($result);
        $update_query = "UPDATE users SET verified=1 WHERE token='$token'";

        if(mysqli_query($conn, $update_query)) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['verified'] = 1;
            $_SESSION['adminVerified'] = 0;

            $_SESSION['message'] = "Your email adress was successfully verified";
            $_SESSION['alert-class'] = "alert-success";
            header('location: index.php');
            exit();
        }
    } else {
        echo 'User not found';
    }
}

// Jesli uzytkownik zapomni hasła

if(isset($_POST['forgot-password'])) {
    $email = $_POST['email'];

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Email address is invalid";
    }
    if(empty($email)) {
        $errors['email'] = "Email required";
    }

    if (count($errors) == 0) {
        $sql = "SELECT * FROM users WHERE email='$email' LIMIT 1";
        $result = mysqli_query($conn, $sql);
        $user = mysqli_fetch_assoc($result);
        $token = $user['token'];
        sendPasswordResetLink($email, $token);
        header('location: login.php?user=true');
        exit(0);
    }
}

if (isset($_POST['reset-password-btn'])) {
    $password = $_POST['password'];
    $passwordConf = $_POST['passwordConf'];

    if(empty($password) || empty($passwordConf)){
        $errors['password'] = "Hasło jest wymagane!";
    }

    if($password !== $passwordConf) {
        $errors['password'] = 'Hasła nie są jednakowe!';
    }

    $password = password_hash($password, PASSWORD_DEFAULT);
    $email = $_SESSION['email'];

    if (count($errors) == 0) {
        $sql = "UPDATE users SET password=? WHERE email=?";
        $result = $conn-> prepare($sql);
        $result->execute(array($password, $email));
        if($result) {
            header('location: login.php');
            exit(0);
        }
    }
}

function resetPassword($token) {
    global $conn;
    $sql = "SELECT * FROM users WHERE token='$token' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
    $_SESSION['email'] = $user['email'];
    header('location: reset_password.php');
    exit(0);
}