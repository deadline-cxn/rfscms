#include <sys/types.h>
#include <sys/stat.h>
#include <unistd.h>
#include <time.h>
#include <stdlib.h>
#include <stdio.h>
#include <string.h>
#include <mysql.h>
using namespace std;
MYSQL *con;
void finish_with_error(MYSQL *con) { fprintf(stderr, "%s\n", mysql_error(con)); mysql_close(con); exit(1); }
void purge_file(int id, char * filename) {
	char q[1024]; memset(q,0,1024);
	sprintf(q,"delete from files where id ='%d'",id);
	if(mysql_query(con, q)) { finish_with_error(con); }
        MYSQL_RES *result = mysql_store_result(con);
	printf("PURGED: %s\n",filename);
}
int main() {
	chdir("..");
	con = mysql_init(NULL); if(con==NULL) { fprintf(stderr, "%s\n", mysql_error(con)); exit(1); }
	printf("Find Orphan Files (MySQL: %s)\n", mysql_get_client_info());
	if(mysql_real_connect(con, "localhost", "root", "!QAZ2wsx", "area56", 0, NULL, 0) == NULL) { finish_with_error(con); }
	if(mysql_query(con, "SELECT id, location FROM files")) { finish_with_error(con); }
	MYSQL_RES *result = mysql_store_result(con);
	if(result==NULL) { finish_with_error(con); }
	int num_fields = mysql_num_fields(result);
	MYSQL_ROW row;
	struct stat sb;
	char filename[1024]; memset(filename,0,1024);
	int id;
	while((row = mysql_fetch_row(result))) {
		id=atoi(row[0]);
		strcpy(filename,row[1]);
		if(stat(filename, &sb)==-1) {
			purge_file(id,filename);
		}
	}

	mysql_free_result(result);
	mysql_close(con);
	exit(0);
}

