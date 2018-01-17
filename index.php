<?php

// Chargement du source XML
$xml = new DOMDocument;
$xml->load('file.xml');

$xsl = new DOMDocument;
$xsl->load('file.xsl');

// Configuration du transformateur
$proc = new XSLTProcessor;
$proc->importStyleSheet($xsl); // attachement des règles xsl

header("Content-type: text/xml; charset-ISO-ISO-8859-1");

echo $proc->transformToXML($xml);