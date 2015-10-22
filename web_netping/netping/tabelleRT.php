<!--<script type="text/javascript">
setTimeout('location.href="http:./tabelleRT.php"',2000);
</script><br>-->
<!--<META HTTP-EQUIV="REFRESH" CONTENT="2">-->
<?php include "header_refresh.html";?>
<?php @include_once("stat/src/include.php"); ?>
<?php include "libreria.php";?>
<table style="border: 3px solid black;width: 800px; background-image: url(sfondi/nuvole.jpg);" align="center" background="&#8221;nuvole.jpg&#8221;" cellpadding="0" cellspacing="0">
<tr>
<?php
function disegna_barre($v,$n,$vm){
	$v1=str_repeat("|",log(($v["avg(byte_in_sec)"]*10000)/($vm),10)*15);
//	echo "v1=",log(($v["avg(byte_in_sec)"])*100/($vm),10)*30," ";
	$v2=str_repeat("|",log(($v["avg(byte_out_sec)"]*10000)/($vm),10)*15);
//	echo "v2=",log(($v["avg(byte_out_sec)"]*100)/($vm),10)*30,"<br>";
//	echo $v1," ",$v2," ",$vm,log(($v["avg(byte_out_sec)"]*10000)/($vm),10)*15,"<br>";
//	echo log(($v["avg(byte_out_sec)"]*10000)/($vm),10)*15,"<br>";
	echo'<form style="text-align:left; color:black" action="">';
	echo '<tr style='.'"text-align: center; color:green;"'.'><td ><font size="2">'.$n.'</font></td>';
	echo '<td><form style="text-align:left; color:black" action="">';
	echo '<input style="background-color:transparent;color:blue;border-width:0;font-weigth:bold;" type="text"  name="" value="'.$v1.'">';
	echo '</form></td>';
	echo '<td style="color:blue"><font size="2">'.(int)$v["avg(byte_in_sec)"].'</td>';
	echo '<td><form style="text-align:left; color:black" action="">';
	echo '<input style="background-color:transparent;color:brown;border-width:0;font-weigth:bold;" type="text" name="" value="'.$v2.'">';
	echo '</form></td>';
	echo '<td style="color:brown" ><font size="2">'.(int)$v["avg(byte_out_sec)"].'</td>';
}
?>
<!--<?php include "graph.php";?>-->
<?php
$oggi= explode("-",date("d-M-Y"));
$operazione = "Disegna";  //$_POST['operazione'];
$mesi=array("None","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
#---------------------------------------------------------------------------------------

#---------------------------------------------------------------------------------------
#       Sezione TracciamentoTabelle
#---------------------------------------------------------------------------------------
if($operazione=="Disegna"){
	$ora=date("H");
	$giorno=date("d");
	$mese=date("M");
	$anno=date("Y");
	$lista= $_POST['lista'];
//	echo $lista."<br>";
	$db = new DBclass();
	$db->connetti();
	$nodi = $db->estrai_record("nodi",array ("ID","nome","ip_wifi","ip_man","interface","creato","attivo","registrato"));
	$db->disconnetti();
	$a =explode("|",$lista); //lista dei grafici da tracciare
	$vin_max="0";
	$vout_max="0";
	$medie=array();
	foreach($a as $l){
		$ll=explode("@",$l);
		$condizione=sprintf("id_nodo = '%s' and giorno ='%s' and left(ora,2)='%s' and mese='%s' and anno='%s'",$ll[2],$giorno,$ora,$mese,$anno);
		$valori=array ("id_nodo","avg(byte_out_sec)");
		$valori= array_merge ($valori,array("avg(byte_in_sec)"));
		$vmedi = $db->estrai_valore_medio("dati",$valori,$condizione);
		if ($v_max < $vmedi[0]["avg(byte_in_sec)"]) $v_max=$vmedi[0]["avg(byte_in_sec)"];
		if ($v_max < $vmedi[0]["avg(byte_out_sec)"])$v_max=$vmedi[0]["avg(byte_out_sec)"];
		$medie[]=$vmedi[0];
		$indice=0;
		$namegraph=$ll[1]."@".$ll[0];
		$nomegrafico[]=$namegraph;
	}
}
?>
<!--Fine Codice PHP -->
<?php include "menu.html";?>
 <td style="border: 1px solid black;background-image: url(weblink21.gif); height: 200px; width: 700px; vertical-align: top; color: rgb(249, 57, 6);">
<!-- Form  -->
 <form style="text-align: center;" name="graphic" action="tabelleRT.php" method="post">
 <!--  Sezione  di selezione Nodi   -->
<h2>Rappresentazione In Tempo Reale  del Traffico sui Nodi<br><hr>
<!--------------------------------------------------------------------->
<?php echo '<input name="lista"  value="'.$lista.'" type="hidden" size="100" readonly'; ?>
<!--  Sezione  di Rappresentazione grafica  dei Nodi -->
<?php
#---------------------------------------------------------------------------------------
#       Sezione Presentazione Tabella 
#---------------------------------------------------------------------------------------
	if ($operazione=="Disegna") {
			echo '<table border="1" style="width:100%; color: blue;" align="center">';
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
		echo '<hr><h4>Ultimo aggiornamento    '.date("H:i:s");
?>
<!--------------------------------------------------------------------->
</form>
	</td>
</tr>
<?php include "footer.html";?>



