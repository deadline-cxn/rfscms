/* 4brute -- brute-force the plaintext out of a list of collected
 * crypt'd tripcodes, as used by the futallaby-based image boards
 * (i.e: 4chan.org, wakachan.net)
 * --
 * Compile:
 * gcc -O3 -o 4brute 4brute.c -lssl # Most Linuxen
 * gcc -O3 -o 4brute 4brute.c -ldes # NetBSD
 * gcc -O3 -o 4brute 4brute.c ../mumble/libdes.a # Mine
 * gcc -O3 -fast -mcpu=7450 -o 4tripper 4tripper.c -lcrypto -lssl # OSX on a G4
 * UBUNTU:  gcc -O3 -o trip trip.c -lssl -lcrypto 
 * --
 * Usage:
 * ./4brute tripcodelist |tee >cracked.
 * Where "channerlist" looks like:
 *     Lain:dBqnRui06E
 *     Thock:a5sXscBP32
 *     WAHa:WAHa.06x36
 *     [etc..]
 * --
 * Hints:
 * The default searchspace is rather large; however 95% of people will
 * be using a single lowercase word as a tripcode, or some other such
 * shortcut. Try these at first:
 * ./4brute -k 0123456789. -p 10000 <tripcode file>
 * ./4brute -k 0123456789abcdef -p 10000 <tripcode file> - the Lain special!
 * ./4brute -k -r abcdefghijklmnopqrstuvwxyz -p 10000 <tripcode file>
 * Of course, people will now look at the above and immediately try to use
 * letters that would maximise your search time, in which case, you can do
 * things like this:
 * ./4brute -r -k ABCDEFGHIJKLMNOPQRSTUVWXYZ <tripcode file>
 * ./4brute -r -k \?\>\<\/\.\,\"\:\'\;\}\{\]\[ ...etc... <tripcode file>
 * --
 * TODO:
 * Provide something for partitioning up the search across N machines.
 * --
 * COMING SOON (maybe): the quick dictionary cracker. It needs a rewrite
 * though..
 * --
 * Copyright 2004 Chris Baird,, <cjb@brushtail.apana.org.au>
 * Licenced as per the GNU Public Licence Version 2.
 * Released: 2004/12/22. Your CPU heatsink /is/ working, right?
 */

#include <stdio.h>
#include <stdlib.h>
#include <string.h>		/* strncpy(3) */
#include <stdlib.h>		/* srandom(3) */
#include <time.h>		/* time(3) */
#include <signal.h>		/* signal(3) */

/* not quite the fastest DES library around, but still reasonable, and
 * most free Unixen should have it available. (Works for at least NetBSD
 * and Debian GNU/Linux (after "apt-get install libssl-dev")  */

#include <openssl/des.h>

// gotta ask for a robust way to tell the difference between the two..
#if !NEW_OPENSSL
#  define our_fcrypt DES_fcrypt //  NetBSD, Linux...
#else
#  define our_fcrypt DES_fcrypt //  Gentoo, OSX...
#endif
extern char *our_fcrypt(const char *buf,const char *salt, char *ret);

/* lol internet  */

void usage(void)
{
  fprintf (stderr, "usage: 4brute [-s string] [-e string] [-t string] "
	   "[-p num] [-r] tripcodefile\n"
	   "\t-s string : initial key for search\n"
	   "\t-e string : final key for search\n"
	   "\t-k string : characters to use in the keys\n"
	   "\t-p num	: show progress every <num> keys checked\n"
	   "\t-r	: randomize the order of the keytable\n"
	   "	\"tripcodefile\" has the format \"username:tripcode\", "
	   "one per line\n");
  exit (1);
}

void indexify (int *counts, char *word, char *table)
{
  int i, j;

  for (i = 0; (word[i] != 0) && (i < 9); i++)
    {
      for (j = 0; table[j] != 0 && table[j] != word[i]; j++) {}
      counts[i] = j;
    }
}

void scramble (char *table)
{
  int i, l, r;
  char t;

  srandom ((unsigned int)time(NULL));
  l = strlen (table);
  for (i = 0; table[i] != 0; i++)
    {
      r = random () % l;
      t = table[i];
      table[i] = table[r];
      table[r] = t;
    }
}

char wordchecked[9] = "4chan";

void interrupted (int value)
{
  printf ("\n\"%s\"  \n", wordchecked);
  exit(-1);
}

