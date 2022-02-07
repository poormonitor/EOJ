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
	{"abs",		    CTRL('a')},
	{"all",		    CTRL('A')},
	{"any",		    CTRL('A')},
	{"bin",		    CTRL('b')},
	{"bool",	    MTCT('B')},
	{"callable",	CTRL('c')},
	{"chr",		    CTRL('c')},
	{"classmethod",	CTRL('C')},
	{"compile",	    CTRL('c')},
	{"complex",	    CTRL('c')},
	{"delattr",	    CTRL('d')},
	{"dict",	    MTCT('D')},
	{"dir",		    CTRL('d')},
	{"divmod",	    CTRL('d')},
	{"enumerate",	CTRL('e')},
	{"eval",	    CTRL('e')},
	{"execfile",	CTRL('e')},
	{"filter",	    CTRL('f')},
	{"float",	    MTCT('f')},
	{"format",	    CTRL('f')},
	{"frozenset",	CTRL('F')},
	{"getattr",	    CTRL('g')},
	{"globals",	    CTRL('g')},
	{"hasattr",	    CTRL('h')},
	{"hash",	    CTRL('h')},
	{"hex",		    CTRL('h')},
	{"id",		    CTRL('i')},
	{"int",		    CTRL('i')},
	{"iter",	    MTCT('i')},
	{"len",		    CTRL('l')},
	{"list",	    MTCT('L')},
	{"locals",	    CTRL('l')},
	{"long",	    MTCT('l')},
	{"map",		    CTRL('m')},
	{"max",		    CTRL('m')},
	{"min",		    CTRL('m')},
	{"next",		CTRL('n')},
	{"object",	    MTCT('o')},
	{"oct",		    CTRL('o')},
	{"open",	    CTRL('o')},
	{"ord",		    CTRL('o')},
	{"pow",		    CTRL('p')},
	{"property",	CTRL('p')},
	{"range",	    CTRL('r')},
	{"reduce",	    CTRL('r')},
	{"repr",	    CTRL('r')},
	{"reversed",	CTRL('r')},
	{"round",	    CTRL('r')},
	{"set",		    MTCT('s')},
	{"setattr",	    CTRL('s')},
	{"slice",	    CTRL('s')},
	{"sorted",	    CTRL('s')},
	{"staticmethod",	CTRL('S')},
	{"str",		    MTCT('s')},
	{"sum",		    CTRL('s')},
	{"super",	    CTRL('s')},
	{"tuple",	    MTCT('t')},
	{"type",	    CTRL('t')},
	{"unichr",	    CTRL('u')},
	{"unicode",	    CTRL('u')},
	{"vars",	    CTRL('v')},
	{"xrange",	    CTRL('x')},
	{"zip",		    CTRL('z')}
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
	CTRL('a'),	    /* abs */
	CTRL('A'),	    /* all */
	CTRL('A'),	    /* any */
	CTRL('b'),	    /* bin */
	CTRL('B'),	    /* bool */
	CTRL('c'),	    /* callable */
	CTRL('c'),	    /* chr */
	CTRL('C'),	    /* classmethod */
	CTRL('c'),	    /* compile */
	CTRL('c'),	    /* complex */
	CTRL('d'),	    /* delattr */
	CTRL('D'),	    /* dict */
	CTRL('d'),	    /* dir */
	CTRL('d'),	    /* divmod */
	CTRL('e'),	    /* enumerate */
	CTRL('e'),	    /* eval */
	CTRL('e'),	    /* execfile */
	CTRL('f'),	    /* filter */
	CTRL('f'),	    /* float */
	CTRL('f'),	    /* format */
	CTRL('F'),	    /* frozenset */
	CTRL('g'),	    /* getattr */
	CTRL('g'),	    /* globals */
	CTRL('h'),	    /* hasattr */
	CTRL('h'),	    /* hash */
	CTRL('h'),	    /* hex */
	CTRL('i'),	    /* id */
	CTRL('i'),	    /* int */
	CTRL('i'),	    /* iter */
	CTRL('l'),	    /* len */
	CTRL('L'),	    /* list */
	CTRL('l'),	    /* locals */
	CTRL('l'),	    /* long */
	CTRL('m'),	    /* map */
	CTRL('m'),	    /* max */
	CTRL('m'),	    /* min */
	CTRL('n'),	    /* next */
	CTRL('o'),	    /* object */
	CTRL('o'),	    /* oct */
	CTRL('o'),	    /* open */
	CTRL('o'),	    /* ord */
	CTRL('p'),	    /* pow */
	CTRL('p'),	    /* property */
	CTRL('r'),	    /* range */
	CTRL('r'),	    /* reduce */
	CTRL('r'),	    /* repr */
	CTRL('r'),	    /* reversed */
	CTRL('r'),	    /* round */
	CTRL('s'),	    /* set */
	CTRL('s'),	    /* setattr */
	CTRL('s'),	    /* slice */
	CTRL('s'),	    /* sorted */
	CTRL('S'),	    /* staticmethod */
	CTRL('s'),	    /* str */
	CTRL('s'),	    /* sum */
	CTRL('s'),	    /* super */
	CTRL('t'),	    /* tuple */
	CTRL('t'),	    /* type */
	CTRL('u'),	    /* unichr */
	CTRL('u'),	    /* unicode */
	CTRL('v'),	    /* vars */
	CTRL('x'),	    /* xrange */
	CTRL('z'),	    /* zip */
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
