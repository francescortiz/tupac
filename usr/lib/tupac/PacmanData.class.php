<?php

define(VERSION_REGEX, '/\-[0-9a-z\._A-Z\+\~\:]+\-[0-9a-z\._A-Z\+\~\:]+$/');

// This class stores pacman database cache and manages it.
class PacmanData {
	var $repo_list;
	var $file_list;
	var $verification;

	var $cache_version;

	var $installed_packages;

	public function PacmanData() {
		$this->cache_version=$GLOBALS['cache_version'];
	}
	
	public function verifyRepo($repo) {
		if ($repo!='local')
			return file_exists(getRepoDir($repo).'.lastupdate')?getPipeContents('cat '.getRepoDir($repo).'.lastupdate'):getPipeContents('find '.getRepoDir($repo).' -maxdepth 1');
		else
			return getPipeContents('find '.getRepoDir($repo).' -maxdepth 1');
	}

	public static function getPackageData($repo, $package, $installed) {
		$packageName=preg_replace(VERSION_REGEX,'',$package);
		$packageDesc=PacmanData::parsePacmanDataFile(getRepoDir($repo).$package.'/desc');
		if ($packageName!=$packageDesc['%NAME%'][0]) {
			sendMessage('integrityError',array(
				'%p'=>$packageName,
				'%desc_name'=>$packageDesc['%NAME%'][0]
			));
			exit;
		}
		return array(
			'dir'			=> $package
			,'name'			=> $packageName
			,'installed'		=> $installed
			,'desc'			=> $packageDesc['%DESC%'][0]
			,'version'		=> $packageDesc['%VERSION%'][0]
		);
	}

	public function getRepoData($repo) {
		if ($repo=='local')
			$installed='true';
		else
			$installed='false';
		
		$this->repo_list[$repo]=array();
		$this->verification[$repo]=PacmanData::verifyRepo($repo);
		$packages=opendir(getRepoDir($repo));
		while ( false !== ($package=readdir($packages)) ) {
			if (substr($package,0,1)!='.') {
				$packageName=preg_replace(VERSION_REGEX,'',$package);
				$this->repo_list[$repo][$packageName]=PacmanData::getPackageData($repo, $package, $installed);
			}
		}
		closedir($packages);
	}

	public function recheckLocal() {
		$this->installed_packages=array();
		
		foreach ($this->repo_list as $repo=>$repoData)
			foreach ($repoData as $packageName=>$packageData) {
				if ($this->repo_list['local'][$packageName])
					$this->installed_packages[$packageName]=$this->repo_list['local'][$packageName];

				if ($repo!='local')
					if ($this->installed_packages[$packageName]) {
						unset($this->repo_list['local'][$packageName]);
						$this->repo_list[$repo][$packageName]['installed']=$this->installed_packages[$packageName]['version'];
					} else
						$this->repo_list[$repo][$packageName]['installed']='false';
			}
	}

	public function saveData() {
		// Save cache
		Std::write($GLOBALS['tupac_directory'].'/tupac_data',serialize($this));
		chmod($GLOBALS['tupac_directory'].'/tupac_data',0666);
	}

	public function updateList() {
		$this->repo_list=array();
		$this->verification=array();

		$repos=getRepoDirList();
		foreach ( $repos as $repo ) {
			if ( substr($repo,0,1)!='.' && is_dir(getRepoDir($repo)) ) {
				$this->getRepoData($repo);
			}
		}
		
		$this->recheckLocal();

		$this->saveData();
	}

	static function checkIfSearchMatches($searchArray, $packageData) {
		$matches=count($searchArray);
		
		foreach ($searchArray as $searchWord)
			if (
				//preg_match('/'.$searchWord.'/i', $packageData['name']) // check package name
				//|| preg_match('/'.$searchWord.'/i', $packageData['desc']) // check description
				stripos($packageData['name'], $searchWord)!==false // check package name
				|| stripos($packageData['desc'], $searchWord)!==false // check description
				)
				$matches--;
		

		return $matches==0;
	}

