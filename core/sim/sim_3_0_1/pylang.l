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

#include	"options.h"
#include	"token.h"
#include	"language.h"
#include	"algollike.h"
#include	"idf.h"
#include	"lex.h"
#include	"lang.h"

/* General language front end data */
Token lex_token;
size_t lex_nl_cnt;
size_t lex_tk_cnt;
size_t lex_non_ascii_cnt;

/* Language-dependent data */

/* Data for module idf */

static const struct idf ppcmd[] = {
	{"import",      MTCT('i')},
};

static const struct idf reserved[] = {
	{"and",		    META('a')},
	{"as",		    MTCT('a')},
	{"assert",	    CTRL('A')},
	{"break",	    CTRL('b')},
	{"class",	    MTCT('C')},
	{"continue",	CTRL('c')},
	{"def",		    MTCT('d')},
	{"del",		    CTRL('D')},
	{"elif",	    MTCT('e')},
	{"else",	    MTCT('E')},
	{"except",	    MTCT('x')},
	{"exec",	    CTRL('X')},
	{"finally",	    MTCT('f')},
	{"for",		    CTRL('F')},
	{"from",	    MTCT('F')},
	{"global",	    CTRL('g')},
	{"if",		    MTCT('I')},
	{"in",		    META('i')},
	{"is",		    META('I')},
	{"lambda",	    MTCT('L')},
	{"not",		    META('n')},
	{"or",		    META('o')},
	{"pass",	    CTRL('p')},
	{"print",	    CTRL('P')},
	{"raise",	    CTRL('R')},
	{"return",	    CTRL('r')},
	{"try",		    MTCT('t')},
	{"while",	    MTCT('w')},
	{"with",	    MTCT('W')},
	{"yield",	    MTCT('y')},
	{"True",        NORM('t')},
	{"False",       NORM('f')},
	{"None",        NORM('n')},
	// all built-in functions
	{"abs",		    NORM('a')},
	{"all",		    NORM('A')},
	{"any",		    NORM('A')},
	{"bin",		    NORM('b')},
	{"bool",	    NORM('B')},
	{"callable",	NORM('c')},
	{"chr",		    NORM('c')},
	{"classmethod",	NORM('C')},
	{"compile",	    NORM('c')},
	{"complex",	    NORM('c')},
	{"delattr",	    NORM('d')},
	{"dict",	    NORM('D')},
	{"dir",		    NORM('d')},
	{"divmod",	    NORM('d')},
	{"enumerate",	NORM('e')},
	{"eval",	    NORM('e')},
	{"execfile",	NORM('e')},
	{"filter",	    NORM('f')},
	{"float",	    NORM('f')},
	{"format",	    NORM('f')},
	{"frozenset",	NORM('F')},
	{"getattr",	    NORM('g')},
	{"globals",	    NORM('g')},
	{"hasattr",	    NORM('h')},
	{"hash",	    NORM('h')},
	{"hex",		    NORM('h')},
	{"id",		    NORM('i')},
	{"int",		    NORM('i')},
	{"iter",	    NORM('i')},
	{"len",		    NORM('l')},
	{"list",	    NORM('L')},
	{"locals",	    NORM('l')},
	{"long",	    NORM('l')},
	{"map",		    NORM('m')},
	{"max",		    NORM('m')},
	{"min",		    NORM('m')},
	{"next",		NORM('n')},
	{"object",	    NORM('o')},
	{"oct",		    NORM('o')},
	{"open",	    NORM('o')},
	{"ord",		    NORM('o')},
	{"pow",		    NORM('p')},
	{"property",	NORM('p')},
	{"range",	    NORM('r')},
	{"reduce",	    NORM('r')},
	{"repr",	    NORM('r')},
	{"reversed",	NORM('r')},
	{"round",	    NORM('r')},
	{"set",		    NORM('s')},
	{"setattr",	    NORM('s')},
	{"slice",	    NORM('s')},
	{"sorted",	    NORM('s')},
	{"staticmethod",	NORM('S')},
	{"str",		    NORM('s')},
	{"sum",		    NORM('s')},
	{"super",	    NORM('s')},
	{"tuple",	    NORM('t')},
	{"type",	    NORM('t')},
	{"unichr",	    NORM('u')},
	{"unicode",	    NORM('u')},
	{"vars",	    NORM('v')},
	{"xrange",	    NORM('x')},
	{"zip",		    NORM('z')}
};

