<?php include "header.html";?>
<?php @include_once("stat/src/include.php"); ?>
<?php include "libreria.php";?>
<table style="border: 3px solid black; width:900px; background-image: url(sfondi/nuvole.jpg);border: 3px solid black;" align="center" background="&#8221;nuvole.jpg&#8221;" cellpadding="0" cellspacing="0">

<tr>
<?php include "menu.html";?>
  <!--      <td style="border: 1px solid black;background-color: rgb(238, 238, 238); height: 200px; width: 700px; vertical-align: top; color: rgb(249, 57, 6);">-->
 <td style="border: 1px solid black;background-image: url(weblink21.gif); height: 200px; width: 800px; vertical-align: top; color: rgb(249, 57, 6);">
    
      
<!-- Form di Registrazione dati del Nodo -->
			   <h3 style="text-align: center;" >Elenco dei Nodi</h3>
				<table border="1" style="width:60%; color: blue;" align="center">
<?php
//		echo "<tr style=".'"text-align: center; color:black;"'."> <td>Nome</td> <td>IP Antenna</td><td>IP Manutenzione</td> <td>Interfaccia</td><td>Registrato</td></tr>";
		$db = new DBclass();
		$db->connetti();
		$nodi = $db->estrai_record("nodi",array ("ID","nome","location","ip","fetch_url","attivo","creato"));
//		$servizi= $db->estrai_record("servizi",array ("descrizione","ip","porta"));
//   	echo count($nodi);
		if (count($nodi) > 0){
//			var_dump($nodi[0]);
			foreach ($nodi as $row){
				$servizi=array();
//				$servizi= $db->estrai_record("servizi",array ("descrizione","ip","porta"),"id_nodo='".$row['ID']."'");
//				var_dump($servizi);
				$data_ora=explode(" ",$row['creato']);
				$attivo="No";
				$colore='"text-align: center; color:red;"';
				if ($row['attivo']) {
					$attivo="Si";
					$colore='"text-align: center; color:blue;"';
				}
//				$ora=explode(" ",$row['creato'])[1];
				echo "<tr style=".'"text-align: center; color:black;"'."><td >Nome</td><td style=".'"text-align: center; color:green;"'.">".$row['nome']."</td></tr>";
				echo "<tr style=".'"text-align: center; color:black;"'."><td >Posizione</td><td style=".'"text-align: center; color:green;"'.">".$row['location']."</td></tr>";
				echo "<tr style=".'"text-align: center; color:black;"'."><td >Indirizzo IP</td><td style=".'"text-align: center; color:blue;"'.">".$row['ip']."</td></tr>";
				echo "<tr style=".'"text-align: center; color:black;"'."><td >URL</td><td style=".'"text-align: center; color:blue;"'.">".$row['fetch_url']."</td></tr>";
//				echo "<tr style=".'"text-align: center; color:black;"'."><td >Attivo</td><td style=".'"text-align: center; color:red;"'.">".$attivo."</td></tr>";
				echo "<tr style=".'"text-align: center; color:black;"'."><td >Attivo</td><td style=".$colore.">".$attivo."</td></tr>";
				echo "<tr style=".'"text-align: center; color:black;"'."><td >Registrato il</td><td style=".'"text-align: center; color:blue;"'.">".$data_ora[0]."</td></tr>";
//				foreach ($servizi as $s){
//					var_dump($s);
//					echo "<tr style=".'"text-align: center; color:red;background-color:beige;"'."><td >Servizio</td><td style=".'"text-align: left; color:red;"'.">".$s['descrizione']."</td></tr>";
//					echo "<tr style=".'"text-align: center; color:red;;background-color:beige;"'."><td >Indirizzo</td><td style=".'"text-align: left; color:red;"'.">".$s['ip'].":".$s['porta']."</td></tr>";
//				}
				echo "<tr style=".'"text-align: center;background-color:black;"'."><td ></td><td style=".'"text-align: left; color:blue;"'.">"."</td></tr>";
//				echo "<br>";
//				echo "<tr> <td>".$row['nome']."</td> <td>".$row['ip_wifi']."</td><td>".$row['ip_man']."</td> <td>".$row['interface']."</td><td>".$row['creato']."</td></tr>";
			}			
		}
		$db->disconnetti();
?>
				</table>
	   </td>
 </tr>
<?php include "footer.html";?>

