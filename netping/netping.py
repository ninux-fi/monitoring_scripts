#! /usr/bin/python
#============================================================================
#============================================================================
#============================================================================
#============================================================================
#============================================================================
#============================================================================
import MySQLdb as mdb
import os
import sys
import string
import time
import datetime
import random
import smtplib
from subprocess import Popen,PIPE
import base64
import ConfigParser
#============================================================================
#============================================================================
class logger():
	def __init__(self,logfile,logarch):
		self.reg=False
		self.report=False
		self.name=""
		self.path=""
		self.records=[]
		self.arch=logarch
		filename=string.split(logfile,"/")
		for f in filename[:-1]:
			self.path=self.path+f+"/"
		for f in filename[-1:]:
			self.name=self.name+f
#		print self.path+self.name
		if (not os.path.isfile(self.path+self.name)):
			dfile = open (self.path+self.name,"w")
			dfile.write ("Nuovo File del "+time.strftime("%c")+"\n")
			dfile.close
#============================================================================
#============================================================================

#============================================================================
#============================================================================
	def event_log(self,message):
#		print time.strftime("%H:%M")
#		print self.path+self.name
		dfile = open (self.path+self.name,"a")
#		print message
		dfile.write (message)
		dfile.close()
		self.archive()
#============================================================================
#============================================================================
	def archive(self):
		if (time.strftime("%H") =="00"):
			if (time.strftime("%M") > "02"):
				if (not self.reg):
					os.system ("mv "+self.path+self.name+" "+self.arch+time.strftime("%H:%M-%a-%b-%d-%Y")+self.name)
					self.reg=True
		elif (time.strftime("%H:%M") > "00:59"):
			if (self.reg):
				self.reg=False
#============================================================================
#============================================================================
	def send_report(self):
		if (time.strftime("%H:%M") =="03:00"):
			if (not self.report):
				f=open("/home/salvatore/netping/mail_account","r")
				self.account=string.split(f.read(),"\n")
				f.close()
	#			print self.account
				self.user=string.split(self.account[1],",")[0]
				self.passw=string.split(self.account[1],",")[1]
				self.sender=string.split(self.account[2],",")[0]
				self.receiver=string.split(self.account[2],",")[1]
				self.provider=self.account[0]
				self.report=True
#                      "0123456789012345678901201234567890123450123456789001234567890112345656780123456789012012345678900"
#                                  22                   15           10        10          10           12        10        30
#				                      "--------------------------------------------------------------------------------------------------------------------"	
#			 	self.testo=self.testo+"|        Nodo          |    Indirizzo  | Rx Byte  | Tx Byte  | Attivita % |   Stato  |           Contatto           |"
#				                      "--------------------------------------------------------------------------------------------------------------------"
#                    	          "|RossiniMusicaDalleOnde|172.119.200.255|4294967296|4294967296|    100%    |Non Attivo|                              "
#				                      "--------------------------------------------------------------------------------------------------------------------"	
				self.testo="--------------------------------------------------------------------------------------------------------------------\n"	
			 	self.testo=self.testo+"|        Nodo          |    Indirizzo  | Rx MByte | Tx MByte | Attivita % |   Stato  |           Contatto           |\n"
				for rec in self.records:
					self.testo=self.testo+"|----------------------|---------------|----------|----------|------------|----------|------------------------------|\n"	
					self.testo=self.testo+rec
				self.testo=self.testo+"--------------------------------------------------------------------------------------------------------------------\n"	
				self.records=[]
				self.server=smtplib.SMTP(self.provider)
				self.server.login(self.user,base64.b64decode(self.passw))
				self.oggetto="NxRM  --  Report del giorno "+time.strftime("%A %d %B %Y")+" --"
				self.messaggio="From:%s\nTo:%s\nSubject:%s\n\n%s" %(self.sender,self.receiver,self.oggetto,self.testo)
				self.server.sendmail(self.sender,self.receiver,self.messaggio)
				self.server.quit()
				self.event_log ("["+time.strftime("%c")+"] "+"NxRM  --  Report del giorno "+time.strftime("%A %B %d %Y")+" --"+ " INVIATO a "+self.receiver+"\n")
		elif (time.strftime("%H:%M") > "03:30"):
			if (self.report):
				self.report=False
