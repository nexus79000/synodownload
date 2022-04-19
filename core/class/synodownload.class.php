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

class synodownload extends eqLogic {
	/*     * *************************Attributs****************************** */

	private static $_nb_cmd = 15;
	private static $_nb_dl_pause;
	private static $_nb_dl_resume;
	private static $_nb_dl_partage;
	
	public static $_widgetPossibility = array(
		'custom' => true);	
	
	/*     * ***********************Methode static*************************** */
	
	/*     * *********************Methode d'instance************************* */
	public function cronDaily($_eqLogic_id = null){
	
		self::initPlugin();

	}
	
	public function cron15(){
	
		self::pull();
		
	}
	
	
	public function postInsert() {
		
	}
	
	public function preUpdate() {
// Action
		$resume = $this->getCmd(null, 'resume');
		if (!is_object($resume)) {
			$resume = new synodownloadCmd();
			$resume->setLogicalId('resume');
		}
		$resume->setIsVisible(0);
		$resume->setName(__('Resume', __FILE__));
		$resume->setType('action');
	//	$resume->setDisplay('generic_type','GENERIC_ACTION');
		$resume->setSubType('other');
		$resume->setEqLogic_id($this->getId());
		$resume->save();
				
		$pause = $this->getCmd(null, 'pause');
		if (!is_object($pause)) {
			$pause = new synodownloadCmd();
			$pause->setLogicalId('pause');
		}
		$pause->setIsVisible(0);
		$pause->setName(__('Pause', __FILE__));
		$pause->setType('action');
	//	$pause->setDisplay('generic_type','GENERIC_ACTION');
		$pause->setSubType('other');
		$pause->setEqLogic_id($this->getId());
		$pause->save();
		
		$delete = $this->getCmd(null, 'delete');
		if (!is_object($delete)) {
			$delete = new synodownloadCmd();
			$delete->setLogicalId('delete');
		}
		$delete->setIsVisible(0);
		$delete->setName(__('Delete', __FILE__));
		$delete->setType('action');
	//	$delete->setDisplay('generic_type','GENERIC_ACTION');
		$delete->setSubType('other');
		$delete->setEqLogic_id($this->getId());
		$delete->save();
		
		$condition = $this->getCmd(null, 'condition');
		if (!is_object($condition)) {
			$condition = new synodownloadCmd();
			$condition->setLogicalId('condition');
		}
		$condition->setIsVisible(1);
		$condition->setName(__('ALL_action', __FILE__));
		$condition->setType('action');
	//	$condition->setDisplay('generic_type','GENERIC_ACTION');
		$condition->setSubType('message');
		$condition->setDisplay('message_disable', 1);
		$condition->setDisplay('title_placeholder', __('Action (resume, pause ou delete)', __FILE__));
		//$condition->setSubType('other');
		$condition->setEqLogic_id($this->getId());
		$condition->save();
		
		$refresh = $this->getCmd(null, 'refresh');
		if (!is_object($refresh)) {
			$refresh = new synodownloadCmd();
			$refresh->setLogicalId('refresh');
		}
		$refresh->setIsVisible(1);
		$refresh->setName(__('Rafraichir', __FILE__));
		$refresh->setType('action');
	//	$refresh->setDisplay('generic_type','GENERIC_ACTION');
		$refresh->setSubType('other');
		$refresh->setEqLogic_id($this->getId());
		$refresh->save();
			
//Info

		$vitesse_dl = $this->getCmd(null, 'vitesse_dl');
		if (!is_object($vitesse_dl)) {
			$vitesse_dl = new synodownloadCmd();
			$vitesse_dl->setLogicalId('vitesse_dl');
		}
		$vitesse_dl->setIsVisible(1);
		$vitesse_dl->setName(__('Vitesse Download', __FILE__));
		$vitesse_dl->setType('info');
	//	$vitesse_dl->setDisplay('generic_type','GENERIC_INFO');
		$vitesse_dl->setSubType('string');
		$vitesse_dl->setEqLogic_id($this->getId());
		$vitesse_dl->save();

		$vitesse_ul = $this->getCmd(null, 'vitesse_ul');
		if (!is_object($vitesse_ul)) {
			$vitesse_ul = new synodownloadCmd();
			$vitesse_ul->setLogicalId('vitesse_ul');
		}
		$vitesse_ul->setIsVisible(1);
		$vitesse_ul->setName(__('Vitesse Upload', __FILE__));
		$vitesse_ul->setType('info');
	//	$vitesse_ul->setDisplay('generic_type','GENERIC_INFO');
		$vitesse_ul->setSubType('string');
		$vitesse_ul->setEqLogic_id($this->getId());
		$vitesse_ul->save();
		
		$nb_total_dl = $this->getCmd(null, 'nb_total_dl');
		if (!is_object($nb_total_dl)) {
			$nb_total_dl = new synodownloadCmd();
			$nb_total_dl->setLogicalId('nb_total_dl');
		}
		$nb_total_dl->setIsVisible(1);
		$nb_total_dl->setName(__('Nombre de téléchargement', __FILE__));
		$nb_total_dl->setType('info');
	//	$vitesse_ul->setDisplay('generic_type','GENERIC_INFO');
		$nb_total_dl->setSubType('numeric');
		$nb_total_dl->setEqLogic_id($this->getId());
		$nb_total_dl->save();
		
		$nb_dl_pause = $this->getCmd(null, 'nb_dl_pause');
		if (!is_object($nb_dl_pause)) {
			$nb_dl_pause = new synodownloadCmd();
			$nb_dl_pause->setLogicalId('nb_dl_pause');
		}
		$nb_dl_pause->setIsVisible(1);
		$nb_dl_pause->setName(__('Téléchargement en pause', __FILE__));
		$nb_dl_pause->setType('info');
	//	$nb_dl_pause->setDisplay('generic_type','GENERIC_INFO');
		$nb_dl_pause->setSubType('numeric');
		$nb_dl_pause->setEqLogic_id($this->getId());
		$nb_dl_pause->save();
		
		$nb_dl_resume = $this->getCmd(null, 'nb_dl_resume');
		if (!is_object($nb_dl_resume)) {
			$nb_dl_resume = new synodownloadCmd();
			$nb_dl_resume->setLogicalId('nb_dl_resume');
		}
		$nb_dl_resume->setIsVisible(1);
		$nb_dl_resume->setName(__('Téléchargement en cours', __FILE__));
		$nb_dl_resume->setType('info');
	//	$nb_dl_resume->setDisplay('generic_type','GENERIC_INFO');
		$nb_dl_resume->setSubType('numeric');
		$nb_dl_resume->setEqLogic_id($this->getId());
		$nb_dl_resume->save();
		
		$nb_dl_partage = $this->getCmd(null, 'nb_dl_partage');
		if (!is_object($nb_dl_partage)) {
			$nb_dl_partage = new synodownloadCmd();
			$nb_dl_partage->setLogicalId('nb_dl_partage');
		}
		$nb_dl_partage->setIsVisible(1);
		$nb_dl_partage->setName(__('Téléchargement en partage', __FILE__));
		$nb_dl_partage->setType('info');
	//	$nb_dl_partage->setDisplay('generic_type','GENERIC_INFO');
		$nb_dl_partage->setSubType('numeric');
		$nb_dl_partage->setEqLogic_id($this->getId());
		$nb_dl_partage->save();
	
	}	

