<?
include_once("include/lib.all.php");

// sc_access_method_add("files", "upload");

sc_database_add("courses","name","text","NOT NULL");
sc_database_add("courses","description","text","NOT NULL");
sc_database_add("courses","image","text","NOT NULL");
sc_database_add("courses","available","text","NOT NULL");


sc_database_add("courses_component","name","text","NOT NULL");
sc_database_add("courses_component","image","text","NOT NULL");


function sc_module_course_list($x) { eval(scg());
    echo "<h2>Courses available</h2>";
    echo "<div class=\"courses_box\">";
    $result=sc_query("select * from courses where available='yes'");
    $num=mysql_num_rows($result);
    for($i=0;$i<$num;$i++) {
        $course=mysql_fetch_object($result);
		
		sc_togglediv_start("course_info$course->id","<a href=\"$RFS_SITE_URL/modules/courses/courses.php?id=$course->id\"> COURSE: $course->id $course->name</a>",1);
		echo "<div class=\"course_info\">";
		echo $course->description;
		echo "</div>";
		sc_togglediv_end();
		
		

    }
    echo "</div>";
}



?>