#============================================================================
#============================================================================
	def add_node_report(self,nodo,wip,in_b,out_b,act,stato,contatto):
		if (not len(contatto)):
			contatto="Non Conosciuto"
		else:
			contatto=contatto.split("@")[0]
		self.message="|"+nodo.center(22," ")+"|"+wip.center(15," ")+"|"+in_b.rjust(10," ")+"|"+out_b.rjust(10," ")+"|"+act.center(12," ")+"|"+stato.center(10," ")+"|"+contatto.center(30," ")+"|\n"
		self.event_log("["+time.strftime("%c")+"]:"+"Report : "+self.message)
		self.records.append(self.message)
		
#============================================================================
#============================================================================
#============================================================================
# Classe che relizza il comportamento di un Nodo.
# Sono definiti i metodi per campionare, attivare disattivare e rilevare i dati
# E' possibile riconoscere se un nodo e' attivo o no.
# Nel caso sia attivo questo viene campionato in media alla frequenza 
# configurata (12 campioni/ora)
# Nel caso non sia attivo  viene interrogato a frequenza dieci volte inferiore
#(1 Tentativo ogni ora)
# Quando un nodo non risponde alla richiesta di attivazione o di interrogazione
# viene messo in attesa e considerato non attivo.
#
#============================================================================
class NODO():
#============================================================================
# Inizializzazione Nodo:
# nodo_ref = Dizionario che descrive il nodo. Le chiavi sono i nomi delle colonne 
# della tabella nodi DB MysQL
#============================================================================
	def __init__(self,nodo_ref,logobj):
		self.me = nodo_ref
		self.log=logobj
		self.first=True
		self.me["registrato"]=0
		self.me["attivo"]=0
		self.last_status=False
		self.registra()
		f=open("/home/salvatore/netping/mail_account","r")
		self.account=string.split(f.read(),"\n")
		f.close()
#	print self.account
		self.user=string.split(self.account[1],",")[0]
		self.passw=string.split(self.account[1],",")[1]
		self.sender=string.split(self.account[2],",")[0]
		self.receiver=string.split(self.account[2],",")[1]
		self.provider=self.account[0]
#============================================================================
#  Realizza la politica di interrogazione dei nodi
#
#============================================================================
	def run(self):
# Acquisizione dati remoti
#		print self.me["ip"]+" is running"
		self.registra()	# verifica se il nodo e' ancora o di nuovo attivo
		if (self.me ["attivo"]):
#			print self.me["ip"]+" is active"
			if (not self.get_data()): # risposta completa ricevuta ?
				self.deactivate()  # NO ! registra il nodo Non attivo
#		print self.me["ip"]+" cicle end"
		
#============================================================================
# Attiva un nodo al campionamento dichiarandolo attivo
# se risponde al ping. Altrimento lo disattiva
#============================================================================
	def registra (self):
		cmd = "ping -c 1 %s " % self.me["ip"]
		p = Popen(cmd, shell=True, stdin=PIPE, stdout=PIPE, stderr=PIPE, close_fds=True)
		res = p.stdout.read()
		for r in string.split(res,"\n") :
			if r.find("1 received") <> -1:
				self.update()
				return
#			else:
		self.deactivate()
#============================================================================
# Rileva il contatto  registrato del nodo  (es: miaposta@mailprovider.it)
#============================================================================
	def get_contatto(self):
#		result = os.popen("snmpget -c public -v1 "+self.me["ip_man"]+" SNMPv2-MIB::sysContact.0")
#---------------------------------------------------------------------------------------------------------
		cmd = "snmpget -c public -v1 "+self.me["ip_man"]+" SNMPv2-MIB::sysContact.0"
		p = Popen(cmd, shell=True, stdin=PIPE, stdout=PIPE, stderr=PIPE, close_fds=True)
		r = p.stdout.read()
#---------------------------------------------------------------------------------------------------------
#		r=result.readline()
#		print "ho letto :  ",r,type(r)
		if (len(r)):
#			print string.split(r,": ")[1].strip("\n")
			return(string.split(r,": ")[1].strip("\n"))
		else:
			return ""
