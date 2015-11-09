#! /usr/bin/python
#
import urllib2
import json
import sys

class olsr_graph():

    def __init__(self,json_url):
        self.json_url=json_url
        
    def get_json(self):
        resp=urllib2.urlopen(self.json_url)
        f=resp.read()
        f_json=json.loads(f)
        invert_repo={}
        for address in f_json["mid"]:
            invert_repo[address["ipAddress"]]=[]
            for aliases in address["aliases"]:
                invert_repo[address["ipAddress"]].append(aliases["ipAddress"])
            print address["ipAddress"],invert_repo[address["ipAddress"]]

url=sys.argv[1]
c=olsr_graph(url)
c.get_json()

                
