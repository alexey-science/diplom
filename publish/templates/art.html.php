<?php
    $query = $params['query'];
    $si = $params['serv'];
    $type = $params['type'];
?>
<div class="popup-block" id="popup">
    <div class="form-block">
        <form action="?reg_uq" method="post" >
		<label for="name">Имя:</label> <br>
		<input class="input-block" type="text" name="name" placeholder="Имя" id="name" /><br /><br />
		
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

		<input  type="submit" class="btn-block" name="Submit" value="Добавить"/>
	    <input type="button" class="btn-block" value="Отмена" onclick="close_PopUP()">
    </form>
    </div>    
</div>
<div class="acinfo-block">
    <div class="acc-block">
        <div class="mainacc-block">
            <a class="text-acc" href="?getInfo&uq_id=<?php echo $query[0][0]['id'] ?>"><?php echo strip_tags($query[0][0]['name']); ?></a>
            <a href="?update&uq_id=<?php echo $query[0][0]['id'] ?>"  class="update-block"></a>
        </div>
        <div class="addacc-block">
            <button onclick="show_PopUP()" class="addbtnacc">Добавить аккаунт</button>
        </div>
        <?php foreach ($query[1] as $item): ?>
           <div class="mainacc-block">
                <a href="?del&uq_id=<?php echo $item['id'] ?>"  class="del-block"></a>
                <a class="text-acc" href="?getInfo&uq_id=<?php echo $item['id'] ?>"><?php echo strip_tags($item['name']); ?></a>
                <a href="?update&uq_id=<?php echo $item['id'] ?>"  class="update-block"></a>
        </div>
        <?php endforeach ?>
    </div>
    <div class="info-block">
        <?php if($type=='user'): ?>
        <div class="linkinfo">
            <p class="header">Google Scholar</p>
            <?php foreach ($si['gs'] as $item): ?>
                <p class="link-row"><a href="?getStat&type=<?=$type?>&service=gs&sid=<?=$item['id']?>"><?=strip_tags($item['name']);?> </a>Дата: <?=strip_tags($item['dateq']);?> </p>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <div class="linkinfo">
            <p class="header">Elibrary</p>
            <?php foreach ($si['elib'] as $item): ?>
                <p class="link-row"><a href="?getStat&type=<?=$type?>&service=elib&sid=<?=$item['id']?>"><?=strip_tags($item['name']);?> </a> Дата: <?=strip_tags($item['dateq']);?> </p>
            <?php endforeach; ?>
        </div>
        <div class="linkinfo">
            <p class="header">КиберЛенинка</p>
            <?php foreach ($si['clen'] as $item): ?>
                <p class="link-row"><a href="?getStat&type=<?=$type?>&service=clen&sid=<?=$item['id']?>"><?=strip_tags($item['name']);?> </a> Дата: <?=strip_tags($item['dateq']);?> </p>
            <?php endforeach; ?>
        </div>
    </div>
</div>