#============================================================================
# Rileva il luogo di posizionamento dell'antenna registrato del nodo  (es:Lippi)
#============================================================================
	def get_location(self):
#		result = os.popen("snmpget -c public -v1 "+self.me["ip_man"]+" SNMPv2-MIB::sysLocation.0")
#---------------------------------------------------------------------------------------------------------
		cmd = "snmpget -c public -v1 "+self.me["ip_man"]+" SNMPv2-MIB::sysLocation.0"
		p = Popen(cmd, shell=True, stdin=PIPE, stdout=PIPE, stderr=PIPE, close_fds=True)
		r = p.stdout.read()
#		r = string.split(res,"\n")
#---------------------------------------------------------------------------------------------------------
#		r=result.readline()
		if (len(r)):
			return(string.split(r,": ")[1].strip("\n"))
		else:
			return ""
#============================================================================
# Ricerca l'indice di tabella della porta del nodo da monitorare
# (es: ath0 -> indice 5)
#============================================================================
	def get_index_if(self):
#		print "cerca "+self.me["interface"]
#		result = os.popen("snmpwalk -v 1 -c public "+self.me["ip_man"]+" interfaces.ifTable.ifEntry.ifDescr")
#---------------------------------------------------------------------------------------------------------
		cmd = "snmpwalk -v 1 -c public "+self.me["ip_man"]+" interfaces.ifTable.ifEntry.ifDescr"
		p = Popen(cmd, shell=True, stdin=PIPE, stdout=PIPE, stderr=PIPE, close_fds=True)
		res = p.stdout.read()
		result = string.split(res,"\n")
#---------------------------------------------------------------------------------------------------------
		index = 0
#		for r in result.readlines():
		for r in result:
			if (len(r)):
				if ((string.find(r,self.me["interface"]) <> -1) and (string.find(r,self.me["interface"]+".") == -1)):
					self.me["index_if"]=str(index+1)
					return (index+1)
				index=index+1
			else:
				return(0)
		return (0)
#============================================================================
# Rileva i dati di campionamento se il nodo e' dichiarato attivo
# altrimenti provvede a verificare il ritono in attivita'
#============================================================================
	def  get_data(self):
		url=self.me["url"]
		url=string.split(url,"//")
		opt=""
		if url[0] == "https:" :
			opt="--no-check-certificate"
		url=url[0]+"//"+self.me["ip"]+"/"+url[1]
		url=self.me["fetch_url"]
#---------------------------------------------------------------------------------------------------------
# lettura file remoto
#---------------------------------------------------------------------------------------------------------
		log.event_log ("[%s] %s : wget %s/ping.csv -O ping%s.csv" % (time.strftime("%c"),self.me["nome"],url,self.me["ip"]))
#		cmd=  "wget -t 1 -T 10 %s %s/ping.csv -O ping%s.csv" % (opt,url,self.me["ip"])
		cmd=  "wget -t 1 -T 10  %s/ping.csv -O ping%s.csv" % (url,self.me["ip"])
		p = Popen(cmd, shell=True, stdin=PIPE, stdout=PIPE, stderr=PIPE, close_fds=True)
		res = p.stdout.read()
#		r = string.split(res,"\n")
#---------------------------------------------------------------------------------------------------------
# verifica se il file e' stato scaricato
#---------------------------------------------------------------------------------------------------------
		cmd = 'test -f ping%s.csv  && echo "si" ||  echo "no"'% (self.me["ip"])
		p = Popen(cmd, shell=True, stdin=PIPE, stdout=PIPE, stderr=PIPE, close_fds=True)
		res = p.stdout.read()
		if string.split(res,"\n")[0]=="no":
			log.event_log (" con  Risultato : notOk\n")
			log.event_log ("["+time.strftime("%c")+"] "+"File ping.csv non ricevuto da  "+self.me["nome"]+"@"+self.me["ip"]+"\n")
			return False
		log.event_log (" con  Risultato : Ok\n")
		cmd = 'test -f ping%s-1.csv  && echo "si" ||  echo "no"'% (self.me["ip"])
		p = Popen(cmd, shell=True, stdin=PIPE, stdout=PIPE, stderr=PIPE, close_fds=True)
		res = p.stdout.read()
