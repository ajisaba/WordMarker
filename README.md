WordMarker
====

## Description
This is a simple class for adding markers befor specified words and after them.
It does not depend on the order of specified words to add markers.

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