	public function searchByPackageNameAndDescription($searchArray) {
		
		$tmpLocal=$this->repo_list['local'];

		$packages_available_to_install=array();
		$packages_available_to_install_in_aur=array();

		$repoCount=0;
		
		// First known repositories
		foreach ($this->repo_list as $repo=>$repoData) {
			if (
				$repo=='local'
					||
				( strlen($GLOBALS['active_repos']) && false===strpos($GLOBALS['active_repos'],','.$repo.',') )
				)
				continue;

			$firstCoincidence=true;

			foreach ($repoData as $packageData) {
				if ( PacmanData::checkIfSearchMatches($searchArray, $packageData) )	{
					if ($firstCoincidence) {
						$firstCoincidence=false;
						$currRepoColor=$GLOBALS['repoColors'][$repoCount%count($GLOBALS['repoColors'])];
						$repoCount++;
					}

					$packages_available_to_install[]=$repo.'/'.$packageData['name'];
					$index=count($packages_available_to_install);
					
					if ($packageData['installed']!=='false')
						if ($packageData['version']==$this->installed_packages[$packageData['name']]['version'])
							sendMessage('packageInfoListInstalled',array(
								'%c'=>$index,
								'%z'=>$currRepoColor,
								'%r'=>$repo,
								'%p'=>$packageData['name'],
								'%v'=>$packageData['version'],
								'%d'=>$packageData['desc'],
								'%i'=>$packageData['installed'],
								'%s'=>''
							));
						else
							sendMessage('packageInfoListOtherInstalled',array(
								'%c'=>$index,
								'%z'=>$currRepoColor,
								'%r'=>$repo,
								'%p'=>$packageData['name'],
								'%v'=>$packageData['version'],
								'%d'=>$packageData['desc'],
								'%i'=>$packageData['installed'],
								'%s'=>''
							));
					else
						sendMessage('packageInfoListNotInstalled',array(
								'%c'=>$index,
								'%z'=>$currRepoColor,
								'%r'=>$repo,
								'%p'=>$packageData['name'],
								'%v'=>$packageData['version'],
								'%d'=>$packageData['desc'],
								'%i'=>'',
								'%s'=>''
							));
				}
			}
		}

		if (!$GLOBALS['NO_AUR']) {
			PacmanData::searchInAur($searchArray);
			$currRepoColor=$GLOBALS['repoColors'][$repoCount%count($GLOBALS['repoColors'])];
			$repoCount++;

			$repo='aur';

			foreach ($GLOBALS['aur_matches'] as $packageName=>$packageData) {
				$packages_available_to_install_in_aur[]=$repo.'/'.$packageData['name'];
				$index=( count($packages_available_to_install)+count($packages_available_to_install_in_aur) );

				if ($this->installed_packages[$packageName]) {
					$packageData['installed']=$this->installed_packages[$packageName]['version'];
					unset($tmpLocal[$packageName]);
				}
				
				if ($packageData['installed']!=='false')
					if ($packageData['version']==$packageData['installed'])
						sendMessage('aurPackageInfoListInstalled',array(
							'%c'=>$index,
							'%z'=>$currRepoColor,
							'%r'=>$repo,
							'%p'=>$packageData['name'],
							'%v'=>$packageData['version'],
							'%d'=>$packageData['desc'],
							'%i'=>$packageData['installed'],
							'%s'=>$packageData['safe'],
							'%x'=>$packageData['votes']
						));
					else
						sendMessage('aurPackageInfoListOtherInstalled',array(
							'%c'=>$index,
							'%z'=>$currRepoColor,
							'%r'=>$repo,
							'%p'=>$packageData['name'],
							'%v'=>$packageData['version'],
							'%d'=>$packageData['desc'],
							'%i'=>$packageData['installed'],
							'%s'=>$packageData['safe'],
							'%x'=>$packageData['votes']
						));
				else
					sendMessage('aurPackageInfoListNotInstalled',array(
						'%c'=>$index,
						'%z'=>$currRepoColor,
						'%r'=>$repo,
						'%p'=>$packageData['name'],
						'%v'=>$packageData['version'],
						'%d'=>$packageData['desc'],
						'%i'=>'',
						'%s'=>$packageData['safe'],
						'%x'=>$packageData['votes']
					));
			}
		}

		// Then local repository
		foreach ($tmpLocal as $packageName=>$packageData) {
			if (PacmanData::checkIfSearchMatches($searchArray, $packageData)) {
				sendMessage('packageInfoListInstalled',array(
					'%c'=>'',
					'%z'=>'',
					'%r'=>'_NO_REPO_',
					'%p'=>$packageData['name'],
					'%v'=>$packageData['version'],
					'%d'=>$packageData['desc'],
					'%i'=>'',
					'%s'=>''
				));
			}
		}

		if (!$GLOBALS['NO_PROMP']) {
			if (count($packages_available_to_install) || count($packages_available_to_install_in_aur)) {
				sendMessage('choosePackages',array());
				$input=readline('==: ');
				$input=split("[\n\r\t ]+",$input);
				if (count($input)) {
					$packages_to_install='';
					$packages_to_install_in_aur='';
					foreach($input as $package_to_install)
						if ($package_to_install<=count($packages_available_to_install))
							$packages_to_install.=$packages_available_to_install[$package_to_install-1].' ';
						else
							$packages_to_install_in_aur.=$packages_available_to_install_in_aur[$package_to_install-count($packages_available_to_install)-1].' ';

					if (preg_match('/\w+/',$packages_to_install)) {
						sendMessage('callingPacman',array());
						call('sudo '.getSettings('PACMAN_BINARY', 'pacman').' -S '.$packages_to_install);
					}
					if (preg_match('/\w+/',$packages_to_install_in_aur)) {
						sendMessage('callingYaourt',array());
						call('yaourt -S '.$packages_to_install_in_aur);
					}
				}
			} else
				sendMessage('nothingToInstall',array());
		}
	}
	