#---------------------------------------------------------------------------------------------------------
# verifica se il file precedente esiste 
#---------------------------------------------------------------------------------------------------------
		if string.split(res,"\n")[0]=="si":
#---------------------------------------------------------------------------------------------------------
# confronto con il precedente file remoto
#---------------------------------------------------------------------------------------------------------
			cmd="diff --suppress-common-lines  ping%s.csv ping%s-1.csv" % (self.me["ip"],self.me["ip"])
			p = Popen(cmd, shell=True, stdin=PIPE, stdout=PIPE, stderr=PIPE, close_fds=True)
			res = p.stdout.read()
			r = string.split(res,"\n")
#			print "file differenza : ",r
#---------------------------------------------------------------------------------------------------------
# salva nel DB i record del file remoto che non erano nel file ultimo letto 
#---------------------------------------------------------------------------------------------------------
			for l in r:
				ll=string.split(l," ")
				if ll[0]=="<" and len(ll)>= 8:
					self.save_data(ll[1:])
		else:
#---------------------------------------------------------------------------------------------------------
# salva nel DB tutti i record del file remoto
#---------------------------------------------------------------------------------------------------------
			cmd="cat ping%s.csv" % (self.me["ip"])
			p = Popen(cmd, shell=True, stdin=PIPE, stdout=PIPE, stderr=PIPE, close_fds=True)
			res = p.stdout.read()
			r = string.split(res,"\n")
#---------------------------------------------------------------------------------------------------------
# salva i dati nel DB
#---------------------------------------------------------------------------------------------------------
			for l in r:
				ll=string.split(l," ")
				if  len(ll)>= 8:
					self.save_data(ll)
#---------------------------------------------------------------------------------------------------------
# salva l'utimo file remoto analizzato
#---------------------------------------------------------------------------------------------------------
		cmd="mv ping%s.csv ping%s-1.csv" % (self.me["ip"],self.me["ip"])
		p = Popen(cmd, shell=True, stdin=PIPE, stdout=PIPE, stderr=PIPE, close_fds=True)
		res = p.stdout.read()
#		r=result.readlines()
		return True

#===============================================================================
# Registra il nodo come  attivo, anche sul DB 
# Registra inoltre le credenziali di contatto e il luogo di istallazione
#===============================================================================
	def update (self):
		if not self.me["attivo"] :
			log.event_log ("["+time.strftime("%c")+"] "+"Attivazione Nodo: "+self.me["nome"]+" "+self.me["ip"]+"\n")
#		print "Riattivazione Nodo: ",self.me["nome"]," " ,self.me["ip_wifi"]
		if (ping_db.reopenDB("net_ping")):
			v={"registrato":'1',"attivo":'1'}
			ping_db.update_record ("nodi",v,"ip ='"+self.me["ip"]+"'",debug =0 )
			ping_db.closeDB()
			self.me["registrato"]=1
			self.me["attivo"] = 1
#============================================================================
# Registra il nodo come non attivo, anche sul DB per mancata risposta
#============================================================================
	def deactivate(self):
		if self.me["attivo"] :
			log.event_log ("["+time.strftime("%c")+"] "+"Disattivazione Nodo: "+self.me["nome"]+" " +self.me["ip"]+"\n")
#		print "Disattivazione Nodo: ",self.me["nome"]," " ,self.me["ip_wifi"]
		if (ping_db.reopenDB("net_ping")):
			ping_db.update_record ("nodi",{"registrato":'0',"attivo":'0'} , "ip='"+self.me["ip"]+"'",debug =0 )
			self.me["registrato"]=0
			self.me["attivo"] = 0
			ping_db.closeDB()
#============================================================================
# Salva i dati acquisiti nel DB MySQL : tabella "dati"
#============================================================================
	def save_data(self,data,debug=0):
		log.event_log ("[%s] Added record : %s@%s %s\n" %(time.strftime("%c"),self.me["nome"],self.me["ip"],str(data)))
		t=string.split(time.strftime("%a %m %d %H:%M:%S %Y")," ")
		giorno=t[2]
		mese=t[1]
		anno=t[4]
		ora=t[3]
		valori={}
		valori["id_nodo"]=self.me["ID"]
		valori["giorno"]= t[2]
		valori["mese"]=mese=t[1]
		valori["anno"]=t[4]
		valori["ora_locale"]=t[3]
