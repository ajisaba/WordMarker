WordMarker
====

## Description
This is a class for adding markers befor specified words and after thme.
It does not depend on order of specified words to add markers.

## Usage

``` php

require_once('WordMarker.php');

$listWord = array(
    'security',
    'security hole',
);

$str = '... hole security hole ...';

$marker = new WordMarker();
$marker->setMarker('<b>', '</b>');
$result = $marker->addMarker($str, $listWord);

print $result;   // '... hole <b>security hole</b> ...'

```
