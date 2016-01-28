<?php
require_once 'question.php';

class Database {
	var $Connection;
	
	function __construct( $Connectionstr, $username, $password ) {
		$this->Connection = new PDO( $Connectionstr, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'") );
		$this->Connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->Connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$this->Connection->beginTransaction();
	}
	
	function Commit() {
		$this->Connection->commit();
	}
	
	function RollBack() {
		$this->Connection->rollBack();
	}
	
	function FoundRows() {
		$s = 'SELECT FOUND_ROWS()';
		
		$stmt = $this->Connection->prepare( $s );
		$stmt->execute();
		
		return $stmt->fetchColumn();
	}
	
	function CreateQuestionTable() {
		$s = 'CREATE TABLE IF NOT EXISTS Questions ( ';
		$s .= 'id INT NOT NULL AUTO_INCREMENT, ';
		$s .= 'created_timestamp DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, ';
		$s .= 'have_seen TINYINT(1) NOT NULL DEFAULT \'0\', ';
		$s .= 'seen_date DATE DEFAULT NULL, ';
		$s .= 'submitter TEXT, ';
		$s .= 'game_title TEXT NOT NULL, ';
		$s .= 'question TEXT NOT NULL, ';
		$s .= 'correct_answer TEXT NOT NULL, ';
		$s .= 'fake_answer_1 TEXT NOT NULL, ';
		$s .= 'fake_answer_2 TEXT NOT NULL, ';
		$s .= 'fake_answer_3 TEXT NOT NULL, ';
		$s .= 'difficulty INT NOT NULL, ';
		$s .= 'PRIMARY KEY (id), ';
		$s .= 'KEY have_seen (have_seen) ';
		$s .= ') ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1';
		
		$stmt = $this->Connection->prepare( $s );
		$stmt->execute();
	}
	
	function GetQuestions() {
		$s = 'SELECT id, created_timestamp, have_seen, seen_date, submitter, game_title, question, correct_answer, fake_answer_1, fake_answer_2, fake_answer_3, difficulty ';
		$s .= 'FROM Questions ';
		
		$stmt = $this->Connection->prepare( $s );
		$stmt->execute();
		
		$questions = array();
		while( $r = $stmt->fetch() ) {
			$questions[] = new Question( $r['id'], $r['created_timestamp'], $r['have_seen'], $r['seen_date'], $r['submitter'], $r['game_title'], $r['question'], $r['correct_answer'], $r['fake_answer_1'], $r['fake_answer_2'], $r['fake_answer_3'], $r['difficulty'] );
		}
		return $questions;
	}
	
	function CreateQuestion( $name, $game, $question, $answer, $fake1, $fake2, $fake3, $difficulty ) {
		$args = array();
		$s = 'INSERT INTO Questions ( submitter, game_title, question, correct_answer, fake_answer_1, fake_answer_2, fake_answer_3, difficulty ) ';
		$s .= 'VALUES ( :submitter, :game_title, :question, :correct_answer, :fake_answer_1, :fake_answer_2, :fake_answer_3, :difficulty ) ';
		
		$args['submitter'] = $name;
		$args['game_title'] = $game;
		$args['question'] = $question;
		$args['correct_answer'] = $answer;
		$args['fake_answer_1'] = $fake1;
		$args['fake_answer_2'] = $fake2;
		$args['fake_answer_3'] = $fake3;
		$args['difficulty'] = $difficulty;
		
		$stmt = $this->Connection->prepare( $s );
		$stmt->execute( $args );
	}
	
	function DeleteQuestion( $id ) {
		$args = array();
		$s = 'DELETE FROM Questions ';
		$s .= 'WHERE id = :id ';
		
		$args['id'] = $id;
		
		$stmt = $this->Connection->prepare( $s );
		$stmt->execute( $args );
	}
	
	function SetHaveSeen( $id, $haveSeen ) {
		$args = array();
		$s = 'UPDATE Questions ';
		$s .= 'SET have_seen = :have_seen, seen_date = :seen_date ';
		$s .= 'WHERE id = :id ';
		
		$args['id'] = $id;
		$args['have_seen'] = $haveSeen;
		if ( $haveSeen ) {
			$args['seen_date'] = date( 'Y-m-d' );
		} else {
			$args['seen_date'] = null;
		}
		
		$stmt = $this->Connection->prepare( $s );
		$stmt->execute( $args );
	}
}
?>
