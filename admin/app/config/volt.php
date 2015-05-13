<?php

$compiler = $volt->getCompiler();



$compiler->addFunction('strtotime', 'strtotime');
$compiler->addFunction('str_replace', 'str_replace');
$compiler->addFunction('substr', 'substr');

$compiler->addFunction('decodePostParam', function($resolvedArgs, $exprArgs) {
     return 'ApiPost::decodePostParam(' . $resolvedArgs . ')';
});