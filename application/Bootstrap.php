<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

	protected function _initAutoLoad() {
		if ($_SERVER ['COMPANY'] != 'Hitech Solutions Co.,Ltd')
			die ( 'Unlicensed contact our at http://hitech-solutions.vn' );
		Zend_Layout::startMvc ();
		$front = Zend_Controller_Front::getInstance ();
		if ((IS_MOBILE === true) && ! isset ( $_SESSION ['IS_MOBILE'] )) {
			$_SESSION ['Q_MOBILE'] = true;
		}
		
		if (HAS_MOBILE === true) {
			if (! isset ( $_SESSION ['Q_MOBILE'] )) {
				$useragent = $_SERVER ['HTTP_USER_AGENT'];
				if (preg_match ( '/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od|ad)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent ) || preg_match ( '/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr ( $useragent, 0, 4 ) )) {
					//Mobile detected
					$_SESSION ['Q_MOBILE'] = true;
					$_SESSION ['IS_MOBILE'] = true;
				}
			}
		} else
			unset ( $_SESSION ['Q_MOBILE'] );
		
		if (isset ( $_SESSION ['Q_MOBILE'] ) && isset ( $_GET ['IS_MOBILE'] )) {
			if ($_GET ['IS_MOBILE'] == 0)
				$_SESSION ['Q_MOBILE'] = 0;
			else
				$_SESSION ['Q_MOBILE'] = 1;
		}
		//print_r($_SESSION);die;
		/**
		 * ************ Cacher config
		 */
		if (CACHER == 'MEMCACHE') {
			$frontendOpts = array ('caching' => true,'lifetime' => 1800,'automatic_serialization' => true );
			$backendOpts = array ('servers' => array (array ('host' => MEMCACHE_SERVER,'port' => 11211 ) ),'compression' => false );
			$cache = Zend_Cache::factory ( 'Core', 'Memcached', $frontendOpts, $backendOpts );
		} elseif (CACHER == 'FILE') {
			$frontend = array ('caching' => true,'lifetime' => 1800,'automatic_serialization' => true );
			$backend = array ('cache_dir' => APPLICATION_PATH . '/tmp' );
			$cache = Zend_Cache::factory ( 'core', 'File', $frontend, $backend );
		}
		Zend_Registry::set ( 'Cacher', $cache );
		if (CACHER_CLEAR === true)
			$cache->clean ();
		/**
		 * * End Cacher
		 */
		$front->registerPlugin ( new My_Plugin_Localizer () );
		
		if (! $Setting = Zend_Registry::get ( 'Cacher' )->load ( 'SETTING' )) {
			$Setting = ( object ) My_Plugin_Array::ArrayKeyValue ( 'id', 'value', Settings::getAll ()->toArray () );
			Zend_Registry::get ( 'Cacher' )->save ( $Setting, 'SETTING', array ('SETTING' ) );
		}
		Zend_Registry::set ( 'Setting', $Setting );
		
		//Load Router
		$Exclude = false;
		if (ROUTES_EXCLUDE_ADMIN === true) {
			$uri = trim ( strtolower ( $_SERVER ['REQUEST_URI'] ), '/' );
			$uri = explode ( '/', $uri );
			if (in_array ( $uri [0], array ('admin' ) ))
				$Exclude = true;
		}
		if (! $Exclude) {
			$router = $front->getRouter ();
			if ($_SESSION ['Q_MOBILE']) {
				$router_name = 'mobile';
				$router_path = APPLICATION_PATH . 'modules/mobile/configs/routes.xml';
			} else {
				$router_name = 'default';
				$router_path = APPLICATION_PATH . 'modules/default/configs/routes.xml';
			}
			
			if (ROUTES_CACHE) {
				if (! $routes = Zend_Registry::get ( 'Cacher' )->load ( 'ROUTER_' . $router_name )) {
					$routes = new Zend_Config_Xml ( $router_path, null );
					Zend_Registry::get ( 'Cacher' )->save ( $routes, 'ROUTER_' . $router_name, array ('SETTING' ) );
				}
			} else {
				$routes = new Zend_Config_Xml ( $router_path, null );
			}
			$router->addConfig ( $routes );
		}
	}

}

