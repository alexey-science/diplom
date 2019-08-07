<?php
$qoutes = $params['quotes'];
$authors = $params['authors'];
$years = $params['years'];
$type = $params['type'];
$other = $params['otherInfo'];
$mainelib = $params['serv']['elib'];
$countart = 0;
$countq = 0;
foreach ($years as $items){
    $countart += $items['countart'];
}
foreach ($qoutes as $items){
    $countq += $items['countquotes'];
}
?>

<div class="tableInfo-block">

    <div class="ht-block">
        <p>Elibrary</p>
        <button class="btn-block export-block" onclick="getCSV('restable',  '<?=strip_tags($mainelib[0]['name'])?>');">Экспорт в CSV</button>
        <a id="result" href="" style="display: none"> Скачать</a>
    </div>
    <div id ="restable" class="tableWrapper">
        <table name="table" class="table-block">
            <tr class="select">
                <td> Имя: </td>
                <td> <?=$mainelib[0]['name']?></td>
            </tr>
   
            <tr class="select">
                <td>h-index </td>
                <td><?=$mainelib[0]['hindex'] ?></td>
            </tr>


            <?php if($other): ?>
                <tr class="select">
                    <td>Дополнительная информация: </td>
                    <td></td>
                </tr>
                <?php foreach($other as  $item): ?>
                <tr>
                    <td>
                       <?=$item['name_index'];?>
                    </td>
                    <td>
                        <?=$item['value_index'];?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>


            <tr class="select">
                <td>Всего статей: </td>
                <td><?=$countart ?></td>
            </tr>
              <?php if($type == 'user'): ?>

                <tr class="select">
                    <td>Всего цитирований: </td>
                    <td><?=$countq ?></td>
                </tr>
                    <tr class="select">
                    <td>Статистика по цитированию: </td>
                    <td></td>
                </tr>
                <tr>
                    <td>Год </td>
                    <td>Кол-во цитирований</td>
                </tr>
                <?php foreach ($qoutes as $item): ?>
                    <tr>
                        <td> <?= strip_tags($item['yearsquotes'])?></td>
                        <td> <?=$item['countquotes']?></td>
                    </tr>
                <?php endforeach; ?>

            <?php endif; ?>
            
            <tr class="select">
                <td>Статистика статей по годам: </td>
                <td></td>
            </tr>
            <tr>
                <td>Год </td>
                <td>Кол-во статей</td>
            </tr>
            <?php foreach ($years as $item): ?>
                <tr>
                    <td> <?=$item['years']?></td>
                    <td> <?=$item['countart']?></td>
                </tr>
            <?php endforeach; ?>

            <?php if($type == 'org'): ?>
                <tr class="select">
                    <td>Статистика по авторам: </td>
                    <td></td>
                </tr>

            <form method="post" action="index.php">
                <input type="hidden" name="grouping" value="<?='http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']?>"/>
                <tr>
                    <td><input type="submit" value="Объеденить"/></td>
                    <td>Автор </td>
                    <td>Кол-во статей</td>
                </tr>
                <?php foreach ($authors as $item): ?>
                    <tr>
                        <td><input type="checkbox" name="ids[]" value="<?=$item['id']?>"></td>
                        <td> <?= strip_tags($item['author'])?></td>
                        <td> <?=$item['countart']?></td>
                    </tr>
                <?php endforeach; ?>

            <?php endif; ?>
        </table>
    </div>
</div>