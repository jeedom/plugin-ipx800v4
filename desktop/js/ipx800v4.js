
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
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="name" style="width : 140px;" placeholder="{{Nom}}"></td>';
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
    tr += '<input class="cmdAttr form-control actionParameter R" data-l1key="configuration" data-l2key="actionParameterR" placeholder="{{Numéro de la sortie de 1 à 56}}" />';
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
    $('#table_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
    if (isset(_cmd.type)) {
        $('#table_cmd tbody tr:last .cmdAttr[data-l1key=type]').value(init(_cmd.type));
    }
    jeedom.cmd.changeType($('#table_cmd tbody tr:last'), init(_cmd.subType));
}
