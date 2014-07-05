<?php

class Admin_AdvController extends Zend_Controller_Action {
	
	function init() {
		$Member = new My_Plugin_Auth ( $this->getRequest () );
		$this->Member = $_SESSION ['Member'];
	}
	
	public function indexAction() {
		$this->view->Title = "List advs";
		$this->view->headTitle ( $this->view->Title );
		$condition = array();
		$filter = array ();
		if ($this->getRequest ()->getParam ( 'domain' )) {
			$filter ['domain'] = $this->getRequest ()->getParam ( 'domain' );
			$condition ['domains_id=?'] = $filter['domain'];
		}
		if ($this->getRequest ()->getParam ( 'location' )) {
			$filter ['location'] = $this->getRequest ()->getParam ( 'location' );
			$condition ['location=?'] = $filter['location'];
		}
		$this->view->filter = $filter;
		$page = $this->getRequest ()->getParam ( 'page' );
		list ( $this->view->Pager, $this->view->Adv ) = Adv::getAll ( $condition, $page, 10 );
	
	}
	
	public function createAction() {
		$this->view->Title = "Create adv";
		$this->view->headTitle ( $this->view->Title );
		
		if ($this->getRequest ()->isPost ()) {
			$request = $this->getRequest ()->getParams ();
			$Adv = new Adv() ;
			$Adv->merge ( $request );
			$Adv->save ();
			$this->Member->log ( 'Create adv: ' . $Adv->link . '(' . $Adv->id . ')', 'Adv' );
			My_Plugin_Libs::setSplash ( ' Adv :<b>' . $Adv->link . '</b> was created' );
			//redirect to list
			$this->_redirect ( $this->_helper->url ( 'index', 'adv', 'admin' ) );

		}
		$dir = 'uploads/adv';
		$this->view->Adv = self::getFileDir ( $dir );
	}

	public function editAction() {
		$Adv = Adv::getById ( $this->getRequest ()->getParam ( 'id' ) );		
		$this->view->Title = "Edit adv " . $Adv->link;
		$this->view->headTitle ( $this->view->Title );
		if ($this->getRequest ()->isPost ()) {
			$request = $this->getRequest ()->getParams ();
			$Adv->merge ( $request );	
			$Adv->save ();
			$this->Member->log ( 'Edit adv: ' . $Adv->link . '(' . $Adv->id . ')', 'Adv' );
			My_Plugin_Libs::setSplash ( 'Adv <b>' . $Adv->link . '</b> has been saved successfully' );
					
			$this->_redirect ( $this->_helper->url ( 'index', 'adv', 'admin' ) );

		}
		$this->view->Adv = $Adv;
		$dir = 'uploads/adv';
		$this->view->Advs = self::getFileDir ( $dir );
	}
	
	public function deleteAction() {
		$this->view->Title = "Delete adv";
		$this->view->headTitle ( $this->view->Title );
		$Adv = Adv::getById ( $this->getRequest ()->getParam ( 'id' ) );
		if ($Adv) {
			$name = $Adv->link;		
			if ($this->getRequest ()->isPost ()) {
				$Adv->delete();
				$this->Member->log ( 'Delete adv: ' . $name . '(' . $this->getRequest ()->getParam ( 'id' ) . ')', 'Adv' );
				rmdir ( $dir );
				//redirect to list
				$this->_redirect ( $this->_helper->url ( 'index', 'adv', 'admin' ) );
			}
			$this->view->Adv = $Adv;
		}
	
	}
	private function getFileDir($dir) {
		
		$Adv = scandir ( $dir );
		foreach ( $Adv as $key => $file ) {
			if (in_array ( $file, array ('.', '..', '.svn', 'thumb.db' ) )) {
				unset ( $Adv [$key] );
				continue;
			}
			$Adv [$key] = My_Plugin_Libs::fileDetail ( $dir . '/' . $file );
		}
		return $Adv;
	}
	
	public function advfileAction() {
		$this->view->Title = "Advertising library";
		$this->view->headTitle ( $this->view->Title );
		$dir =  'uploads/adv';
		if ($this->getRequest ()->isPost ()) {
			$images = new Zend_Form_Element_File ( 'images' );
			$images->setDestination ( $dir );
			// $element->addValidator ( 'Size', false, 512000 );
			$images->addValidator ( 'Extension', false, 'jpg,png,gif,swf' );
			$images->setMultiFile ( count ( $_POST ['images'] ) );
			$images->receive ();
		}
		if ($this->getRequest ()->getParam ( 'delete' )) {
			@unlink ( $dir . '/' . $this->getRequest ()->getParam ( 'delete' ) );
		}
		$this->view->Adv = self::getFileDir ( $dir );
	}
	
	
	
}

