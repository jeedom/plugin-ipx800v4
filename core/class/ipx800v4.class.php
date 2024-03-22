<?php
/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see .
*/

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class ipx800v4 extends eqLogic {
	/* * *************************Constante****************************** */
	const TYPE_DATA = array(
		'allD' => array('infoParameterD', '/[0-1]{56}/', 'entrées digitales', 56),
		'allR' => array('infoParameterR', '/[0-1]{56}/', 'relais', 56),
		'allVO' => array('infoParameterVO', '/[0-1]{128}/', 'sorties virtuelles', 128),
		'allVI' => array('infoParameterVI', '/[0-1]{128}/', 'entrée virtuelles', 128),
		'D' => array('infoParameterD', array(0, 1), 'de l\'entrée digitale ', 56),
		'R' => array('infoParameterR', array(0, 1), 'du relais ', 56),
		'VO' => array('infoParameterVO', array(0, 1), 'de la sortie virtuelle ', 128),
		'VI' => array('infoParameterVI', array(0, 1), 'de l\'entrée virtuelle ', 128)
	);
	const DATA_UNITAIRE_REGEX = '/^([A-Z]+)(\d{1,3})$/';

	/* * *************************Attributs****************************** */

	private static $_eqLogics = null;

	/* * ***********************Methode static*************************** */

	public static function event() {
		if (init('onvent') == 1) { //D'origine dans la classe
			$cache = array();
			foreach (self::searchConfiguration('"ip":"' . init('ip') . '"', 'ipx800v4') as $ipx800v4) {
				if (!isset($cache[$ipx800v4->getConfiguration('ip')])) {
					$cache[$ipx800v4->getConfiguration('ip')] = $ipx800v4->getIPXinfo(array('R', 'D'));
				}
				ipx800v4::pull($ipx800v4->getId(), $cache);
			}
			return;
		}

		if (init('typeData') != '') { //Si on a un typeData défini
			log::add('ipx800v4', 'debug', 'Receveid data :' . print_r($_GET, true));
			$typeData = init('typeData');
			$getData = init('data');
			$ipx800v4_list = self::searchConfiguration('"ip":"' . init('ip', $_SERVER['REMOTE_ADDR']) . '"', 'ipx800v4'); //on récupère l'ensemble des eqLogics de type ipx800v4 qui ont pour adresse, l'adresse de l'IPX emetteur
			if (array_key_exists($typeData, self::TYPE_DATA)) { //si le typeData est déclaré dans la constante de classe
				log::add('ipx800v4', 'debug', 'Type data found :' . $typeData);
				if (!preg_match(self::TYPE_DATA[$typeData][1], $getData)) {
					log::add('ipx800v4', 'error', __('Il y a un problème dans les données des ', __FILE__) . self::TYPE_DATA[$typeData][2] . ', ' . strlen($getData) . ' valeur(s) reçue(s) sur ' . self::TYPE_DATA[$typeData][3] . ' valeurs attendues (' . $getData . ')');
					return;
				}
				//on vérifie le format de la chaine de valeur reçue
				foreach ($ipx800v4_list as &$ipx800v4) { //pour tous les eqLogics trouvés précédemment
					foreach ($ipx800v4->getCmd('info') as $cmd) { //si la commande est de type info et est une entrée digitale, on met à jour
						$index = $cmd->getConfiguration(self::TYPE_DATA[$typeData][0]);
						if ($index != '' && is_numeric($index)) {
							$ipx800v4->checkAndUpdateCmd($cmd, $getData[$index - 1], false);
						}
					}
				}
			} elseif (preg_match(self::DATA_UNITAIRE_REGEX, $typeData, $matches) && array_key_exists($matches[1], self::TYPE_DATA)) { //si le typeData correspond à un typeData de valeur unitaire
				if (intval($matches[2]) > self::TYPE_DATA[$matches[1]][3] || !in_array($getData, self::TYPE_DATA[$matches[1]][1])) {
					log::add('ipx800v4', 'error', __('Il y a un problème dans les données ', __FILE__) . self::TYPE_DATA[$matches[1]][2] . $matches[0] . ', valeur reçue: ' . $getData);
					return;
				}
				log::add('ipx800v4', 'debug', 'Type data not found, try unitaire regexp :' . print_r($matches, true));
				//Si le numéro de l'entrée est cohérent et que la valeur renvoyée est autorisée
				foreach ($ipx800v4_list as &$ipx800v4) { //pour tous les eqLogics trouvés précédemment
					foreach ($ipx800v4->getCmd('info') as $cmd) { //si la commande est de type info et est une entrée digitale, on met à jour
						if ($cmd->getConfiguration(self::TYPE_DATA[$matches[1]][0]) == intval($matches[2])) {
							$ipx800v4->checkAndUpdateCmd($cmd, $getData, false);
						}
					}
				}
			}
			return;
		}
		$cmd = ipx800v4Cmd::byId(init('id'));
		if (!is_object($cmd) || $cmd->getEqType() != 'ipx800v4') {
			throw new Exception(__('Commande ID ipx800v4 inconnue, ou la commande n\'est pas de type ipx800v4 : ', __FILE__) . init('id') . ', Valeur: ' . init('value'));
		}
		$cmd->event(init('value'));
	}

	public static function deamon_info() {
		$return = array();
		$return['log'] = '';
		$return['state'] = 'nok';
		$cron = cron::byClassAndFunction('ipx800v4', 'pull');
		if (is_object($cron) && $cron->running()) {
			$return['state'] = 'ok';
		}
		$return['launchable'] = 'ok';
		return $return;
	}

	public static function deamon_start() {
		self::deamon_stop();
		$deamon_info = self::deamon_info();
		if ($deamon_info['launchable'] != 'ok') {
			throw new Exception(__('Veuillez vérifier la configuration', __FILE__));
		}
		$cron = cron::byClassAndFunction('ipx800v4', 'pull');
		if (!is_object($cron)) {
			throw new Exception(__('Tâche cron introuvable', __FILE__));
		}
		$cron->setDeamonSleepTime(config::byKey('api::frequency', 'ipx800v4', 1));
		$cron->save();
		$cron->run();
	}

	public static function deamon_stop() {
		$cron = cron::byClassAndFunction('ipx800v4', 'pull');
		if (!is_object($cron)) {
			throw new Exception(__('Tâche cron introuvable', __FILE__));
		}
		$cron->halt();
	}

	public static function deamon_changeAutoMode($_mode) {
		$cron = cron::byClassAndFunction('ipx800v4', 'pull');
		if (!is_object($cron)) {
			throw new Exception(__('Tâche cron introuvable', __FILE__));
		}
		$cron->setEnable($_mode);
		$cron->save();
	}

	public static function cronDaily() {
		if (config::byKey('autosave_ipx_config', 'ipx800v4') == 1) {
			$alreadySave = array();
			foreach (self::byType('ipx800v4', true) as $ipx800v4) {
				if ($ipx800v4->getConfiguration('ip') == '') {
					continue;
				}
				if (isset($alreadySave[$ipx800v4->getConfiguration('ip')])) {
					continue;
				}
				try {
					$ipx800v4->saveIPXConfig();
				} catch (Exception $e) {
					log::add('ipx800v4', 'error', $e->getMessage());
					continue;
				}
				$alreadySave[$ipx800v4->getConfiguration('ip')] = $ipx800v4->getConfiguration('ip');
			}
		}
		try {
			if (date('i') == 0 && date('s') < 10) {
				sleep(10);
			}
			$plugin = plugin::byId(__CLASS__);
			$plugin->deamon_start(true);
		} catch (\Exception $e) {
		}
	}

	public static function pull($_eqLogic_id = null, $_cache = null) {
		$cache = array();
		if (self::$_eqLogics == null) {
			self::$_eqLogics = self::byType('ipx800v4', true);
		}
		if ($_cache != null) {
			$cache = $_cache;
		}
		foreach (self::$_eqLogics as &$ipx800v4) {
			if ($_eqLogic_id != null && $_eqLogic_id != $ipx800v4->getId()) {
				continue;
			}
			if ($ipx800v4->getConfiguration('ip') == '') {
				continue;
			}
			if (!isset($cache[$ipx800v4->getConfiguration('ip')])) {
				$cache[$ipx800v4->getConfiguration('ip')] = $ipx800v4->getIPXinfo();
			}
			foreach ($ipx800v4->getCmd('info') as $cmd) {
				if ($cmd->getConfiguration('infoType') == '010v') {
					$key = 'X-010V N°' . $cmd->getConfiguration('infoParameter010vExt');
					$channel = 'ch' . $cmd->getConfiguration('infoParameter010v');
					if (isset($cache[$ipx800v4->getConfiguration('ip')][$key]) && isset($cache[$ipx800v4->getConfiguration('ip')][$key][$channel])) {
						$ipx800v4->checkAndUpdateCmd($cmd, $cache[$ipx800v4->getConfiguration('ip')][$key][$channel], false);
					}
				} else {
					$key = $cmd->getConfiguration('infoType') . $cmd->getConfiguration('infoParameter' . $cmd->getConfiguration('infoType'));
					if (isset($cache[$ipx800v4->getConfiguration('ip')][$key])) {
						$value = $cache[$ipx800v4->getConfiguration('ip')][$key];
						if (is_array($value) && isset($value['Valeur'])) {
							if (isset($value['Etat']) && $value['Etat'] == 'OFF') {
								$value = 0;
							} else {
								$value = $value['Valeur'];
							}
						}
						if($cmd->getConfiguration('infoType') == 'VR'){
			                        	$value = 100 - $value;
			                        }
						$ipx800v4->checkAndUpdateCmd($cmd, $value, false);
					}
				}
			}
		}
	}

	public static function listCmdTemplate($_template = '') {
		$path = dirname(__FILE__) . '/../config/template';
		if (isset($_template) && $_template != '') {
			$files = ls($path, $_template . '.json', false, array('files', 'quiet'));
			if (count($files) == 1) {
				try {
					$content = file_get_contents($path . '/' . $files[0]);

					$deviceConfiguration = is_json($content, array(), true);
					return $deviceConfiguration[$_template];
				} catch (Exception $e) {
					return array();
				}
			}
		}
		$files = ls($path, '*.json', false, array('files', 'quiet'));
		$return = array();
		foreach ($files as $file) {
			try {
				$content = file_get_contents($path . '/' . $file);
				$return = array_merge($return, is_json($content, array()));
			} catch (Exception $e) {
			}
		}
		if (isset($_template) && $_template != '') {
			if (isset($return[$_template])) {
				return $return[$_template];
			}
			return array();
		}
		return $return;
	}

	/* * *********************Méthodes d'instance************************* */

	public function decrypt() {
		$this->setConfiguration('password', utils::decrypt($this->getConfiguration('password')));
		$this->setConfiguration('apikey', utils::decrypt($this->getConfiguration('apikey')));
	}

	public function encrypt() {
		$this->setConfiguration('password', utils::encrypt($this->getConfiguration('password')));
		$this->setConfiguration('apikey', utils::encrypt($this->getConfiguration('apikey')));
	}

	public function saveIPXConfig() {
		$filepath = __DIR__ . '/../../data/' . $this->getConfiguration('ip') . '.gce';
		$url = 'http://';
		if ($this->getConfiguration('username') != '' && $this->getConfiguration('password') != '') {
			$url .= urlencode($this->getConfiguration('username')) . ':' . urlencode($this->getConfiguration('password')) . '@';
		}
		$url .= $this->getConfiguration('ip') . '/admin/download/config.gce';
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_FILE, fopen($filepath, 'w+'));
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$errno = curl_exec($ch);
		if (!$errno) {
			$error_message = curl_strerror($errno);
			curl_close($ch);
			throw new Exception(__('Impossible de récuperer la sauvegarde de l\'ipx800 ', __FILE__) . $this->getConfiguration('ip') . ' : ' . $error_message);
		}
		curl_close($ch);
		if (filesize($filepath) < 100) {
			$content = file_get_contents($filepath);
			unlink($filepath);
			throw new Exception(__('Erreur taille du fichier inférieure à 100 octets pour ', __FILE__) . $this->getConfiguration('ip') . ' : ' . $content);
		}
	}

	public function postSave() {
		$refresh = $this->getCmd(null, 'refresh');
		if (!is_object($refresh)) {
			$refresh = new ipx800v4Cmd();
		}
		$refresh->setName(__('Rafraîchir', __FILE__));
		$refresh->setEqLogic_id($this->getId());
		$refresh->setLogicalId('refresh');
		$refresh->setType('action');
		$refresh->setSubType('other');
		$refresh->save();
	}

	public function getIPXinfo($_onlyApi = null) {
		$return = array();
		$api = array();

		if ($_onlyApi != null && is_array($_onlyApi)) {
			$apiCallType = $_onlyApi;
		} else {
			$apiCallType = array('all', 'A', 'VA', 'C', 'R', 'D', 'VI', 'VO', 'VA', 'PW', 'XTHL', 'VR', 'XENO', 'FP', 'G', 'T', 'XPWM', 'X010V');
		}
		foreach ($apiCallType as $get) {
			if (config::byKey('api::' . $get, 'ipx800v4', 1) != 1) {
				continue;
			}
			$url = 'http://' . $this->getConfiguration('ip') . '/api/xdevices.json?key=' . $this->getConfiguration('apikey') . '&Get=' . $get;
			$request_http = new com_http($url);
			try {
				$return = array_merge($return, is_json($request_http->exec(), array()));
			} catch (Exception $e) {
			}
		}
		log::add('ipx800v4', 'debug', 'IPX800 ' . $this->getConfiguration('ip') . ' info : ' . json_encode($return));
		return $return;
	}

	public function applyCmdTemplate($_config) {
		if (!is_array($_config)) {
			throw new Exception(__('La configuration d\'un template doit etre un tableau', __FILE__));
		}
		if (!isset($_config['template'])) {
			throw new Exception(__('Aucun nom de template trouvé', __FILE__));
		}
		$template = self::listCmdTemplate($_config['template']);
		if (!is_array($template) || count($template) < 1) {
			throw new Exception(__('Template introuvable', __FILE__));
		}
		if (!isset($template['commands']) || count($template['commands']) < 1) {
			throw new Exception(__('Aucune commandes trouvé dans le template', __FILE__));
		}
		$config = array();
		foreach ($_config as $key => $value) {
			$config['#' . $key . '#'] = $value;
		}
		$cmd_order = 0;
		$cmds_template = json_decode(str_replace(array_keys($config), $config, json_encode($template['commands'])), true);
		$link_cmds = array();
		$link_actions = array();
		foreach ($cmds_template as $command) {
			$cmd = new ipx800v4Cmd();
			$cmd->setOrder($cmd_order);
			$cmd->setEqLogic_id($this->getId());
			utils::a2o($cmd, $command);
			try {
				$cmd->save();
			} catch (Exception $e) {
			}

			$cmd_order++;
			if (isset($command['value'])) {
				$link_cmds[$cmd->getId()] = $command['value'];
			}
			if (isset($command['configuration']) && isset($command['configuration']['updateCmdId'])) {
				$link_actions[$cmd->getId()] = $command['configuration']['updateCmdId'];
			}
		}
		if (count($link_cmds) > 0) {
			foreach ($this->getCmd() as $eqLogic_cmd) {
				foreach ($link_cmds as $cmd_id => $link_cmd) {
					if ($link_cmd == $eqLogic_cmd->getName()) {
						$cmd = cmd::byId($cmd_id);
						if (is_object($cmd)) {
							$cmd->setValue($eqLogic_cmd->getId());
							$cmd->save();
						}
					}
				}
			}
		}
		if (count($link_actions) > 0) {
			foreach ($this->getCmd() as $eqLogic_cmd) {
				foreach ($link_actions as $cmd_id => $link_action) {
					if ($link_action == $eqLogic_cmd->getName()) {
						$cmd = cmd::byId($cmd_id);
						if (is_object($cmd)) {
							$cmd->setConfiguration('updateCmdId', $eqLogic_cmd->getId());
							$cmd->save();
						}
					}
				}
			}
		}
		return;
	}

	/* * **********************Getteur Setteur*************************** */
}

