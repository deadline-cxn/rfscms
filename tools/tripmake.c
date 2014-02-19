/* 4tripper -- brute-force searching for simple crypt() tripcodes,
 * as used by the futallaby-based image boards (i.e: 4chan.org)
 * --
 * Compile:
 * gcc -O3 -o tripper 4tripper.c -lssl # Most Linux
 * gcc -O3 -o tripper 4tripper.c -ldes # NetBSD
 * gcc -O3 -o tripper 4tripper.c ../mumble/libdes.a # Mine
 * gcc -O3 -fast -mcpu=7450 -o 4tripper 4tripper.c -lcrypto -lssl # OSX on a G4
 *
 *
 * UBUNTU:
 * gcc -Ofast -o tripmake tripmake.c -lssl -lcrypto -lrt
 *
 * --
 * Usage:
 * ./tripper | grep -i monkey
 * --
 * Copyright 2004 Chris Baird,, <cjb@brushtail.apana.org.au>
 * Licenced as per the GNU Public Licence Version 2.
 * Released: 2004/12/22. Your CPU heatsink /is/ working, right?
 * --
 * TODO:
 * Accept arguments for the key to resume/finish searching from (for
 * simple load distribution)
 */

#include <stdio.h>
#include <unistd.h>
#include <stdlib.h>
#include <time.h>
#include <string.h>
/*  Debian GNU/Linux (after "apt-get install libssl-dev")  */
#include <openssl/des.h>

#if !NEW_OPENSSL
#  define our_fcrypt DES_fcrypt /* NetBSD, Linux... */
#else
#  define our_fcrypt DES_fcrypt /* Gentoo, OSX... */
#endif
extern char *our_fcrypt(const char *buf,const char *salt, char *ret);

long get_tickcount(void)
{
	struct timespec now;
	if (clock_gettime(CLOCK_MONOTONIC, &now))
		return 0;
	return now.tv_sec * 1000.0 + now.tv_nsec / 1000000.0;
}

int main(int argc, char *argv[])
{

#define BUFSIZE 8192
	int quit=0, i, counts[8], bp;
	char c, buffer[BUFSIZE+1024], result[14], salt[3], word[9];
	char search[1024];
	strcpy(search,argv[1]);
	printf(" [%s]\n", search);

	srand(get_tickcount());

	memset(buffer,0,1024);
	/* I haven't throughly checked whether all these characters are valid in a tripcode as yet. */
	char table[]="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"
	             "0123456789 .!:#/`()_$[]+*{-";
	bp = 0;
	salt[2] = 0;
	for (i=0; i<8; i++) {
		counts[i] = -1;
		word[i] = table[rand()%52];
	}
	counts[0] = 0;
	word[0] = table[0];

	while (!quit) {
		salt[0] = word[1];
		salt[1] = word[2];

		our_fcrypt (word, salt, result);
		for (i = 0; (word[i] != 0) && (i < 8); i++)
			buffer[bp++] = word[i];
		buffer[bp++] = ' ';

		for (i = 3; i < 13; i++)
			buffer[bp++] = result[i];
		buffer[bp++] = 0;

		if ((bp > BUFSIZE)) {
			if(strcasestr(buffer,search)) {
				printf("%s\n",buffer);
				memset(buffer,0,1024);
			}
			bp = 0;
		}

		i = 0;
check:
		counts[i]++;
		c = table[counts[i]];
		word[i] = c;

		if (c == 0) {
			counts[i] = 0;
			word[i] = table[0];
			i++;
			if (i < 8)
				goto check;
			quit = 1;
		}
	}

	return 0;
}
