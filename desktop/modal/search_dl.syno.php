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
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<div id='div_searchSynodlAlert' style="display: none;"></div>
<div class="form-group">
	<div class="col-sm-9">
		<input class="form-control input-sm txtsearch" type="text" placeholder="Recherche"<?php if ( isset($_GET['keyword']) && $_GET['keyword']!= '' ){echo 'value="'. str_replace('%20', ' ',$_GET['keyword']).'"';}?> />
	</div>
	<a class="btn btn-success btn-search" id="bt_validSearch" data-syno_id="<?php echo init('id');?>"><i class="fas fa-search"></i></a>
	<a class="btn btn-success pull-right" id="bt_add_dl" data-syno_id="<?php echo init('id');?>"><i class="fas fa-play"></i> {{Télécharger}}</a>
</div>

<div id="div_title" style="margin-top: 5px;height:20px)">
	<table class="table table-condensed">
		<thead>
			<tr>
				<!--<th style="width : 60px;">{{Check}}</th>-->
				<th style="width : 50px;text-align: center;">{{Check}}</th>
				<th style="width:calc(100% - 201px);text-align: left;" >{{Nom du téléchargement}}</th>
				<th style="width:75px;text-align: center;">{{Taille}}</th>
				<th style="width:60px;text-align: center;">{{Peers}}</th>
				<th style="width:16px;text-align: center;"></th>
			</tr>
		</thead>
	</table>
</div>

<div id="div_result" style="overflow:auto;height:calc(100% - 140px)">
	<table class="table table-condensed">
		<tbody>
			<?php
				if ( isset($_GET['keyword']) && $_GET['keyword']!= '' ) {
					$result_arr=synodownload::search(init('id'), $_GET['keyword']);
			
					
					if ($result_arr->total != '0'){
						foreach ( $result_arr->results as $result) {
							echo '<tr>';
							echo '<td style="width : 50px;text-align: center;">';
							echo '<input type="checkbox" class="configKey tooltips form-control checkbox song" data-url="' . $result->dlurl . '" " style="height: 20px ! important;" /> ';
							echo '</td>';
							echo '<td style="width:calc(100% - 185px);text-align: left;">';
							echo $result->title;
							echo '</td>';
							echo '<td style="width:75px;text-align: center;">';
							echo synodownload::convertSpeed($result->size);
							echo '</td>';
							echo '<td style="width:60px;text-align: center;">';
							echo $result->peers;
							echo '</td>';
							echo '</tr>';
						}
					}else{
						echo '<tr>';
						echo '<th colspan=4 > {{ Rien à afficher}}</th>';
						echo '</tr>';
					}
				}else{
					echo '<tr>';
					echo '<th colspan=4 > {{ Vous pouvez lancer une recherche}}</th>';
					echo '</tr>';
				}
			?>			
	</tbody>
	</table>
</div>

<script>

$('#bt_validSearch').on('click',function(){
	var id = $(this).attr('data-syno_id');
	var keyword = $('.txtsearch').val();
		
	var keyword = keyword.replace(/ /g,'%20')
	$('#md_modal2').load('index.php?v=d&plugin=synodownload&modal=search_dl.syno&id=' + id + '&keyword=' + keyword).dialog('open');
});

 $('#bt_add_dl').on('click',function(){
    var id = $(this).attr('data-syno_id');
	var listurl;
	
	$(':checkbox.song').each(function () {
        var ischecked = $(this).is(':checked');
        if (ischecked) {
			//checkbox_value += $(this).attr('data-name') + "|";
			if (listurl == null){
				listurl = $(this).attr('data-url');
			}else{
				listurl += ","+$(this).attr('data-url');
			}
		}
	});
	//$('#div_searchSynodlAlert').showAlert({message: listurl, level: 'danger'})
		
	$.ajax({// fonction permettant de faire de l'ajax
		type: "POST", // methode de transmission des données au fichier php
		url: "plugins/synodownload/core/ajax/synodownload.ajax.php", // url du fichier php
		data: {
			action: "addsearchdl",
			id :id,
			listurl : listurl
		},
		dataType: 'json',
		error: function (request, status, error) {
			handleAjaxError(request, status, error,$('#div_searchSynodlAlert'));
		},
		success: function (data) { // si l'appel a bien fonctionné
			if (data.state != 'ok') {
				$('#div_searchSynodlAlert').showAlert({message: data.result, level: 'danger'});
				return;
			}
		}
		});
});

</script>




