<?php require_once 'controllers/authController.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/0233149bfc.js" crossorigin="anonymous"></script>
    <title>Reset Password</title>
    <link rel="stylesheet" href="./styles/login-styles/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body>
    <form class="form" method="post" action="reset_password.php" >

        <h2 class="form__header">Reset Password</h2>

        <?php if(count($errors) > 0): ?>
            <ul class="errorList">
                <?php foreach($errors as $error): ?>
                    <li class="errorList__error"><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

            
        <div class="formPart">
            <i class="fas fa-unlock formPart__icon"></i>
            <input class="formPart__input" type="password" name="passwordReset" placeholder="Wpisz hasło..."/>
        </div>

        <div class="formPart">
            <i class="fas fa-unlock formPart__icon"></i>
            <input class="formPart__input" type="password" name="passwordResetConf" placeholder="Wpisz hasło..."/>
        </div>
            
        <div class="formPart">
            <i class="fas fa-sign-in-alt formPart__icon"></i>
            <button class="formPart__button" type="submit" name="reset-password-btn">Reset Password</button>
        </div>

    </form>
    
</body>
</html>