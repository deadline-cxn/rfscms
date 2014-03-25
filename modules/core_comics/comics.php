<?

chdir("../../");
include ("header.php");
echo "<h1>Comics </h1>";
echo "<table border=0 width=800 cellspacing=0 cellpadding=0><tr><td class=contenttd>";

if($give_file == "comics") {
    if (empty($data->name))
        echo "<p>No...</p>\n";
    else {
        echo "<p> Uploading files... </p>\n";
        echo $_FILES['userfile']['name'];
        $uploadFile = "$RFS_SITE_PATH/images/comics/" . $_FILES['userfile']['name'];
        $oldname = $_FILES['userfile']['name'];
        if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadFile)) {
            system("chmod 755 $uploadFile");
            $error = "File is valid, and was successfully uploaded. ";
            $httppath = "$RFS_SITE_URL/images/comics/" . $_FILES['userfile']['name'];
            echo "<p>File stored as [<a href=\"$httppath\" target=\"_blank\">$httppath</a>]</p>\n";
        } else {
            $error = "File upload error!";
            echo "File upload error! [\n";
            echo $_FILES['userfile']['name'] . "][" . $_FILES['userfile']['error'] . "][" .
                $_FILES['userfile']['tmp_name'] . "][" . $uploadFile . "]\n";
        }
        if (!$error)
            $error .= "No files have been selected for upload";
        echo "<P>Status: [$error]</P>\n";
    }

    $pan = "panel$panel";
    lib_mysql_query("update `comics_pages` set `$pan`='$httppath' where `pid`='$pid'");
    $action = "defpage";
}

function renumber_pages($id) {
    $comic = mysql_fetch_object(lib_mysql_query("select * from `comics` where `id`='$id'"));
    $res = lib_mysql_query("select * from `comics_pages` where `parent`='$comic->id' order by `page` asc");
    $npages = mysql_num_rows($res);
    for ($i = 0; $i < $npages; $i++) {
        $newpage = $i + 1;
        $page = mysql_fetch_object($res);
        lib_mysql_query("update `comics_pages` set `page`='$newpage' where `pid`='$page->pid'");
    }
}

function template_mini_preview($id) {
    eval(lib_rfs_get_globals());
    $template = mysql_fetch_array(lib_mysql_query("select * from `comics_page_templates` where `id`='$id'"));
    echo "<table border=0><tr><td class=contenttd>&nbsp;";
    for ($i = 0; $i < $template['panels']; $i++) {
        $var = "panel" . ($i + 1) . "_x";
        $x = $template[$var];
        if ($x)
            $x = $x / 6;

        $var = "panel" . ($i + 1) . "_y";
        $y = $template[$var];
        if ($y)
            $y = $y / 6;

        $var = "panel" . ($i + 1) . "_l";
        $l = $template[$var];

        echo "<img src=\"$RFS_SITE_URL/images/comics_page_bkg.gif\" width=$x height=$y>&nbsp;";
        if ($l == "yes")
            echo "<br>&nbsp;";
    }

    echo "</td></tr></table>";
}

function template_full_preview($id) {
    eval(lib_rfs_get_globals());
    $template = mysql_fetch_array(lib_mysql_query("select * from `comics_page_templates` where `id`='$id'"));
    echo "<table border=0><tr><td class=contenttd>";
    for ($i = 0; $i < $template['panels']; $i++) {
        $var = "panel" . ($i + 1) . "_x";
        $x = $template[$var];
        $var = "panel" . ($i + 1) . "_y";
        $y = $template[$var];
        $var = "panel" . ($i + 1) . "_l";
        $l = $template[$var];
        echo "<img src=\"$RFS_SITE_URL/images/comics_page_bkg.gif\" width=$x height=$y>&nbsp;";
        if ($l == "yes")
            echo "<br>";
    }
    echo "</td></tr></table>";
}

function getcomic($title, $volume, $issue) {
    $res = mysql_fetch_object(lib_mysql_query("select * from `comics` where `title`='$title' and `volume`='$volume' and `issue`='$issue'"));
    return $res;
}

