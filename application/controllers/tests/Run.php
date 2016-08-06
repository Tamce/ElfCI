<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH.'libraries/Signal.php');
class Test extends Signal {
	function __construct() {
		parent::__construct();
		$this->signal->register('boom!');
	}
	function triger() {
		return $this->signal->emit('boom!');
	}
}

class Run extends CI_Controller
{
	function index()
	{
		$this->load->library('unit');
		$this->unit->start();
		
		$this->unit->assert('测试CI的load file', function() {
			$this->load->file(APPPATH . 'libraries/include/SignalControl.php');
			return class_exists('ElfStack\SignalControl', false);
		}, '测试CI的Loader加载file时的行为和简单的require相似，即简单包含文件。');


		$this->twoInstanceUnequal = $this->unit->assert('两个实例不应该严格相等', function() {
			return new ElfStack\SignalControl() !== new ElfStack\SignalControl();
		}, '确保在php中使用<code>!==</code>比较两个实例得到的结果是true');


		$this->unit->assert('测试raw_load_class函数', function() {
			if (function_exists('raw_load_class') === false) return false;
			$in1 = raw_load_class('ElfStack\SignalControl', 'include/SignalControl.php');
			$in2 = raw_load_class('ElfStack\SignalControl', 'include/SignalControl.php');
			return $this->twoInstanceUnequal && $in1 === $in2;
		}, '测试raw_load_class函数两次执行返回的是同一个实例，即表明没有多次实例化一个类。<br>注：该测试依赖上一项测试');


		$this->unit->assert('测试信号系统能正常工作', function() {
			$obj = new Test();
			$obj->signal->connect('boom!', function() {
				echo 'call successfully!';
				return 'return value';
			});
			return $obj->triger();
		}, '槽函数中输出"call successfully"，本次测试返回信号类的emit方法的返回值（包含所有槽函数返回值的数组）');

		$this->unit->assert('数组测试结果覆写', function() {
			return array('vals in array', '__overide_unit_test' => false);
		}, '实际上这是测试单元测试类的一个测试，这个测试预期应该是<b>不通过</b>，因为在php中非空的函数或者对象都会被视为true，但可能存在某种情况使得即使函数返回结果不是空也要使测试不通过的情况，因此做了覆写测试结果的设计。<br><br>后来更新：仔细思考后发现这个功能完全是多余的orz，既然单元测试的测试函数只在测试时用到/编写，那么完全可以写一个判断直接返回true或false...<br>现在能想到唯一的用处就是在保持测试不通过的情况在单元报告里检查函数返回值了...');

		$this->unit->printResult();
	}
}
