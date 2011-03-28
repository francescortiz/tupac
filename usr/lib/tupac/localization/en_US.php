<?php
/*
 * Created on 23/05/2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
$messageset=array(
	'packageInfoListInstalled'			=>'%c %z%r/'.INSTALLEDLIGHT.'%p'.VERSIONLIGHT.' %v'.SAFELIGHT.'%s '.INSTALLMATCHLIGHT.'- This is the installed version'.NORMAL."\n".'    %d'."\n"
	,'packageInfoListOtherInstalled'	=>'%c %z%r/'.INSTALLEDLIGHT.'%p'.VERSIONLIGHT.' %v'.SAFELIGHT.'%s '.NORMAL.INSTALLNOTMATCHLIGHT.'- Another version installed: %i'.NORMAL."\n".'    %d'."\n"
	,'packageInfoListNotInstalled'		=>'%c %z%r/'.HIGHLIGHT.'%p'.VERSIONLIGHT.' %v'.SAFELIGHT.'%s'.NORMAL."\n".'    %d'."\n"
	,'aurPackageInfoListInstalled'		=>'%c %z%r/'.INSTALLEDLIGHT.'%p'.VERSIONLIGHT.' %v'.SAFELIGHT.' (%x votes)%s '.INSTALLMATCHLIGHT.'- This is the installed version'.NORMAL."\n".'    %d'."\n"
	,'aurPackageInfoListOtherInstalled'	=>'%c %z%r/'.INSTALLEDLIGHT.'%p'.VERSIONLIGHT.' %v'.SAFELIGHT.' (%x votes)%s '.NORMAL.INSTALLNOTMATCHLIGHT.'- Another version installed: %i'.NORMAL."\n".'    %d'."\n"
	,'aurPackageInfoListNotInstalled'	=>'%c %z%r/'.HIGHLIGHT.'%p'.VERSIONLIGHT.' %v'.SAFELIGHT.' (%x votes)%s'.NORMAL."\n".'    %d'."\n"
	,'invalidDirectory'			=>'ERROR: %dir is not a valid directory'."\n"
	,'fileMissing'				=>"\r".HIGHLIGHT.'%file'.INEXISTENTLIGHT.' is missing in '.HIGHLIGHT.'%p'.NORMAL."\n"
	,'recomendRootCheckDir'			=>HIGHLIGHT.'==> It is recomended that you perform --checkdir as root because non accessible files are marked as missing.'.NORMAL."\n"
	,'recomendRootOrphans'			=>HIGHLIGHT.'==> It is recomended that you perform --orphans as root because non accessible files are not processed.'.NORMAL."\n"
	,'wrongColorSet'			=>'ERROR: wrong colorset. Available colorsets are: darkbg, lightbg, nocolor.'."\n"
	,'integrityError'			=>HIGHLIGHT.'FATAL: Integrity error! The package is corrupted! Its directory name \'%p\' doesn\'t match its description name: %desc_name'.NORMAL."\n"
	,'choosePackages'			=>HIGHLIGHT.'==> Enter the package numbers you want to install. Separate choices with a space. Example: 1 2 5 14'.NORMAL."\n"
	,'callingPacman'			=>'Calling pacman...'."\n"
	,'callingYaourt'			=>'Calling yaourt...'."\n"
	,'nothingToInstall'			=>HIGHLIGHT.'==> Nothing to install'.NORMAL."\n"
	,'creatingFileList'			=>'==> Creating file list'."\n"
	,'reusingFileList'			=>'==> Reusing existing file list'."\n"
	,'corruptedFileList'			=>'==> Existing file list is corrupted. Creating a new one...'."\n"
	,'ownedFile'				=>'%file'.OWNERLIGHT.' is owned by %p'.NORMAL.' '.VERSIONLIGHT.'%v'.NORMAL."\n"
	,'ownedFileMissing'			=>INEXISTENTLIGHT.'%file was owned by %p'.NORMAL.' '.VERSIONLIGHT.'%v'.INEXISTENTLIGHT.', but it doesn\'t exist anymore! Verify your system integrity!'.NORMAL."\n"
	,'unownedFile'				=>'%file'.UNOWNEDLIGHT.' has no owner'.NORMAL."\n"
	,'unownedFileMissing'			=>'%file'.INEXISTENTLIGHT.' has no owner but, anyway, it doesn\'t exist'.NORMAL."\n"
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
	,'cacheNeedRoot'			=>ERRORLIGHT.'ERROR: To be able to create TUPAC_CACHE directory you need to be root. Once it is created you can operate as normal user'.NORMAL."\n"
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
							."\n".'  tupac --aur-updates                : Check version of AUR packages. CAREFUL: It highlights'
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
