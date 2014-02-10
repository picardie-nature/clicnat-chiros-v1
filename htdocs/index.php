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
		if (isset($_POST['clicnat_login']) && isset($_POST['clicnat_pwd'])) {
			$utilisateur = bobs_utilisateur::by_login($this->db, trim($_POST['clicnat_login']));
			if(!$utilisateur) {
				$_SESSION['id_utilisateur'] = false;
				$this->ajoute_alerte('danger', "Nom d'utilisateur ou mot de passe incorrect");
			} else{
				if (!$utilisateur->acces_chiros)
				{
					$_SESSION['id_utilisateur'] = false;
					$this->ajoute_alerte('danger', "Accès réservé aux membres du réseau Chiros");
				}else{
					if (!$utilisateur->auth_ok(trim($_POST['clicnat_pwd']))) {
						$_SESSION['id_utilisateur'] = false;
						$this->ajoute_alerte('danger', "Nom d'utilisateur ou mot de passe incorrect");
					} else {
						$_SESSION['id_utilisateur'] = $utilisateur->id_utilisateur;
						$this->ajoute_alerte('success', "Connexion réussie");
					}
					$this->redirect('?t=accueil');
					}
				}
		} else {
			if (isset($_GET['fermer'])) {
				$_SESSION['id_utilisateur'] = false;
				$this->ajoute_alerte('info', 'Vous êtes maintenant déconnecté');
				$this->redirect('?t=accueil');
			}
		}
	}

	public function display() {
		global $start_time;

		session_start();

		if (!isset($_SESSION['id_utilisateur']))
			$_SESSION['id_utilisateur'] = false;

		$this->assign('page', $this->template());
		$before_func = 'before_'.$this->template();
		if (method_exists($this, $before_func)) {
			if ($this->template() != 'accueil') {
				if ($_SESSION['id_utilisateur'] == false) {
					throw new Exception('vous devez être identifié');
				}
			}

			if ($_SESSION['id_utilisateur']) 
				$this->assign('utl', get_utilisateur($this->db, $_SESSION['id_utilisateur']));
			else
				$this->assign('utl', false);

			$this->$before_func();
		} else {
			throw new Exception('404 Page introuvable');
		}
		parent::display($this->template().".tpl");
	}
}

require_once(DB_INC_PHP);
get_db($db);
$chiros = new Chiros($db);
$chiros->display();
?>
