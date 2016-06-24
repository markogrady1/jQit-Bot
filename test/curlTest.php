<?php namespace Test;
require __DIR__ . '/../vendor/autoload.php';
use src\Curl;

class CurlTest extends \PHPUnit_Framework_TestCase {
    private $curlVal;
    
    function setUp(){
        $this->curlVal = new Curl();
    }
    
    public function testToJSONArray() {
        $data = json_encode(array("issues", "pull requests", "forks"));
        $returnVal = $this->curlVal->toJSONArray($data);
        $this->assertEquals('"issues","pull requests","forks",', $returnVal);
    }
    
    public function testAssignClient() {
        $returnedURL = $this->curlVal->assignClient("http://someurl.com", 1, "open");
        $this->assertEquals("http://someurl.com?client_id=somevalue&client_secret=somevalueopen1", $returnedURL);
    }

    public function testFormatData() {
        $obj = (object) array('name' => 'jquery', "issues" => 21);
        $returnedURL = $this->curlVal->formatData(json_encode($obj));
        $str = '[{"name":"jquery","issues":21}]';
        $this->assertEquals($str, $returnedURL);
    }

    public function testGetCurrentDateTime() {
        $returnedURL = $this->curlVal->getCurrentDateTime();
        $expected = split(":", $returnedURL);
        $this->assertEquals(count($expected), 3);
    }
}