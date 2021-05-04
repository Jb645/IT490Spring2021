#!/bin/bash

CONFIG=~/git/IT490Spring2021/distribution.conf
STATUS=0
COUNT=0
EXCLUDE=0

declare -a EXCLUDEARRAY
declare -a DIFFERENCES

init () {
	i=0
	while IFS= read -r line;
	do
	if [[ $i == 0 ]]
	then
	 SERVERIP="${line:11}"
	elif [[ $i == 1 ]]
	then
	 SERVER="${line:6}"
	 SERVER=$SERVER@$SERVERIP
	 echo $SERVER
	elif [[ $i == 2 ]]
	then
	  ABSOLUTEPATH="${line:15}"
	  echo $ABSOLUTEPATH
  	elif [[ $i == 3 ]]
      	then
	  CLIENTPATH="${line:9}"
	  echo $CLIENTPATH	  
  	elif [[ $i == 4 ]]
	then
	  EXCLUDEFILE="${line:14}"
	fi
	i=$((i+1))
	done < $CONFIG


echo $TEST

}

server_status () {
	ping -c 3 $SERVERIP > /dev/null && STATUS=1 || STATUS=0
}

compare_all () {
	for entry in $CLIENTPATH*
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
			NEWDIR=$CLIENTPATH$filename/
			ABSOLUTEPATH=$ABSOLUTEPATH$filename/
			compare
		fi
		
		if [[ $filename == SKIP ]]
		then 
			continue
		fi

		if diff -q $entry <(ssh $SERVER cat $ABSOLUTEPATH$filename) > /dev/null
		then
        		echo "$filename files are the same"	
		else
			DIFFERENCES+=($entry)
        		echo "$filename files are different"
		fi
	done
	echo "Done"
	echo "Total differences:"
	for value in "${DIFFERENCES[@]}"
	do
		echo $value
	done
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
			ABSOLUTEPATH=$ABSOLUTEPATH$filename/
			compare
		fi

	        if [[ $filename == SKIP ]]
                then
                        continue
                fi

                if diff -q $entry <(ssh $SERVER cat $ABSOLUTEPATH$filename) > /dev/null
                then
                	echo "$filename files are the same"
                else
                        echo "$filename files are different"
			DIFFERENCES+=($entry)
		fi
        done
	
	NEWDIR=$(dirname $NEWDIR)/
	ABSOLUTEPATH=$(dirname $ABSOLUTEPATH)/
	COUNT=($COUNT-1)
	filename='SKIP'
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
	
	echo "Are you sure you want to upload (YES/NO?)"
	read option

	if [[ $option == "NO" ]]
	then
        	echo "Goodbye"
        	exit
	elif [[ $option == "YES" ]]
	then
		echo "Uploading"
	else
		echo "Command invalid"
		exit
	fi
	
	CLIENTPATH=${CLIENTPATH%?}
	BASEDIRNAME="${CLIENTPATH##*/}"
	TARGET=${ABSOLUTEPATH%?}
	for entry in "${DIFFERENCES[@]}"
        do
		TEMPDIR=""
		PARENTDIR="$(dirname $entry)"
		check_dir
		ssh $SERVER "mkdir -p $TARGET$TEMPDIR"
		scp $entry $SERVER:$TARGET$TEMPDIR/
	done
}

check_dir () {
	PARENTDIRNAME="${PARENTDIR##*/}"
	if [[ $PARENTDIRNAME != $BASEDIRNAME ]]
	then
		PARENTDIR="$(dirname $PARENTDIR)"
		TEMPDIR="/$PARENTDIRNAME$TEMPDIR"
		echo $TEMPDIR
		check_dir
	fi


}

init
server_status

if [[ $STATUS == 1 ]]
then
	echo "Server is up"
else
	echo "Server is down"	
	exit
fi

echo "$EXCLUDEFILE is exluding the following files:"

i=0
while IFS= read -r line; do
	EXCLUDEARRAY[i]=$line
	i=($i+1)
done < "$EXCLUDEFILE"
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


