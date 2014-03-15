<?
chdir("../../");
include("include/lib.mysql.php");

if(empty($action)) $action="list_exams";

if(empty($_REQUEST['exam_id'])) $title="Exams";
else {	$tex=lib_mysql_fetch_one_object("select * from exams where id='".$_REQUEST['exam_id']."'");
		$title="Exam ($tex->name)";
}
include("header.php");

echo "<h1>Exams</h1>";

if(!lib_rfs_bool_true($_SESSION['logged_in'])) {
	echo "You're not logged in. <br> "; 
	for($i=0;$i<35;$i++) echo "<br>";
	include("footer.php");
	exit;
}

	$qc=$_SESSION["question_id"];
	if($qc) {

        $r=lib_mysql_query("select * from exam_questions where id='$qc'");
        $q=mysql_fetch_assoc($r);
				
		$correct=false;
        
		d_echo("PREVIOUS QUESTION RESULTS: ($exam_id.$qc) ");
		d_echo("type: ".$q['type']." "."correct answer: ".$q['correct_answer']);

       $qx=explode(",",$q["correct_answer"]);
		
		for($i=0;$i<count($qx);$i++) d_echo($qx[$i]." -> ".$q["choice_".$qx[$i]]);

		if ($q["type"]=="multi_select")  {

			if($a=="A") $correct="$a";
			if($b=="B") $correct.=",$b";
			if($c=="C") $correct.=",$c";
			if($d=="D") $correct.=",$d";
			if($e=="E") $correct.=",$e";
			if($f=="F") $correct.=",$f";

			$correct=ltrim($correct,",");
			$answer=$correct;

			// echo " $correct <BR>";
			//echo $q['correct_answer']." <br>";
			
			if($correct==$q['correct_answer']) {
				$correct=true;
				//echo "CORRECT!<BR>";
			} else {
				$correct=false;
				//echo "INCORRECT!<BR>";
			}
		}

		if ( 	($q["type"]=="fib_dropdown") ||
				($q["type"]=="fill_in_blank") ) {

					if(!empty($qx[0])) {
						//echo $qx[0]." --- ".$a." --- ";
						if($qx[0]==$a) {
							// echo "CORRECT";
							$correct=true;
							}
						}
						
					if(!empty($qx[1])) {
						 //echo $qx[1]." --- ".$b." --- ";
						if($qx[1]==$b) { 
							// echo "CORRECT";
							 $correct=true; 
							}
							else $correct=false;
						}
						
					if(!empty($qx[2])) {
						//echo $qx[2]." --- ".$c." --- ";
						if($qx[2]==$c) { 
							//echo "CORRECT";
							$correct=true; 
							}
							else $correct=false;
						}
						
					if(!empty($qx[3])) {
						//echo $qx[3]." --- ".$d." --- ";
						if($qx[3]==$d) { 
							//echo "CORRECT";
							$correct=true; 
							}
							else $correct=false;
						}
						
					if(!empty($qx[4])) {
						//echo $qx[4]." --- ".$e." --- ";
						if($qx[4]==$e) { 
							//echo "CORRECT";
							$correct=true; 
							}
							else $correct=false;
						}
						
					if(!empty($qx[5])) {
						//echo $qx[5]." --- ".$f." --- ";
						if($qx[5]==$f) { 
							//echo "CORRECT";
							$correct=true; 
							}
							else $correct=false;
						}
				
        }
		
		
		if($q["type"]=="multiple_choice") {
			
			//echo " --- $group1 --- ";
			//echo $q['correct_answer'];
			/// echo " --- ";
			$answer=$group1;
			
			if($group1==$q["correct_answer"])  {
				//echo "CORRECT";
				$correct=true;
			}				
		}  // c3745-is-mz.122-13.T9.bin
		
		if($q["type"]=="true_false") {
			
			//echo " --- $group1 --- ";
			//echo $q['correct_answer'];
			//echo " --- ";
			
			$answer=$group1;		
			
			if(strtolower($group1)==$q["correct_answer"]) {
				
			 //echo "CORRECT";
				$correct=true;	
			}
		}
		
        d_echo("group1: $group1 <BR>
        a: $a <BR>
        b: $b <BR>
        c: $c <BR>
        d: $d <BR>
        e: $e <BR>
        f: $f");
		
		
		d_echo (" CORRECT: $correct ");		

			$r=lib_mysql_query("select * from exam_users where `question_id`='$qc' and
															  `user`='$data->name' and
															  `exam_id`='$exam_id' ");
															  
			$eud=@mysql_fetch_object($r);
															
			if($eud->id) { 
				
				
					lib_mysql_query("update exam_users set `correct`='$correct'  where `question_id`='$qc' and `user`='$data->name' and `exam_id`='$exam_id' ");
					lib_mysql_query("update exam_users set `completed`='$answer'  where `question_id`='$qc' and `user`='$data->name' and `exam_id`='$exam_id' ");
					
					
			}
			else {
					if(!empty($exam_id)) {
						$q= "insert into exam_users   (`user`, `exam_id`, `question_id`, `correct`) values ('$data->name', '$exam_id', '$qc', '$correct') ";
						echo $q."<br>";
						lib_mysql_query($q);
						
					}
													
			}

		//echo "<hr>";
		$_SESSION["question_id"]="";
	}
    
//}


if($action=="reset_exam"){
    $qid=0;
    $_SESSION["exam_$exam_id"]="0";
}

if($action=="admin_exam_test") {
    $_SESSION["exam_$exam_id"]="1";
    $action="run_exam";
}

if($action=="list_exams") {

    if( lib_access_check("exams","edit") ) { 
            echo "[<a href='$RFS_SITE_URL/modules/exams/exams.php?action=admin_edit'>Exam Administration</a>]";
    }	
	
	
	$n=0;
    $r=lib_mysql_query("select * from exams");
    if($r) $n=mysql_num_rows($r);
	
	echo "<table border=0 cellspacing=0 cellpadding=2>";	
	echo "<tr> <td></td> <td> Name </td>  <td>Completed</td> <td>Your Score</td>  <td>Passing Score</td> </tr>";
    for($i=0;$i<$n;$i++) {
			$gt++; if($gt>1) $gt=0;
			$exam=mysql_fetch_object($r);
			echo "<tr>";
			
			echo "<td class=sc_project_table_$gt>";			
			echo "<a href='$RFS_SITE_URL/modules/exams/exams.php?action=wipe_exam&exam_id=$exam->id'>
			<img src='$RFS_SITE_URL/images/icons/Play.png' width=16 border=0></a>
			</td>";
			
			echo "<td class=sc_project_table_$gt>";
			
			echo "<a href='$RFS_SITE_URL/modules/exams/exams.php?action=wipe_exam&exam_id=$exam->id'>$exam->name</a>";
			
			echo "</td>";
			
			echo "<td class=sc_project_table_$gt>";
			echo exams_get_total_questions_answered($data->name,$exam->id);
			
			echo " ( ".exams_get_completed_prct($data->name,$exam->id)."% )";
			
			echo "</td>";
			
			
			echo "<td class=sc_project_table_$gt>";
			$eprct=exams_get_prct($data->name,$exam->id);
			$escor=exams_get_score($data->name,$exam->id);
			$etotq=exams_get_total_questions($exam->id);
			
			$oclr="RED";
			if($eprct>=$exam->pass_percent) $oclr="GREEN";
			
			lib_forms_info("$escor/$etotq : $eprct%", "WHITE", $oclr);
			
			echo "</td>";
			

			echo "<td class=sc_project_table_$gt>";
			echo $exam->pass_percent."%";			
			echo "</td>";
			
			
			echo "</tr>";			
    }
	echo "</table>";
    if(!$n) echo "There are no exams... yet.<br>";

}

if($action=="wipe_exam") {
		
	$exam   = lib_mysql_fetch_one_object("select * from exams where id='$exam_id'");
	$eprct  = exams_get_prct($data->name,$exam_id);
	$escor  = exams_get_score($data->name,$exam_id);
	$etotq  = exams_get_total_questions($exam_id);
	$etotqa = exams_get_total_questions_answered($data->name,$exam_id);
	$oclr="RED";
	if($eprct>=$exam->pass_percent)
		$oclr="GREEN";
	if(!$confirmed)
	if($oclr=="GREEN") {
		if( $etotq == $etotqa ) {
			if($_SESSION["question_id"]<1) {
				lib_forms_info("$escor/$etotq : $eprct%", "WHITE", $oclr);
				lib_forms_info($exam->pass_percent."% minimum passing score required.", "WHITE", "BLUE");
				lib_forms_warn("You have already taken this exam and passed. If you wish, you may retake the exam, but your current score will be erased and you will have to take the exam in it's entirety with a passing score to recieve credit. If you are sure then
				<a href='$RFS_SITE_URL/modules/exams/exams.php?action=wipe_exam&exam_id=$exam_id&confirmed=true'><img src=$RFS_SITE_URL/images/icons/Play.png border=0>click here</a>. Note: This action can not be reversed.");
				for($i=0;$i<35;$i++) echo "<br>";
				include("footer.php");
				exit();		
				
			}
		}
	}
	
	$x=exams_get_last_question_answered($data->name,$exam_id);
	if($x) {
		$a = exams_convert_question_id_to_sequence($x);
		$texam_sequence	= $a["exam_sequence"];
		if(($texam_sequence) > 1) { 
			$_SESSION["exam_$exam_id"]=$texam_sequence;
			$question=lib_mysql_fetch_one_object("select * from exam_questions where exam_sequence='$texam_sequence' and exam_id='$exam_id'");	
			
			lib_forms_question(" 
			<h1> $exam->name CBT
			
			<hr>
			You have already completed a portion of this test.<br>
			Would you like to resume this test, or restart it?<br>
						
			<hr>
			
			<center>
			".
			lib_buttons_text("$RFS_SITE_URL/modules/exams/exams.php?exam_id=$exam->id&action=really_wipe_exam","RESTART")
			.
			lib_buttons_text("$RFS_SITE_URL/modules/exams/exams.php?exam_id=$exam->id&action=run_exam","RESUME")."
			</center>
			<hr>
			<br>
			
			
			");
			
			
			// $action="run_exam";
		}
		else {
			$action="really_wipe_exam";
		}
	}
	else {
			$action="really_wipe_exam";
	}
}

if($action=="really_wipe_exam") {
	
	exams_wipe_user_exam($data->name,$exam_id);
	$_SESSION["question_id"]=0;
	$_SESSION["exam_$exam_id"]=1;
	$action="run_exam";
	
}

if($action=="run_exam") {
	
	$exam   = lib_mysql_fetch_one_object("select * from exams where id='$exam_id'");
	$eprct  = exams_get_prct($data->name,$exam_id);
	$escor  = exams_get_score($data->name,$exam_id);
	$etotq  = exams_get_total_questions($exam_id);
	$etotqa = exams_get_total_questions_answered($data->name,$exam_id);
	
	$oclr="RED";
	if($eprct>=$exam->pass_percent) $oclr="GREEN";
	
    d_echo("Exam $exam_id");
    $exam=lib_mysql_fetch_one_object("select * from exams where id='$exam_id'");
    d_echo("$exam->name");

    if($qsid!=$_SESSION["exam_$exam_id"]) {
		if(!empty($qsid)) 
		$_SESSION["exam_$exam_id"]=$qsid;		
	} 

    $r=lib_mysql_query("select * from exam_questions where exam_id='$exam_id'");
    $nq=mysql_num_rows($r);

    d_echo("$nq");

    $qsid=$_SESSION["exam_$exam_id"];
	
	// echo "exam_$exam_id <br> \$qsid: $qsid <br>";

    if($qsid<2) $qsid=1;
        $_SESSION["exam_$exam_id"]=1;

    d_echo("$qsid");
    $question=lib_mysql_fetch_one_object("select * from exam_questions where exam_sequence='$qsid' and exam_id='$exam_id'");
    d_echo($question->type);
	
	$_SESSION["question_id"]=$question->id;
	
	// echo "!!!! \$question->type = $question->type<br>";
	// echo "!!!! \$question->id = $question->id<br>";
	
	
	if(empty($question->type)) {
		
		
		$qq=" <BR><h1>END OF EXAM!</h1><HR>";
		
		/*
		$r=lib_mysql_query("select * from exam_users where user='$data->name' and exam_id='$exam_id'");		
		$nq=mysql_num_rows($r);
		
		$r=lib_mysql_query("select * from exam_users where user='$data->name' and exam_id='$exam_id' and correct='1'");
		$nqc=mysql_num_rows($r);
		
		$prct=$nqc/$nq;
		
		$prct=round($prct*100);
		*/
		$nqc=exams_get_score($data->name,$exam_id);
		$prct=exams_get_prct($data->name,$exam_id);

		$qq.="<h1>";
		$qq.="You answered $nqc questions correctly out of $nq <br>";		
		$qq.="Percentage answered correctly $prct %<br>";		
		
		$exam=lib_mysql_fetch_one_object("select * from exams where id='$exam_id'");
		
		$qq.="Minimum passing score for this exam: $exam->pass_percent <br> <hr>";		
		if($prct>$exam->pass_percent) {
			$qq.="<BR>Congratulations, you passed.<br><BR><hr>";
			$qq.="Tasks covered by this exam:<br>";
			$r=lib_mysql_query("select distinct task from exam_questions where exam_id='$exam_id'");
			for($i=0;$i<mysql_num_rows($r);$i++) {
				$eq=mysql_fetch_object($r);
				$qq.= " $eq->task <br>";
			}
			
		} else {
			$qq.="You did not pass, please review the missed questions.<br><br>";
			
			
		}		
		$qq.="<hr></h1>";

		$qq.="[<a href=\"$RFS_SITE_URL/modules/exams/exams.php?action=wipe_exam&exam_id=$exam_id\">Take this exam again</a>]";
		$qq.="[Review missed questions]";
		$qq.="[Review all questions]";
		
		if( lib_access_check("exam_questions","edit") ) {
				$qq.="[<a href='$RFS_SITE_URL/modules/exams/exams.php?action=admin_edit'>Exam Administration</a>]";
		}
		
		$qq.="<BR><BR> <BR><BR>";
		
		lib_forms_question($qq);
			
		
		
		
	}
	else {

    $qq= "<br><h1> EXAM: $exam->name question ($qsid / $nq) </h1> ";

    $qq.="<hr>";

    $qsid++;

    $qq.="<form action=\"$RFS_SITE_URL/modules/exams/exams.php\" method=\"post\">\n";
    $ql= " <hr><center>
    <input type=hidden name=exam_id value=$exam->id>
    <input type=hidden name=qsid value=$qsid>
    <input type=hidden name=action value=run_exam>
    <input type=submit name=submit value=Continue>
    </form>\n";

    if(!empty($question->intro))   $qq.= "$question->intro <hr>";

    if($question->type=="multiple_choice") {

        lib_div("MULTIPLE CHOICE TEST QUESTION");

        $qq.="$question->question <BR>\n";
        $qq.="<center><img src='$question->question_image' border=0 align=center width=64></center><hr>\n";



        $qq.= "<input type=\"radio\" name=\"group1\" value=\"A\"> A) $question->choice_1 <br>\n";
        $qq.= "<input type=\"radio\" name=\"group1\" value=\"B\"> B) $question->choice_2 <br>\n";

        if(!empty($question->choice_3))
            $qq.= "<input type=\"radio\" name=\"group1\" value=\"C\"> C) $question->choice_3 <br>\n";
        if(!empty($question->choice_4))
            $qq.= "<input type=\"radio\" name=\"group1\" value=\"D\"> D) $question->choice_4 <br>\n";
        if(!empty($question->choice_5))
            $qq.= "<input type=\"radio\" name=\"group1\" value=\"A\"> E) $question->choice_5 <br>\n";
        if(!empty($question->choice_6))
            $qq.= "<input type=\"radio\" name=\"group1\" value=\"A\"> F) $question->choice_6 <br>\n";

       $qq.=$ql;
    }

    if($question->type=="multi_select") {		
        lib_div("MULTI SELECT TEST QUESTION");
				
		$qq.="$question->question<br><br>";

		$qq.="<input type=checkbox name=a value=\"A\"> $question->choice_1 <br>\n";
		$qq.="<input type=checkbox name=b value=\"B\"> $question->choice_2 <br>\n";
		if(!empty($question->choice_3)) 
			$qq.="<input type=checkbox name=c value=\"C\"> $question->choice_3 <br>\n";
		if(!empty($question->choice_4)) 
			$qq.="<input type=checkbox name=d value=\"D\"> $question->choice_4 <br>\n";
		if(!empty($question->choice_5)) 
			$qq.="<input type=checkbox name=e value=\"E\"> $question->choice_5 <br>\n";
		if(!empty($question->choice_6)) 
			$qq.="<input type=checkbox name=f value=\"F\"> $question->choice_6 <br>\n";
			
		$qq.=$ql;

    }

    if($question->type=="true_false") {


        lib_div("TRUE OR FALSE TEST QUESTION");

        $qq.="$question->question<br><br>";

        $qq.= "<input type=\"radio\" name=\"group1\" value=\"TRUE\"> TRUE <br>\n";
        $qq.= "<input type=\"radio\" name=\"group1\" value=\"FALSE\"> FALSE <br>\n";

        //        $qq.="TRUE or FALSE <br>";
        $qq.=$ql;
    }

    if($question->type=="fill_in_blank") {

        lib_div("FILL IN THE BLANK TEST QUESTION");

        $oq=str_replace("-a-","<input type=text name=a>", $question->question);
        $oq=str_replace("-b-","<input type=text name=b>", $oq);
        $oq=str_replace("-c-","<input type=text name=c>", $oq);
        $oq=str_replace("-d-","<input type=text name=d>", $oq);
        $oq=str_replace("-e-","<input type=text name=e>", $oq);
        $oq=str_replace("-f-","<input type=text name=f>", $oq);


		$qq.=$oq.$ql;
    }

				           
    if($question->type=="fib_dropdown") {

            lib_div("FILL IN THE BLANK DROP DOWN TEST QUESTION");

            $opts = "<option name=none>Fill in the blank\n";
            $opts.= "<option>$question->choice_1\n";
            $opts.= "<option>$question->choice_2\n";
            if(!empty($question->choice_3))
            $opts.= "<option>$question->choice_3\n";
            if(!empty($question->choice_4))
            $opts.= "<option>$question->choice_4\n";
            if(!empty($question->choice_5))
            $opts.= "<option>$question->choice_5\n";
            if(!empty($question->choice_6))
            $opts.= "<option>$question->choice_6\n";


            $oq=str_replace("-a-","<select name=a>".$opts."</select>", $question->question);
            $oq=str_replace("-b-","<select name=b>".$opts."</select>", $oq);
            $oq=str_replace("-c-","<select name=c>".$opts."</select>", $oq);
            $oq=str_replace("-d-","<select name=d>".$opts."</select>", $oq);
            $oq=str_replace("-e-","<select name=e>".$opts."</select>", $oq);
            $oq=str_replace("-f-","<select name=f>".$opts."</select>", $oq);

            $qq.=$oq.$ql;
    }


    $qq.="<BR><BR><BR>";
	
    lib_forms_question($qq);
	
    lib_div("END OF TEST QUESTION");
	
	}
}


if(lib_access_check("exams","edit") ) {
	
	if($action=="admin_exam_edit_questions") {
		if(!is_numeric($questions)) $questions=100;
		
		$r=lib_mysql_query("select * from exam_questions where exam_id='$exam_id' order by exam_sequence");
		$n=mysql_num_rows($r);
		if($questions>$n) $questions=$n;
		
		lib_mysql_query("update exams set `questions` = '$questions' where id='$exam_id'");
		$action="admin_exam_edit";
	}
	
	if($action=="admin_exam_edit_method") {
		lib_mysql_query("update exams set `method`='$method' where id='$exam_id'");
		$action="admin_exam_edit";
	}
	
	if($action=="admin_exam_edit_pass_percent") {
		if(!is_numeric($pass_percent)) $pass_percent=70;
		// echo " $exam_id  $pass_percent <br>";
		lib_mysql_query("update exams set `pass_percent`='$pass_percent' where id='$exam_id'");
		$action="admin_exam_edit";		
	}
		
	if($action=="admin_exam_question_edit") {
				
		echo "[<a href=$RFS_SITE_URL/modules/exams/exams.php?action=run_exam&exam_id=$exam_id>Run this exam</a>]";
		echo "[<a href=$RFS_SITE_URL/modules/exams/exams.php?action=admin_edit>List all exams</a>]";
		echo "[<a href=$RFS_SITE_URL/modules/exams/exams.php?action=admin_exam_edit&exam_id=$exam_id>List all questions</a>]<br>";
				
		$qt=lib_mysql_fetch_one_object("select * from exam_questions where id='$q'");
			
		lib_forms_build( "$RFS_SITE_URL/modules/exams/exams.php",
			   "action=admin_exam_question_edit_2".$RFS_SITE_DELIMITER.
			   "SHOW_SELECTOR_exam_question_types#name#type#$qt->type".$RFS_SITE_DELIMITER.	
			   "SHOW_SELECTOR_cfetp_tasks#task&name#task#$qt->task",
			   "exam_questions",
			   "select * from exam_questions where id='$q'",
			   "id",
			   "type".$RFS_SITE_DELIMITER."task",
			   "omit",
			   "",
			   100,
			   "submit" );		
	}
	
	if($action=="admin_exam_question_edit_2") {
	
		$r=lib_mysql_query("select * from exam_question_types where name='$type'");
		$eqt=mysql_fetch_object($r);
		
		if(empty($eqt->type)){
			$r=lib_mysql_query("select * from exam_question_types where type='$type'");
			$eqt=mysql_fetch_object($r);
		}
		if(empty($eqt->type)) $eqt->type="multiple_choice";


		lib_mysql_query("update exam_questions set `exam_id`='$exam_id' where id='$id'");
		lib_mysql_query("update exam_questions set `exam_sequence` ='$exam_sequence' where id='$id'");
		lib_mysql_query("update exam_questions set `type`='$eqt->type' where id='$id'");
		lib_mysql_query("update exam_questions set `intro`='$intro' where id='$id'");
		lib_mysql_query("update exam_questions set `question`='$question' where id='$id'");
		lib_mysql_query("update exam_questions set `question_image`='$question_image' where id='$id'");
		lib_mysql_query("update exam_questions set `correct_answer`='$correct_answer' where id='$id'");
		lib_mysql_query("update exam_questions set `choice_1`='$choice_1' where id='$id'");
		lib_mysql_query("update exam_questions set `choice_2`='$choice_2' where id='$id'");
		lib_mysql_query("update exam_questions set `choice_3`='$choice_3' where id='$id'");
		lib_mysql_query("update exam_questions set `choice_4`='$choice_4' where id='$id'");
		lib_mysql_query("update exam_questions set `choice_5`='$choice_5' where id='$id'");
		lib_mysql_query("update exam_questions set `choice_6`='$choice_6' where id='$id'");
		lib_mysql_query("update exam_questions set `task`='$task' where id='$id'");
			
		$action="admin_exam_edit";
		
	}
	
	if($action=="admin_exam_edit_add") {
		echo "Adding a new exam question ($exam_sequence) for exam $exam_id<br>";
		
		// echo "<p>Choose the type of exam question.</p>";
				
		lib_forms_optionize(	"$RFS_SITE_URL/modules/exams/exams.php",
						   "action=admin_exam_edit_add_2".$RFS_SITE_DELIMITER.
						   "exam_id=$exam_id".$RFS_SITE_DELIMITER.
						   "exam_sequence=$exam_sequence",
						   "exam_question_types",
						   "name",
						   0,
						   "Select type of exam question",
						   1);
	}
	
	if($action=="admin_exam_edit_add_2") {
		
		$r=lib_mysql_query("select * from exam_question_types where name='$name'");
		$eqt=mysql_fetch_object($r);
				
		echo "Adding new question to exam: $exam_id -> $exam_sequence -> $name ($eqt->type)<br>";		
		
		lib_forms_build( "$RFS_SITE_URL/modules/exams/exams.php",
	       "action=admin_exam_edit_add_3".$RFS_SITE_DELIMITER.
		   "aeexam_id=$exam_id".$RFS_SITE_DELIMITER.
		   "aeexam_sequence=$exam_sequence".$RFS_SITE_DELIMITER.
		   "aetype=$eqt->type".$RFS_SITE_DELIMITER.
		   "SHOW_SELECTOR_cfetp_tasks#task&name#task#Select a task to associate with this question",
	       "exam_questions",
	       "",
	       "id".$RFS_SITE_DELIMITER.
		   "exam_id".$RFS_SITE_DELIMITER.
		   "exam_sequence".$RFS_SITE_DELIMITER.
		   "type",
	       "task",
	       "omit",
	       "",
	       100,
	       "submit" );
		
	}
	
	if($action=="admin_exam_edit_add_3") {

		$exam_id=$aeexam_id;
		$exam_sequence=$aeexam_sequence;
		$type=$aetype;
		
		lib_mysql_query(" insert into exam_questions 
							(`exam_id`,`exam_sequence`,`type`, `intro`, `question`, `question_image`, `correct_answer`, `choice_1`, `choice_2`, `choice_3`, `choice_4`, `choice_5`, `choice_6`, `task` )
					VALUES ('$exam_id','$exam_sequence','$type', '$intro', '$question', '$question_image', '$correct_answer', '$choice_1', '$choice_2', '$choice_3', '$choice_4', '$choice_5', '$choice_6', '$task' )	");
		lib_mysql_query(" update exams set `questions`=`questions`+1 where id='$exam_id'");
		$action="admin_exam_edit";
	}
	
    if($action=="admin_exam_edit") {
		
		$exam=lib_mysql_fetch_one_object("select * from exams where id='$exam_id'");
		
		echo "<h1>EDIT EXAM $exam_id $exam->name</h1>";
		
		echo "[<a href=$RFS_SITE_URL/modules/exams/exams.php?action=run_exam&exam_id=$exam_id>Run this exam</a>]";
		echo "[<a href=$RFS_SITE_URL/modules/exams/exams.php?action=admin_edit>List all exams</a>]<br>";
		
		echo "<table border=0><tr><td> Method: $exam->method </td><td>";		
		echo " <form action='$RFS_SITE_URL/modules/exams/exams.php' method='post'>	
				<input type='hidden' name='action' value='admin_exam_edit_method'>
				<input type='hidden' name='exam_id' value='$exam_id'>								
				<select name='method' onchange='this.form.submit()'>
				<option>Select testing method
				<option>Sequential<option>Random</select>
				</form> </td></tr></table>";
				
		
		echo "<table border=0><tr><td>Passing percentage: </td><td>";
		echo " <form action='$RFS_SITE_URL/modules/exams/exams.php' method='post'>	
				<input type='hidden' name='action' value='admin_exam_edit_pass_percent'>
				<input type='hidden' name='exam_id' value='$exam_id'>
				<input name='pass_percent' size='2' value='$exam->pass_percent'  onblur='this.form.submit()'  >
				</form> </td></tr></table>";		
		
		echo "<table border=0><tr><td>Questions: $exam->questions</td><td>";		
		echo " <form action='$RFS_SITE_URL/modules/exams/exams.php' method='post'>	
				<input type='hidden' name='action' value='admin_exam_edit_questions'>
				<input type='hidden' name='exam_id' value='$exam_id'>
				<input name='questions' size='2' value='$exam->questions'  onblur='this.form.submit()'  >
				</form> </td></tr></table>";		
		
		
		echo "<hr>Question Pool:<br>";
		
		$q= "select MAX(`exam_sequence`) from `exam_questions` where exam_id='$exam_id'";
		$rrr=lib_mysql_query($q);
		$exq=mysql_fetch_array($rrr);
		$exam_sequence=$exq[0]+1;
		
		echo "[<a href='$RFS_SITE_URL/modules/exams/exams.php?action=admin_exam_edit_add&exam_id=$exam_id&exam_sequence=$exam_sequence'><img src='$RFS_SITE_URL/images/icons/plus_2.gif' width=16 border=0>Add Question</a>]<br>";		

		echo "<table border=0 cellspacing=0>";
		echo "<tr>";
		echo "<td> &nbsp; </td>";
		echo "<td> &nbsp; </td>";
		echo "<td> &nbsp; </td>";
		echo "<td> &nbsp; </td>";
		echo "<td> &nbsp; </td>";
		echo "<td> Type </td>";
		echo "<td> Intro </td>";
		echo "<td> Question </td>";
		echo "<td> Correct Answer </td>";
		echo "<td> Choice 1 (A)</td>";
		echo "<td> Choice 2 (B)</td>";
		echo "<td> Choice 3 (C)</td>";
		echo "<td> Choice 4 (D)</td>";
		echo "<td> Choice 5 (E)</td>";
		echo "<td> Choice 6 (F)</td>";
		
		echo "<td> Task </td>";
		
		
		echo "</tr>";
			
        $r=lib_mysql_query("select * from exam_questions where exam_id='$exam_id' order by exam_sequence");
        $n=mysql_num_rows($r);
        for($i=0;$i<$n;$i++){
			
				$gt++; if($gt>1) $gt=0;
			
				echo "<tr>";
		
				$q=mysql_fetch_object($r);
				echo "<td class=sc_project_table_$gt>
				<a href='$RFS_SITE_URL/modules/exams/exams.php?action=admin_exam_question_edit&exam_id=$exam->id&q=$q->id'>";
                echo "<img src='$RFS_SITE_URL/images/icons/Edit.png' width=16 border=0>";
                echo "</a>
				</td>";				


                				
				echo "<td class=sc_project_table_$gt>
				<a href='$RFS_SITE_URL/modules/exams/exams.php?action=admin_exam_question_remove&exam_id=$exam->id&q=$q->id'>";
                echo "<img src='$RFS_SITE_URL/images/icons/Delete.png' width=16 border=0>";
                echo "</a>
				</td>";
				echo "<td class=sc_project_table_$gt><img src='$RFS_SITE_URL/images/icons/arrow-up.png' border=0 width=16></td>";
				echo "<td class=sc_project_table_$gt><img src='$RFS_SITE_URL/images/icons/arrow-down.png' border=0 width=16></td>";
				echo "<td class=sc_project_table_$gt>$q->exam_sequence)</td>";
				echo "<td class=sc_project_table_$gt>$q->type</td>";
				echo "<td class=sc_project_table_$gt>".lib_string_truncate($q->intro,85)."</td>";
				echo "<td class=sc_project_table_$gt>$q->question</td>";
				echo "<td class=sc_project_table_$gt>$q->correct_answer</td>";
				
				echo "<td class=sc_project_table_$gt>$q->choice_1</td>";
				echo "<td class=sc_project_table_$gt>$q->choice_2</td>";
				echo "<td class=sc_project_table_$gt>$q->choice_3</td>";
				echo "<td class=sc_project_table_$gt>$q->choice_4</td>";
				echo "<td class=sc_project_table_$gt>$q->choice_5</td>";
				echo "<td class=sc_project_table_$gt>$q->choice_6</td>";
				
				echo "<td class=sc_project_table_$gt>$q->task</td>";
				
				
				
				
				
				
				echo "<td class=sc_project_table_$gt>";
				if(!empty($q->question_image)) 
					lib_rfs_echo("<img src=$q->question_image width=32>");
				echo "</td>";
				echo "</tr>";
				$exam_sequence=$q->exam_sequence;
        }
		echo "</table>";
		$exam_sequence++;
		

    }
	
    if($action=="admin_new_exam") {

        $r=lib_mysql_query( "insert into exams (`name`) VALUES ('$name')");
        $id = mysql_insert_id();
        echo "<p> ";
        if($id)
            echo " Exam $name ($id) created";
        else
            echo " Exam creation error. ";
        echo "</p> ";
    }

	if($action=="admin_exam_change_pod") {
	
		echo "<p> CHANGE EXAM POD </p>";
		echo "<p> Exam: $exam_id </p>";
		echo "<p> POD: $name </p>";
		// $pod=lib_mysql_fetch_one_object("select * from pods where name='$name'");
		$q="update `exams` set `pod_id`='$name' where `id`='$exam_id'";
		
		lib_mysql_query($q);
		
		$action="admin_edit";
	}

    if($action=="admin_edit"){
		
		    // Types of questions:
    //
    // Multiple choice
    // Fill in the blank
    // Fill in the blank (with dropdown box)
    // True / False
    // Matching

        sc_bqf("action=admin_new_exam".$RFS_SITE_DELIMITER."SHOW_TEXT_name=new exam","Create new exam");

        $q="select * from exams order by name ";
        $r=lib_mysql_query($q); $n=mysql_num_rows($r);

        echo "<table border=0 cellspacing=o cell padding=0>";

        echo "<tr>";
        echo "<td>Tools</td>";
        echo "<td>Name </td>";
		echo "<td>Method</td>";
		echo "<td>Pass Percent</td>";
		echo "<td>Questions</td>";
		echo "<td>Total Questions</td>";
        echo "<td>POD</td>";

        echo "</tr>";

        for($i=0;$i<$n;$i++){

            $exam=mysql_fetch_object($r);
            $gt++; if($gt>2) $gt=1;

            echo "<tr>";

                echo "<td class=sc_project_table_$gt>";
                ///////// tools
                                                echo "<table border=0 cellspacing=0 cellpadding=0>";
                                        echo "<tr>";


                ///// edit
                                    echo "<td>";
                echo "<a href='$RFS_SITE_URL/modules/exams/exams.php?action=admin_exam_edit&exam_id=$exam->id'>";
                echo "<img src='$RFS_SITE_URL/images/icons/Edit.png' width=32 border=0>";
                echo "</a>";
                                    echo "</td>";


                ///// delete
                                    echo "<td>";
                echo "<a href='$RFS_SITE_URL/modules/exams/exams.php?action=admin_exam_remove&exam_id=$exam->id'>";
                echo "<img src='$RFS_SITE_URL/images/icons/Delete.png' width=32 border=0>";
                echo "</a>";
                                    echo "</td>";

                ///// rename

                                    echo "<td>";
                echo "<a href='$RFS_SITE_URL/modules/exams/exams.php?action=admin_exam_rename&exam_id=$exam->id'>";
                echo "<img src='$RFS_SITE_URL/images/icons/Tag.png' width=32  border=0>";
                echo "</a>";
                                    echo "</td>";



                ///// run

                                    echo "<td>";
                echo "<a href='$RFS_SITE_URL/modules/exams/exams.php?action=admin_exam_test&exam_id=$exam->id'>";
                echo "<img src='$RFS_SITE_URL/images/icons/Play.png' width=32  border=0>";
                echo "</a>";
                                    echo "</td>";



                                            echo "</tr>";
                                                    echo "</table>";

                echo "</td>";

                //// exam name

                echo "<td class=sc_project_table_$gt>";
					echo $exam->name;
                echo "</td>";


                //// exam method

                echo "<td class=sc_project_table_$gt>";
					echo $exam->method;
                echo "</td>";
				
               //// exam pass percent

                echo "<td class=sc_project_table_$gt>";
					echo $exam->pass_percent;
                echo "</td>";
		
               //// exam questions

                echo "<td class=sc_project_table_$gt>";
					echo $exam->questions;
                echo "</td>";
				
				
               //// exam total questions

                echo "<td class=sc_project_table_$gt>";
				$rnq=lib_mysql_query("select * from exam_questions where exam_id='$exam->id'");
				$nq=mysql_num_rows($rnq);
					echo $nq;
                echo "</td>";



                

                //// POD association
					echo "<td class=sc_project_table_$gt>";
                    $pod_id=$exam->pod_id;
                    $pod=" ";
                    if($pod_id==0){
                        $pod="Select POD to associate with this exam";
                    }
                    else	{
                            $podd=lib_mysql_fetch_one_object("select * from pods where id='$pod_id'");
							$pod=$podd->name;
                    }
					

                lib_forms_optionize(	lib_domain_phpself(),
                                "action=admin_exam_change_pod".$RFS_SITE_DELIMITER.
                                "exam_id=$exam->id",
                                "pods",
                                "name",
                                "1",
                                $pod,
                                "1"
                                );
				echo "</td>";


            echo "</tr>";
        }

        echo "</table>";        
    }

}


for($i=0;$i<35;$i++) echo "<br>";
include("footer.php");


?>
