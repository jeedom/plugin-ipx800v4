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
require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
function ipx800v4_install() {
	$cron = cron::byClassAndFunction('ipx800v4', 'pull');
	if (!is_object($cron)) {
		$cron = new cron();
		$cron->setClass('ipx800v4');
		$cron->setFunction('pull');
		$cron->setEnable(1);
		$cron->setDeamon(1);
		$cron->setDeamonSleepTime(1);
		$cron->setSchedule('* * * * *');
		$cron->setTimeout(1440);
		$cron->save();
	}
}
function ipx800v4_update() {
	$cron = cron::byClassAndFunction('ipx800v4', 'pull');
	if (!is_object($cron)) {
		$cron = new cron();
	}
	$cron->setClass('ipx800v4');
	$cron->setFunction('pull');
	$cron->setEnable(1);
	$cron->setDeamon(1);
	$cron->setDeamonSleepTime(1);
	$cron->setTimeout(1440);
	$cron->setSchedule('* * * * *');
	$cron->save();
	$cron->stop();
	foreach (ipx800v4::byType('ipx800v4') as $ipx800v4) {
		$ipx800v4->save();
	}
}
function ipx800v4_remove() {
	$cron = cron::byClassAndFunction('ipx800v4', 'pull');
	if (is_object($cron)) {
		$cron->remove();
	}
}
?>