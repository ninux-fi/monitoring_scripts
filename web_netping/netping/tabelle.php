<!--<script type="text/javascript">
setTimeout('location.href="http:./tabelleRT.php"',2000);
</script><br>-->
<!--<META HTTP-EQUIV="REFRESH" CONTENT="2">-->
<?php include "header.html";?>
<?php @include_once("stat/src/include.php"); ?>
<?php include "libreria.php";?>
<table style="border: 3px solid black;width: 800px; background-image: url(sfondi/nuvole.jpg);" align="center" background="&#8221;nuvole.jpg&#8221;" cellpadding="0" cellspacing="0">
<tr>
<?php
function disegna_barre($v,$n,$vm){
//	echo $vm;
//	echo ($v["avg(byte_in_sec)"]*100)/$vi,str_repeat("|",10);
	$v1=str_repeat("|",($v["avg(byte_in_sec)"]*100)/(2*$vm));
	$v2=str_repeat("|",($v["avg(byte_out_sec)"]*100)/(2*$vm));
//	echo $v1," ",$v2," ",$vm,"<br>";
	echo'<form style="text-align:left; color:black" action="">';
//	echo '<br><h4>'.$n.' '.'In&nbsp;&nbsp;'.'<input style="background-color:transparent;color:blue;border-width:0;font-weigth:bold;" type="text" name="" value="'.str_repeat("|",($v["avg(byte_in_sec)"]*100)/$vi).'">',($v["avg(byte_in_sec)"])."B/s";
	echo '<tr style='.'"text-align: center; color:green;"'.'><td ><font size="2">'.$n.'</font></td>';
//	echo '<td ><font size="2">'.$v["avg(byte_in_sec)"].'</td><td><td>';
	echo '<td><form style="text-align:left; color:black" action="">';
	echo '<input style="background-color:transparent;color:blue;border-width:0;font-weigth:bold;" type="text" name="" value="'.$v1.'">';
	echo '</form></td>';
	echo '<td style="color:blue"><font size="2">'.(int)$v["avg(byte_in_sec)"].'</td>';
	echo '<td><form style="text-align:left; color:black" action="">';
	echo '<input style="background-color:transparent;color:brown;border-width:0;font-weigth:bold;" type="text" name="" value="'.$v2.'">';
	echo '</form></td>';
	echo '<td style="color:brown" ><font size="2">'.(int)$v["avg(byte_out_sec)"].'</td>';
}
?>
<?php include "graph.php";?>
<?php
exec("rm "."*.grf*");
$oggi= explode("-",date("d-M-Y"));
$operazione= $_POST['operazione'];
$giorno=$_POST['giorno'];
$mese=$_POST['mese'];
$anno=$_POST['anno'];
$mesi=array("None","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
$bin_graph=$_POST['bin'];
$min_bin_graph=$_POST['min_bin'];
$max_bin_graph=$_POST['max_bin'];
$bout_graph=$_POST['bout'];
$min_bout_graph=$_POST['min_bout'];
$max_bout_graph=$_POST['max_bout'];
$opentime=time();
#---------------------------------------------------------------------------------------
#       Sezione Ricerca Nodi
#---------------------------------------------------------------------------------------
if(($operazione=="Aggiungi") || ($operazione=="")){
	$lista= $_POST['lista'];
	if ($lista){
		$lista=$lista."|".$_POST['grafico'];
	}
	else{
		$lista=$_POST['grafico'];
	}
	//echo "operazione ".$operazione."<br>";
	$db = new DBclass();
	$db->connetti();
	$nodi = $db->estrai_record("nodi",array ("ID","nome","ip_wifi","ip_man","interface","creato","attivo","registrato"));
	$db->disconnetti();
	if ($_POST['grafico']=="Tutti"){
			$lista="";
		foreach($nodi as $nodo){
			$lista=$lista.$nodo['nome']."@".$nodo['ip_wifi']."@".$nodo['ID']."|";
//			$operazione="Disegna";
		}
		$lista=substr($lista,0,strlen($lista)-1);
	}
}
#---------------------------------------------------------------------------------------

#---------------------------------------------------------------------------------------
#       Sezione Tracciamento Grafici
#---------------------------------------------------------------------------------------
if($operazione=="Disegna"){
	$ora=date("H");
	$giorno=date("d");
	$mese=date("M");
	$anno=date("Y");
	$lista= $_POST['lista'];
	$db = new DBclass();
	$db->connetti();
	$nodi = $db->estrai_record("nodi",array ("ID","nome","ip_wifi","ip_man","interface","creato","attivo","registrato"));
	$db->disconnetti();
	$a =explode("|",$lista); //lista dei grafici da tracciare
	//var_dump($a);
//	$giorno="24";
//	$mese="Sep";
//	$anno="2014";
	$vin_max="0";
	$vout_max="0";
	$medie=array();
	foreach($a as $l){
//		echo var_dump($l);
		$ll=explode("@",$l);
//		echo $ll[1],"-".$ll[0]."-".$ll[2]."<br>";
//		echo $ll[1],"-".$ll[0]."<br>";
		$condizione=sprintf("id_nodo = '%s' and giorno ='%s' and left(ora,2)='%s' and mese='%s' and anno='%s'",$ll[2],$giorno,$ora,$mese,$anno);
//		echo $condizione."<br>"; 
		$valori=array ("id_nodo","avg(byte_out_sec)");
		$valori= array_merge ($valori,array("avg(byte_in_sec)"));
		$vmedi = $db->estrai_valore_medio("dati",$valori,$condizione);
//		var_dump($vmedi);
		if ($v_max < $vmedi[0]["avg(byte_in_sec)"]) $v_max=$vmedi[0]["avg(byte_in_sec)"];
		if ($v_max < $vmedi[0]["avg(byte_out_sec)"]) $v_max=$vmedi[0]["avg(byte_out_sec)"];
		$medie[]=$vmedi[0];
		$indice=0;
//		var_dump($medie);
		$namegraph=$ll[1]."@".$ll[0];
		$nomegrafico[]=$namegraph;
	}
}
?>
<!--Fine Codice PHP -->
<?php include "menu.html";?>
      <!--      <td style="border: 1px solid black;background-color: rgb(238, 238, 238); height: 200px; width: 700px; vertical-align: top; color: rgb(249, 57, 6);">-->
 <td style="border: 1px solid black;background-image: url(weblink21.gif); height: 200px; width: 700px; vertical-align: top; color: rgb(249, 57, 6);">
<!-- Form  -->
 <form style="text-align: center;" name="graphic" action="tabelle.php" method="post">
 <!--  Sezione  di selezione Nodi   -->
<h2>Rappresentazione In Tempo Reale  del Traffico sui Nodi<br>
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
			echo '<option value= "'.$nodo['nome']."@".$nodo['ip_wifi']."@".$nodo['ID'].'">'.$nodo['nome']."@".$nodo['ip_wifi'].'</option>';
		}
		echo '<option value= "Tutti">Tutti</option>';
		echo '</select>';
	}
