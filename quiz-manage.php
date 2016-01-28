<?php

header('Content-Type: text/html; charset=UTF-8');

require_once 'include/database.php';
require_once 'include/question.php';

include 'credentials.php';
$db = new Database( $__db_connstr__, $__db_username__, $__db_password__ );
$db->CreateQuestionTable();



$id = false;
if ( isset($_GET['id']) ) { $id = (int)$_GET['id']; }

if ( $id !== false && isset($_GET['setHaveSeen']) ) {
	$haveSeen = (int)$_GET['setHaveSeen'];
	if ( $haveSeen === 0 || $haveSeen === 1 ) {
		$db->SetHaveSeen( $id, $haveSeen );
	}
}
if ( $id !== false && isset($_GET['delete']) ) {
	if ( $_GET['delete'] === 'true' ) {
		$db->DeleteQuestion( $id );
	}
}
	

if ( $_SERVER["REQUEST_METHOD"] === "POST" ) {
	if ( isset($_POST['create_question']) && $_POST['create_question'] === 'true' ) {
		// TODO: this really needs more validity checks...
		$db->CreateQuestion( $_POST['name'], $_POST['game'], $_POST['question'], $_POST['answer'], $_POST['fake1'], $_POST['fake2'], $_POST['fake3'], $_POST['difficulty'] );
	}
}

$Questions = $db->GetQuestions();

echo '<html>';
echo '<body>';

echo '<div>';
?>
<form action="quiz-manage.php" method="post">
  Name: <input type="text" name="name" /><br />
  Game: <input type="text" name="game" /><br />
  Question: <input type="text" name="question" /><br />
  Correct Answer: <input type="text" name="answer" /><br />
  False Answer 1: <input type="text" name="fake1" /><br />
  False Answer 1: <input type="text" name="fake2" /><br />
  False Answer 1: <input type="text" name="fake3" /><br />
  Difficulty: 
	<input type="radio" name="difficulty" value="1" checked>1
	<input type="radio" name="difficulty" value="2">2
	<input type="radio" name="difficulty" value="3">3
	<input type="radio" name="difficulty" value="4">4
	<input type="radio" name="difficulty" value="5">5
  <br />
  <input type="hidden" name="create_question" value="true">
  <input type="submit" value="Create Question">
</form>
<?php
echo '</div>';


echo '<table>';
echo '<tr>';
echo '<th>ID</th>';
echo '<th>CreatedTimestamp</th>';
echo '<th>HaveSeen</th>';
echo '<th>SeenDate</th>';
echo '<th>Submitter</th>';
echo '<th>GameTitle</th>';
echo '<th>Question</th>';
echo '<th>CorrectAnswer</th>';
echo '<th>FakeAnswer1</th>';
echo '<th>FakeAnswer2</th>';
echo '<th>FakeAnswer3</th>';
echo '<th>Difficulty</th>';
echo '</tr>';
foreach ( $Questions as $q ) {
	echo '<tr>';
	echo '<td>'.$q->ID.'</td>';
	echo '<td>'.$q->CreatedTimestamp.'</td>';
	echo '<td>'.$q->HaveSeen.' <a href="?id='.$q->ID.'&setHaveSeen='.( $q->HaveSeen ? '0' : '1' ).'">change</a></td>';
	echo '<td>'.$q->SeenDate.'</td>';
	echo '<td>'.$q->Submitter.'</td>';
	echo '<td>'.$q->GameTitle.'</td>';
	echo '<td>'.$q->Question.'</td>';
	echo '<td>'.$q->CorrectAnswer.'</td>';
	echo '<td>'.$q->FakeAnswer1.'</td>';
	echo '<td>'.$q->FakeAnswer2.'</td>';
	echo '<td>'.$q->FakeAnswer3.'</td>';
	echo '<td>'.$q->Difficulty.'</td>';
	echo '<td><a href="?id='.$q->ID.'&delete=true">delete</a></td>';
	echo '</tr>';
}
echo '</table>';



echo '</body>';
echo '</html>';

$db->Commit();

?>