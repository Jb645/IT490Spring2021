#! /usr/bin/python3
import http.client

conn = http.client.HTTPSConnection("visual-crossing-weather.p.rapidapi.com")

headers = {
    'x-rapidapi-key': "d5fb326ed9mshe394b66d2f61ec9p111228jsnbb4bbf3816fa",
    'x-rapidapi-host': "visual-crossing-weather.p.rapidapi.com"
    }

conn.request("GET", "/history?startDateTime=2019-01-01T00%3A00%3A00&aggregateHours=24&location=Washington%2CDC%2CUSA&endDateTime=2019-01-03T00%3A00%3A00&unitGroup=us&dayStartTime=8%3A00%3A00&contentType=csv&dayEndTime=17%3A00%3A00&shortColumnNames=0", headers=headers)

res = conn.getresponse()
data = res.read()

print(data.decode("utf-8"))
