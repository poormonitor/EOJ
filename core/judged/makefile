all: judged.cc judged.http
	g++ -Wall -c -DOJ_USE_MYSQL -I/usr/local/mysql/include/mysql -I/usr/include/mysql  judged.cc
	g++ -Wall -o judged judged.o -L/usr/local/mysql/lib/mysql -L/usr/lib/mysql  -L/usr/lib64/mysql  -lmysqlclient

judged.http: judged.cc
	g++ -Wall -o judged.http judged.cc
clean:
	rm judged judged.http judged.o
install:
	chmod a+x judged
	cp judged /usr/bin
