<?php
$authors = $params['authors'];
$years = $params['years'];
$type = $params['type'];
$mainclen = $params['serv']['clen'];
$other = $params['otherInfo'];
$countart = 0;
foreach ($years as $items){
    $countart += $items['countart'];
}
?>

<div class="tableInfo-block">

    <div class="ht-block">
        <p>КиберЛенинка</p>
        <button class="btn-block export-block" onclick="getCSV('restable',  '<?=strip_tags($mainclen[0]['name'])?>');">Экспорт в CSV</button>
        <a id="result" href="" style="display: none"> Скачать</a>
    </div>
    <div id ="restable" class="tableWrapper">
        <table name="table" class="table-block">
            <tr class="select">
                <td> Имя: </td>
                <td> <?=$mainclen[0]['name']?></td>
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
            <?php if($type == 'user'): ?>
                <tr>
                    <td>Загрузок: </td>
                    <td><?=$mainclen[0]['downloads']?></td>
                </tr>
                <tr>
                    <td>Просмотров: </td>
                    <td><?=$mainclen[0]['views']?></td>
                </tr>
                <tr>
                    <td>В избранном: </td>
                    <td><?=$mainclen[0]['fav']?></td>
                </tr>
            <?php endif; ?>
            <tr class="select">
                <td>Всего статей: </td>
                <td><?=$countart ?></td>
            </tr>
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
                <tr>
                    <td>Автор </td>
                    <td>Кол-во статей</td>
                </tr>
                <?php foreach ($authors as $item): ?>
                    <tr>
                        <td> <?= strip_tags($item['author'])?></td>
                        <td> <?=$item['countart']?></td>
                    </tr>
                <?php endforeach; ?>

            <?php endif; ?>
        </table>
    </div>
</div>