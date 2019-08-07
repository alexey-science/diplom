<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Вход</title>
</head>
<body>
    <div id="login" class="login-block">
    <p> <?=$params['mes'] ?> </p>

    <form action="?login" method="POST">

        <Label  for="email"> Email: </Label><br>
        <input class="input-block" type="text" name="email" placeholder="Email" id="email">
        <br>
        <Label for="pass"> Пароль: </Label><br>
        <input class="input-block" type="password" name="pass" id="pass" placeholder="Пароль">
        <br>
        <input class="btn-block" type="submit" value="Войти">    

    </form>
    </div>
</body>
</html>