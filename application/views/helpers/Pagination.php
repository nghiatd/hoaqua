<?php

class App_View_Helper_Pagination extends Zend_View_Helper_Abstract {

	/**
	 * preDispatch function.
	 * 
	 * Define the layout path based on what module is being used.
	 *
	 * @author Aaron (bachya1208[at]googlemail.com)
	 * @access public
	 * @param Zend_Controller_Request_Abstract $request
	 * @return void
	 * <ul>
                	<li class="Activities"><a href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">Next >></a></li>
                </ul>
	 */
	public function Pagination($pager, $text = '', $Condition = array(), $style = 'simple') {
		
		unset ( $Condition ['page'] );
		unset ( $Condition ['msg'] );
		$_t = '';
		foreach ( $Condition as $key => $item ) {
			if (is_array ( $item ))
				foreach ( $item as $a => $b )
					$_t .= '&' . $key . '[' . $a . ']=' . $b;
			else
				$_t .= '&' . $key . '=' . $item;
		}
		$text = str_replace ( '{%Condition}', $_t, $text );
		
		$pager_layout = new Doctrine_Pager_Layout ( $pager, new Doctrine_Pager_Range_Sliding ( array ('chunk' => 10 ) ), $text );
		$pager_layout->setTemplate ( '<li><a href="{%url}">{%page}</a></li>' );
		$pager_layout->setSelectedTemplate ( '<li class="cur">{%page}</li>' );
		$str = '<ul> ';
		if ($pager->getPage () > 1)
			$str .= '<li><a href="' . str_replace ( '{%page}', $pager->getPreviousPage (), $text ) . '" class="pagepreview">Trang trước</a></li>';
		$str .= $pager_layout->display ( array (), true );
		if ($pager->getPage () < $pager->getLastPage ())
			$str .= '<li><a href="' . str_replace ( '{%page}', $pager->getNextPage (), $text ) . '" class="pagepreview">Trang sau</a></li>';
		
		//$str .= '</ul>';
		if ($style == 'advanced')
			$str .= '<br>' . 'Hiển thị ' . $pager->getFirstIndice () . ' - ' . $pager->getLastIndice () . ' của ' . $pager->getNumResults () . '/ ' . $pager->getLastPage () . ' trang';
		$str .= '<ul> ';
		return $str;
	}
}
