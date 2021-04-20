#!/bin/bash

server_status () {
	ping -c 1 192.168.1.72 > /dev/null && echo 'Server is up' || echo 'Server is down'
}

server_status
