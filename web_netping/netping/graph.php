
<?php 

include "libchart/classes/libchart.php";

function grafico_doppio ($v,$nomegrafico,$titolo,$i,$ii,$init) {
//	$chart = new VerticalBarChart(600,200);
//	$chart = new VerticalBarChart();
	$chart = new LineChart();
	$dataSet = new XYDataSet();
	$x=$init;
	foreach($v as $e){
		$dataSet->addPoint(new Point(sprintf("%02d",$x),$e[$i]+0));
		$x=$x+1;
	}
	$dataSet1 = new XYDataSet();
	$x=$init;
	foreach($v as $e){
		$dataSet1->addPoint(new Point(sprintf("%02d",$x),$e[$ii]+0));
		$x=$x+1;
	}
	$dataSet3 = new XYSeriesDataSet();
	$dataSet3->addSerie("Vento Medio", $dataSet);
	$dataSet3->addSerie("Raffiche", $dataSet1);
	$chart->setDataSet($dataSet3);
	$chart->setTitle($titolo);
	$chart->render($nomegrafico);
}

function grafico ($v,$nomegraph,$titolo,$x,$upper=Null){
#	$chart = new VerticalBarChart(800,200);
	$chart = new LineChart(700,245);
//	var_dump($x);
	$ii=0;
	$serie=array();
//	echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>><br>";
	foreach ($v as $s){
//		var_dump($s);
//		echo "<br>";
		$serie[$ii] = new XYDataSet();
		$nome_serie[]=$s[0];
//		echo $s[0]."<br>";
//		unset($s[0]);
		$flag=True;
		$i=0;
		$ib=0;
		foreach($s as $e){
			if(!$flag){ # per saltare il primo elemento
//			var_dump($e+0.0);
				while ($ib<$x[$i]){
					$serie[$ii]->addPoint(new Point(sprintf("%02d",$ib),0.0));
					$ib=$ib+1;
				}
				$serie[$ii]->addPoint(new Point(sprintf("%02d",$x[$i]),$e+0.0));
//				echo "i=",$i,"x=",$x[$i],"ib=",$ib."<br>";
				$ib=$ib+1;
				$i=$i+1;
			}
			$flag=False;
		}
		$ii=$ii+1;
	}
	$ii=0;
	$dataSet = new XYSeriesDataSet();
	foreach ($serie as $ds){
		$dataSet->addSerie($nome_serie[$ii],$ds);
		$ii=$ii+1;
	}
	if ($upper){
//		echo "Set massimo valore",$upper,"<br>";
		$chart->setUpper($upper); //solo con LineChar()
		$chart->setLower(0.0);	 //solo con LineChar()
	}
	$chart->setDataSet($dataSet);
	$chart->setTitle($titolo);
	$chart->render($nomegraph);
}
?>
