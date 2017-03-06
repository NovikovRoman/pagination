<?php
namespace Pagination\Templates;

use Pagination\Template;
use Pagination\interfaceTemplate;

class Hellip extends Template implements interfaceTemplate
{
    private $numMiddlePages;
    const DEFAULT_NUM_MIDDLE_PAGES = 5;

    private $templatePageHellip = '<li><a href="#url#">&hellip;</a></li>';

    public function __construct($url)
    {
        parent::__construct($url);
        $this->setMiddlePages(self::DEFAULT_NUM_MIDDLE_PAGES);
    }

    /**Установим шаблон для многоточия
     * @param string $templatePageHellip
     * @return $this
     */
    public function setTemplatePageHellip($templatePageHellip)
    {
        $this->templatePageHellip = $templatePageHellip;
        return $this;
    }

    /**Сколько страниц в середине.
     * Например 5 будет выглядеть так (6 -текущая страница):
     * 1 ... 4 5 <6> 7 8 ... 25
     * @param integer $num
     * @return $this
     */
    public function setMiddlePages($num)
    {
        $this->numMiddlePages = (int)$num ?: self::DEFAULT_NUM_MIDDLE_PAGES;
        return $this;
    }

    /**Собираем html
     * @return string
     */
    public function getHtml()
    {
        $html = [];
        $startLinePage = $this->currentPage - intval($this->numMiddlePages / 2);
        $startLinePage = $startLinePage > 0 ? $startLinePage : 1;
        $endLinePage = $startLinePage + $this->numMiddlePages - 1;
        if ( $endLinePage > $this->numPages) {
            $endLinePage = $this->numPages;
            if ($endLinePage - $startLinePage < $this->numMiddlePages) {
                $startLinePage = $endLinePage - $this->numMiddlePages + 1;
                $startLinePage = $startLinePage > 0 ? $startLinePage : 1;
            }
        }
        if ($this->currentPage > 1 && $startLinePage > 1) {
            $str = str_replace('#page#', 1, $this->templatePage);
            $url = $this->getUrl();
            $str = str_replace('#url#', $url, $str);
            $html[] = $str;
            if ($startLinePage > 2) {
                $url = $this->getUrl([$this->pageName => round($startLinePage / 2)]);
                $html[] = str_replace('#url#', $url, $this->templatePageHellip);
            }
        }

        do {
            if ($startLinePage == $this->currentPage) {
                $html[] = str_replace('#page#', $this->currentPage, $this->templateCurrentPage);
            } elseif ($startLinePage == 1) {
                $str = str_replace('#page#', $startLinePage, $this->templatePage);
                $str = str_replace('#url#', $this->getUrl(), $str);
                $html[] = $str;
            } else {
                $str = str_replace('#page#', $startLinePage, $this->templatePage);
                $url = $this->getUrl([$this->pageName => $startLinePage]);
                $str = str_replace('#url#', $url, $str);
                $html[] = $str;
            }
            $startLinePage++;
        } while ($startLinePage <= $endLinePage);

        if ($this->currentPage < $this->numPages && $endLinePage < $this->numPages) {
            if ($endLinePage < $this->numPages - 1) {
                $page = round($endLinePage + ($this->numPages - $endLinePage) / 2);
                $url = $this->getUrl([$this->pageName => $page]);
                $html[] = str_replace('#url#', $url, $this->templatePageHellip);
            }
            $str = str_replace('#page#', $this->numPages, $this->templatePage);
            $url = $this->getUrl([$this->pageName => $this->numPages]);
            $str = str_replace('#url#', $url, $str);
            $html[] = $str;
        }
        return str_replace('#pages#', implode('', $html), $this->templateWrapper);
    }
}