<?php require_once 'controllers/authController.php'; ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/0233149bfc.js" crossorigin="anonymous"></script>
    <title>Wyślij zadanie </title>
    <link rel="stylesheet" href="./styles/sendTask-styles/sentTask.css">
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
        <form class="form" method="post" action="sendTask.php">
            <h2 class="form__header">Wyślij zadanie <?php $_SESSION['username'] ?></h2>

            <?php if(count($errors) > 0): ?>
                <ul class="errorList">
                    <?php foreach($errors as $error): ?>
                        <li class="errorList__error"><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php 
                require_once 'vendor/autoload.php';
                require_once 'config/constants.php';

                if(isset($_POST['sendmail'])) {
                    // Create the Transport
                    $transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
                    ->setUsername(EMAIL)
                    ->setPassword(PASSWORD);
                    ;

                    // Create the Mailer using your created Transport
                    $mailer = new Swift_Mailer($transport);

                        // Create a message
                        global $mailer;

                        $emailFrom = $_SESSION['email'];
                        $emailContent = $_POST['message'];

                        $body = "
                            <div>$emailContent</div>
                            <div>Od: $emailFrom</div>
                        ";


                        $message = (new Swift_Message($_POST['subjectSendTask']))
                        ->setFrom(EMAIL)
                        ->setTo(EMAIL)
                        ->setBody($body, 'text/html');

                        // Send the message
                        $result = $mailer->send($message);
                }

            ?>

            <div class="formPart">
                <i class="far fa-envelope-open formPart__icon"></i>
                <input type="text" class="formPart__input" id="subject" name="subjectSendTask" placeholder="Temat wiadomości..." maxlength="50">
            </div>
            <div class="formPart">
                <i class="fas fa-unlock formPart__icon"></i>
                <textarea class="formPart__input" type="textarea" id="message" name="message" placeholder="Treść wiadomości..." maxlength="6000" rows="4"></textarea>
            </div>
            <div class="formPart">
                <i class="fas fa-lock formPart__icon"></i>
                <input name="fieldAttachment" multiple="multiple" class="formPart__input" type="file" width="10px" id="file">
            </div>
            <div class="formPart">
                <i class="fas fa-sign-in-alt formPart__icon"></i>
                <button class="formPart__button" type="submit" name="sendmail">Wyślij</button>
            </div>
        </form>
    </main>
</body>
</html>