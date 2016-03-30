<?php namespace src;

require_once "../vendor/autoload.php";

class JqitBot {

	private $repoHistory;
	private $pullData;
	private $issueData;

	public function __construct() {
		$this->pullData = new PullRequests();
		$this->issueData = new Issues();
		$this->$repoHistory = new History();
	}
}

$jqitBot = new JqitBot();
