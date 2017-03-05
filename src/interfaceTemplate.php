<?php
namespace Pagination;

interface interfaceTemplate
{
    public function __construct($url);

    public function getHtml();
}