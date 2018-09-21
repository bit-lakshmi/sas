#!/Python27/python
print "Content-type: text/html\n\n"

import os, time, mysql.connector, cgi, datetime, time, urllib, json
from fnmatch import fnmatch
now = datetime.datetime.now()

form = cgi.FieldStorage()
SCODE = form.getvalue("pcode")
HOST = form.getvalue("host")
cnx = mysql.connector.connect(host='192.168.23.92', port=3306, database='sas', user='admin', password='2648')
cursor = cnx.cursor()
sql = "SELECT pdisk_name, pname, pstype FROM projects WHERE pcode = '{}'".format(SCODE)
cursor.execute(sql)

STYPE = ""
SFOLDER = ""
for pdata in cursor:
    SFOLDER = pdata[0]
    STYPE = pdata[2]
    print '<div class="card-content"><blockquote><h5>%s</h5></blockquote></div>' % pdata[1]

#SFOLDER = "18 The elephant and king of mice"
#SCODE = "EKM"
root = 'D:/SAS-Backup/Library/Sync/Punchtantra stories/Stories/'+SFOLDER+'/06 Animation'
pattern = "*.mb"

if STYPE == 1:
    root = 'D:/SAS-Backup/Library/Sync/Occasional Films/'+SFOLDER+'/06 Animation'


import sqlite3
s3db = sqlite3.connect(':memory:')
sql3 = s3db.cursor()
sql3.execute('''
    CREATE TABLE shots(id INTEGER PRIMARY KEY, isnew INT, ismod INT, sname VERCHAR, filename, VERCHAR,
                       path VERCHAR, newmodi VERCHAR, lastmodi VERCHAR, created VERCHAR)
''')
s3db.commit()




cnx = mysql.connector.connect(host='192.168.23.92', port=3306, database='sas', user='admin', password='2648')
cursor = cnx.cursor()

def storeshot(isnew, ismod, sname, filename, path, newmodi, lastmodi, created):
    sql3.execute('''INSERT INTO shots(isnew, ismod, sname, filename, path, newmodi, lastmodi, created)VALUES(?,?,?,?,?,?,?,?)''', (isnew, ismod, sname, filename, path, newmodi, lastmodi, created))
    s3db.commit()


def upadteshot(sname, filename, path, newmodi, lastmodi, created):
    syncshots = sql3.execute('''SELECT * FROM shots WHERE sname = ? ''', (sname,))
    for shot in syncshots:
        if(shot[5] > newmodi):
            sql3.execute('''UPDATE shots SET isnew = 0, ismod=1, sname= ?, filename = ?, path = ?, newmodi = ?, lastmodi = ?, created = ? WHERE sname = ? ''',(sname, filename, path, newmodi, shot[5], created, sname))
        else:
            sql3.execute('''UPDATE shots SET isnew = 0, ismod=1, sname= ?,filename = ?, path = ?, newmodi = ?, lastmodi = ?, created = ? WHERE sname = ? ''',(sname, filename, path, newmodi, newmodi, created, sname))
    s3db.commit()


def mtimetomel(FILE_PATH):
    if(os.path.exists(FILE_PATH)):
        return datetime.datetime.strptime(time.ctime(os.path.getmtime(FILE_PATH)), '%a %b %d %H:%M:%S %Y').strftime('%Y/%m/%d %H:%M:%S')
    else:
        return ""

def amstrtounix(x):
    return time.mktime(datetime.datetime.strptime(x, "%Y/%m/%d %H:%M:%S").timetuple())

