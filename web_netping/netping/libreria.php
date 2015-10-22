<?php
//-------------------------------------------------
//-------------------------------------------------
//-------------------------------------------------
//-------------------------------------------------
//-------------------------------------------------
function validate_email($email){
   $exp = "^[a-z\'0-9]+([._-][a-z\'0-9]+)*@([a-z0-9]+([._-][a-z0-9]+))+$";
   if(eregi($exp,$email)){
      if(checkdnsrr(array_pop(explode("@",$email)),"MX")){
        return true;
      }
		else{
        return false;
      }
   }
	else{
      return false;
   }   
}
//-------------------------------------------------
//function modifica_nodo ($n,$ipw,$ipm,$if,$mail,$contatto) {
function  modifica_nodo ($n,$ipm,$mail,$location,$url) {
	$all_valid=true;
	$valid = ip2long($ipm) !== false;
	if (!$valid){	
		$all_valid=false;
		$ipm.="(IP non Valido)";
	}
//	if ($mail=="hown"){
//		echo $mail;
		if(!validate_email($mail)){
			$all_valid=false;
			$mail.=" (Indirizzo mail non valido)";
		}
//	}
	if ($all_valid){
		$db = new DBclass();
		$db->connetti();
		$nr=$db->esiste("nodi_prova","ip ='".$ipm."'");
		if($nr == 1){
			$record ["nome"]=$n;
//			$record["ip_wifi"]=$ipw;
			$record["ip"]=$ipm;
			$record["location"]=$location;
			$record["fetch_url"]=$url;
			$record["contattomail"]=$mail;
			$db->update("nodi_prova",$record,"ip = '".$ipm."'");
		}
		elseif($nr >1){
			$ipm.="-(Troppi Nodi esistenti)";
			$all_valid=false;
		}
		elseif($nr==0){
			$ipm.="(Il Nodo non esiste)";
			$all_valid=false;
		}
		$db->disconnetti();
	}
//	echo $n."<br>";
//	return array ($n, $ipw, $ipm,$contatto,$all_valid);
	return array ($n, $location,$url,$ipm,$mail);
}
//-------------------------------------------------
//-------------------------------------------------
function cerca_nodo ($n,$ip) {
	$all_valid=true;
	$valid = ip2long($ip) !== false;
	if (!$valid){	
		$ip.="(IP non Valido)";
		$all_valid=false;
	}
	if ($all_valid){
		$db = new DBclass();
		$db->connetti();
		$nr=$db->esiste("nodi_prova","ip ='".$ip."'");
		if($nr == 1){
			$record=$db->estrai_record("nodi_prova",array ("nome","location","fetch_url","ip","creato","attivo","registrato","contattomail"),"ip = '".$ip."'");
//			echo var_dump($record);
			$n=$record [0]["nome"];
			$url=$record[0]["fetch_url"];
			$ipm=$record[0]["ip"];
			$location=$record[0]["location"];
//			$mail=$record[0]["mail"];
			$contatto=$record[0]["contattomail"];
			$all_valid="trovato";
		}
		elseif($nr >1){
			$ipm.="-(Troppi Nodi esistenti)";
			$all_valid=false;
		}
		elseif($nr==0){
			$ipm.="(Il Nodo non esiste)";
			$all_valid=false;
		}
		$db->disconnetti();
	}
//	echo $n."<br>";
//	echo $if."<br>";
	return array ($n,$location,$url,$ipm,$contatto,$all_valid);
}
//-------------------------------------------------
//-------------------------------------------------
function registrazione_nodo ($n,$ipm,$mail,$location,$url) {
//	echo "Passata di qui <br>";
	$all_valid=true;
	if (!empty($n)){
		$valid = ip2long($ipm) !== false;
		if (! $valid){	
			$ipm.="(IP non Valido)";
			$all_valid=false;
		}
		if(!validate_email($mail)){
			$all_valid=false;
			$mail.=" (Indirizzo mail non valido)";
		}
/*		$valid = ip2long($ipm) !== false;
		if (! $valid){	
			$all_valid=false;
			$ipm.="(IP non Valido)";
		}*/
	}
//	echo "Passata di qui <br>";
	if ($all_valid){
		$db = new DBclass();
		$db->connetti();
//		$db->	estrai_record("nodi",array ("nome","ip_wifi","ip_man","interface","creato","attivo","registrato"),"nome = 'Firenze::Lippi'");
		if(!$db->esiste("nodi_prova","ip ='".$ipm."'")){
			if(!$db->esiste("nodi_prova","ip = '".$ipm."'")){
				$db->inserisci("nodi_prova",array($n,$location,$url,$ipm,date("Y/m/d H:i:s", time()),0,0,$mail),array("nome","location","fetch_url","ip","creato","attivo","registrato","contattomail"));
	//			echo date("Y/m/d H:i:s", time()); 		
			}
			else{
				$ipm.="(Nodo esistente)";
				$all_valid=false;
			}
		}
		else{
			$ipm.="(Nodo esistente)";
			$all_valid=false;
		}
		$db->disconnetti();
	}
//	echo $n."<br>";
	return array ($n,$ipm,$all_valid);
}
//-------------------------------------------------
//-------------------------------------------------
function aggiungi_servizio ($ipw,$d,$ips,$p) {
	$all_valid=true;
//	echo $ipw."<br>";
//	echo "porta=".$p."<br>";
	if (!empty($d)){
		$valid = ip2long($ipw) !== false;
		if (!$valid){	
			$ipw .="(Indirizzo non Valido)";
			$all_valid=false;
		}
	 	$valid = ip2long($ips) !== false;
//		echo $ips."<br>";
		if (!$valid){	
			$all_valid=false;
			$ipm.="(Indirizzo non Valido)";
		}
	}
	if ($all_valid){
		$db = new DBclass();
		$db->connetti();
		$colonne=array ("ID","nome","ip_wifi","ip_man","interface","creato","attivo","registrato");
		$condizioni= "ip_wifi='".$ipw."'";
		if($nodo=$db->estrai_record("nodi",$colonne,$condizioni)){
//			echo var_dump($nodo)."<br>";
			$ID=$nodo[0]["ID"];
			$colonne=array ("ip","porta");
//			echo $ID."<br>";
			$condizioni = "id_nodo =".$ID." and ip='".$ips."' and porta='".$p."'";
//			$db->estrai_record("servizi",$colonne,$condizioni);
			if(!($servizio=$db->estrai_record("servizi",$colonne,$condizioni))){	
//				echo var_dump($servizio)."<br>";
				$db->inserisci("servizi",array($ID,$ips,$p,$d/*," ", date("Y/m/d H:i:s", time()),$if,"false","false"*/),array("id_nodo","ip","porta","descrizione"));
			}
			else{
				$ips.="(Servizio esistente)";
			}
		}
		else{
			$ipw.="(Nodo Non Registrato)";
		}
		$db->disconnetti();
	}
	return array ($ipw, $d,$ips,$p);
}
//-------------------------------------------------
//-------------------------------------------------
//  Classe  Data Base
//-------------------------------------------------
class DBclass {
//-------------------------------------------------
// parametri per la connessione al database
   private $nomehost = "localhost";    
   private $nomeuser = "ping";         
   private $password = "ping";
	private $nomedb = "net_ping";
	private $connessione ="";
//-------------------------------------------------
// controllo sulle connessioni attive
  	private $attiva = false;
//-------------------------------------------------
// funzione per la connessione a MySQL
//----------------------------------------------- 
	public function connetti(){
		if(!$this->attiva){
	   	if($this->connessione = mysql_connect($this->nomehost,$this->nomeuser,$this->password) or die (mysql_error())){
// selezione del database
	      	$selezione = mysql_select_db($this->nomedb,$this->connessione) or die (mysql_error());
	      }
	   }
		else{
	   	return true;
	   }
	}
//-------------------------------------------------------------------------
//-------------------------------------------------------------------------
// funzione per la chiusura della connessione
//-------------------------------------------------------------------------
	public function disconnetti()	{
		     if($this->attiva) {
		            if(mysql_close()) {
		      			$this->attiva = false;
		          		return true;
		            }
						else {
		            	return false;
		            }
		     }
	 }
//-------------------------------------------------------------------------

//-------------------------------------------------------------------------
// funzione per l'esecuzione delle query
//-------------------------------------------------------------------------
	public function query($sql){
//		echo $sql."<br>";
	  	if(isset($this->attiva)){
			$sql = mysql_query($sql,$this->connessione) or die (mysql_error());
			return $sql;
	  	}
	  	else{
	  		return false;
		}
	}
//-----------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------
//funzione per l'inserimento dei dati in tabella
//i l parametro “$t” rappresenta il nome della tabella in cui si desidera effettuare l’inserimento,
//  questa variabile verrà quindi passata come argomento al comando INSERT INTO;
// “$r” è un array destinato a contenere i nomi dei campi da popolare, questo array non potrà essere vuoto, quindi la funzione ne controllerà
//  il contenuto prima di eseguire la query;
// “$v” è un array che rappresenta i valori che andranno a popolare i diversi campi i cui nomi sono anche i valori del vettore “$r”;
//-----------------------------------------------------------------------------------------------------------------------------------------
    public function inserisci($t,$v,$r = null) {
    	if(isset($this->attiva)){
         	$istruzione = 'INSERT INTO '.$t;
            if($r != null){
//                $istruzione .= ' ('.$r.')';
		         for($i = 0; $i < count($r); $i++){
		             //if(is_string($r[$i]))
		                 $r[$i] = $r[$i];
		         }
	            $r = implode(',',$r);
	            $istruzione .= ' ('.$r.')';
            }
//				echo $istruzione."<br>";				
            for($i = 0; $i < count($v); $i++){
                if(is_string($v[$i]))
//                	  echo $v[$i]."<br>";
                    $v[$i] = '"'.$v[$i].'"';
            }
            $v = implode(',',$v);
            $istruzione .= ' VALUES ('.$v.')';
				echo $istruzione."<br>";
            $query = mysql_query($istruzione,$this->connessione) or die (mysql_error());
      }
		else{
      	return false;
      }
	}
//-----------------------------------------------

//-----------------------------------------------
// funzione per l'estrazione dei record
//-----------------------------------------------
	public function estrai($query){
	  	if(isset($this->attiva)){
			$result = query($query) or die('Errore...');
	  		$r = mysql_fetch_object($result);
	  		return $r;
	  	}
		else{
	  		return false; // nessun risultato
	  }
	}
//--------------------------------------------------------------------------- 
// Funzione per la verifica di esistenza di almeno  un elemento
//
// $t è la tabella di ricerca
// $cond è una stringa la condizione di ricerca  es: "ip_wifi='172.19.77.52' and ip_man='10.150.28.25"
//
//--------------------------------------------------------------------------- 
	public function esiste($t,$cond){
//		echo "condizione".$cond."<br>";
	  	if(isset($this->attiva)){
			$query ="select  * from ".$t;
			if ($cond != null){
				$query .=" where ".$cond;
			}
//			echo $query."<br>";
			$result = $this->query($query) or die('Errore...');
			if (mysql_num_rows($result) > 0){
				$row=mysql_fetch_array($result);
//				echo $row[$c]."<br>";
//				$row=array();
				return true;	 //esiste		
			}
			else{
	  			return false; // non esiste
			}				
	  	}
		else{
	  		return false; // nessun risultato
	  }
	}
//--------------------------------------------------------------------------- 
// Funzione per la estrazione di elementi
//
// $t è la tabella di ricerca
// $c è un array dei valori di ritorno  ("nome","ip_wifi","creato","ip_man")
// $o è una stringa la condizone di ricerca  es: "ip_wifi='172.19.77.52' and ip_man='10.150.28.25"
//
//--------------------------------------------------------------------------- 
	public function estrai_record ($t,$c,$o = null){
//		echo "Funzione estrai_record<br>";
	  	if(isset($this->attiva)){
			$query ="select  * from ".$t;
			if ($o != null){
				$query .=" where ".$o;
			}
//			echo $query."<br>";
			$result = $this->query($query) or die('Errore...');
//			var_dump($result);
			if (mysql_num_rows($result) > 0){
				$r=array();
				while ($row = mysql_fetch_array($result)){
//					var_dump($row);
					$rr=array();
					foreach($c as $col){
//						echo $col." ".$row[$col]."<br>";
						$rr[$col]=$row[$col];
					}
//					var_dump($rr);
					$r[]=$rr;
//					echo "<br>";
				}
//				echo var_dump($r);
				return $r;
//				return $result;	 //esiste		
			}
			else{
	  			return false; // non esiste
			}				
	  	}
		else{
			echo "Connessione DB non attiva<br>";
	  		return false; // nessun risultato
	  }
	}
//--------------------------------------------------------------------------- 
// Funzione per la estrazione di elementi
//
// $t è la tabella di riferimento ("dati")
// $c è il vettore delle operazioni sulle colonne della tabella  ("ID","tempo", "avg(byte:in_sec)," creato","ip_man")
// $o è una stringa la condizone di ricerca  es: "ip_wifi='172.19.77.52' and ip_man='10.150.28.25 group by left(tempo,2)"
//
//--------------------------------------------------------------------------- 
	public function estrai_valore_medio ($t,$c,$o ="true"){
	  	if(isset($this->attiva)){
			$query =sprintf("select %s  from %s where %s ",implode(",",$c),$t,$o);
//			echo $query."<br>";
			$result = $this->query($query) or die('Errore...');
//			var_dump($result);
			if (mysql_num_rows($result) > 0){
				$r=array();
				while ($row = mysql_fetch_array($result)){
//					var_dump($row);
					$rr=array();
					foreach($c as $col){
//						echo $col." ".$row[$col]."<br>";
						$rr[$col]=$row[$col];
					}
//					var_dump($rr);
					$r[]=$rr;
//					echo "<br>";
				}
//				echo var_dump($r);
				return $r;
//				return $result;	 //esiste		
			}
			else{
	  			return false; // non esiste
			}				
	  	}
		else{
			echo "Connessione DB non attiva<br>";
	  		return false; // nessun risultato
	  }
	}
//--------------------------------------------------------------------------- 
// Funzione per la modifica di elementi
//
// $t è la tabella di riferimento
// $c è un array dei valori   ("nome"=> "Firenze:Lippi","ip_wifi"=>"192.19.177.52"...........)
// $o è una stringa la condizione di ricerca  es: "ip_wifi='172.19.77.52' and ip_man='10.150.28.25"
//--------------------------------------------------------------------------- 
   public function update($t,$v,$o = null) {
    	if(isset($this->attiva)){
         $istruzione = 'UPDATE '.$t.' SET ';
         $i=False;
         foreach ($v as $key => $vv){
         		$vvv=$vv;
            	if(is_string($vv))
            		$vvv = '"'.$vv.'"';
           		if (!$i) {
         			$istruzione .= $key."=".$vvv;
         			$i=True;
         		}
	         	else {
 	        			$istruzione .= ",".$key."=".$vvv;
   	      	}
   		}
         $istruzione .= " WHERE ".$o;
			echo $istruzione."<br>";
			$query = mysql_query($istruzione,$this->connessione) or die (mysql_error());
		}
		else{
      	return false;
      }
	}
//--------------------------------------------------------------------------- 
//--------------------------------------------------------------------------- 
//--------------------------------------------------------------------------- 
//----------------------------------------------- 
// Fine Classe Data Base
//----------------------------------------------- 
}
?>

