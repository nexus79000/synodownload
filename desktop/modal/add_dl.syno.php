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
<div id='div_searchSynodlAlert2' style="display: none;"></div>

<!--
<div class="form-group" style="height:35px">
	<span class="pull-left" style="margin-top: 3px;margin-right: 3px;"><i class="fas fa-file-o"></i></span>
	<div class="col-sm-9" style="height:30px">	
		<input class="form-control input-sm addfile" id="input_file" type="file" placeholder="{{Fichier de téléchargement}}" multiple />
	</div>
	<a class="btn btn-success pull-right" id="bt_add_file" data-syno_id="<?php echo init('id');?>" style="width: 150px"><i class="fas fa-plus-circle"></i> {{Ajouter fichier}}</a>
</div>
-->

<!--
<div class="form-group" style="height:35px">
	<span class="pull-left" style="margin-top: 3px;margin-right: 3px;"><i class="fas fa-file-o"></i></span>
	<form method="POST" action="" enctype="multipart/form-data">
		<input type="hidden" name="MAX_FILE_SIZE" value="1000000">
		<div class="col-sm-9" style="height:30px">
			<input class="form-control input-sm addfile" id="input_file" type="file" name="ifile" placeholder="{{Fichier de téléchargement}}" multiple />
		</div>
		<input class="btn btn-success pull-right" id="bt_add_file" data-syno_id="<?php echo init('id');?>" style="width: 150px" type="submit" name="ajouter" value="{{Ajouter fichier}}">
	</form>
</div>
-->


<div class="form-group" style="height:35px">
	<span class="pull-left" style="margin-top: 3px;margin-right: 3px;"><i class="fas fa-globe"></i></span>
	<div class="col-sm-9" style="height:30px">
		<input class="form-control input-sm txtaddurl" type="test"  placeholder="{{Lien ou URL de téléchargement}}" />
	</div>
	<a class="btn btn-success pull-right" id="bt_add_url" data-syno_id="<?php echo init('id');?>" style="width: 150px"><i class="fas fa-plus-circle"></i> {{Ajouter URL/Lien}}</a>
</div>
<script>

/* $('#bt_add_file').on('click',function(){
	var allowedTypes = ['torrent', 'nzb', 'txt'];
    var id = $(this).attr('data-syno_id');
	var inputf,
		files,
		filesLen,
		file,
		fType,
		fr;
	var f_name, 
		f_size, 
		f_type, 
		f_content;


$('#div_searchSynodlAlert').showAlert({message: 'Test', level: 'danger'});

	inputf = document.getElementById('input_file');
	if (!inputf.files[0]) {
		$('#div_searchSynodlAlert').showAlert({message: 'Pas de fichier selectionné', level: 'danger'});
    } else {
        files = inputf.files;
		filesLen = files.length;
		
		for (var i = 0; i < filesLen; i++) {
			file = files[i];
            fType = file.name.split('.');
            fType = fType[fType.length - 1].toLowerCase();
           
			if (allowedTypes.indexOf(fType) != -1) {
                f_name = file.name;
				f_size = file.size;
		
				fr = new FileReader();
				fr.onload = function(event) {
					f_content=event.target.result;
					
					$.ajax({// fonction permettant de faire de l'ajax
						type: "POST", // methode de transmission des données au fichier php
						url: "plugins/synodownload/core/ajax/synodownload.ajax.php", // url du fichier php
					//	contentType: "multipart/form-data",
						data: {
							action: "addfiledl",
							id :id,
							f_name : f_name,
							f_size : f_size,
							f_content : f_content,
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
				}
				
				fr.readAsText(file);
				//fr.readAsDataURL (file,'UTF-8');
		
				
				//$('#div_searchSynodlAlert2').showAlert({message: f_content, level: 'danger'});
				
/*				$.ajax({// fonction permettant de faire de l'ajax
					type: "POST", // methode de transmission des données au fichier php
					url: "plugins/synodownload/core/ajax/synodownload.ajax.php", // url du fichier php
				//	contentType: "multipart/form-data",
					data: {
						action: "addfiledl",
						id :id,
						f_name : f_name,
						f_size : f_size,
						f_content : f_content,
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
*/
/*           }else {
				$('#div_searchSynodlAlert').showAlert({message: 'L\'extention du fichier n\'est pas pris en charge («torrent», «nzb», «txt»).', level: 'danger'});
			}
		}
	}
});
*/


 $('#bt_add_url').on('click',function(){
    var id = $(this).attr('data-syno_id');
	var listurl = $('.txtaddurl').val();;
	
	
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




