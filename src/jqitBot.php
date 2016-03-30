<?php namespace src;

require_once "../vendor/autoload.php";

class JqitBot {

	private $repoHistory;
	private $pullData;
	private $issueData;

	public function __construct() {
		$this->pullData = new RepoPullData_init();
		$this->issueData = new RepoIssueData_init();
		$this->$repoHistory = new RepoHistory_init();
	}
}

$jqitBot = new JqitBot();