/* Special treatment of identifiers */

static Token
idf2token(int hashing) {
	Token tk;

	tk = idf_in_list(yytext, reserved, sizeof reserved, IDF);
	if (Token_EQ(tk, IDF) && hashing) {
		/* return a one-Token hash code */
		tk = idf_hashed(yytext);
	}
	return tk;
}

/* Token sets for module algollike */
const Token Non_Finals[] = {
	IDF,
	NORM('{'),
	NORM('('),
	NORM('['),
	META('a'),      /* and */
	MTCT('a'),	    /* as */
	CTRL('A'), 		/* assert */
	CTRL('b'),	    /* break */
	MTCT('C'),	    /* class */
	CTRL('c'),	    /* continue */
	MTCT('d'),	    /* def */
	CTRL('D'),	    /* del */
	MTCT('e'),	    /* elif */
	MTCT('E'),	    /* else */
	MTCT('x'),	    /* except */
	CTRL('X'),	    /* exec */
	MTCT('f'),	    /* finally */
	MTCT('F'),	    /* for */
	MTCT('i'),	    /* from */
	CTRL('g'),	    /* global */
	MTCT('I'),	    /* if */
	MTCT('L'),	    /* lambda */
	META('n'),	    /* not */
	META('o'),	    /* or */
	CTRL('p'),	    /* pass */
	CTRL('P'),	    /* print */
	CTRL('R'),	    /* raise */
	CTRL('r'),	    /* return */
	MTCT('t'),	    /* try */
	MTCT('w'),	    /* while */
	MTCT('W'),	    /* with */
	MTCT('y'),	    /* yield */
	NORM('t'),	    /* True */
	NORM('f'),	    /* False */
	NORM('n'),	    /* None */
	No_Token
};
const Token Non_Initials[] = {
	NORM(')'),
	NORM(']'),
	NORM('}'),
	NORM(';'),
	No_Token
};
const Token Openers[] = {
	NORM('('),
	NORM('['),
	NORM('{'),
	No_Token
};
const Token Closers[] = {
	NORM(')'),
	NORM(']'),
	NORM('}'),
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

Digit		([0-9a-fA-F])

UniCode		(\\u{Digit}{Digit}{Digit}{Digit})
AnyQuoted	((\\.)|{UniCode})
StrChar		([^\"\n\\]|{AnyQuoted})
ChrChar		([^\'\n\\]|{AnyQuoted})

SingleLineCom	("#".*)

Idf		([A-Za-z][A-Za-z0-9_]*)

%%

{SingleLineCom}"\n"	{		/* single-line comment */
		return_eol();
	}

\"{StrChar}*\"	{			/* strings */
		return_ch('"');
	}

\'{ChrChar}+\'	{			/* characters */
		return_ch('\'');
	}

(0x)?{Digit}+("l"|"L")?	{		/* numeral, passed as an identifier */
		return_tk(IDF);
	}

{Idf}/"("	{			/* identifier in front of ( */
		Token tk;

		tk = idf2token(is_set_option('F'));
		if (!Token_EQ(tk, No_Token)) return_tk(tk);
	}

{Idf}	{				/* identifier */
		Token tk;

		tk = idf2token(0 /* no hashing */);
		if (!Token_EQ(tk, No_Token)) return_tk(tk);
	}

\;	{				/* semicolon, conditionally ignored */
		if (is_set_option('f')) return_ch(yytext[0]);
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
