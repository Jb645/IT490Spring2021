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
	  echo "Machine ip: $MACHINEIP"
  	elif [[ $i == 1 ]]
	then
      	  ONSTANDBY="${line:12}"
	fi
	i=$((i+1))
	done < $CONFIG
  }
machine_status () {
	ping -c 3 $MACHINEIP > /dev/null && STATUS=1 || STATUS=0
}

init

if [[ $ONSTANDBY != 1 ]]
then
	echo "Machine is not on standby"
	exit
fi

machine_status

if [[ $STATUS == 1 ]]
then
	echo "Machine is up"
	exit
else
	echo "Machine is down, swapping in"
	sed -i '2s/.*/on-standby: 0/' $CONFIG
fi
