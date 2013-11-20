<?php
define("NineteenEleven", true);
include_once '../includes/config.php';
include_once '../includes/sourcebans.php';
include_once '../includes/SourceQuery/SourceQuery.class.php';
$Query = new SourceQuery( );
$sb = new SourceBans;

define( 'SQ_TIMEOUT',     1 );
define( 'SQ_ENGINE',      SourceQuery :: SOURCE );

$servers = $sb->serverList();
$i=1;
foreach ($servers as $server) {

	try
	{
		$Query->Connect( $server["ip"], $server["port"], SQ_TIMEOUT, SQ_ENGINE );
		$svInfo = $Query->GetInfo( );
		$name = $svInfo['HostName'];
		if (empty($name)) {
			$name = "Server did not respond";
		}
		$tags = $svInfo['GameTags'];
		$Map = $svInfo['Map'];
		$Players = $svInfo['Players'];
		$MaxPlayers = $svInfo['MaxPlayers'];
		$Mod = $svInfo['ModDesc'];
		$link = "'".$server['ip']."','".$server['port']."','players{$i}'";
		echo "<div class='server'>";
		echo "<a href='steam://connect/".$server['ip'].":".$server['port']."'><img src='images/connect.png' title='Connect to $name'></a><a href='#' onclick=getPlayers(".$link.") id='sv{$i}'>";
		echo "<div class='hostname' id=hn{$i} data-tags='{$tags}'>$name</div>";
		echo "<div class='mod' id=map{$i}>$Mod</div>";		
		echo "<div class='map' id=map{$i}>$Map</div>";
		echo "<div class='numPlayers' id='numPlayers{$i}'>{$Players}/{$MaxPlayers}</div>";
		echo "</a></div>";
		echo "<div id='players{$i}' class='players' style='display:none;'></div>";


	}
	catch( Exception $e )
	{
		//die();
	}
	
	$Query->Disconnect( );

$i++;
}
echo "<a href='http://nineteeneleven.info'><div class='server'>Sourcebans Server Browser Powered by NineteenEleven</div></a>";
unset($i);
unset($Query);
unset($sb);
?>