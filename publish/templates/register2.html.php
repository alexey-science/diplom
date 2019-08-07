
	<script type="text/javascript">
		function valid(form) {
			var fail = false;
			var name = form.name.value;
			var email = form.email.value;
			var password = form.password.value;
			var RePassword = form.RePassword.value;	
			var adr_pattern = /^[-._a-z0-9]+@(?:[a-z0-9][-a-z0-9]+\.)+[a-z]{2,6}$/i;
			
			if (name == "" || name == " ") 
				fail = "Вы не ввели свое имя";
			else if (adr_pattern.test(email) == false) 
				fail = "Вы ввели email некорректно";
			else if (password == "") 
				fail = "Вы не ввели пароль";
			else if (password != RePassword) 
				fail = "Пароли не совпадают";			
			if(fail)
				alert(fail);						
		}
		function changeRadio(){
			var urb = document.getElementById("user_rb");
			var orb = document.getElementById("org_rb");
			document.getElementById("user_reg").style.display = urb.checked?'block':'none';
			document.getElementById("org_reg").style.display = orb.checked?'block':'none';
		}
	</script>

<div><?php echo $params['mes'] ?></div>
	<form action="?registry" method="post" name="login/parol" id="form">
		<label for="name">Имя:</label>
		<input type="text" name="name" placeholder="Имя" id="name" /><br /><br />
		
		<label for="email">Email:</label>
		<input type="text" name="email" placeholder="Email" id="email" /><br /><br />

		<label for="">Выберите тип аккаунта:</label><br><br>
		<label onclick="changeRadio()"> <input type="radio" name="acctype" value="org" id="org_rb"> Организация</label>
		<label onclick="changeRadio()"> <input type="radio" name="acctype" value="user" id="user_rb"> Пользователь</label> <br><br>

		<div id="user_reg">
		<label>Введите ссылку на профиль Google Scholar: <input type="text" name="gsRef"></label> <br><br>
		<label>Введите id профиля Elibrary: <input type="text" name="elibRef"></label> <br><br>
		<label>Введите ссылку на  профиль в КиберЛеннка: <input type="text" name="clenRef"></label> <br><br>
		</div>

		<div id="org_reg">
			<label>Введите id профиля Elibrary: <input type="text" name="elibRef"></label> <br><br>
			<label>Введите полное наименование организации: <input type="text" name="clenRef"></label> <br><br>
		</div>

		<label for="password">Пароль:</label>
		<input type="password" name="pass" placeholder="Пароль" 
id="password" /><br /><br />
		<label for="RePassword">Проверка пароля:</label>
		<input type="password" name="RePassword" placeholder="Введите пароль" 
id="RePassword" /><br /><br />
		<input type="submit" onclick="valid(document.getElementById('form'))" name="Submit" value="Зарегистрироваться"/>
	</form>
