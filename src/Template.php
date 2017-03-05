<?php
namespace Pagination;

/**Задаем основные шаблоны-обертки
 * #url# - сюда вставится сгенерированный url
 * #pages# - сюда вставятся все страницы
 * #page# - сюда вставится номер страницы
 * Class Template
 * @package Pagination
 */
abstract class Template extends Pagination
{
    protected $templateWrapper = '<nav><ul class="pagination">#pages#</ul></nav>';
    protected $templatePage = '<li><a href="#url#">#page#</a></li>';
    protected $templateCurrentPage = '<li class="active"><span>#page#</span></li>';

    /**Задаем шаблон для общей обертки пагинации
     * @param $templateWrapper
     * @return $this
     */
    public function setTemplateWrapper($templateWrapper)
    {
        $this->templateWrapper = $templateWrapper;
        return $this;
    }

    /**Задаем шаблон для обертки ссылки на страницу
     * @param $templatePage
     * @return $this
     */
    public function setTemplatePage($templatePage)
    {
        $this->templatePage = $templatePage;
        return $this;
    }

    /**Задаем шаблон для обертки текущей страницы
     * @param $templateCurrentPage
     * @return $this
     */
    public function setTemplatePageCurrent($templateCurrentPage)
    {
        $this->templateCurrentPage = $templateCurrentPage;
        return $this;
    }
}