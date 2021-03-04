#! /usr/bin/python3

import requests
key = open("Output.txt","w")

url = "https://weather.visualcrossing.com/VisualCrossingWebServices/rest/services/weatherdata/history?&aggregateHours=24&startDateTime=2019-06-13T00:00:00&endDateTime=2019-06-20T00:00:00&unitGroup=uk&contentType=csv&dayStartTime=0:0:00&dayEndTime=0:0:00&location=Sterling,VA,US&key=d5fb326ed9mshe394b66d2f61ec9p111228jsnbb4bbf3816fa"
#format better
#get from query
querystring = { "aggregateHours":"24",
                "location":"Washington,DC,USA",
                "startDateTime":"2019-01-01T00:00:00",
                "endDateTime":"2019-01-01T23:00:00",
                "unitGroup":"us",
                "contentType":"csv",
                "shortColumnNames":""}

headers = {
    'x-rapidapi-key': "d5fb326ed9mshe394b66d2f61ec9p111228jsnbb4bbf3816fa" ,
    'x-rapidapi-host': "visual-crossing-weather.p.rapidapi.com"
    }

response = requests.request("GET", url)


key.write(response.text)
key.close()
