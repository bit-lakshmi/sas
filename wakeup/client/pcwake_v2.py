#pip install apscheduler
import urllib, json, urllib2, json, os, datetime, subprocess, logging
from apscheduler.schedulers.background import BlockingScheduler as scheduler
logging.basicConfig()


#os.environ['http_proxy'] = '192.168.23.90:808'
#os.environ['https_proxy'] = '192.168.23.90:808'
def myfn():
    api = "http://www.solartsstudio.com/wakeup/restful"
    #api = "http://sas.com/wakeup/restful.php"
    wml = 'D:\wake\WakeMeOnLan.exe /wakeupmulti '
    hdr = {'User-Agent': 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.64 Safari/537.11'}
    
    url = api + "?api=ping"
    req = urllib2.Request(url, headers=hdr)
    try:
        page = urllib2.urlopen(req)
    except urllib2.HTTPError, e:
        print e.fp.read()

    data = json.loads(page.read())
    status = data['status']
    if(status == '1'):
        wml = wml + data['ip']
        os.system(wml)
        print  datetime.datetime.now()
        print "Starting : " + data['ip']
        url = api + "?api=pingupdate&sip=" + data['ip']
        req = urllib2.Request(url, headers=hdr)
        try:
            page = urllib2.urlopen(req)
        except urllib2.HTTPError, e:
            print e.fp.read()
        print page.read()
    else:
        print  datetime.datetime.now()

# Execute your code before starting the scheduler
print('Starting scheduler, ctrl-c to exit!')

sch = scheduler()
sch.add_job(myfn, 'interval', seconds=10)
try:
    sch.start()
except KeyboardInterrupt:
    print('Scheduler stoped by user exiting!')
pass
