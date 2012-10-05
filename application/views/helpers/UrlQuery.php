<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UrlQuery
 *
 * @author = "piter";
 */
class Zend_View_Helper_UrlQuery extends Zend_View_Helper_Url
{
    public function urlQuery(array $urlOptions = array(), $name = null, $reset = false, $encode = true, array $params = array(), $reset_params = false)
    {
        $url = $this->url($urlOptions, $name, $reset, $encode);
        $params = $reset_params ? $params : $params + Zend_Controller_Front::getInstance()->getRequest()->getQuery();
        $url = empty($params) ? $url : sprintf('%s?%s', $url, http_build_query($params, '', '&amp;'));

        return $url;
    }
}
?>
