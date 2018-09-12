#!/Python27/python
print "Content-type: text/html\n\n"

import socket
import cgi
import os, json, mysql.connector
form = cgi.FieldStorage()
host = form.getvalue("host")
pdisk =  form.getvalue("pdiskn")
psname =  form.getvalue("psname")
port = 5555 

cnx = mysql.connector.connect(host='192.168.23.92', port=3306, database='sas', user='admin', password='2648')
cursor = cnx.cursor()
sql = "SELECT pstype FROM projects WHERE pdisk_name = '{}'".format(pdisk)
cursor.execute(sql)

SFOLDER = ""
for pdata in cursor:
    SFOLDER = pdata

MPATH = "X:\Punchtantra stories\Stories"

if SFOLDER[0] == 1:
	MPATH = "X:\Occasional Films"



SEL_STORY = pdisk



CS_PATH = os.path.normpath(MPATH + "/" + SEL_STORY)
COMP_PATH = os.path.normpath(CS_PATH + "/09 Comp")


SHOT_NO = psname
SUB_SHOT = ""
if not SHOT_NO.isdigit():
	SUB_SHOT = SHOT_NO.split("-")
	SHOT_NO = SUB_SHOT[0]
	SUB_SHOT = SUB_SHOT[1].upper()
pass

SH_NAME = os.path.normpath('Shot_' + str(SHOT_NO))


CSH_PATH = os.path.normpath(COMP_PATH + "/"+ SH_NAME)
#if not os.path.isdir(CSH_PATH):
#	CSH_PATH = os.path.normpath("X:/Library/Sync/Punchtantra stories/Stories/"+ SEL_STORY + "/06 Animation")


if SUB_SHOT == "":
	try:
		s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
		s.connect((host, port))
		encoded_str = json.dumps({"fname":"fpath","data":CSH_PATH}, sort_keys=True )
		print encoded_str
		s.sendall(encoded_str)
		#data = s.recv(1024)
		s.close()
	except Exception as e:
		print e
else:
	CSH_PATH = os.path.normpath(CSH_PATH + "/" + SUB_SHOT)
	
	try:
		s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
		s.connect((host, port))
		encoded_str = json.dumps({"fname":"fpath","data":CSH_PATH}, sort_keys=True )
		print encoded_str
		s.sendall(encoded_str)
		#data = s.recv(1024)
		s.close()
	except Exception as e:
		print e
pass




