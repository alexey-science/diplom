<?php
$qoutes = $params['quotes'];
$years = $params['years'];
$type = $params['type'];
$maings = $params['serv']['gs'];
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
        <p>Google Scholar</p>
        <button class="btn-block export-block" onclick="getCSV('restable',  '<?=strip_tags($maings[0]['name'])?>');">Экспорт в CSV</button>
        <a id="result" href="" style="display: none"> Скачать</a>
    </div>
    <div id ="restable" class="tableWrapper">
        <table class="table-block">
            <tr class="select">
                <td> Имя: </td>
                <td> <?=$maings[0]['name']?></td>
            </tr>

            <tr class="select">
                <td>h-index </td>
                <td><?=$maings[0]['hindex'] ?></td>
            </tr>
            <tr class="select">
                <td>Всего статей: </td>
                <td><?=$countart ?></td>
            </tr>
            <tr class="select">
                <td>Всего цитирований: </td>
                <td><?=$countq ?></td>
            </tr>

            <?php if($type == 'user'): ?>
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


        </table>
    </div>
</div>