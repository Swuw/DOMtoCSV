<?php

include 'simple_html_dom.php';

?>

<html>
<head>
    <title>Download resourse</title>
</head>
<body>
<h1>Download</h1>


<?php
$url = "http://estoremedia.space/DataIT/";
$i = 1;
$n = 0;
$urlProduct = "index.php?page=";

$html = file_get_html($url);
$countPages = $html->find('li.page-item');

foreach($countPages as $countPage) {
    $value['datapage'] = (int) $countPage->find('a', 0)->plaintext;
    $values[] = $value;
}

$value = max($values);
$numberOfPages = $value['datapage'];

while ( $i <= $numberOfPages ){

    $allHTML = file_get_html($url.$urlProduct."$i");
    $blocks = $allHTML->find('div.card');

    foreach($blocks as $block) {
        $item['href'] = $url.$block->find('h4 a', 0)->href;
        $items[] = $item;
    }

        $i++;
}

$countItems = count($items);

while ( $n <= ($countItems - 1) ){

    $urlProductPage = $items[$n]['href'];
    $productHTML = file_get_html("$urlProductPage");
    $productElements = $productHTML->find('div.col-lg-9');

    foreach($productElements as $productElement) {
        $product['title'] = $productElement->find('p.card-text', 0)->plaintext;
        $product['url_product'] = $urlProductPage;
        $product['image'] = $productElement->find('img.card-img-top', 0)->src;
        $product['action_price'] = $productElement->find('span.price-promo', 0)->plaintext;
        $product['regular_price'] = !empty($productElement->find('del.price-old', 0)->plaintext) ? $productElement->find('del.price-old', 0)->plaintext : $productElement->find('span.price', 0)->plaintext;
        $ratingArray = preg_split('/ /', $productElement->find('small.text-muted', 0)->plaintext);
        $product['count_rewiews'] = str_replace(['(', ')'], '', (!empty($ratingArray[1]) ? $ratingArray[1] : ''));
        $product['rating'] = substr_count((!empty($ratingArray[0]) ? $ratingArray[0] : ''), '&#9733;').'/5';
        $products[] = $product;
    }

    $n++;
}

$head = [
        'Title',
        'Url_product',
        'Url_image',
        'Action_price',
        'Regular_price',
        'Count_rewiews',
        'Rating',
];

array_unshift($products, $head);

$file = fopen('Data.csv', 'w');
foreach ($products as $product){
    fputcsv($file, $product);
}
fclose($file);

echo 'Done!'

?>

</body>
</html>

