import subprocess
from subprocess import call
import datetime

backup_loc = "dbdump"

print "dumping database"
call("mysqldump -u root -proot wordpress > "+backup_loc, shell=True)

print "dumped database to "+backup_loc

call("git add "+backup_loc, shell=True)

call("git commit -m'backed up database on "+str(datetime.datetime.today())+"'", shell=True)

call("git push",shell=True)
