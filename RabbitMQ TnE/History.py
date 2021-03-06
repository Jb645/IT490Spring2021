#! /usr/bin/python3

import csv
import codecs
import urllib.request
import requests

import sys
from datetime import date

key = open("API_Key.txt","r")


BaseURL = 'https://weather.visualcrossing.com/VisualCrossingWebServices/rest/services/timeline/'





try:
    Place = sys.argv[1]
except:
    Place = "Washington"

try:
    Date = sys.argv[2]
except:
    Date = date.today()


DegreeSystem = 'unitGroup=' + urllib.parse.quote("us")

Place_Query = Place+'/'
APIKey = '&key='+urllib.parse.quote(key.read()[:-1])
print(Date)

URL= BaseURL+Place_Query+str(Date)+"?"+DegreeSystem+APIKey

response = requests.get(str(URL))
x =response.json()
y=x.get('days')
print ("Location = "+ str(x.get('address'))+"\n","Date = " +str(y[0]["datetime"])+"\n","Temperature(f) = " +str(y[0]["temp"])+"\n","Condition = "+ str(y[0]["conditions"])+"\n" )
#print(sys.argv[1],sys.argv[2])