#		valori["ip"]=self.me["ip"]
		valori["ora_remota"]=data[1]
		valori["data_remota"]=data[0]
		valori["ip_sorg"]=data[2]
		valori["ip_dest"]=data[3]
		valori["min"]=data[4]
		valori["avg"]=data[5]
		valori["max"]=data[6]
#		valori["mdev"]="0"
		if (debug):
			print valori
		if (ping_db.reopenDB("net_ping")):
			ping_db.inserisci_record("dati",valori)
			ping_db.closeDB()
#============================================================================
# Salva statistiche giornaliere  nel DB MySQL : tabella "report"
# i dati sono salvati dopo le ore 24 di ogni giorno
#============================================================================
	def save_daily_data(self,debug=0):
		if ((time.strftime("%H") == "23")  and  (not self.saved_today)):
			self.t=string.split(time.strftime("%a %b %d %H:%M:%S %Y")," ")
		if ((time.strftime("%H") == "00")  and  (not self.saved_today)):
			self.saved_today=True
#			self.time_end=time.time()
#			t=string.split(time.strftime("%a %b %d %H:%M:%S %Y")," ")
#			print time.strftime("%a %b %d %H:%M:%S %Y")
#			print t[0],t[1],t[2],t[3],t[4]
			giorno=self.t[2]
			mese=self.t[1]
			anno=self.t[4]
#			ora=t[3]
			valori={}
			valori["id_nodo"]=self.me["ID"]
			valori["byte_in"]=self.acc_bin
			valori["byte_out"]=self.acc_bout
#			valori["activity"]=int((self.acc_attivo/86400.0)*100)
			valori["activity"]=int((self.acc_attivo/(self.time_end-self.time_start))*100)
			valori["total_act"]=self.acc_attivo
			valori["total_noact"]=self.acc_nonattivo
			valori["status"]=self.me["attivo"]
			valori["giorno"]=giorno
			valori["mese"]=mese
			valori["anno"]=anno
#			valori["ora"]=t[3]
			if (debug):
				print valori
			if (ping_db.reopenDB("ninux_rate")):
				ping_db.inserisci_record("report",valori)
				ping_db.closeDB()
			self.stato="NonAttivo"
			if (self.me["attivo"]):
				self.stato="Attivo" 
#			log.add_node_report(self.me["nome"],self.me["ip_wifi"],str(int(self.acc_bin)),str(int(self.acc_bout)),str(int((self.acc_attivo/86400.0)*100))+"%",self.stato,self.me["contatto"])
			log.add_node_report(self.me["nome"],self.me["ip_wifi"],"%3.2f"%(((self.acc_bin)/1048576.0)),"%3.2f"%(self.acc_bout/1045576.0),str(valori["activity"])+"%",self.stato,self.me["contatto"])
			self.acc_bin=0
			self.acc_bout=0
			self.acc_attivo=0	
			self.acc_noattivo=0
			self.time_start=time.time()
		elif (time.strftime("%H") > "00"):
			self.saved_today=False
#============================================================================
#============================================================================
# Invia una mail di alert per cambio di stato : 
#			da           "Attivo"     ====> "Non attivo"
# oppure 
#		 	da           "Non Attivo" ====> "Attivo" 
#============================================================================
	def alert (self,stato):
		if (self.me["mail"] == "hown"):
			self.server=smtplib.SMTP(self.provider)
			self.server.login(self.user,base64.b64decode(self.passw))
			self.oggetto="NxRM alert "+self.me['nome']+"@"+self.me['ip_wifi']
			self.testo="Il nodo %s@%s dalle ore %s del %s risulta %s" %(self.me['nome'],self.me['ip_wifi'],time.strftime("%H:%M"),time.strftime("%a-%d-%b-%Y"),stato)
			self.messaggio="From:%s\nTo:%s\nSubject:%s\n\n%s" %(self.sender,self.receiver,self.oggetto,self.testo)
			self.server.sendmail(self.sender,self.me["contatto"],self.messaggio)
			self.server.quit()
		log.event_log ("["+time.strftime("%c")+"] "+"Alert :"+self.me["nome"]+" " +self.me["ip_wifi"]+" il nodo risulta "+stato+"\n")
