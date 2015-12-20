
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

 function addCmdToTable(_cmd) {
    if (!isset(_cmd)) {
        var _cmd = {configuration: {}};
    }
    if (!isset(_cmd.configuration)) {
        _cmd.configuration = {};
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
    tr += '<select class="cmdAttr form-control tooltips input-sm" data-l1key="value" style="display : none;margin-top : 5px;" title="{{La valeur de la commande vaut par défaut la commande}}">';
    tr += '<option value="">Aucune</option>';
    tr += '</select>';

    tr += '</td>';
    tr += '<td>';
    tr += '<span class="type" type="' + init(_cmd.type) + '">' + jeedom.cmd.availableType() + '</span>';
    tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>';
    tr += '</td>';
    tr += '<td>';

    tr += '<span class="actionType">';

    tr += '<div class="row">';
    tr += '<div class="col-xs-6">';
    tr += '<select class="cmdAttr form-control" data-l1key="configuration" data-l2key="actionCmd">';
    tr += '<option value="Set">{{On}}</option>';
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
    tr += '<option value="C" class="actionArgument Set">{{Compteur}}</option>';
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
    tr += '</div>';

    tr += '<div class="col-xs-6">';
    tr += '<input class="cmdAttr form-control actionOption VA" data-l1key="configuration" data-l2key="actionOptionVA" placeholder="{{Valeur}}" style="display:none;" />';
    tr += '<input class="cmdAttr form-control actionOption C" data-l1key="configuration" data-l2key="actionOptionC" placeholder="{{Opération}}" style="display:none;" />';
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
    tr += '<input class="cmdAttr form-control infoParameter ENO" data-l1key="configuration" data-l2key="infoParameterENO" placeholder="{{EnOcean de 1 à 48}}" style="display:none;" />';
    tr += '<input class="cmdAttr form-control infoParameter A" data-l1key="configuration" data-l2key="infoParameterA" placeholder="{{Numéro du compteur de 1 à 16}}" style="display:none;" />';
    tr += '</div>';
    tr += '</div>';

    tr += '</span>';


    tr += '</td>';

    tr += '<td>';
    tr += '<span><input type="checkbox" class="cmdAttr bootstrapSwitch" data-size="mini" data-l1key="isHistorized" data-label-text=" {{Historiser}}" /></span> ';
    tr += '<span><input type="checkbox" class="cmdAttr bootstrapSwitch" data-size="mini" data-l1key="isVisible" data-label-text=" {{Afficher}}" checked/></span> ';
    tr += '<span class="expertModeVisible"><input type="checkbox" data-size="mini" class="cmdAttr bootstrapSwitch" data-l1key="display" data-label-text=" {{Inverser}}" data-l2key="invertBinary" /></span> ';
    tr += '</td>';
    tr += '<td>';
    tr += '<input class="cmdAttr form-control tooltips input-sm" data-l1key="unite" placeholder="Unité" title="{{Unité}}">';
    tr += '<input class="tooltips cmdAttr form-control input-sm expertModeVisible" data-l1key="configuration" data-l2key="minValue" placeholder="{{Min}}" title="{{Min}}" style="margin-top : 5px;"> ';
    tr += '<input class="tooltips cmdAttr form-control input-sm expertModeVisible" data-l1key="configuration" data-l2key="maxValue" placeholder="{{Max}}" title="{{Max}}" style="margin-top : 5px;">';
    tr += '</td>';

    tr += '<td>';
    if (is_numeric(_cmd.id)) {
        tr += '<a class="btn btn-default btn-xs cmdAction expertModeVisible" data-action="configure"><i class="fa fa-cogs"></i></a> ';
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>';
    }
    tr += '<i class="fa fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i>';
    tr += '</td>';
    tr += '</tr>';
    $('#table_cmd tbody').append(tr);

    var tr = $('#table_cmd tbody tr:last');
    jeedom.eqLogic.builSelectCmd({
        id: $(".li_eqLogic.active").attr('data-eqLogic_id'),
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
