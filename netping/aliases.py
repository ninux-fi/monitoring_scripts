#! /usr/bin/python
#
import urllib2
import json
import sys
import requests
import socket
from distutils import spawn
import time
import os


class olsr_graph():

    def __init__(self, json_url):
        self.json_url = json_url
        # FIXME check return value here
        self.curl = spawn.find_executable("curl")

    def get_or_curl(self, url):
        resp = urllib2.urlopen(url)
        try:
            s = resp.read()
        except socket.error:
            temp_file = "/tmp/netping_tmp_" + str(int(time.time()))
            curl_string = self.curl + " " + url + " -o " + temp_file
            os.popen(curl_string)
            f = open(temp_file, "r")
            s = f.read()
            f.close()
        return s

    def get_mid(self):
        mid_url = self.json_url + "/mid"
        resp = urllib2.urlopen(mid_url)
        f = resp.read()
        f_json = json.loads(f)
        # invert_repo maps a secondari interface IP to a primary one
        invert_repo = {}
        for address in f_json["mid"]:
            invert_repo[address["ipAddress"]] = []
            for aliases in address["aliases"]:
                invert_repo[address["ipAddress"]].append(aliases["ipAddress"])
        return invert_repo

    def get_config(self):
        conf_url = self.json_url + "/conf"
        resp = urllib2.urlopen(conf_url)
        f = resp.read()
        f_json = json.loads(f)
        main_IP = f_json["config"]["mainIpAddress"]
        return main_IP

    def get_interfaces(self):
        """ OLSR plugins returns a wrong JSON, without
        the opening bracket """
        intf_url = self.json_url + "/interfaces"
        f = self.get_or_curl(intf_url)
        try:
            f_json = json.loads(f)
        except ValueError:
            patched_f = "{" + f
            f_json = json.loads(patched_f)
        all_addresses = []
        for intf in f_json["interfaces"]:
            all_addresses.append(intf["ipv4Address"])
        return all_addresses

    def usage(self):
        print "./alias.py jsonplugin_url"
        print "ex: ./alias.py http://localhost:9090/"

url = sys.argv[1]
c = olsr_graph(url)
invert_repo = c.get_mid()
main_IP = c.get_config()
invert_repo[main_IP] = [i for i in c.get_interfaces() if i != main_IP]

for main_IP, aliases in invert_repo.items():
    print main_IP, aliases

