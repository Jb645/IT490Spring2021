INSTRUCTIONS:
-Place the files in ~/bash/
-Designate the target IP and absolute path to your log file in standby.conf
-Enter the command: 'crontab -e' and paste the contents of cronjob.txt

EXPLANATION:
-Cronjob runs if-machine-up.sh, which pings the specified ip 3 times. 
-If the ping fails, the machine standby status changes from 1 to 0, meaning that is no longer on standby. 
-While the target machine is up, the machine will periodically sync files from it to ensure that when the machine goes down, it can pick up where it left off.
-Once the machine is longer on standby, it will write to the logs that target machine is down and will turn on a specifc server so that clients can be redirected to it. *NOT IMPLEMENTED YET*
