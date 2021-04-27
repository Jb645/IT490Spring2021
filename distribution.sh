#!/bin/bash

SERVERIP=25.14.165.46
STATUS=0


server_status () {
	ping -c 3 $SERVERIP > /dev/null && STATUS=1 || STATUS=0
}

unused  () {
	if diff -q ~/git/IT490Spring2021/logs.txt <(ssh sean@$SERVERIP cat /home/sean/Documents/Projects/IT490Spring2021/logs.txt) > /dev/null
then
        echo "Files are equal"
else
        echo "Files are different"
fi
}

compare_all () {
	for entry in ~/git/IT490Spring2021/*
	do
		filename=$(basename -- $entry)
		if [[ -d $entry ]]
		then
			echo "$filename is a directory"
			NEWDIR=~/git/IT490Spring2021/$filename/
			compare
		fi

		if diff -q $entry <(ssh tim@$SERVERIP cat /home/tim/git/IT490Spring2021/$filename) > /dev/null
		then
        		echo "$filename files are the same"	
		else
        		echo "$filename files are different"
		fi
	done
	echo "Done"
}

compare () {
	 
	for entry in $NEWDIR*
        do
                filename=$(basename -- $entry)
                if [[ -d $entry ]]
                then
                        echo "$filename is a directory"
                	$NEWDIR=$NEWDIR$filename/
			compare
		fi

                if diff -q $entry <(ssh tim@$SERVERIP cat $NEWDIR$filename) > /dev/null
                then
                	echo "$filename files are the same"
                else
                        echo "$filename files are different"
                fi
        done

}

upload () {
	compare_all
}

server_status

if [[ $STATUS == 1 ]]
then
	echo "Server is up"
else
	echo "Server is down"	
	exit
fi

echo "Your options are: UPLOAD or DOWNLOAD"
read option

if [[ $option == "UPLOAD" ]]
then
	echo "Creating and uploading package"
	upload
	exit
elif [[ $option == "DOWNLOAD" ]]
then
	echo "Checking files on server"
	exit
else
	echo "Invalid command"
fi

