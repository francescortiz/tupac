<?php

class TupacSettings {

	var $data;

    function TupacSettings() {
    	$this->data = array();
    	
    	if (file_exists($GLOBALS['tupac_config_file'])) {
    		$tmpdata = PacmanData::parsePacmanDataFile($GLOBALS['tupac_config_file']);
    		foreach ($tmpdata as $key=>$value) {
    			$key = substr($key,1,strlen($key)-2);
    			$this->data[$key] = $value[0];
    		}
    	}
    		
    }
    
    public static function initialize() {
			$GLOBALS['settings']=new TupacSettings();
    }
    
    public function set($key, $value=NULL) {
    	if ($value!==NULL)
    		$this->data[$key] = $value;
    	else
    		unset($this->data[$key]);
    	
		$fh=fopen($GLOBALS['tupac_config_file'],'w');
		foreach ($this->data as $key=>$value) {
			fwrite($fh, '%'.$key."%\n".$value."\n");
		}
		fclose($fh);
    }

    public function get($key, $default=NULL) {
    	return $this->data[$key]?$this->data[$key]:$default;
    }
}

TupacSettings::initialize();

function setSettings($key, $value=NULL) {
	$GLOBALS['settings']->set($key, $value);
}

function getSettings($key, $default=NULL) {
	return $GLOBALS['settings']->get($key, $default);
}
?>