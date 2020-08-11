<?php require_once 'controllers/authController.php'; ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarejestruj się</title>
    <link rel="stylesheet" href="./styles/register-styles/register.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/0233149bfc.js" crossorigin="anonymous"></script>
</head>
<body>

    <form class="form" method="post" action="register.php">
        <h2 class="form__header">Rejestracja</h2>

        <?php if(count($errors) > 0): ?>
            <ul class="errorList">
                <?php foreach($errors as $error): ?>
                    <li class="errorList__error"><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <div class="formPart">
            <i class="fas fa-user formPart__icon"></i>
            <input class="formPart__input" type="text" name="username" pattern="[a-zA-Z0-9]{1,}" title="Nie używaj znaków specjalnych" placeholder="Wpisz login..." value="<?php echo $username; ?>"/>
        </div>
        <div class="formPart">
            <i class="far fa-envelope-open formPart__icon"></i>
            <input class="formPart__input" type="text" name="email" placeholder="Wpisz email..." value="<?php echo $email; ?>"/>
        </div>
        <div class="formPart">
            <i class="fas fa-unlock formPart__icon"></i>
            <input class="formPart__input" type="password" pattern="[a-zA-Z0-9]{1,}" title="Nie używaj znaków specjalnych" name="password" placeholder="Wpisz hasło..."/>
        </div>
        <div class="formPart">
            <i class="fas fa-lock formPart__icon"></i>
            <input class="formPart__input" type="password" name="passwordConf" placeholder="Powtórz hasło..."/>
        </div>
        <div class="formPart">
            <i class="fas fa-sign-in-alt formPart__icon"></i>
            <button class="formPart__button" type="submit" name="signup-btn">Zarejestruj się!</button>
        </div>
        <p class="form__register">
            Już masz konto? <a href='login.php'>Zaloguj się!</a>
        </p>
    </form>
</body>
</html>