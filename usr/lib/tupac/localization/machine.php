<?php
/*
 * Created on 23/05/2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
$messageset=array(
	'packageInfoListInstalled'			=>'%c||%r||%p||%v||%i||%d||%s'."\n"
	,'packageInfoListOtherInstalled'	=>'%c||%r||%p||%v||%i||%d||%s'."\n"
	,'packageInfoListNotInstalled'		=>'%c||%r||%p||%v||%i||%d||%s'."\n"
	,'aurPackageInfoListInstalled'		=>'%c||%r||%p||%v||%i||%d||%s||%x'."\n"
	,'aurPackageInfoListOtherInstalled'	=>'%c||%r||%p||%v||%i||%d||%s||%x'."\n"
	,'aurPackageInfoListNotInstalled'	=>'%c||%r||%p||%v||%i||%d||%s||%x'."\n"
	,'invalidDirectory'			=>'ERROR: %dir is not a valid directory'."\n"
	,'fileMissing'				=>"\r".'%file is missing in %p'."\n"
	,'recomendRootCheckDir'			=>'WARNING: root is recommended. Nonacccessible files marked as missing'."\n"
	,'recomendRootOrphans'			=>'WARNING: root is recommended. Nonacccessible files not processed'."\n"
	,'wrongColorSet'			=>'ERROR: wrong colorset. Available colorsets are: darkbg, lightbg, nocolor.'."\n"
	,'integrityError'			=>'FATAL: Integrity error! The package is corrupted! Its directory name \'%p\' doesn\'t match its description name: %desc_name'."\n"
	,'choosePackages'			=>'==> Enter the package numbers you want to install. Separate choices with a space. Example: 1 2 5 14'."\n"
	,'callingPacman'			=>'Calling pacman...'."\n"
	,'callingYaourt'			=>'Calling yaourt...'."\n"
	,'nothingToInstall'			=>'==> Nothing to install'."\n"
	,'creatingFileList'			=>'==> Creating file list'."\n"
	,'reusingFileList'			=>'==> Reusing existing file list'."\n"
	,'corruptedFileList'			=>'==> Existing file list is corrupted. Creating a new one'."\n"
	,'ownedFile'				=>'%file is owned by %p %v'."\n"
	,'ownedFileMissing'			=>'%file was owned by %p %v, but it doesn\'t exist anymore! Verify your system integrity!'."\n"
	,'unownedFile'				=>'%file has no owner'."\n"
	,'unownedFileMissing'			=>'%file has no owner but, anyway, it doesn\'t exist'."\n"
	,'generatingCache'			=>'==> Generating TUPAC_CACHE'."\n"
	,'generatingCacheCorrupted'		=>'==> Regenerating TUPAC_CACHE (database is corrupted)'."\n"
	,'generatingCacheTupacUpdated'		=>'==> Regenerating TUPAC_CACHE (TUPAC_CACHE version got updated)'."\n"
	,'generatingCacheDatabaseUpdated'	=>'==> Regenerating TUPAC_CACHE (database got updated)'."\n"
	,'repoIsGone'				=>'::-   repo "%r" doesn\'t exist anymore'."\n"
	,'repoIsUpdated'			=>'::*   repo "%r" has been updated'."\n"
	,'repoIsNew'				=>'::+   new repo "%r"'."\n"
	,'removePackage'			=>':::--- remove %p'."\n"
	,'updatePackage'			=>':::*** update %p'."\n"
	,'addPackage'				=>':::+++ add %p'."\n"
	,'recheckingLocal'			=>':::... rechecking installed packages'."\n"
	,'saving'				=>'::: saving'."\n"
	,'reusingCache'				=>'==> Reusing existing TUPAC_CACHE'."\n"
	,'cacheNeedRoot'			=>'ERROR: To be able to create TUPAC_CACHE directory you need to be root. Once it is created you can operate as normal user'."\n"
	,'wrongProxy'				=>'ERROR: Proxy format must be [host]:[port]. To erase it use "none"'."\n"
	,'usage'				=>'tupac: A cached pacman implementatioin. Version: '.$GLOBALS['tupac_version']
							."\n".''
							."\n".' Usage:'
							."\n".'  tupac [word] [word] [word] ...     : Search for and install packages that match all [word]'
							."\n".'  tupac -Ss [word] [word] [word] ... : Search for packages that match all [word]'
							."\n".'  tupac -Qo [file] [file] [file] ... : Search for each [file] owner'
							."\n".'  tupac --checkdir [directory]       : Check integrity of a directory.'
							."\n".'  tupac --orphans [directory]        : Find files that are not part of any package'
							."\n".'  tupac                              : Just update cache'
							."\n".'  tupac [anything else]              : bypass to yaourt'
							."\n".'  tupac --set-proxy [host:port|none] : set up a proxy'
							."\n".'  tupac --set-settings [key] [value] : set custom settings for tupac. If no value is given'
							."\n".'                                       settings get deleted.'
							."\n".'  tupac --aur-updates                : Check version of AUR packages. CAREFUL: It marks'
							."\n".'                                       upgrades AS WELL AS DOWNGRADES'
							."\n".''
							."\n".' Modifiers:'
							."\n".'  --safe                             : Only search for safe packages'
							."\n".'  --noaur                            : Don\'t search in AUR'
							."\n".'  --noprompt                         : Don\'t prompt anything'
							."\n".'  --color [darkbg|lightbg|nocolor]   : Choose color scheme'
							."\n".'  --repos repo1,repo2,repo3,...      : Set active repositories'
							."\n".'  --lang [xx_XX|machine]             : Set working language'
							."\n".''
							."\n".' Available settings:'
							."\n".'  PACMAN_BINARY                      : Alternate pacman command'
							."\n".'  PROXY_NAME                         : Proxy server'
							."\n".'  PROXY_PORT                         : Proxy port'
							."\n".'  VERBOSITY                          : 0 (default)'
							."\n".'                                       1 - Show detailed list of database changes on'
							."\n".'                                           cache update'
							."\n"
);
