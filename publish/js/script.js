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
			if(urb.checked){
			document.getElementById("user_reg").style.display ='flex';
			document.getElementById("textClen").innerText= "Введите ссылку на  профиль в КиберЛенинка: ";
			}else if(orb.checked){
			document.getElementById("user_reg").style.display ='none';
			document.getElementById("textClen").innerText = "Введите полное наименование организации: ";
				
			}
			
		}

function show_PopUP(){
	document.getElementById("popup").style.display = "flex";
}

function close_PopUP(){
	document.getElementById("popup").style.display = "none";
}
      
function getCSV(form_id, namef){
	jQuery.ajax({
		url:      "index.php?getCSV&name=" + namef, //Адрес подгружаемой страницы
		type:     "POST", //Тип запроса
		dataType: "html", //Тип данных
		data: 'texth='+document.getElementById(form_id).innerHTML,
		success: function(response) { //Если все нормально
			$('a#result').attr('href',response);
			document.getElementById('result').style.display = 'inline-block';
		},
		error: function(response) { //Если ошибка
			document.getElementById('result').style.display = 'inline-block';
			document.getElementById('result').innerHTML = "Ошибка при отправке формы";
		}
	});
}       