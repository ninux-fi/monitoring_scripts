<?php include "header.html";?>
<?php @include_once("stat/src/include.php");?>
<?php include "libreria.php";?>
<table style="border: 3px solid black;width: 800px; background-image: url(sfondi/nuvole.jpg);" align="center" background="&#8221;nuvole.jpg&#8221;" cellpadding="0" cellspacing="0">
<tr>
<?php include "menu.html";?>
<?php
// lettura variabili POST
$nomenodo=$_POST['nomenodo'];
$ipwifi=$_POST['ipwifi'];
$ipman=$_POST['ipman'];
$if=$_POST['if'];
$ipservizio=$_POST['ipservizio'];
$descrizione=$_POST['descrizione'];
$porta=$_POST['porta'];
$registrato="true";
$operazione=$_POST['operazione'];
$mailref=$_POST['mail_add'];
$eseguito=false;
$trovato=false;
$readonly="";
$mail=$_POST['mail'];
$url=$_POST["url"];
$location=$_POST["location"];
//echo $if."-".$mailref."<br>";
// --------------------------
// Analisi Input ricevuto
//---------------------------

//--- Registrazione di un nuovo Nodo --------
//
if (!empty($nomenodo) and ($operazione=="Registra")){
//	echo $nomenodo."<br>";
	list ($nomenodo,$ipman,$registrato) = registrazione_nodo($nomenodo,$ipman,$mailref,$location,$url);
	$eseguito=true;
}
elseif ($operazione == "Registra"){
	 $nomenodo="Il Nodo deve avere un Nome";
}
//--- Aggiunta di un nuovo Servizio --------
//
/*if (!empty($descrizione) and ($operazione=="Aggiungi")){
	list ($ipwifi,$descrizione,$ipservizio,$porta) = aggiungi_servizio($ipwifi,$descrizione,$ipservizio,$porta);
}
elseif ($operazione == "Aggiungi"){
	 $descrizione="Il Servizio deve avere una Nome";
}*/
//--- Cancellazione di un Nodo (non ancora attiva)--------
//
if (!empty($descrizione) and ($operazione=="Cancella")){
	list ($ipwifi,$descrizione,$ipservizio) =  cancellazione_nodo($nomenodo,$ipwifi,$ipman);
}
elseif ($operazione == "Cancella"){
	 $descrizione="Il Servizio deve avere una Nome";
}
//--- Modifica di un  un Nodo esistente (non ancora attiva)--------
//
if (!empty($nomenodo) and ($operazione=="Modifica")){
		echo $location;
		list ($nomenodo,$location,$url,$ipman,$mailref,$trovato) =  modifica_nodo($nomenodo,$ipman,$mailref,$location,$url);
	$readonly="readonly";
}
elseif ($operazione == "Modifica"){
	 $descrizione="Il Servizio deve avere una Nome";
}
if (!empty($ipman) and ($operazione=="Cerca")){
	list ($nomenodo,$location,$url,$ipman,$mailref,$trovato) = cerca_nodo($nomenodo,$ipman);
	if ($trovato) $readonly="readonly";
	echo $if."<br>";
}
elseif ($operazione =="Cerca"){
	 $ipwifi=" Specificare un indirizzo";
}
?>
<!--Fine Codice PHP -->
<!--      <td style="border: 1px solid black;background-color: rgb(238, 238, 238); height: 200px; width: 700px; vertical-align: top; color: rgb(249, 57, 6);">-->
 <td style="border: 1px solid black;background-image: url(weblink21.gif); height: 200px; width: 700px; vertical-align: top; color: rgb(249, 57, 6);">
<!-- Form di Registrazione dati del Nodo -->
      <form style="text-align: center;" name="Registrazione" action="registrazione.php" method="post">
        <h3>Gestione Nodo</h3>
			<?php
				if ((!$registrato) and ($eseguito)){
					echo "(Registrazione non eseguita) <br>";
				}
			?>
        <hr>
        <h4 style="text-align: center;">&nbsp;Identita'</h4>
 	<span style="color: black;">&nbsp;Nome del Nodo:&nbsp;&nbsp;&nbsp;</span>
	<input style="color: black;" name="nomenodo" <?php  echo "value=".'"'.$nomenodo.'"'; ?> type="text" size='30'>
	<br style="color: black;">
   <br style="color: black;">
