<?php
class Lib_View_Helper_Hello extends Zend_View_Helper_Abstract {

	public function hello($name, $greeting) {
		return "hello $name! $greeting";
	}
}