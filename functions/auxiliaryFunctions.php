<?php

//Fremdcode von http://php.net/manual/de/function.copy.php
function recurse_copy($src,$dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                recurse_copy($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

function zipFolderContent($folder, $fileName) {
	$zip = new ZipArchive;
	$zipFile = $folder.'/'.$fileName.'.zip';
	if ($zip->open($zipFile, ZipArchive::CREATE) !== TRUE) {
		die('cannot open '.$fileName);
	}	
	$options = array('add_path' => $fileName.'/', 'remove_all_path' => TRUE);
	$zip->addGlob($folder.'/*.html', 0, $options);
	$zip->addGlob($folder.'/*.x*', 0, $options);
	$zip->addGlob($folder.'/*.js', 0, $options);
	$zip->addGlob($folder.'/*.php', 0, $options);
	$zip->addGlob($folder.'/*.c*', 0, $options);
	$zip->addGlob($folder.'/*.ttl', 0, $options);
	$zip->addGlob($folder.'/*.rdf', 0, $options);
	$zip->addGlob($folder.'/*.kml', 0, $options);
	$zip->addFile($folder.'/dataPHP', $fileName.'/dataPHP');

	$zip->close();
}

/*
$xml = file_get_contents('../marcxml/vd16/Leipzig/567203298.xml');
$dom = new DOMDocument();
$dom->loadXML($xml);
$xpath = new DOMXPath($dom);
$xpath->registerNamespace("marc", "http://www.loc.gov/MARC21/slim");
$nodeList = $dom->getElementsByTagName('record');
foreach ($nodeList as $recordNode) {
	getValuesMARC($recordNode, $xpath, '035', 'a');
}
*/

function getValuesMARC($recordNode, $xpath, $field, $subfield) {
	$result = array();
	$resultNodeList = $xpath->evaluate('marc:datafield[@tag="'.$field.'"]/marc:subfield[@code="'.$subfield.'"]', $recordNode);
    foreach ($resultNodeList as $node) {
        $result[] = $node->nodeValue;
    }
	if ($result == array()) {
		return(null);
	}
	return($result);
}



?>
