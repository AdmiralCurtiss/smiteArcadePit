<?php
class Question {
	var $ID;
	var $CreatedTimestamp;
	var $HaveSeen;
	var $SeenDate;
	var $Submitter;
	var $GameTitle;
	var $Question;
	var $CorrectAnswer;
	var $FakeAnswer1;
	var $FakeAnswer2;
	var $FakeAnswer3;
	var $Difficulty;
	
	function __construct( $ID, $CreatedTimestamp, $HaveSeen, $SeenDate, $Submitter, $GameTitle, $Question, $CorrectAnswer, $FakeAnswer1, $FakeAnswer2, $FakeAnswer3, $Difficulty ) {
		$this->ID = $ID;
		$this->CreatedTimestamp = $CreatedTimestamp;
		$this->HaveSeen = $HaveSeen;
		$this->SeenDate = $SeenDate;
		$this->Submitter = $Submitter;
		$this->GameTitle = $GameTitle;
		$this->Question = $Question;
		$this->CorrectAnswer = $CorrectAnswer;
		$this->FakeAnswer1 = $FakeAnswer1;
		$this->FakeAnswer2 = $FakeAnswer2;
		$this->FakeAnswer3 = $FakeAnswer3;
		$this->Difficulty = $Difficulty;
	}
}
?>
