#!/usr/bin/python3
# -*- coding: utf-8 -*-

import mysql.connector

mydb = mysql.connector.connect(
  host = "localhost",
  user = "root",
  passwd = "schleichkatze",
  database = "helmstedt"
)

mycursor = mydb.cursor()
mycursor.execute("SELECT id, gnd FROM helmstedt.temp_prof_kat")
myresult = mycursor.fetchall()
gnds = [x[1] for x in myresult if x[1] != None]
print('|'.join(gnds))


"""
# Eine Liste (geordnet, indexiert und veränderlich)
mylist = ['Lerche', 'Schneider', 'Zimmermann', 'Kästner', 'Raabe', 'Schmidt-Glintzer', 'bURSCHEL']
mylist[len(mylist) - 1] = mylist[len(mylist) - 1].swapcase()
mylist.append('Ritter Rost')
mylist.insert(0, 'Zimmermann')
print(mylist)
"""

"""
# Ein Tupel (ist unveränderlich)
mytuple = ('Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag')
#print(mytuple[3:6])
"""

"""
# Ein Set (unindexiert und ungeordnet, Elemente sind unveränderlich, können aber vermehrt oder reduziert werden)
myset = {'Adenauer', 'Erhard', 'Kiesinger', 'Brandt', 'Schmidt', 'Kohl', 'Schröder', 'Merkel', 'Schulz'}
myset.remove('Schulz')
myset.add('Kramp-Karrenbauer')
for i in myset:
    print(i)
"""

"""
# Ein Dictionary
mydict = {'Mann':'vyras', 'Frau':'moteris','Fisch':'žuvis', 'Biber':'bebras', 'Stadt':'miestas', 'König':'karalius'}
for x, y in mydict.items():
  print(x + ' heißt auf Litauisch ' + y)
"""
    
"""
# Eine Datumsoperation
import time
import datetime

time = time.localtime(time.time())
print(time)
"""

"""
# Eine Funktion
def makeName(forename, surname, title=""):
	result = forename + " " + surname
	if title:
		result = title + " " + result
	return result

print(makeName("Hartmut", "Beyer", "Magister artium"))
"""

"""
# Eine Klasse
class Person:
	def __init__(self, forename, surname):
		self.forename = forename
		self.surename = surname

person = Person('Ben', 'Gurion')
print(person.forename)
"""

"""
# Eine Klasse
class Language:
	def __init__(self, code):
		self.codes = {
			"eng":"Englisch", 
			"ger":"Deutsch", 
			"fre":"Französisch",
			"rus":"Russisch"
		}
		if code not in self.codes:
			self.name = code
			return
		self.name = self.codes[code]		
	
lang = Language("rus")
print(lang.name)
"""

"""
# Eine Datei aus dem Netz auslesen
import urllib.request as ur
url = "http://diglib.hab.de/edoc/ed000228/1623_06.xml"
fileobject = ur.urlopen(url)
string = fileobject.read()
print(string)
"""

"""
# Eine XML-Datei parsen
import xml.etree.ElementTree as et
tree = et.parse('test.xml')
root = tree.getroot()
nbs = root.findall('.//{http://www.tei-c.org/ns/1.0}rs')
name = ""
for ent in nbs:
    if ent.get('type') == 'person':
        name = str(ent.text).strip()
        ref = str(ent.get('ref')).strip()
        print(name + ' - ' + ref)
"""

"""
# Laden und Auslesen einer XML-Datei im Netz
import urllib.request as ur
import xml.etree.ElementTree as et

url = "http://diglib.hab.de/edoc/ed000228/1623_08.xml"
fileobject = ur.urlopen(url)
tree = et.parse(fileobject)
root = tree.getroot()
nbs = root.findall('.//{http://www.tei-c.org/ns/1.0}rs')
name = ""
for ent in nbs:
    if ent.get('type') == 'person':
        name = str(ent.text).strip()
        ref = str(ent.get('ref')).strip()
        print(name + ' - ' + ref)
"""
