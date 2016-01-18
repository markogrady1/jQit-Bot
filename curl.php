<?php 

/**
 *	This class deals with the HTTP requests for various 
 *   URLs concerning Issues and Pull Requests
 *
 **/
class Curl {

	public function  getCurlData($url, $isHistory = false) {
	
		$curl = curl_init($url);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($curl, CURLOPT_HTTPHEADER, array("User-Agent: php-curl"));

		$response = curl_exec($curl);

		$info = curl_getinfo($curl);

		if ($info['http_code'] == 200) {

			curl_close($curl);

			$this->prevCurl = $response;

			$data = json_decode($response, true);

			if (empty($data)) {

				return false;

			} else {
				if ($isHistory) {
					return sizeof($data);
				}

				return $response;
			}
			return false;

		} else {

			echo "Curl Error: " . curl_error($curl);
		}
	}

	public function getClosedCurl($url, $isPull) {
		$iss = array();
		$curl = curl_init($url);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($curl, CURLOPT_HTTPHEADER, array("User-Agent: php-curl"));

		$response = curl_exec($curl);

		$info = curl_getinfo($curl);

		if($info['http_code'] == 200) {

			curl_close($curl);

			$this->prevCurl = $response;

			$data = json_decode($response, true);

			if(empty($data)){

				return false;

			} else {
				$i = 0;
			
				foreach ($data as $key => $value) {
					$iss[$i]['url'] = $value['url'];
					$iss[$i]['id'] = $value['id'];
					$iss[$i]['title'] = $value['title'];
					$iss[$i]['body'] = $value['body'];
					$iss[$i]['created_at'] = $value['created_at'];
					$iss[$i]['updated_at'] = $value['updated_at'];
					$iss[$i]['closed_at'] = $value['closed_at'];
					if($isPull){
						$iss[$i]['label'] = $value['head']['label'];
					}
					$iss[$i++]['assignee'] = $value['assignee'];
					
				}
				return json_encode($iss);
				}
				return false;

			} else {

				echo "Curl Error: " . curl_error($curl);
		}
	}
	
	public function getIssueHistoryCurl($url, $page, $openPrArray) {
		$curl = curl_init($url);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($curl, CURLOPT_HTTPHEADER, array("User-Agent: php-curl"));


		$response = curl_exec($curl);
		$response = $response . "\n";
		$info = curl_getinfo($curl);

		if($info['http_code'] == 200) {

		curl_close($curl);

		$data = json_decode($response, true);
		$repoName = array();
		$p = 0;
		$filename = 'repo_history/repo_issue_history.txt';

		$repoName = array();
		$i = 0;
		$repoString = "";

		$date = new DateTime();
		$isoFormat =  $date->format('c = U');
		$isoFormat = split(' = ', $isoFormat);
		$isoFormat = substr($isoFormat[0], 0, 19);
		$isoFormat = $isoFormat . 'Z';

		if($page == 1) {
		$today = "*". $isoFormat . " = " .$date->format('U') .',' . PHP_EOL;
		$handle = fopen($filename, 'a+');
				fwrite($handle, $today);
		}
		foreach ($data as $key => $value) {

			$repoName[$i]['name'] = $value['name'];
			$repoName[$i]['issues'] = intval(['open_issues']) - intval($openPrArray[$p++]);
			$repoString = $repoName[$i]['name'] . "  " . $repoName[$i++]['issues'] . "," . PHP_EOL;
				$handle = fopen($filename, 'a+');
				fwrite($handle, $repoString);			
		}						
					fclose($handle);
		} else {

			echo "Curl Error: " . curl_error($curl);
		}
    	}
	
    	public function assignClient($url, $page, $state = '') {

		$url = str_replace("{/number}", "", $url);

		$url = $url . "?client_id=" . ID . "&client_secret=" . SEC . "" . $state . "" . $page . "";
		
		return $url;	
    	}

    	public function toJSONArray($data) {
    	$rawData1 = preg_replace("/^\[/", "", $data);
		$rawData1 = preg_replace("/\]$/", ",", $rawData1);

		return $rawData1;
    	}
}
