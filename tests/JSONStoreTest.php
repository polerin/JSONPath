<?php
namespace Flow\JSONPath\Tests;

require_once __DIR__ . "/../vendor/autoload.php";

use Flow\JSONPath\JSONPath;
use Flow\JSONPath\JSONStore;

use Flow\JSONPath\JSONPathException;


class JSONStoreTest extends JSONPathTest
{

    public function setUp()
    {
         $this->Subject = new JSONStore($this->exampleData());
    }

    public function testGet()
    {
//	$result1 = $
    }
}

