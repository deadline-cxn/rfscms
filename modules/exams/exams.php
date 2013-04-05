<?

chdir("../../");
include("include/lib.mysql.php"); 

if(empty($_REQUEST['exam_id'])) $title="Exams";
else  {
        $tex=mfo1("select * from exams where id='".$_REQUEST['exam_id']."'");
        $title="Exam ($tex->name)";
}

$answer_data[1]="A";
$answer_data[2]="B";
$answer_data[3]="C";
$answer_data[4]="D";
$answer_data[5]="E";
$answer_data[6]="F";


include("header.php");

if(!sc_yes($_SESSION['logged_in'])) {
	echo "You're not logged in. <br> "; 
	for($i=0;$i<35;$i++) echo "<br>";
	include("footer.php");
	exit;
}


if(sc_access_check("exams.php","view_results")) {
    
	$qc=$_SESSION['question_id'];
	if($qc) {

        $r=sc_query("select * from exam_questions where id='$qc'");
        $q=mysql_fetch_assoc($r);
		
		//echo "<hr>";
		
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
			
			
			if($group1==$q["correct_answer"])  {
				//echo "CORRECT";
				$correct=true;	
			}				
		}  // c3745-is-mz.122-13.T9.bin
		
		if($q["type"]=="true_false") {
			
			//echo " --- $group1 --- ";
			//echo $q['correct_answer'];
			//echo " --- ";
			
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

			$r=sc_query("select * from exam_users where `question_id`='$qc' and
															  `user`='$data->name' and
															  `exam_id`='$exam_id' ");
															  
			$eud=@mysql_fetch_object($r);
															
			if($eud->id) { 
								sc_query("update exam_users set `correct`='$correct'  where `question_id`='$qc' and
																	  `user`='$data->name' and
																	   `exam_id`='$exam_id'");
			}
			else {
		
					sc_query("insert into exam_users   (`user`, `exam_id`, `question_id`, `correct`)
													values ('$data->name', '$exam_id', '$qc', '$correct') ");
													
			}

		//echo "<hr>";
		$_SESSION['question_id']="";
	}
    
}


if($action=="reset_exam"){
    $qid=0;
    $_SESSION['exam_$exam_id']="0";
}

if($action=="admin_exam_test") {
    $_SESSION['exam_$exam_id']="1";
    $action="run_exam";
}

if(empty($action)) $action="list_exams";

if ($action=="list_exams") {

    if( sc_access_check("exams.php","edit") ) { 
            echo "[<a href='$RFS_SITE_URL/modules/exams/exams.php?action=admin_edit'>Exam Administration</a>]";
    }	
	
	
	$n=0;
    $r=sc_query("select * from exams");
    if($r) $n=mysql_num_rows($r);
	echo "<table border=0 cellspacing=0 cellpadding=2>";
	
	echo "<tr> <td></td> <td> Name </td>  <td>Your Score</td>  <td>Passing Score</td> </tr>";
    for($i=0;$i<$n;$i++) {
			$gt++; if($gt>1) $gt=0;
			$exam=mysql_fetch_object($r);
			echo "<tr>";
			
			echo "<td class=sc_project_table_$gt>";			
			echo "<a href='$RFS_SITE_URL/modules/exams/exams.php?action=run_exam&exam_id=$exam->id'>
			<img src='$RFS_SITE_URL/images/icons/Play.png' width=16 border=0></a>
			</td>";
			
			echo "<td class=sc_project_table_$gt>";
			
			echo "<a href='$RFS_SITE_URL/modules/exams/exams.php?action=run_exam&exam_id=$exam->id'>$exam->name</a>";
			
			echo "</td>";
			
			
			echo "<td class=sc_project_table_$gt>";
			$eprct=exams_get_prct($data->name,$exam->id);
			$escor=exams_get_score($data->name,$exam->id);
			$etotq=exams_get_total_questions($exam->id);
			
			$oclr="RED";
			if($eprct>=$exam->pass_percent) $oclr="GREEN";
			
			sc_info("$escor/$etotq : $eprct%", "WHITE", $oclr);
			
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
	exams_wipe_user_exam($data->name,$exam_id);
	$action="run_exam";
}

if($action=="run_exam") {
	
	$exam   = mfo1("select * from exams where id='$exam_id'");			
	$eprct  = exams_get_prct($data->name,$exam_id);
	$escor  = exams_get_score($data->name,$exam_id);
	$etotq  = exams_get_total_questions($exam_id);			
	$etotqa = exams_get_total_questions_answered($data->name,$exam_id);
	$oclr="RED";
	if($eprct>=$exam->pass_percent) $oclr="GREEN";
	
	
	if($oclr=="GREEN") {
		if( $etotq == $etotqa ) {
			
			sc_info("$escor/$etotq : $eprct%", "WHITE", $oclr);
			sc_info($exam->pass_percent."% minimum passing score required.", "WHITE", "BLUE");
			sc_warn("You have already taken this exam and passed. If you wish, you may retake the exam, but your current score will be erased and you will have to take the exam in it's entirety with a passing score to recieve credit. If you are sure then
			<a href='$RFS_SITE_URL/modules/exams/exams.php?action=wipe_exam&exam_id=$exam_id'><img src=$RFS_SITE_URL/images/icons/Play.png border=0>click here</a>. Note: This action can not be reversed.");
			for($i=0;$i<35;$i++) echo "<br>";
			include("footer.php");
			exit();
		
		}

	}

    d_echo("Exam $exam_id");
    $exam=mfo1("select * from exams where id='$exam_id'");
    d_echo("$exam->name");

    if($qsid!=$_SESSION['exam_$exam_id']) $_SESSION['exam_$exam_id']=$qsid;

    $r=sc_query("select * from exam_questions where exam_id='$exam_id'");
    $nq=mysql_num_rows($r);

    d_echo("$nq");

    $qsid=$_SESSION['exam_$exam_id'];

    if($qsid<1) $qsid=1;
        $_SESSION['exam_$exam_id']=1;

    d_echo("$qsid");
    $question=mfo1("select * from exam_questions where exam_sequence='$qsid' and exam_id='$exam_id'");
    d_echo($question->type);
	
	$_SESSION['question_id']=$question->id;
	
	if(empty($question->type)) {
		
		
		$qq=" <BR><BR> END OF EXAM! <HR>";
		
		/*
		$r=sc_query("select * from exam_users where user='$data->name' and exam_id='$exam_id'");		
		$nq=mysql_num_rows($r);
		
		$r=sc_query("select * from exam_users where user='$data->name' and exam_id='$exam_id' and correct='1'");
		$nqc=mysql_num_rows($r);
		
		$prct=$nqc/$nq;
		
		$prct=round($prct*100);
		*/
		$nqc=exams_get_score($data->name,$exam_id);
		$prct=exams_get_prct($data->name,$exam_id);

		$qq.="<h1>";
		$qq.="You answered $nqc questions correctly out of $nq <br>";		
		$qq.="Percentage answered correctly $prct %<br>";		
		
		$exam=mfo1("select * from exams where id='$exam_id'");
		
		$qq.="Minimum passing score for this exam: $exam->pass_percent <br> <hr>";		
		if($prct>$exam->pass_percent) {
			$qq.="<BR>Congratulations, you passed.<br><BR><hr>";
			$qq.="Tasks covered by this exam:<br>";
			$r=sc_query("select distinct task from exam_questions where exam_id='$exam_id'");
			for($i=0;$i<mysql_num_rows($r);$i++) {
				$eq=mysql_fetch_object($r);
				$qq.= " $eq->task <br>";
			}
			
		} else {
			$qq.="You did not pass, please review the missed questions.<br><br>";
			
			
		}		
		$qq.="<hr></h1>";

		$qq.="[<a href=\"$RFS_SITE_URL/modules/exams/exams.php?action=run_exam&exam_id=$exam_id\">Take this exam again</a>]";
		$qq.="[Review missed questions]";
		$qq.="[Review all questions]";
		
		if( sc_access_check("exam_questions","edit") ) {
				$qq.="[<a href='$RFS_SITE_URL/modules/exams/exams.php?action=admin_edit'>Exam Administration</a>]";
		}
		
		$qq.="<BR><BR> <BR><BR>";
		
		sc_question($qq);
			
		
		
		
	}
	else {
		
		


    $qq="<br><h1> EXAM: $exam->name question ($qsid / $nq) </h1> ";
    // $qq.="<span style='color:red;background-color:black;'>    Development mode: this question is type: $question->type (ignore this) </span>";
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

        sc_div("MULTIPLE CHOICE TEST QUESTION");

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
        sc_div("MULTI SELECT TEST QUESTION");
				
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


        sc_div("TRUE OR FALSE TEST QUESTION");

        $qq.="$question->question<br><br>";

        $qq.= "<input type=\"radio\" name=\"group1\" value=\"TRUE\"> TRUE <br>\n";
        $qq.= "<input type=\"radio\" name=\"group1\" value=\"FALSE\"> FALSE <br>\n";

        //        $qq.="TRUE or FALSE <br>";
        $qq.=$ql;
    }

    if($question->type=="fill_in_blank") {

        sc_div("FILL IN THE BLANK TEST QUESTION");

        $oq=str_replace("-a-","<input type=text name=a>", $question->question);
        $oq=str_replace("-b-","<input type=text name=b>", $oq);
        $oq=str_replace("-c-","<input type=text name=c>", $oq);
        $oq=str_replace("-d-","<input type=text name=d>", $oq);
        $oq=str_replace("-e-","<input type=text name=e>", $oq);
        $oq=str_replace("-f-","<input type=text name=f>", $oq);


		$qq.=$oq.$ql;
    }

				           
    if($question->type=="fib_dropdown") {

            sc_div("FILL IN THE BLANK DROP DOWN TEST QUESTION");

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
    sc_question($qq);
    sc_div("END OF TEST QUESTION");
	
	}
}



if(sc_access_check("exams.php","edit") ) {
	
	
	if($action=="admin_exam_edit_questions") {
		if(!is_numeric($questions)) $questions=100;
		
		$r=sc_query("select * from exam_questions where exam_id='$exam_id' order by exam_sequence");
		$n=mysql_num_rows($r);
		if($questions>$n) $questions=$n;
		
		sc_query("update exams set `questions` = '$questions' where id='$exam_id'");
		$action="admin_exam_edit";
	}
	
	if($action=="admin_exam_edit_method") {
		sc_query("update exams set `method`='$method' where id='$exam_id'");
		$action="admin_exam_edit";
	}
	
	if($action=="admin_exam_edit_pass_percent") {
		if(!is_numeric($pass_percent)) $pass_percent=70;
		// echo " $exam_id  $pass_percent <br>";
		sc_query("update exams set `pass_percent`='$pass_percent' where id='$exam_id'");
		$action="admin_exam_edit";		
	}
		
	
	if($action=="admin_exam_question_edit") {
		
echo "[<a href=$RFS_SITE_URL/modules/exams/exams.php?action=run_exam&exam_id=$exam_id>Run this exam</a>]";
echo "[<a href=$RFS_SITE_URL/modules/exams/exams.php?action=admin_edit>List all exams</a>]";
echo "[<a href=$RFS_SITE_URL/modules/exams/exams.php?action=admin_exam_edit&exam_id=$exam_id>List all questions</a>]<br>";
		
		$qt=mfo1("select * from exam_questions where id='$q'");
		
	sc_bf( "$RFS_SITE_URL/modules/exams/exams.php",
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
		
		/*		
		echo "		 <BR>
		id: $id <BR>
			exam_id: $exam_id <BR>
			exam_sequence: $exam_sequence <BR>
			type: $type	 <BR>
			intro: $intro	 <BR>
			question: $question	 <BR>
			question_image: $question_image	 <BR>
			correct_answer: $correct_answer <BR>	
			choice_1: $choice_1	 <BR>
			choice_2: $choice_2	 <BR>
			choice_3: $choice_3	 <BR>
			choice_4: $choice_4	 <BR>
			choice_5: $choice_5	 <BR>
			choice_6: $choice_6  <BR>
		";
		
		*/
		
		$r=sc_query("select * from exam_question_types where name='$type'");
		$eqt=mysql_fetch_object($r);
		
		if(empty($eqt->type)){
			$r=sc_query("select * from exam_question_types where type='$type'");
			$eqt=mysql_fetch_object($r);
		}
		if(empty($eqt->type)) $eqt->type="multiple_choice";


sc_query("update exam_questions set `exam_id`='$exam_id' where id='$id'");
sc_query("update exam_questions set `exam_sequence` ='$exam_sequence' where id='$id'");
sc_query("update exam_questions set `type`='$eqt->type' where id='$id'");
sc_query("update exam_questions set `intro`='$intro' where id='$id'");
sc_query("update exam_questions set `question`='$question' where id='$id'");
sc_query("update exam_questions set `question_image`='$question_image' where id='$id'");
sc_query("update exam_questions set `correct_answer`='$correct_answer' where id='$id'");
sc_query("update exam_questions set `choice_1`='$choice_1' where id='$id'");
sc_query("update exam_questions set `choice_2`='$choice_2' where id='$id'");
sc_query("update exam_questions set `choice_3`='$choice_3' where id='$id'");
sc_query("update exam_questions set `choice_4`='$choice_4' where id='$id'");
sc_query("update exam_questions set `choice_5`='$choice_5' where id='$id'");
sc_query("update exam_questions set `choice_6`='$choice_6' where id='$id'");
sc_query("update exam_questions set `task`='$task' where id='$id'");
		
		$action="admin_exam_edit";
		
	}
	
	if($action=="admin_exam_edit_add") {
		echo "Adding a new exam question ($exam_sequence) for exam $exam_id<br>";
		
		// echo "<p>Choose the type of exam question.</p>";
				
		sc_optionizer(	"$RFS_SITE_URL/modules/exams/exams.php",
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
		
		$r=sc_query("select * from exam_question_types where name='$name'");
		$eqt=mysql_fetch_object($r);
				
		echo "Adding new question to exam: $exam_id -> $exam_sequence -> $name ($eqt->type)<br>";		
		
		sc_bf( "$RFS_SITE_URL/modules/exams/exams.php",
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
		/*
		echo "		 <BR>
			exam_id: $exam_id <BR>
			exam_sequence: $exam_sequence <BR>
			type: $type	 <BR>
			intro: $intro	 <BR>
			question: $question	 <BR>
			question_image: $question_image	 <BR>
			correct_answer: $correct_answer <BR>	
			choice_1: $choice_1	 <BR>
			choice_2: $choice_2	 <BR>
			choice_3: $choice_3	 <BR>
			choice_4: $choice_4	 <BR>
			choice_5: $choice_5	 <BR>
			choice_6: $choice_6  <BR>
		";
		*/
sc_query("
insert into exam_questions 
       (`exam_id`,`exam_sequence`,`type`, `intro`, `question`, `question_image`, `correct_answer`, `choice_1`, `choice_2`, `choice_3`, `choice_4`, `choice_5`, `choice_6`, `task` )
VALUES ('$exam_id','$exam_sequence','$type', '$intro', '$question', '$question_image', '$correct_answer', '$choice_1', '$choice_2', '$choice_3', '$choice_4', '$choice_5', '$choice_6', '$task' )	");

		
		$action="admin_exam_edit";
	}
	
	
	
    if($action=="admin_exam_edit") {
		
		$exam=mfo1("select * from exams where id='$exam_id'");
		
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
			
        $r=sc_query("select * from exam_questions where exam_id='$exam_id' order by exam_sequence");
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
				echo "<td class=sc_project_table_$gt>".sc_trunc($q->intro,85)."</td>";
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
					rfs_echo("<img src=$q->question_image width=32>");
				echo "</td>";
				echo "</tr>";
				$exam_sequence=$q->exam_sequence;
        }
		echo "</table>";
		$exam_sequence++;
		

    }
	

    if($action=="admin_new_exam") {

        $r=sc_query( "insert into exams (`name`) VALUES ('$name')");
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
	// $pod=mfo1("select * from pods where name='$name'");
	$q="update `exams` set `pod_id`='$name' where `id`='$exam_id'";
	
	sc_query($q);
	
	$action="admin_edit";
}

    // Types of questions:
    //
    // Multiple choice
    // Fill in the blank
    // Fill in the blank (with dropdown box)
    // True / False
    // Matching

    if($action=="admin_edit"){

        sc_bqf("action=admin_new_exam".$RFS_SITE_DELIMITER."SHOW_TEXT_name=new exam","Create new exam");

        $q="select * from exams order by name ";
        $r=sc_query($q); $n=mysql_num_rows($r);

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
				$rnq=sc_query("select * from exam_questions where exam_id='$exam->id'");
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
                            $podd=mfo1("select * from pods where id='$pod_id'");
							$pod=$podd->name;
                    }
					

                sc_optionizer(	sc_phpself(),
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



/*
if($action=="add_script_group"){
	sc_query("insert into script_groups (`name`) values('$name')");
	$r=sc_query("select * from script_groups where name = '$name'");
	$scr=mysql_fetch_object($r) ;
	echo "<h1>Add $scr->name</h1>";

}

if($action=="script_group_change_pod"){

	echo "script_group [$script_group] <br>";
	echo "name [$name] <br>";

	sc_query("update script_groups set pod='$name' where id='$script_group'");

	$action="show_script_groups";

}

if($action=="script_change_group"){
	echo "<h1>Change GROUP!</h1>";

	// remove_script_from_groups($script);

	echo "$script<br>";
	echo "$name<br>";
	$sgr=sc_query("select * from script_groups where id=$name");
	$sg=mysql_fetch_object($sgr);
	echo "Script Group -> $sg->name <br>";
	$sgsc=",$sg->scripts,";
	$sgsc=str_replace(",$script,",",",$sgsc);
	$sgsc=str_replace(",,",",",$sgsc);
	$sgsc=rtrim($sgsc,",");
	$sgsc=$script.$sgsc;

	sc_query("update script_groups set scripts = '$sgsc' where id='$name'");
	sc_query("update scripts set script_group='$name' where id='$sgsc'");

	echo "Scripts in this group: $sgsc <br>";

	$action="show_scripts";

}

if($action=="remove_script_from_group"){
		//echo "Remove script[$script] from group [$group]<br><br>";
		$r=sc_query("select * from script_groups where id='$group'");
		$sg=mysql_fetch_object($r);
		$sgx=explode(",",$sg->scripts);
		$sgi="";
		for($si=0;$si<count($sgx);$si++){
			//echo "$sgx[$si] <BR>";
			if($sgx[$si]==$script){

			}
				//echo " ^^^^^^ WINNER <BR>";
			else
				$sgi.=$sgx[$si].",";

		}
		$sgi=rtrim($sgi,",");
		echo "$sgi<br>";
		sc_query("update script_groups set scripts='$sgi' where id='$group'");
		$action="show_script_groups";
}

if($action=="show_script_groups"){

	$q="select * from script_groups";
	$r=sc_query($q);
	$n=mysql_num_rows($r);
	echo "<table border=0>";

	echo "<tr>";
	echo "<td>Name</td>";
	echo "<td>Pod</td>";
	echo "<td>Scripts</td>";

	echo "</tr>";

	for($i=0;$i<$n;$i++){

		$s=mysql_fetch_object($r);
		$gt++; if($gt>2) $gt=1;

		echo "<tr>";

		echo "<td class=sc_project_table_$gt>$s->name</td>";

		echo "<td class=sc_project_table_$gt>";

		// echo $s->pod;

		$dpod="Select Pod";
		if($s->pod>0){
			$pr=sc_query("select * from pods where id='$s->pod'");
			$pod=mysql_fetch_object($pr);
			$dpod=$pod->name;
		}

		sc_optionizer(	sc_phpself(), // return page
							"action=script_group_change_pod¥". // variables to return
							"script_group=$s->id",
							"pods", // table to optionize
							"name", // field to optionize
							"1", // use id method
							$dpod, // default text to show
							"1"  // use on change method
							);


		echo "</td>";

		echo "<td class=sc_project_table_$gt>";

		//$sr=sc_query("select * from scripts where script_group='$s->id'");
		//$ns=mysql_num_rows($sr);
		//for($ni=0;$ni<$ns;$ni++){
		//	$ins=mysql_fetch_object($sr);
		//	echo "$ins->name $ins->network_device ($ins->file)<br>";
		//}
		$sx=explode(",",$s->scripts);

		//echo "<BR> [COUNT [";
		//echo count($sx);
		//echo "]]<BR>";

		for($sii=0;$sii<count($sx);$sii++){

			if(!empty($sx[$sii])) {
				$scr_name=mfo1("select * from scripts where id='$sx[$sii]'");
				echo "<a href='$RFS_SITE_URL/net.php?action=remove_script_from_group&group=$s->id&script=$sx[$sii]'>";
				echo "<img src=images/icons/network_devices/cross.png border=0>";
				echo "</a>";
				echo $scr_name->name."<br>";
			}


		}


		echo "</td>";
		echo "</tr>";
	}

	echo "</table>";

	$page=$GLOBALS['site_url'].$GLOBALS['PHP_SELF'];
	sc_bf($page,"action=add_script_group","script_groups","","","name","include","",60,"add");
}


function orphan_script_scan($dir){
	echo "Scanning [$dir]<br>";
	$dir_count=0;
    $dirfiles = array();
    $handle=opendir($dir) or die("Unable to open filepath");
    while (false!==($file = readdir($handle))) array_push($dirfiles,$file);
    closedir($handle);
    reset($dirfiles);
    while(list ($key, $file) = each ($dirfiles)){
        if($file!="."){
            if($file!=".."){
                if(is_dir($dir."/".$file)){
					orphan_scan($dir."/".$file);
				}
				else {
			        $filefound=0;
                    $url = "$dir/$file";
                    $res=sc_query("select * from `scripts` where `file` like '%$file%'");
                    if(mysql_num_rows($res)>0) $filefound=1;
                    $res=sc_query("select * from `scripts` where `name` = '%$file%'");
                    if(mysql_num_rows($res)>0) $filefound=1;
                    if($filefound){
						// echo "Skipped [$url] already in database<br>";
                    }
                    else{
                        $time=date("Y-m-d H:i:s");
                        $filetype=sc_getfiletype($file);
                        // $filesizebytes=filesize(getcwd()."/$dir/$file");
                        $name=$file;
                        sc_query("INSERT INTO `scripts` (`file`) VALUES('$file');");
                        sc_query("UPDATE scripts SET `name`='$file' where `file`='$name'");
                        sc_query("UPDATE scripts SET `type`='none' where `file`='$name'");
                        echo "Added [$url] to database<br>";
                        $dir_count++;
                   }
				 }
            }
        }
    }
	echo "Finished scan for orphan scripts<br>";
}

function purge_scripts(){
	$scripts=sc_query("select * from scripts");
	for($i=0;$i<mysql_num_rows($scripts);$i++){
		$script=mysql_fetch_object($scripts);

		if( (!file_exists("/var/www/scripts/$script->file"))  ||
				(filesize("/var/www/scripts/$script->file")==0)
			) {
			system("rm /var/www/scripts/$script->file");
			sc_query("delete from scripts where id = $script->id");
			echo "-- > $script->file removed <BR>";

		}


	}

}

if($action=="orphan_scripts"){
	echo "<h2>Searching for scripts</h2>";
	orphan_script_scan("/var/www/scripts");
	$action="show_scripts";
}

if($action=="purge_scripts"){
	echo "<h2>Purging scripts</h2>";
	purge_scripts();
	$action="show_scripts";
}

if($action=="script_assoc"){
	sc_query("update scripts set network_device='$network_device' where id='$name'");
	$action="show_scripts";
}

if($action=="script_change_network_device"){

	sc_query("update scripts set network_device='$ipaddress' where id='$script'");
	$scr=mfo1("select * from scripts where id='$script'");

	echo "SCRIPT: $scr->name <BR>";
	$nd=mfo1("select * from network_devices where id='$ipaddress'");
	echo "NETWORK DEVICE: $nd->name ($nd->ipaddress)<BR>";

	$action="show_scripts";


}
*/
/*
function remove_script_from_groups($script){
	$r=sc_query("select * from script_groups");
	for($i=0;$i<mysql_num_rows($r);$i++) {
		$sg=mysql_fetch_object($r);
		$sgsx=explode(",",$sg->scripts);
		$sgso="";
		for($si=0;$si<count($sgsx);$si++){
			if($sgsx[$si]!=$script){
				$sgso.=$sgsx[$si].",";
			}
		}
		$sgso=rtrim($sgso,",");
		echo "removing script $script from $sg->name ($sgso)<br>";
		sc_query("update script_groups set scripts='$sgso'");
	}
}
  */

  /*

if($action=="script_modify_go") {

	echo "<h1>Edit Script</h1>";
	$action="show_scripts";
	$scr=mfo1("select * from scripts where id=$id");
	$fp=fopen("/var/www/scripts/$scr->file",wt);
	if($fp){
		fputs($fp,$config);
		fclose($fp);
	}
	$action="show_scripts";

}

if($action=="show_script") {

	$script=mfo1("select * from scripts where id=$id");

	echo "<h1>Edit script $script->file </h1>";

	$scr=file_get_contents("/var/www/scripts/$script->file");

	// $scr=str_replace("<","&lt;",$scr);

	$dorksz="id=$script->id";

	sc_bqf("action=script_modify_go¥".$dorksz."¥".
			"SHOW_TEXTAREA_32#110#config=$scr",
			"Modify");
}
if($action=="script_remove_go"){
	echo "<h1>Remove script confirmed</h1>";
	$s=mfo1("select * from scripts where id='$script'");
	echo sc_warn("$script $s->name $s->file removed");
	sc_query("delete from scripts where id='$script'");

	$cm="rm /var/www/scripts/$s->name";
	echo "<br>$cm<br>";
	system($cm);

	$action="show_scripts";

}
if($action=="script_remove"){
	echo "<h1>Remove script</h1>";
	$s=mfo1("select * from scripts where id='$script'");
	sc_confirmform(sc_warn("Remove $s->name from server and database?"),
					"net.php",
					"action=script_remove_go".
					"¥script=$script" );
	$action="show_scripts";
}

if($action=="script_rename_go") {
	echo "<h1>Rename script</h1>";
	$cm="mv '/var/www/scripts/$script' '/var/www/scripts/$new_name'";
	echo "<br>$cm<br>";
	system($cm);
	purge_scripts();
	orphan_script_scan("/var/www/scripts");
	$action="show_scripts";
}

if($action=="script_rename") {
	$s=mfo1("select * from scripts where id='$script'");
	echo "<h1>Rename script</h1>";
	echo "Rename: $s->name";
	sc_bqf("action=script_rename_go".
			"¥script=$s->name".
			"¥SHOW_TEXT_1#60#new_name=$s->name",
			"submit");
	$action="show_scripts";
}

if($action=="script_copy_go") {
	echo "<h1>Copy script</h1>";
	sc_inform("copied /var/www/scripts/$script to /var/www/scripts/$copy_to_filename");
	$cm="cp '/var/www/scripts/$script' '/var/www/scripts/$copy_to_filename'";
	echo "<br>$cm<br>";
	system($cm);

	orphan_script_scan("/var/www/scripts");
	$action="show_scripts";
}

if($action=="script_copy"){

	$s=mfo1("select * from scripts where id='$script'");
	$newname="copy-".time()."-".$s->name;
	echo "<h1>Copy script</h1>";
	sc_bqf("action=script_copy_go".
			"¥script=$s->name".
			"¥SHOW_TEXT_1#60#copy_to_filename=$newname",
			"submit");

	$action="show_scripts";
}

if($action=="script_push"){
	$action="show_scripts";
}
*/





}


for($i=0;$i<35;$i++) echo "<br>";
include("footer.php");


?>
