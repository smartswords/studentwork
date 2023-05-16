<?php

const KEITARO_IP = '89.163.214.132';
const KEITARO_POSTBACK_KEY = 'c0c5f70';
const CRM_DOMAIN = 'affiliate-crm.com';
const LANDING_NAME = 'ElReceta';

$subid = $_POST['subid']??'';

sendLeadToCRM($subid);



function sendLeadToCRM($subid)
{

    $url = str_replace('https://','',$_POST['landing']);
    $url = str_replace('http://','',$url);

    $url = explode('/',$url)[0];
    $data = array(
        'phone' => str_replace(' ','',str_replace('+', '', $_POST['phonecc']) . $_POST['phone']),
        'full_name' => $_POST['first_name'] . ' ' . $_POST['last_name'],
        'email' => $_POST['email'],
        'ip' => $_SERVER['REMOTE_ADDR'],
        'landing' => $url.'?params={'.$subid.'}',
        'landing_name' => LANDING_NAME,
        'source' => 'FB',
        'country' => $_POST['country2'],
        // 'keitaro_id' => $subid,
        'description' => $_POST['comment'],
        // 'user_id' => 4,
        
    );

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://affiliate-crm.com/api/leads',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => http_build_query($data),
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    $response = json_decode($response, true);

    setcookie('cabinet', $response['link_auto_login'], time() + 60 * 90);
    // // if($response[])
    // if(isset($response['status'])){        
    //     header('location: ./thanks.php?status=success');
    // }else{

        header('location: ./thanks.php?pixel='.$_POST['pixel']);
    // }
}