	public static function getAurRPCPackageInfo($packageName) {
		global $include_path;
		
		$responseRaw=httpSocketConnection(
			'aur.archlinux.org',
			'GET',
			'/rpc.php',
			'type=info&arg='.$packageName
		);
		$AurRPCpackageData = json_decode($responseRaw);
		//print_r($AurRPCpackageData);
		return $AurRPCpackageData;
	}
	
	public function checkAurUpdates() {
		$tmpLocal=$this->repo_list['local'];

		$packages_available_to_install_in_aur=array();

		$currRepoColor=$GLOBALS['repoColors'][0%count($GLOBALS['repoColors'])];

		$repo='aur';

		foreach ($tmpLocal as $packageName=>$packageData) {
			$aurInfo = PacmanData::getAurRPCPackageInfo($packageName);
			if ($aurInfo->type != 'error') {
				$packages_available_to_install_in_aur[]=$repo.'/'.$packageData['name'];
				$index = count($packages_available_to_install_in_aur);
	
				if ($this->installed_packages[$packageName]) {
					$packageData['installed']=$this->installed_packages[$packageName]['version'];
					unset($tmpLocal[$packageName]);
				}
				
				if ($packageData['installed']!=='false')
					if ($aurInfo->results->Version==$packageData['installed'])
						sendMessage('aurPackageInfoListNotInstalled',array(
							'%c'=>$index,
							'%z'=>$currRepoColor,
							'%r'=>$repo,
							'%p'=>$packageData['name'],
							'%v'=>$aurInfo->results->Version,
							'%d'=>$packageData['desc'],
							'%i'=>$packageData['installed'],
							'%s'=>$packageData['safe'],
							'%x'=>$aurInfo->results->NumVotes
						));
					else
						sendMessage('aurPackageInfoListOtherInstalled',array(
							'%c'=>$index,
							'%z'=>$currRepoColor,
							'%r'=>$repo,
							'%p'=>$packageData['name'],
							'%v'=>$aurInfo->results->Version,
							'%d'=>$packageData['desc'],
							'%i'=>$packageData['installed'],
							'%s'=>$packageData['safe'],
							'%x'=>$aurInfo->results->NumVotes
						));
			}
		}

		// Then local repository
		foreach ($tmpLocal as $packageName=>$packageData) {
			sendMessage('packageInfoListNotInstalled',array(
				'%c'=>'',
				'%z'=>'',
				'%r'=>'_NO_REPO_',
				'%p'=>$packageData['name'],
				'%v'=>$packageData['version'],
				'%d'=>$packageData['desc'],
				'%i'=>'',
				'%s'=>''
			));
		}

		if (!$GLOBALS['NO_PROMP']) {
			if (count($packages_available_to_install_in_aur)) {
				sendMessage('choosePackages',array());
				$input=readline('==: ');
				$input=split("[\n\r\t ]+",$input);
				if (count($input)) {
					$packages_to_install='';
					$packages_to_install_in_aur='';
					foreach($input as $package_to_install)
						$packages_to_install_in_aur.=$packages_available_to_install_in_aur[$package_to_install-1].' ';

					if (preg_match('/\w+/',$packages_to_install_in_aur)) {
						sendMessage('callingYaourt',array());
						call('yaourt -S '.$packages_to_install_in_aur);
					}
				}
			} else
				sendMessage('nothingToInstall',array());
		}
		
	}

