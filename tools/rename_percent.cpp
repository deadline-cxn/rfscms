#include <sys/types.h>
#include <sys/stat.h>
#include <unistd.h>
#include <time.h>
#include <stdlib.h>
#include <stdio.h>
#include <string.h>
#include <vector>
#include <string>
#include <dirent.h>
using namespace std;

vector<string> files;

bool isdir(char *dir) { struct stat st; if(stat(dir,&st)==-1) return false; if(st.st_mode&S_IFDIR) return true; return false; }
void check_percents(char* filename) {
	char q[1024]; memset(q,0,1024); 
	bool bfound=false;
	for(int i=0;i<strlen(filename);i++) {
		 if( (filename[i]=='%')  ) {// ||
                //      (filename[i]==' '))  {
			bfound=true;
			q[i]='_';
		}
		else
		q[i]=filename[i];
	}
	if(bfound) {
		printf("%s\n%s\n",filename,q);
		rename(filename,q);
		strcpy(filename,q);
	}

}
void scan_dir(char *dir) {
// 	printf("Scanning [%s]\n",dir);
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
		(strcasecmp(epdf->d_name,"folder.jpg")) )  {
		sprintf(nfn,"%s/%s",dir,(char *)epdf->d_name);
		check_percents(nfn);
		if(isdir(nfn)) {
		 scan_dir(nfn);
		}
            }
          }
    	}
    	closedir(dpdf);
}

int main() {
	chdir("..");
	printf("RFSCMS Remove percent signs command line utility\n");
	scan_dir("files");
	exit(0);
}

