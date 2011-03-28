<?php

// This class creates files cache and stores it. It is in a separate class to be able to divide cache into parts.
class PacmanFilesList {
	var $file_list;
	
	var $verification;
	
	public function PacmanFilesList() {
		$this->updateFileList();
	}

	public function updateFileList() {
		//$this->file_list=array();
		
		if ($this->verification!=$GLOBALS['data']->verification['local']) {
			$file_list_copy=$this->file_list;
			
			foreach ($GLOBALS['data']->installed_packages as $packageName=>$packageData) {
				if ($this->file_list[$packageName]) {
					if ($this->file_list[$packageName]['version']!=$packageData['version']) {
						// Update
						$this->file_list[$packageName]=PacmanData::parsePacmanDataFile($GLOBALS['pacman_directory'].'local/'.$packageData['dir'].'/files');
						$packageFiles=$this->file_list[$packageName]['%FILES%'];
						$files_count=count($packageFiles);
						for ($i=0; $i<$files_count; $i++) {
							eval('$packageFiles[$i]="/'.str_replace(array('?','$'),array('\?', '\$'), $packageFiles[$i]).'";');
						}
						$this->file_list[$packageName]['%FILES%']=$packageFiles;
						$this->file_list[$packageName]['version']=$packageData['version'];
					}
				} else {
					// Add
					$this->file_list[$packageName]=PacmanData::parsePacmanDataFile($GLOBALS['pacman_directory'].'local/'.$packageData['dir'].'/files');
					$packageFiles=$this->file_list[$packageName]['%FILES%'];
					$files_count=count($packageFiles);
					for ($i=0; $i<$files_count; $i++) {
						eval('$packageFiles[$i]="/'.str_replace(array('?','$'),array('\?', '\$'), $packageFiles[$i]).'";');
					}
					$this->file_list[$packageName]['%FILES%']=$packageFiles;
					$this->file_list[$packageName]['version']=$packageData['version'];				
				}
				unset($file_list_copy[$packageName]);
			}
			
			if (count($file_list_copy))
				foreach($file_list_copy as $packageName=>$filesData)
					unset($this->file_list[$packageName]);
			
			$this->verification=$GLOBALS['data']->verification['local'];
	
			Std::write($GLOBALS['tupac_directory'].'/tupac_filelist',serialize($this));
			chmod($GLOBALS['tupac_directory'].'/tupac_filelist',0666);
		}
	}
}