	public function verifyIntegrity() {
		if ($this->cache_version!=$GLOBALS['cache_version'])
			return 'cache_updated';

		return 'database_outdated';
	}

	static function parsePacmanDataFile($file) {
		$fh=fopen($file,'r');
		$data=array();
		$key="";
		while ($line=fgets($fh)) {
			$line=trim($line);
			if (substr($line,0,1)=='%') {
				$key=$line;
				$data[$key]=array();
			} else if (strlen($line)) {
				$data[$key][]=$line;
			}
		}
		fclose($fh);

		return $data;
	}

	public function initializeFileList() {
		if (!file_exists($GLOBALS['tupac_directory'].'/tupac_filelist')) {
			sendMessage('creatingFileList',array());
			$GLOBALS['pacman_file_list']=new PacmanFilesList();
		} else if (!$GLOBALS['pacman_file_list']) {
			//sendMessage('reusingFileList',array());
			$GLOBALS['pacman_file_list']=unserialize(getFileContents($GLOBALS['tupac_directory'].'/tupac_filelist'));
			
			if ($GLOBALS['pacman_file_list']===false) {
				unlink($GLOBALS['tupac_directory'].'/tupac_filelist');
				sendMessage('corruptedFileList',array());
				$GLOBALS['pacman_file_list']=new PacmanFilesList();
			} else
				$GLOBALS['pacman_file_list']->updateFileList();
		}
	}
	
	public function findFileOwner($file) {
		$file=str_replace('./','', $file);
		if (!preg_match('/^\//',$file))
			$file=getcwd().'/'.$file;

		$currDir=getcwd();
		$filename=preg_replace('/.*\//','',$file);
		$dir=substr($file,0,strlen($file)-strlen($filename));
		chdir($dir);
		$file=getcwd().'/'.$filename;
		chdir($currDir);
		
		PacmanData::initializeFileList();
		
		$fileFound=true;
		if (!file_exists($file) && !is_link($file))
			$fileFound=false;
		
		$file_list=$GLOBALS['pacman_file_list']->file_list;

		foreach ($file_list as $packageName=>$filesData) {
			$packageFiles=$filesData['%FILES%'];
			$packageFiles_count=count($packageFiles);
			for ($i=0; $i<$packageFiles_count; $i++) {
				if ($packageFiles[$i]==$file)
					if ($fileFound)
						return sendMessage('ownedFile',array('%file'=>$file,'%p'=>$packageName,'%v'=>$filesData['version']));
					else
						return sendMessage('ownedFileMissing',array('%file'=>$file,'%p'=>$packageName,'%v'=>$filesData['version']));
			}
		}
		if ($fileFound)
			sendMessage('unownedFile',array('%file'=>$file));
		else
			sendMessage('unownedFileMissing',array('%file'=>$file));
	}

