<?php require_once 'controllers/authController.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/0233149bfc.js" crossorigin="anonymous"></script>
    <title>Zaloguj się</title>
    <link rel="stylesheet" href="./styles/login-styles/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body>
    <form class="form" method="post" action="login.php" >

        <h2 class="form__header">Zaloguj się</h2>

        <?php if(count($errors) > 0): ?>
            <ul class="errorList">
                <?php foreach($errors as $error): ?>
                    <li class="errorList__error"><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <div class="formPart">
            <i class="fas fa-user-tie formPart__icon"></i>
            <input class="formPart__input" type="text" name="username" placeholder="Wpisz login..." value="<?php echo $username; ?>"/>
        </div>
            
        <div class="formPart">
            <i class="fas fa-unlock formPart__icon"></i>
            <input class="formPart__input" type="password" name="password" placeholder="Wpisz hasło..."/>
        </div>
            
        <div class="formPart">
            <i class="fas fa-sign-in-alt formPart__icon"></i>
            <button class="formPart__button" type="submit" name="login-btn">Zaloguj</button>
        </div>

        <p class="form__register">
            Jeszcze nie uczysz się z nami? <a href='register.php'>Zarejestruj się!</a>
        </p>
        <div>
            <a href="forgot_password.php">Zapomniałeś hasła?</a>
        </div>
    </form>
    
</body>
</html>