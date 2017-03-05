<?php

namespace Pagination;

class Pagination
{
    protected $currentPage;
    protected $totalItems;
    protected $numPages;
    protected $pageSize;
    protected $url;
    protected $pageName;

    public function __construct($url)
    {
        $this->setCurrentPage(1);
        $this->setPageSize(10);
        $this->setTotal(0);
        $this->setUrl($url);
        $this->setName('page');
    }

    /**Установим текущую страницу
     * @param integer $currentPage > 0
     * @return $this
     */
    public function setCurrentPage($currentPage)
    {
        $this->currentPage = (int)abs($currentPage) ?: 1;
        return $this;
    }

    /**Установим кол-во элементов(статей) на странице
     * @param integer $pageSize > 0
     * @return $this
     */
    public function setPageSize($pageSize)
    {
        $this->pageSize = (int)abs($pageSize) ?: 1;
        return $this;
    }

    /**Общее кол-во элементов(статей)
     * @param $total
     * @return $this
     */
    public function setTotal($total)
    {
        $this->totalItems = (int)abs($total);
        $this->numPages = ceil($this->totalItems / $this->pageSize);
        return $this;
    }

    /**Установим имя GET-параметра,
     * в котором будет отправляться номер страницы
     * @param string $pageName
     * @return $this
     */
    public function setName($pageName)
    {
        $this->pageName = $pageName;
        $this->deleteParamQuery($pageName);
        return $this;
    }

    /**Установим url страницы где будем выводить пагинацию
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = parse_url($url);
        if (isset($this->url['query']) && $this->url['query']) {
            parse_str($this->url['query'], $this->url['query']);
        } else {
            $this->url['query'] = [];
        }
        $this->setName($this->pageName);
        return $this;
    }

    /**Удалим ненужный GET-параметр из url
     * Тот который не нужен на других страницах
     * @param string $param
     * @return $this
     */
    public function deleteParamQuery($param)
    {
        if (isset($this->url['query'][$param])) {
            unset($this->url['query'][$param]);
        }
        return $this;
    }

    /**Удалим массив ненужных GET-параметров из url
     * @param array $params
     * @return $this
     */
    public function deleteArrayParamsQuery($params)
    {
        foreach ($params as $param) {
            $this->deleteParamQuery($param);
        }
        return $this;
    }

    /**Получим сформированный url (можно с доп. параметрами)
     * @param array $params
     * @return string
     */
    protected function getUrl($params = [])
    {
        $url = $this->url;
        if (count($params)) {
            if (isset($url['query'])) {
                $url['query'] = array_merge_recursive($url['query'], $params);
            } else {
                $url['query'] = $params;
            }
        }
        return $this->unparseUrl($url);
    }

    /**Собираем url, разбитый parse_url в строку
     * @param $url
     * @return string
     */
    private function unparseUrl($url)
    {
        $scheme = isset($url['scheme']) ? $url['scheme'] . '://' : '';
        $host = isset($url['host']) ? $url['host'] : '';
        $port = isset($url['port']) ? ':' . $url['port'] : '';
        $user = isset($url['user']) ? $url['user'] : '';
        $pass = isset($url['pass']) ? ':' . $url['pass'] : '';
        $pass = ($user || $pass) ? $pass . '@' : '';
        $path = isset($url['path']) ? $url['path'] : '';
        $query = '';
        if (isset($url['query']) && count($url['query'])) {
            $query = '?' . http_build_query($url['query']);
        }
        $fragment = isset($url['fragment']) ? '#' . $url['fragment'] : '';
        return implode('', [
            $scheme, $user, $pass, $host, $port, $path, $query, $fragment
        ]);
    }
}