<?php
/*
 * Created on 23/05/2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

dl("json.so");

include $include_path.'PacmanData.class.php'; 
include $include_path.'PacmanFileList.class.php'; 

$GLOBALS['tupac_config_file']=getPipeContents('echo -n $HOME').'/.tupacrc';
include $include_path.'TupacSettings.class.php';

define('VERBOSITY_1', 1);
define('VERBOSITY_0', 0);
$GLOBALS['VERBOSITY'] = getSettings('VERBOSITY', '0');


#error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

$a=array(
	0,
	1,
	2
);


function getRepoDir($repo) {
	return $GLOBALS['pacman_directory'].($repo=='local'?'':'sync/').$repo.'/';
}

function getRepoDirList() {
	$directories=array();
	
	$directories[]='local';
	
	$dh=opendir($GLOBALS['pacman_directory'].'sync/');
	while (false !== ($dir=readdir($dh)) ) {
		$directories[]=$dir;
	}
	closedir($dh);
	
	return $directories;
}

define ('BLACK',	"\033[0;30m");
define ('RED',		"\033[0;31m");
define ('GREEN',	"\033[0;32m");
define ('ORANGE',	"\033[0;33m");
define ('BLUE',		"\033[0;34m");
define ('PURPLE',	"\033[0;35m");
define ('CYAN',		"\033[0;36m");
define ('LIGHT_GRAY',	"\033[0;37m");

define ('DARK_GRAY',	"\033[1;30m");
define ('LIGHT_RED',	"\033[1;31m");
define ('LIGHT_GREEN',	"\033[1;32m");
define ('YELLOW',	"\033[1;33m");
define ('LIGHT_BLUE',	"\033[1;34m");
define ('PINK',		"\033[1;35m");
define ('LIGHT_CYAN',	"\033[1;36m");
define ('WHITE',	"\033[1;37m");

define ('YELLOW_BLUE_BACKGROUND', "\033[44;1;33m");

define ('NORMAL',	"\033[m");

function mapColors($colorSet) {
	switch($colorSet) {
		case "lightbg":
			define('HIGHLIGHT', BLACK);
			define('VERSIONLIGHT', GREEN);
			define('SAFELIGHT', BLUE);
			define('INSTALLEDLIGHT', YELLOW_BLUE_BACKGROUND);
			define('INSTALLMATCHLIGHT', BLUE);
			define('INSTALLNOTMATCHLIGHT', RED);
			define('OWNERLIGHT', RED);
			define('INEXISTENTLIGHT', LIGHT_RED);
			define('DIRECTORYLIGHT', LIGHT_RED);
			define('UNOWNEDLIGHT', PINK);
			define('ERRORLIGHT', RED);
			$GLOBALS['repoColors']=array(
				GREEN,
				PURPLE,
				ORANGE,
				CYAN
			);
			break;
		case "darkbg":
			define('HIGHLIGHT', WHITE);
			define('VERSIONLIGHT', GREEN);
			define('SAFELIGHT', LIGHT_GREEN);
			define('INSTALLEDLIGHT', YELLOW_BLUE_BACKGROUND);
			define('INSTALLMATCHLIGHT', YELLOW);
			define('INSTALLNOTMATCHLIGHT', ORANGE);
			define('OWNERLIGHT', ORANGE);
			define('INEXISTENTLIGHT', LIGHT_RED);
			define('DIRECTORYLIGHT', LIGHT_RED);
			define('UNOWNEDLIGHT', PINK);
			define('ERRORLIGHT', RED);
			$GLOBALS['repoColors']=array(
				GREEN,
				PURPLE,
				ORANGE,
				CYAN
			);
			break;
		case "nocolor":
			define('HIGHLIGHT', '');
			define('VERSIONLIGHT', '');
			define('SAFELIGHT', '');
			define('INSTALLEDLIGHT', '');
			define('INSTALLMATCHLIGHT', '');
			define('INSTALLNOTMATCHLIGHT', '');
			define('OWNERLIGHT', '');
			define('INEXISTENTLIGHT', '');
			define('DIRECTORYLIGHT', '');
			define('ERRORLIGHT', '');
			define('UNOWNEDLIGHT', '');
			define('NORMAL', '');
			$GLOBALS['repoColors']=array(
				''
			);
			break;
		default:
			$GLOBALS['die']='wrongColorSet';
			break;
	}
}

class Std {
	static function send($what) {
		fwrite(STDOUT, $what."\n");
	}

	static function readBytes($count) {
		return fread(STDIN, $count);
	}

	static function rm($file) {
		if (file_exists($file))
			unlink($file);
	}
	static function write($file, $content) {
		file_put_contents($file, $content);
	}
}

function sendMessage($msgId, $msgData, $verbosity=VERBOSITY_0) {
	global $messageset, $messages;
	
	if ($GLOBALS['VERBOSITY']<$verbosity)
		return;
	
	$message=$messageset[$msgId];
	if (!$message)
		$message=$messages['en_US'][$msgId];
	
	foreach ($msgData as $key=>$value)
		$message=str_replace($key, $value, $message);
	
	echo $message;
	
}

$uid=getPipeContents('echo -n $UID');
$GLOBALS['uid']=$uid;

function walkDir($dir) {
	$tmpDir=$dir;
	chdir($dir);
	$dir=getcwd();
	chdir($tmpDir);
	$dh=opendir($dir);
	while (FALSE !== $something=readdir($dh))
		if ($something!='.' && $something!='..') {
			$something_absolute=$dir.'/'.$something;
			if (is_dir($something_absolute) && !is_link($something_absolute))
				walkDir($something_absolute);
			else
				$GLOBALS['WALK_DIR_RESULTS'][$something_absolute]=TRUE;
		}
	closedir($dh);
}

function httpSocketConnection($host, $method, $path, $data) {
	$method = strtoupper($method);
	
	$port=80;
	
	if (getSettings('PROXY_NAME') && getSettings('PROXY_PORT')) {
		$path='http://'.$host.$path;
		$host=getSettings('PROXY_NAME');
		$port=getSettings('PROXY_PORT');
	}
	
	if ($method == "GET") {
		$path.= '?'.$data;
	}
	
	$filePointer = fsockopen($host, $port, $errorNumber, $errorString);
	
	if (!$filePointer) {
		return false;
	}

	$requestHeader = $method." ".$path." HTTP/1.1\r\n";
	$requestHeader.= "Host: ".$host."\r\n";
	$requestHeader.= "Content-type: text/plain\r\n";

	if ($method == "POST") {
		$requestHeader.= "Content-Type: application/x-www-form-urlencoded\r\n";
		$requestHeader.= "Content-Length: ".strlen($data)."\r\n";
	}
	
	$requestHeader.= "Connection: close\r\n\r\n";
	
	if ($method == "POST") {
		$requestHeader.= $data;
	}
	
	fwrite($filePointer, $requestHeader);
	
	$responseHeader = '';
	$responseContent = '';

	do {
		$responseHeader.= fread($filePointer, 1);
	} while (!preg_match('/\\r\\n\\r\\n$/', $responseHeader));
	
	
	if (!strstr($responseHeader, "Transfer-Encoding: chunked")) {
		while (!feof($filePointer)) {
			$responseContent.= fgets($filePointer, 128);
		}
	} else {

		while ($chunk_length = hexdec(fgets($filePointer))) {
			$responseContentChunk = '';
		
			$read_length = 0;
			
			while ($read_length < $chunk_length) {
				$responseContentChunk .= fread($filePointer, $chunk_length - $read_length);
				$read_length = strlen($responseContentChunk);
			}

			$responseContent.= $responseContentChunk;
			
			fgets($filePointer);
		}
	}

	return chop($responseContent);
}

function debugArray($array) {
	foreach($array as $key=>$value) {
		echo $key.': '.$value."\n";
	}
}

function getFileContents($file) {
	$fh=fopen($file,'r');
	$out='';
	while($tmpData=fread($fh,1024)) {
		$out.=$tmpData;
	}
	fclose($fh);

	return $out;
}
function getPipeContents($file) {
	$fh=popen($file,'r');
	$out='';
	while($tmpData=fread($fh,1024)) {
		$out.=$tmpData;
	}
	fclose($fh);

	return $out;
}

$_ENV['tty']=getPipeContents('tty');

// gnore_user_abort + proc_open was the only combination that i found that kills child processes when pressing Control + C
ignore_user_abort(true);
function call($program) {
	$null=array();
	$ph=proc_open($program.' > '.$_ENV['tty'].' < '.$_ENV['tty'], $null, $null);

	$proc_status=proc_get_status($ph);

	$proc_status['running']=true;
	while ($proc_status['running']) {
		usleep(200);
		$proc_status=proc_get_status($ph);
	}
}


function checkCache() {
	// Check if we hgave cache
	if (!file_exists($GLOBALS['tupac_directory'].'/tupac_data')){
		// If any of the main files is missing, regenerate cache
		Std::rm($GLOBALS['tupac_directory'].'/tupac_data');
		Std::rm($GLOBALS['tupac_directory'].'/tupac_filelist');

		sendMessage('generatingCache',array());
		
		// Gather information
		$GLOBALS['data']=new PacmanData();
		$GLOBALS['data']->updateList();
	} else {
		// Read Cache
		$GLOBALS['data']=unserialize(getFileContents($GLOBALS['tupac_directory'].'/tupac_data'));

		if ($GLOBALS['data']===false) {
			// Unserialized failed
			sendMessage('generatingCacheCorrupted',array());
			
			Std::rm($GLOBALS['tupac_directory'].'/tupac_data');
			Std::rm($GLOBALS['tupac_directory'].'/tupac_filelist');
			
			$GLOBALS['data']=new PacmanData();
			$GLOBALS['data']->updateList();
		} else
			switch ($GLOBALS['data']->verifyIntegrity()) {
				case 'cache_updated':
					sendMessage('generatingCacheTupacUpdated',array());
					
					Std::rm($GLOBALS['tupac_directory'].'/tupac_data');
					Std::rm($GLOBALS['tupac_directory'].'/tupac_filelist');
					
					$GLOBALS['data']=new PacmanData();
					$GLOBALS['data']->updateList();
					break;
				case 'database_outdated':
					//sendMessage('generatingCacheDatabaseUpdated',array());
					
					$anyChanges=false;
					
					// Get currently available repos
					$available_repos=array();
					$repoDirList=getRepoDirList();
					foreach ($repoDirList as $repo) {
						if (substr($repo,0,1)!='.')
							$available_repos[$repo]=NULL;
					}

					// The database needs to be updated
					$mustRecheckLocal=false;
					$repo_list=$GLOBALS['data']->repo_list;
					
					foreach ($repo_list as $repo => $repoData) {
						unset($available_repos[$repo]);
						
						if ($repo=='local' && $GLOBALS['data']->verification[$repo]!=PacmanData::verifyRepo($repo) ) {
							// We check local separately, since it is not a repo.
							sendMessage('repoIsUpdated', array('%r'=>$repo));
							
							$anyChanges=true;
							
							$installed=true;
							
							$available_packages=array();
							// get current packages
							$dh=opendir(getRepoDir($repo));
							while (FALSE !== $package=readdir($dh)) {
								if (substr($package,0,1)!='.' && is_dir(getRepoDir($repo).$package)) {
									$packageName=preg_replace('/\-[0-9a-z\._A-Z]*\-[0-9a-z\._A-Z]*$/','',$package);
									$available_packages[$packageName]=$package;
								}
							}
							closedir($dh);
							
							$installed_packages=$GLOBALS['data']->installed_packages;
							
							// Walk packages of the current repo
							foreach ($installed_packages as $packageName=>$packageData) {
								if (!$available_packages[$packageName]) {
									// The package is not installed anymore
									sendMessage('removePackage',array('%p'=>$packageName), VERBOSITY_1);
									unset($repo_list['local'][$packageName]);
									foreach($repo_list as $tmpRepo=>$tmpRepoData) {
										if ($tmpRepoData[$packageName])
											$repo_list[$tmpRepo][$packageName]['installed']='false';
									}
									unset($installed_packages[$packageName]);
								} elseif ($packageData['dir']!=$available_packages[$packageName]) {
									// Package updated. Since it is the list of installed packages, set its version to installed version
									sendMessage('updatePackage',array('%p'=>$packageName), VERBOSITY_1);
									$installed_packages[$packageName]=PacmanData::getPackageData($repo, $available_packages[$packageName], 'true');
									$installed_info=$installed_packages[$packageName]['version'];
									$installed_packages[$packageName]['installed']=$installed_info;
									foreach($repo_list as $tmpRepo=>$tmpRepoData) {
										if ($tmpRepoData[$packageName]) {
											$repo_list[$tmpRepo][$packageName]['installed']=$installed_info;
											// in local installed version equals to avaiable version
											if ($repo=='local')
												$repo_list[$tmpRepo][$packageName]['version']=$installed_info;
										}
									}
								}

								unset($available_packages[$packageName]);
							}
							
							// Add new packages. This is, packages that are listed in the repo directory but that aren't marked as installed.
							foreach ($available_packages as $packageName => $package)
								if ($package) {
									$packageFound=false;
									sendMessage('addPackage',array('%p'=>$packageName), VERBOSITY_1);
									$installed_packages[$packageName]=PacmanData::getPackageData($repo, $available_packages[$packageName], 'true');
									$installed_info=$installed_packages[$packageName]['version'];
									$installed_packages[$packageName]['installed']=$installed_info;
									foreach($repo_list as $tmpRepo=>$tmpRepoData) {
										if ($tmpRepo!='local' && $tmpRepoData[$packageName]) {
											$repo_list[$tmpRepo][$packageName]['installed']=$installed_info;
											$packageFound=true;
										}
									}
									// It wasn't in any repo. Let's add it to the pseudorepo local
									if (!$packageFound)
										$repo_list['local'][$packageName]=$installed_packages[$packageName];
								}
							
							$GLOBALS['data']->installed_packages=$installed_packages;
							
							$GLOBALS['data']->verification[$repo]=PacmanData::verifyRepo($repo);
							
							continue;
						}
						
						if (!is_dir(getRepoDir($repo))) {
							sendMessage('repoIsGone', array('%r'=>$repo));
							unset($repo_list[$repo]);
							foreach ($repoData as $packageName=>$packageData)
								if ($GLOBALS['data']->installed_packages[$packageName]) {
									$packageFound=false;
									foreach ($repo_list as $tmpRepo=>$tmpRepoData) {
										if ($tmpRepo!='local' && $tmpRepoData[$packageName]) {
											$packageFound=true;
											break;
										}
									}
									if (!$packageFound)
										$repo_list['local'][$packageName]=$packageData;
								}
							
							$anyChanges=true;
						} else if ( $GLOBALS['data']->verification[$repo]!=PacmanData::verifyRepo($repo) ) {
							sendMessage('repoIsUpdated', array('%r'=>$repo));
							$anyChanges=true;

							$available_packages=array();
							
							// get current packages
							$dh=opendir(getRepoDir($repo));
							while (FALSE !== $package=readdir($dh))
								if (substr($package,0,1)!='.' && is_dir(getRepoDir($repo).$package)) {
									$packageName=preg_replace('/\-[0-9a-z\._A-Z]*\-[0-9a-z\._A-Z]*$/','',$package);
									$available_packages[$packageName]=$package;
								}
							closedir($dh);

							// Walk packages of the current repo
							foreach ($repoData as $packageName=>$packageData) {
								if (!$available_packages[$packageName]) {
									// The package doesn't exist anymore in the directory of the repo
									sendMessage('removePackage',array('%p'=>$packageName), VERBOSITY_1);
									if ($packageData['installed']!='false') {
										// since it is installed, we have to move it to the pseudorepo local
										$repo_list['local'][$packageName]=$repo_list[$repo][$packageName];
										$packageFound=false;
										foreach($repo_list as $tmpRepo=>$tmpRepoData) {
											if ($tmpRepo!='local' && $tmpRepoData[$packageName])
												$packageFound=true;
										}
										
										if (!$packageFound)
											$repo_list['local'][$packageName]=$packageData;
										
									}
									unset($repo_list[$repo][$packageName]);
								} elseif ($packageData['dir']!=$available_packages[$packageName]) {
									// Package updated
									sendMessage('updatePackage',array('%p'=>$packageName), VERBOSITY_1);
									$installed_info=$packageData['installed'];
									$repo_list[$repo][$packageName]=PacmanData::getPackageData($repo, $available_packages[$packageName], $installed_info);
								}

								unset($available_packages[$packageName]);
							}

							// Add new packages. This is, packages that are listed in the repo directory but that aren't available in the repo.
							foreach ($available_packages as $packageName => $package) {
								if ($package) {
									sendMessage('addPackage',array('%p'=>$packageName), VERBOSITY_1);
									
									$repo_list[$repo][$packageName]=PacmanData::getPackageData($repo, $package, $installed);

									// Add install information
									if ($repo_list['local'][$packageName])
										$GLOBALS['data']->installed_packages[$packageName]=$repo_list['local'][$packageName];

									if ($GLOBALS['data']->installed_packages[$packageName]) {
										unset($repo_list['local'][$packageName]);
										$repo_list[$repo][$packageName]['installed']=$GLOBALS['data']->installed_packages[$packageName]['version'];
										
										// remove it from the pseudo repo local
										if ($repo_list['local'][$packageName])
											unset($repo_list['local'][$packageName]);
									} else
										$repo_list[$repo][$packageName]['installed']='false';
								}
							}

							$GLOBALS['data']->verification[$repo]=PacmanData::verifyRepo($repo);
						}
					}

					$GLOBALS['data']->repo_list=$repo_list;

					foreach($available_repos as $repo=>$dummy) {
						sendMessage('repoIsNew', array('%r'=>$repo));
						$GLOBALS['data']->getRepoData($repo);
						$mustRecheckLocal=true;
					}

					if ($mustRecheckLocal) {
						$anyChanges=true;
						sendMessage('recheckingLocal',array());
						// we copy installed packages to pseudorepo local
						$GLOBALS['data']->repo_list['local'] = array_merge(
							$GLOBALS['data']->installed_packages
							,$GLOBALS['data']->repo_list['local']
						);
						$GLOBALS['data']->recheckLocal();
					}
					
					if ($anyChanges) {
						sendMessage('saving',array());
						$GLOBALS['data']->saveData();
					}
					
					break;
				default:
					// We never get here
					//sendMessage('reusingCache',array());
					break;
			}
	}
}

function setProxy($proxy) {
	switch ($proxy) {
		case "none":
			setSettings('PROXY_NAME');
			setSettings('PROXY_PORT');
			break;
		default:
			$proxy=split(':',$proxy);
			if (count($proxy)!=2) {
				$GLOBALS['die']='wrongProxy';
				return;
			}
			
			setSettings('PROXY_NAME', $proxy[0]);
			setSettings('PROXY_PORT', $proxy[1]);
			break;
	}
}

/******************************************
*******************************************
*******************************************
*******THE PROGRAM STARTS HERE*************
*******************************************
*******************************************
******************************************/


