#!/Python27/python
print "Content-type: text/html\n\n"
import sys, os, cgi, mysql.connector

form = cgi.FieldStorage()
pcode = form.getvalue("pcode")
psno = form.getvalue("sno")
pext = form.getvalue("ext")
ptype = form.getvalue("type")


cnx = mysql.connector.connect(host='192.168.23.92', port=3306, database='sas', user='admin', password='2648')
cursor = cnx.cursor()

sql = "SELECT pdisk_name, pname, pstype FROM projects WHERE pcode = '{}'".format(pcode)
cursor.execute(sql)

STYPE = ""
SFOLDER = ""
for pdata in cursor:
    SFOLDER = pdata[0]
    STYPE = pdata[2]

if ptype == "bg":
    root = 'D:/SAS-Backup/Punchtantra stories/Stories/'+SFOLDER+'/01 Story Board/Bgs/PNG/'
elif ptype == "sb":
    root = 'D:/SAS-Backup/Punchtantra stories/Stories/'+SFOLDER+'/01 Story Board/SB/'

pext = pext.format(psno)
root = root + pext

src = root


if(os.path.exists(src)):
    pass
else:
    pre, ext = os.path.splitext(src)
    src = pre + ".jpg"
#src = r"D:\SAS-Backup\Punchtantra stories\Stories\17 The Jackal and the Drum\01 Story Board\Bgs\PNG\BG_( 7).png"

data_uri = open(src, 'rb').read().encode('base64').replace('\n', '')
print "data:image/jpeg;base64,{0}".format(data_uri)
#img_tag = '<img src="data:image/jpeg;base64,{0}" alt="" style="width: 400px;"/>'.format(data_uri)
 
#print(img_tag)
