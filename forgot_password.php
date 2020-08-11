<?php require_once 'controllers/authController.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/0233149bfc.js" crossorigin="anonymous"></script>
    <title>Reset hasła</title>
    <link rel="stylesheet" href="./styles/login-styles/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body>
    <form class="form" method="post" action="forgot_password.php" >

        <h2 class="form__header">Reset hasła</h2>

        <p>Wprowadz adres email, abyśmy mogli wysłać na niego email do resetu hasła</p>

        <?php if(count($errors) > 0): ?>
            <ul class="errorList">
                <?php foreach($errors as $error): ?>
                    <li class="errorList__error"><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
            
        <div class="formPart">
            <i class="fas fa-unlock formPart__icon"></i>
            <input class="formPart__input" type="email" name="email" placeholder="Wpisz ema..."/>
        </div>
            
        <div class="formPart">
            <i class="fas fa-sign-in-alt formPart__icon"></i>
            <button class="formPart__button" type="submit" name="forgot-password">Resetuj hasło</button>
        </div>
    </form>
    
</body>
</html>