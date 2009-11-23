<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dwoo_test extends Controller {

    function index()
    {
    	
    	$this->parser->parse('test', array('message' => 'OH HAI!'));
    	
	}
}

?>