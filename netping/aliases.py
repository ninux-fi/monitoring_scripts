#! /usr/bin/python
#
import urllib2
import json
import sys


class olsr_graph():

    def __init__(self, json_url):
        self.json_url = json_url

    def get_mid(self):
        mid_url = self.url + "/mid"
        resp = urllib2.urlopen(mid_url)
        f = resp.read()
        f_json = json.loads(f)
        invert_repo = {}
        for address in f_json["mid"]:
            invert_repo[address["ipAddress"]] = []
            for aliases in address["aliases"]:
                invert_repo[address["ipAddress"]].append(aliases["ipAddress"])
            print address["ipAddress"], invert_repo[address["ipAddress"]]

    def get_config(self):
        resp = urllib2.urlopen(self.json_url)
        f = resp.read()
        f_json = json.loads(f)
        invert_repo = {}
        for address in f_json["mid"]:
            invert_repo[address["ipAddress"]] = []
            for aliases in address["aliases"]:
                invert_repo[address["ipAddress"]].append(aliases["ipAddress"])
            print address["ipAddress"], invert_repo[address["ipAddress"]]

    def usage(self):
        print "./alias.py jsonplugin_url"
        print "ex: ./alias.py http://localhost:9090/"



url = sys.argv[1]
c = olsr_graph(url)
c.get_mid()
