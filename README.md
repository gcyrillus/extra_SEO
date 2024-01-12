# extra_SEO
Plugin d'outils d'aide au referencement pour le CMS <a href="https://pluxml.org" title="CMS Flat-File à l'XML">PluXml</a>.

<h1>Aide</h1>
<h2>Installation</h2>
<p>extraire l'archive dans le repertoire <code>plugins</code> de votre PluXml et renommer ce repertoir en <code>extra_SEO</code>.</p>
<h2>Mise à jour</h2>
<p>Copier les fichiers de la nouvelle version dans le repertoire du plugin pour les remplacer.</p>
<p>Réengistrer la configuration pour finaliser votre mise à jour</p>
<hr>

<h1>Présentation</h1>

<h2>Pr&eacute;ambule</h2>
<p>Ce plugin tente de regrouper plusieurs approches d'insertions de metadonnées et autres configurations utilent au réferencement en général, chaque option est configurable independament.</p>
<p>Plusieurs de ces options sont aussi déjà possibles depuis differents plugins ou script à intégrer aux thèmes. Certaines portions de ce plugin peuvent reprendre en partie et amelioré le code d'autres plugins ou scripts.</p>
<p>La liste des options s'allongera et se modifiera au fil des MAJ (aprés identifications de bugs, incoherences, fonctionnalités manquantes ou à modifiées, etc.)</p>

<h3>Lesquelles</h3>
<p></p>
<ol>
<li>URL canonique (desactivation pour les version 5.9.X en dev)</li>
<li>En complément au Sitemap du CMS PluXml (option d'exclusion de certaines catégories ou page statiques)</li>
<li>Données structurées pour des résultats enrichis</li>
<li>Les balises META OpenGraph</li>
<li>Les balises META 'Twitter Card'</li>
<li>l'edition des fichiers robots.txt et humans.txt et .htaccess</li>
<li>Déclarer le plugin plxMySearch comme un moteur de recherche pour le navigateur</li>
<li>En mode article, retire de la sidebar tous les liens menant vers la page elle-même</li>
<li>Afficher les liens des articles "précédent" et "suivant" de même catégorie</li>
</ol>

<h2>Fonctionnement et caract&eacute;ristiques</h2>
<p>Aprés installation et activation du plugin, plusieurs onglets vous permettrons d'activer ou de modifier differentes options.</p>
<p>Selon les options selectionnées, le plugin injectera dans le code de vos pages les metadonnées correspondantes.</p>
<p>Les fichiers robots.txt et humans.txt seront créés et pourront être édités</p>

<h3>Caract&eacute;ristiques</h3>
<p>Le plugin genere uniquement les données selectionnées et existantes.</p>
<p>Si les plugin plxMySearch , plxMyBetterUrls ou plxMultilingue sont disponibles et activés, ils seront pris en compte.</p>


<h3>Fonctionnement</h3>
<p></p>
<dl>
<dt>Les URL canoniques</dt>
<dd>L'URL selectionnée est celle produite par la fonction urlRewrite() du CMS. Dans le cas ou vous activez l'URL rewriting , c'est cette URL réecrite qui est produite comme URL canonique quelques soit l'URL utilisé pour acceder à votre page . Ceci pour eviter le "duplicate content".</dd>
<dt>Le sitemap</dt>
<dd> Le plugin permet de retirer du site map categorie et page statique à partir de leur identifiant (numero xxx).</dd>
<dt>Données structurées</dt>
<dd>Celle ci servent à enrichir l'affichage de vos pages proposées dans les moteurs de recherche sans améliorer leur position</dd>
<dt>OpenGraph et Twitter Card</dt>
<dd>Ces données servent principalement aux réseau sociaux, créer à l'origine pour facebook, elle permettent de partager efficacement l'une de vos pages dans les réseaux sociaux en indiquant leur titre , description et image </dd>
<dt>les fichiers robots.txt et humans.txt</dt>
<dd>Le fichier robots.txt s'adresse aux moteurs de recherche et leur indique ce qu'il peuvent indexer ou ne pas indexer.<br>Le fichier humans.txt est un fichier permettant de présenter les personnes ayant participé à la création de votre site</dd>
<dt>OpenSearch</dt>
<dd>Si le plugin plxMySearch est activé et configuré en methode <b>get</b> , un fichier xml est généré à la racine de votre site, celui-ci  permet au visiteur d'ajouter votre page de recherche aux moteurs de recherche de son Navigateur <em></small>(les recherches n'auront lieu que sur votre site)</small></em>. Cela peut-être utile pour un site sur un sujet précis ou technique bien fourni.</dd>
</dl>

<h2>Description</h2>

<h3>Cot&eacute; visiteurs</h3>
<p>Si l'option opensearch est active, alors le visiteur peut ajouter votre page de recherche à la liste des moteurs de recherches de son navigateur</p>
<p>Si le visiteur est un robot indexeur , comme google ou facebook, alors ils trouvera les données utiles à l'indexation de votre site.</p>
<p>Vous pouvez exclure les liens dans la sidebar pointant sur la page en cours</p>
<p>Vous pouvez afficher les liens: article précédent et suivant, au pied de l'article.</p>

<h3>cot&eacute; administration</h3>

<h4>La page configuration</h4>
<p>Page accessible &agrave; partir de la liste des plugins</p>
<p><b><u>Vous pouvez configurez:</u></b></p>
<ol>
<li>L'insertion de la META "canonical"</li>
<li>Exclure une ou plusieurs catégories ou pages statiques du sitemap</li>
<li>Servir les données structurées</li>
<li>Servir les META OpenGraph</li>
<li>Servir les META 'Twitter Card'</li>
<li>Editer les fichiers robots.txt et humans.txt</li>
<li>Proposer votre propre moteur de recherche aux navigateurs.</li>
<li>En mode article, retire de la sidebar tous les liens menant vers la page elle-même</li>
<li>Afficher les liens des articles "précédent" et "suivant" de même catégorie</li>
<li>...</li>
</ol>	
<h4>La page Administration</h4>
<p>Cette page n'existe pas (encore?)</p>
<h2>Aide</h2>
<p>Il n'y a pour le moment aucune difficultés recencés à l'utilisation de ce plugin. Si vous avez difficultés d'utilisation, le forum de <a href="https://forum.pluxml.org" target="_blank" title="Forum du CMS PluXml">PluXml</a> est tout à fait adapté.</p>

<p>Pour les bugs, c'est ici même :<a href="https://github.com/gcyrillus/extra_SEO/issues">issue</a>.</p>
