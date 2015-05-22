<?php
set_time_limit(999999);
$port = "90";
$range = "179.111.216.0/24";

function parseIP( $content ){
	$p = explode("(", $content );
	$p1 = explode(")", $p[1] );
	return $p1[0];
}

function validIP($ip){
    if(preg_match("^([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}^", $ip))
        return true;
    else
        return false;
} 

$ips = array();

while (@ ob_end_flush()); 

$proc = popen("nmap -Pn -p ".$port." --open ".$range, 'r');
while (!feof($proc))
{
    $data = fgets($proc, 512);
    if( !empty( trim($data) ) )
	if( validIP( parseIP( $data ) ) )
	  $ips[] = parseIP( $data );

    @ flush();
}

$ipsToJs = "\"".implode("\",\"", $ips)."\"";
?>

<script>
	var ips = [<?=$ipsToJs?>];
	var port = <?=$port?>;

	for(var i=0; i<ips.length; i++){
		console.info( ips[i] + ":" + port )
        	window.open("http://" + ips[i] + ":" + port, "_blank"); // will open new tab on window.onload
	}
</script>
