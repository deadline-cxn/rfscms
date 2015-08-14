History File:

3.3.5 June 13, 2014:
- Fixed pictures finishing page
- Fixed videos finishing page
- Added get remote thumbnail image function for videos
- Fixed todo_list list and task creation

3.3.4 June 12, 2014:
- Added debug output in lib_mysql_query()
- Fixed some sql database issues
- Added USTREAM.tv support for videos url submissions
- Added file url submission enhancements
- Added remote image cache function
- Fixed Video Wall bugs
- Fixed videos thumbnail caching

3.3.3 June 11, 2014:
- Fixed up arrangement panel editing
- Fixed up TODO List bugs
- Fixed AJAX bug in lib_ajax having to do with SELECT input method

3.3.2 June 10, 2014:
- Fixed wiki bug not showing linked pages
- Added $RFS_SITE_WIKI_SHOW_LINKED_PAGES (bool - [on or off])
- Changed extended file information to use unzip instead of 7zip

3.3.2 June 7, 2014:
- Added Vimeo submission support to videos module
- Added FunnyOrDie submission support to videos module
- Added USTREAM submission support to videos module
- Added Twitch.tv submission support to video module
- Removed drop down selector to choose what website the video should be pulled from, it now auto-recognizes from the url you enter
- If the url is not, youtube, liveleak or vimeo, it tries to read in generic meta tags from the url you enter  

3.3.1 June 6, 2014:
- Added liveleak submission to videos module
- fixed some video module bugs
- Added $RFS_SITE_WIKI_TOP_BUTTONS (bool)
- Modified t.css for all themes to update private message formatting
- Updated tools folder
- Added database security check to install.php;
  The script will terminate if it discovers a defined database.
  The database must be cleared  or config.php manually in order for the script to complete.
- Fixed smilies bug that prevented new smilies from being created
- Added ban management into admin interface
- Fixed access method bug
- Started tracking with HISTORY.md

