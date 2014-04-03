<?php
// Apparently there is JSON of Available Files. Snag it later.
$xml = new SimpleXMLElement('<xml/>');
$xml->addAttribute('version', '1.0'); 
$xml->addAttribute('encoding', 'UTF-8');

$datestring = strtotime('last Sunday');
$week_start = date('Y-m-d', $datestring); 													// Start date is in YYYY-MM-DD format, this date will be relative to server time.
$rebroadcast = array ("00:00","04:00","08:00","12:00","16:00","20:00"); 								// 24 Hour Schedule of Rebroadcast Times, Relative to Account Timezone
$assets = array ("20140330_1_300k_0003.mp4", "20140330_1_600k_0003.mp4", "20140330_1_1200k_0003.mp4");	// Files to be Assoicated with Events, Does Not Check if they Actually Exist on Service
$streamname = "simlive1";																				// Stream to push simulated live events to.

$simlive_day = array(
    "sunday"    => array ("13:15","15:00","17:30"), // Sunday exception to normal schedule.
    "monday"  => $rebroadcast,
    "tuesday"  => $rebroadcast,
    "wednesday" => $rebroadcast,
    "thursday"  => $rebroadcast,
    "friday" => $rebroadcast,
    "saturday" => $rebroadcast,
);

Header('Content-type: text/xml');
Header('Content-Disposition: attachment; filename="'.date('Ymd', $datestring).'_schedule.xml"');

echo '<?xml version="1.0" encoding="UTF-8" ?>'."\r\n"; ?>
<schedule>
	<!-- Start date is in YYYY-MM-DD format -->
	<week start_date="<?php echo $week_start;?>">
		<?php

		foreach ($simlive_day as $day => $services) {
			echo "\r\n		";
			echo "<$day>\r\n";

						foreach ($services as $service => $servicetime) {				// Iterate through the times for each day, create an event for each.
						echo "			";
						echo "<event start_time=\"$servicetime\" stream_name=\"$streamname\">\r\n";
							foreach ($assets as $files => $file) {						// Iterate through the array of files and create an file tag for each.
							echo "				";
							echo "<file>$file</file>\r\n";
								//$file = $event->addChild('file', $file);
							}
						echo "			";
						echo "</event>\r\n";
					}
			echo "		";
			echo "</$day>";
		}
		?>

	</week>
</schedule>