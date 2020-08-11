<?php
session_start();

if(isset($_POST['title'])){
    require '../config/db.php';

    $title = $_POST['title'];
    $id_user = $_SESSION['id'];

    if(empty($title)){
        header("Location: ../index.php?mess=error");
    }else {
        $stmt = $conn->prepare("INSERT INTO tasks (title, id_user) VALUES (?, ?)");
        $res = $stmt->execute([$title, $id_user]);

        if($res){
            header("Location: ../index.php?mess=success"); 
        }else {
            header("Location: ../index.php");
        }
        $conn = null;
        exit();
    }
}else {
    header("Location: ../index.php?mess=error");
}