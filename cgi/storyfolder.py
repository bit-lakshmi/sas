#!/Python27/python
print "Content-type: text/html\n\n"

import os
import cgi

form = cgi.FieldStorage()
PNO = form.getvalue("pno")
PNAME = form.getvalue("pname")
PTYPE = form.getvalue("ptype")

MPATH = "D:/SAS-Backup/Punchtantra stories/Stories/"

if PTYPE == "1":
    MPATH = "D:/SAS-Backup/Occasional Films/"

NSTORY = MPATH + PNO + " " + PNAME

NSTORY = os.path.normpath(NSTORY)

if not os.path.exists(NSTORY):
    os.makedirs(NSTORY)
    CONCEPT = os.path.normpath(NSTORY + "/00 Concept")
    os.makedirs(CONCEPT)
    SBM = os.path.normpath(NSTORY + "/01 Story Board")
    os.makedirs(SBM)
    SB = os.path.normpath(SBM + "/SB")
    os.makedirs(SB) 
    BGs = os.path.normpath(SBM + "/BGs")
    os.makedirs(BGs)
    PSD = os.path.normpath(BGs + "/PSD")
    os.makedirs(PSD)
    JPG = os.path.normpath(BGs + "/JPG")
    os.makedirs(JPG)


    AUDIO = os.path.normpath(NSTORY + "/02 Audio")
    os.makedirs(AUDIO)
    Dialogue = os.path.normpath(AUDIO + "/Dialogue")
    os.makedirs(Dialogue)
    AFINAL = os.path.normpath(AUDIO + "/Final")
    os.makedirs(AFINAL)


    MODELING = os.path.normpath(NSTORY + "/03 Modeling")
    os.makedirs(MODELING)
    Assets = os.path.normpath(MODELING + "/Assets")
    os.makedirs(Assets)
    Ch = os.path.normpath(MODELING + "/Character")
    os.makedirs(Ch)

    TEXT = os.path.normpath(NSTORY + "/04 Texturing")
    os.makedirs(TEXT)
    Assets = os.path.normpath(TEXT + "/Assets")
    os.makedirs(Assets)
    Ch = os.path.normpath(TEXT + "/Character")
    os.makedirs(Ch)

    RIG = os.path.normpath(NSTORY + "/05 Rigging")
    os.makedirs(RIG)
    Assets = os.path.normpath(RIG + "/Assets")
    os.makedirs(Assets)
    Ch = os.path.normpath(RIG + "/Character")
    os.makedirs(Ch)

    ANI = os.path.normpath(NSTORY + "/06 Animation")
    os.makedirs(ANI)
    LIGHT = os.path.normpath(NSTORY + "/07 Lighting")
    os.makedirs(LIGHT)
    COMP = os.path.normpath(NSTORY + "/09 Comp")
    os.makedirs(COMP)
    FINAL = os.path.normpath(NSTORY + "/10 Final")
    os.makedirs(FINAL)

    PM = os.path.normpath(NSTORY + "/11 Promo Materials")
    os.makedirs(PM)
    PMCOMP = os.path.normpath(PM + "/Comp")
    os.makedirs(PMCOMP)
    PMELE = os.path.normpath(PM + "/Element")
    os.makedirs(PMELE)
    PMTREN = os.path.normpath(PM + "/Render")
    os.makedirs(PMTREN)
    PMTHUMB = os.path.normpath(PM + "/Thumbnail")
    os.makedirs(PMTHUMB)

pass

print "1"
