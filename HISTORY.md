History File:

3.3.4 ( ) June 12, 2014:
- Added debug output in lib_mysql_query()
- Fixed some sql database issues
- Added USTREAM.tv support for videos url submissions
- Added file url submission enhancements
- Added remote image cache function

3.3.3 (1265) June 11, 2014:
- Fixed up arrangement panel editing
- Fixed up TODO List bugs
- Fixed AJAX bug in lib_ajax having to do with SELECT input method

3.3.2 (1254) June 10, 2014:
- Fixed wiki bug not showing linked pages
- Added $RFS_SITE_WIKI_SHOW_LINKED_PAGES (bool - [on or off])
- Changed extended file information to use unzip instead of 7zip

3.3.2 (1246) June 7, 2014:
- Added Vimeo submission support to videos module
- Added FunnyOrDie submission support to videos module
- Added USTREAM submission support to videos module
- Added Twitch.tv submission support to video module
- Removed drop down selector to choose what website the video should be pulled from, it now auto-recognizes from the url you enter
- If the url is not, youtube, liveleak or vimeo, it tries to read in generic meta tags from the url you enter  

3.3.1 (1245) June 6, 2014:
- Added liveleak submission to videos module
- fixed some video module bugs

3.3.1 (1223) May 28, 2014:
- Added $RFS_SITE_WIKI_TOP_BUTTONS (bool)
- Modified t.css for all themes to update private message formatting

3.3.1 (1221) May 28, 2014:
- Updated tools folder

3.3.1 (1206):
- Added database security check to install.php;
  The script will terminate if it discovers a defined database.
  The database must be cleared  or config.php manually in order for the script to complete.
- Fixed smilies bug that prevented new smilies from being created

3.3.1 (1204):
- Added ban management into admin interface

3.3.1 (1203):
- Fixed access method bug
- Started tracking with HISTORY.md

