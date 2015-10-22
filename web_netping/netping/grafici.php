<?php include "header.html";?>
<?php @include_once("stat/src/include.php"); ?>
<?php include "libreria.php";?>
<table style="border: 3px solid black;width: 800px; background-image: url(sfondi/nuvole.jpg);" align="center" background="&#8221;nuvole.jpg&#8221;" cellpadding="0" cellspacing="0">
<tr>
<?php include "graph.php";?>
<?php
exec("rm "."*.grf*");
$oggi= explode("-",date("d-M-Y"));
//var_dump($oggi);
$operazione= $_POST['operazione'];
$giorno=$_POST['giorno'];
//echo $_POST['giorno']."-".$girono."-<br>";
$mesi=array("None","Jan ","Feb ","Mar ","Apr ","May ","Jun ","Jul ","Aug ","Sep ","Oct ","Nov ","Dec ");
//echo $_POST['mese']."-<br>";
$mese=array_search($_POST['mese'],$mesi);
$anno=$_POST['anno'];
$mesi=array("None","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
$bin_graph=$_POST['bin'];
$min_bin_graph=$_POST['min_bin'];
$max_bin_graph=$_POST['max_bin'];
$bout_graph=$_POST['bout'];
$min_bout_graph=$_POST['min_bout'];
$max_bout_graph=$_POST['max_bout'];
$opentime=time();
//echo $opentime,"<br>";
//echo $bin_graph."---".$min_bin_graph."---".$max_bin_graph."---".$bout_graph."---".$min_bout_graph."---".$max_bout_graph;
//echo $operazione."<br>";
#---------------------------------------------------------------------------------------
#       Sezione Ricerca Nodi
#---------------------------------------------------------------------------------------
if(($operazione=="Aggiungi") || ($operazione=="")){
	$lista= $_POST['lista'];
	if ($lista){
		$lista=$lista."|".$_POST['grafico'];
	}
	else{
		$lista=$lista.$_POST['grafico'];
	}
	//echo "operazione ".$operazione."<br>";
	$db = new DBclass();
	$db->connetti();
	$nodi = $db->estrai_record("nodi",array ("ID","nome","ip","location","fetch_url"));
	$db->disconnetti();
}
#---------------------------------------------------------------------------------------
#---------------------------------------------------------------------------------------
#       Sezione Tracciamento Grafici
#---------------------------------------------------------------------------------------
if($operazione=="Disegna"){
	$lista= $_POST['lista'];
	$db = new DBclass();
	$db->connetti();
//	$nodi = $db->estrai_record("nodi",array ("ID","nome","ip_wifi","ip_man","interface","creato","attivo","registrato"));
	$nodi = $db->estrai_record("nodi",array ("ID","nome","ip","location","fetch_url"));
//	$db->disconnetti();
	$a =explode("|",$lista); //lista dei grafici da tracciare
//	var_dump($a);
//	echo "<br>";
	$vmax=0;	
	foreach($a as $l){
//		echo var_dump($l);
		$ll=explode("@",$l);
//		echo $ll[1],"-".$ll[0]."-".$ll[2]."<br>";
//		$condizione=sprintf("id_nodo = '%s' and giorno ='%s' and mese='%s' and anno='%s' group by left(ora,2) order by ID",$ll[2],$giorno,$mese,$anno);
		$group="left(ora_remota,2)";
		if ($giorno=="any"){
			$giorno="%";
			$group="right(data_remota,2)";
		}
		if ($mese=="any") {
			$mese="%";
			$giorno="%";
			$group="mid(data_remota,6,2)";
		} 
		$condizione=sprintf("id_nodo = '%s' and data_remota like '%s' group by ip_dest",$ll[2],$anno."-".$mese."-".$giorno);
		$links=$db->estrai_record("dati",array("ip_sorg","ip_dest"),$condizione);
		$db->disconnetti();
//		var_dump($links);
//		echo "<br>";
//		echo $condizione."<br>";
		foreach($links as $ln){
//			var_dump($ln);
//			echo "<br>";
			$condizione=sprintf("id_nodo = '%s' and data_remota like '%s' and ip_sorg='%s' and ip_dest='%s' group by %s",$ll[2],$anno."-".$mese."-".$giorno,$ln["ip_sorg"],$ln["ip_dest"],$group);
			$valori=array ("id_nodo","avg(min)","avg(avg)","avg(max)",$group);
			$vmedi = $db->estrai_valore_medio("dati",$valori,$condizione);
//			var_dump($vmedi);
//			echo "<br>";
			$valori=array();
			$indice=0;
			$xvalues=array();
			if(1) {
				$valori[$indice][0]="Min ";
				$indice=$indice+1;
			}
			if(1) {
				$valori[$indice][0]="Avg";
				$indice=$indice+1;
			}
			if(1){
				$valori[$indice][0]="Max";
				$indice=$indice+1;
			}
			foreach($vmedi as $vm){
				$indice=0;
				if(1){
					if ($vm["avg(min)"]>$vmax1)  $vmax1=$vm["avg(min)"];
					$valori[$indice][]=$vm["avg(min)"];
					$indice=$indice+1;
				}
				if(1){
					if ($vm["avg(avg)"]>$vmax2)  $vmax2=$vm["avg(avg)"];
					$valori[$indice][]=$vm["avg(avg)"];
					$indice=$indice+1;
				}
				if(1){
					if ($vm["avg(max)"]>$vmax3)  $vmax3=$vm["avg(max)"];
					$valori[$indice][]=$vm["avg(max)"];
					$indice=$indice+1;
				}
				$titolo='Nome: '.$ll[0]."- IP sorg".$ln["ip_sorg"]." IP dest:".$ln["ip_dest"]." del ".$giorno."-".$mese."-".$anno;
				$xvalues[]=$vm[$group];
			}
			$indice=0;
			if(1) {
				$valori[$indice][0]=$valori[$indice][0].sprintf(" %.3f ",$vmax1)."(ms)";
				$indice=$indice+1;
			}
			if(1) {
				$valori[$indice][0]=$valori[$indice][0].sprintf(" %.3f ",$vmax2)."(ms)";
				$indice=$indice+1;
			}
			if(1) {
				$valori[$indice][0]=$valori[$indice][0].sprintf(" %.3f ",$vmax3)."(ms)";
				$indice=$indice+1;
			}
			$vmax1=0;
			$vmax2=0;
			$vmax3=0;
			$namegraph=$ln["ip_dest"].".grf".$opentime;
			$nomegrafico[]=$namegraph;
//		}
//			var_dump($nomegrafico);
//			var_dump($xvalues);
//			var_dump($valori);
			echo "<br>";
			if ($indice) grafico($valori,$namegraph,$titolo,$xvalues);
		}
	}
}
?>
<!--Fine Codice PHP -->
<?php include "menu.html";?>

      <!--      <td style="border: 1px solid black;background-color: rgb(238, 238, 238); height: 200px; width: 700px; vertical-align: top; color: rgb(249, 57, 6);">-->
 <td style="border: 1px solid black;background-image: url(weblink21.gif); height: 200px; width: 700px; vertical-align: top; color: rgb(249, 57, 6);">
<!-- Form  -->
 <form style="text-align: center;" name="graphic" action="grafici.php" method="post">
 <!--  Sezione  di selezione Nodi per rappresentazione grafica  -->
<h2>Rappresentazione Grafica del Traffico sui Nodi<br>
 <?php
#---------------------------------------------------------------------------------------
#       Sezione Compilazione Lista dei Nodi Grafici
#---------------------------------------------------------------------------------------
//	echo $nodi;
	if(($operazione=="Aggiungi") || ($operazione=="")){
		echo '<h4> Scegli quelli che vuoi ispezionare</b><br><br>'	;
		echo '<select name = "grafico" >';
		foreach ($nodi as $nodo){
	//		echo "nodo ".$nodo['nome']."<br>";
			echo '<option value= "'.$nodo['nome']."@".$nodo['ip']."@".$nodo['ID'].'">'.$nodo['nome']."@".$nodo['ip'].'</option>';
		}
		echo '</select>';
	}
?>
<!--------------------------------------------------------------------->
<input name="lista" <?php  echo " value=".'"'.$lista.'"'; ?> type="hidden" size='100' readonly>
<!--  Sezione  di Rappresentazione grafica  dei Nodi -->
<?php
#---------------------------------------------------------------------------------------
#       Sezione Presentazione  Grafici (immagini .png)
#       nome immagine IP.wifi.del.nodo.png
#---------------------------------------------------------------------------------------
	if(($operazione=="Aggiungi") || ($operazione==""))
		echo'<input name="operazione" value="Aggiungi" type="submit"><br><hr>';
	if ($operazione=="Disegna") {
			foreach ($nomegrafico as $grafico){
//				echo $grafico."<br>";
				echo '<img  alt="" src="'.$grafico.'"/>';
			}
	}
#---------------------------------------------------------------------------------------
#       Sezione Selezione periodo di tracciamento Grafici
# 			- Giornaliero
# 			- Mensile 
# 			- Annuale
#---------------------------------------------------------------------------------------
	elseif ($operazione=="Aggiungi"){
		$a =explode("|",$lista);
		foreach($a as $l){
			$l=explode("@",$l);
			echo "&nbsp;&nbsp;&nbsp;&nbsp;". $l[2]." - ".$l[0]." ---> ".$l[1]."<br>";
		}
		echo '<hr><select name = "giorno" >';
		$giorni=array("any","01","02","03","04","05","06","07","08","09","10");
		$giorni=array_merge($giorni,array("11","12","13","14","15","16","17","18","19","20"));
		$giorni=array_merge($giorni,array("21","22","23","24","25","26","27","28","29","30","31"));
		foreach ($giorni as $giorno){
			if ($giorno==$oggi[0]) echo '<option value= "'.$giorno.'"  selected>'.$giorno.'</option>';
			else echo '<option value= "'.$giorno.'">'.$giorno.'</option>';
		}
		echo '</select>';
		echo '<select name = "mese" >';
		$mesi=array("any","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
		foreach ($mesi as $mese){
//			echo $oggi[1].'<br>';
			if ($mese==$oggi[1]) echo '<option value= "'.$mese.' " selected>'.$mese.'</option>';
			else echo '<option value= "'.$mese.'">'.$mese.'</option>';
		}
		echo '</select>';
		echo '<select name = "anno" >';
		$anni=array("2014","2015","2016");
		foreach ($anni as $anno){
			if ($anno==$oggi[2]) echo '<option value= "'.$anno.'"selected>'.$anno.'</option>';
			else echo '<option value= "'.$anno.'">'.$anno.'</option>';
		}
		echo '</select><br>';
/*		echo '<hr><h6 style="color: blue ;"><input type="checkbox" name="bin" value="True">Rate In (B/s)';
		echo  '<input type="checkbox" name="min_bin" value="True">Min Rate In (B/s)';
		echo  '<input type="checkbox" name="max_bin" value="True">Max Rate In (B/s)<br>';
		echo  '<input type="checkbox" name="bout" value="True">Rate Out (B/s)';
		echo  '<input type="checkbox" name="min_bout" value="True">Min Rate Out (B/s)';
		echo  '<input type="checkbox" name="max_bout" value="True">Max Rate Out (B/s)<br><hr>';*/
	}
#---------------------------------------------------------------------------------------
#       Sezione Abilitazione Tracciamento Grafici
#---------------------------------------------------------------------------------------
	if ($operazione=="Aggiungi")echo '<input name="operazione" value="Disegna" type="submit">';
?>
<!--------------------------------------------------------------------->
</form>
	</td>
</tr>
<?php include "footer.html";?>