for path, subdirs, files in os.walk(root):
    for name in files:
        if fnmatch(name, pattern):
            fpath = os.path.join(path, name)
            SFRAME = []
            JSDATA = ""
            SJSON = 0
            JSPATH = fpath.replace(".mb", ".json")
            if(os.path.exists(JSPATH)):
                with open(JSPATH) as f:
                    JSDATA = json.load(f)
                    SJSON = 1

            FILENAME = os.path.basename(fpath)
            if "Shot_" in FILENAME:
                FILENAME = FILENAME.split('.')[0]
                AM_file_pre = FILENAME
                FILENAME = FILENAME.split('_')
                AM_SNAME = FILENAME[1]
                cursor.execute("SELECT id FROM tasks WHERE pcode = %s AND sname = %s", (SCODE, AM_SNAME))
                isShot = 1
                for pdata in cursor:
                    if(pdata[0] != ""):
                        isShot = 0
                if(isShot):
                    addmodi = 1
                    syncshots = sql3.execute('SELECT * FROM shots')
                    for shot in syncshots:
                        if(shot[3] == AM_SNAME):
                            upadteshot(AM_SNAME,os.path.basename(fpath),fpath.replace(os.path.basename(fpath), ""), mtimetomel(fpath), "", mtimetomel(fpath))
                            addmodi = 0
                    if(addmodi):
                        storeshot(1,0,AM_SNAME,os.path.basename(fpath),fpath.replace(os.path.basename(fpath), ""),mtimetomel(fpath), "", mtimetomel(fpath))
                else:
                    ofpath = "D:/SAS-Backup/Punchtantra stories/Stories/"+SFOLDER+"/07 Lighting/Shot_"+os.path.basename(fpath).split("_")[1]+"/" + os.path.basename(fpath)
                    foPath = "D:/SAS-Backup/Punchtantra stories/Stories/"+SFOLDER+"/07 Lighting/Shot_"+os.path.basename(fpath).split("_")[1]
                    if STYPE == 1:
                        ofpath = "D:/SAS-Backup/Occasional Films/"+SFOLDER+"/07 Lighting/Shot_"+os.path.basename(fpath).split("_")[1]+"/" + os.path.basename(fpath)

                    OJSPATH = ofpath.replace(".mb", ".json")
                    OJDATA = ""
                    OJSON =  0

                    def getupdateotime(fofpath):
                        if(OJSON):
                            return OJDATA['ctime']
                        else:
                            return mtimetomel(fofpath)

                    def getsynctime(fofpath):
                        if(SJSON):
                            return JSDATA['ctime']
                        else:
                            return mtimetomel(fofpath)

                    if(os.path.exists(OJSPATH)):
                        with open(OJSPATH) as f:
                            OJDATA = json.load(f)
                            OJSON = 1

                    if(os.path.exists(ofpath)):
                        if(getsynctime(fpath) != getupdateotime(ofpath)):
                            if(os.path.exists(JSPATH)):
                                upadteshot(AM_SNAME,os.path.basename(fpath),fpath.replace(os.path.basename(fpath), ""),JSDATA['ctime'], JSDATA['ctime'], JSDATA['ctime'])
                            else:
                                upadteshot(AM_SNAME,os.path.basename(fpath),fpath.replace(os.path.basename(fpath), ""),mtimetomel(fpath), mtimetomel(ofpath), mtimetomel(fpath))
                    else:
                        addmodi = 1
                        print "SN: " +  AM_SNAME + "<br>"
                        syncshots = sql3.execute('SELECT * FROM shots')
                        for shot in syncshots:
                            print "<pre>"
                            print shot
                            print "</pre>"
                            if(shot[3] == AM_SNAME):
                                if(os.path.exists(JSPATH)):
                                    upadteshot(AM_SNAME,os.path.basename(fpath),fpath.replace(os.path.basename(fpath), ""),JSDATA['ctime'], JSDATA['ctime'], JSDATA['ctime'])
                                else:
                                    upadteshot(AM_SNAME,os.path.basename(fpath),fpath.replace(os.path.basename(fpath), ""),mtimetomel(fpath), "", mtimetomel(fpath))
                                
                            else:
                                addmodi = 0
                        #datetime.date(getsynctime(fpath)) < datetime.date(getupdateotime(ofpath)))
                        """if(amstrtounix(getupdateotime(ofpath)) < amstrtounix(getsynctime(fpath))):
                            print "New"
                        else:
                            print "Old"""
                        if(addmodi):
                            print "<br>Sync: " + getsynctime(fpath)
                            print "<br>"
                            print "X: " + getupdateotime(ofpath)
                            print "<br><br>" 
                            if(os.path.exists(JSPATH)):
                                storeshot(1,0,AM_SNAME,os.path.basename(fpath),fpath.replace(os.path.basename(fpath), ""),JSDATA['ctime'], "", JSDATA['ctime'])

                            else:
                                storeshot(1,0,AM_SNAME,os.path.basename(fpath),fpath.replace(os.path.basename(fpath), ""),mtimetomel(fpath), "", mtimetomel(fpath))


syncshots = sql3.execute('SELECT * FROM shots ORDER BY sname ASC')
for shot in syncshots:
    if(shot[1]):
        print "<div class=\"card white darken-1\"><div class=\"card-content\"><span class=\"card-title\"><b class='chip green accent-4' style='color: white;'>New Shot</b></span><p>"
    else:
        print "<div class=\"card white darken-1\"><div class=\"card-content\"><span class=\"card-title\"><b class='chip deep-purple darken-3' style='color: white;'>Shot Modified</b></span><p>"
    print "<br>Shot No.: "+ str(shot[3])
    print "<br>File Name: <a id='syncpath' data-host='"+HOST+"' data-pdiskn='"+ urllib.quote_plus(str(shot[6]))+"' style='cursor: pointer;'>" + str(shot[4]) + "</a>"
    print("<br>Last modified: %s" % str(shot[7]))
    print("<br>Created: %s" % str(shot[9]))
    print "<p></div><div class=\"card-action\"><a id='syncpath' data-host='{}' data-pdiskn='{}' style='cursor: pointer;'>Explorer</a></div></div>".format(HOST,urllib.quote_plus(str(shot[6])))
cnx.close()
s3db.close()
