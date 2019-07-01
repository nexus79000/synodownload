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
			<label class="col-sm-3 control-label">{{Adresse IP Download Station}}</label>
			<div class="col-sm-3">
				<input type="text" class="configKey tooltips form-control" data-l1key="synoAddr" placeholder="IP"/>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">{{Port Download Station}}</label>
			<div class="col-sm-3">
				<input type="text" class="configKey tooltips form-control" data-l1key="synoPort" placeholder="Port"/>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label" >{{Connexion sécurisée}}</label>
			<div class="col-sm-1">
				<input type="checkbox" class="configKey tooltips form-control checkbox" data-l1key="synoHttps" />
			</div>
		</div>

	</fieldset>
</form>


<?php include_file('desktop', 'synodownload', 'js', 'synodownload');?>