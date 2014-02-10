<?php
/**
 * Promontoire
 *
 * Mise à disposition au public du compte rendu de la base
 */

$start_time = microtime(true);
$context = 'chiros';

if (file_exists('config.php')) 
	require_once('config.php');
else
	require_once('/etc/baseobs/config.php');

define('LOCALE', 'fr_FR.UTF-8');


require_once(SMARTY_DIR.'Smarty.class.php');
require_once(OBS_DIR.'element.php');
require_once(OBS_DIR.'espece.php');
require_once(OBS_DIR.'smarty.php');


class Chiros extends clicnat_smarty {
	protected $db;

	public function __construct($db) {
		setlocale(LC_ALL, LOCALE);
		parent::__construct($db, SMARTY_TEMPLATE_CHIROS, SMARTY_COMPILE_CHIROS, null, SMARTY_CACHEDIR_CHIROS);
		
	}

	public function template() {
		return isset($_GET['t'])?trim($_GET['t']):'accueil';
	}

	public function before_accueil() {
	
	}

	public function display() {
		global $start_time;

		session_start();

		$this->assign('page', $this->template());
		$before_func = 'before_'.$this->template();
		if (method_exists($this, $before_func))
			$this->$before_func();
		else
			throw new Exception('404 Page introuvable');

		parent::display($this->template().".tpl");
	}
}

require_once(DB_INC_PHP);
get_db($db);
$chiros = new Chiros($db);
$chiros->display();
?>
