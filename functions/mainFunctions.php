<?php

set_time_limit(600);

function getFromUNApi($pathCSV, $format, $pathPut, $fillZeros = false) {
    if (!is_dir($pathPut)) {
        mkdir($pathPut, 0777, true);
    }

    $csv = file_get_contents($pathCSV);
    $document = str_getcsv($csv, "\n");
    array_shift($document);
    $void = array();
    foreach ($document as $row) {
        $fields = str_getcsv($row, ";");
        $ppn = $fields[0];
        if ($fillZeros == true and strlen($ppn) == 8) {
            $ppn = '0'.$ppn;
        }
        if (file_exists($pathPut.$ppn.'.xml')) {
        	continue;
        }
        //$path = 'http://unapi.gbv.de/?id=gvk:ppn:'.$ppn.'&format='.$format;
        $path = 'http://unapi.k10plus.de/?id=gvk:ppn:'.$ppn.'&format='.$format;
        $string = file_get_contents($path);
        if ($string) {
            file_put_contents($pathPut.$ppn.'.xml', $string);
        }
        else {
            $void[] = $ppn;
        }
    }
    $data = implode('|', $void);
    file_put_contents($pathPut.'/voidPPNs.txt', $data);
}

function makeRawDC($pathFrom, $pathTo, $pathXSL = 'marcxmlToDC.xsl') {
    if (!is_dir($pathTo)) {
        mkdir($pathTo, 0777, true);
    }
    $xsl = new DOMDocument;
    $xsl->load($pathXSL);
    $proc = new XSLTProcessor;
    $proc->importStyleSheet($xsl);
    $dir = dir($pathFrom);
    while ($fileName = readdir($dir->handle)) {
        if (substr($fileName, -4) != '.xml') {
            continue;
        }
        $pathLoad = $pathFrom.$fileName;
        $xml = new DOMDocument;
        $xml->load($pathLoad);
        $dc = $proc->transformToDoc($xml);
        $dc->formatOutput = true;
        $dc->save($pathTo.$fileName);
    }
}

function makeRDF($pathFrom, $saveTo = 'publications') {

    $graph = new EasyRdf_Graph();
    EasyRdf_Namespace::set('dcterms', 'http://purl.org/dc/terms/');
    EasyRdf_Namespace::set('gnd', 'http://d-nb.info/gnd/');
    EasyRdf_Namespace::set('pcp', 'http://aditus.catalogus-professorum.org/');
    EasyRdf_Namespace::set('gndo', 'http://d-nb.info/standards/elementset/gnd#');
    EasyRdf_Namespace::set('dbo', 'http://dbpedia.org/ontology/');
    EasyRdf_Namespace::set('fabio', 'http://purl.org/spar/fabio/');

    $dir = dir($pathFrom);
    while ($fileName = readdir($dir->handle)) {
        if (substr($fileName, -4) != '.xml') {
            continue;
        }
        $pathLoad = $pathFrom.$fileName;
        $xml = new DOMDocument;
        $xml->load($pathLoad);
        $graph = addFromDOM($graph, $xml, substr($fileName, 0, -4));
    }

	$serialiser = new EasyRdf_Serialiser_Turtle;
	$turtle = $serialiser->serialise($graph, 'turtle');
	file_put_contents('RDF/'.$saveTo.'.ttl', $turtle); 

	$serialiserX = new EasyRdf_Serialiser_RdfXml;
	$rdfxml = $serialiserX->serialise($graph, 'rdfxml');
	file_put_contents('RDF/'.$saveTo.'.rdf', $rdfxml);
}

function addFromDOM($graph, $dom, $recordName) {
    $recordNodes = $dom->getElementsByTagName('record');
    if (count($recordNodes) != 1) {
        throw new Exception(count($recordNodes).' records in '.$recordName.'.xml');
    }
    foreach ($recordNodes as $recordNode) {
        $recordResource = $graph->resource('pcp:publications/k10p_'.$recordName, 'dcterms:BibliographicResource');
        $children = $recordNode->childNodes;
        foreach ($children as $child) {
            if ($child->nodeName == 'title' or $child->nodeName == 'publisher' or $child->nodeName == 'date' or $child->nodeName == 'format') {
                $recordResource->addLiteral('dcterms:'.$child->nodeName, $child->nodeValue);
            }
            elseif ($child->nodeName == 'language') {
                $recordResource->addLiteral('dcterms:language', $child->nodeValue, 'iso6392'); 
            }
            elseif ($child->nodeName == 'pages') {
                $recordResource->addLiteral('fabio:hasPageCount', $child->nodeValue);
            }
            elseif ($child->nodeName == 'place') {
                $recordResource->addLiteral('fabio:hasPlaceOfPublication', $child->nodeValue);
            }            
            elseif ($child->nodeName == 'subject') {
                $recordResource->addLiteral('dc:subject', $child->nodeValue, 'http://uri.gbv.de/terminology/aadgenres/');
            }            
            elseif ($child->nodeName == 'hasFormat') {
                $recordResource->addResource('dcterms:hasFormat', $child->nodeValue);
            }
            elseif ($child->nodeName == 'relation') {
                if (substr($child->nodeValue, 0, 4) == 'VD16') {
                    $relationResource = $graph->resource('http://gateway-bayern.de/'.strtr($child->nodeValue, array(' ' => '+')));
                    $recordResource->addResource('dcterms:relation', $relationResource);
                }
                else {
                    $recordResource->addLiteral('dcterms:relation', $child->nodeValue);
                }
            }
            elseif ($child->nodeName == 'creator' or $child->nodeName == 'contributor') {
                $explode = explode('#', $child->nodeValue);
                if (!empty($explode[1])) {
                    $personResource = $graph->resource($explode[1], 'gndo:Person');
                    $personResource->addLiteral('gndo:preferredNameForThePerson', $explode[0]);
                    $recordResource->addResource('dcterms:'.$child->nodeName, $personResource);
                }
                else {
                    $recordResource->addLiteral('dcterms:'.$child->nodeName, $child->nodeValue);
                }
            }
        }
    }
    return($graph);
}

function getNames($gnds) {
	$names = array();
	foreach ($gnds as $gnd) {
		$request = new gnd_request($gnd);
		if ($request->preferredName) {
			$names[] = $request->preferredName;
		}
	}
	return array();
}

?>
