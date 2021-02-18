#! /usr/bin/python3

import requests

url = "https://visual-crossing-weather.p.rapidapi.com/history"
#format better
#get from query
querystring = {"startDateTime":"2019-01-01T00:00:00","aggregateHours":"24","location":"Washington,DC,USA","endDateTime":"2019-01-03T00:00:00","unitGroup":"us","dayStartTime":"8:00:00","contentType":"csv","dayEndTime":"17:00:00","shortColumnNames":"0"}

headers = {
    'x-rapidapi-key': "d5fb326ed9mshe394b66d2f61ec9p111228jsnbb4bbf3816fa",
    'x-rapidapi-host': "visual-crossing-weather.p.rapidapi.com"
    }

response = requests.request("GET", url, headers=headers, params=querystring)

print(response.text)