#		print "["+time.strftime("%c")+"] "+"Inviato Alert :"+self.me["nome"]+" " +self.me["ip_wifi"]+" il nodo risulta "+stato+"\n"

#============================================================================
# Ripristina l'ultimo valore di sequence: 
#============================================================================
	def get_last_sequence(self):
		if (ping_db.reopenDB("ninux_rate")):
			condizione='id_nodo='+str(self.me["ID"])+' and id=(select max(id) from dati where id_nodo='+str(self.me["ID"])+")"
			r=ping_db.estrai_record("dati",["sequence","ID"],condizione)
			ping_db.closeDB()
##			print r
			if (r):
				return (r[0]["sequence"])
			else:
				return False
		else:
			return False
#============================================================================
#============================================================================
#============================================================================
#============================================================================
#============================================================================
#============================================================================
#============================================================================
#============================================================================
# Classe Data Base che generalizza le principali operazioni
# di estrazione , inserimento e selezione dei dati
#============================================================================
class DB :
#============================================================================
# Inizializzazione per istanza
# Si definisce l'host su cui risiede il data base
# L'utente
# La password
#============================================================================
	def __init__ (self,host,user,passw,logobj, debug=0):
		self.host=host
		self.user=user
		self.passw=passw
		self.open=0
		self.log=logobj
		if (debug):
			print self.host,self.passw,self.user
#============================================================================
#============================================================================
#  Connessione allo Schema di riferimento
#  Rileva i nomi delle tablle dello schemo e i nomi delle colonne di
#  ogni tabella
#============================================================================
	def openDB (self,schema, debug=0):
		self.schema=schema
		try:
			self.db= mdb.connect(host = self.host, user = self.user, passwd = self.passw, db = schema)
		except mdb.Error, e:
			self.log.event_log("Errore di Connessione DB")
#			print "Errore di Connessione DB"
			return (0)
		finally:
			self.tables=[]
			self.colonne={}
			self.open=1
			self.queryDB("show tables")
			self.tables=self.cur.fetchall()
			if (debug):
				print self.tables
			for t in self.tables:
#				self.queryDB("show columns from "+t[0])
				self.queryDB("show columns from %s" % t[0])
				cs=self.cur.fetchall()
				cc=[]
				for c in cs:
					cc.append(c[0])
				self.colonne[t[0]]=cc 	#dizionario key=tabella valore = [col1,col2...,coln]
				if (debug):
					print self.colonne 
			return (1)
#============================================================================
	def reopenDB (self,schema, debug=0):
		if (not self.open): 
			try:
				self.db= mdb.connect(host = self.host, user = self.user, passwd = self.passw, db = schema)
			except mdb.Error, e:
#				print "Errore di Connessione DB"
				self.log.event_log("Errore di Riconnessione DB")
				return (0)
			finally:
				self.open=1
				return (1)
		else:
			return (0)
#============================================================================
# Query generica
# Esegue anche la Commit
# Ritorna 0 se corretta
# Ritorna -1  se la sintassi non e' corretta o non c'e' connessione 
# (Protrebbe essere dichiarata Private) 
#============================================================================
	def queryDB (self,comand, debug=0):
		if (debug):
			print "query : "+ comand
		if (self.open):
			try:
				self.cur = self.db.cursor()
				self.cur.execute(comand)
			except mdb.Error, e:
				self.log.event_log("Errore di Query DB\n")
				return (-1)
			finally:
				return (0)
		else:
				self.log.event_log("Errore di Query DB\n")
				return (-1)
#============================================================================
# Conta gli elementi di  "tabella"
# filtrati da "condizione"
# Ritorna : il numero di elemnti
# Ritorna : -1 se errore
# ============================================================================
	def conta (self,tabella,condizione='', debug=0):
		if condizione=='':
			query="select count(*) from %s " %(tabella)
		else:
			query="select count(*) from %s where %s " %(tabella, condizione)
		if (not self.queryDB(query,debug)):
			return(self.cur.fetchall()[0][0])
		else:
			return -1
#============================================================================
# Chiude la connessione con il Data VBase se aperta
# Ritorna 0 se corretto
# Ritorna -1 se non connesso
# ============================================================================
	def closeDB (self, debug=0):
		if (self.open):
			self.db.close()
			self.open=0
