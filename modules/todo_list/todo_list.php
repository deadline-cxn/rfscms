<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////

if(stristr(getcwd(),"modules")) { chdir("../../"); }
include_once("include/lib.all.php");
include("header.php");

function todo_list_action_f_rfs_db_element_del1() { adm_action_f_rfs_db_element_del1(); }
function todo_list_action_f_rfs_db_element_ed1() { adm_action_f_rfs_db_element_ed1(); }

function todo_list_action_() { eval(scg());
	echo "<h1>TODO List</h1>";
	echo "<hr>";
	sc_button("$RFS_SITE_URL/modules/todo_list/todo_list.php?action=new_todo_list","New List");
	echo "<hr>";
	$r=sc_query("select * from todo_list");
	for($i=0;$i<mysql_num_rows($r);$i++) {
		$tdl=mysql_fetch_object($r);
		
		rfs_db_element_edit($tdl->name,
							"$RFS_SITE_URL/modules/todo_list/todo_list.php",
							"",
							"todo_list",
							$tdl->id); 
		echo "<br>";
	// todo_list: 			name	description	assigned_to	owner
	}
}

function todo_list_action_view_todo_list() { eval(scg());
	$r=sc_query("select * from todo_list where id=$id");
	$tdl=mysql_fetch_object($r);
	
	echo "<h1>$tdl->name</h1>";
	echo "$tdl->description<br>";
	
	if(!empty($tdl->assigned_to))
		echo "Assigned to: $tdl->assigned_to<br>";
	if(!empty($tdl->owner))
	echo "Owner: $tdl->owner<br>";
	
	echo "List: $tdl->id<br>";
	echo "<hr>";
	
	echo "<style>
	
.todo_ {

	margin: 5px;
	padding: 5px;
	background-color: #0F0;
	color: #FFF;

}
	
	</style>";
	
$r=sc_query("
select * from `todo_list_task` 
where (`list`='$tdl->id') and
	  (`name`='Stuff') ;");
	  
	$n=mysql_num_rows($r);

	if($n>0) {
		echo "Open tasks:<br>";
		
		echo "<table border=0 cellpadding=5 cellspacing=0>";
			echo "<tr>";
			
			echo "<th>";
			echo "Status";
			echo "</th>";
			
			echo "<th>";
			echo "Priority";
			echo "</th>";
			
			echo "<th>";
			echo "Name";
			echo "</th>";
			
			echo "<th>";
			echo "Opened";
			echo "</th>";
			
			echo "<th>";
			echo "Due";
			echo "</th>";
			
			echo "<th>";
			echo "Step";
			echo "</th>";
			
			echo "<th>";
			echo "Action";
			echo "</th>";
			
			echo "</tr>";		
		
		for($i=0;$i<$n;$i++) {
			$task=mysql_fetch_object($r);
			
			// todo_list_task: name opened due list priority step
			
			echo "<tr>";
			
			echo "<td class=\"todo_$tdl->status\">";
			echo "$tdl->status";
			echo "</td>";
			
			echo "<td class=\"todo_$tdl->status\">";
			echo "$tdl->priority";
			echo "</td>";
			
			echo "<td class=\"todo_$tdl->status\">";
			echo "$task->name";
			echo "</td>";
			
			echo "<td class=\"todo_$tdl->status\">";
			echo "$task->opened";
			echo "</td>";
			
			echo "<td class=\"todo_$tdl->status\">";
			echo "$task->due";
			echo "</td>";
			
			echo "<td class=\"todo_$tdl->status\">";
			echo "$task->step";
			echo "</td>";
			
			echo "<td class=\"todo_$tdl->status\">";
			echo "$task->action";
			echo "</td>";
			
			echo "</tr>";
		}
		echo "</table>";
		
	}
	else {
		echo "There are no tasks.<br>";
	}
		
	
}

function todo_list_action_new_todo_list_go() { eval(scg());
	sc_query("insert into todo_list (`name`,`owner`) values ('$name','$data->name')");
	todo_list_action_();
}
function todo_list_action_new_todo_list() { eval(scg());
	echo "<h1> Create TODO List</h1>";
	sc_bf( 	sc_phpself(),
			"action=new_todo_list_go".$RFS_SITE_DELIMITER,
			"todo_list","","id","","","",50,"New TODO List" );
}		   

include("footer.php");
?>