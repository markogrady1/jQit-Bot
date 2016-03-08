<?php namespace Test;
require __DIR__ . '/../vendor/autoload.php';
use src\Curl;

class CurlTest extends \PHPUnit_Framework_TestCase {
    private $curlVal;
    
    function setUp(){
        $this->curlVal = new Curl();
    }
    
    public function testToJSONArray() {
        $data = json_encode(array("one", "two", "three"));
        $returnVal = $this->curlVal->toJSONArray($data);
        $this->assertEquals('"one","two","three",', $returnVal);
    }
    
    public function testAssignClient() {
        $returnedURL = $this->curlVal->assignClient("http://someurl.com", 1, "open");
        $this->assertEquals("http://someurl.com?client_id=ID&client_secret=SECopen1", $returnedURL);
    }
}