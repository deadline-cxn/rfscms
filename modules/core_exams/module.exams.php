<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.rfscms.org/
/////////////////////////////////////////////////////////////////////////////////////////
// EXAMS CORE MODULE
/////////////////////////////////////////////////////////////////////////////////////////
include_once("include/lib.all.php");

$RFS_ADDON_NAME="exams";
$RFS_ADDON_VERSION="1.0.0";
$RFS_ADDON_SUB_VERSION="0";
$RFS_ADDON_RELEASE="";
$RFS_ADDON_DESCRIPTION="Exams";
$RFS_ADDON_REQUIREMENTS="";
$RFS_ADDON_COST="";
$RFS_ADDON_LICENSE="";
$RFS_ADDON_DEPENDENCIES="";
$RFS_ADDON_AUTHOR="Seth T. Parson";
$RFS_ADDON_AUTHOR_EMAIL="seth.parson@rfscms.org";
$RFS_ADDON_AUTHOR_WEBSITE="http://rfscms.org/";
$RFS_ADDON_IMAGES="";
$RFS_ADDON_FILE_URL="";
$RFS_ADDON_GIT_REPOSITORY="";
$RFS_ADDON_URL=lib_modules_get_base_url_from_file(__FILE__);

lib_menus_register("Exams","$RFS_SITE_URL/modules/core_exams/exams.php");
lib_access_add_method("exams", "create");
lib_access_add_method("exams", "add");
lib_access_add_method("exams", "delete");
lib_access_add_method("exams", "deleteothers");
lib_access_add_method("exams", "edit");
lib_access_add_method("exams", "editothers");
lib_access_add_method("exams", "viewresults");
lib_access_add_method("exam_questions", "edit");

$answer_data[1]="A";
$answer_data[2]="B";
$answer_data[3]="C";
$answer_data[4]="D";
$answer_data[5]="E";
$answer_data[6]="F";

function adm_action_lib_exams_exam_edit() { eval(lib_rfs_get_globals());
    lib_domain_gotopage("$RFS_SITE_URL/modules/core_exams/exams.php?action=admin_edit");
}

function exams_convert_question_id_to_sequence($question_id) {
	$x=lib_mysql_fetch_one_object("select * from `exam_questions` where `id`='$question_id'");
	$out = array(
		"exam_id" => $x->exam_id,
		"exam_sequence" => $x->exam_sequence );	
	return $out;
	
}

function exams_get_last_question_answered($user,$exam_id) {
	$r=lib_mysql_query("select * from `exam_users` where `user`='$user' 
												and `exam_id`='$exam_id' 
												and `completed` IS NULL; ");
	$x=mysql_fetch_object($r);
	// echo " $x->question_id <br>";
	return $x->question_id;
	
}

function exams_get_total_questions($exam_id) {
	$exam=lib_mysql_fetch_one_object("select * from exams where id='$exam_id'");
	$nq=$exam->questions;
	return $nq;
}

function exams_get_total_questions_answered($user,$exam_id) {
	$r=lib_mysql_query("select * from `exam_users` where `user`='$user' 
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
	$r=lib_mysql_query("select * from exam_users where user='$user' and exam_id='$exam_id' and correct='1'");
	$nqc=mysql_num_rows($r);
	return $nqc;
}

function exams_wipe_user_exam($user,$exam_id) {
	lib_mysql_query("delete from exam_users where user='$user' and exam_id='$exam_id'");
	$exam=lib_mysql_fetch_one_object("select * from exams where id='$exam_id'");
	for($i=1;$i<($exam->questions+1);$i++)  {
		$exq=lib_mysql_fetch_one_object("select * from exam_questions where `exam_id`='$exam_id' and `exam_sequence`='$i'");
		$q= "insert into exam_users (`user`,`exam_id`,`question_id`) values('$user', '$exam_id', '$exq->id') ";
		// echo $q."<br>";
		lib_mysql_query($q);
	}
}

?>