	public function preSave() {
		$this->setCategory('multimedia', 1);
        $this->setLogicalId('SynoDownload_'.$this->getid());
	}

	public function postSave() {
        
        // Création de la session initial	
		/*if ($this->getConfiguration('synoUser')!='' && $this->getConfiguration('synoPwd')!='' ) {
			if ( cache::byKey('SYNO.Download.' .$this->getId(). '.sid') ){
				self::createURL();
				self::updateAPIs();
				self::getSid($this);
			}
			self::pull($this->getId());
		}*/
	}
	
	public function createCmd($_id) {
		log::add('synodownload', 'debug',' Ajout Commande ' . $_id );
	
		$id_dl = $this->getCmd(null, 'id_dl_' . $_id);
		if (!is_object($id_dl)) {
			$id_dl = new synodownloadCmd();
			$id_dl->setLogicalId('id_dl_' . $_id);
			$id_dl->setIsVisible(0);
			$id_dl->setName(__('DL ' . $_id . ' Id', __FILE__));
		}
		$id_dl->setType('info');
		$id_dl->setSubType('string');
	//	$id_dl->setDisplay('generic_type','GENERIC_INFO');
		$id_dl->setEqLogic_id($this->getId());
		$id_dl->save();
		
		$nom_dl = $this->getCmd(null, 'nom_dl_' . $_id);
		if (!is_object($nom_dl)) {
			$nom_dl = new synodownloadCmd();
			$nom_dl->setLogicalId('nom_dl_' . $_id);
			$nom_dl->setIsVisible(1);
			$nom_dl->setName(__('DL ' . $_id . ' Nom', __FILE__));
		}
		$nom_dl->setType('info');
		$nom_dl->setSubType('string');
	//	$nom_dl->setDisplay('generic_type','GENERIC_INFO');
		$nom_dl->setEqLogic_id($this->getId());
		$nom_dl->save();
	
		$etat_dl = $this->getCmd(null, 'etat_dl_' . $_id);
		if (!is_object($etat_dl)) {
			$etat_dl = new synodownloadCmd();
			$etat_dl->setLogicalId('etat_dl_' . $_id);
			$etat_dl->setIsVisible(1);
			$etat_dl->setName(__('DL ' . $_id . ' Etat ', __FILE__));
		}
		$etat_dl->setType('info');
	//	$etat_dl->setDisplay('generic_type','GENERIC_INFO');
		$etat_dl->setSubType('string');
		$etat_dl->setEqLogic_id($this->getId());
		$etat_dl->save();	
	
		$pourcentage_dl = $this->getCmd(null, 'pourcentage_dl_' . $_id);
		if (!is_object($pourcentage_dl)) {
			$pourcentage_dl = new synodownloadCmd();
			$pourcentage_dl->setLogicalId('pourcentage_dl_' . $_id);
			$pourcentage_dl->setIsVisible(1);
			$pourcentage_dl->setName(__('DL ' . $_id . ' Pourcentage', __FILE__));
		}
		$pourcentage_dl->setUnite('%');
		$pourcentage_dl->setType('info');
	//	$pourcentage_dl->setDisplay('generic_type','GENERIC_INFO');
		$pourcentage_dl->setSubType('numeric');
		$pourcentage_dl->setEqLogic_id($this->getId());
		$pourcentage_dl->save();
	
	}
	
	public function updateCmd($_id, $_task){
		global $_nb_dl_pause,$_nb_dl_resume,$_nb_dl_partage;
		
		$id_dl = $_task->id;
		$changed = $this->checkAndUpdateCmd('id_dl_' . $_id, $id_dl) || $changed;
		
		$nom_dl = $_task->title;
		$changed = $this->checkAndUpdateCmd('nom_dl_' . $_id, $nom_dl) || $changed;
		
		$etat_dl = self::convertState($_task->status);
		$changed = $this->checkAndUpdateCmd('etat_dl_' . $_id, $etat_dl) || $changed;
		switch ($etat_dl) {
			case 'Partage':
				$_nb_dl_partage=$_nb_dl_partage+1;
				break;
			case 'Téléchargement':	
			case 'Finalisation':
			case 'Vérification':
			case 'Execution':
				$_nb_dl_resume=$_nb_dl_resume+1;
				break;
			default :	
				$_nb_dl_pause=$_nb_dl_pause+1;
				break;
		}
		if ($_task->size == '0'){
			$pourcentage_dl = 0;
		}else{
			$pourcentage_dl = round( $_task->additional->transfer->size_downloaded * 100 / $_task->size );
		}
		$changed = $this->checkAndUpdateCmd('pourcentage_dl_' . $_id, $pourcentage_dl) || $changed;

	}
	
