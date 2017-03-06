<?php
require_once __DIR__ . '/vendor/autoload.php';
use Pagination\Templates\Hellip;
use Pagination\Templates\Simple;

$currentPage = 7;
$total = 200;
$url = '/news/articles/2017/';

/* template Hellip ------------------------------------------------------------------- */
/* 1 … 5 6 <7> 8 9 … 20 */
$pagen = new Hellip($url);
$pagen->setTotal($total)->setCurrentPage($currentPage);
// $pagen->setPageSize(10); // the default
// $pagen->setMiddlePages(5); // the default
echo $pagen->getHtml();
echo '<hr>';

/* template Simple ------------------------------------------------------------------- */
/* «  5 6 <7> 8 9  » */
$pagen = new Simple($url);
$pagen->setTotal($total)->setCurrentPage($currentPage);
// $pagen->setPageSize(10); // the default
// $pagen->setMiddlePages(5); // the default
echo $pagen->getHtml();
echo '<hr>';