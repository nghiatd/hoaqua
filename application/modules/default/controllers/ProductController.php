<?php

class ProductController extends Zend_Controller_Action {
	
	public function indexAction() {
		
		$id = $this->getRequest()->getParam('id');
		//print_r($this->getRequest()->getParams());die();
		
		if($id)
			{
				$Categories = ProductCategories::getByNamePlain($id);
				
				$condition ['product_categories_id=?'] = $Categories->id;
				
				$this->view->id = $id ;
				$this->view->Categories = $Categories;
				$this->view->headTitle ( $Categories->title );
			}	
			else $this->view->headTitle ( 'Sản phẩm' );
		list($this->view->Pager, $this->view->Products) = Products::getAll($condition, $page);
	}
	
	public function detailAction() {
		$Product = Products::getByNamePlain($this->getRequest()->getParam('id'));
		$this->view->headTitle ( $Product->title );
		$this->view->Product = $Product;
		$this->view->Cate = $Product->ProductCategories;
		$this->view->ListPro = Products::getWithOutPage(array('status=?'=>1,'product_categories_id=?'=>$Product->product_categories_id,'id!=?'=>$Product->id),3);
	}
	
	public function searchAction() {
		$this->view->headTitle ( 'Tìm kiếm' );
		if($this->getRequest()->isPost()){
			$keyword = $this->getRequest()->getParam('keyword');
			$condition = array ('status=?'=>1);
			if ($keyword) {
				$condition ['title LIKE ? OR detail LIKE ? OR description LIKE ?'] =array("%{$keyword}%", "%{$keyword}%", "%{$keyword}%");
			}
			$page = $this->getRequest()->getParam('page');
			list($this->view->Pager, $this->view->Products) = Products::getAll($condition, $page);
		}

	}
	
}