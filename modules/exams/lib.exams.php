<?
include_once("include/lib.all.php");

sc_access_method_add("exams", "add");
sc_access_method_add("exams", "delete");
sc_access_method_add("exams", "deleteothers");
sc_access_method_add("exams", "edit");
sc_access_method_add("exams", "editothers");


function adm_action_lib_exams_exam_edit() { eval(scg());
    sc_gotopage("$RFS_SITE_URL/modules/exams/exams.php?action=admin_edit");
}

function exams_get_total_questions($exam_id) {
	$exam=mfo1("select * from exams where id='$exam_id'");
	$nq=$exam->questions;
	return $nq;
}

function exams_get_total_questions_answered($user,$exam_id) {
	$r=sc_query("select * from exam_users where user='$user' and exam_id='$exam_id'");
	$nqca=mysql_num_rows($r);
	return $nqca;
}

function exams_get_prct($user,$exam_id) {
	$nq=exams_get_total_questions($exam_id);
	$nqc=exams_get_score($user,$exam_id);
	$prct=$nqc/$nq;
	$prct=round($prct*100);
	return $prct;

}
function exams_get_score($user,$exam_id) {		
	$r=sc_query("select * from exam_users where user='$user' and exam_id='$exam_id' and correct='1'");
	$nqc=mysql_num_rows($r);
	return $nqc;
}

function exams_wipe_user_exam($user,$exam_id) {
	sc_query("delete from exam_users where user='$user' and exam_id='$exam_id'");
}

?>
