<?php
	/**
		* Plugin 	canonique,opengraph,ld-json,....
		* version   	2.0.1	17/01/2024
		* Licence   	GNU General Public License v3.0 
		* @author	Cyrille Griboval.
	**/
	
	
	class extra_SEO extends plxPlugin {
		
		
		public $lang = '';
		public $links ='<!--nav prevnext-->';
		
        const BEGIN_CODE = '<?php' . PHP_EOL;
        const END_CODE = PHP_EOL . '?>';		
		
		
		/**
			* Constructeur de la classe
			*
			* @param	default_lang	langue par défaut
			* @return	stdio
			* @author	Stephane F
		**/
		public function __construct($default_lang) {
			
			global $plxShow;
			global $plxMotor;	
			
			
			# appel du constructeur de la classe plxPlugin (obligatoire)
			parent::__construct($default_lang);
			
			
			
			# droits pour accèder à la page config.php du plugin
			$this->setConfigProfil(PROFIL_ADMIN);
			
			
			# déclaration des hooks
			$this->addHook('plxMotorParseArticle', 'plxMotorParseArticle');
			$this->addHook('ThemeEndHead', 'ThemeEndHead');
			$this->addHook('SitemapBegin', 'SitemapBegin');
			$this->addHook('plxShowLastArtList', 'plxShowLastArtList');
			$this->addHook('plxMotorPreChauffageBegin','plxMotorPreChauffageBegin');
			$this->addHook('IndexBegin','IndexBegin');
			$this->addHook('IndexEnd','IndexEnd');
			
			
		}
		
		
		public function plxMotorPreChauffageBegin() {
			if($this->getParam('send404ON') == 1) {		
				echo self::BEGIN_CODE;
			?>		
			include(PLX_ROOT.'plugins/<?= basename(__DIR__) ?>/preHeat.php');
			return true;
			<?php
				echo self::END_CODE;	
			}
		}	
		
		/*
  			* injection des scripts de metadonnées ld-json aux articles
			* @ author G.Cyrillus
			* $this->articleOG(article);
		*/
		public function plxMotorParseArticle() {
			echo self::BEGIN_CODE;
		?>
		$plxMotor = plxMotor::getInstance();
		$plugin = $plxMotor->plxPlugins->aPlugins['<?= __CLASS__ ?>'];
		
		if($plxMotor->mode =='article' and $plugin->getParam('prevNextON') =='1') $art['content'] .=$plugin->links;	
		if($plxMotor->mode !=='' and ($plxMotor->mode =='home' or $plxMotor->mode =='article' or $plxMotor->mode =='categorie' or $plxMotor->mode =='tags' or $plxMotor->mode =='archives')){
		if ($art['chapo'] !='' ) {
		
		if ($plugin->getParam('ogON') =='1' and $plxMotor->mode =='article') $art['chapo'] .= $plugin->articleOG($art);
		if ($plugin->getParam('ldON') =='1') 	$art['chapo'] .=   $plugin->articleLD($art);
		}
		else {
		if ($plugin->getParam('ogON') =='1' and $plxMotor->mode =='article') $art['content'] .= $plugin->articleOG($art);
		if ($plugin->getParam('ldON') =='1') 	$art['content'] .=   $plugin->articleLD($art);
		}
		}
		<?php
            echo self::END_CODE;
		}
		
		
		
		public function IndexBegin(){
			echo self::BEGIN_CODE;
		?>
		$plxShow  = plxShow::getInstance();
		$plxMotor = plxMotor::getInstance();
		$plugin = $plxMotor->plxPlugins->aPlugins['<?= __CLASS__ ?>'];
		if($plxShow->plxMotor->mode =='article') {	
		}
		if ($plugin->getParam('prevNextON') =='1' and $plxMotor->mode =='article') {
		$formatPrev = '<a href="#prevUrl" title="#prevTitle" rel="prev">&laquo; <span>#prevArt</span></a> ';
		$formatNext = ' <a href="#nextUrl" title="#nextTitle" rel="next"><span>#nextArt</span> &raquo;</a>';
		$ordre = preg_match('/asc/',$plxShow->plxMotor->tri)?'sort':'rsort';
		$plugin->links = '';
		
		if( $plxShow->catId()!= "home") { // Des articles parmi les articles d'une catégorie
		$ID_CAT = str_replace(',', "|",str_pad ($plxShow->catId(), 3, '0', STR_PAD_LEFT));
		$aFiles = $plxShow->plxMotor->plxGlob_arts->query('/^[0-9]{4}.((?:[0-9]|home|,)*(?:' .$ID_CAT. ')(?:[0-9]|home|,)*).[0-9]{3}.[0-9]{12}.[a-z0-9-]+.xml$/','art',$ordre,0,false,'before');
		} else { // Des articles parmi tous les articles ? ou on affiche rien ?
		$aFiles = $plxShow->plxMotor->plxGlob_arts->query('/[0-9]{4}.[home|0-9,]*.[0-9]{3}.[0-9]{12}.[a-z0-9-]+.xml$/','art',$ordre,0,false,'before');
		}
		
		if(!is_array($aFiles)) @$aFiles[] = $aFiles;// php 8 tousse ici
		arsort($aFiles);
		$key = array_search(basename($plxShow->plxMotor->plxRecord_arts->f('filename')), $aFiles);
		$prevUrl = $prev = isset($aFiles[$key-1])? $aFiles[$key-1] : false;
		$nextUrl = $next = isset($aFiles[$key+1])? $aFiles[$key+1] : false;
		
		$plxGlob_arts = clone $plxShow->plxMotor->plxGlob_arts;
		
		if($prev AND preg_match('/([0-9]{4}).[home|0-9,]*.[0-9]{3}.[0-9]{12}.([a-z0-9-]+).xml$/',$prev,$capture))
		$prevUrl=$plxShow->plxMotor->urlRewrite('?article'.intval($capture[1]).'/'.$capture[2]);
		if ($prev){
		$art = $plxShow->plxMotor->parseArticle(PLX_ROOT.$plxShow->plxMotor->aConf['racine_articles'].$prev);
		$nextTitle = STRIP_TAGS($art['title']);
		}
		if($next AND preg_match('/([0-9]{4}).[home|0-9,]*.[0-9]{3}.[0-9]{12}.([a-z0-9-]+).xml$/',$next,$capture))
		$nextUrl=$plxShow->plxMotor->urlRewrite('?article'.intval($capture[1]).'/'.$capture[2]);
		if ($next) {
		$art = $plxShow->plxMotor->parseArticle(PLX_ROOT.$plxShow->plxMotor->aConf['racine_articles'].$next);
		$prevTitle = STRIP_TAGS($art['title']);
		}
		if($ordre=='rsort') { 
		$dummy=$prevUrl; $prevUrl=$nextUrl; $nextUrl=$dummy; 
		}
		if($prevUrl) {
		$plugin->links = str_replace('#prevUrl', $prevUrl, $formatPrev);
		$plugin->links = str_replace('#prevTitle', $prevTitle, $plugin->links);
		$plugin->links = str_replace('#prevArt', $plugin->getlang('L_PREV_ART'), $plugin->links);
		}
		if($nextUrl) {
		$plugin->links .= str_replace('#nextUrl', $nextUrl, $formatNext);
		$plugin->links = str_replace('#nextTitle', $nextTitle, $plugin->links);
		$plugin->links = str_replace('#nextArt', $plugin->getlang('L_NEXT_ART'), $plugin->links);
		}
		return $plugin->links;
		}
		
		<?php
            echo self::END_CODE;		
		}
		
		
		
		public function indexEnd() {
			echo self::BEGIN_CODE;
		?>	
		$output = str_replace('<!--nav prevnext-->', ob_get_clean().'<nav id="<?= __CLASS__ ?>" class="prevNext">'.$plugin->links.'</nav>', $output);
		if(version_compare(PLX_VERSION, '5.8.9', ">")) $output = str_replace($plxShow->pageUrl(), ob_get_clean().$plxShow->plxMotor->urlRewrite( str_replace($plxShow->plxMotor->racine, '',$plxShow->pageUrl())), $output);
		<?php
            echo self::END_CODE;		
		}
		
		public function ThemeEndHead() {
			
			echo self::BEGIN_CODE;
		?>
		$plugin = $plxMotor->plxPlugins->aPlugins['<?= __CLASS__ ?>'];
		$pagination='';	
		$reqUri=   $plxShow->plxMotor->get;
		preg_match('/(\/?page[0-9]+)$/', $reqUri, $matches);		
		if ($plugin->getParam('canON') =='1') {
		# est ce une page numérotée
		if( $matches) $pagination =$matches[0];
		if($plxShow->catId(true) AND intval($plxShow->catId()) =='0') echo '	<link rel="canonical" href="'.$plxShow->plxMotor->urlRewrite().$pagination.'" />'.PHP_EOL  ;
		if($plxShow->plxMotor->mode=='categorie' AND $plxShow->catId(true) AND intval($plxShow->catId()) !='0') echo '	<link rel="canonical" href="'.$plxShow->plxMotor->urlRewrite('?categorie'. intval($plxShow->catId()).'/'.$plxShow->plxMotor->aCats[str_pad($plxShow->catId(),3,0,STR_PAD_LEFT)]['url']).$pagination.'" />'.PHP_EOL  ;
		if($plxShow->plxMotor->mode=='article'  AND $plxShow->plxMotor->plxRecord_arts->f('numero')) echo PHP_EOL.'	<link rel="canonical" href="'.$plxShow->plxMotor->urlRewrite('?article' . intval($plxShow->plxMotor->plxRecord_arts->f('numero')) . '/' . $plxShow->plxMotor->plxRecord_arts->f('url')).'" />'.PHP_EOL  ;
		if( $plxShow->plxMotor->mode=='static'  ) { 
		echo '	<link rel="canonical" href="'.$plxShow->plxMotor->urlRewrite('?static'. intval($plxShow->staticId()).'/'.$plxShow->plxMotor->aStats[str_pad($plxShow->staticId(),3,0,STR_PAD_LEFT)]['url']).'" />'.PHP_EOL ;
		}
		else{
		# enfin on regarde si il s'agit d'un plugin qui squatte les pages statiques			
		foreach($plxShow->plxMotor->plxPlugins->aPlugins as $plug){				
		if($plug->getParam('url') == $plxShow->plxMotor->mode)  echo '	<link rel="canonical" href="'.$plxShow->plxMotor->urlRewrite('?'.$_SERVER['QUERY_STRING']).'"/>'.PHP_EOL;
		}
		} 
		}
		# meta :og du site
		$plugin->websiteOG();
		
		# meta :twitter du site
		$plugin->websiteTw();
		
		# ld-json du site
		if ($plugin->getParam('ldON') =='1') 	 $plugin->websiteLD();
		
		if($plxShow->plxMotor->mode =='categorie') {	
		# ld-json : fil d'ariane categorie
		if ($plugin->getParam('ldON') =='1') 	 $plugin->breadcrumbsLD($plxMotor->mode,$plxShow->plxMotor->aCats[$plxShow->plxMotor->cible]['name'] ) ;
		}
		
		if($plxShow->plxMotor->mode =='archives' ) {
		# Pas d'indexation des pages "archives"	
		echo PHP_EOL.'	<meta name="robots" content="noindex,nofollow">';	
		# ld-json : fil d'ariane archives
		if ($plugin->getParam('ldON') =='1') 	 $plugin->breadcrumbsLD($plxShow->plxMotor->mode, plxDate::formatDate($plxShow->plxMotor->cible, $plxShow->getLang(strtoupper($plxShow->plxMotor->mode)).' #month #num_year(4)') ) ;
		}
		if($plxShow->plxMotor->mode =='tags' ) {
		# Pas d'indexation des pages "mots clés"	
		// echo PHP_EOL.'	<meta name="robots" content="noindex,nofollow">';
		}
		
		# ajoute le moteur de recherche du site au navigateur
		if(@class_exists('plxMySearch') && $plxMotor->plxPlugins->aPlugins['plxMySearch']->getParam('method') == 'get' && $plxShow->plxMotor->aConf['urlrewriting']) {
		if($plugin->getParam('openSearchON') == 1) echo '	<link rel="search" type="application/opensearchdescription+xml" title="'.$plxShow->plxMotor->aConf['title'].'" href="'.$plxShow->plxMotor->urlRewrite('opensearch.xml').'">';
		}
		if($plxShow->plxMotor->mode =='home' ) {
		# ld-json : fil d'ariane 
		if( $matches) $pagination =$matches[0];
		if ($plugin->getParam('ldON') =='1') 	 $plugin->breadcrumbsLD($plxShow->plxMotor->mode,$plxShow->getLang('HOME').' '.$pagination )  ;
		}

		<?php
			
            echo self::END_CODE;
			
		}
		
		public function plxShowLastArtList() {	
			$plxShow  = plxShow::getInstance();
			if($plxShow->mode() =='article' AND $plxShow->plxMotor->plxPlugins->aPlugins['extra_SEO']->getParam('exArtLinkON') == 1 ) {
				unset($plxShow->plxMotor->plxGlob_arts->aFiles[str_pad($plxShow->artId(), 4, "0", STR_PAD_LEFT)]); $plxShow->plxMotor->plxGlob_coms->aFiles = array_diff_key($plxShow->plxMotor->plxGlob_coms->aFiles, array_filter(
				$plxShow->plxMotor->plxGlob_coms->aFiles, 
				function ($value, $key) use ($plxShow) {
					return substr($value, 0, 4) === str_pad($plxShow->artId(), 4, "0", STR_PAD_LEFT);
				}, 
				ARRAY_FILTER_USE_BOTH)
				);
			}		
		}
		
		
		
		
		# fonctions application+ld-json
		
		/*
			* Ajout metadonnées aux articles
			* @ author G.Cyrillus
			*
			* @ return metadonnées
			
		*/
		public function articleLD($art){
			include_once(PLX_CORE.'lib/class.plx.show.php');	
			$plxShow  = plxShow::getInstance();
			$plxMotor = plxMotor::getInstance();
			
			$ld='';
			$ld.='
			<script type="application/ld+json">
			{
			"@context": "https://schema.org",
			"@type": "Article",
			"url" : "'.$plxShow->plxMotor->urlRewrite('?article' . intval($art['numero']) . '/' . $art['url']).'",
			"mainEntityOfPage": {
			"@type": "WebPage",
			"@id": "';
			$ld.= $art['url'];
			$ld.='"
			},
			"headline": "';
			$ld.= $art['title'];
			$ld.='",
			';
			if (trim($art['thumbnail'])){
				$ld.='"image": "'.$plxShow->plxMotor->urlRewrite(trim($art['thumbnail'])).'",
				';
			}
			$ld.= '"datePublished": "'.plxDate::formatDate($art['date'],'#num_year(4)-#num_month-#num_dayT#hour:#minute:00Z').'",
			"dateModified": "'.plxDate::formatDate($art['date_update'], '#num_year(4)-#num_month-#num_dayT#hour:#minute:00Z') .'",
			"author": {
			"@type": "Person",
			"name": "'. $plxMotor->aUsers[$art['author']]['name'].'"
			}';
			if (trim($art['tags'])){
				$ld.= ',
				"keywords":"'. $art['tags'].'"';
			}
			$ld.='
			}
			</script>'.PHP_EOL;
			return $ld;
		}
		
		/*
			* Ajout metadonnées des fils d'Ariane
			* @ author G.Cyrillus
			*
			* @ echo metadonnées
			
		*/
		public function breadcrumbsLD($mode,$name) {
			
			$plxShow  = plxShow::getInstance();
			$plxMotor = plxMotor::getInstance();
			$breadCrummbsld='';
			$capture ='';
			$uri = ltrim($_SERVER['REQUEST_URI'],'/');
			$subPage='';
			if (preg_match('/(\/?page[0-9]+)$/',$uri, $capture) && $plxShow->plxMotor->mode !='home') {
				$pageUri=$uri;
                $uri = str_replace($capture[1], '', $uri);
				
				$subPage=',{															
			"@type": "ListItem",
			"position": 3 ,
			"name": "'. $name .'",
			"item": "'. $plxShow->plxMotor->aConf['racine']. $pageUri .'"
			}
				
				';
			}
			$breadCrummbsld.='
			<script type="application/ld+json">
			{
			"@context": "https://schema.org",
			"@type": "BreadcrumbList",
			"itemListElement": [{
			"@type": "ListItem",
			"position": 1,
			"name": "'. $plxShow->getLang('HOME') .'",
			"item": "'. $plxShow->plxMotor->aConf['racine'] .'"
			},{															
			"@type": "ListItem",
			"position": 2 ,
			"name": "'. $name .'",
			"item": "'. $plxShow->plxMotor->aConf['racine']. $uri .'"
			}'.$subPage.'
			]
			}
			</script>
			';
			
			echo $breadCrummbsld;
			
		}
		
		/*
			* Ajout metadonnées du site
			* @ author G.Cyrillus
			*
			* @plxMySearch
			* @potAction = "SearchAction"
			*
			* @getParam('ldAS') 
			* @sameAs = "SameAs"
			*
			* echo metadonnées
			
		*/
		
		public function websiteLD() {
			$plxShow  = plxShow::getInstance();
			$plxMotor = plxMotor::getInstance();
			if(isset($plxShow->plxMotor->plxPlugins->aPlugins['plxMySearch']) && $plxShow->plxMotor->plxPlugins->aPlugins['plxMySearch']->getParam('method') == 'get' && $plxShow->plxMotor->aConf['urlrewriting'])  {
				$potAction = ','.PHP_EOL.'			"potentialAction": {
				"@type": "SearchAction",
				"target": "'. $plxShow->plxMotor->racine .$plxShow->plxMotor->plxPlugins->aPlugins['plxMySearch']->getParam('url').'?searchfield={searchfield}' .'",
				"query-input": "required name=searchfield"
				}'.PHP_EOL;		
			}
			else {$potAction='';}
			if($this->getParam('ldAS') &&  trim($this->getParam('ldAS')) !='') {
				$datas=explode(" , ", $this->getParam('ldAS'));
				$sameAs =','.PHP_EOL.'			"sameAs": [';
				$i=0;
				foreach($datas as $k => $v){
					if($i>0) $sameAs .=',';
					$sameAs .= '"'.$v.'"';
					$i++;				
				}
				$sameAs .=']';
			}
			else {$sameAs='';}
			$websiteLD='';
			$websiteLD.='
			<script type="application/ld+json">
			{
			"@context": "https://schema.org",
			"@type": "WebSite",
			"name": "'.plxUtils::strCheck($plxShow->plxMotor->aConf['title']).'",
			"description": "'.str_replace('"', "'",plxUtils::strCheck($plxShow->plxMotor->aConf['description'])).'",
			"url": "'.$plxShow->plxMotor->racine .'"'.$sameAs.$potAction.'							
			}
			</script>
			';	
			echo $websiteLD;
		}
		
		# fonctions OpenGraph
		
		/*
			* Ajout meta Opengraph du site
			* @ author G.Cyrillus
			*
			* @og:image = logo.png
			* @og:imagealt = texte alternatif
			*
			* @plxMultiLingue 
			* @og:local:alternate = autre langues configurées
			*
			* echo meta opengraph du site
			
		*/	
		public function websiteOG() {
			$plxShow  = plxShow::getInstance();
			$plxMotor = plxMotor::getInstance();
			
			if ($plxShow->plxMotor->mode != 'erreur') {	
				$meta = 'description';
				$desc='';
				$image='';
				
				if ($plxShow->plxMotor->mode == 'home') {
					$desc= plxUtils::strCheck($plxShow->plxMotor->aConf['description']);
				}
				if(file_exists($plxShow->plxMotor->urlRewrite($plxMotor->racine). 'data/medias/logo.png')) 
				{$image='    <meta property="og:image" content="'.$plxShow->plxMotor->urlRewrite($plxMotor->racine). 'data/medias/logo.png">'.PHP_EOL.
					'	<meta property="og:image:alt" content="Logo '.plxUtils::strCheck($plxShow->plxMotor->aConf['title']).'">'.PHP_EOL;
				}			
				if ($plxShow->plxMotor->mode == 'article') {
					$meta_content = trim($plxShow->plxMotor->plxRecord_arts->f('meta_' . $meta));
					if (!empty($meta_content)) { 
						$desc= plxUtils::strCheck($meta_content); 
					}
					else {
						$content = preg_replace('#<script(.*?)>(.*?)</script>#is', '',$plxShow->plxMotor->plxRecord_arts->f('chapo').$plxShow->plxMotor->plxRecord_arts->f('content'));
						$content = trim(preg_replace('/\t/', '', $content));
						$desc =str_replace(array("\r", "\n"), ' ', substr(trim(strip_tags($content)),0, 152)).'...';
					}
				}
				if ($plxShow->plxMotor->mode == 'static') {
					if (!empty($plxShow->plxMotor->aStats[$plxShow->plxMotor->cible]['meta_' . $meta]))  $desc= plxUtils::strCheck($plxShow->plxMotor->aStats[$plxShow->plxMotor->cible]['meta_' . $meta]);
				}
				if ($plxShow->plxMotor->mode == 'categorie') {
					if(version_compare(PLX_VERSION, '5.8.0', ">=")  && $plxShow->catThumbnail('#img_url',false) !=''  ) {
						$image ='	<meta property="og:image" content="'. $plxShow->catThumbnail('#img_url',false).'"/>'.PHP_EOL;
					}
					if (!empty($plxShow->plxMotor->aCats[$plxShow->plxMotor->cible]['meta_' . $meta]))  $desc= plxUtils::strCheck($plxShow->plxMotor->aCats[$plxShow->plxMotor->cible]['meta_' . $meta]);
				}			
				
				
				$og='';
				$og.='	<meta property="og:site_name" content="'.plxUtils::strCheck($plxShow->plxMotor->aConf['title']).'">'.PHP_EOL.
				'	<meta property="og:description" content="'. str_replace('"', "'",$desc).'">'.PHP_EOL.
				'	<meta property="og:type" content="website">'.PHP_EOL.
				$image;
				
				$racine= $plxShow->plxMotor->urlRewrite($plxMotor->racine);
				
				# si plugin multilingue, on met à jour le lien d'accueil
				if(defined('PLX_MYMULTILINGUE') and $plxShow->defaultLang(false) != $newMot->aConf['default_lang']) {
					$newMot= clone $plxMotor;
					$newMot->getConfiguration(PLX_ROOT.'data/configuration/parametres.xml');			
					$racine.=$plxShow->defaultLang(false).'/';					
				}	
				
				$racine=$plxShow->plxMotor->urlRewrite('?'.$_SERVER['QUERY_STRING']);
				$og.='	<meta property="og:url" content="'.$racine. '">'.PHP_EOL.
				'	<meta property="og:locale" content="'.$plxShow->defaultLang(false).'">'.PHP_EOL;
				
				# si plugin multilingue on ajoute les langues alternatives
				if(defined('PLX_MYMULTILINGUE')) {
					$alternateLangs = $plxShow->plxMotor->plxPlugins->aPlugins['plxMyMultiLingue']->aLangs;
					foreach($alternateLangs as $k => $v) {
						if($v != $plxShow->defaultLang(false)) $og .= '	<meta property="og:locale:alternate" content="'.$v.'">'.PHP_EOL;
					}
				}
				echo $og;
			}
		}
		/*
			* Ajout meta Opengraph des articles
			* @ author G.Cyrillus
			*
			* @og:type = type du document
			* @og:author = Auteur
			*
			* @og:title = titre article
			* @og:description = description de l'article . 
			* extrait de meta_description ou les 152 premiers caractéres de l'article
			*
			*
			* @og:image = image accroche de l'article
			* @og:imagealt = texte alternatif
			*
			* @og:url = url de l'article
			* @og:locale = langue de l'article
			*
			* echo meta opengraph du site
			
		*/	
		public function articleOG($art) {
			include_once(PLX_CORE.'lib/class.plx.show.php');	
			$plxShow  = plxShow::getInstance();
			$plxMotor = plxMotor::getInstance();
			$og=PHP_EOL;
			# quels metas et valeur veut-on extraire?
			/*  pas pris en compte:
				article:expiration_time - datetime - When the article is out of date after.
				article:section - string - A high-level section name. E.g. Technology
				article:tag - string array - Tag words associated with this article.
			*/
			
			
			$ogmetas= array(
			'title'						=>$art['title'],
			'description'				=>$art['meta_description'],
			'type'						=>'article',
			'article:published_time'	=> plxDate::formatDate($art['date'],'#num_year(4)-#num_month-#num_dayT#hour:#minute:00Z'),
			'article:modifed_time'		=> plxDate::formatDate($art['date_update'],'#num_year(4)-#num_month-#num_dayT#hour:#minute:00Z'),
			'article:author'			=>$plxMotor->aUsers[$art['author']]['name'],
			'image'						=>$art['thumbnail'],
			'url'						=>$plxShow->plxMotor->urlRewrite($plxShow->plxMotor->racine).$art['url'],
			'image:alt'					=>$art['thumbnail_title'],
			'locale'					=>$plxShow->defaultLang(false)
			);
			foreach($ogmetas as $meta => $v) {
				# y a t-il un  meta description disponible?
				if($meta == 'description' and $v =='' and strlen(trim(strip_tags($art['chapo'].$art['content']))) > 152) {
					# on coupe le contenu a 155  152+3 
					$v= str_replace(array("\r", "\n"), ' ',substr(trim(strip_tags($art['chapo'].$art['content'])),0, 152)).'...';
				}
				elseif($meta == 'description' and $v ==''  and strlen(trim(strip_tags($art['chapo'].$art['content']))) < 152) {
					# pas besoin de coupé
					$v = str_replace(array("\r", "\n"), ' ',trim(strip_tags($art['chapo'].$art['content'])));
				}
				if($meta == 'image' and $v !='') $v= $plxShow->plxMotor->urlRewrite($plxShow->plxMotor->racine).$v; // on en fait une URL absolue
				# enfin on n'affiche pas de meta vide
				if($v !='') $og.= '	<meta property="og:'.$meta.'" content="'.str_replace('"', "'",$v). '">'.	PHP_EOL;
			}
			
			return $og;
			
		}
		
		public function websiteTw() {
			$plxShow  = plxShow::getInstance();
			$plxMotor = plxMotor::getInstance();
			if ($plxShow->plxMotor->mode != 'erreur' and $this->getParam('twON')==1) {			
				
				# on recupere le titre (extrait de la fonction plxShow->pageTitle();
				if ($plxShow->plxMotor->mode == 'home') {
					$title = $plxShow->plxMotor->aConf['title'];
					} elseif ($plxShow->plxMotor->mode == 'categorie') {
					$title_htmltag = $plxShow->plxMotor->aCats[$plxShow->plxMotor->cible]['title_htmltag'];
					$title = $title_htmltag != '' ? $title_htmltag : $plxShow->plxMotor->aCats[$plxShow->plxMotor->cible]['name'];
					} elseif ($plxShow->plxMotor->mode == 'article') {
					$title_htmltag = $plxShow->plxMotor->plxRecord_arts->f('title_htmltag');
					$title = $title_htmltag != '' ? $title_htmltag : $plxShow->plxMotor->plxRecord_arts->f('title');
					} elseif ($plxShow->plxMotor->mode == 'static') {
					$title_htmltag = $plxShow->plxMotor->aStats[$plxShow->plxMotor->cible]['title_htmltag'];
					$title = $title_htmltag != '' ? $title_htmltag : $plxShow->plxMotor->aStats[$plxShow->plxMotor->cible]['name'];
					} elseif ($plxShow->plxMotor->mode == 'archives') {
					preg_match('/^(\d{4})(\d{2})?(\d{2})?/', $plxShow->plxMotor->cible, $capture);
					$year = !empty($capture[1]) ? ' ' . $capture[1] : '';
					$month = !empty($capture[2]) ? ' ' . plxDate::getCalendar('month', $capture[2]) : '';
					$day = !empty($capture[3]) ? ' ' . plxDate::getCalendar('day', $capture[3]) : '';
					$title = L_PAGETITLE_ARCHIVES . $day . $month . $year;
					} elseif ($plxShow->plxMotor->mode == 'tags') {
					$title = L_PAGETITLE_TAG . ' ' . $plxShow->plxMotor->cibleName;
					} elseif ($plxShow->plxMotor->mode == 'erreur') {
					$title = $plxShow->plxMotor->plxErreur->getMessage();
					} else { # mode par défaut
					$title = $plxShow->plxMotor->aConf['title'];
				}
				
				
				/* */
				
				
				# est ce une page numérotée
				$pagination='';
				$reqUri=   $plxShow->plxMotor->get;
				
				#on recupere l'url par défaut			
				$url=$plxShow->plxMotor->urlRewrite('?'.$_SERVER['QUERY_STRING']);
				
				preg_match('/(\/?page[0-9]+)$/', $reqUri, $matches);
				if( $matches) $pagination =$matches[0];
				if($plxShow->catId(true) AND intval($plxShow->catId()) =='0') $url=$plxShow->plxMotor->urlRewrite().$pagination  ;
				if($plxShow->plxMotor->mode=='categorie' 
				AND $plxShow->catId(true) 
				AND intval($plxShow->catId()) !='0'
				) $url=$plxShow->plxMotor->urlRewrite('?categorie'.
				intval($plxShow->catId()).
				'/'.$plxShow->plxMotor->aCats[str_pad($plxShow->catId(),3,0,STR_PAD_LEFT)]['url']).
				$pagination;
				if($plxShow->plxMotor->mode=='article'  AND $plxShow->plxMotor->plxRecord_arts->f('numero')) $url=$plxShow->plxMotor->urlRewrite('?article' . intval($plxShow->plxMotor->plxRecord_arts->f('numero')) . '/' . $plxShow->plxMotor->plxRecord_arts->f('url'));
				if( $plxShow->plxMotor->mode=='static'  ) { 
					$url=$plxShow->plxMotor->urlRewrite('?static'. intval($plxShow->staticId()).'/'.$plxShow->plxMotor->aStats[str_pad($plxShow->staticId(),3,0,STR_PAD_LEFT)]['url']);
				}
				else{
					# enfin on regarde si il s'agit d'un plugin qui squatte les pages statiques			
					foreach($plxShow->plxMotor->plxPlugins->aPlugins as $plug){				
						if($plug->getParam('url') == $plxShow->plxMotor->mode)  $url=$plxShow->plxMotor->urlRewrite('?'.$_SERVER['QUERY_STRING']);
					}
				}
				$meta = 'description';
				$desc='';
				$img='';
				
				if ($plxShow->plxMotor->mode == 'home') {
					$desc= plxUtils::strCheck($plxShow->plxMotor->aConf['description']);
				}
				
				if ($plxShow->plxMotor->mode == 'article') {
					$meta_content = trim($plxShow->plxMotor->plxRecord_arts->f('meta_' . $meta));
					if($plxShow->plxMotor->plxRecord_arts->f('thumbnail') !='') $img = $plxShow->plxMotor->urlRewrite().trim($plxShow->plxMotor->plxRecord_arts->f('thumbnail'));
					if (!empty($meta_content)){  
						$desc= plxUtils::strCheck($meta_content); 
					}
					else{
						$content = preg_replace('#<script(.*?)>(.*?)</script>#is', '',$plxShow->plxMotor->plxRecord_arts->f('chapo').$plxShow->plxMotor->plxRecord_arts->f('content'));
						$content = trim(preg_replace('/\t/', '', $content));
						$desc =str_replace(array("\r", "\n"), ' ', substr(trim(strip_tags($content)),0, 192)).'...';
					}
				}
				if ($plxShow->plxMotor->mode == 'static') {
					if (!empty($plxShow->plxMotor->aStats[$plxShow->plxMotor->cible]['meta_' . $meta]))  $desc= plxUtils::strCheck($plxShow->plxMotor->aStats[$plxShow->plxMotor->cible]['meta_' . $meta]);
				}
				if ($plxShow->plxMotor->mode == 'categorie') {
					if (!empty($plxShow->plxMotor->aCats[$plxShow->plxMotor->cible]['meta_' . $meta]))  $desc= plxUtils::strCheck($plxShow->plxMotor->aCats[$plxShow->plxMotor->cible]['meta_' . $meta]);
				}
				
				if($this->getParam('twID')) $site=$this->getParam('twID');
				else $site='';
				$tweetArray =array(
				'card' 			=>'summary',
				'title'			=> $title ,
				'url'			=> $url,
				'description'	=> $desc,
				'site'			=> $site,
				'image'			=> $img
				);		
				
				$twC='';
				foreach($tweetArray as $metatw => $v) {
					# on n'affiche pas de meta vide
					if($v !='') $twC.= '	<meta name="twitter:'.$metatw.'" content="'.str_replace('"', "'",$v). '">'.	PHP_EOL;
				}
				echo $twC;	
			}
		}
		
		
		
		# exclusion dans le sitemap  d'une ou plusieurs categorie et page statique
		public function SitemapBegin() {
			$removeCat  = trim($this->getParam('removeCat' ));
			$removeStat = trim($this->getParam('removeStat'));
			echo self::BEGIN_CODE;
		?>
		if(!empty('<?= $removeCat ?>')) {
		$cat = array_keys($plxMotor->aCats);
		$remove = explode('|', '<?= $removeCat ?>');
		foreach ($cat as $key) {
		$plxMotor->aCats[$key]["active"] = in_array($key, $remove) ? 0 : 1;
		}
		$activeCats = explode('|', $plxMotor->activeCats);
		$activeCats = array_diff($activeCats, $remove);
		$plxMotor->activeCats = implode('|', $activeCats);
		}
		
		if(!empty('<?= $removeStat ?>')) {
		$stat = array_keys($plxMotor->aStats);
		$excludeStat = explode('|', '<?= $removeStat ?>');
		foreach ($stat as $k) {
		if(in_array($k, $excludeStat)) unset($plxMotor->aStats[$k]);
		}
		}
		
			if(version_compare(PLX_VERSION, '5.9.0', ">="))  include(PLX_PLUGINS.'extra_SEO/inc.sitemapBegin.php');
		
		<?php
			echo self::END_CODE;
			return;
		}
		
		public function missingPNG($text,$size) {  
			/* faut-il generer des images aux réseaux sociaux et a quelle taille ? */
		}
		public function plxMotorDemarrageEnd() {
			/* y a un truc qu'on a oublié ? */
		}
		
	}	