<!--	<span style="color: black;">&nbsp;&nbsp;IP Antenna WiFi:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
	<input style="color: black;" name="ipwifi"  <?php  echo "value=".'"'.$ipwifi.'" '.$readonly." "; ?>type="text" size='30'> <br style="color: black;"><br style="color: black;">-->
	<span style="color: black;">&nbsp;&nbsp;&nbsp;&nbsp;Indirizzo IP:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
	<input name="ipman" indirizzo="" ip="" della="" wifi=""  <?php  echo "value=".'"'.$ipman.'"'; ?> type="text" size='30'><br><br>
	<span style="color: black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;URL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
	<input name="url" indirizzo="" ip="" della="" wifi=""  <?php  echo "value=".'"'.$url.'"'; ?> type="text" size='30'><br><br>
	<span style="color: black;">&nbsp;&nbsp;&nbsp;&nbsp;Posizione:;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
	<input name="location" indirizzo="" ip="" della="" wifi=""  <?php  echo "value=".'"'.$location.'"'; ?> type="text" size='30'><br><br>
<!--	<span style="color: black;">&nbsp;&nbsp;Nome Interfaccia:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
	<select name="if">
		<?php
				if ($if=="ath0"){ echo '<option value="ath0" selected>ath0</option>';}
				else { echo '<option value="ath0" >ath0</option>';}

				if ($if=="wlan0"){ echo '<option value="wlan0" selected>wlan0</option>';}
				else { echo '<option value="wlan0" >wlan0</option>';}

				if ($if=="eth0"){ echo '<option value="eth0" selected>eth0</option>';}
				else { echo '<option value="eth0" >eth0</option>';}


				if ($if=="eth1"){ echo '<option value="eth1" selected>eth1</option>';}
				else { echo '<option value="eth1" >eth1</option>';}
		?>
<!--		<option value="eth1" selected>eth1</option>-->
	</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br><br>
	<hr>
	<span style="color: red;">Se il Nodo non è Attivo segnala con<br></span>
	<span style="color: black;">

<?php
		echo '<input  type="radio" name="mail" value="none" checked="checked">Nessuna Email&nbsp;&nbsp;&nbsp;';
//		if ($mail=="list") { echo '<input  type="radio" name="mail" value="list" checked="checked">Email in Ninux Mail List&nbsp;&nbsp;&nbsp;';}
//		else { echo '<input  type="radio" name="mail" value="list">Email in Ninux Mail List&nbsp;&nbsp;&nbsp;';}
		if ($mail=="hown") {echo '<input  type="radio" name="mail" value="hown" checked="checked">Email su Casella Personale<br>';}
		else {echo '<input  type="radio" name="mail" value="hown">Email su Casella Personale<br>';}
?>
	</span>
	<br><span style="color: black;">Indirizzo Email :
	<input style="color: black;" name="mail_add" <?php  echo "value=".'"'.$mailref.'"'; ?> type="text" size='30'><br>

<!--	<input name="if" indirizzo="" ip="" della="" wifi=""  <?php  echo "value=".'"'.$ipman.'"'; ?> type="text"><br><br> -->
   <hr>
<?php
//	 echo $trovato;
    if (!$trovato) echo '<input name="operazione" value="Registra" type="submit">';
    if (!$trovato) echo ' <input name="operazione" value="Cerca" type="submit">';
    if ($trovato=="trovato") echo ' <input name="operazione" value="Cancella" type="submit">';
    if ($trovato=="trovato") echo '  <input name="operazione" value="Modifica" type="submit">';
?>
<!--   <hr>
   <h4 style="text-align: left;">&nbsp;Servizi Offerti</h4>
	<span style="color: blue;">Descrizione:&nbsp;&nbsp;&nbsp;</span>
	<input style="color: black;" name="descrizione" <?php  echo "value=".'"'.$descrizione.'"'; ?> type="text" size='30'><br>
	<span style="color: blue;">Indirizzo IP:&nbsp;&nbsp;&nbsp;&nbsp;</span>
	<input style="color: black;" name="ipservizio" <?php  echo "value=".'"'.$ipservizio.'"'; ?> type="text" size='30'><br>
	<span style="color: blue;">Porta:&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;
	<input style="color: black;" name="porta" <?php echo "value=".'"'.$porta.'"'; ?> type="text" size='30'>
   <hr>
   <input name="operazione" value="Aggiungi" type="submit"> -->
   </form>  <hr>
   </td>
   </tr>
<?php include "footer.html";?>

