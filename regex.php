<?php

// $proxies = array( 
//   '46.252.38.72:80', '61.135.217.21:80', '61.135.217.20:80',
//   '182.254.218.141:80', '111.202.159.79:80', '107.151.152.211:80',
//   '190.248.94.78:8080', '124.88.67.24:83', '124.88.67.34:81'
// );

/*  Регулярные выражения для парсинга объявления  */
$reg_ex_site = '/<a\s*href="(.+)"\s*class=".+"\s*>\s*<strong>\s*(.+)\s*<\/strong>\s*<\/a>/';

$reg_ex_title = array(
  'desktop' => '/<h1>\s*(.+)\s*<\/h1>\s*.*\s*<a\s*class.*><strong>(.*)<\/strong><\/a>\s*.*\s*Добавлено:\s*в(.*),\s*<small>.*:\s*(.*)<\/small>/i',
  'mobile' => '/<h1>\s*(.+)\s*<\/h1>\s*.*\s*<a\s*class.*><strong>(.*)<\/strong><\/a>\s*.*\s*.+\s*<a\s*href=.*>Опубликовано с мобильного<\/a>\s*в(.*),\s*<small>.*:\s*(.*)<\/small>/i',
  'label' => '/<h1>\s*<span\s*class=.*>.*<\/span>\s*(.+)\s*<\/h1>\s*.*\s*<a\s*class.*><strong>(.*)<\/strong><\/a>\s*.*\s*Добавлено:\s*в(.*),\s*<small>.*:\s*(.*)<\/small>/i'
);
$reg_ex_info = array(
  'fromWho' => '/<tr>\s*<th>Объявление от<\/th>\s*<td\s*class="value">\s*<strong>\s*<a\s*href.*>\s*(.*)\s*<\/a>/i',
  'flatType' => '/<tr>\s*<th>Тип квартиры<\/th>\s*<td\s*class="value">\s*<strong>\s*<a\s*href.*>\s*(.*)\s*<\/a>/i',
  'colRooms' => '/<tr>\s*<th>Количество комнат<\/th>\s*<td\s*class="value">\s*<strong>\s*(\d*)\s*<\/strong>/i',
  'square' => '/<tr>\s*<th>Общая площадь<\/th>\s*<td\s*class="value">\s*<strong>\s*(.*)<sup>/i',
  'lifeSquare' => '/<tr>\s*<th>Жилая площадь<\/th>\s*<td\s*class="value">\s*<strong>\s*(.*)<sup>/i',
  'kitchenSquare' => '/<tr>\s*<th>Площадь кухни<\/th>\s*<td\s*class="value">\s*<strong>\s*(.*)<sup>/i',
  'houseType' => '/<tr>\s*<th>Тип<\/th>\s*<td\s*class="value">\s*<strong>\s*<a\s*href.*>\s*(.*)/i',
  'floor' => '/<tr>\s*<th>Этаж<\/th>\s*<td\s*class="value">\s*<strong>\s*(\d*)/i',
  'numStoreys' => '/<tr>\s*<th>Этажность дома<\/th>\s*<td\s*class="value">\s*<strong>\s*(\d*)/i'
);
$reg_ex_description = '/<div\s*class="clr"\s*id="textContent">\s*<p\s*class="\w* \w* \w*">\s*(.{0,700}\.)/is';
$reg_ex_price = '/<div\s*class="price-label">\s*<strong\s*class="xxxx-large.+">([\d $грн\.]*)\s*<\/strong>/i';
$reg_ex_img = '/<div\s*class="tcenter img-item">\s*.*\s*<img\s*src="(.*)"\s*class/i';

$reg_ex_next_page = '/<span\s+class=".+ current">\s*<span>\d<\/span>\s*<\/span>\s*<\/span>\s*<span\s+class="item fleft">\s*<a\s+class=".*"\s*href="(.*)">/i';

/*  Функция для получения страницы по ссылке  */
function get_page($url/*, $proxy = '107.151.152.211:80'*/){
  return file_get_contents($url
  // , false, 
  //   stream_context_create(
  //     array(
  //       'ssl'=>array(
  //         'verify_peer'=>false,
  //         'verify_peer_name'=>false
  //       ),
  //       'http'=> array( 
  //         'proxy' => 'tcp://' . $proxy,
  //         'request_fulluri' => true
  //        )

  //   ))
    );
}