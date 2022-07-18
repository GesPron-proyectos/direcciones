<?php


require_once 'classes/CreateDocx.inc';

$docx = new CreateDocx();
$docx->addTemplate('documentos/001.docx');
$docx->addTemplateVariable('NAME', 'John Smith');
$docx->createDocx('unoo');
?>