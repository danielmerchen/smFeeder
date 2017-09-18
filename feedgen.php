<?php
// Apparently there is JSON of available Files. Snag it later, will need to rebuilt the login system.
$xml = new SimpleXMLElement('<xml/>');
$xml->addAttribute('version', '1.0'); 
$xml->addAttribute('encoding', 'UTF-8');

// Base start date off of Today if it's Sunday, or last Sunday if it has already passed.
$stream_title = "Watermark Sunday";

$datestring = date('D');
if($datestring == 'Sun'){
	$datestring	= strtotime('this Sunday');	
}
else{
	$datestring	= strtotime('last Sunday');	
}
//$datestring		= strtotime('last Sunday');
$week_start 	= date('Y-m-d', $datestring); 																	// Start date is in YYYY-MM-DD format, this date will be relative to server time.
$rebroadcast 	= array ("00:00","03:58","07:57","11:58","15:58","19:58"); 										// 24 Hour Schedule of Rebroadcast Times, Relative to Account Timezone
$assets 		= array ("20170910_0855_2200_0000.mp4");														// Files to be Assoicated with Events, Does Not Check if they Actually Exist on Service, previously this could've been an array of inputs, as of sometime in 2016 they moved to transcoding the input files in Amazon Elastic Transcoder - now we only feed a single asset.
$streamname 	= "yourstreamname";																				// Stream to push simulated live events to.

$simlive_day = array(
	//"sunday"	=> $rebroadcast,					// Use the regular broadcast schedule, uncomment this line if you'd like this day to be generated.
    "sunday"	=> array ("12:58","14:58","16:58"), // Make an exception to the schedule, override with a custom arrahy of values. These values are for a 24 clock, values 0-23.
    "monday"	=> $rebroadcast,
   	"tuesday"	=> $rebroadcast,
    "wednesday" => $rebroadcast,
    "thursday"  => $rebroadcast,
    "friday"	=> $rebroadcast,
    "saturday"	=> $rebroadcast,
);

header('Content-type: text/xml');
header('Content-Disposition: attachment; filename="'.date('Ymd', $datestring).date('_YmdGis').'_schedule.xml"');
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