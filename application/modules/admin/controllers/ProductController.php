<?php

class Admin_ProductController extends Zend_Controller_Action {
	
	function init() {
		$Member = new My_Plugin_Auth ( $this->getRequest () );
		$this->Member = $_SESSION ['Member'];
	}
	
	private function _checkForm($form) {
		$error = array ();
		if (empty ( $form ['title'] ))
			$error [] = 'Tên sản phẩm không thể để trống';
		if ($form ['product_categories_id'] == 0)
			$error [] = 'Bạn hãy chọn danh mục sản phẩm';
		return $error;
	}
	
	private function _uploadFiles($uploadDir = '') {
		if (! is_dir ( $uploadDir )) {
			@mkdir ( $uploadDir );
			chmod ( $uploadDir, 0777 );
		}
		//upload thumb image
		$image = new Zend_Form_Element_File ( 'image' );
		$image->setDestination ( $uploadDir );
		$image->addValidator ( 'Count', false, 1 );
		$image->addValidator ( 'Extension', false, 'jpg,png,gif' );
		$image->addFilter ( 'Rename', array ('target' => $uploadDir . DIRECTORY_SEPARATOR . 'thumb.jpg', 'overwrite' => true ) );
		$image->receive ();
		//uploads images for gallery
		$images = new Zend_Form_Element_File ( 'images' );
		$images->setDestination ( $uploadDir );
		//$element->addValidator ( 'Size', false, 512000 );
		$images->addValidator ( 'Extension', false, 'jpg,png,gif' );
		$images->setMultiFile ( count ( $_POST ['images'] ) );
		$images->receive ();
		
		return $images->getFileName ( null, false );
	}
	
	/**
	 * List destination
	 */
	public function indexAction() {
		$this->view->Title = "Quản lý sản phẩm";
		$this->view->headTitle ( $this->view->Title );
		//Filter
		$condition = array ();
		$filter = array ();
		if ($this->getRequest ()->getParam ( 'product_categories_id' )) {
			$filter ['product_categories_id'] = $this->getRequest ()->getParam ( 'product_categories_id' );
			$condition ['product_categories_id=?'] = $filter ['product_categories_id'];
		}
		if ($this->getRequest ()->getParam ( 'keyword' ))
			$condition ['title LIKE ?'] = "%{$this->getRequest ()->getParam ( 'keyword' )}%";

		if (($this->getRequest ()->getParam ( 'status' )) != '') {
			$filter ['status'] = $this->getRequest ()->getParam ( 'status' );
			$condition ['status=?'] = $filter ['status'];
		}		
		
		//print_r($this->getRequest()->getParams());
		$this->view->filter = $filter;
		$page = $this->getRequest ()->getParam ( 'page' );
		list ( $this->view->Pager, $this->view->Products ) = Products::getAll ( $condition, $page );
	
	}
	
	/**
	 * Create
	 */
	public function createAction() {
		
		$this->view->Title = "Tạo mới sản phẩm";
		$this->view->headTitle ( $this->view->Title );
		if ($this->getRequest ()->isPost ()) {
			$request = $this->getRequest ()->getParams ();
			//checkform
			$error = $this->_checkForm ( $request );
			if (count ( $error ) == 0) {
				$Products = new Products ();
				$Products->merge ( $request );
				$Products->title_plain = My_Plugin_Libs::text2url ( $Products->title );
				$Products->created_date = date ( 'Y-m-d H:i:s' );
				if ($Products->trySave ()) {
					if ($_FILES) {
						$uploadDir = "uploads".DIRECTORY_SEPARATOR."product".DIRECTORY_SEPARATOR.$Products->id.DIRECTORY_SEPARATOR;
						$images = $this->_uploadFiles ( $uploadDir );
					}
					if ($request ['caption'])
						if (count ( $request ['caption'] ) == 1) {
							$curent_images [] = array ('caption' => pos ( $request ['caption'] ), 'file' => $images );
						} else {
							foreach ( $request ['caption'] as $key => $item ) {
								$curent_images [] = array ('caption' => $item, 'file' => $images ['images_' . $key . '_'] );
							}
						}
					$Products->updateImages ( $curent_images );	
					
					$this->Member->log ( 'Create Products: ' . $Products->title . '(' . $Products->id . ')', 'Products' );
					My_Plugin_Libs::setSplash ( 'Sảm phẩm:<b>' . $Products->title . '</b> đã được tạo' );
					//redirect to list
					$this->_redirect ( $this->_helper->url ( 'index', 'product', 'admin' ) );
				} else
					$error [] = 'Thao tác lưu dữ liệu không thành công. Xin hãy thử lại';
			}
			if (count ( $error ))
				$this->view->error = $error;
		}
	}
	
