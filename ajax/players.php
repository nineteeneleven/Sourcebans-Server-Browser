<?php
if (isset($_GET['ip'])) {

define('NineteenEleven', TRUE);

include_once '../includes/SourceQuery/SourceQuery.class.php';
	$Query = new SourceQuery( );


	$ip = $_GET['ip'];
	$port = $_GET['port'];
	if (empty($ip)||empty($port)) {
		die();
	}
	define( 'SQ_TIMEOUT',     1 );
	define( 'SQ_ENGINE',      SourceQuery :: SOURCE );

	try
	{
		$Query->Connect( $ip, $port, SQ_TIMEOUT, SQ_ENGINE );
			$players = $Query->GetPlayers( );
			if (empty($players)) {
				echo "The server is empty.";
			}else{
				foreach ($players as $player) {
					$Name = $player['Name'];
					$TimeF = $player['TimeF'];
					// $Id = $player['Id'];
					// $Frags = $player['Frags'];
					// $Time = $player['Time'];
					if (empty($Name)) {
						$Name = "Player Loading In";
					}
					echo "<div class='player'>";
					echo "<div class='pName'>$Name</div>";
					echo "<div class='pTimeF'>$TimeF</div>";
					echo "</div>";
				}
			}
	}
	catch( Exception $e )
	{
		echo $e->getMessage( );
	}
	
	$Query->Disconnect( );

unset($Query);
}else{
	echo "No data was sent to the server. <br />";
}


?>