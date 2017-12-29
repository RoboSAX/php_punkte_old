<?php
	$data = "";
	$content[][] = "";
	$today = "2018,5,20,";
	$db_ablauf = $pdo -> prepare("SELECT * FROM ablauf ORDER BY position,ablaufID");
	$db_ablauf -> execute();
	$i = 0;
	while($db_event = $db_ablauf -> fetch(PDO::FETCH_ASSOC)) {
		$content[$i] = $db_event;
		$data .= "['".$db_event["bezeichnung"]."', ".mktime(substr($db_event["zeit"],0,2),substr($db_event["zeit"],3,2)).", ".mktime(substr($db_event["zeit"],0,2),(substr($db_event["zeit"],3,2)+$db_event["dauer"]))."],";
		$i++;
	}
	trim($data,",");
?>
<h1>Ablauf</h1>
<div id="chart"></div>
<script>
	$('#chart').jqChart({
        legend: {visible: false},
		animation: {duration: 2},
        shadows: {enabled: true},
		axes: [{
			type: 'dateTime',
			location: 'bottom',
			//minimum: <?=mktime(9)?>,
			//maximum: <?=mktime(17)?>,
			interval: 5,
			intervalType: 'minutes' // 'years' |  'months' | 'weeks' | 'days' | 'minutes' | 'seconds' | 'millisecond'

		}],
		crosshairs: {
            enabled: true,
            hLine: false,
            vLine: {strokeStyle: '#cc0a0c'},
			snapToDataPoints: false
        },
        series: [{
			type: 'gantt',
			fillStyles: ['#CA6B4B', '#005CDB', '#F3D288', '#506381', '#F1B9A8'],
			data: [<?=$data?>]
		}]
    });
</script>
<form action="?abschnitt=ablauf" method="post">
	<table class="list">
		<thead>
			<tr>
				<th></th>
				<th>Position</th>
				<th>Typ</th>
				<th>Bezeichnung</th>
				<th>Zeit</th>
				<th>Dauer<br>(in Minuten)</th>
				<th>Puffer<br>(in Minuten)</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$i = 0;
				foreach($content as $entry) {
					echo "<tr><td><input type='radio' name='ablaufID' value='".$entry["ablaufID"]."'></td>";
					echo "<td>".$entry["position"]."</td>";
					echo "<td>".$entry["typ"]."</td>";
					echo "<td>".$entry["bezeichnung"]."</td>";
					echo "<td>".substr($entry["zeit"],0,5)."</td>";
					echo "<td>".$entry["dauer"]."</td>";
					echo "<td>".$entry["puffer"]."</td></tr>";
					$i++;
				}
			?>
		</tbody>
	</table>
	<input type="submit" name="addevent" value="Zeitabschnitt erstellen">
    <input type="submit" name="changeevent" value="Zeitabschnitt bearbeiten">
</form>