	public function removeCmd($_id) {
	log::add('synodownload', 'debug',' Suppression Commande' . $_id );
		$id_dl = $this->getCmd(null, 'id_dl_' . $_id);
		$id_dl->remove();
		
		$nom_dl = $this->getCmd(null, 'nom_dl_' . $_id);
		$nom_dl->remove();
	
		$etat_dl = $this->getCmd(null, 'etat_dl_' . $_id);
		$etat_dl->remove();	
	
		$pourcentage_dl = $this->getCmd(null, 'pourcentage_dl_' . $_id);
		$pourcentage_dl->remove();
		
	}

	/*     * **********************Getteur Setteur*************************** */

	public function createURL(){  // OK
		//création de l'URL
		if (config::byKey('synoHttps','synodownload') == true) {
			$racineURL='https://'. config::byKey('synoAddr','synodownload').':'. config::byKey('synoPort','synodownload');
		}else{
			$racineURL='http://'. config::byKey('synoAddr','synodownload').':'. config::byKey('synoPort','synodownload');
		}
		config::save('SYNO.conf.url', $racineURL , 'synodownload');
	}

	public static function getCurlPage($_url,$_ContentType=null,$_databinary=null) {//OK
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_HEADER, false);
		//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_URL, $_url);
		//curl_setopt($ch, CURLOPT_REFERER, $_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		if( $_ContentType !== null && $_databinary !== null){
			curl_setopt($ch, CURLOPT_HEADER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $_ContentType);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $_databinary);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:47.0) Gecko/20100101 Firefox/47.0');
			curl_setopt($ch, CURLOPT_ENCODING, '');
			curl_setopt($ch, CURLOPT_REFERER, $_url);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			
			log::add('synodownload', 'debug',' Appel de curl trace ' );
			//trace
			curl_setopt($ch, CURLINFO_HEADER_OUT, true);
			curl_setopt($ch, CURLOPT_VERBOSE, true);
			$verbose = fopen('/var/www/html/log/curl_debug.log', 'a');
			curl_setopt($ch, CURLOPT_STDERR, $verbose);
			fclose($verbose);
		}
		
		if( ! $result = curl_exec($ch))
		{
			$verbose = fopen('/var/www/html/log/curl_result.log', 'a');
			fputs($verbose , $result);
			fclose($verbose);
			log::add('synodownload', 'error',' Appel de curl en erreur12 : ' . $result );
			
			$erreur=curl_error($ch);
			log::add('synodownload', 'error',' Appel de curl en erreur : ' . $erreur );
			curl_close($ch);
			return $erreur;
		} 
		curl_close($ch);
		return $result;
	}


	
	public static function appelURL($_eqLogicId, $_API, $_method=null, $_download=null, $libre=null,$_ContentType=null,$_databinary=null) { //OK
		//Construit l'URL, l'appel et retourne 
		$eqlogic=synodownload::byId($_eqLogicId);
		log::add('synodownload', 'debug',' Appel url  Affiche eqlogic ' . $eqlogic->getName() );

		if(config::byKey('SYNO.conf.url','synodownload')== null){ self::initPlugin(); }
		if(config::byKey($_API,'synodownload')== null){ self::initPlugin(); }
		if($eqlogic->getConfiguration('sid')==null){self::initPlugin();}
		
		$url=config::byKey('SYNO.conf.url','synodownload');
		$arrAPI=config::byKey($_API,'synodownload');
		$sessionsid=$eqlogic->getConfiguration('sid');
		
		$apiName = 'SYNO.API.Auth';
		$apiPath = $arrAPI['path'];
		$apiVersion = $arrAPI['version'];
		
		if ( $_ContentType == null && $_databinary == null){		
			$fURL = $url.'/webapi/'.$apiPath.'?api=' . $_API . '&version='.$apiVersion;
			if($_method !== null){
				$fURL = $fURL . '&method=' . $_method;
			}
			if($_download !== null){
				$fURL = $fURL . '&id=' . $_download;
			}
			if($libre !== null){
				$libre = str_replace(' ', '%20', $libre); // -> ' ' par %20
				$libre = str_replace('/', '%2F', $libre); // -> / par %2F
				$libre = str_replace('"', '%22', $libre); // -> " par %22
				$libre = str_replace(':', '%3A', $libre); // -> : par %3A
				$libre = str_replace(',', '%2C', $libre); // -> , par %2C
				$fURL = $fURL . '&' . $libre;
			}
			$fURL = $fURL . '&_sid='. $sessionsid;
		}else{
			$fURL = $url.'/webapi/'.$apiPath.'/' . $_API;
			//$_databinary += ['api'=> $_API] + $_databinary;
			//$_databinary += ['method'=> $_method] + $_databinary;
			//$_databinary += ['version'=> $apiVersion] + $_databinary;
			log::add('synodownload', 'debug', 'File add databinary   ' . print_r($_databinary) );
		}
		
		log::add('synodownload', 'debug',' Appel de l\'API : ' . $_API . '  url : ' . $fURL );
		$json = synodownload::getCurlPage($fURL,$_ContentType,$_databinary);
		
		$obj = json_decode($json);
		if($obj->success != "true"){
			//if( $obj->error->code != "500" ) {
				log::add('synodownload', 'error',' Appel de l\'API : ' . $_API . ' en erreur, url : ' . $fURL . ' code : ' . $obj->error->code );
			//}
			if( $obj->error->code == "105"  || $obj->error->code=="106" || $obj->error->code=="119" ){ // || $obj->error->code=="107" ){
				log::add('synodownload', 'info',' Réinitialisation de la connection ' );
				self::getSid($eqlogic);
			}
		}
		return $obj;
		
	}
	
	public function updateAPIs(){  //OK
		//Mise à jour des API version et chemin 
		//Get SYNO.API.Auth Path (recommended by Synology for further update)
		log::add('synodownload', 'debug',' Mise à jour des API - Début' );

		$url=config::byKey('SYNO.conf.url','synodownload');
		$list_API = array(
		'SYNO.DownloadStation2.BTSearch',
//		'SYNO.DownloadStation2.Captcha',
//		'SYNO.DownloadStation2.Package.Info',
//		'SYNO.DownloadStation2.Package.Module',
//		'SYNO.DownloadStation2.Package.Service',
//		'SYNO.DownloadStation2.RSS.Feed',
//		'SYNO.DownloadStation2.RSS.Filter',
//		'SYNO.DownloadStation2.RSS.Item',
//		'SYNO.DownloadStation2.Settings.BT',
//		'SYNO.DownloadStation2.Settings.BTSearch',
//		'SYNO.DownloadStation2.Settings.Emule',
//		'SYNO.DownloadStation2.Settings.Emule.Location',
//		'SYNO.DownloadStation2.Settings.FileHosting',
//		'SYNO.DownloadStation2.Settings.FtpHttp',
//		'SYNO.DownloadStation2.Settings.Global',
		'SYNO.DownloadStation2.Settings.Location',
//		'SYNO.DownloadStation2.Settings.Nzb',
//		'SYNO.DownloadStation2.Settings.Rss',
//		'SYNO.DownloadStation2.Settings.Scheduler',
		'SYNO.DownloadStation2.Task',
//		'SYNO.DownloadStation2.Task.BT',
//		'SYNO.DownloadStation2.Task.BT.File',
//		'SYNO.DownloadStation2.Task.BT.Peer',
//		'SYNO.DownloadStation2.Task.BT.Tracker',
//		'SYNO.DownloadStation2.Task.List',
//		'SYNO.DownloadStation2.Task.List.Polling',
//		'SYNO.DownloadStation2.Task.NZB.File',
//		'SYNO.DownloadStation2.Task.NZB.Log',
//		'SYNO.DownloadStation2.Task.Source',
		'SYNO.DownloadStation2.Task.Statistic',
//		'SYNO.DownloadStation2.Task.eMule',
//		'SYNO.DownloadStation2.Thumbnail',
//		'SYNO.DownloadStation2.eMule.Search',
//		'SYNO.DownloadStation2.eMule.Server'

		//'SYNO.DownloadStation.BTSearch',
		//'SYNO.DownloadStation.Info',
		//'SYNO.DownloadStation.Schedule',
		//'SYNO.DownloadStation.Task',
		//'SYNO.DownloadStation.Statistic',
		//'SYNO.DownloadStation.RSS.Site',
		//'SYNO.DownloadStation.RSS.Feed',
		//'SYNO.DownloadStation.Xunlei.Task'
		'SYNO.API.Auth'
		);
		
		$fURL=$url . '/webapi/query.cgi?api=SYNO.API.Info&method=Query&version=1&query=SYNO.API.Auth,SYNO.DownloadStation2.';
		
		$json = synodownload::getCurlPage($fURL);
		$obj = json_decode($json);
				
		if($obj->success != "true"){
			log::add('synodownload', 'error', ' Mise à jour des API ' . $API . ' en erreur, url : ' . $fURL . ' , code : ' . $obj->error->code );
		}else{
			foreach ($list_API as $element){
				config::save($element, array (
											"path" => $obj->data->$element->path,
											"version" =>$obj->data->$element->maxVersion
										)
							, 'synodownload');
			}
			log::add('synodownload', 'debug',' Mise à jour des API - OK' );
		}
		log::add('synodownload', 'debug',' Mise à jour des API - Fin' );
	}
	
	public function getSid($_eqLogic){ //OK
		log::add('synodownload', 'debug',' Création de la session - Début : ' . $_eqLogic->getName() );
		
		$url=config::byKey('SYNO.conf.url','synodownload');
		$login=urlencode($_eqLogic->getConfiguration('synoUser'));
		$pass=urlencode($_eqLogic->getConfiguration('synoPwd'));

		$arrAPI=config::byKey('SYNO.API.Auth','synodownload');
			
		$apiName = 'SYNO.API.Auth';
		$apiPath = $arrAPI['path'];
		$apiVersion = $arrAPI['version'];
		
		//Login and creating SID
		$fURL = $url.'/webapi/'. $apiPath .'?api=' . $apiName . '&method=login&version='. $apiVersion .'&account='.$login.'&passwd='.$pass.'&session=DownloadStation&format=sid';
		//$json = file_get_contents($fURL);
		$json = synodownload::getCurlPage($fURL);
		$obj = json_decode($json);
		if($obj->success != "true"){
			log::add('synodownload', 'error',' Création de la session sur ' . $_eqLogic->getName() . ' en erreur, url : ' . $fURL . ', code : ' . $obj->error->code );
			exit();
		}else{
			//authentification successful
			$sid = $obj->data->sid;
			$_eqLogic->setConfiguration('sid', $sid);
			$_eqLogic->save();
			log::add('synodownload', 'debug',' Création de la session OK , $sid : ' . $sid);
		}
		log::add('synodownload', 'debug',' Création de la session - Fin ' );
	}
	
	public function deleteSid($_eqLogic){ //OK
		//Logout and destroying SID
		log::add('synodownload', 'debug',' Destruction de la session - Début : ' . $_eqLogic->getName() );
		
		$url=config::byKey('SYNO.conf.url','synodownload');
		$sessionsid=$_eqLogic->getConfiguration('sid');

		$arrAPI=config::byKey('SYNO.API.Auth','synodownload');
			
		$apiName = 'SYNO.API.Auth';
		$apiPath = $arrAPI['path'];
		$apiVersion = $arrAPI['version'];

		if($sessionsid==null){
			log::add('synodownload', 'debug',' Pas de session à détruire ');
		}else{
			$fURL=$url.'/webapi/'.$apiPath.'?api=SYNO.API.Auth&method=Logout&version='.$apiVersion.'&session=DownloadStation&_sid='.$sessionsid;
			$json = synodownload::getCurlPage($fURL);
			$obj = json_decode($json);
			if($obj->success != "true"){
				log::add('synodownload', 'error',' Destruction de la session en erreur, code : ' . $obj->error->code );
				exit();
			}else{
				//authentification successful
				$_eqLogic->setConfiguration('sid','');
				$_eqLogic->save();
				log::add('synodownload', 'debug',' Destruction de la session - OK ');
			}
		}
		log::add('synodownload', 'debug',' Destruction de la session - Fin ');
	}
	
	public function initPlugin(){
		self::createURL();
		self::updateAPIs();
		foreach (synodownload::byType('synodownload') as $eqLogic) {
			log::add('synodownload', 'debug', ' Mise à jour station :' . $eqLogic->getName() );
			if ($eqLogic->getConfiguration('sid') != '' ){
				log::add('synodownload', 'debug', ' Suppression du sid de la station :' . $eqLogic->getName() );
				self::deleteSid($eqLogic);
			}
			self::getSid($eqLogic);
			//Purge du cache
			log::add('synodownload', 'debug', ' Purge du cache ' );
			$cache=cache::byKey('SYNO.Download.' .$eqLogic->getId(). '.n0');
			$cache->remove();
		}
	}
	
	private function recup_info($_eqLogic,$_eqLogic_id =null ){
		if($_eqLogic->getIsEnable()){
			try {
				
				global $_nb_dl_pause,$_nb_dl_resume,$_nb_dl_partage;
				$_nb_dl_pause=0;
				$_nb_dl_resume=0;
				$_nb_dl_partage=0;
				log::add('synodownload', 'debug', ' Récupération des statistiques de la station ' .$_eqLogic->getName());
				$obj=$_eqLogic->statistics($_eqLogic->getId());
				if (is_array($obj) || is_object($obj)){
					$changed = $_eqLogic->checkAndUpdateCmd('vitesse_dl', intval($obj->download_rate)) || $changed;
					$changed = $_eqLogic->checkAndUpdateCmd('vitesse_ul', intval($obj->upload_rate)) || $changed;
				}
				
				log::add('synodownload', 'debug', ' Récupération de la list des téléchargement de la station ' .$_eqLogic->getName());
				//Récupération du nombre de download précédent
				$n0=0;
				if ( cache::byKey('SYNO.Download.' .$_eqLogic->getId(). '.n0') ){
					$cache_n0=cache::byKey('SYNO.Download.' .$_eqLogic->getId(). '.n0');
					$n0=$cache_n0->getvalue();
				}
				
				$obj=$_eqLogic->listDownload($_eqLogic->getId());
				
				$i=0;
				if (is_array($obj) || is_object($obj)){
					foreach ($obj->task as $task) {
						$i++;
						if ($i > $n0 ){
							//Ajouter cmd
							$_eqLogic->createCmd($i);
						}
						// Mettre a jour
						$_eqLogic->updateCmd($i,$task);
					}
					for ($y = $i+1; $y <= $n0; $y++){
						$_eqLogic->removeCmd($y);
					}
					//Actualisation du nombre de download
					cache::set('SYNO.Download.' .$_eqLogic->getId(). '.n0', $i);
					
					log::add('synodownload', 'debug',' Récupération des nombres dl Totaux : T='.$i.' P='.$_nb_dl_pause.' R='.$_nb_dl_resume.' Px='.$_nb_dl_partage );
					$changed = $_eqLogic->checkAndUpdateCmd('nb_total_dl', intval($i)) || $changed;
					$changed = $_eqLogic->checkAndUpdateCmd('nb_dl_pause', intval($_nb_dl_pause)) || $changed;
					$changed = $_eqLogic->checkAndUpdateCmd('nb_dl_resume', intval($_nb_dl_resume)) || $changed;
					$changed = $_eqLogic->checkAndUpdateCmd('nb_dl_partage', intval($_nb_dl_partage)) || $changed;
			
				}
				if ($changed) {
					$_eqLogic->refreshWidget();
				}
				$_eqLogic->refreshWidget();
				
			} catch (Exception $e) {
				if ($_eqLogic_id != null) {
					log::add('synovideo', 'error', $e->getMessage());
				} else {
					log::add('synovideo', 'error', __('Erreur sur ', __FILE__) . $_eqLogic->getHumanName() . ' : ' . $e->getMessage());
				}
			}
		}	
	}
	
	public static function pull($_eqLogic_id = null){ //
		log::add('synodownload', 'debug',' Var $_eqLogic_id ' . $_eqLogic_id );
		if ($_eqLogic_id == null) {
			log::add('synodownload', 'debug',' Récupération de l\'état des stations - Début' );
			foreach (synodownload::byType('synodownload') as $eqLogic) {
				self::recup_info($eqLogic);
			}
			log::add('synodownload', 'debug',' Récupération de l\'état des stations - Fin' );
		}else{
			log::add('synodownload', 'debug',' Récupération de l\'état de la station - Début' );
			$eqLogic=synodownload::byId($_eqLogic_id);
			self::recup_info($eqLogic,$_eqLogic_id);
			log::add('synodownload', 'debug',' Récupération de l\'état de la station - Fin' );

		}
	}
	
	public static function convertSpeed($_speed) {
	   $resultat = $_speed;
		for ($i=0; $i < 8 && $resultat >= 1024; $i++) {
			$resultat = $resultat / 1024;
		}
		if ($i > 0) {
			return preg_replace('/,00$/', '', number_format($resultat, 2, ',', '')) . ' ' . substr('KMGTPEZY',$i-1,1) . 'o';
		} else {
			return $resultat . ' o';
		}
	}

	public static function convertState($_state) {
		switch ($_state) {
			case '1':
			case '11':
				return __('En attente', __FILE__);
			case '2':
				return __('Téléchargement', __FILE__);
			case '3':
				return __('Pause', __FILE__);
			case '4':
				return __('Finalisation', __FILE__);
			case '5':
				return __('Terminé', __FILE__);
			case '6':
				return __('Vérification', __FILE__);
			case '8':
				return __('Partage', __FILE__);
			case 'filehosting_waiting':
				return __('Attente hébergeur', __FILE__);
			case 'extracting':
				return __('Extraction', __FILE__);
			case '13':
				return __('Execution', __FILE__);
			case '105':
			case '106':
				return __('Pb d\'espace', __FILE__);
			case '101':
			case '113':
			case '':
				return __('Erreur', __FILE__);
			}
		return $_state;
	}
	
	//SYNO.DownloadStation.Info
	//	- getconfig
	//	- setserverconfig
	
	
	//SYNO.DownloadStation.Schedule
	// 	-getconfig
	//	-setconfig
	
	public function getSettingLocation($_eqLogicId){
//default_destination
//enable_delete_torrent_nzb_watch
//enable_torrent_nzb_watch
//torrent_nzb_watch_foder

		$obj=self::appelURL($_eqLogicId,'SYNO.DownloadStation2.Settings.Location','get',null,null);
		
		return $obj->data;

	}
	
	public function getSchedule($_eqLogic){ //OK
		
		$obj=self::appelURL($_eqLogic,'SYNO.DownloadStation.Schedule','getconfig',null,null);
		
		return $obj->data;
	}
	
	public function setSchedule($_eqLogic,$_activeS, $_activeSEmule=null ){ //OK
		
		$compl_URL='enabled=' . $_activeS;
		if ( $_activeSEmule != null ){$compl_URL='&emule_enabled=' . $_activeSEmule ; }
		
		self::appelURL($_eqLogic,'SYNO.DownloadStation.Schedule','setconfig',null,null);
	}
	
	//SYNO.DownloadStation.Task
	
	public function listDownload($_eqLogicId) { //OK

		$compl_URL='additional=["detail","transfer"]';
	
		$obj=self::appelURL($_eqLogicId,'SYNO.DownloadStation2.Task','list',null,$compl_URL);
		
		return $obj->data;
	}
	
	public function create($_eqLogicId,$_uri=null, $_file=null,$_destination, $_username , $_password, $_f_size, $_f_content) { //
		
		$boundary = "-----------------------------".md5(rand());
		$_download = null ;
		if ( $_destination == null ) {
			$obj=self::getSettingLocation($_eqLogicId);
		}
		if ( $_uri != null ){
			$compl_URL='type=url&url=["' . str_replace('&', '%26',$_uri) . '"]' ;
			$compl_URL= $compl_URL . '&destination="'. $obj->default_destination .'"';
			$compl_URL=$compl_URL . '&create_list=false';
		}
		if ( $_file != null ){
			log::add('synodownload', 'debug', 'File add' );
			$compl_URL=null;
			$ContentType='Content-Type: multipart/form-data; boundary='.$boundary;
			log::add('synodownload', 'debug', 'File add ContentType ' . $ContentType );
/*			$databinary=array(
			//	'api' 			=> 'SYNO.DownloadStation2.Task',
			//	'method' 		=> $method,
			//	'version' 		=> '2',
				'type' 			=> 'file',
				'file' 			=> '["torrent"]',
				'destination' 	=> $obj->default_destination,
				'create_list' 	=> 'false',
				'mtime'			=> $_SERVER["REQUEST_TIME"],
				'size' 			=> $_f_size,
				'torrent; filename='.$_file => 'Content-Type: application/octet-stream;'.$_f_content
			);
*/			
			$crlf = "\r\n";
			$databinary="--".$boundary.$crlf;
			$databinary.='Content-Disposition: form-data; name="api"'.$crlf;
			$databinary.='SYNO.DownloadStation2.Task'.$crlf;
			
			$databinary.="--".$boundary.$crlf;
			$databinary.='Content-Disposition: form-data; name="method"'.$crlf;
			$databinary.='create'.$crlf;
			
			$databinary.="--".$boundary.$crlf;
			$databinary.='Content-Disposition: form-data; name="version"'.$crlf;
			$databinary.='2'.$crlf;
			
			$databinary.="--".$boundary.$crlf;
			$databinary.='Content-Disposition: form-data; name="type"'.$crlf;
			$databinary.='"file"'.$crlf;
			
			$databinary.="--".$boundary.$crlf;
			$databinary.='Content-Disposition: form-data; name="file"'.$crlf;
			$databinary.='["torrent"]'.$crlf;
			
			$databinary.="--".$boundary.$crlf;
			$databinary.='Content-Disposition: form-data; name="destination"'.$crlf;
			$databinary.=$obj->default_destination . $crlf;
			
			$databinary.="--".$boundary.$crlf;
			$databinary.='Content-Disposition: form-data; name="create_list"'.$crlf;
			$databinary.='false'.$crlf;
			
			$databinary.="--".$boundary.$crlf;
			$databinary.='Content-Disposition: form-data; name="mtime"'.$crlf;
			$databinary.=$_SERVER["REQUEST_TIME"] .$crlf;
			
			$databinary.="--".$boundary.$crlf;
			$databinary.='Content-Disposition: form-data; name="size"'.$crlf;
			$databinary.=$_file .$crlf;
			
			$databinary.= "--".$boundary.$crlf;
			$databinary.='Content-Disposition: form-data; name="torrent"; filename="'.$_file. '"'.$crlf;
			//$databinary.='Content-Type: application/octet-stream'.$crlf;
			$databinary.='Content-Type: text/plain'.$crlf;
			$databinary.=$_f_content;
			$databinary.=$boundary. "--";			
			
			
			log::add('synodownload', 'debug', 'File add databinary ' . print_r($databinary) );
			
		}

		if ( $_username != null ){$compl_URL= $compl_URL . '&username' . $_username ; }//ne fonctionne pas
		if ( $_password != null ){$compl_URL= $compl_URL . '&password' . $_password ; }//ne fonctionne pas
		
	
		self::appelURL($_eqLogicId,'SYNO.DownloadStation2.Task','create',$_download,$compl_URL,$ContentType,$databinary);
	}
	
	public function delete($_eqLogicId,$_download=null, $_force='false') { //OK
		
		$compl_URL='force_complete=' . $_force ;
	
		self::appelURL($_eqLogicId,'SYNO.DownloadStation2.Task','delete',$_download,$compl_URL);
		self::pull();
	}
	
	public function pause($_eqLogicId,$_download=null) { //OK
				
		self::appelURL($_eqLogicId,'SYNO.DownloadStation2.Task','pause',$_download,null);
		self::pull();
	}
	
	public function resume($_eqLogicId,$_download=null  ) { //OK
		
		self::appelURL($_eqLogicId,'SYNO.DownloadStation2.Task','resume',$_download,null);	
		self::pull();
	}
	
	public function condition($_eqLogicId,$_type  ) { //OK
		
		$compl_URL='type=["emule"]&type_inverse=true';

		self::appelURL($_eqLogicId,'SYNO.DownloadStation2.Task', $_type.'_condition',null,$compl_URL);
		self::pull();
	}
	
	public function edit($_eqLogic,$_download=null, $_sharefolder=null ) { //OK

		$compl_URL='destination' . $_sharefolder ;
	
		self::appelURL($_eqLogic,'SYNO.DownloadStation.Task','edit',$_download,$compl_URL);
	}
	
	public function search($_eqLogicId, $_keyword){
		//action=%22search%22&keyword=%22test%22&api=SYNO.DownloadStation2.BTSearch&method=start&version=1
		$limit_search =500;
		//Lancement de la recherche
		$compl_URL='action="search"&keyword="' . $_keyword .'"';
		$eqlogic=synodownload::byId($_eqLogicId);
		
		//Récupération du resultat
		$obj=self::appelURL($_eqLogicId,'SYNO.DownloadStation2.BTSearch','start',null,$compl_URL);

		$compl_URL='sort_by=title&order=ASC&offset=0&limit='.$limit_search.'&id="'. $obj->data->id .'"';
		//Attente du résultat
		$i=0;
		do{
			$i++;
			$obj1=self::appelURL($_eqLogicId,'SYNO.DownloadStation2.BTSearch','list',null,$compl_URL);
			
		}while ($obj1->data->is_running == "true" || $i==100);

		return $obj1->data;
		
	}
		
	public function statistics($_eqLogicId) { // OK V2
				
		$obj=self::appelURL($_eqLogicId,'SYNO.DownloadStation2.Task.Statistic','get',null,null);
		
		return $obj->data;
	}
	
	
	public function toHtml($_version = 'dashboard') {
        
        $replace = $this->preToHtml($_version, array('#synoid#' => $this->getlogicalId()), true);
        if (!is_array($replace)) {
			return $replace;
		}
        $version = jeedom::versionAlias($_version);
		$replace['#text_color#'] = $this->getConfiguration('text_color');
		$replace['#version#'] = $_version;
        $replace['#synoid#'] = $this->getlogicalId();

        
		$cmd_refresh=$this->getCmd('action','refresh');
		$replace['#cmd_' . $cmd_refresh->getLogicalId() . '_id#'] = $cmd_refresh->getId();
		
		$cmd_condition=$this->getCmd('action','condition');
		$replace['#cmd_' . $cmd_condition->getLogicalId() . '_id#'] = $cmd_condition->getId();

		$cmd_vit_dl = $this->getCmd(null, 'vitesse_dl');
		if (is_object($cmd_vit_dl)) {
			$replace['#vitDL#'] = $this->convertSpeed($cmd_vit_dl->execCmd());
		}else {
			$replace['#vitDL#'] = __('N/A', __FILE__);
		}		
      
		$cmd_vit_ul = $this->getCmd(null, 'vitesse_ul');
		if (is_object($cmd_vit_ul)) {
			$replace['#vitUL#'] = $this->convertSpeed($cmd_vit_ul->execCmd());
		}else {
			$replace['#vitUL#'] = __('N/A', __FILE__);
		}

		$div_Download='';
		$div_Download_id=1;
		
		$cache_n0=cache::byKey('SYNO.Download.' . $this->getId(). '.n0');
		$n0=$cache_n0->getvalue();                
		
		if ($n0 != 0) {
			for ($i = 1; $i <= $n0; $i++) {
				$cmd_id_dl = $this->getCmd(null, 'id_dl_' . $i);
				if (is_object($cmd_id_dl)) {
					$id_DL= $cmd_id_dl->execCmd();
				}else {
					$id_DL= __('N/A', __FILE__);
				}
								
				$cmd_nom_dl = $this->getCmd(null, 'nom_dl_'.$i);
				if (is_object($cmd_nom_dl)) {
					$NameC= $cmd_nom_dl->execCmd();
					$NameR= substr($cmd_nom_dl->execCmd(),0,50);			
				}else {
					$NameC= __('N/A', __FILE__);
					$NameR= __('N/A', __FILE__);
				}
								
				$cmd_etat_dl = $this->getCmd(null, 'etat_dl_'.$i);
				if (is_object($cmd_etat_dl)) {
					$State= $cmd_etat_dl->execCmd();
				}else {
					$State= __('N/A', __FILE__);
				}
							
				$cmd_pourcentage_dl = $this->getCmd(null, 'pourcentage_dl_'.$i);
				if (is_object($cmd_pourcentage_dl)) {
					$Percent= $cmd_pourcentage_dl->execCmd();
				}else {
					$Percent= __('N/A', __FILE__);
				}
				
				$cmd=$this->getCmd('action','resume');
				$cmd_resume_id = $cmd->getId();
				$cmd=$this->getCmd('action','pause');
				$cmd_pause_id = $cmd->getId();
				$cmd=$this->getCmd('action','delete');
				$cmd_delete_id = $cmd->getId();
				
				if ( $i==($div_Download_id*5)-4 ){
					$hidden='display: none';
					if ( $i==1){
						$hidden='display: block';
					}
					$div_Download = $div_Download . '<div class="panel_list' .$div_Download_id . '" style="' . $hidden .'" id="panel_list' .$div_Download_id . '" >';
				}
				$div_Download = $div_Download . ' <!-- repere !i==  ' . $i .' -->';
				$div_Download = $div_Download . '
				<div class="Download" id="' . $id_DL . '">
					<div class="AffName" ><span Title="' . $NameC . '" >' . $NameR . '</span> </div>
					<div class="AffState" ><span >' . $State . '</span> </div>
					<div class="AffPercent" > <span >' . $Percent . '%</span> </div>
					<div class="AffAction" >';
				switch ($State) {
					case 'Pause' :
					case 'Terminé' :
					case 'Erreur' :
					case 'Pb d\'espace': 
						$div_Download = $div_Download . '
							<span class="cmd actdownload ' . $id_DL . ' resume "  data-cmd_id="' . $cmd_resume_id . '" data-dbid="' . $id_DL . '" ><i class="fas fa-play"></i></span>';
						break;
					case 'En attente' :
					case 'Téléchargement' :
					case 'Finalisation' :
					case 'Vérification' :
					case 'Partage' :
					case 'Attente hébergeur' : 
						$div_Download = $div_Download . 
						'	<span class="cmd actdownload ' . $id_DL . ' pause " data-cmd_id="' . $cmd_pause_id . '" data-dbid="' . $id_DL . '" ><i class="fas fa-pause"></i></span>';
						break;
					default:
						break;
				}
				$div_Download = $div_Download . '
						<span class=" cmd actdownload ' . $id_DL . ' delete " data-cmd_id="' . $cmd_delete_id . '" data-dbid="' . $id_DL . '" ><i class="fas fa-times"></i></span>
					</div><!-- fin class AffAction -->	
				</div><!-- fin class Download -->';
				
				if ( $i==($div_Download_id*5)){
					$div_Download = $div_Download . '</div> <!-- fin class panel_list -->';
					if ($i != $n0){
						$div_Download_id++;
					}
				}
			}
			
			if (  substr($div_Download, -14, 10) != 'panel_list'){
				$div_Download = $div_Download . '</div> <!-- fin class panel_list -->';
			}
		}else{
			$div_Download = '		
			<div class="panel_list1" style="display: block" id="panel_list1"> <!-- repere !i==  1 -->
				<div class="Download" id="dbid_241">
					<div class="AffName"><span title="Pas de téléchargement">Pas de téléchargement</span> </div>
					<div class="AffState"><span>N/A</span> </div>
					<div class="AffPercent"> <span>N/A</span> </div>
				</div><!-- fin class Download sans telechargement-->
			</div>';
		}
		$replace['#div_Download#'] = $div_Download;
		$replace['#nb_div_Download#'] = $div_Download_id;

        return $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'synodownload', 'synodownload')));
	}

}

