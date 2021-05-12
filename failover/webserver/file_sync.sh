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
	elif [[ $i == 5 ]]
        then
          CLIENTIP="${line:7}"
	elif [[ $i == 6 ]]
	then
	  CLIENTNAME="${line:9}"
	fi
	i=$((i+1))
	done < $CONFIG
}

download () {
	ssh $SERVER "cd $ABSOLUTEPATH;./distribution_client_dl.sh '$CLIENTNAME' '$CLIENTIP' '$CLIENTPATH'"
}

init

echo "$EXCLUDEFILE is exluding the following files:"

i=0
while IFS= read -r line; do
	EXCLUDEARRAY[i]=$line
	i=($i+1)
done < "$EXCLUDEFILE"
echo ${EXCLUDEARRAY[*]}
echo "========================================"

download
