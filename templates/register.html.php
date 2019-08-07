<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
    <title>Document</title>
</head>
<body>
    
  <div class="login-block">
  <div><?php echo $params['mes'] ?></div>
	<form action="?registry" method="post" name="login/parol" id="form">
		<label for="name">Имя:</label> <br>
		<input class="input-block" type="text" name="name" placeholder="Имя" id="name" /><br /><br />
		
		<label for="email">Email:</label> <br>
		<input class="input-block" type="text" name="email" placeholder="Email" id="email" /><br /><br />

		<label for="">Выберите тип аккаунта:</label><br><br>
		<label onclick="changeRadio()"> <input  checked type="radio" name="acctype" value="org" id="org_rb"> Организация</label>
		<label onclick="changeRadio()"> <input  type="radio" name="acctype" value="user" id="user_rb"> Пользователь</label> <br><br>

		<div id="inputs-block">
		<div id="user_reg">
		<label>Введите ссылку на профиль Google Scholar: <br> 
        <input class="input-block" type="text" name="gsRef"  placeholder="Ссылка Google Scholar"></label> 
        <br><br>
        </div>
		<label>Введите id профиля Elibrary: <br>
        <input class="input-block" type="text" name="elibRef" placeholder="id Elibrary"></label> 
        <br><br>
		<label for="clenInput" id="textClen">Введите полное наименование организации: </label><br>
        <input class="input-block" id="clenInput" type="text" name="clenRef"  placeholder="КиберЛенинка"> <br><br>
		</div>
		
		<label for="password">Пароль:</label><br>
		<input class="input-block" type="password" name="pass" placeholder="Пароль" 
        id="password" /><br /><br />
		<label for="RePassword">Проверка пароля:</label><br>
		<input class="input-block" type="password" name="RePassword" placeholder="Введите пароль" 
         id="RePassword" /><br /><br />
		<input  type="submit" class="btn-block" onclick="valid(document.getElementById('form'))" name="Submit" value="Зарегистрироваться"/>
	</form>
  </div>
</body>
</html>

