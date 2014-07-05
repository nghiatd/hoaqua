<?php

class Admin_ContentController extends Zend_Controller_Action {
	function init() {
		$Member = new My_Plugin_Auth ( $this->getRequest () );
		$this->Member = $_SESSION ['Member'];
	}
	
	private function _checkForm($form) {
		$error = array ();
		if (empty ( $form ['title'] ))
			$error [] = 'Tiêu đề bài viết không thể để trống';
		if (empty ( $form ['detail'] ))
			$error [] = 'Chi tiết bài viết không thể để trống';
		return $error;
	}
	public function indexAction() {
		$this->view->Title = "Quản lý Bài viết";
		$this->view->headTitle ( $this->view->Title );
		
		list ( $this->view->Pager, $this->view->Content ) = Contents::getAll ();
	}
	public function createAction() {
		$this->view->Title = "Tạo mới Bài viết";
		$this->view->headTitle ( $this->view->Title );
		if ($this->getRequest ()->isPost ()) {
			$request = $this->getRequest ()->getParams ();
			//print_r($request);die;
			//checkform
			$error = $this->_checkForm ( $request );
			
			if (count ( $error ) == 0) {
				$Content = new Contents ();
				$Content->merge ( $request );
				$Content->title_plain = My_Plugin_Libs::text2url ( $Content->title );
				if ($Content->trySave ()) {
						$Content->created_date = date ( 'Y-m-d H:i:s' );
						$Content->save ();
						$this->Member->log ( 'Create Content:' . $Content->title . '(' . $Content->id . ')', 'Content' );
						My_Plugin_Libs::setSplash ( 'Bài viết: <b>' . $Content->title . '</b> đã được tạo. ' );
						//redirect to list
						$this->_redirect ( $this->_helper->url ( 'index', 'content', 'admin' ) );
					} else
						$error [] = 'Thao tác lưu dữ liệu không thành công. Xin hãy thử lại';

			}
			if (count ( $error ))
				$this->view->error = $error;
		}
	}
	
	public function editAction() {
		$this->view->Title = "Chỉnh sửa Bài viết";
		$this->view->headTitle ( $this->view->Title );
		$Content = Contents::getById ( $this->getRequest ()->getParam ( 'id' ) );
		if ($this->getRequest ()->isPost ()) {
			$request = $this->getRequest ()->getParams ();
			//checkform
			$error = $this->_checkForm ( $request );
			if (count ( $error ) == 0) {
				$Content->merge ( $request );
				$Content->title_plain = My_Plugin_Libs::text2url ( $Content->title );
				if ($Content->trySave ()) {
					$Content->save ();
					$this->Member->log ( 'Edit Content:' . $Content->title . '(' . $Content->id . ')', 'Content' );
					My_Plugin_Libs::setSplash ( 'Bài viết: <b>' . $Content->title . '</b> đã được sửa. ' );
					//redirect to list
					$this->_redirect ( $this->_helper->url ( 'index', 'content', 'admin' ) );
				} else
					$error [] = 'Thao tác lưu dữ liệu không thành công. Xin hãy thử lại';
			
			}
			if (count ( $error ))
				$this->view->error = $error;
		}
		$this->view->Content = $Content;
	}
	/**
	 * Delete a Country
	 */
	public function deleteAction() {
		$Content = Contents::getById ( $this->getRequest ()->getParam ( 'id' ) );
		if ($Content) {
			if ($this->getRequest ()->isPost ()) {
				
				$Content->delete ();
				$this->Member->log ( 'Delete content :' . $Content->title . '(' . $this->getRequest ()->getParam ( 'id' ) . ')', 'Content' );
				$this->_redirect ( $this->_helper->url ( 'index', 'content', 'admin' ) );
			}
			
			$this->view->Content = $Content;
		}
	}
}

