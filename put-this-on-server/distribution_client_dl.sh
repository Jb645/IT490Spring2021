#!/bin/bash

CONFIG=~/git/rabbitmqphp_example/distribution.conf
STATUS=0
COUNT=0
EXCLUDE=0

declare -a EXCLUDEARRAY
declare -a DIFFERENCES

init () {
	i=0
	while IFS= read -r line;
	do
	if [[ $i == 3 ]]
      	then
          CLIENTPATH="${line:9}"
	fi
  	if [[ $i == 4 ]]
	then
	  EXCLUDEFILE="${line:14}"
	fi
	i=$((i+1))
	done < $CONFIG

	SERVER=$SERVERNAME@$SERVERIP
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
	
	echo "Are you sure you want to download? (YES/NO)"
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

SERVERNAME=$1
SERVERIP=$2
ABSOLUTEPATH=$3

echo "$SERVERNAME"
echo "$SERVERIP"
echo "$ABSOLUTEPATH"

init
server_status

if [[ $STATUS == 1 ]]
then
	echo "Connection established"
else
	echo "Connection failed"	
	exit
fi

i=0
while IFS= read -r line; do
	EXCLUDEARRAY[i]=$line
	i=($i+1)
done < "$EXCLUDEFILE"

upload

