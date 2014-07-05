<?php

class NewsController extends Zend_Controller_Action {

	public function indexAction() {
		
		$this->view->headTitle ( 'Tin tức' );
		$page = $this->getRequest()->getParam('page');
		list($this->view->Pager, $this->view->News) = Contents::getAll(array('status=?'=>1, 'categories_id=?'=>0),$page, 10);
	}
	
	public function detailAction() {
		$News = Contents::getByNamePlain($this->getRequest()->getParam('id'));
		//print_r($this->getRequest()->getParams());die();
		$this->view->headTitle ( $News->title );
		$this->view->News = $News ;
		$this->view->NewsList = Contents::getWithOutPage(array('id!=?'=>$News->id,'categories_id!=?'=>1),5);
	}
	
}