class ipx800v4Cmd extends cmd {
	/* * *************************Attributs****************************** */

	/* * ***********************Methode static*************************** */

	/* * *********************Methode d'instance************************* */

	public function alreadyInState($_options) {
		//$eqLogic = $this->getEqLogic();
		$cmdValue = $this->getCmdValue();
		$value =  $cmdValue->execCmd();
		if ($this->getConfiguration('actionArgument') == 'VR' && $this->getSubType() == 'other') {
			return ((100 - $this->getConfiguration('actionOptionVR')) == $value);
		}
		return parent::alreadyInState($_options);
	}

	public function execute($_options = array()) {
		if ($this->getLogicalId() == 'refresh') {
			ipx800v4::pull($this->getEqLogic_Id());
			return;
		}
		$eqLogic = $this->getEqLogic();
		$url = 'http://' . $eqLogic->getConfiguration('ip') . '/api/xdevices.json?key=' . $eqLogic->getConfiguration('apikey');
		$url .= '&' . $this->getConfiguration('actionCmd') . $this->getConfiguration('actionArgument');
		if (in_array($this->getConfiguration('actionArgument'), array('VA', 'C', 'VR', 'PulseUP', 'PulseDOWN', 'FP', 'G', 'Thermo', 'PWM', '010v','EnoVR'))) {
			if ($this->getConfiguration('actionArgument') == 'PWM') {
				$url .= '=' . $this->getConfiguration('actionParameter' . $this->getConfiguration('actionArgument'));
				$url .= '&PWMValue';
			} elseif ($this->getConfiguration('actionArgument') == '010v') {
				$url .= '=' . $this->getConfiguration('actionParameter010vExt');
				$url .= '&010vCha=' . $this->getConfiguration('actionParameter010v');
				$url .= '&010vValue';
			} elseif ($this->getConfiguration('actionArgument') == 'Thermo') {
				$url .= '=' . ($this->getConfiguration('actionParameter' . $this->getConfiguration('actionArgument')) - 1);
				$url .= '&Hys=' . $this->getConfiguration('actionOptionThermo_hys');
				$url .= '&Cons';
			} else {
				if (strlen($this->getConfiguration('actionParameter' . $this->getConfiguration('actionArgument'))) == 1) {
					$url .= '0' . $this->getConfiguration('actionParameter' . $this->getConfiguration('actionArgument'));
				} else {
					$url .= $this->getConfiguration('actionParameter' . $this->getConfiguration('actionArgument'));
				}
			}
			$value = $this->getConfiguration('actionOption' . $this->getConfiguration('actionArgument'));
			switch ($this->getSubType()) {
				case 'slider':
					if (trim($value) == '') {
						$value = '#slider#';
					}
					$value = str_replace('#slider#', urlencode($_options['slider']), $value);
					if($this->getConfiguration('actionArgument') == 'VR'){
			                 	$value = 100 - $value;
			                }
					break;
				case 'color':
					if (trim($value) == '') {
						$value = '#color#';
					}
					$value = str_replace('#color#', urlencode($_options['color']), $value);
					break;
				case 'message':
					if (trim($value) == '') {
						$value = '#title# #message#';
					}
					$value = str_replace('#title#', urlencode($_options['title']), $value);
					$value = str_replace('#message#', urlencode($_options['message']), $value);
					break;
			}
			$url .= '=' . $value;
		} else {
			$url .= '=' . $this->getConfiguration('actionParameter' . $this->getConfiguration('actionArgument'));
		}
		log::add('ipx800v4', 'info', 'Call url ' . $url);
		$request_http = new com_http($url);
		$result = is_json($request_http->exec());
		if (isset($result['status']) && $result['status'] != 'Success') {
			usleep(15000);
			$result = is_json($request_http->exec());
		}
		if (isset($result['status']) && $result['status'] != 'Success') {
			throw new \Exception(__('Echec de l\'éxecution de la commande : ', __FILE__) . json_encode($result));
		}
		usleep(10000);
	}

	/* * **********************Getteur Setteur*************************** */
}
