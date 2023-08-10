<?php if(!defined('PLX_ROOT')) exit; ?>
<?php
	
	# Control du token du formulaire
	plxToken::validateFormToken($_POST);
	
	# Liste des langues disponibles et prises en charge par le plugin
	$aLangs = array($plxAdmin->aConf['default_lang']);
	
	# Si le plugin plxMyMultiLingue est installé on filtre sur les langues utilisées
	# On garde par défaut le fr si aucune langue sélectionnée dans plxMyMultiLingue
	if(defined('PLX_MYMULTILINGUE')) {
		$langs = plxMyMultiLingue::_Langs();
		$multiLangs = empty($langs) ? array() : explode(',', $langs);
		$aLangs = $multiLangs;
	}
	
	#onglet ouvert par défaut
	$tab='#onglet-0';
	
	# si maj
	if(!empty($_POST)) {
		if(!file_exists(PLX_ROOT.'robots.txt')) touch(PLX_ROOT.'robots.txt');
		if(!file_exists(PLX_ROOT.'humans.txt')) touch(PLX_ROOT.'humans.txt');
		$plxPlugin->setParam('openSearchAdult', $_POST['openSearchAdult'], 'string');
		$plxPlugin->setParam('openSearchON', $_POST['openSearchON'], 'numeric');
		$plxPlugin->setParam('ogON', $_POST['ogON'], 'numeric');
		$plxPlugin->setParam('twON', $_POST['twON'], 'numeric');
		$plxPlugin->setParam('ldAS', $_POST['ldAS'], 'string');
		$plxPlugin->setParam('ldON', $_POST['ldON'], 'numeric');
		$plxPlugin->setParam('canON', $_POST['canON'], 'numeric');
		$plxPlugin->setParam('canType', $_POST['canType'], 'numeric');
        $plxPlugin->setParam('removeCat', $_POST['removeCat'], 'string');
        $plxPlugin->setParam('removeStat', $_POST['removeStat'], 'string');
		$plxPlugin->setParam('exArtLinkON', $_POST['exArtLinkON'], 'numeric');
		$plxPlugin->setParam('prevNextON', $_POST['prevNextON'], 'numeric');
		$plxPlugin->setParam('send404ON', $_POST['send404ON'], 'numeric');
		$plxPlugin->setParam('cloneMode', $_POST['cloneMode'], 'string');
		
		
		/* EXEMPLES
			$plxPlugin->setParam('str', $_POST['str'], 'string');
			$plxPlugin->setParam('cdata', $_POST['cdata'], 'cdata');
		*/
		
		# sauvegarde données modifiées
		$plxPlugin->saveParams();
		
		if($_POST['robotsTxt'] != file_get_contents(PLX_ROOT.'robots.txt')) {
			file_put_contents(PLX_ROOT.'robots.txt',$_POST['robotsTxt']);
		}	
		if($_POST['humansTxt'] != file_get_contents(PLX_ROOT.'humans.txt')) {
			file_put_contents(PLX_ROOT.'humans.txt',$_POST['humansTxt']);
		}
		
		if($_POST['#onglet-1'])  $tab='#onglet-1';
		if($_POST['#onglet-2'])  $tab='#onglet-2';
		if($_POST['#onglet-3'])  $tab='#onglet-3';
		if($_POST['#onglet-4'])  $tab='#onglet-4';
		if($_POST['#onglet-5'])  $tab='#onglet-5';
		if($_POST['#onglet-6'])  $tab='#onglet-6';
		if($_POST['#onglet-7'])  $tab='#onglet-7';
		if($_POST['#onglet-8'])  $tab='#onglet-8';
		if($_POST['#onglet-9'])  $tab='#onglet-9';
		if($_POST['#onglet-10']) $tab='#onglet-10';
		# add any more need
		
		# renvoi sur la page aprés traitement
		header('Location: parametres_plugin.php?p='.basename(__DIR__).$tab);
		exit;
	}
	
	#initialisation variables 
	$var['removeCat'] 		= $plxPlugin->getParam('removeCat')			=='' ? ''			: $plxPlugin->getParam('removeCat');
	$var['removeStat'] 		= $plxPlugin->getParam('removeStat')		=='' ? ''			: $plxPlugin->getParam('removeStat');
	$var['openSearchON'] 	= $plxPlugin->getParam('openSearchON')		=='' ? 0			: $plxPlugin->getParam('openSearchON');
	$var['openSearchAdult'] = $plxPlugin->getParam('openSearchAdult')	=='' ? 'false'		: $plxPlugin->getParam('openSearchAdult');
	$var['twON'] 			= $plxPlugin->getParam('twON')				=='' ? 0			: $plxPlugin->getParam('twON');
	$var['ldAS'] 			= $plxPlugin->getParam('ldAS')				=='' ? ''			: $plxPlugin->getParam('ldAS');
	$var['ldON'] 			= $plxPlugin->getParam('ldON')				=='' ? 0			: $plxPlugin->getParam('ldON');
	$var['ogON'] 			= $plxPlugin->getParam('ogON')				=='' ? 0			: $plxPlugin->getParam('ogON');
	$var['canON'] 			= $plxPlugin->getParam('canON')				=='' ? 1			: $plxPlugin->getParam('canON');
	$var['canType'] 		= $plxPlugin->getParam('canType')			=='' ? 0			: $plxPlugin->getParam('canType');
	$var['exArtLinkON'] 	= $plxPlugin->getParam('exArtLinkON')		=='' ? 0			: $plxPlugin->getParam('exArtLinkON');
	$var['prevNextON'] 		= $plxPlugin->getParam('prevNextON')		=='' ? 0			: $plxPlugin->getParam('prevNextON');
	$var['send404ON'] 		= $plxPlugin->getParam('send404ON')			=='' ? 0			: $plxPlugin->getParam('send404ON');
	$var['cloneMode'] 		= $plxPlugin->getParam('cloneMode')			=='' ? 0			: $plxPlugin->getParam('cloneMode');
	
	
