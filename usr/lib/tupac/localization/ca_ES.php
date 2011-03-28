<?php
/*
 * Created on 23/05/2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
$messageset=array(
	'packageInfoListInstalled'			=>'%c %z%r/'.INSTALLEDLIGHT.'%p'.VERSIONLIGHT.' %v'.SAFELIGHT.'%s '.INSTALLMATCHLIGHT.'- Aquesta és la versió instal·lada'.NORMAL."\n".'    %d'."\n"
	,'packageInfoListOtherInstalled'	=>'%c %z%r/'.INSTALLEDLIGHT.'%p'.VERSIONLIGHT.' %v'.SAFELIGHT.'%s '.NORMAL.INSTALLNOTMATCHLIGHT.'- Una altra versió instal·lada: %i'.NORMAL."\n".'    %d'."\n"
	,'packageInfoListNotInstalled'		=>'%c %z%r/'.HIGHLIGHT.'%p'.VERSIONLIGHT.' %v'.SAFELIGHT.'%s'.NORMAL."\n".'    %d'."\n"
	,'aurPackageInfoListInstalled'		=>'%c %z%r/'.INSTALLEDLIGHT.'%p'.VERSIONLIGHT.' %v'.SAFELIGHT.' (%x vots)%s '.INSTALLMATCHLIGHT.'- Aquesta és la versió instal·lada'.NORMAL."\n".'    %d'."\n"
	,'aurPackageInfoListOtherInstalled'	=>'%c %z%r/'.INSTALLEDLIGHT.'%p'.VERSIONLIGHT.' %v'.SAFELIGHT.' (%x vots)%s '.NORMAL.INSTALLNOTMATCHLIGHT.'- Una altra versió instal·lada: %i'.NORMAL."\n".'    %d'."\n"
	,'aurPackageInfoListNotInstalled'	=>'%c %z%r/'.HIGHLIGHT.'%p'.VERSIONLIGHT.' %v'.SAFELIGHT.' (%x vots)%s'.NORMAL."\n".'    %d'."\n"
	,'invalidDirectory'			=>'ERROR: %dir no és un directori vàlid'."\n"
	,'fileMissing'				=>"\r".HIGHLIGHT.'%file'.INEXISTENTLIGHT.' falta a '.HIGHLIGHT.'%p'.NORMAL."\n"
	,'recomendRootCheckDir'			=>HIGHLIGHT.'==> Es recomana executar --checkdir com a root perqué els ficheros inaccesibles són marcats com a desapareguts.'.NORMAL."\n"
	,'recomendRootOrphans'			=>HIGHLIGHT.'==> Es recomana executar --orphans com a root perqué els ficheros inaccesibles no són processats.'.NORMAL."\n"
	,'wrongColorSet'			=>'ERROR: joc de colors incorrecte. Els jocs de colors disponibles són: darkbg, lightbg, nocolor.'."\n"
	,'integrityError'			=>HIGHLIGHT.'FATAL: Error d\'integritat! El paquet està corrupte! El nom del directori \'%p\' no coincideix amb el nom de la descripció: %desc_name'.NORMAL."\n"
	,'choosePackages'			=>HIGHLIGHT.'==> Introdueixi el número dels paquets a instal·lar. Separi\'ls amb espais. Exemple: 1 2 5 14'.NORMAL."\n"
	,'callingPacman'			=>'Cridant el pacman...'."\n"
	,'callingYaourt'			=>'Cridant el yaourt...'."\n"
	,'nothingToInstall'			=>HIGHLIGHT.'==> Res a instal·lar'.NORMAL."\n"
	,'creatingFileList'			=>'==> Creant la llista d\'arxius'."\n"
	,'reusingFileList'			=>'==> Reutilitzant la llista d\'arxius'."\n"
	,'corruptedFileList'			=>'==> La llista d\'arxius existent està corrupta. Creant-ne una de nova...'."\n"
	,'ownedFile'				=>'%file'.OWNERLIGHT.' es propietat de %p'.NORMAL.' '.VERSIONLIGHT.'%v'.NORMAL."\n"
	,'ownedFileMissing'			=>INEXISTENTLIGHT.'%file era propietat de %p'.NORMAL.' '.VERSIONLIGHT.'%v'.INEXISTENTLIGHT.', però ja no existeix! Comprova la integritat del teu sistema!'.NORMAL."\n"
	,'unownedFile'				=>'%file'.UNOWNEDLIGHT.' no té propietari'.NORMAL."\n"
	,'unownedFileMissing'			=>'%file'.INEXISTENTLIGHT.' no té propietari, però, de todas formes, no existeix'.NORMAL."\n"
	,'generatingCache'			=>'==> Generant la TUPAC_CACHE'."\n"
	,'generatingCacheCorrupted'		=>'==> Regenerant la TUPAC_CACHE (base de dades corrupta)'."\n"
	,'generatingCacheTupacUpdated'		=>'==> Regenerant la TUPAC_CACHE (la versió de la TUPAC_CACHE ha sigut actualitzada)'."\n"
	,'generatingCacheDatabaseUpdated'	=>'==> Regenerant la TUPAC_CACHE (la base de dades ha sigut actualitzada)'."\n"
	,'repoIsGone'				=>'::-   la repo "%r" ja no existeix'."\n"
	,'repoIsUpdated'			=>'::*   la repo "%r" ha sigut actualitzada'."\n"
	,'repoIsNew'				=>'::+   nova repo "%r"'."\n"
	,'removePackage'			=>':::--- treure %p'."\n"
	,'updatePackage'			=>':::*** actualitza %p'."\n"
	,'addPackage'				=>':::+++ afegeix %p'."\n"
	,'recheckingLocal'			=>':::... Tornant a comprovar els paquets instal·lats'."\n"
	,'saving'				=>'::: desant'."\n"
	,'reusingCache'				=>'==> Reutilitzant la TUPAC_CACHE existent'."\n"
	,'cacheNeedRoot'			=>ERRORLIGHT.'ERROR: Par a poder crear el directorio de la TUPAC_CACHE has de ser root. Una cop creat, pots treballar com a usuari normal'.NORMAL."\n"
	,'wrongProxy'				=>'ERROR: El format del proxy ha de ser [host]:[port]. Per treure\'l utilitzi "none"'."\n"
	,'usage'				=>'tupac: Una implementació de pacman amb cache. Versió: '.$GLOBALS['tupac_version']
							."\n".''
							."\n".' Ús:'
							."\n".'  tupac [paraula] [paraula] ...      : Buscar i instal·lar paquets que contenguin totes les [paraula]'
							."\n".'  tupac -Ss [paraula] [paraula] ...  : Buscar paquets que contenguin totes les [paraula]'
							."\n".'  tupac -Qo [arxiu] [arxiu] ...      : trobar el propietari de cada [arxiu]'
							."\n".'  tupac --checkdir [directori]       : comprovar la integritat de [directori]'
							."\n".'  tupac --orphans [directori]        : trobar els arxius que no pertanyen a cap paquet'
							."\n".'  tupac                              : només crear la caché'
							."\n".'  tupac [anything else]              : se li passa al yaourt'
							."\n".'  tupac --set-proxy [host:port|none] : configurar un proxy'
							."\n".'  tupac --set-settings [key] [value] : establir parametres personalitzats del tupac. Si no hi ha'
							."\n".'                                       cap [value] el parámetre será eliminat.'
							."\n".'  tupac --aur-updates                : Comprova la versió dels paquets d\'AUR. COMPTE: Resalta tant'
							."\n".'                                       les actualitzacions com les VERSIONS INFERIORS'
							."\n".''
							."\n".' Modificadors:'
							."\n".'  --safe                             : Només buscar paquets segurs'
							."\n".'  --noaur                            : Només buscar a l\'AUR'
							."\n".'  --noprompt                         : No preguntar res'
							."\n".'  --color [darkbg|lightbg|nocolor]   : Escollir el tema de color'
							."\n".'  --repos repo1,repo2,repo3,...      : Especificar les repos actives'
							."\n".'  --lang [xx_XX|machine]             : Seleccionar l\'idioma'
							."\n".''
							."\n".' Available settings:'
							."\n".'  PACMAN_BINARY                      : Comanda alternativa a pacman'
							."\n".'  PROXY_NAME                         : Servidor proxy'
							."\n".'  PROXY_PORT                         : Port del servidor proxy'
							."\n".'  VERBOSITY                          : 0 (predeterminat)'
							."\n".'                                       1 - mostra una llista detallada de canvias al '
							."\n".'                                           actualitzar la cache'
							."\n"
);
