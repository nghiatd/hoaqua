<?php

class IndexController extends Zend_Controller_Action {

	public function indexAction() {
		$this->view->headTitle ( 'Trang chủ' );
		//$this->view->headMeta ()->appendName ( 'keywords', Zend_Registry::get('Setting')->KEYWORD)->appendName ( 'description', Zend_Registry::get('Setting')->DESCRIPTION );
		$this->view->Products = Products::getWithOutPage(array(),12);
	}
}