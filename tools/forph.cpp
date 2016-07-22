#include <sys/types.h>
#include <sys/stat.h>
#include <unistd.h>
#include <time.h>
#include <stdlib.h>
#include <stdio.h>
#include <string.h>
#include <vector>
#include <string>
#include <mysql.h>
#include <dirent.h>
using namespace std;

// define your database config in this file
// #include "db_config.h"
// example:
<<<<<<< HEAD

#define DB_HOST "127.0.0.1"
#define DB_USER "root"
#define DB_PASS "!QAZ2wsx"
#define DB_DB   "sethcoder.com"

#define RFSCMS_FORPH_VER "1.0.2"
=======
// #define DB_HOST "localhost"
// #define DB_USER "rfs_cms_user"
// #define DB_PASS "password"
// #define DB_DB   "rfs_cms_db"

#define RFSCMS_FORPH_VER "1.2.0"
>>>>>>> 8c19cfef5d6d5f8254da19a7f52c1c267da6ec39

MYSQL *con;
vector<string> files;

void finish_with_error(MYSQL *con) {
	fprintf(stderr, "%s\n", mysql_error(con));
	mysql_close(con);
	exit(1);
}
bool isdir(char *dir) { struct stat st; if(stat(dir,&st)==-1) return false; if(st.st_mode&S_IFDIR) return true; return false; }
long  filesize(char *file) { struct stat st; stat(file, &st); long size = st.st_size; return size; }
void add_file(char* file, char* filename) {
	char q[1024]; memset(q,0,1024);
	char fout[1024]; memset(fout,0,1024);
	char fnout[1024]; memset(fnout,0,1024);
<<<<<<< HEAD
	// char mysql_error[1024]; memset(mysql_error,0,1024);
	int ms_error=0;
 
	long fsize;

	fsize=filesize(filename);
	mysql_real_escape_string(con, fout,file, strlen(file));
	mysql_real_escape_string(con, fnout, filename, strlen(filename));
	// name, location, submitter, category, hidden, downloads,
	// description, filetype, size, id, time, lastupdate, thumb,
	// version, homepage, owner, platform, os, rating, worksafe,
	// md5, tags, ignore
	sprintf(q,"insert into `files` (`name`, `location`, `submitter`, `category`, `size`,`worksafe`,`hidden`,`time`,`lastupdate`) \
                             values('%s'  , '%s',       'forph',     'unsorted', '%lu' ,'no'      ,'yes'   ,NOW() ,NOW());", fout,fnout,fsize);
//	strcpy(q,"select * from files;");

	ms_error=mysql_query(con,q);

	if (ms_error) { 
        fprintf(stderr, "%s\n", mysql_error(con));
    } //printf("ERROR: [%d] [%s] %s [%lu]\n",mysql_error,fout,fnout,fsize);
=======
	char category[1024]; memset(category,0,1024);
	long fsize;
	
	fsize=filesize(filename);
	mysql_real_escape_string(con, fout,file, strlen(file));
	mysql_real_escape_string(con, fnout, filename, strlen(filename));
// name, location, submitter, category, hidden, downloads,
// description, filetype, size, id, time, lastupdate, thumb,
// version, homepage, owner, platform, os, rating, worksafe,
// md5, tags, ignore
	sprintf(q,"insert into `files` (`name`, `location`, `submitter`, `category`,`size`,`worksafe`,`hidden`,`time`) \
				             values('%s',         '%s', 'forph',     'unsorted', '%lu',      'no',   'yes', CURRENT_TIME);",
					fout,fnout,fsize);
	if(mysql_query(con,q)) printf("ERROR: ===========================================\n%s\n==================================\n %s [%lu]\n",q,fnout,fsize);
>>>>>>> 8c19cfef5d6d5f8254da19a7f52c1c267da6ec39
	else    	       printf("ADDED: %s [%lu]\n",fnout,fsize);
}
bool file_in_db(char *filename) {
	if(files.empty()) {
		if(mysql_query(con, "select location from files")) { finish_with_error(con); }
	        MYSQL_RES *result = mysql_store_result(con);
		MYSQL_ROW row;
    		while((row = mysql_fetch_row(result))) files.push_back(row[0]);
	}
	for(unsigned n=0; n < files.size(); ++n) {
		string x=files.at(n);
		if(!strcmp(x.c_str(),filename)) return true;
	}
	return false;
}
void scan_dir(char *dir) {
//	printf("Scanning [%s]\n",dir);
    if(!strcmp(dir,"files/files_b/black_hole")) return;
    
    printf("Scanning %s\n",dir);
    
    char nfn[1024];
    DIR *dpdf;
    struct dirent *epdf;
    dpdf = opendir(dir);
    if (dpdf != NULL) {
    while (epdf = readdir(dpdf)) {
        if( (strcmp(epdf->d_name,"." )) &&
        (strcmp(epdf->d_name,"..")) &&
    (strcasecmp(epdf->d_name,"desktop.ini")) &&
            (strcasecmp(epdf->d_name,"thumbs.db")) &&
    (strcasecmp(epdf->d_name,"folder.jpg")) ) {
    sprintf(nfn,"%s/%s",dir,(char *)epdf->d_name);
    if(!isdir(nfn)) {
      if(!file_in_db(nfn)) add_file(epdf->d_name, nfn);
    }
        else {
     if(epdf->d_name[0]!='.') scan_dir(nfn);
            }
        }
      }
    }
    closedir(dpdf);
}

int main() {
	int xx=chdir("..");
	con = mysql_init(NULL);
	if(con==NULL) {
		fprintf(stderr, "%s\n", mysql_error(con));
		exit(1);
	}

	printf("RFSCMS Find ORPhan files (%s)\n(MySQL: %s)\n",RFSCMS_FORPH_VER, mysql_get_client_info());

	if(mysql_real_connect(con, DB_HOST, DB_USER, DB_PASS, DB_DB, 0, NULL, 0) == NULL) {
		finish_with_error(con);
	}
	char dir[1024];
	strcpy(dir,"files");
	scan_dir(dir);
	mysql_close(con);
	exit(0);
}
