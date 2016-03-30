<?php  namespace src;

set_time_limit(0);

class History {

	private $closedPrArray = array();

	private $openPrArray = array();

	public function __construct() {
		$this->openPrArray = $this->getPullsHistory();
		$this->closedPrArray = $this->getClosedPullsHistory();
		$this->getIssueHistory($this->openPrArray);
		$this->getClosedIssueHistory($this->closedPrArray);
	}


	public function getClosedPullsHistory() {
	$stringVal = file_get_contents("../repo_collection/rep.json");

		$json_a = json_decode($stringVal, true);
		$issueUrlArr = array();
		$i = 0;
		foreach ($json_a as $key => $value) {
			$issueUrlArr[$i++] = $value['pulls_url'];
		}
		$date = new \DateTime();

				$isoFormat =  $date->format('c = U');
				$isoFormat = split(' = ', $isoFormat);
				$isoFormat = substr($isoFormat[0], 0, 19);
				$isoFormat = $isoFormat . 'Z';
				$today = "*". $isoFormat . " = " .$date->format('U') .',' . PHP_EOL;
		$filename = "../repo_history/closed_PR_history.txt";
		$handle = fopen($filename, 'a+');
        fwrite($handle, $today);


		$fileData = "";
		$dataString = array();
		$dataArray = array();
		$pull = new Curl();
		$endState = false;
		$file = 1 ;
		$n = array();
		$k = 0;
		foreach ($issueUrlArr as $isslArr) {
			$closedData = 0;
			$n = split('/', $issueUrlArr[$k]);
			
			for ($j= 1; $j < 400; $j++) {

				$url = $pull->assignClient($isslArr, "&page=" . $j, "&per_page=100&state=closed");

				$rawData = $pull->getCurlData($url, true);

				if (!$rawData) {

					break;

				}else{
					$closedData += $rawData;
				}
			}
            $closedPrArray[$k] = $closedData;
			$dataArray[$k++] = "{ 'name': " . $n[5] . ", closed_issues: " . $closedData . "}";
			$str = $n[5] . "  " . $closedData . "," . PHP_EOL;
			fwrite($handle, $str);
	}
    return $closedPrArray;
}

public function getClosedIssueHistory($closedPrArray) {
	$stringVal = file_get_contents("../repo_collection/rep.json");

	$json_a = json_decode($stringVal, true);
	$issueUrlArr = array();
	$i = 0;
	foreach ($json_a as $key => $value) {
		$issueUrlArr[$i++] = $value['issues_url'];
	}
	$date = new \DateTime();

			$isoFormat =  $date->format('c = U');
			$isoFormat = split(' = ', $isoFormat);
			$isoFormat = substr($isoFormat[0], 0, 19);
			$isoFormat = $isoFormat . 'Z';
			$today = "*". $isoFormat . " = " .$date->format('U') .',' . PHP_EOL;
	$filename = "../repo_history/closed_issue_history.txt";


	$handle = fopen($filename, 'a+');
	fwrite($handle, $today);

	$fileData = "";
	$dataString = array();
	$dataArray = array();
	$issue = new Curl();
	$endState = false;
	$file = 1 ;
	$n = array();
	$k = 0;
		foreach ($issueUrlArr as $isslArr) {
			$closedData = 0;
			$n = split('/', $issueUrlArr[$k]);
			
			for($j= 1; $j < 400; $j++){

				$url = $issue->assignClient($isslArr, "&page=" . $j, "&per_page=100&state=closed");

				$rawData = $issue->getCurlData($url, true);

				if (!$rawData) {
					break;

				} else {
					$closedData += $rawData;
				}
			}
            $closedData = ($closedData - $closedPrArray[$k]);
			$dataArray[$k++] = "{ 'name': " . $n[5] . ", closed_issues: " . $closedData . "}";
			$str= $n[5] . "  " . $closedData . "," . PHP_EOL;
			fwrite($handle, $str);
		}
}

public function getPullsHistory() {
	$stringVal = file_get_contents("../repo_collection/rep.json");

	$json_a = json_decode($stringVal, true);
	$issueUrlArr = array();
	$i = 0;
	foreach ($json_a as $key => $value) {
		$issueUrlArr[$i++] = $value['pulls_url'];
	}
	$date = new \DateTime();

			$isoFormat =  $date->format('c = U');
			$isoFormat = split(' = ', $isoFormat);
			$isoFormat = substr($isoFormat[0], 0, 19);
			$isoFormat = $isoFormat . 'Z';
			$today = "*". $isoFormat . " = " .$date->format('U') .',' . PHP_EOL;
	$filename = "../repo_history/repo_pulls_history.txt";

	$handle = fopen($filename, 'a+');
	fwrite($handle, $today);

	$fileData = "";
	$dataString = array();
	$dataArray = array();
	$pull = new Curl();
	$endState = false;
	$file = 1 ;
	$n = array();
	$k = 0;
	foreach ($issueUrlArr as $isslArr) {
		$closedData = 0;
		$n = split('/',$issueUrlArr[$k]);
		
		for ($j= 1; $j < 400; $j++) {

			$url = $pull->assignClient($isslArr, "&page=" . $j, "&per_page=100");

			$rawData = $pull->getCurlData($url, true);

			if(!$rawData){
				break;

			}else{
				$closedData += $rawData;
			}
		}
        $openPrArray[$k] = $closedData;
	$dataArray[$k++] = "{ 'name': " . $n[5] . ", issues: " . $closedData . "}";
	$str= $n[5] . "  " . $closedData . "," . PHP_EOL;
	fwrite($handle, $str);
	}
	return $openPrArray;
}

public function getIssueHistory($openPrArray) {
    $stringVal = file_get_contents("../repo_collection/rep.json");

    $json_a = json_decode($stringVal, true);
    $issueUrlArr = array();
    $i = 0;
    foreach ($json_a as $key => $value) {
        $issueUrlArr[$i++] = $value['issues_url'];
    }
    $date = new \DateTime();
    $isoFormat =  $date->format('c = U');
    $isoFormat = split(' = ', $isoFormat);
    $isoFormat = substr($isoFormat[0], 0, 19);
    $isoFormat = $isoFormat . 'Z';

    $today = "*". $isoFormat . " = " .$date->format('U') .',' . PHP_EOL;
    $filename = "../repo_history/repo_issue_history.txt";

    $handle = fopen($filename, 'a+');
    fwrite($handle, $today);

    $fileData = "";
    $dataString = array();
    $dataArray = array();
    $issue = new Curl();
    $endState = false;
    $file = 1 ;
    $n = array();
    $k = 0;
    foreach ($issueUrlArr as $isslArr) {
        $closedData = 0;
        $n = split('/', $issueUrlArr[$k]);

        for ($j= 1; $j < 400; $j++) {

            $url = $issue->assignClient($isslArr, "&page=" . $j, "&per_page=100&state=open");

            $rawData = $issue->getCurlData($url, true);

            if (!$rawData) {
                break;

            } else {
                $closedData += $rawData;
            }
        }
        $closedData = ($closedData - $openPrArray[$k]);
        $dataArray[$k++] = "{ 'name': " . $n[5] . ", closed_issues: " . $closedData . "}";
        $str= $n[5] . "  " . $closedData . "," . PHP_EOL;
        fwrite($handle, $str);
    }
}

}