	static function searchInAur($searchArray) {
		$GLOBALS['aur_matches']=array();
		
		if ($GLOBALS['onlySafeAurResults'])
			echo 'Only safe aur results has been disabled temporaly.'."\n";
			
		$responseRaw=httpSocketConnection(
			'aur.archlinux.org',
			'GET',
			'/rpc.php',
			'type=search&arg='.urlencode($searchArray[0])
		);
		
		$AurRPCpackagesData = json_decode($responseRaw);
		
		if ( is_array($AurRPCpackagesData->results) ) {
			foreach($AurRPCpackagesData->results as $AurRPCpackageData) {
				$packageData=array();
	
				//if (strpos($test,'<span class=\'green\'>')!==false)
				//	$packageData['safe']=' (SAFE)';
	
				$packageData['name']=$AurRPCpackageData->Name;
				$packageData['version']=$AurRPCpackageData->Version;
				$packageData['votes']=$AurRPCpackageData->NumVotes;
				$packageData['desc']=$AurRPCpackageData->Description;
				$packageData['installed']='false';
				
				if (PacmanData::checkIfSearchMatches($searchArray, $packageData))
					$GLOBALS['aur_matches'][$packageData['name']]=$packageData;
			}
		}
	}

	public function checkDirectory($dir) {
		$dir=str_replace('./','', $dir);
		if (!preg_match('/^\//',$dir))
			$dir=getcwd().'/'.$dir;

		if (!is_dir($dir))
			return sendMessage('invalidDirectory', array('%dir'=>$dir));

		PacmanData::initializeFileList();

		$dir_strlen=strlen($dir);

		$progress_position=0;
		$progress_length=count($GLOBALS['pacman_file_list']->file_list);

		foreach ($GLOBALS['pacman_file_list']->file_list as $packageName=>$packageData) {
			echo "\r".(round($progress_position++/$progress_length*10000)/100)."%";
			$tmpFiles=$packageData['%FILES%'];
			$tmpFiles_count=count($tmpFiles);
			for ($i=0; $i<$tmpFiles_count; $i++) {
				$currFile = $tmpFiles[$i];
				if ( substr($currFile, 0, $dir_strlen)==$dir && !file_exists($currFile) && !is_link($currFile))
					sendMessage('fileMissing',array('%file'=>$currFile,'%p'=>$packageName));
			}
		}

		echo "\r";
		if ($GLOBALS['uid']!=0)
			sendMessage('recomendRootCheckDir',array());
	}

	static function findUnownedFiles($dir) {
		$dir=str_replace('./','', $dir);
		if (!preg_match('/^\//',$dir))
			$dir=getcwd().'/'.$dir;

		if (!is_dir($dir))
			return sendMessage('invalidDirectory',array('%dir'=>$dir));

		PacmanData::initializeFileList();

		walkDir($dir);

		$dir_strlen=strlen($dir);

		$progress_position=0;
		$progress_length=count($GLOBALS['pacman_file_list']->file_list);

		foreach ($GLOBALS['pacman_file_list']->file_list as $packageName=>$packageData) {
			echo "\r".(round($progress_position++/$progress_length*10000)/100).'%';
			$tmpFiles=$packageData['%FILES%'];
			$tmpFiles_count=count($tmpFiles);
			for ($i=0; $i<$tmpFiles_count; $i++) {
				$currFile = $tmpFiles[$i];
				$GLOBALS['WALK_DIR_RESULTS'][$currFile]=FALSE;
			}
		}

		echo "\r";

		foreach ($GLOBALS['WALK_DIR_RESULTS'] as $file=>$ownerMissing)
			if ($ownerMissing===TRUE)
				echo $file."\n";

		if ($GLOBALS['uid']!=0)
			sendMessage('recomendRootOrphans',array());
	}

}
