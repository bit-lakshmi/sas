#!/Python27/python
print "Content-type: text/html\n\n"

from datetime import datetime
from dateutil.tz import tzutc, tzlocal

utc = datetime.now(tzutc())
print('UTC:   ' + str(utc))

local = utc.astimezone(tzlocal())
print('Local: ' + str(local))
