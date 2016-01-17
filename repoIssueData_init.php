<?php 

set_time_limit(0);
include 'config.php';
include 'curl.php';

getOpenIssues();
getClosedIssues();


function getOpenIssues() {
	$string = file_get_contents("repo_collection/rep.json");
	$json_a = json_decode($string, true);
	$issueUrlArr = array();
	$i = 0;
	foreach ($json_a as $key => $value) {
		$issueUrlArr[$i++] = $value['issues_url'];
	}
	$fileData = "";

	$issue = new Curl();
	$endState = false;
	$file = 1 ;
	foreach ($issueUrlArr as $isslArr) {
		for($j= 1;$j< 100; $j++){

		$url = $issue->assignClient($isslArr, "&page=" . $j);

		$rawData = $issue->getCurlData($url);

			if(!$rawData){

				if($j== 1){

				$handle = fopen("issues/open/" . $file . "_issues.json", 'w');

				}else{

				$handle = fopen("issues/open/" . $file . "_issues.json", 'a+');
				}
				break;

			}else{

				$rawData1 = $issue->toJSONArray($rawData);
				$fileData .= $rawData1;
				
				if($j == 1){

				$handle = fopen("issues/open/" . $file . "_issues.json", 'w');

				}else{

				$handle = fopen("issues/open/" . $file . "_issues.json", 'a+');
				}
			}
		}

		// if($fileData != ""){
			$fileData = "[" . $fileData;
		// }

		$fileData = preg_replace("/\,$/", "]", $fileData);
		if($fileData == "["){
			$fileData = $fileData . "]";
		}
		fwrite($handle, $fileData);

		$fileData = null;

		$file++;
	}
}



function getClosedIssues() {
	$string = file_get_contents("repo_collection/rep.json");
	$json_a = json_decode($string, true);
	$pullUrlArray = array();
	$i = 0;
	foreach ($json_a as $key => $value) {
		$pullUrlArray[$i++] = $value['issues_url'];
	}
	$fileData = "";

	$pull = new Curl();
	$endState = false;
	$file = 1 ;
	foreach ($pullUrlArray as $pullArr) {
		for($j= 1;$j< 400; $j++){

		$url = $pull->assignClient($pullArr, "&page=" . $j, "&state=closed");

		$rawData = $pull->getClosedCurl($url, false);

			if(!$rawData){

				if($j== 1){

				$handle = fopen("issues/closed/" . $file . "_closed_issues.json", 'w');

				}else{

				$handle = fopen("issues/closed/" . $file . "_closed_issues.json", 'a+');
				}
				break;

			}else{

				$rawData1 = $pull->toJSONArray($rawData);
			
				$fileData .= $rawData1;
				
				if($j == 1){

				$handle = fopen("issues/closed/" . $file . "_closed_issues.json", 'w');

				}else{

				$handle = fopen("issues/closed/" . $file . "_closed_issues.json", 'a+');
				}
			}
		}
		$fileData = "[" . $fileData . "]";
		$fileData = preg_replace("/}{/", "},{", $fileData);
		$fileData = preg_replace("/\,]$/", "]", $fileData);
		if($fileData == "["){
			$fileData = $fileData . "]";
		}
		fwrite($handle, $fileData);

		$fileData = null;

		$file++;
	}
}