#============================================================================

#============================================================================
#  Esegue una commit della query attiva
#============================================================================
	def commitDB (self):
		if (self.open):
			try:
				self.db.commit()
			except mdb.Error, e:
				self.log.event_log("Errore di Commit DB\n")
				return (-1)
			finally:
				return (0)
		else:
				self.log.event_log("Errore di Commit DB\n")
				return (-1)
#============================================================================

#============================================================================
# Al momento non utilizata
#============================================================================
	def roolbackDB(self):
		self.db.rollback()
#============================================================================
	def get_colonne(self,tabella):
		return (self.colonne[tabella])
#============================================================================
# La funzione esegue una ricerca nei record di una tabella
#  tabella : Tabella di ricerca
#	colonne : colonne della tabella da estrarre (default tutte : *)
#	condizione : stringa delle condizioni (filtro)  di estrazione in stile SQL 
#			Esempio : "if_wifi='10.150.28.5' and if_man ='172.19.177.1'"
# Ritorna un array di dizionario le cui chiavi sono i nomi delle colonne della tabella
# Rtorna una lista vuota se non ci sono record che soddisfano le condizioni
# Ritorna  -1 se Data Base non connesso o la sintassi non e' corretta
#============================================================================
	def estrai_record(self,tabella,colonne=["*"],condizione='', debug=0):
		ret={}
		retval=[]
		query="select "
		if self.open :
			for c in colonne:
				query = query +c+", "
			query=query[:len(query)-2] 		#togliere l'ultima virgola
			query=query+" from "+tabella
			if (condizione !=''):
				query=query+ " where "+ condizione
			if (not self.queryDB(query,debug)):
				results=self.cur.fetchall()
				if (len(colonne)==1):
					colonne = self.colonne[tabella]
				for row in results:
					i=0
					for r in row:
						ret[colonne[i]]=r
						i=i+1
					if (debug):
						print ret	
					retval.append(ret)
					ret={}
				if (debug):
					print retval
				return (retval) # Lista di Dizionari le cui chiavi sono i nomi delle colonne della tabella
			else:
				return (-1)
		else:
			return (-1)
#============================================================================
#============================================================================
# Inserisce un record nella tabella "tabella"
# i valori che sono in un dizionario:
# {'nome_colonna1': Valore,......,'nome_colonna1': Valore)
#  Ritorna 0 se il record e' inserito correttamente
#  Ritorna-1 se non inserito o la sintassi non e' corretta
#============================================================================
	def inserisci_record(self,tabella,valori,debug=0):
		if(self.open):
			query = "INSERT INTO "+tabella+ " ("
#        $query .= ' ('.$r.')';
			for v in valori.keys() :
				if (debug):
					print v
				query= query + v+ " ,"
			query=query[:len(query)-2] 		#togliere l'ultima virgola
			query =query +" ) VALUES ("
			for v in valori.values() :
				if (isinstance(v,str)):
					query=query +"'"+ v + "' ,"
				else:
					query=query +str(v)+ " ,"
			query=query[:len(query)-2] 		#togliere l'ultima virgola
			query =query + ")"
			if (debug):
				print query
			return (self.queryDB(query,debug))
#============================================================================

#============================================================================
# Aggiorna  i valori di un record esistente
# della Tabella "tabella"
#  i valori da sostituire sono dischirati in un dizionario
# {'nome_colonna1': Valore,......,nome_colonnaN': Valore}
#
#  Ritorna 0 se il record e' inserito correttamente
#  Ritorna -1 se non aggiornato o la sintassi non e' corretta
#============================================================================
	def update_record (self,tabella,valori,condizione = "",debug = 0 ):
		if(self.open):
			query = "UPDATE "+tabella+" SET "
			for v in valori.keys():
				if (isinstance(valori[v],str)):
					query=query + v +" = '"+ valori[v]+"', "
				else:
					query=query + v +" = "+ valori[v]+", "
			query=query[:len(query)-2] 		#togliere l'ultima virgola
			if (len(condizione)):
				query=query + " WHERE "+ condizione
			if (debug):
				print query
			return (self.queryDB(query),debug)
