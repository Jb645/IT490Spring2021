#!/bin/bash

SERVERIP=25.14.165.46
STATUS=0
COUNT=0

EXCLUDEFILE=~/git/IT490Spring2021/avoid.txt
EXCLUDE=0

declare -a EXCLUDEARRAY

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
		check_exclude
		if [[ $EXCLUDE == 1 ]]
		then
			EXCLUDE=0
			continue
		fi

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
	COUNT=$count+1
	for entry in $NEWDIR*
        do
                check_exclude
                if [[ $EXCLUDE == 1 ]]
                then
                        EXCLUDE=0
                        continue
                fi

                filename=$(basename -- $entry)
                if [[ -d $entry ]]
                then
                        echo "$filename is a directory"
			NEWDIR=$NEWDIR$filename/
			compare
		fi

                if diff -q $entry <(ssh tim@$SERVERIP cat $NEWDIR$filename) > /dev/null
                then
                	echo "$filename files are the same"
                else
                        echo "$filename files are different"
                fi
        done
	
	if [[ $COUNT < 1 ]]
	then
		NEWDIR=$(dirname $NEWDIR)/
	fi
}

check_exclude ()
{
	arraylength=${#EXCLUDEARRAY[@]}
	for (( i=0; i<${arraylength}+1; i++ ));
        do
		if [[ "${EXCLUDEARRAY[$i]}" == "$entry" ]]
                then
			echo "$entry is excluded"
			EXCLUDE=1
               		break
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

echo "Exluding the following files:"

i=0
while IFS= read -r line; do
	EXCLUDEARRAY[i]=$line
	i=($i+1)
done < $EXCLUDEFILE
echo ${EXCLUDEARRAY[*]}
echo "========================================"

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


