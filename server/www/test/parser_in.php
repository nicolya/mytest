<?php
/**
 * Created by PhpStorm.
 * User: nicolya
 * Date: 20.10.15
 * Time: 17:19
 */

################# коннекты и настройки #####################
$dbh = new PDO('mysql:host=localhost;dbname=wordpress', 'root', 'root');
$dbh->exec('SET NAMES utf8');
############################################################

$data   = file_get_contents("http://www.cbr.ru/scripts/XML_daily.asp");
$count=0;
$dt = '';
if(!empty($data)){
    $file    = simplexml_load_string($data);

    $attrs = $file->attributes();
    $dt = $attrs["Date"];
    // проверка на существование даты
    $res = $dbh->query("SELECT Сurs_val_data
                                            FROM curs_val
                                            WHERE Сurs_val_data LIKE '".$attrs["Date"]."' ");
    if($res->rowCount()==0){
        foreach ($file->Valute as $curs) {
            // добавляем курсы валют
            $dbh->query("INSERT INTO curs_val (NumCode, CharCode, Nominal, Name, Value, Сurs_val_data)
                                    VALUE ( " . $curs->NumCode . "
                                            , '" . $curs->CharCode . "'
                                            , "  . $curs->Nominal . "
                                            , '" . $curs->Name . "'
                                            , '" . $curs->Value . "'
                                            , '" . $attrs["Date"] . "' ) ");
            $count++;
        }
    }
}
echo "Обновилось на дату: ".$dt."</br> Всего: ".$count;