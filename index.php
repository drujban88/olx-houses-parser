<?php
include('./regex.php');

ini_set('max_execution_time', 900);

/*  OLX -> недвижимость (string) */
$olx = get_page('https://www.olx.ua/nedvizhimost/');
$proxy_id = 0;
$proxy_size = count($proxies);

while(isset($olx) && $olx != ''){

    //$proxy_id = $proxy_id == $proxy_size ? 0 : $proxy_id;
    //$cur_proxy = $proxies[$proxy_id++];

    /*  Регулярное выражение для поиска ссылок на объявления  */
    $matches_site = array();

    /*  Заполнение массива $matches_site результатами поиска  $reg_ex_site в $olx  */
    preg_match_all(
        $reg_ex_site,
        $olx,
        $matches_site,
        PREG_PATTERN_ORDER
    );

    /*  Количество страниц для парсинга  */
    $ad_size = count($matches_site[1]);

    /*  Создание массивов для результатов парсинга объявления  */
    $matches_item_title = array();
    $matches_item_descripion = array();
    $matches_item_price = array();
    $matches_item_img = array();
    $matches_item_info = array(
        'fromWho' => array(),
        'flatType' => array(),
        'colRooms' => array(),
        'square' => array(),
        'lifeSquare' => array(),
        'kitchenSquare' => array(),
        'houseType' => array(),
        'floor' => array(),
        'numStoreys' => array()
    );

    /*  Заполнение массивов результатами парсинга объявления  */
    for($i = 0; $i < $ad_size; $i++){

        $page = get_page($matches_site[1][$i]/*, $cur_proxy*/);

        /*  Поиск описания  */
        preg_match_all(
            $reg_ex_title['desktop'],
            $page,
            $matches_item_title[$i],
            PREG_PATTERN_ORDER
        );
        preg_match_all(
            $reg_ex_description,
            $page,
            $matches_item_descripion[$i],
            PREG_PATTERN_ORDER
        );
        if(!isset($matches_item_title[$i][0][0])){
            preg_match_all(
                $reg_ex_title['mobile'],
                $page,
                $matches_item_title[$i],
                PREG_PATTERN_ORDER
            );
        }
        if(!isset($matches_item_title[$i][0][0])){
            preg_match_all(
                $reg_ex_title['label'],
                $page,
                $matches_item_title[$i],
                PREG_PATTERN_ORDER
            );
        }

        /*  Поиск цены  */
        preg_match_all($reg_ex_price, $page, $matches_item_price[$i], PREG_PATTERN_ORDER);

        /*  Поиск информации об объявлении  */
        preg_match_all($reg_ex_info['fromWho'], $page, $matches_item_info['fromWho'][$i], PREG_PATTERN_ORDER);
        preg_match_all($reg_ex_info['flatType'], $page, $matches_item_info['flatType'][$i], PREG_PATTERN_ORDER);
        preg_match_all($reg_ex_info['colRooms'], $page, $matches_item_info['colRooms'][$i], PREG_PATTERN_ORDER);
        preg_match_all($reg_ex_info['square'], $page, $matches_item_info['square'][$i], PREG_PATTERN_ORDER);
        preg_match_all($reg_ex_info['lifeSquare'], $page, $matches_item_info['lifeSquare'][$i], PREG_PATTERN_ORDER);
        preg_match_all($reg_ex_info['kitchenSquare'], $page, $matches_item_info['kitchenSquare'][$i], PREG_PATTERN_ORDER);
        preg_match_all($reg_ex_info['houseType'], $page, $matches_item_info['houseType'][$i], PREG_PATTERN_ORDER);
        preg_match_all($reg_ex_info['floor'], $page, $matches_item_info['floor'][$i], PREG_PATTERN_ORDER);
        preg_match_all($reg_ex_info['numStoreys'], $page, $matches_item_info['numStoreys'][$i], PREG_PATTERN_ORDER);

        /*  Поиск ссылок на картинки  */
        preg_match_all($reg_ex_img, $page, $matches_item_img[$i], PREG_PATTERN_ORDER);
    }

    /*  Теги, которые нужно удалить из описания  */
    $replace_tags = array('<br>', '<span>');

    /*  Удаление html разметки из описания и заполнение массива результатов  */
    for($i = 0; $i < $ad_size; $i++){
        $matches_item_descripion[$i][1][0] = strip_tags($matches_item_descripion[$i][1][0], $replace_tags);
        $matches_item_price[$i][1][0] = strip_tags($matches_item_price[$i][1][0], $replace_tags);

        $results[]= array(
            'name' => $matches_item_title[$i][1][0],
            'location' => $matches_item_title[$i][2][0],
            'date' => $matches_item_title[$i][3][0],
            'id' => $matches_item_title[$i][4][0],
            'price' => $matches_item_price[$i][1][0],
            'description' => $matches_item_descripion[$i][1][0],
            'fromWho' => isset($matches_item_info['fromWho'][$i][1][0])?$matches_item_info['fromWho'][$i][1][0]:'не указано',
            'flatType' => isset($matches_item_info['flatType'][$i][1][0])?$matches_item_info['flatType'][$i][1][0]:'не указано',
            'colRooms' => isset($matches_item_info['colRooms'][$i][1][0])?$matches_item_info['colRooms'][$i][1][0]:'не указано',
            'square' => isset($matches_item_info['square'][$i][1][0])?$matches_item_info['square'][$i][1][0]:'не указано',
            'lifeSquare' => isset($matches_item_info['lifeSquare'][$i][1][0])?$matches_item_info['lifeSquare'][$i][1][0]:'не указано',
            'kitchenSquare' => isset($matches_item_info['kitchenSquare'][$i][1][0])?$matches_item_info['kitchenSquare'][$i][1][0]:'не указано',
            'houseType' => isset($matches_item_info['houseType'][$i][1][0])?$matches_item_info['houseType'][$i][1][0]:'не указано',
            'floor' => isset($matches_item_info['floor'][$i][1][0])?$matches_item_info['floor'][$i][1][0]:'не указано',
            'numStoreys' => isset($matches_item_info['numStoreys'][$i][1][0])?$matches_item_info['numStoreys'][$i][1][0]:'не указано',
            'img1' => isset($matches_item_img[$i][1][0])?$matches_item_img[$i][1][0]:'не указано',
            'img2' => isset($matches_item_img[$i][1][1])?$matches_item_img[$i][1][1]:'не указано',
            'img3' => isset($matches_item_img[$i][1][2])?$matches_item_img[$i][1][2]:'не указано',
            'src' => $matches_site[1][$i]
        );
    }

    $tmp = null;
    preg_match_all($reg_ex_next_page, $olx, $tmp, PREG_PATTERN_ORDER);

    $olx = get_page($tmp[1][0]/*, $cur_proxy*/);

    //echo $olx;
    //echo $cur_proxy;
}

