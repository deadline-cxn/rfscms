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
MYSQL *con;
vector<string> files;
void finish_with_error(MYSQL *con) { fprintf(stderr, "%s\n", mysql_error(con)); mysql_close(con); exit(1); }
bool isdir(char *dir) { struct stat st; if(stat(dir,&st)==-1) return false; if(st.st_mode&S_IFDIR) return true; return false; }
bool file_in_db(char *filename) {
	if(files.empty()) {
		if(mysql_query(con, "select location from files")) { finish_with_error(con); }
	        MYSQL_RES *result = mysql_store_result(con);
		MYSQL_ROW row;
    		while((row = mysql_fetch_row(result))) {
			files.push_back(row[0]);
	        }
	}
	for(unsigned n=0; n < files.size(); ++n) {
		string x=files.at(n);
		if(!strcmp(x.c_str(),filename)) return true;
	}
	return false;
}
void scan_dir(char *dir) {
	//	printf("Scanning [%s]\n",dir);
	char nfn[1024];
	DIR *dpdf;
        struct dirent *epdf;
        dpdf = opendir(dir);
        if (dpdf != NULL) {
        while (epdf = readdir(dpdf)) {
            if( (strcmp(epdf->d_name,"." )) &&
	        (strcmp(epdf->d_name,"..")) )  {
		sprintf(nfn,"%s/%s",dir,(char *)epdf->d_name);
		if(!isdir(nfn)) {
  		  if(!file_in_db(nfn))
		     printf("File found: %s\n",nfn);
		}
	        else {
  		 if(epdf->d_name[0]!='.')
		    scan_dir(nfn);
                }
            }
          }
    	}
    	closedir(dpdf);
}

int main() {
	chdir("..");
	con = mysql_init(NULL); if(con==NULL) { fprintf(stderr, "%s\n", mysql_error(con)); exit(1); }
	printf("Find Orphan Files (MySQL: %s)\n", mysql_get_client_info());
	if(mysql_real_connect(con, "localhost", "root", "!QAZ2wsx", "area56", 0, NULL, 0) == NULL) { finish_with_error(con); }
	scan_dir("files");
	mysql_close(con);
	exit(0);
}

