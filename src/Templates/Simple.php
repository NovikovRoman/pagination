<?php
namespace Pagination\Templates;

use Pagination\Template;
use Pagination\interfaceTemplate;

class Simple extends Template implements interfaceTemplate
{
    private $numMiddlePages;
    const DEFAULT_NUM_MIDDLE_PAGES = 5;

    private $templatePagePrev = '<li class="pagination-prev"><a href="#url#">&laquo;</a></li>';
    private $templatePageNext = '<li class="pagination-next"><a href="#url#">&raquo;</a></li>';

    public function __construct($url)
    {
        parent::__construct($url);
        $this->setMiddlePages(self::DEFAULT_NUM_MIDDLE_PAGES);
    }

    /**Установим шаблон для пред. страницы
     * @param string $templatePagePrev
     * @return $this
     */
    public function setTemplatePagePrev($templatePagePrev)
    {
        $this->templatePagePrev = $templatePagePrev;
        return $this;
    }

    /**Установим шаблон для пред. страницы
     * @param string $templatePageNext
     * @return $this
     */
    public function setTemplatePageNext($templatePageNext)
    {
        $this->templatePageNext = $templatePageNext;
        return $this;
    }

    /**Сколько страниц в середине.
     * Например 5 будет выглядеть так (6 -текущая страница):
     * « 4 5 <6> 7 8 »
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
        $endLinePage = $endLinePage > $this->numPages ? $this->numPages : $endLinePage;

        if ($this->currentPage > 1) {
            $html[] = str_replace('#url#', $this->getUrl(), $this->templatePagePrev);
        }

        do {
            if ($startLinePage == $this->currentPage) {
                $html[] = str_replace('#page#', $this->currentPage, $this->templateCurrentPage);
            } else {
                $str = str_replace('#page#', $startLinePage, $this->templatePage);
                if ($startLinePage > 1) {
                    $url = $this->getUrl([$this->pageName => $startLinePage]);
                } else {
                    $url = $this->getUrl();
                }
                $str = str_replace('#url#', $url, $str);
                $html[] = $str;
            }
            $startLinePage++;
        } while ($startLinePage <= $endLinePage);

        if ($endLinePage < $this->numPages) {
            $url = $this->getUrl([$this->pageName => $endLinePage + 1]);
            $html[] = str_replace('#url#', $url, $this->templatePageNext);
        }

        return str_replace('#pages#', implode('', $html), $this->templateWrapper);
    }
}