if (lib_access_check("comics", "create")) {
    if ($action == "newcomic") {
        echo "<h1>Add a new comic</h1>";
        echo "<table border=0>";
        echo "<form enctype=application/x-www-form-URLencoded method=post action=$RFS_SITE_URL/modules/core_comics/comics.php>";
        echo "<input type=hidden name=action value=newcomic2>";
        echo "<tr><td class=contenttd>Title of comic</td><td class=contenttd><input name=title></td></tr>";
        echo "<tr><td class=contenttd>Volume        </td><td class=contenttd><input name=volume></td></tr>";
        echo "<tr><td class=contenttd>Issue         </td><td class=contenttd><input name=issue></td></tr>";
        echo "<tr><td class=contenttd>              </td><td class=contenttd><input type=submit name=Go value=Go></td></tr>";
        echo "</form></table>";
        include ("footer.php");
        exit;
    }

    if ($action == "newcomic2") {
        echo "<h1>Add a new comic</h1>";

        $res = lib_mysql_query("select * from `comics` where `title`='$title' and `volume`='$volume' and `issue`='$issue'");
        if (mysql_num_rows($res)) {
            $comic = mysql_fetch_object($res);
            echo "<p>That title, volume, and issue already exists!<br>";
            echo "[<a href=$RFS_SITE_URL/modules/core_comics/comics.php?action=editcomic&id=$comic->id>Edit $title vol. $volume issue $issue</a>]";
            echo "</p>";
            include ("footer.php");
            exit;
        }
        $time = date("Y-m-d H:i:s");
        lib_mysql_query("INSERT INTO `comics` (`title`, `volume`, `issue`, `published`,`time`,`author`)
                                VALUES ('$title', '$volume', '$issue', 'no','$time','$data->id');");
        echo "<p>New comic [$title vol. $volume issue $issue] created successfully!</p>";
        $action = "editcomic";
    }

    if ($action == "addpagetemplatego") {
        echo "<h1>Add a new comic page template</h1>";
        echo "Template name: $name<br>";
        lib_mysql_query("insert into `comics_page_templates` (`name`) VALUES ('$name');");
        $action = "editpagetemplatego";
    }

    if ($action == "addpagetemplate") {
        echo "<h1>Add a new comic page template</h1>";
        echo "<table border=0>";
        echo "<form enctype=application/x-www-form-URLencoded method=post action=$RFS_SITE_URL/modules/core_comics/comics.php>";
        echo "<input type=hidden name=action value=addpagetemplatego>";
        echo "<tr><td class=contenttd>Name of template</td><td class=contenttd><input name=name></td></tr>";
        echo "<tr><td class=contenttd>Panels</td><td class=contenttd>";
        echo "<select name=panels>";
        for ($i = 1; $i < 9; $i++)
            echo "<option>$i";
        echo "</select>";
        echo "</td></tr>";
        for ($i = 1; $i < 9; $i++) {
            echo "<tr><td class=contenttd>Panel $i Width</td><td class=contenttd>";
            $var = "panel" . $i . "_x";
            echo "<input name=\"$var\">";
            echo "</td></tr>";
            echo "<tr><td class=contenttd>Panel $i Height</td><td class=contenttd>";
            $var = "panel" . $i . "_y";
            echo "<input name=\"$var\">";
            echo "</td></tr>";
            echo "<tr><td class=contenttd>Linefeed after Panel $i</td><td class=contenttd>";
            $var = "panel" . $i . "_l";
            echo "<select name=\"$var\">";
            echo "<option>no<option>yes";
            echo "</select>";
            echo "</td></tr>";
        }
        echo "<tr><td class=contenttd></td><td class=contenttd><input type=submit name=Go value=Go></td></tr>";
        echo "</form></table>";
        include ("footer.php");
        exit;

    }

    if ($action == "delpagetemplatego") {
        $template = mysql_fetch_object(lib_mysql_query("select * from `comics_page_templates` where `id`='$tid'"));
        lib_mysql_query("delete from `comics_page_templates` where `id`='$tid'");
        echo "$template->name template removed.<br>";
    }

    if ($action == "delpagetemplate") {
        $template = mysql_fetch_object(lib_mysql_query("select * from `comics_page_templates` where `id`='$tid'"));
        template_mini_preview($tid);
        echo "<table border=\"0\"><tr><td class=\"lib_forms_warning\"><center>" .
            lib_string_convert_smiles(":X") . "\n";
        echo "<br>WARNING:<br>The template $template->name will be completely removed are you sure?</center>\n";
        echo "</td></tr></table>\n";
        echo "<table><tr><td class=contenttd><form enctype=application/x-www-form-URLencoded action=\"$RFS_SITE_URL/modules/core_comics/comics.php\">\n";
        echo "<input type=hidden name=action value=delpagetemplatego><input type=hidden name=tid value=$tid>\n";
        echo "<input type=\"submit\" name=\"submit\" value=\"Fuck Yeah!\"></form></td>\n";
        echo "<td class=contenttd><form enctype=application/x-www-form-URLencoded action=\"$RFS_SITE_URL/modules/core_comics/comics.php\"><input type=\"submit\" name=\"no\" value=\"No\"></form></td></tr></table>\n";
    }

    if ($action == "editpagetemplatego") {
        echo "<h1>Updating comic page template</h1>";
        $template = mysql_fetch_object(lib_mysql_query("select * from `comics_page_templates` where `name`='$name'"));
        echo "Panels: $panels<br>";
        lib_mysql_query("update `comics_page_templates` set `panels`='$panels' where `id`='$template->id'");
        foreach ($_REQUEST as $key => $value) {
            if ($key == "panel1_x")
                $panel1_x = $value;
            if ($key == "panel2_x")
                $panel2_x = $value;
            if ($key == "panel3_x")
                $panel3_x = $value;
            if ($key == "panel4_x")
                $panel4_x = $value;
            if ($key == "panel5_x")
                $panel5_x = $value;
            if ($key == "panel6_x")
                $panel6_x = $value;
            if ($key == "panel7_x")
                $panel7_x = $value;
            if ($key == "panel8_x")
                $panel8_x = $value;
            if ($key == "panel1_y")
                $panel1_y = $value;
            if ($key == "panel2_y")
                $panel2_y = $value;
            if ($key == "panel3_y")
                $panel3_y = $value;
            if ($key == "panel4_y")
                $panel4_y = $value;
            if ($key == "panel5_y")
                $panel5_y = $value;
            if ($key == "panel6_y")
                $panel6_y = $value;
            if ($key == "panel7_y")
                $panel7_y = $value;
            if ($key == "panel8_y")
                $panel8_y = $value;
            if ($key == "panel1_l")
                $panel1_l = $value;
            if ($key == "panel2_l")
                $panel2_l = $value;
            if ($key == "panel3_l")
                $panel3_l = $value;
            if ($key == "panel4_l")
                $panel4_l = $value;
            if ($key == "panel5_l")
                $panel5_l = $value;
            if ($key == "panel6_l")
                $panel6_l = $value;
            if ($key == "panel7_l")
                $panel7_l = $value;
            if ($key == "panel8_l")
                $panel8_l = $value;
        }

        if (!empty($panel1_x)) {
            echo "Panel 1 Width: $panel1_x<br>";
            lib_mysql_query("update `comics_page_templates` set `panel1_x`='$panel1_x' where `id`='$template->id'");
        }
        if (!empty($panel1_y)) {
            echo "Panel 1 Height: $panel1_y<br>";
            lib_mysql_query("update `comics_page_templates` set `panel1_y`='$panel1_y' where `id`='$template->id'");
        }
        if (!empty($panel1_l)) {
            echo "Linefeed after Panel 1: $panel1_l<br>";
            lib_mysql_query("update `comics_page_templates` set `panel1_l`='$panel1_l' where `id`='$template->id'");
        }

        if (!empty($panel2_x)) {
            echo "Panel 2 Width: $panel2_x<br>";
            lib_mysql_query("update `comics_page_templates` set `panel2_x`='$panel2_x' where `id`='$template->id'");
        }
        if (!empty($panel2_y)) {
            echo "Panel 2 Height: $panel2_y<br>";
            lib_mysql_query("update `comics_page_templates` set `panel2_y`='$panel2_y' where `id`='$template->id'");
        }
        if (!empty($panel2_l)) {
            echo "Linefeed after Panel 2: $panel2_l<br>";
            lib_mysql_query("update `comics_page_templates` set `panel2_l`='$panel2_l' where `id`='$template->id'");
        }

        if (!empty($panel3_x)) {
            echo "Panel 3 Width: $panel3_x<br>";
            lib_mysql_query("update `comics_page_templates` set `panel3_x`='$panel3_x' where `id`='$template->id'");
        }
        if (!empty($panel3_y)) {
            echo "Panel 3 Height: $panel3_y<br>";
            lib_mysql_query("update `comics_page_templates` set `panel3_y`='$panel3_y' where `id`='$template->id'");
        }
        if (!empty($panel3_l)) {
            echo "Linefeed after Panel 3: $panel3_l<br>";
            lib_mysql_query("update `comics_page_templates` set `panel3_l`='$panel3_l' where `id`='$template->id'");
        }

        if (!empty($panel4_x)) {
            echo "Panel 4 Width: $panel4_x<br>";
            lib_mysql_query("update `comics_page_templates` set `panel4_x`='$panel4_x' where `id`='$template->id'");
        }
        if (!empty($panel4_y)) {
            echo "Panel 4 Height: $panel4_y<br>";
            lib_mysql_query("update `comics_page_templates` set `panel4_y`='$panel4_y' where `id`='$template->id'");
        }
        if (!empty($panel4_l)) {
            echo "Linefeed after Panel 4: $panel4_l<br>";
            lib_mysql_query("update `comics_page_templates` set `panel4_l`='$panel4_l' where `id`='$template->id'");
        }

        if (!empty($panel5_x)) {
            echo "Panel 5 Width: $panel5_x<br>";
            lib_mysql_query("update `comics_page_templates` set `panel5_x`='$panel5_x' where `id`='$template->id'");
        }
        if (!empty($panel5_y)) {
            echo "Panel 5 Height: $panel5_y<br>";
            lib_mysql_query("update `comics_page_templates` set `panel5_y`='$panel5_y' where `id`='$template->id'");
        }
        if (!empty($panel5_l)) {
            echo "Linefeed after Panel 5: $panel5_l<br>";
            lib_mysql_query("update `comics_page_templates` set `panel5_l`='$panel5_l' where `id`='$template->id'");
        }

        if (!empty($panel6_x)) {
            echo "Panel 6 Width: $panel6_x<br>";
            lib_mysql_query("update `comics_page_templates` set `panel6_x`='$panel6_x' where `id`='$template->id'");
        }
        if (!empty($panel6_y)) {
            echo "Panel 6 Height: $panel6_y<br>";
            lib_mysql_query("update `comics_page_templates` set `panel6_y`='$panel6_y' where `id`='$template->id'");
        }
        if (!empty($panel6_l)) {
            echo "Linefeed after Panel 6: $panel6_l<br>";
            lib_mysql_query("update `comics_page_templates` set `panel6_l`='$panel6_l' where `id`='$template->id'");
        }

        if (!empty($panel7_x)) {
            echo "Panel 7 Width: $panel7_x<br>";
            lib_mysql_query("update `comics_page_templates` set `panel7_x`='$panel7_x' where `id`='$template->id'");
        }

        if (!empty($panel7_y)) {
            echo "Panel 7 Height: $panel7_y<br>";
            lib_mysql_query("update `comics_page_templates` set `panel7_y`='$panel7_y' where `id`='$template->id'");
        }
        if (!empty($panel7_l)) {
            echo "Linefeed after Panel 7: $panel7_l<br>";
            lib_mysql_query("update `comics_page_templates` set `panel7_l`='$panel7_l' where `id`='$template->id'");
        }

        if (!empty($panel8_x)) {
            echo "Panel 8 Width: $panel8_x<br>";
            lib_mysql_query("update `comics_page_templates` set `panel8_x`='$panel8_x' where `id`='$template->id'");
        }
        if (!empty($panel8_y)) {
            echo "Panel 8 Height: $panel8_y<br>";
            lib_mysql_query("update `comics_page_templates` set `panel8_y`='$panel8_y' where `id`='$template->id'");
        }
        if (!empty($panel8_l)) {
            echo "Linefeed after Panel 8: $panel8_l<br>";
            lib_mysql_query("update `comics_page_templates` set `panel8_l`='$panel8_l' where `id`='$template->id'");
        }
        echo "<p>Mini preview:</p>";
        template_mini_preview($template->id);
    }

    if ($action == "editpagetemplate") {
        $template = mysql_fetch_array(lib_mysql_query("select * from `comics_page_templates` where `id`='$tid'"));
        echo "<h1>Edit a comic page template</h1>";
        echo "<table border=0>";
        echo "<form enctype=application/x-www-form-URLencoded method=post action=$RFS_SITE_URL/modules/core_comics/comics.php>";
        echo "<input type=hidden name=action value=editpagetemplatego>";
        echo "<tr><td class=contenttd>Name of template</td><td class=contenttd>" . $template['name'] .
            "</td></tr>";
        echo "<input type=hidden name=name value=\"" . $template['name'] . "\">";
        echo "<tr><td class=contenttd>Panels</td><td class=contenttd>";
        echo "<select name=panels>";
        echo "<option>" . $template['panels'];
        for ($i = 1; $i < 9; $i++)
            echo "<option>$i";
        echo "</select>";
        echo "</td></tr>";
        for ($i = 1; $i < 9; $i++) {
            echo "<tr><td class=contenttd>Panel $i Width</td><td class=contenttd>";
            $var = "panel" . $i . "_x";
            echo "<input name=\"$var\" value=\"" . $template[$var] . "\">";
            echo "</td></tr>";
            echo "<tr><td class=contenttd>Panel $i Height</td><td class=contenttd>";
            $var = "panel" . $i . "_y";
            echo "<input name=\"$var\" value=\"" . $template[$var] . "\">";
            echo "</td></tr>";
            echo "<tr><td class=contenttd>Linefeed after Panel $i</td><td class=contenttd>";
            $var = "panel" . $i . "_l";
            echo "<select name=\"$var\"><option>" . $template[$var];
            echo "<option>no<option>yes";
            echo "</select>";
            echo "</td></tr>";
        }

        echo "<tr><td class=contenttd></td><td class=contenttd><input type=submit name=Go value=Go></td></tr>";
        echo "</form></table>";
        include ("footer.php");
        exit;
    }

    if ($action == "previewtemplatefull") {
        template_full_preview($tid);
        $action = "addpage";
    }

    if ($action == "addpagego") {
        $comic = mysql_fetch_object(lib_mysql_query("select * from `comics` where `id`='$id'"));
        $template = mysql_fetch_object(lib_mysql_query("select * from `comics_page_templates` where `id`='$tid'"));
        $res = lib_mysql_query("select * from `comics_pages` where `parent`='$comic->id' order by `page` desc limit 1");
        $page = mysql_fetch_object($res);
        $pageno = $page->page + 1;
        echo "<h1>Add a page to comic $comic->title</h1>";
        lib_mysql_query("insert into `comics_pages` (`parent`,`template`, `page`) VALUES ('$id','$tid','$pageno')");
        echo "Page created & $template->name template applied to page<br>";
        $action = "editcomic";
    }

    if ($action == "addpage") {
        $comic = mysql_fetch_object(lib_mysql_query("select * from `comics` where `id`='$id'"));
        echo "<h1>Adding a page to comic $comic->title</h1>";
        $res = lib_mysql_query("select * from `comics_page_templates`");
        $ntemplates = mysql_num_rows($res);
        echo "<p>There are $ntemplates templates defined...</p>";
        for ($i = 0; $i < $ntemplates; $i++) {
            $template = mysql_fetch_object($res);
            echo "<table border=0><tr><td class=contenttd>";
            template_mini_preview($template->id);
            echo "</td><td class=contenttd>";
            echo "Template: $template->name<br>";
            echo "Panels: $template->panels<br>";
            echo "[<a href=\"$RFS_SITE_URL/modules/core_comics/comics.php?action=addpagego&id=$comic->id&tid=$template->id\">Use $template->name Template</a>]<br>";
            echo "[<a href=\"$RFS_SITE_URL/modules/core_comics/comics.php?action=previewtemplatefull&tid=$template->id&id=$id\">Full size preview</a>]<br>";
            echo "[<a href=\"$RFS_SITE_URL/modules/core_comics/comics.php?action=editpagetemplate&tid=$template->id\">Edit template</a>]<br>";
            echo "[<a href=\"$RFS_SITE_URL/modules/core_comics/comics.php?action=delpagetemplate&tid=$template->id\">Delete template</a>]";
            echo "</td></tr>";
            echo "</table>";
        }
        echo "[<a href=\"$RFS_SITE_URL/modules/core_comics/comics.php?action=addpagetemplate\">Add new template</a>]<br>";
        include ("footer.php");
        exit;
    }

    if ($action == "clearpanel") {
        $pan = "panel$panel";
        lib_mysql_query("update `comics_pages` set `$pan`='' where `pid`='$pid'");
        $action = "defpage";
    }

    if ($action == "definepanel") {
        $pan = "panel$panel";
        lib_mysql_query("update `comics_pages` set `$pan`='$userfile' where `pid`='$pid'");
        $action = "defpage";
    }

    if ($action == "defpage") {
        $page = mysql_fetch_object(lib_mysql_query("select * from `comics_pages` where `pid`='$pid'"));
        $pagea = mysql_fetch_array(lib_mysql_query("select * from `comics_pages` where `pid`='$pid'"));
        $template = mysql_fetch_array(lib_mysql_query("select * from `comics_page_templates` where `id`='$page->template'"));
        echo "<table border=0><tr><td class=contenttd>";
        echo "<table border=0><tr>";
        for ($i = 0; $i < $template['panels']; $i++) {
            $var = "panel" . ($i + 1) . "_x";
            $x = $template[$var];
            $var = "panel" . ($i + 1) . "_y";
            $y = $template[$var];
            $var = "panel" . ($i + 1) . "_l";
            $l = $template[$var];
            $var = "panel" . ($i + 1);
            $url = $pagea[$var];
            if (empty($url))
                $url = "$RFS_SITE_URL/images/comics_page_bkg.gif";
            echo "<td class=contenttd background=\"$url\" width=$x height=$y align=center>";
            echo "<p>$x x $y</p>";
            echo "<table border=0>\n";
            echo "<form  enctype=\"multipart/form-data\" action=\"$RFS_SITE_URL/modules/core_comics/comics.php\" method=\"post\">\n";
            echo "<input type=hidden name=action value=definepanel>\n";
            echo "<input type=hidden name=pid value=$page->pid>";
            echo "<input type=hidden name=panel value=" . ($i + 1) . ">";
            echo "<tr><td class=contenttd><input name=\"userfile\"> </td></tr>\n";
            echo "<tr><td class=contenttd><input type=\"submit\" name=\"submit\" value=\"URL\"></td></tr>\n";
            echo "</form>\n";
            echo "</table>\n";
            echo "<table border=0>\n";
            echo "<form enctype=\"multipart/form-data\" action=\"$RFS_SITE_URL/modules/core_comics/comics.php\" method=\"post\">\n";
            echo "<input type=hidden name=give_file value=comics>\n";
            echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"99900000\">";
            echo "<input type=hidden name=local value=\"images/comics\">";
            echo "<input type=hidden name=hidden value=yes>\n";
            echo "<input type=hidden name=pid value=$page->pid>";
            echo "<input type=hidden name=panel value=" . ($i + 1) . ">";
            echo "<tr><td class=contenttd><input name=\"userfile\" type=\"file\"> </td></tr>\n";
            echo "<tr><td class=contenttd><input type=\"submit\" name=\"submit\" value=\"Upload!\"></td></tr>\n";
            echo "</form>\n";
            echo "</table>\n";
            echo "<table border=0>\n";
            echo "<form  enctype=\"multipart/form-data\" action=\"$RFS_SITE_URL/modules/core_comics/comics.php\" method=\"post\">\n";
            echo "<input type=hidden name=action value=clearpanel>\n";
            echo "<input type=hidden name=pid value=$page->pid>";
            echo "<input type=hidden name=panel value=" . ($i + 1) . ">";
            echo "<tr><td class=contenttd><input type=\"submit\" name=\"submit\" value=\"Clear Panel\"></td></tr>\n";
            echo "</form>\n";
            echo "</table>\n";
            echo "</td>";
            if ($l == "yes")
                echo "</tr></table><table border=0><tr>";
        }
        echo "</tr></table>";
        echo "</td></tr></table>";
    }

    if ($action == "delpage") {
        $page = mysql_fetch_object(lib_mysql_query("select * from `comics_pages` where `pid`='$pid'"));
        rfs_comics_page_mini_preview($pid);
        echo "<table border=\"0\"><tr><td class=\"lib_forms_warning\"><center>" .
            lib_string_convert_smiles(":X") . "\n";
        echo "<br>WARNING:<br>The page will be completely removed are you sure?</center>\n";
        echo "</td></tr></table>\n";
        echo "<table><tr><td class=contenttd><form enctype=application/x-www-form-URLencoded action=\"$RFS_SITE_URL/modules/core_comics/comics.php\">\n";
        echo "<input type=hidden name=action value=delpagego><input type=hidden name=pid value=$pid>\n";
        echo "<input type=\"submit\" name=\"submit\" value=\"Fuck Yeah!\"></form></td>\n";
        echo "<td class=contenttd><form enctype=application/x-www-form-URLencoded action=\"$RFS_SITE_URL/modules/core_comics/comics.php\"><input type=\"submit\" name=\"no\" value=\"No\"></form></td></tr></table>\n";
    }

    if ($action == "delpagego") {
        echo "<p>Deleting page $pid</p>";
        $page = mysql_fetch_object(lib_mysql_query("select * from `comics_pages` where `pid`='$pid'"));
        rfs_comics_page_mini_preview($pid);
        lib_mysql_query("delete from `comics_pages` where `pid`='$pid'");
        renumber_pages($page->parent);
        echo "<p>Page deleted...</p>";
        $action = "editcomic";
    }

    if ($action == "movepageup") {
        if ($page < 2) {
            echo "<p>You can't move that page up</p>";
        } else {
            $comic = mysql_fetch_object(lib_mysql_query("select * from `comics` where `id`='$id'"));
            $pagea = $page - 1;
            $pageabove = mysql_fetch_object(lib_mysql_query("select * from `comics_pages` where `parent`='$id' and `page`='$pagea'"));
            $pagebelow = mysql_fetch_object(lib_mysql_query("select * from `comics_pages` where `parent`='$id' and `page`='$page'"));
            $pagena = $pageabove->page;
            $pagenb = $pagebelow->page;
            lib_mysql_query("update `comics_pages` set `page`='$pagena' where `pid`='$pagebelow->pid'");
            lib_mysql_query("update `comics_pages` set `page`='$pagenb' where `pid`='$pageabove->pid'");
        }
        $action = "editcomic";
    }

    if ($action == "movepagedown") {
        $comic = mysql_fetch_object(lib_mysql_query("select * from `comics` where `id`='$id'"));
        $totalpages = mysql_num_rows(lib_mysql_query("select * from `comics_pages` where `parent`='$id'"));
        if ($page > ($totalpages - 1)) {
            echo "<p>You can't move that page down</p>";
        } else {
            $comic = mysql_fetch_object(lib_mysql_query("select * from `comics` where `id`='$id'"));
            $pagea = $page + 1;
            $pageabove = mysql_fetch_object(lib_mysql_query("select * from `comics_pages` where `parent`='$id' and `page`='$pagea'"));
            $pagebelow = mysql_fetch_object(lib_mysql_query("select * from `comics_pages` where `parent`='$id' and `page`='$page'"));
            $pagena = $pageabove->page;
            $pagenb = $pagebelow->page;
            lib_mysql_query("update `comics_pages` set `page`='$pagena' where `pid`='$pagebelow->pid'");
            lib_mysql_query("update `comics_pages` set `page`='$pagenb' where `pid`='$pageabove->pid'");
        }
        $action = "editcomic";
    }

    if ($action == "editcomic") {
        $comic = mysql_fetch_object(lib_mysql_query("select * from `comics` where `id`='$id'"));
        echo "<h1>Editing Comic: $comic->title vol. $comic->volume issue $comic->issue</h1>";
        $res = lib_mysql_query("select * from `comics_pages` where `parent`='$comic->id' order by `page` asc");
        $npages = mysql_num_rows($res);
        echo "<p>There are $npages pages defined...</p>";
        for ($i = 0; $i < $npages; $i++) {
            $page = mysql_fetch_object($res);
            echo "<table border=0><tr><td class=contenttd>";
            rfs_comics_page_mini_preview($page->pid);
            echo "</td><td class=contenttd>";
            echo "Page: $page->page<br>";
            $template = mysql_fetch_object(lib_mysql_query("select * from `comics_page_templates` where `id`='$page->template'"));
            echo "Template: $template->name<br>";
            echo "[<a href=\"$RFS_SITE_URL/modules/core_comics/comics.php?action=movepageup&id=$comic->id&page=$page->page\">Move up</a>]<br>";
            echo "[<a href=\"$RFS_SITE_URL/modules/core_comics/comics.php?action=movepagedown&id=$comic->id&page=$page->page\">Move down</a>]<br>";
            echo "[<a href=\"$RFS_SITE_URL/modules/core_comics/comics.php?action=defpage&pid=$page->pid\">Define panels</a>]<br>";
            echo "[<a href=\"$RFS_SITE_URL/modules/core_comics/comics.php?action=delpage&pid=$page->pid\">Delete this page</a>]";
            echo "</td></tr></table>";
        }
        echo "[<a href=\"$RFS_SITE_URL/modules/core_comics/comics.php?action=addpage&id=$comic->id\">Add new page</a>]<br>";
        echo "<br>";
    }

    if ($action == "publish") {
        lib_mysql_query("update `comics` set `published`='yes' where `id`='$id'");
    }

    if ($action == "unpublish") {
        lib_mysql_query("update `comics` set `published`='no' where `id`='$id'");
    }
}

if ($action == "viewcomic") {
    $comic = mysql_fetch_object(lib_mysql_query("select * from `comics` where `id`='$id'"));
    $pres = lib_mysql_query("select * from `comics_pages` where `parent`='$id' order by page asc");
    $page = mysql_fetch_object($pres);
    $npages = mysql_num_rows($pres);
    $pagenumber=$_REQUEST['pagenumber'];
    
    if(!empty($pagenumber)) {
        $press = lib_mysql_query("select * from `comics_pages` where `parent`='$id' and `page`='$pagenumber'");
        $page = mysql_fetch_object($press);
    }
    
    echo "<center>";
    if(empty($pagenumber)) $pagenumber = 1;
    echo "<h1>$comic->title vol. $comic->volume issue $comic->issue page $pagenumber ";

    if ($npages > 1) {
        if ($page->page > 1)
            echo "[<a href=$RFS_SITE_URL/modules/core_comics/comics.php?action=viewcomic&id=$id&pagenumber=1>Page 1</a>]";
        if ($page->page > 1)
            echo "[<a href=$RFS_SITE_URL/modules/core_comics/comics.php?action=viewcomic&id=$id&pagenumber=" . ($page->page - 1) . ">Prev</a>]";
        if ($page->page < $npages)
            echo "[<a href=$RFS_SITE_URL/modules/core_comics/comics.php?action=viewcomic&id=$id&pagenumber=" . ($page->page + 1) . ">Next</a>]";
        if ($page->page < $npages)
            echo "[<a href=$RFS_SITE_URL/modules/core_comics/comics.php?action=viewcomic&id=$id&pagenumber=$npages>Last Page</a>]";
    }
    echo "</h1>";
    page_full_view($page->pid);
    if ($npages > 1) {
        if ($page->page > 1)
            echo "[<a href=$RFS_SITE_URL/modules/core_comics/comics.php?action=viewcomic&id=$id&pagenumber=1>Page 1</a>]";
        if ($page->page > 1)
            echo "[<a href=$RFS_SITE_URL/modules/core_comics/comics.php?action=viewcomic&id=$id&pagenumber=" . ($page->page - 1) . ">Prev</a>]";
        if ($page->page < $npages)
            echo "[<a href=$RFS_SITE_URL/modules/core_comics/comics.php?action=viewcomic&id=$id&pagenumber=" . ($page->page + 1) . ">Next</a>]";
        if ($page->page < $npages)
            echo "[<a href=$RFS_SITE_URL/modules/core_comics/comics.php?action=viewcomic&id=$id&pagenumber=$npages>Last Page</a>]";
    }
    echo "</center>";
} else {
    echo "<table border=0 cellspacing=0 cellpadding=1 width=100%><tr><td class=contenttd>";
    echo "<table width=100% border=0><tr>";
    echo "<td valign=top class=contenttd>";

    echo "<table border=0 width=100%><tr><td align=center class=contenttd>";
    $res = lib_mysql_query("select * from comics where `published`='yes' order by time desc limit 1");
    $numc = mysql_num_rows($res);
    $comic = mysql_fetch_object($res);
    $page = mysql_fetch_object(lib_mysql_query("select * from `comics_pages` where `parent`='$comic->id' order by `page` asc limit 1"));
    page_full_view($page->pid); // ,"$RFS_SITE_URL/modules/core_comics/comics.php?action=viewcomic&id=$comic->id");
    echo "<a href=\"$RFS_SITE_URL/modules/core_comics/comics.php?action=viewcomic&id=$comic->id\" class=\"a_cat\">$comic->title vol. $comic->volume issue $comic->issue</a>";
    echo "<br>";
    echo "</td></tr></table>";
    echo "<p align=right><a href=$RFS_SITE_URL/modules/core_comics/comics.php class=a_cat>more...</a></p>";
    echo "</td></tr></table>";
    echo "</td></tr></table>";

}

echo "<table border=0 width=100%><tr><td valign=top width=440 class=contenttd>";

$res = lib_mysql_query("select * from comics where `published`='yes' order by time desc");
$numc = mysql_num_rows($res);

echo "<p> $numc Comics available...</p>";

for ($i = 0; $i < $numc; $i++) {
    $comic = mysql_fetch_object($res);
    echo "<a href=\"$RFS_SITE_URL/modules/core_comics/comics.php?action=viewcomic&id=$comic->id\">$comic->title vol. $comic->volume issue $comic->issue</a>";
    if(lib_access_check("comics","unpublish"))
        echo "[<a href=\"$RFS_SITE_URL/modules/core_comics/comics.php?action=unpublish&id=$comic->id\">Unpublish</a>]";
    echo "<br>";
}

if(lib_access_check("comics","admin")) {
    echo "<h1>Comics Administration</h1>";
    echo "[<a href=$RFS_SITE_URL/modules/core_comics/comics.php?action=newcomic>Add new comic</a>]<br>";
    echo "<h1>Unpublished Comics:</h1>";
    $res = lib_mysql_query("select * from comics where `published`='no' order by time desc");
    $numc = mysql_num_rows($res);
    for ($i = 0; $i < $numc; $i++) {
        $comic = mysql_fetch_object($res);
        
        if(lib_access_check("comics","edit"))
        echo "[<a href=\"$RFS_SITE_URL/modules/core_comics/comics.php?action=editcomic&id=$comic->id\">Edit $comic->title vol. $comic->volume issue $comic->issue</a>]";

        if(lib_access_check("comics","publish"))
        echo "[<a href=\"$RFS_SITE_URL/modules/core_comics/comics.php?action=publish&id=$comic->id\">Publish</a>]<br>";
    }
}

echo "</td></tr></table>";
echo "</td></tr></table>";

include ("footer.php");

?>
