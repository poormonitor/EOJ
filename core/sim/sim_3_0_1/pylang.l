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
size_t lex_non_ASCII_cnt;

/* Language-dependent data */
#include	"idf.h"

static const struct idf reserved[] = {
	{"and",		NORM('a')},
	{"assert",	NORM('a')},
	{"break",	NORM('b')},
	{"class",	NORM('c')},
	{"continue",	NORM('c')},
	{"def",		NORM('d')},
	{"del",		NORM('d')},
	{"elif",	NORM('e')},
	{"else",	NORM('e')},
	{"except",	NORM('e')},
	{"exec",	NORM('x')},
	{"finally",	NORM('f')},
	{"for",		NORM('f')},
	{"from",	NORM('f')},
	{"global",	NORM('g')},
	{"if",		NORM('i')},
	{"import",	NORM('i')},
	{"in",		NORM('i')},
	{"is",		NORM('i')},
	{"lambda",	NORM('l')},
	{"not",		NORM('n')},
	{"or",		NORM('o')},
	{"pass",	NORM('p')},
	{"print",	NORM('p')},
	{"raise",	NORM('r')},
	{"return",	NORM('r')},
	{"try",		NORM('t')},
	{"while",	NORM('w')},
	{"with",	NORM('w')},
	{"yield",	NORM('y')},
};

/* Token sets for properties module */
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
