#!/bin/bash

CONFIG=~/git/IT490Spring2021/standby.conf
STATUS=0

init () {
	i=0
	while IFS= read -r line;
	do
	if [[ $i == 0 ]]
	then
          MACHINEIP="${line:12}"
	  echo "$MACHINEIP"
	fi
	done < $CONFIG
  }
machine_status () {
	ping -c 3 $MACHINEIP > /dev/null && STATUS=1 || STATUS=0
}

init
machine_status

if [[ $STATUS == 1 ]]
then
	echo "Machine is up"
	exit
else
	echo "Machine is down, swapping in"
fi
