%{
/*	This file is part of the software similarity tester SIM.
	Written by Dick Grune, Vrije Universiteit, Amsterdam.
	Python part written by poormonitor.
	$Id: pyplang.l
*/

/*
	Python language front end for the similarity tester.
	Author:	Gertjan Akkerman <akkerm@cs.vu.nl>
	Date:	Thu, 9 Apr 87 11:15:23 MDT
*/

#include	"token.h"
#include	"language.h"
#include	"algollike.h"
#include	"lex.h"
#include	"lang.h"

/* General language front end data */
Token lex_token;
size_t lex_nl_cnt;
size_t lex_tk_cnt;
size_t lex_non_ascii_cnt;

/* Language-dependent data */
#include	"idf.h"

static const struct idf reserved[] = {
	{"and",		    META('a')},
	{"as",		    CTRL('s')},
	{"assert",	    CTRL('A')},
	{"break",	    CTRL('b')},
	{"class",	    CTRL('C')},
	{"continue",	CTRL('c')},
	{"def",		    CTRL('d')},
	{"del",		    CTRL('D')},
	{"elif",	    CTRL('e')},
	{"else",	    CTRL('E')},
	{"except",	    CTRL('x')},
	{"exec",	    CTRL('X')},
	{"finally",	    CTRL('f')},
	{"for",		    CTRL('F')},
	{"from",	    CTRL('f')},
	{"global",	    CTRL('g')},
	{"if",		    CTRL('i')},
	{"import",	    MTCT('I')},
	{"in",		    CTRL('i')},
	{"is",		    CTRL('i')},
	{"lambda",    	CTRL('L')},
	{"not",		    META('n')},
	{"or",		    META('o')},
	{"pass",	    CTRL('p')},
	{"print",	    CTRL('P')},
	{"raise",	    CTRL('r')},
	{"return",    	CTRL('R')},
	{"try",		    CTRL('t')},
	{"while",	    CTRL('w')},
	{"with",	    CTRL('W')},
	{"yield",	    CTRL('y')},
	// All Python 3 built-in functions
	{"abs",		    NORM('a')},
	{"aiter",       NORM('A')},
	{"all",	    	NORM('a')},
	{"any",	    	NORM('a')},
	{"ascii",    	NORM('A')},
	{"bin",	    	NORM('b')},
	{"bool",	    NORM('B')},
	{"bytearray",	NORM('B')},
	{"bytes",	    NORM('B')},
	{"callable",	NORM('C')},
	{"chr",		    NORM('c')},
	{"classmethod",	NORM('C')},
	{"compile",	    NORM('C')},
	{"complex",	    NORM('C')},
	{"delattr",	    NORM('D')},
	{"dict",	    NORM('D')},
	{"dir",		    NORM('d')},
	{"divmod",	    NORM('d')},
	{"enumerate",	NORM('E')},
	{"eval",	    NORM('e')},
	{"exec",	    NORM('e')},
	{"filter",    	NORM('f')},
	{"float",	    NORM('f')},
	{"format",    	NORM('F')},
	{"frozenset",	NORM('F')},
	{"getattr",	    NORM('G')},
	{"globals",	    NORM('g')},
	{"hasattr",	    NORM('H')},
	{"hash",	    NORM('h')},
	{"help",	    NORM('h')},
	{"hex",		    NORM('h')},
	{"id",		    NORM('i')},
	{"input",	    NORM('I')},
	{"int",		    NORM('i')},
	{"isinstance",	NORM('I')},
	{"issubclass",	NORM('I')},
	{"iter",	    NORM('I')},
	{"len",		    NORM('l')},
	{"list",	    NORM('L')},
	{"locals",    	NORM('l')},
	{"map",		    NORM('m')},
	{"max",		    NORM('m')},
	{"memoryview",	NORM('M')},
	{"min",		    NORM('m')},
	{"next",	    NORM('n')},
	{"object",	    NORM('o')},
	{"oct",		    NORM('o')},
	{"open",	    NORM('o')},
	{"ord",		    NORM('o')},
	{"pow",		    NORM('p')},
	{"property",	NORM('P')},
	{"range",	    NORM('r')},
	{"repr",	    NORM('r')},
	{"reversed",	NORM('R')},
	{"round",	    NORM('r')},
	{"set",		    NORM('s')},
	{"setattr",	    NORM('S')},
	{"slice",	    NORM('s')},
	{"sorted",	    NORM('S')},
	{"staticmethod",	NORM('S')},
	{"str",		    NORM('s')},
	{"sum",		    NORM('s')},
	{"super",	    NORM('s')},
	{"tuple",	    NORM('t')},
	{"type",	    NORM('t')},
	{"vars",	    NORM('v')},
	{"zip",		    NORM('z')},
};

/* Token sets for module algollike */
const Token Non_Finals[] = {
	NORM('('),
	NORM('['),
	No_Token
};
const Token Non_Initials[] = {
	NORM(')'),
	NORM(']'),
	No_Token
};
const Token Openers[] = {
	NORM('('),
	NORM('['),
	No_Token
};
const Token Closers[] = {
	NORM(')'),
	NORM(']'),
	No_Token
};

/* Language-dependent code */

const char *Subject = "Python programs";

void
Init_Language(void) {
	Init_Algol_Language(Non_Finals, Non_Initials, Openers, Closers);
}


int
May_Be_Start_Of_Run(Token ch) {
	return May_Be_Start_Of_Algol_Run(ch);
}

size_t
Best_Run_Size(const Token *str, size_t size) {
	return Best_Algol_Run_Size(str, size);
}

%}

%option	noyywrap

%Start	Comment

Layout		([ \t\r\f])
ASCII95		([\040-\176])

AnyQuoted	(\\.)
StrChar		([^\"\n\\]|{AnyQuoted})
ChrChar		([^\'\\]|{AnyQuoted})

IdfChar		([-!#$%&*+,/0-9:;<=>?@A-Z\\^_`a-z{}~])

EscIdf		(({IdfChar}|\\.)+)
QuotIdf		("|"[^\|\n]*"|")
Idf		([A-Za-z_][A-Za-z0-9_]*)

StartComment	("'''")
EndComment	("'''")
SingleLineCom	("#".*)

HexDigit	([0-9a-fA-F])

%%

";".*$	{				/* comment */
	}

\"{StrChar}*\"	{			/* strings */
		return_ch('"');
	}

{Idf}	{				/* identifier */
		return_tk(idf_in_list(yytext, reserved, sizeof reserved, IDF));
	}

\n	{				/* count newlines */
		return_eol();
	}

{Layout}	{			/* ignore layout */
	}

{ASCII95}	{			/* copy other text */
		return_ch(yytext[0]);
	}

.	{				/* count non-ASCII chars */
		lex_non_ascii_cnt++;
	}

%%

/* More language-dependent code */

void
yystart(void) {
	BEGIN INITIAL;
}
