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

	/*     * *********************Méthodes d'instance************************* */

	/*     * **********************Getteur Setteur*************************** */
}

class ipx800v4Cmd extends cmd {
	/*     * *************************Attributs****************************** */

	/*     * ***********************Methode static*************************** */

	/*     * *********************Methode d'instance************************* */

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
			$url .= '=' . $this->getConfiguration('actionOption' . $this->getConfiguration('actionArgument'));
		} else if ($this->getConfiguration('actionArgument') == 'C') {
			if (strlen($this->getConfiguration('actionParameter' . $this->getConfiguration('actionArgument'))) == 1) {
				$url .= '0' . $this->getConfiguration('actionParameter' . $this->getConfiguration('actionArgument'));
			} else {
				$url .= $this->getConfiguration('actionParameter' . $this->getConfiguration('actionArgument'));
			}
			$url .= '=' . $this->getConfiguration('actionOption' . $this->getConfiguration('actionArgument'));
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
