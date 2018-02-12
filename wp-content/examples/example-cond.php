<?php // example: conditional loading

if(is_admin() ) {
  require_once(dirname(__FILE__) . '/admin/do-stuff.php');
}




?>
