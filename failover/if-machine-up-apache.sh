#!/bin/bash

CONFIG=~/bin/standby.conf
STATUS=0

init () {
	i=0
	while IFS= read -r line;
	do
	if [[ $i == 0 ]]
	then
          MACHINEIP="${line:12}"
  	elif [[ $i == 1 ]]
	then
      	  ONSTANDBY="${line:12}"
  	elif [[ $i == 2 ]]
	then
	  LOGFILE="${line:10}"	
	fi
	i=$((i+1))
	done < $CONFIG
  }
machine_status () {
	ping -c 3 $MACHINEIP > /dev/null && STATUS=1 || STATUS=0
}

init
echo ""
time=$(date +"%c")
echo $time

if [[ $ONSTANDBY != 1 ]]
then
	echo "Not on standby"
	cp -r /var/www/html /home/tim/bin/webserver/
	# For server logs
	echo "$time:" >> "$LOGFILE"
	echo "Webserver backup made" >> "$LOGFILE"
	# For local logs
	echo "Webserver backup made"
	exit
fi

machine_status

if [[ $STATUS == 1 ]]
then
	echo "Machine($MACHINEIP) is up"
	exit
else
	# For server logs
	echo "$time:" >> "$LOGFILE"
	echo "Machine: $MACHINEIP is down, swapping in\n" >> "$LOGFILE"
	# For local logs
        echo "Machine: $MACHINEIP is down, swapping in"
	sed -i '2s/.*/on-standby: 0/' $CONFIG

	echo "tim123" | sudo -S /etc/init.d/apache2 start	
fi