int main(int argc, char *argv[])
{
  FILE *fpass;
  char c, *p, salt0, salt1, salt[3], word[9], result[14], line[96];
  char users[4096][64], crypts[4096][16], salttable[256], table[256];
  int i, j, usercount=0, count=1, quit=0, counts[8], ending[8], counttick=0;
  int opt_e=0, opt_r=0, opt_k=0;
  extern char *optarg;
  extern int optind;

  /* setup */

  strcpy (table, "etaonrishdlfcmugpywbvkxjqz"
	  "ETAONRISHDLFCMUGPYWBVKXJQZ0123456789"
	  " .!:#/`()_$[]+*{}-");

  salt[2] = 0;
  for (i = 0; i < 8; i++)
    {
      counts[i] = -1;
      word[i] = 0;
    }

  for (i = 0; i <= 255; i++)
    salttable[i] = '.';
  for (i = '/'; i <= 'z'; i++)
    salttable[i] = i;
  for (i = ':'; i <= '@'; i++)
    salttable[i] = i + 7;
  for (i = '['; i <= '`'; i++)
    salttable[i] = i + 6;

  /* arg parsing */

  while ((c = getopt(argc, argv, "p:k:s:e:r")) != -1)
    switch (c)
      {
      case 'p':
	counttick = atoi (optarg);
	break;

      case 's':
	strncpy (word, optarg, 8);
	word[8] = 0;
	indexify (counts, word, table);
	printf ("Starting search from \"%s\"\n", word);
	break;

      case 'e':
	opt_e = 1;
	indexify (ending, optarg, table);
	printf ("Ending search at \"%s\"\n", optarg);
	break;

      case 'k':
	opt_k = 0;
	strncpy (table, optarg, 256);
	printf ("Searching through: %s\n", table);
	break;

      case 'r':
	opt_r = 1;
	break;

      default: usage();
      }

  argc -= optind;
  argv += optind;

  if (opt_r)
    {
      scramble (table);
      printf ("Searching through (randomised): %s\n", table);
    }

  counts[0] = 0;
  word[0] = table[0];

  /* tripcode file reading */

  if ((fpass = fopen (argv[0], "r")) == 0)
    usage();

  while (fgets (line, 96, fpass) != 0)
    {
      for (i = 0, p = line; *p != ':';)
	users[usercount][i++] = *p++;
      users[usercount][i] = 0;

      for (i = 0, p++; *p != '\n';)
	crypts[usercount][i++] = *p++;
      crypts[usercount][i] = 0;

      if (i != 10)
	{
	  printf ("Bad input at line %d\n", usercount);
	  exit(1);
	}

      usercount++;
    }
  fclose (fpass);
  printf ("Number of users scanned: %d\n\n", usercount); fflush(stdout);

  /* SIGINT catching */

  signal (SIGINT, interrupted);

  /* mainloop */

  while (!quit)
    {
      if (opt_e && (counts[0] == ending[0] && counts[1] == ending[1] &&
		    counts[2] == ending[2] && counts[3] == ending[3] &&
		    counts[4] == ending[4] && counts[5] == ending[5] &&
		    counts[6] == ending[6] && counts[7] == ending[7]))
	quit = 1;

      /* find the right salt .. $salt=substr($cap."H.",1,2); */

      salt0 = word[1];
      salt1 = word[2];
      if (!salt0)
	{
	  salt0 = 'H';
	  salt1 = '.';
	}
      else if (!salt1)
	{
	  salt1 = 'H';
	}
      salt[0] = salttable[salt0];
      salt[1] = salttable[salt1];

      /* blah */

      if (counttick && (count++ == counttick))
	{
	  printf ("%s\r", word); fflush(stdout);
	  count = 1;
	}

      /* crunch */

      our_fcrypt (word, salt, result);

      for (i = 0; i < usercount; i++)
	{
	  if (result[3] != crypts[i][0]) continue;
	  if (result[4] != crypts[i][1]) continue;
	  if (result[5] != crypts[i][2]) continue;
	  if (result[6] != crypts[i][3]) continue;
	  if (result[7] != crypts[i][4]) continue;
	  if (result[8] != crypts[i][5]) continue;
	  if (result[9] != crypts[i][6]) continue;
	  if (result[10] != crypts[i][7]) continue;
	  if (result[11] != crypts[i][8]) continue;
	  if (result[12] != crypts[i][9]) continue;
	  printf ("Username \"%s\"\r\t\t\t\t\tCrypt \"%s\"\tTripcode \"%s\"\n",
		  users[i], crypts[i], word);
	  fflush(stdout);
	  for (j = 0; j < 10; j++)
	    crypts[i][j] = 0;
	}

      strcpy (wordchecked, word);

      /* bump */

      i = 0;
    check:
      counts[i]++;
      c = table[counts[i]];
      word[i] = c;

      if (c == 0)
	{
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
