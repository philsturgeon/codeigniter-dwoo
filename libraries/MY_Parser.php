<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * CodeIgniter Dwoo Parser Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Parser
 * @license     http://philsturgeon.co.uk/code/dbad-license
 * @link		http://philsturgeon.co.uk/code/codeigniter-dwoo
 */


class MY_Parser extends CI_Parser {

	private $_ci;
	private $_dwoo;
	private $_parser_compile_dir = '';
	private $_parser_cache_dir = '';
	private $_parser_cache_time = 0;
	private $_parser_allow_php_tags = array();
	private $_parser_allowed_php_functions = array();
	private $_parser_assign_refs = array();

	function __construct($config = array())
	{
		if ( ! empty($config))
		{
			$this->initialize($config);
		}

		$this->_ci = & get_instance();
		$this->_dwoo = self::spawn();
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize preferences
	 *
	 * @access	public
	 * @param	array
	 * @return	void
	 */
	function initialize($config = array())
	{
		foreach ($config as $key => $val)
		{
			$this->{'_' . $key} = $val;
		}
	}

	// --------------------------------------------------------------------

	function spawn()
	{
		// Main Dwoo object
		$dwoo = new Dwoo;

		// The directory where compiled templates are located
		$dwoo->setCompileDir($this->_parser_compile_dir);
		$dwoo->setCacheDir($this->_parser_cache_dir);
		$dwoo->setCacheTime($this->_parser_cache_time);

		// Security
		$security = new MY_Security_Policy;

		$security->setPhpHandling($this->_parser_allow_php_tags);
		$security->allowPhpFunction($this->_parser_allowed_php_functions);

		$dwoo->setSecurityPolicy($security);

		return $dwoo;
	}

	// --------------------------------------------------------------------

	/**
	 *  Parse a view file
	 *
	 * Parses pseudo-variables contained in the specified template,
	 * replacing them with the data in the second param
	 *
	 * @access	public
	 * @param	string
	 * @param	array
	 * @param	bool
	 * @return	string
	 */
	function parse($template, $data = array(), $return = FALSE, $is_include = FALSE)
	{
		$string = $this->_ci->load->view($template, $data, TRUE);

		return $this->_parse($string, $data, $return, $is_include);
	}

	// --------------------------------------------------------------------

	/**
	 *  String parse
	 *
	 * Parses pseudo-variables contained in the string content,
	 * replacing them with the data in the second param
	 *
	 * @access	public
	 * @param	string
	 * @param	array
	 * @param	bool
	 * @return	string
	 */
	function string_parse($string, $data = array(), $return = FALSE, $is_include = FALSE)
	{
		return $this->_parse($string, $data, $return, $is_include);
	}

	function parse_string($string, $data = array(), $return = FALSE, $is_include = FALSE)
	{
		return $this->_parse($string, $data, $return, $is_include);
	}

	// --------------------------------------------------------------------

	/**
	 *  Parse
	 *
	 * Parses pseudo-variables contained in the specified template,
	 * replacing them with the data in the second param
	 *
	 * @access	public
	 * @param	string
	 * @param	array
	 * @param	bool
	 * @return	string
	 */
	function _parse($string, $data, $return = FALSE, $is_include = FALSE)
	{
		// Start benchmark
		$this->_ci->benchmark->mark('dwoo_parse_start');

		// Convert from object to array
		if ( ! is_array($data))
		{
			$data = (array) $data;
		}

		$data = array_merge($data, $this->_ci->load->_ci_cached_vars);

		foreach ($this->_parser_assign_refs as $ref)
		{
			$data[$ref] = & $this->_ci->{$ref};
		}

		// --------------------------------------------------------------------
		// Convert elapsed time and memory usage for dwoo compatibility

		if (CI_VERSION < 2)
		{
			$string = str_replace(array('{elapsed_time}', '{memory_usage}'), array('[elapsed_time]', '[memory_usage]'), $string);
		}

		// --------------------------------------------------------------------
		// Object containing data
		$dwoo_data = new Dwoo_Data;
		$dwoo_data->setData($data);

		try
		{
			// Object of the template
			$tpl = new Dwoo_Template_String($string);

			$dwoo = $is_include ? self::spawn() : $this->_dwoo;

			// render the template
			$parsed_string = $dwoo->get($tpl, $dwoo_data);
		}
		catch (Exception $e)
		{
			show_error($e);
		}

		// --------------------------------------------------------------------
		// Parse out the elapsed time and memory usage,
		// then swap the pseudo-variables with the data

		$elapsed = $this->_ci->benchmark->elapsed_time('total_execution_time_start', 'total_execution_time_end');

		if (CI_VERSION < 2)
		{
			$memory = ( ! function_exists('memory_get_usage')) ? '0' : round(memory_get_usage() / 1024 / 1024, 2) . 'MB';

			$string = str_replace(array('[elapsed_time]', '[memory_usage]'), array($elapsed, $memory), $string);
		}

		// Finish benchmark
		$this->_ci->benchmark->mark('dwoo_parse_end');

		// Return results or not ?
		if ( ! $return)
		{
			$this->_ci->output->append_output($parsed_string);
			return;
		}

		return $parsed_string;
	}

	// --------------------------------------------------------------------
}

/**
*class MY_Security_Policy extends Dwoo_Security_Policy {
*
*	public function callMethod(Dwoo_Core $dwoo, $obj, $method, $args)
*	{
*		return call_user_func_array(array($obj, $method), $args);
*	}
*
*	public function isMethodAllowed()
*	{
*		return TRUE;
*	}
*}
**/

// END MY_Parser Class

/* End of file MY_Parser.php */
/* Location: ./application/libraries/MY_Parser.php */