class synodownloadCmd extends cmd {
	/*     * *************************Attributs****************************** */
	public static $_widgetPossibility = array('custom' => true);
	/*     * ***********************Methode static*************************** */

	/*     * *********************Methode d'instance************************* */

	public function execute($_options = array()) {
		
		$synodownload = $this->getEqLogic();
		log::add('synodownload', 'debug', $synodownload->getHumanName().' Commande ['.$this->getName() . '] id '. $this->getLogicalId() );
		
		switch($this->getLogicalId()) {
			case 'refresh':
				$synodownload->pull($synodownload->getId());
				break;
			case 'resume':
				log::add('synodownload', 'debug', $synodownload->getHumanName().' dbid : ' . $_options['dbid']);
				$download=$_options['dbid'];
				$synodownload->resume($synodownload->getId() ,$download);
				break;
			case 'pause':
				log::add('synodownload', 'debug', $synodownload->getHumanName().' dbid : ' . $_options['dbid']);
				$download=$_options['dbid'];
				$synodownload->pause($synodownload->getId() ,$download);
				break;
			case 'delete':
				log::add('synodownload', 'debug', $synodownload->getHumanName().' dbid : ' . $_options['dbid']);
				$download=$_options['dbid'];
				$synodownload->delete($synodownload->getId() ,$download);
				break;
			case 'condition':
				if (!empty($_options['title'])){
					log::add('synodownload', 'debug', $synodownload->getHumanName().' Action : ' . $_options['title']);
					$action=$_options['title'];
					$synodownload->condition($synodownload->getId() ,$action);
				}
				break;
				
			default:
				throw new Exception(__('Commande non reconnu', __FILE__));
		}
		return false;
	}

	/*     * **********************Getteur Setteur*************************** */
}