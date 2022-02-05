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
	{"as",		    CTRL('a')},
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
	META('a'),      /* and */
	CTRL('a'),      /* as */
	CTRL('A'),      /* assert */
	CTRL('b'),      /* break */
	CTRL('C'),      /* class */
	CTRL('c'),      /* continue */
	CTRL('d'),      /* def */
	CTRL('D'),      /* del */
	CTRL('e'),      /* elif */
	CTRL('E'),      /* else */
	CTRL('x'),      /* except */
	CTRL('X'),      /* exec */
	CTRL('f'),      /* finally */
	CTRL('F'),      /* for */
	CTRL('i'),      /* if */
	MTCT('I'),      /* import */
	CTRL('L'),      /* lambda */
	META('n'),      /* not */
	META('o'),      /* or */
	CTRL('p'),      /* pass */
	CTRL('P'),      /* print */
	CTRL('r'),      /* raise */
	CTRL('R'),      /* return */
	CTRL('t'),      /* try */
	CTRL('w'),      /* while */
	CTRL('W'),      /* with */
	CTRL('y'),      /* yield */
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

StartComment	("'''")
EndComment	("'''")
SafeComChar	([^#\n])
UnsafeComChar	("#")

SingleLineCom	("#".*)

Idf		([A-Za-z][A-Za-z0-9_]*)

%%

{StartComment}	{
		/*	We do not have one single pattern to match a comment
			(although one can be written), for two reasons.
			The matched string might overflow lex-internal buffers
			like yysbuf and yytext; and the pattern would be very
			complicated and impair maintainability.
			So we break up the string into safe chunks and keep
			track of where we are in a start condition <Comment>.
		*/
		BEGIN Comment;
	}

<Comment>{SafeComChar}+	{		/* safe comment chunk */
	}

<Comment>{UnsafeComChar}	{	/* unsafe char, read one by one */
	}

<Comment>"\n"		{		/* to break up long comments */
		return_eol();
	}

<Comment>{EndComment}	{		/* end-of-comment */
		BEGIN INITIAL;
	}

{SingleLineCom}"\n"	{		/* single-line comment */
		return_eol();
	}

\"{StrChar}*\"	{			/* strings */
		return_ch('"');
	}

\'{ChrChar}+\'	{			/* characters */
		return_ch('\'');
	}

^#{Layout}*include.*	{		/* ignore #include lines */
	}

^#{Layout}*{Idf}	{		/* a preprocessor line */
		char *idf = yytext+1;

		/* skip layout in front of preprocessor identifier */
		while (*idf == ' ' || *idf == '\t') {
			idf++;
		}
		return_tk(idf_in_list(idf, ppcmd, sizeof ppcmd, NORM('#')));
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
