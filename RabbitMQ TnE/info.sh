#!/bin/sh
ip=$(hamachi | grep -o '25\.[0-9]\{1,3\}\.[0-9]\{1,3\}\.[0-9]\{1,3\}')
hn=$(hostname)
echo $hn@$ip
pwd 