#============================================================================
#============================================================================
#			Fine DB class
#============================================================================
#============================================================================
#============================================================================
#============================================================================
#============================================================================
#============================================================================
#============================================================================
#============================================================================

#============================================================================
# Program Main 
#============================================================================
#pathnome_log="/home/salvatore/netping/diario.log"
#log=logger(pathnome_log)
#log.event_log ( "[%s] Start net_ping Process\n" % (time.strftime("%c")))
#DataBaseHost='172.16.1.9'
#user='ping'
#password='ping'
#============================================================================
# Legge file di configurazione 
#============================================================================
c=ConfigParser.ConfigParser()
if not(c.read("netping.config")): #il file esiste ?
	print "File Config does not exist"
	exit(-1)
sections=c.sections()
for section in sections:
	for option in c.options(section):
#		print option,"="
		exec  option+"="+'c.get(section,option)'
#		exec 'print option, "=",c.get(section,option)'
if not('pathnome_log' in globals()):
	print "Config File is not correct: 'pathnome_log' not exist"
	exit(-1)
elif not ('databasehost' in globals()):
	print "Config File is not correct: 'datahasehost' not exist"
	exit(-1)
elif not ('path_arch' in globals()):
	print "Config File is not correct: 'path_arc' not exist"
	exit(-1)
elif not('user' in globals()):
	print "Config File is not correct: 'user' not exist"
	exit(-1)
elif not('password' in globals()):
	print "Config File is not correct: 'password' not exist"
	exit(-1)
elif not('schemadb' in globals()):
	print "Config File is not correct : 'schemadb' not exist "
	exit(-1)
#============================================================================
#============================================================================
log=logger(pathnome_log,path_arch)
log.event_log ( "[%s] Start net_ping Process\n" % (time.strftime("%c")))
#============================================================================
ping_db = DB (databasehost,user,password,log) ## Istanzia il Data Base Client
if ping_db.openDB(schemadb) :               ## Apre la connessione al Data Base
	tutti_nodi = ping_db.estrai_record("nodi") # Estrae tutti i nodi
	ping_db.closeDB()
i=0
id_max=0
for s in tutti_nodi:
	if s["ID"] > id_max :
		id_max=s["ID"]
	tutti_nodi[i] = NODO(s,log)
	log.event_log("[%s] %s %s %s %s %s %d\n" %(time.strftime("%c"),s["nome"],s["ip"],s["contattomail"]," - ","attivo = ",s["attivo"]))
	time.sleep(1)
	i=i+1
#try:
while 1:
	twait=3600/len(tutti_nodi)
	for s in tutti_nodi:
#		print time.strftime("%a %b %d %H:%M:%S %Y")
		s.run()
#		print "wait for ",twait,"s"
		time.sleep(twait)  # pause di (1 ora / numero di nodi)
#		time.sleep(5)
#	print time.strftime("%a %b %d %H:%M:%S %Y"), "   altro giro"#
#============================================================================
#  Verifica se sono stati aggiunti altri nodi
#============================================================================
	if ping_db.reopenDB("net_ping") :               ## Apre la connessione al Data Base
		n=ping_db.conta("nodi")
		if (n-len(tutti_nodi))>0 :
			nuovi_nodi = ping_db.estrai_record("nodi","*","ID > %s" %id_max) # Estrae  i nodi nuovi
			ping_db.closeDB()
			for s in nuovi_nodi:
				if s["ID"] > id_max :
					id_max=s["ID"]
				tutti_nodi.append(NODO(s,log))
				log.event_log("[%s] Aggiunto nodo %s@%s" %(time.strftime("%c"),s["nome"],s["ip"]))
				log.event_log("[%s] %s %s %s %s %s %d\n" %(time.strftime("%c"),s["nome"],s["ip"],s["contattomail"]," - ","attivo = ",s["attivo"]))
#		elif (n-len(tutti_nodi))==0:
#			print "Nessun nodo aggiunto"
#		else :
#			print "Nessun nodo eliminato"
		ping_db.closeDB()
#	time.sleep(300)  # pause per 1 ora
#except:
#	log.event_log ( "Errore inatteso :  %s \n "%(sys.exc_info()[0]))		
	
	
	
