<?php
if(empty($PHP_SELF)) $PHP_SELF="";
$RFS_SITE_LOGIN_FORM_CODE  = "<script src=\"\$RFS_SITE_URL/include/md5.js\"> </script><form method=post action=\"\$RFS_SITE_URL/login.php\"><input type=hidden name=outpage value=\"\$thispage\"><input type=hidden name=action value=\"logingo\"><table align=right border=0 cellspacing=0 cellwidth=0 cellpadding=0 valign=middle>\n<tr valign=middle>\n<td align=right class=login><font class=slogan>Login</font></td><td class=login><input type=text name=userid size=10 class=\"b4text\"></td><td></td></tr><tr>\n<td align=right class=login><font class=slogan>Password</font></td><td class=login><input type=password name=password size=10 class=\"b4text\"></td>\n<input type=hidden name=outpage value=$PHP_SELF>\n<input type=hidden name=login value=fo_shnizzle>\n<td valign=middle>\n <input type=\"submit\" name=\"Login\" value=\"Login\">\n</td>\n</form>\n<td>\n</td>\n</tr>\n<tr><td> <!--RTAG_FACEBOOK_LOGIN--> </td><td>&nbsp;(<a href=\$RFS_SITE_URL/login.php?action=join&outpage=$PHP_SELF>register</a>)</td></tr></table>\n";


?>