?>

<!-- formulaire -->
<h3><?php $plxPlugin->lang('L_CONFIG') ?></h3>
<div id="onglets">
	<form  id="form_<?= basename(__DIR__) ?>" action="parametres_plugin.php?p=<?= basename(__DIR__) ?>" method="post" class="<?= basename(__DIR__) ?>">
		<div class="onglet" data-title="URL canonique" class="<?php if($tab== '#onglet-0') echo 'active'; ?>">
			<h4>canonical</h4>
			<fieldset>
				<legend>URLs</legend>
				<p>
					<label for="canON" ><?php $plxPlugin->lang('L_ACTIVATE') ?>&nbsp;:</label>
					<?php plxUtils::printSelect('canON',array('1'=>L_YES,'0'=>L_NO),$var['canON']); ?>
				</p>
				<!--<p>
					<label for="canType" ><?php $plxPlugin->lang('L_KEEP_NATIVE') ?>&nbsp;:</label>
					<?php plxUtils::printSelect('canType',array('1'=>L_YES,'0'=>L_NO),$var['canType']); ?>
				</p>
				<p>
					<?php $plxPlugin->lang('L_INFO_REWRITE') ?>
					
					
				</p>-->
			</fieldset>
		</div>
		<div class="onglet" data-title="sitemap"  class="<?php if($tab== '#onglet-1') echo 'active'; ?>">
			<h4>exclusion du sitemap</h4>
			<fieldset>
				<legend><?php $plxPlugin->lang('L_EXCLUDE_SITEMAP'); ?></legend>
				<?php  // a lie avec met no index no follow ?>
				<div class="flex">
					<label for="removeCat" class="flex">
						<span><?php $plxPlugin->lang('L_REMOVE_SITEMAP_CATEGORY'); ?></span>
						<?php plxUtils::printInput('removeCat',$plxPlugin->getParam('removeCat'),'text','') ?>
					</label>
					<details>
						<summary><?php $plxPlugin->lang('L_LIST_CATEGORY'); ?></summary>
						<ul>
							<?php
								foreach($plxAdmin->aCats as $Cat_num => $Cat_info) {
									if($Cat_info['active']==1) {
										echo '<li>N°<b>'.$Cat_num.'</b> - '. $Cat_info['name'].'</li>'.PHP_EOL;    
									}
								}
							?>
						</ul>
					</details>
				</div>
				
				<div class="flex">
					<label for="removeStat" class="flex">
						<span><?php $plxPlugin->lang('L_REMOVE_SITEMAP_STATIC'); ?></span>
						<?php plxUtils::printInput('removeStat',$plxPlugin->getParam('removeStat'),'text','') ?>
					</label>
					<details><summary><?php $plxPlugin->lang('L_LIST_STATIC'); ?></summary>
						<ul>
							<?php
								foreach($plxAdmin->aStats as $stat_num => $stat_info) {
									if($stat_info['active']==1) {
										echo '<li>N°<b>'.$stat_num.'</b> - '. $stat_info['name'].'</li>'.PHP_EOL;    
									}
								}
							?>
						</ul>
					</details>
				</div>
				<details><summary>preview sitemap</summary>
					<iframe src="<?=PLX_ROOT?>sitemap.php" style="width:100%;min-height:15em"></iframe>
				</details>
			</fieldset>			
			<div style="font-size:0.8em;max-width:60vw;margin:0.5em auto;display:flex;gap:1rem;align-items:center;" class="warning"><b class="help" style="padding:0 0.5em;">?</b><div><?php $plxPlugin->lang('L_EXCLUDE_SITEMAP_HELP'); ?></div>
			</div>
		</div>
		<div class="onglet" data-title="open graph">
			<h4>meta OG</h4>
			
			<fieldset>
				<legend><?php $plxPlugin->lang('L_ACTIVATE') ?>&nbsp;metas OpenGraph</legend>
				<p>
					<label for="ogON" ><?php $plxPlugin->lang('L_ACTIVATE') ?>&nbsp;:</label>
					<?php plxUtils::printSelect('ogON',array('1'=>L_YES,'0'=>L_NO),$var['ogON']); ?>
				</p>
			</fieldset>
			<div class="warning" style="display:flex;gap:0.15rem;"><b class="help">?</b> :<a href="https://fr.wikipedia.org/wiki/Open_Graph_Protocol" target="_blank">wikipedia</a>.</div>
		</div>
		<div class="onglet" data-title="json ld">
			<h4>Json LD</h4>
			<fieldset>
				<legend><?php $plxPlugin->lang('L_ACTIVATE') ?>&nbsp; json metas</legend>
				<p>
					<label for="ldON" ><?php $plxPlugin->lang('L_ACTIVATE') ?>&nbsp;:</label>
					<?php plxUtils::printSelect('ldON',array('1'=>L_YES,'0'=>L_NO),$var['ldON']); ?>
				</p>
			</fieldset>
			
			<fieldset>
				<legend><?php $plxPlugin->lang('L_ADD') ?> <?php $plxPlugin->lang('L_ADD_SAMEAS') ?>&nbsp;: </legend>
				<p>
					<label for="ldAS"><?php $plxPlugin->lang('L_ACCOUNT') ?>&nbsp;:</label>
					<input type="text" id="sameAs" >
					<button type="button" id="addAs"><?php $plxPlugin->lang('L_ADD') ?></button>
				</p>
				<input type="hidden" id="ldAS" name="ldAS" value="<?= $var['ldAS'] ?>">
				<p><?php $plxPlugin->lang('L_LIST_SAMEAS')?></p>
				<div id="listNetwork"></div>
				<script>
					const addAs = document.querySelector("#addAs");
					const sameAs = document.querySelector("#sameAs");
					const sameAsLinks = document.querySelector("#ldAS");
					const ListNetwork = document.querySelector('#listNetwork')
					addAs.addEventListener("click", modifyList);
					function modifyList() {
						let ploder = "";
						if (sameAsLinks.value != "") {
							ploder = " , ";
						}
						if (sameAs.value.trim() != "") {
							sameAsLinks.value = sameAsLinks.value + ploder + sameAs.value.trim();
							sameAs.value = "";
						}
						showList();
					}
					function showList() {
						ListNetwork.innerHTML='';
						let values=sameAsLinks.value.trim();
						let SocialNetworks = values.split(" , ");
						if (SocialNetworks.length > 0 && SocialNetworks[0] !="") {
							const ul = document.createElement("ul");
							SocialNetworks.forEach(function(item) {
								const li = document.createElement('li');
								li.textContent=item;
								const anc = document.createElement('a');
								anc.textContent='X';
								anc.setAttribute('onClick','sameAsLinks.value=delItem(\''+item+'\');showList(sameAsLinks.value);');
								anc.setAttribute('title','delete');
								li.append(anc);
								ul.append(li);  
							});
							ListNetwork.append(ul);
						}
						else {
							ListNetwork.innerHTML='0';
						}
					}
					
					function delItem(value) {
						let SocialNetworks = sameAsLinks.value.split(" , ");
						return SocialNetworks.filter(function (e) {
							return e != value;
						});
					}
					showList();
				</script>
			</fieldset>
			<div class="warning"><a href="https://validator.schema.org/" target="_blank">Validator schema.org</a></div>
		</div>		
		<div class="onglet" data-title="twitter card">
			<h4>meta twitter card</h4>
			<fieldset>
				<legend><?php $plxPlugin->lang('L_ACTIVATE') ?> meta twitter</legend>
				<p>
					<label for="twON" ><?php $plxPlugin->lang('L_ACTIVATE') ?>&nbsp;:</label>
					<?php plxUtils::printSelect('twON',array('1'=>L_YES,'0'=>L_NO),$var['twON']); ?>
				</p>
			</fieldset>
		</div>
		<div class="onglet" data-title="robots txt">
			<h4>fichier robots.txt</h4>
			<fieldset>
			<legend>edition</legend>
			<textarea name="robotsTxt"style="width:100%" ><?php if(file_exists(PLX_ROOT.'robots.txt')){echo file_get_contents(PLX_ROOT.'robots.txt');}?></textarea>
			<div class="warning" style="display:flex;gap:0.15rem;"><b class="help">?</b> :<a href="https://fr.wikipedia.org/wiki/Protocole_d%27exclusion_des_robots" target="_blank">wikipedia</a>.</div>
			</fieldset>
			</div>
			<!-- opensearch si plugin plxMySearch actif configuré en $_GET -->
			<?php	if (class_exists('plxMySearch')&& $plxAdmin->plxPlugins->aPlugins['plxMySearch']->getParam('method') == 'get') { 
				$plxMotor = plxMotor::getInstance();
				$mysearch =  $plxAdmin->plxPlugins->aPlugins['plxMySearch'];
			?>
			<div class="onglet" data-title="Open Search">
				<fieldset>
					<legend>Votre formulaire de recherche dans le navigateur du visiteur</legend>
					<p><label for="openSearchON"><?php $plxPlugin->lang('L_ACTIVATE') ?>&nbsp;:</label><?php plxUtils::printSelect('openSearchON',array('1'=>L_YES,'0'=>L_NO),$var['openSearchON']); ?></p>
					<p><label for="openSearchAdult"><?php $plxPlugin->lang('L_ADULT_CONTENT') ?>&nbsp;:</label>
					<?php plxUtils::printSelect('openSearchAdult',array('true'=>L_YES,'false'=>L_NO),$var['openSearchAdult']); ?></p>
					<?php if($var['openSearchON'] === '1')  {
						if(!file_exists(PLX_ROOT.'opensearch.xml')) {
							touch(PLX_ROOT.'opensearch.xml');
							$xml= '<?xml version="1.0" encoding="UTF-8"?>
							<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/">
							<ShortName>'.$plxMotor->aConf['title'].'</ShortName>
							<Description>'. $plxMotor->aConf['description'].'</Description>
							<Image type="image/x-icon">favicon.ico</Image>
							<Image type="image/png" height="32" width="32">'. $plxMotor->urlRewrite('plugins/plxMySearch/icon.png') .'</Image>
							<AdultContent>'.$var['openSearchAdult'] .'</AdultContent>
							<Language>'.$plxMotor->aConf['default_lang'].'</Language>
							<InputEncoding>UTF-8</InputEncoding>
							<Url type="text/html" method="get" template="'.$plxMotor->urlRewrite('?'.$mysearch->getParam('url')).'?searchfield={searchTerms}"/>
							</OpenSearchDescription>';
							file_put_contents(PLX_ROOT.'opensearch.xml',$xml);
							
						}?>			
						<p><?php $plxPlugin->lang('L_PREVIEW_OPENSEARCH') ?></p>
						<textarea style="width:100%;min-height:auto;" rows="11" readonly><?php echo file_get_contents(PLX_ROOT.'opensearch.xml')?></textarea>
						<?php }
						else {
							if(file_exists(PLX_ROOT.'opensearch.xml')) {
								unlink(PLX_ROOT.'opensearch.xml');
							}
						}
					?>
				</fieldset>
			</div>
			<?php } ?>		
			<div class="onglet" data-title="humans txt" >
				<h4>humans.txt</h4>
				<fieldset>
					<legend>edition</legend>
					<textarea name="humansTxt"style="width:100%" ><?php if(file_exists(PLX_ROOT.'humans.txt')){echo file_get_contents(PLX_ROOT.'humans.txt');}else{echo '  _     _   _      _   _            _         __         _     _     ____
 +-+-+-+-+-+-+-+-+-+-+
 |h|u|m|a|n|s|.|t|x|t|
 +-+-+-+-+-+-+-+-+-+-+
 ';}?></textarea>
					<div class="warning" style="display:flex;gap:0.15rem;"><b class="help">?</b> :<a href="https://fr.wikipedia.org/wiki/Humans.txt" target="_blank">wikipedia</a>.</div>
				</fieldset>
			</div>
		<div class="onglet" data-title="Extra">
			<h4>page Article</h4>
			<fieldset>
				<legend><?php $plxPlugin->lang('L_EXCLUDE_SELF_LINKS') ?></legend>
				<p>
					<label for="exArtLinkON" ><?php $plxPlugin->lang('L_EXCLUDE') ?>&nbsp;:</label>
					<?php plxUtils::printSelect('exArtLinkON',array('1'=>L_YES,'0'=>L_NO),$var['exArtLinkON']); ?>
				</p>
			</fieldset>
			<fieldset>
			<legend><?php $plxPlugin->lang('L_LINK_PREV_NEXT') ?></legend>
			<p>
				<label for="prevNextON" ><?php $plxPlugin->lang('L_PRINT_PREV_NEXT') ?>&nbsp;:</label>
				<?php plxUtils::printSelect('prevNextON',array('1'=>L_YES,'0'=>L_NO),$var['prevNextON']); ?>
			</p>
			</fieldset>
			<h4><?php $plxPlugin->lang('L_ADD_EXTRA_MODE_FILTER') ?></h4>
			<fieldset>
			<legend><?php $plxPlugin->lang('L_SEND_404') ?></legend>
			<p>
				<label for="send404ON" ><?php $plxPlugin->lang('L_SEND_UNKNOWN_T_404') ?>&nbsp;:</label>
				<?php plxUtils::printSelect('send404ON',array('1'=>L_YES,'0'=>L_NO),$var['send404ON']); ?>
			</p>
			<label><?php echo $plxPlugin->lang('L_ADD_EXTRA_MODE'); ?></label> 
	<?php
			plxUtils::printInput('cloneMode',$plxPlugin->getParam('cloneMode'),'text','20-255');
			?>
			</p>
			</fieldset>
		</div>
			<p class="in-action-bar">
				<?php echo plxToken::getTokenPostMethod() ?>
				<input type="submit" name="submit" value="<?php $plxPlugin->lang('L_SAVE') ?>" />
			</p>
	</form>
	<script src="<?= PLX_ROOT.'plugins/'.basename(__DIR__)?>/js/onglets.js"></script>
		<script>
		let txtB = document.querySelector('[name="humansTxt"]');
		let txtC = document.querySelector('[name="robotsTxt"]');
		let contentB = txtB.innerHTML;	
		let contentC = txtC.innerHTML;	
		function escapeRegex(string) {	
			return string.replace(/[\\]/g, '\\$&');
		} 
		(function() {
				txtB.innerHTML=  escapeRegex(contentB);  
				txtC.innerHTML=  escapeRegex(contentC);  
		}())	
	</script>
	<link rel="stylesheet" href="<?= PLX_ROOT.'plugins/'.basename(__DIR__)?>/css/admin.css" media="screen">