?>
<!--------------------------------------------------------------------->
<?php echo '<input name="lista"  value="'.$lista.'" type="hidden" size="100" readonly>'; ?>
<!--  Sezione  di Rappresentazione grafica  dei Nodi -->
<?php
#---------------------------------------------------------------------------------------
#       Sezione Presentazione  Tabelle
#---------------------------------------------------------------------------------------
	if(($operazione=="Aggiungi") || ($operazione==""))
		echo'<input name="operazione" value="Aggiungi" type="submit"><br><hr>';
	if ($operazione=="Disegna") {
			echo '<table border="1" style="width:90%; color: blue;" align="center">';
			echo'<form style="text-align:left; color:black" action="">';
			echo '<tr style='.'"text-align: center; color:green;"'.'><td ><font size="2">Nodo</font></td>';
			echo '<td style="color:blue"><font size="2">%&nbsp;Input</td>';
			echo '<td style="color:blue"><font size="2">B/s</td>';
			echo '<td style="color:brown"><font size="2">%&nbsp;Output</td>';
			echo '<td style="color:brown" ><font size="2">B/s</td>';
			$i=0;
			foreach($medie as $vm){
				disegna_barre($vm,$nomegrafico[$i],$v_max);
				$i=$i+1;
			}
			echo '</table>';
}
#---------------------------------------------------------------------------------------
#       Sezione Tselezione periodo di tracciamento Grafici
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
	}
#---------------------------------------------------------------------------------------
#       Sezione Abilitazione Tracciamento Grafici
#---------------------------------------------------------------------------------------
	if ($operazione=="Aggiungi") {
		echo '</form>';
		echo '<form style="text-align: center;" name="graphic" action="tabelleRT.php" method="post">';
		echo '<input name="lista"  value="'.$lista.'" type="hidden" size="100" readonly>';
		echo '<input name="operazione" value="Disegna" type="submit">';
		echo '</form>';
	}

?>
<!--------------------------------------------------------------------->
<!--</form>-->
	</td>
</tr>
<?php include "footer.html";?>



