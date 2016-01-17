<?php 
set_time_limit(0);
include 'config.php';
include 'curl.php';

$jQrepositories[0] = "https://api.github.com/users/jquery/repos";
getRepositories($jQrepositories);
getOpenPulls();
getClosedPulls();

function getClosedPulls() {
	$string = file_get_contents("repo_collection/rep.json");
		$json_a = json_decode($string, true);
		$pullUrlArr = array();
		$i = 0;
		foreach ($json_a as $key => $value) {
			$pullUrlArr[$i++] = $value['pulls_url'];
		}
		$fileData = "";

		$pull = new Curl();
		$endState = false;
		$file = 1 ;
		foreach ($pullUrlArr as $pullArr) {
			for($j= 1; $j < 400; $j++) {

			$url = $pull->assignClient($pullArr, "&page=" . $j, "&state=closed");

			$rawData = $pull->getClosedCurl($url, true);

				if (!$rawData) {

					if ($j == 1) {

					$handle = fopen("pulls/closed/" . $file . "_closed_pulls.json", 'w');

					} else {

					$handle = fopen("pulls/closed/" . $file . "_closed_pulls.json", 'a+');
					}
					break;

				} else {

					$rawData1 = $pull->toJSONArray($rawData);
					$fileData .= $rawData1;
					
					if ($j == 1) {

					$handle = fopen("pulls/closed/" . $file . "_closed_pulls.json", 'w');

					} else {

					$handle = fopen("pulls/closed/" . $file . "_closed_pulls.json", 'a+');
					}
				}
			}
			$fileData = "[" . $fileData . "]";
			$fileData = preg_replace("/}{/", "},{", $fileData);

			$fileData = preg_replace("/\,]$/", "]", $fileData);
			if ($fileData == "[") {
				$fileData = $fileData . "]";
			}
			fwrite($handle, $fileData);

			$fileData = null;

			$file++;
		}
}


function getOpenPulls() {
	$string = file_get_contents("repo_collection/rep.json");
	$json_a = json_decode($string, true);
	$pullsUrlArr = array();
	$i = 0;
	foreach ($json_a as $key => $value) {
		$pullsUrlArr[$i++] = $value['pulls_url'];
	}
	$fileData = "";

	$pull = new Curl();
	$endState = false;
	$file = 1 ;
	foreach ($pullsUrlArr as $pullArr) {
		for ($j= 1; $j < 100; $j++) {

		$url = $pull->assignClient($pullArr, "&page=" . $j);

		$rawData = $pull->getCurlData($url);

			if (!$rawData) {

				if ($j == 1) {

				$handle = fopen("pulls/open/" . $file . "_pulls.json", 'w');

				} else {

				$handle = fopen("pulls/open/" . $file . "_pulls.json", 'a+');
				}
				break;

			} else {

				$rawData1 = $pull->toJSONArray($rawData);
				$fileData .= $rawData1;
				
				if ($j == 1) {

				$handle = fopen("pulls/open/" . $file . "_pulls.json", 'w');

				} else {

				$handle = fopen("pulls/open/" . $file . "_pulls.json", 'a+');
				}
			}
		}
		$fileData = "[" . $fileData;
		$fileData = preg_replace("/\,$/", "]", $fileData);
		if ($fileData == "[") {
			$fileData = $fileData . "]";
		}
		fwrite($handle, $fileData);

		$fileData = null;

		$file++;
	}
}

function getRepositories(Array $jQrepositories) {
	$fileData = "";

	$history = new Curl();
	$endState = false;
	$file = "rep";
	foreach ($jQrepositories as $isslArr) {
		for($j= 1; $j < 4; $j++) {

		$url = $history->assignClient($isslArr, "&page=" . $j);

		$rawData = $history->getCurlData($url);

			if (!$rawData) {

				if ($j== 1) {

				$handle = fopen("repo_collection/" . $file . ".json", 'w');

				} else {

				$handle = fopen("repo_collection/" . $file . ".json", 'a+');
				}
				break;

			} else {

				$rawData1 = $history->toJSONArray($rawData);
			
				$fileData .= $rawData1;
				
				if ($j == 1) {

				$handle = fopen("repo_collection/" . $file . ".json", 'w');

				} else {

				$handle = fopen("repo_collection/" . $file . ".json", 'a+');
				}
			}
		}
		$fileData = "[" . $fileData;

		$fileData = preg_replace("/\,$/", "]", $fileData);

		if ($fileData == "[") {
			$fileData = $fileData . "]";
		}

		fwrite($handle, $fileData);

		$fileData = null;
		
	}
}
