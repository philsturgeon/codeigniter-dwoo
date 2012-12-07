<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dwoo_test extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('parser');
	}
	
    function index()
    {
    	
    	$this->parser->parse('test', array('message' => 'Hello World!'));
    	
	}
}

?>