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
include_file('core', 'authentification', 'php');
if (!isConnect()) {
	include_file('desktop', '404', 'php');
	die();
}
?>
<form class="form-horizontal">
	<fieldset>
    <div class="form-group">
      <label class="col-lg-4 control-label">{{Demande à faire aux ipx800v4}}</label>
      <div class="col-lg-8">
       <label class="checkbox-inline"><input type="checkbox" class="configKey" data-l1key="api::all" />{{[All] Tous}}</label>
       <label class="checkbox-inline"><input type="checkbox" class="configKey" data-l1key="api::A" />{{[A] Entrée analogique}}</label>
       <label class="checkbox-inline"><input type="checkbox" class="configKey" data-l1key="api::C" />{{[C] Compteurs}}</label>
       <label class="checkbox-inline"><input type="checkbox" class="configKey" data-l1key="api::R" />{{[R] Sortie digital (relai)}}</label>
       <label class="checkbox-inline"><input type="checkbox" class="configKey" data-l1key="api::D" />{{[D] Entrès digital}}</label>
       <label class="checkbox-inline"><input type="checkbox" class="configKey" data-l1key="api::VI" />{{[VI] Entrée virtuel}}</label>
       <label class="checkbox-inline"><input type="checkbox" class="configKey" data-l1key="api::VO" />{{[VO] Sortie virtuel}}</label>
       <label class="checkbox-inline"><input type="checkbox" class="configKey" data-l1key="api::VA" />{{[VA] Entrée analogique}}</label>
       <label class="checkbox-inline"><input type="checkbox" class="configKey" data-l1key="api::PW" />{{[PW] Watchdog}}</label>
       <label class="checkbox-inline"><input type="checkbox" class="configKey" data-l1key="api::XTHL" />{{[XTHL] Sonde THL}}</label>
       <label class="checkbox-inline"><input type="checkbox" class="configKey" data-l1key="api::VR" />{{[VR] Volet roulant}}</label>
       <label class="checkbox-inline"><input type="checkbox" class="configKey" data-l1key="api::XENO" />{{[XENO] EnOcean}}</label>
       <label class="checkbox-inline"><input type="checkbox" class="configKey" data-l1key="api::FP" />{{[FP] Fil pilote}}</label>
       <label class="checkbox-inline"><input type="checkbox" class="configKey" data-l1key="api::G" />{{[G] X-Dimmer}}</label>
       <label class="checkbox-inline"><input type="checkbox" class="configKey" data-l1key="api::T" />{{[T] Thermostat}}</label>
     </div>
   </div>
   <div class="form-group">
    <label class="col-lg-4 control-label">{{Fréquence en secondes des interrogations}}</label>
    <div class="col-lg-8">
      <input type="text" class="configKey" data-l1key="api::frequency" />
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-4 control-label">{{Sauvegarder automatique la configuration des ipx800 tous les jours}}</label>
    <div class="col-lg-8">
      <input type="checkbox" class="configKey" data-l1key="autosave_ipx_config" />
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-4 control-label">{{URL API}}</label>
    <div class="col-lg-8">
      <span><?php echo network::getNetworkAccess('internal') . '/core/api/jeeApi.php?type=ipx800v4&apikey=' . jeedom::getApiKey('ipx800v4') . '&onvent=1'; ?></span>
    </div>
  </div>
</fieldset>
</form>