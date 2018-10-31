#!/Python27/python
print "Content-type: text/html\n\n"

import socket
import cgi
import os, json, mysql.connector
form = cgi.FieldStorage()
host = form.getvalue("host")
pdisk =  form.getvalue("pdiskn")
psname =  form.getvalue("psname")
ftype =  form.getvalue("t")
port = 5555

cnx = mysql.connector.connect(host='192.168.23.92', port=3306, database='sas', user='admin', password='2648')
cursor = cnx.cursor()
sql = "SELECT pstype FROM projects WHERE pdisk_name = '{}'".format(pdisk)
cursor.execute(sql)

SFOLDER = ""
for pdata in cursor:
    SFOLDER = pdata

MPATH = "X:\Punchtantra stories\Stories"
MPATH_NAME = "Punchtantra stories\Stories"
if SFOLDER[0] == 1:
    MPATH = "X:\Occasional Films"
    MPATH_NAME = "Occasional Films"

if SFOLDER[0] == 2:
    MPATH = "X:\Hyderabad Project"
    MPATH_NAME = "Hyderabad Project"

if SFOLDER[0] == 3:
    MPATH = "X:\Oxford Project"
    MPATH_NAME = "Oxford Project"



SEL_STORY = pdisk



CS_PATH = os.path.normpath(MPATH + "/" + SEL_STORY)
COMP_PATH = os.path.normpath(CS_PATH + "/09 Comp")
SCENE_PATH = os.path.normpath(CS_PATH + "/07 Lighting")
FINAL_PATH = os.path.normpath(CS_PATH + "/10 Final")
BG_PATH = os.path.normpath(CS_PATH + "/01 Story Board")


SHOT_NO = psname
SUB_SHOT = ""
if not SHOT_NO.isdigit():
	SUB_SHOT = SHOT_NO.split("-")
	SHOT_NO = SUB_SHOT[0]
	SUB_SHOT = SUB_SHOT[1].upper()
pass

SH_NAME = os.path.normpath('Shot_' + str(SHOT_NO))


CSH_PATH = os.path.normpath(COMP_PATH + "/"+ SH_NAME)
if ftype == 'sf':
    CSH_PATH = os.path.normpath(SCENE_PATH + "/"+ SH_NAME)


if ftype == 'ff':
    CSH_PATH = os.path.normpath(FINAL_PATH + "/"+ SH_NAME)


if ftype == 'bf':
    CSH_PATH = os.path.normpath(BG_PATH)


if ftype == 'syf':
    CSH_PATH = os.path.normpath("X:/Library/Sync/" + MPATH_NAME + "/" + SEL_STORY + "/06 Animation")
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