/* Сохранение результатов в csv */
$csv = fopen('result.csv', 'w');
//добавить UTF-8 в Excel
fputs($csv, $bom = ( chr(0xEF) . chr(0xBB) . chr(0xBF) ));

foreach ($results as $result)
    fputcsv($csv, $result);

fclose($csv);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  <title>OLX parser</title>
  <link rel="stylesheet" href="index.css">

</head>
<body>
  <table>
    <tbody>
      <tr id="labels">
        <td>Номер</td>
        <td>Заголовок</td>
        <td>Локация</td>
        <td>Дата публикации</td>
        <td>ID</td>
        <td class="price">Цена(грн/$)</td>
        <td>Описание</td>
        <td>Кто сдает</td>
        <td>Тип квартиры</td>
        <td>Количество комнат</td>
        <td>Площадь</td>
        <td>Жилая площадь</td>
        <td>Площадь кухни</td>
        <td>Тип дома</td>
        <td>Этаж</td>
        <td>Этажность</td>
        <td>Первая картинка</td>
        <td>Вторая картинка</td>
        <td>Третья картинка</td>
      </tr>
<?php
$id = 1;
foreach ($results as $result){
  echo('
      <tr>
        <td>'.$id++.'</td>
        <td><a href="'.$result['src'].'" target="_blank">'. $result['name'] .'</a></td>
        <td>'. $result['location'] .'</td>
        <td>'. $result['date'] .'</td>
        <td>'. $result['id'] .'</td>
        <td class="price"><p>'. $result['price'] .'</p></td>
        <td>'. $result['description'] .'</td>
        <td>'. $result['fromWho'] .'</td>
        <td>'. $result['flatType'] .'</td>
        <td>'. $result['colRooms'] .'</td>
        <td>'. $result['square'] .'</td>
        <td>'. $result['lifeSquare'] .'</td>
        <td>'. $result['kitchenSquare'] .'</td>
        <td>'. $result['houseType'] .'</td>
        <td>'. $result['floor'] .'</td>
        <td>'. $result['numStoreys'] .'</td>
        <td>'. (($result['img1'] == 'не указано')?$result['img1']:'<img src="'.$result['img1'].'">') .'</td>
        <td>'. (($result['img2'] == 'не указано')?$result['img2']:'<img src="'.$result['img2'].'">') .'</td>
        <td>'. (($result['img3'] == 'не указано')?$result['img3']:'<img src="'.$result['img3'].'">') .'</td>
    </tr>
  ');
}
?>
    </tbody>
  </table>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript" src="index.js"></script>
</body>
</html>
