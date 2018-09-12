#!/Python27/python
print "Content-type: text/html\n\n"

import socket
import cgi
import os, json


form = cgi.FieldStorage()
host = form.getvalue("host")
data =  form.getvalue("data")
port = 5555 

host = host.split('-')
for x in host:
	try:
		s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
		s.connect((x, port))
		encoded_str = json.dumps({"fname":"update","data":{"title":"SAS Update", "data":data}}, sort_keys=True )
		s.sendall(encoded_str)
		#data = s.recv(1024)
		s.close()
	except Exception as e:
		print e