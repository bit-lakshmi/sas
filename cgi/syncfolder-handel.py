#!/Python27/python
print "Content-type: text/html\n\n"

import socket
import cgi
import os, json
form = cgi.FieldStorage()
host = form.getvalue("host")
pdisk =  form.getvalue("pdiskn")
pdisk = pdisk.replace("D:/SAS-Backup", "X:")
pdisk = pdisk.replace("/", "\\")
port = 5555    

try:
	s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
	s.connect((host, port))
	encoded_str = json.dumps({"fname":"fpath","data":pdisk}, sort_keys=True )
	print encoded_str
	s.sendall(encoded_str)
	#data = s.recv(1024)
	s.close()
except Exception as e:
	print e




