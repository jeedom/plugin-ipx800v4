
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

$("#table_cmd").sortable({axis: "y", cursor: "move", items: ".cmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});

$('#table_cmd').on('change','.cmdAttr[data-l1key=type]',function(){
  if($(this).value() == 'action'){
    $(this).closest('.cmd').find('.actionType').show();
    $(this).closest('.cmd').find('.infoType').hide();
  }else{
    $(this).closest('.cmd').find('.actionType').hide();
    $(this).closest('.cmd').find('.infoType').show();
  }
});

$('#table_cmd').on('change','.cmdAttr[data-l1key=configuration][data-l2key=actionCmd]',function(){
  $(this).closest('.cmd').find('.actionArgument').hide();
  $(this).closest('.cmd').find('.actionArgument.'+$(this).value()).show();
});

$('#table_cmd').on('change','.cmdAttr[data-l1key=configuration][data-l2key=actionArgument]',function(){
  $(this).closest('.cmd').find('.actionParameter').hide();
  $(this).closest('.cmd').find('.actionParameter.'+$(this).value()).show();
  
  $(this).closest('.cmd').find('.actionOption').hide();
  $(this).closest('.cmd').find('.actionOption.'+$(this).value()).show();
});

$('#table_cmd').on('change','.cmdAttr[data-l1key=configuration][data-l2key=infoType]',function(){
  $(this).closest('.cmd').find('.infoParameter').hide();
  $(this).closest('.cmd').find('.infoParameter.'+$(this).value()).show();
});

$('.cmdAction[data-action=importFromTemplate]').on('click',function(){
  $('#md_modal').dialog({title: "{{Template commande IPX800}}"});
  $("#md_modal").load('index.php?v=d&plugin=ipx800v4&modal=cmd.template&eqLogic_id=' + $('.eqLogicAttr[data-l1key=id]').value()).dialog('open');
});

$('#bt_downloadIpxBackup').on('click',function(){
  window.open('core/php/downloadFile.php?pathfile=plugins/ipx800v4/data/'+$('.eqLogicAttr[data-l2key=ip]').value()+'.gce', "_blank", null);
});

function addCmdToTable(_cmd) {
  if (!isset(_cmd)) {
    var _cmd = {configuration: {}};
  }
  if (!isset(_cmd.configuration)) {
    _cmd.configuration = {};
  }
  var disabled = '';
  if(init(_cmd.logicalId) == 'refresh'){
    var disabled = 'disabled';
  }
  var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
  tr += '<td>';
  tr += '<span class="cmdAttr" data-l1key="id" style="display:none;"></span>';
  tr += '<div class="row">';
  tr += '<div class="col-sm-6">';
  tr += '<a class="cmdAction btn btn-default btn-sm" data-l1key="chooseIcon"><i class="fa fa-flag"></i> Icone</a>';
  tr += '<span class="cmdAttr" data-l1key="display" data-l2key="icon" style="margin-left : 10px;"></span>';
  tr += '</div>';
  tr += '<div class="col-sm-6">';
  tr += '<input class="cmdAttr form-control input-sm" data-l1key="name">';
  tr += '</div>';
  tr += '</div>';
  if(init(_cmd.logicalId) != 'refresh'){
    tr += '<select class="cmdAttr form-control input-sm" data-l1key="value" style="display : none;margin-top : 5px;" title="{{La valeur de la commande vaut par défaut la commande}}">';
    tr += '<option value="">Aucune</option>';
    tr += '</select>';
  }
  tr += '</td>';
  tr += '<td>';
  
  tr += '<span class="type" type="' + init(_cmd.type) + '">' + jeedom.cmd.availableType() + '</span>';
  tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>';
  tr += '</td>';
  tr += '<td>';
  if(init(_cmd.logicalId) != 'refresh'){
    tr += '<span class="actionType">';
    
    tr += '<div class="row">';
    tr += '<div class="col-xs-6">';
    tr += '<select class="cmdAttr form-control" data-l1key="configuration" data-l2key="actionCmd">';
    tr += '<option value="Set">{{On/Valeur}}</option>';
    tr += '<option value="Clear">{{Off}}</option>';
    tr += '<option value="Toggle">{{Bascule}}</option>';
    tr += '</select>';
    tr += '</div>';
    
    tr += '<div class="col-xs-6">';
    tr += '<select class="cmdAttr form-control" data-l1key="configuration" data-l2key="actionArgument">';
    tr += '<option value="R" class="actionArgument Set Clear Toggle">{{Relais}}</option>';
    tr += '<option value="VO" class="actionArgument Set Clear Toggle">{{Sortie virtuelle}}</option>';
    tr += '<option value="VI" class="actionArgument Set Clear Toggle">{{Entrée virtuelle}}</option>';
    tr += '<option value="VA" class="actionArgument Set">{{Entrée analogique virtuelle}}</option>';
    tr += '<option value="EnoPC" class="actionArgument Set Clear Toggle">{{EnOcean}}</option>';
    tr += '<option value="VR" class="actionArgument Set">{{Volet roulant}}</option>';
    tr += '<option value="C" class="actionArgument Set">{{Compteur}}</option>';
    tr += '<option value="FP" class="actionArgument Set">{{Fil pilote}}</option>';
    tr += '<option value="G" class="actionArgument Set">{{Dimmer}}</option>';
    tr += '<option value="PWM" class="actionArgument Set">{{PWM}}</option>';
    tr += '<option value="T" class="actionArgument Set">{{Thermostat}}</option>';
    tr += '</select>';
    tr += '</div>';
    tr += '</div>';
    
    tr += '<div class="row" style="margin-top:5px;">';
    tr += '<div class="col-xs-6">';
    tr += '<input class="cmdAttr form-control actionParameter R" data-l1key="configuration" data-l2key="actionParameterR" placeholder="{{Numéro du relai de 1 à 56}}" />';
    tr += '<input class="cmdAttr form-control actionParameter VO" data-l1key="configuration" data-l2key="actionParameterVO" placeholder="{{Numéro de la sortie de 1 à 128}}" style="display:none;" />';
    tr += '<input class="cmdAttr form-control actionParameter VI" data-l1key="configuration" data-l2key="actionParameterVI" placeholder="{{Numéro de l\'entrée de 1 à 128}}" style="display:none;" />';
    tr += '<input class="cmdAttr form-control actionParameter VA" data-l1key="configuration" data-l2key="actionParameterVA" placeholder="{{Numéro de l\'entrée de 1 à 32}}" style="display:none;" />';
    tr += '<input class="cmdAttr form-control actionParameter C" data-l1key="configuration" data-l2key="actionParameterC" placeholder="{{Numéro du compteur de 1 à 16}}" style="display:none;" />';
    tr += '<input class="cmdAttr form-control actionParameter VR" data-l1key="configuration" data-l2key="actionParameterVR" placeholder="{{Numéro du volet, ex : 02}}" style="display:none;" />';
    tr += '<input class="cmdAttr form-control actionParameter FP" data-l1key="configuration" data-l2key="actionParameterFP" placeholder="{{Numéro de la zone (00 à 16)}}" style="display:none;" />';
    tr += '<input class="cmdAttr form-control actionParameter EnoPC" data-l1key="configuration" data-l2key="actionParameterEnoPC" placeholder="{{Numéro de l\'actionneur de 1 à 24}}" style="display:none;" />';
    tr += '<input class="cmdAttr form-control actionParameter G" data-l1key="configuration" data-l2key="actionParameterG" placeholder="{{Numéro de la voie de 1 à 24}}" style="display:none;" />';
    tr += '<input class="cmdAttr form-control actionParameter PWM" data-l1key="configuration" data-l2key="actionParameterPWM" placeholder="{{Numéro du canal de 1 à 24}}" style="display:none;" />';
    tr += '<input class="cmdAttr form-control actionParameter T" data-l1key="configuration" data-l2key="actionParameterThermo" placeholder="{{Numéro du thermostat de 1 à 16}}" style="display:none;" />';
    tr += '</div>';
    
    tr += '<div class="col-xs-6">';
    tr += '<input class="cmdAttr form-control actionOption VA" data-l1key="configuration" data-l2key="actionOptionVA" placeholder="{{Valeur}}" style="display:none;" />';
    tr += '<input class="cmdAttr form-control actionOption C" data-l1key="configuration" data-l2key="actionOptionC" placeholder="{{Opération}}" style="display:none;" />';
    tr += '<input class="cmdAttr form-control actionOption VR" data-l1key="configuration" data-l2key="actionOptionVR" placeholder="{{Valeur}}" style="display:none;" />';
    tr += '<input class="cmdAttr form-control actionOption FP" data-l1key="configuration" data-l2key="actionOptionFP" placeholder="{{Valeur}}" style="display:none;" />';
    tr += '<input class="cmdAttr form-control actionOption G" data-l1key="configuration" data-l2key="actionOptionG" placeholder="{{Valeur}}" style="display:none;" />';
    tr += '<input class="cmdAttr form-control actionOption T" data-l1key="configuration" data-l2key="actionOptionThermo" placeholder="{{Valeur}}" style="display:none;" />';
    tr += '<input class="cmdAttr form-control actionOption PWM" data-l1key="configuration" data-l2key="actionOptionPWM" placeholder="{{Valeur}}" style="display:none;" />';
    tr += '</div>';
    tr += '</div>';
    
    tr += '</span>';
    
    tr += '<span class="infoType">';
    
    tr += '<div class="row">';
    tr += '<div class="col-xs-6">';
    tr += '<select class="cmdAttr form-control" data-l1key="configuration" data-l2key="infoType">';
    tr += '<option value="R">{{Relais}}</option>';
    tr += '<option value="D">{{Entrée digital}}</option>';
    tr += '<option value="VI">{{Entrée virtuelle}}</option>';
    tr += '<option value="VO">{{Sortie virtuelle}}</option>';
    tr += '<option value="PW">{{Watchdog}}</option>';
    tr += '<option value="ENO">{{EnOcean}}</option>';
    tr += '<option value="A">{{Entrée analogique}}</option>';
    tr += '<option value="VA">{{Entrée analogique virtuelle}}</option>';
    tr += '<option value="C">{{Compteur}}</option>';
    tr += '<option value="VR">{{Volet roulant}}</option>';
    tr += '<option value="THL">{{THL}}</option>';
    tr += '<option value="FP">{{Fil pilote}}</option>';
    tr += '<option value="G">{{Dimmer}}</option>';
    tr += '<option value="PWM">{{PWM}}</option>';
    tr += '<option value="T">{{Thermostat}}</option>';
    tr += '</select>';
    tr += '</div>';
    
    tr += '<div class="col-xs-6">';
    tr += '<input class="cmdAttr form-control infoParameter R" data-l1key="configuration" data-l2key="infoParameterR" placeholder="{{Numéro du relai de 1 à 56}}" />';
    tr += '<input class="cmdAttr form-control infoParameter VO" data-l1key="configuration" data-l2key="infoParameterVO" placeholder="{{Numéro de la sortie de 1 à 128}}" style="display:none;" />';
    tr += '<input class="cmdAttr form-control infoParameter VI" data-l1key="configuration" data-l2key="infoParameterVI" placeholder="{{Numéro de l\'entrée de 1 à 128}}" style="display:none;" />';
    tr += '<input class="cmdAttr form-control infoParameter VA" data-l1key="configuration" data-l2key="infoParameterVA" placeholder="{{Numéro de l\'entrée de 1 à 32}}" style="display:none;" />';
    tr += '<input class="cmdAttr form-control infoParameter C" data-l1key="configuration" data-l2key="infoParameterC" placeholder="{{Numéro du compteur de 1 à 16}}" style="display:none;" />';
    tr += '<input class="cmdAttr form-control infoParameter D" data-l1key="configuration" data-l2key="infoParameterD" placeholder="{{Entrée digital de 1 à 56}}" style="display:none;" />';
    tr += '<input class="cmdAttr form-control infoParameter PW" data-l1key="configuration" data-l2key="infoParameterPW" placeholder="{{Watchdog de 1 à 32}}" style="display:none;" />';
    tr += '<input class="cmdAttr form-control infoParameter VR" data-l1key="configuration" data-l2key="infoParameterVR" placeholder="{{Numéro de l\'extension et du volet, ex : 1-2}}" style="display:none;" />';
    tr += '<input class="cmdAttr form-control infoParameter PWM" data-l1key="configuration" data-l2key="infoParameterPWM" placeholder="{{Numéro du canal (1 à 24)}}" style="display:none;" />';
    tr += '<input class="cmdAttr form-control infoParameter G" data-l1key="configuration" data-l2key="infoParameterG" placeholder="{{Numéro de la voie (1 à 24)}}" style="display:none;" />';
    
    
    tr += '<select class="cmdAttr form-control infoParameter FP" data-l1key="configuration" data-l2key="infoParameterFP">';
    for (i = 1; i < 5; i++) {
      for (j = 1; j < 5; j++) {
        tr += '<option value="'+i+' Zone '+j+'">{{Ext }} '+i+' {{Zone }}'+j+'</option>';
      }
    }
    tr += '</select>';
    
    tr += '<select class="cmdAttr form-control infoParameter THL" data-l1key="configuration" data-l2key="infoParameterTHL">';
    for (i = 1; i < 15; i++) {
      tr += '<option value="'+i+'-TEMP">{{Température}} '+i+'</option>';
    }
    for (i = 1; i < 15; i++) {
      tr += '<option value="'+i+'-LUM">{{Luminosité}} '+i+'</option>';
    }
    for (i = 1; i < 15; i++) {
      tr += '<option value="'+i+'-HUM">{{Humidité}} '+i+'</option>';
    }
    tr += '</select>';
    
    tr += '<select class="cmdAttr form-control infoParameter ENO" data-l1key="configuration" data-l2key="infoParameterENO">';
    for (i = 1; i < 97; i++) {
      tr += '<option value=" SWITCH'+i+'">Switch '+i+'</option>';
    }
    for (i = 1; i < 25; i++) {
      tr += '<option value=" CONTACT'+i+'">Contact '+i+'</option>';
    }
    for (i = 1; i < 9; i++) {
      tr += '<option value=" WALL PLUG'+i+'">Wall plug '+i+'</option>';
    }
    for (i = 17; i < 41; i++) {
      tr += '<option value=" ANALOG'+i+'">Anologique '+i+'</option>';
    }
    for (i = 1; i < 25; i++) {
      tr += '<option value=" ACTIONNEUR'+i+'">Actionneur '+i+'</option>';
    }
    tr += '</select>';
    
    tr += '<input class="cmdAttr form-control infoParameter A" data-l1key="configuration" data-l2key="infoParameterA" placeholder="{{Numéro du compteur de 1 à 16}}" style="display:none;" />';
    tr += '</div>';
    tr += '</div>';
    
    tr += '</span>';
  }
  tr += '</td>';
  
  tr += '<td>';
  tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" checked/>{{Afficher}}</label></span> ';
  tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span> ';
  tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="invertBinary"/>{{Inverser}}</label></span> ';
  tr += '</td>';
  tr += '<td>';
  tr += '<input class="cmdAttr form-control input-sm" data-l1key="unite" placeholder="Unité" title="{{Unité}}">';
  tr += '<input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="minValue" placeholder="{{Min}}" title="{{Min}}" style="margin-top : 5px;"> ';
  tr += '<input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="maxValue" placeholder="{{Max}}" title="{{Max}}" style="margin-top : 5px;">';
  tr += '</td>';
  
  tr += '<td>';
  if (is_numeric(_cmd.id)) {
    tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fa fa-cogs"></i></a> ';
    tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>';
  }
  tr += '<i class="fa fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i>';
  tr += '</td>';
  tr += '</tr>';
  $('#table_cmd tbody').append(tr);
  
  var tr = $('#table_cmd tbody tr:last');
  jeedom.eqLogic.builSelectCmd({
    id:  $('.eqLogicAttr[data-l1key=id]').value(),
    filter: {type: 'info'},
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (result) {
      tr.find('.cmdAttr[data-l1key=value]').append(result);
      tr.setValues(_cmd, '.cmdAttr');
      jeedom.cmd.changeType(tr, init(_cmd.subType));
    }
  });
}