if ( !file_exists(($tupac_directory)) )
	if ($uid==0) {
		mkdir($tupac_directory);
		chmod($tupac_directory,0777);
	} else {
		// TODO Make this message localized
		//sendMessage('cacheNeedRoot',array());
		Std::send('In order to create the TUPAC_CACHE directory you need to be root. ('.$tupac_directory.')');
		exit;
	}

$colorMap="darkbg";

$lang=getPipeContents('echo -n $LANG');

$GLOBALS['NO_AUR']=false;
$argv_count=count($argv);
for ($i=1; $i<$argv_count; $i++) {
	switch ($argv[$i]) {
		case '--safe':
			$GLOBALS['onlySafeAurResults']=true;
			array_splice($argv,$i,1);
			$i--;
			break;
		case '--noaur':
			$GLOBALS['NO_AUR']=true;
			array_splice($argv,$i,1);
			$i--;
			break;
		case '--noprompt':
			$GLOBALS['NO_PROMP']=true;
			array_splice($argv,$i,1);
			$i--;
			break;
		case '--repos':
			$GLOBALS['active_repos']=','.$argv[$i+1].',';
			array_splice($argv,$i,2);
			$i--;
			break;
		case '--set-proxy':
			$GLOBALS['die']='';
			setProxy($argv[$i+1]);
			break;
		case '--lang':
			$lang=$argv[$i+1];
			array_splice($argv,$i,2);
			$i--;
			break;
		case '--color':
			$colorMap=$argv[$i+1];
			array_splice($argv,$i,2);
			$i--;
			break;
		case '--lightbg':
			$colorMap='lightbg';
			array_splice($argv,$i,1);
			$i--;
			break;
		case '--help':
		case '-h':
			$GLOBALS['die']='usage';
			break;
	}
}

mapColors($colorMap);

