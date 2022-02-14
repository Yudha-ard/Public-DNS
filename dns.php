<?php
 
 echo "Masukan Domain : ";
 $type;
 $in = fopen("php://stdin","r");
 $d = trim(fgets($in));

 $url = "https://dns.google/resolve?name=".$d."&type=A&ecs=&disable_dnssec=true&show_dnssec=true";

 $curl = curl_init($url);
 curl_setopt($curl, CURLOPT_URL, $url);
 curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
 
 $headers = array(
    "Accept: application/json",
 );
 curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
 curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
 curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
 
 $res = curl_exec($curl);
 curl_close($curl);

 echo "============================\n";
 echo " \e[1;36;42mDomain\e[0m : ".$d."\n";
 echo "============================\n";
 $dataRes = json_decode($res, true);

if(is_null($dataRes)) { 
    echo "================================================\n";
    echo "  Null\n";
    echo "================================================\n";
} else if(empty($dataRes['Answer'])) {
    echo "================================================\n";
    echo "  Empty\n";
    echo "================================================\n";
} else {
    $answer = [];
    foreach($dataRes['Answer'] as $key => $value) {
        $answer[$key]['name'] = $value['name'];
        $answer[$key]['type'] = $value['type'];
        $answer[$key]['ttl'] = $value['TTL'];
        $answer[$key]['data'] = $value['data'];
    }
    
    foreach($answer as $key=>$node) {
        if (($node["type"]) == 1) {
            $type = "A";
        }

        echo "domain : ".$node["name"]."\ntype   : ". $type."\nTTL    : ".$node["ttl"]."\nIP     : ".$node["data"]."\n========================\n";
    }
}

if(is_null($dataRes)) { 
    echo "================================================\n";
    echo "  Null\n";
    echo "================================================\n";
} else if(empty($dataRes["Comment"])) {
    echo "================================================\n";
    echo "  Empty\n";
    echo "================================================\n";
} else {
    echo "================================================\n";
    echo "Source  : ".$dataRes["Comment"]."\n";
    echo "================================================\n";
}


?>