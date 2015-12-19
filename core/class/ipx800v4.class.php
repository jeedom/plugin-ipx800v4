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
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class ipx800v4 extends eqLogic {
	/*     * *************************Attributs****************************** */

	/*     * ***********************Methode static*************************** */

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

	public static function deamon_start($_debug = false) {
		self::deamon_stop();
		$deamon_info = self::deamon_info();
		if ($deamon_info['launchable'] != 'ok') {
			throw new Exception(__('Veuillez vérifier la configuration', __FILE__));
		}
		$cron = cron::byClassAndFunction('ipx800v4', 'pull');
		if (!is_object($cron)) {
			throw new Exception(__('Tâche cron introuvable', __FILE__));
		}
		$cron->run();
	}

	public static function pull() {
		$cache = array();
		foreach (ipx800v4::byType('ipx800v4') as $ipx800v4) {
			if (!isset($cache[$ipx800v4->getConfiguration('ip')])) {
				$cache[$ipx800v4->getConfiguration('ip')] = $ipx800v4->getValue();
			}
			$data = $cache[$ipx800v4->getConfiguration('ip')];
			foreach ($ipx800v4->getCmd('info') as $cmd) {
				$key = $cmd->getConfiguration('infoType') . $cmd->getConfiguration('infoParameter' . $cmd->getConfiguration('infoType'));
				if (isset($data[$key])) {
					$value = $data[$key];
					if ($value !== $cmd->execCmd(null, 2)) {
						$cmd->setCollectDate('');
						$cmd->event($value);
					}
				}
			}
		}
	}

	/*     * *********************Méthodes d'instance************************* */

	public function getValue() {
		$return = array();
		$url = 'http://' . $this->getConfiguration('ip') . '/api/xdevices.json?key=' . $this->getConfiguration('apikey') . '&Get=all';
		$request_http = new com_http($url);
		try {
			$result = $request_http->exec();
			if (is_json($result)) {
				$return = array_merge($return, json_decode($result, true));
			}
		} catch (Exception $e) {

		}
		$url = 'http://' . $this->getConfiguration('ip') . '/api/xdevices.json?key=' . $this->getConfiguration('apikey') . '&Get=A';
		$request_http = new com_http($url);
		try {
			$result = $request_http->exec();
			if (is_json($result)) {
				$return = array_merge($return, json_decode($result, true));
			}
		} catch (Exception $e) {

		}
		$url = 'http://' . $this->getConfiguration('ip') . '/api/xdevices.json?key=' . $this->getConfiguration('apikey') . '&Get=VA';
		$request_http = new com_http($url);
		try {
			$result = $request_http->exec();
			if (is_json($result)) {
				$return = array_merge($return, json_decode($result, true));
			}
		} catch (Exception $e) {

		}
		$url = 'http://' . $this->getConfiguration('ip') . '/api/xdevices.json?key=' . $this->getConfiguration('apikey') . '&Get=C';
		$request_http = new com_http($url);
		try {
			$result = $request_http->exec();
			if (is_json($result)) {
				$return = array_merge($return, json_decode($result, true));
			}
		} catch (Exception $e) {

		}
		return $return;
	}

	/*     * **********************Getteur Setteur*************************** */
}

class ipx800v4Cmd extends cmd {
	/*     * *************************Attributs****************************** */

	/*     * ***********************Methode static*************************** */

	/*     * *********************Methode d'instance************************* */

	public function preSave() {
		$this->setEventOnly(1);
	}

	public function execute($_options = array()) {
		$eqLogic = $this->getEqLogic();
		$url = 'http://' . $eqLogic->getConfiguration('ip') . '/api/xdevices.json?key=' . $eqLogic->getConfiguration('apikey');
		$url .= '&' . $this->getConfiguration('actionCmd') . $this->getConfiguration('actionArgument');
		if ($this->getConfiguration('actionArgument') == 'VA') {
			if (strlen($this->getConfiguration('actionParameter' . $this->getConfiguration('actionArgument'))) == 1) {
				$url .= '0' . $this->getConfiguration('actionParameter' . $this->getConfiguration('actionArgument'));
			} else {
				$url .= $this->getConfiguration('actionParameter' . $this->getConfiguration('actionArgument'));
			}
			$value = $this->getConfiguration('actionOption' . $this->getConfiguration('actionArgument'));
			switch ($this->getSubType()) {
				case 'slider':
					$value = str_replace('#slider#', urlencode($_options['slider']), $value);
					break;
				case 'color':
					$value = str_replace('#color#', urlencode($_options['color']), $value);
					break;
				case 'message':
					$value = str_replace('#title#', urlencode($_options['title']), $value);
					$value = str_replace('#message#', urlencode($_options['message']), $value);
					break;
			}
			$url .= '=' . $value;
		} else if ($this->getConfiguration('actionArgument') == 'C') {
			if (strlen($this->getConfiguration('actionParameter' . $this->getConfiguration('actionArgument'))) == 1) {
				$url .= '0' . $this->getConfiguration('actionParameter' . $this->getConfiguration('actionArgument'));
			} else {
				$url .= $this->getConfiguration('actionParameter' . $this->getConfiguration('actionArgument'));
			}
			$value = $this->getConfiguration('actionOption' . $this->getConfiguration('actionArgument'));
			switch ($this->getSubType()) {
				case 'slider':
					$value = str_replace('#slider#', urlencode($_options['slider']), $value);
					break;
				case 'color':
					$value = str_replace('#color#', urlencode($_options['color']), $value);
					break;
				case 'message':
					$value = str_replace('#title#', urlencode($_options['title']), $value);
					$value = str_replace('#message#', urlencode($_options['message']), $value);
					break;
			}
			$url .= '=' . $value;
		} else {
			$url .= '=' . $this->getConfiguration('actionParameter' . $this->getConfiguration('actionArgument'));
		}
		$request_http = new com_http($url);
		$result = $request_http->exec();
		if (strpos($result, '"Success"') === false) {
			throw new Exception(__('Erreur sur l\'envoi de la commande : ' . $url, __FILE__));
		}
	}

	/*     * **********************Getteur Setteur*************************** */
}

?>
