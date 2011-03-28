<?php
/*
 * Created on 23/05/2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */


/**************
 message format
%c: package count
%r: repo
%p: package name
%v: package version
%d: package description
%i: installed version/empty
%s: safe? if true it is set to ' (SAFE)' (with a space in the beggining)

%dir: directory
%file: file
%desc_name : it is the content of the desc file in the repo. Used for integrity check

%z: repo color
**************/

// Assign language
$lang=preg_replace('/\..*/','',$lang);

$lang_file=$include_path.'/localization/'.$lang.'.php';
if ( !file_exists($lang_file) )
	$lang_file=$include_path.'/localization/en_US.php';

include $lang_file; 


