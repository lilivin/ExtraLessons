<?php 

    include 'controllers/authController.php';

    if(isset($_GET['token'])) {
        $token = $_GET['token'];
        verifyUser($token);
    }

    if(isset($_GET['password-token'])) {
        $passwordToken = $_GET['password-token'];
        resetPassword($passwordToken);
    }

    if(!isset($_SESSION['id'])){
        header('location: login.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/0233149bfc.js" crossorigin="anonymous"></script>
    <title>Wyślij zadanie </title>
    <link rel="stylesheet" href="./styles/index-styles/index.css">
    <link rel="stylesheet" href="./styles/navigation-styles/navigation.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body>
<main class="mainSection">
        <nav class="navigation">
            <h3 class="navigation__header">Cześć, <?php echo $_SESSION['username']; ?>!</h3>
            <div>
                <a class="navigation__element" href="index.php">Strona Główna</a>
                <a class="navigation__element" href="sendTask.php">Wyślij zadanie</a>
                <a class="navigation__logOut" href="index.php?logout=1">Wyloguj się</a>
            </div>
        </nav>
        

        <?php if(!$_SESSION['verified']): ?>
            <div class="verifiedAlert">
                Na Twój adres email powinen przyjść email z linkiem aktywacyjnym. Kliknij w niego, aby móc korzystać z funkcjonalności konta.
                Twój adres email: <strong><?php echo $_SESSION['email']; ?></strong>
            </div>
        <?php endif; ?>

        <?php if(!$_SESSION['adminVerified'] && $_SESSION['verified']): ?>
            <p class="verifiedAlert">Niedługo administrator zatwierdzi Twoje konto.</p>
        <?php endif; ?>

        <?php if($_SESSION['adminVerified'] && $_SESSION['verified']): ?>
            <div class="mainScreen">
                <div class="tasks">
                    <h1 class="header">Zadania</h1>
                    <div class="add-section">
                        <form class="addTaskForm" action="./controllers/add.php" method="POST" autocomplete="off">
                            <?php if(isset($_GET['mess']) && $_GET['mess'] == 'error'){ ?>
                                <input type="text" 
                                    name="title" 
                                    placeholder="Wpisz..."
                                    class="addTaskForm__input" />
                            <button class="addTaskForm__button" type="submit">Dodaj</button>

                            <?php }else{ ?>
                            <input type="text" 
                                    name="title" 
                                    placeholder="Wpisz..."
                                    class="addTaskForm__input" />
                            <button class="addTaskForm__button" type="submit">Dodaj</button>
                            <?php } ?>
                        </form>
                    </div>
                    <?php
                        $todos = $conn->query("SELECT * FROM tasks ORDER BY id DESC");
                        $result = $conn-> prepare("SELECT * FROM users WHERE id=? LIMIT 1");
                    ?>
                    <div class="toDoItems">
                        <?php while($todo = $todos->fetch(PDO::FETCH_ASSOC)) { ?>
                            <?php if($_SESSION['id'] == $todo['id_user'] || $_SESSION['id'] == 1): ?>
                                <div class="toDoItem">
                                        <h2 class="toDoItem__title"><?php echo $todo['title'] ?></h2>
                                        <i id="<?php echo $todo['id']; ?>" class="far fa-trash-alt remove"></i>
                                        <p class="toDoItem__date">Stworzono: <?php echo $todo['data_time'] ?> </p>  
                                        <?php 
                                        if($_SESSION['id']==1){
                                            $result->execute(array($todo['id_user']));
                                            $user = $result->fetch(PDO::FETCH_ASSOC);
                                            $student = $user['username'];
                                            echo "<p class='toDoItem__student'>Uczeń: $student</p>";
                                        }
                                        ?>
                                </div>
                            <?php endif ?>
                        <?php } ?>
                    </div>
                </div>
                <div class="lessons">
                    <?php
                        $topics = $conn->query("SELECT * FROM lessons ORDER BY data");
                    ?>
                    <h1 class="header">Zaplanowane lekcje</h1>
                    <?php while($topic = $topics->fetch(PDO::FETCH_ASSOC)) { ?>
                        <?php if($_SESSION['id'] == $topic['id_user'] || $_SESSION['id'] == 1): ?>
                            <div class="lesson">
                                <h1 class="lesson__title"><?php echo $topic['title']; ?></h1>
                                <p class="lesson__data"><?php echo $topic['data']; ?></p>
                                <?php 
                                if($_SESSION['id']==1){
                                    $result->execute(array($topic['id_user']));
                                    $user = $result->fetch(PDO::FETCH_ASSOC);
                                    $student = $user['username'];
                                    echo "<p class='lesson__student'>Uczeń: $student</p>";
                                }
                                ?>
                            </div>
                            
                        <?php endif ?>
                    <?php } ?>
                </div>
                <div class="doneTasks">
                    <h1 class="header">Zadania Wykonane</h1>
                </div>
                <div class="challanges">
                    <h1 class="header">Wyzwania</h1>
                </div>
            </div>
        <?php endif; ?>
    </main>

<script src="jquery-3.2.1.min.js"></script>
<script>

$(document).ready(function(){
    $('.remove').click(function(){
        const id = $(this).attr('id');
        
        $.post("controllers/remove.php",
              {
                  id: id
              },
              (data)  => {
                 if(data){
                    console.log('cos');
                     $(this).parent().hide(600);
                 }
              }
        );
    });
});                        

</script>
</body>
</html>