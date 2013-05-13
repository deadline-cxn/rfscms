<?
include_once("include/lib.all.php");

sc_access_method_add("exams", "create");
sc_access_method_add("exams", "add");
sc_access_method_add("exams", "delete");
sc_access_method_add("exams", "deleteothers");
sc_access_method_add("exams", "edit");
sc_access_method_add("exams", "editothers");
sc_access_method_add("exams", "viewresults");
sc_access_method_add("exam_questions", "edit");

$answer_data[1]="A";
$answer_data[2]="B";
$answer_data[3]="C";
$answer_data[4]="D";
$answer_data[5]="E";
$answer_data[6]="F";

function adm_action_lib_exams_exam_edit() { eval(scg());
    sc_gotopage("$RFS_SITE_URL/modules/exams/exams.php?action=admin_edit");
}

function exams_get_total_questions($exam_id) {
	$exam=mfo1("select * from exams where id='$exam_id'");
	$nq=$exam->questions;
	return $nq;
}

function exams_get_total_questions_answered($user,$exam_id) {
$r=sc_query("select * from `exam_users` where `user`='$user' 
												and `exam_id`='$exam_id' 
												and `completed` IS NOT NULL; ");
		$nqca=mysql_num_rows($r);
return $nqca;
}

function exams_get_completed_prct($user,$exam_id) {
	$nq=exams_get_total_questions_answered($user,$exam_id);
	$nqc=exams_get_score($user,$exam_id);
	if($nq>0) { 
		$prct=$nqc/$nq;
		$prct=round($prct*100);
	}
	else {
		$prct=0;
	}
	return $prct;
}

function exams_get_prct($user,$exam_id) {
	$nq=exams_get_total_questions($exam_id);
	$nqc=exams_get_score($user,$exam_id);
	if($nq>0) { 
		$prct=$nqc/$nq;
		$prct=round($prct*100);
	}
	else {
		$prct=0;
	}
	return $prct;
}
function exams_get_score($user,$exam_id) {		
	$r=sc_query("select * from exam_users where user='$user' and exam_id='$exam_id' and correct='1'");
	$nqc=mysql_num_rows($r);
	return $nqc;
}

function exams_wipe_user_exam($user,$exam_id) {
	sc_query("delete from exam_users where user='$user' and exam_id='$exam_id'");
	$exam=mfo1("select * from exams where id='$exam_id'");
	for($i=1;$i<($exam->questions+1);$i++) {
		$exq=mfo1("select * from exam_questions where `exam_id`='$exam_id' and `exam_sequence`='$i'");
		sc_query("insert into exam_users (`user`,`exam_id`,`question_id`)
										values('$user', '$exam_id', '$exq->id') ");
	}
	
}

?>
