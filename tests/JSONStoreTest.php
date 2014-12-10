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


    /**
     * Test that you get the same collection by walking using an explicit path and an access token
     */ 
    public function testGet()
    {
	$this->markTestIncomplete("Need to figure out what's up with the filter.");
	$result1 = $this->Subject->find('$.store');
        $result2 = $this->Subject->store;


        $this->assertSame($result1, $result2);
    }

}

