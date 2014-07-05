<?php

/**
 * Images
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Images extends BaseImages {
	
	private static $Status = array ('Disable', 'Enable' );
	public static $Location = array ('Hình ảnh', 'Sản phẩm', 'Bài viết' );
	public function getLoction(){
		return self::$Location[$this->location];
	}
	private function getImagesDir() {
		return ROOT_DIR . DATA_DIR . DIRECTORY_SEPARATOR . 'images';
	}
	public static function getAll($Condition = array(), $orderBy = "id DESC",$limit=0) {
		$Video = Doctrine_Query::create ()->from ( __CLASS__ )->orderBy ( $orderBy )->limit($limit);
		foreach ( $Condition as $key => $item ) {
			$Video->addWhere ( $key, $item );
		}
		
		return $Video->execute ();
	}
	public static function getById($id = 0) {
		return Doctrine_Query::create ()->from ( __CLASS__ )->where ( "id=?", $id )->execute ()->getFirst ();
	}
}