	/**
	 * Edit
	 */
	public function editAction() {
		$Products = Products::getById ( $this->getRequest ()->getParam ( 'id' ) );
		$id = $this->Member->id;
		$this->view->Title = "Sửa sản phẩm " . $Products->title;
		$this->view->headTitle ( $this->view->Title );
		if ($this->getRequest ()->isPost ()) {
			$request = $this->getRequest ()->getParams ();
			$curent_images = $Products->getImages ();
			$remove = ( array ) $request ['remove'];
			if (count ( $remove )) {
				$Products->removeImages ( $remove );
			}
			#Cập nhật lại kho ảnh hiện tại
			foreach ( $curent_images as $key => $image ) {
				if (in_array ( $image ['file'], $remove )) {
					unset ( $curent_images [$key] );
				} else {
					$curent_images [$key] ['caption'] = $request ['caption'] [$key];
				}
				unset ( $request ['caption'] [$key] );
			}
			#Upload thêm ảnh
			if ($_FILES) {
				$uploadDir =  "uploads".DIRECTORY_SEPARATOR."product".DIRECTORY_SEPARATOR.$Products->id.DIRECTORY_SEPARATOR;
				$images = $this->_uploadFiles ( $uploadDir );
			}
			
			if ($request ['caption'])
				if (count ( $request ['caption'] ) == 1) {
					$curent_images [] = array ('caption' => pos ( $request ['caption'] ), 'file' => $images );
				} else {
					foreach ( $request ['caption'] as $key => $item ) {
						$file = $images ['images_' . $key . '_'];
						//echo $file;
						if ($file)
							$curent_images [] = array ('caption' => $item, 'file' => $file );
					}
				}
			$Products->updateImages ( $curent_images );
			
			$error = $this->_checkForm ( $request );
			if (count ( $error ) == 0) {
				$Products->merge ( $request );
				$Products->title_plain = My_Plugin_Libs::text2url ( $Products->title );
				$Products->created_date = date ( 'Y-m-d H:i:s' );			
				if ($Products->trySave ()) {
					$Products->save ();
					$this->Member->log ( 'Edit Products:' . $Products->title . '(' . $Products->id . ')', 'Products' );
					My_Plugin_Libs::setSplash ( 'Sản phẩm <b>' . $Products->title . '</b> đã được lưu lại thành công' );
					
					$this->_redirect ( $this->_helper->url ( 'index', 'product', 'admin' ) );
				} else
					$error [] = 'Thao tác lưu dữ liệu không thành công. Xin hãy thử lại';
			}
			if (count ( $error ))
				$this->view->error = $error;
		}
		$this->view->Products = $Products;
	}
	
	public function deleteAction() {
		$Products = Products::getById ( $this->getRequest ()->getParam ( 'id' ) );
		if ($Products) {
			if ($this->getRequest ()->isPost ()) {
				$name = $Products->title;
				$Products->delete ();
				$this->Member->log ( 'Delete Products:' . $name . '(' . $this->getRequest ()->getParam ( 'id' ) . ')', 'Products' );
				$this->_redirect ( $this->_helper->url ( 'index', 'product', 'admin' ) );
			}
			$this->view->Products = $Products;
		}
	}
}

