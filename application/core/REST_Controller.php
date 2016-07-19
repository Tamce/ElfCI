<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class REST_API {
	public $method;
	private $ci;

	function __construct() {
		$this->ci =& get_instance();
		$this->method = $this->ci->input->method();
	}

	public function response($data, $code = 200, $type = null)
	{
		set_status_header($code);
		if (is_array($data) || is_object($data)) {
			$type = empty($type) ? 'application/json' : $type;
			header('Content-Type: ' . $type);
			exit(json_encode($data));
		}
		$type = empty($type) ? 'text/plain' : $type;
		header('Content-Type: ' . $type);
		exit($data);
	}

	public function request($key = null) {
		return $this->ci->input->input_stream($key);
	}

	public function query($key = null) {
		return $this->ci->input->get($key);
	}
}

/**
 * Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/general/controllers.html
 */
class REST_Controller extends CI_Controller {

	public $api;

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->api = new REST_API($this->input->method());
	}
}
