<?php

$dbh = new PDO('mysql:host=localhost;dbname=wordpress', 'root', 'root');
$dbh->exec('SET NAMES utf8');

$mess='';
$count = 1;
if(!empty($_POST['dt'])){
    $data = "WHERE Сurs_val_data LIKE '{$_POST['dt']}'";
}else{
    $data = "WHERE Сurs_val_data LIKE '".date('d.m.Y')."'";
}
$res = $dbh->query("SELECT Сurs_val_data FROM curs_val {$data}");
$row_dt = $res->fetch(PDO::FETCH_OBJ);
if($res->rowCount()==0){
    $mess = "<p class='bg-success'  style='padding:10px 20px; background-color: #ff0000;'>Ошибка ввода даты или нет данных</p>";
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
</head>
<body>
<div class="container" style="padding:40px">
    <div class="bs-callout bs-callout-warning" id="callout-navbar-overflow">
        <h3 id="overflowing-content">Foreign Currency Market</h3>
        <p>Введите дату:</p>
    </div>
    <div>
        <form action="/parser_price.php" method="POST" enctype="multipart/form-data">
            <input type="text" size="17" style="padding: 4px;" name="dt" placeholder="<?=$row_dt->Сurs_val_data?>" value="<?=$_POST['dt']?>" >
            <input class="btn btn-success" type="submit" name="search" value="Найти" style="margin-bottom: 2px;">
        </form>
    </div>
    <div style="text-align:right; padding:0 0 10px; ">Текущая дата: <?=$row_dt->Сurs_val_data?></div>
   <?=$mess?>
    <table class="table table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>NumCode</th>
            <th>CharCode</th>
            <th>Nominal</th>
            <th>Name</th>
            <th>Value</th>
        </tr>
        </thead>
        <tbody>
        <?php $res = $dbh->query("SELECT * FROM curs_val {$data}");
                while ($row = $res->fetch(PDO::FETCH_OBJ)){  ?>
            <tr>
                <td><strong><?=$count++?></strong></td>
                <td><?=$row->NumCode?></td>
                <td><?=$row->CharCode?></td>
                <td><?=$row->Nominal?></td>
                <td><?=$row->Name?></td>
                <td><?=$row->Value?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    </div>
</body>
</html>