<?php
/**
	* Push data to a Mautic form
 *
 * @param  array   $data   The data submitted by your form
 * @param  integer $formId Mautic Form ID
 * @param  string  $ip     IP address of the lead
 * @return boolean 
 
 Example usage:

 require_once("mautic.php");
 
 $formID = 3;
 $form_data_array = array('mauticform[email]'=>$email, 'mauticform[nome1]'=>$nome, 'mauticform[telefone]'=>$contato->telefone, 'mauticform[mensagem]'=>$contato->mensagem);
  pushMauticForm('http://mautic.seusite.com.br',$form_data_array, $formID);
 
 */
function pushMauticForm($host,$data, $formId, $ip = null) {
	if ($host=='') throw new Exception('Endereco Mautic não definido');
    // Get IP from $_SERVER
    if (!$ip) {
        $ipHolders = array(
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        );
        foreach ($ipHolders as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = $_SERVER[$key];
                if (strpos($ip, ',') !== false) {
                    // Multiple IPs are present so use the last IP which should be the most reliable IP that last connected to the proxy
                    $ips = explode(',', $ip);
                    array_walk($ips, create_function('&$val', '$val = trim($val);'));
                    $ip = end($ips);
                }
                $ip = trim($ip);
                break;
            }
        }
    }
    
    $data['formId'] = $formId;
    // return has to be part of the form data array
    if (!isset($data['return'])) {
        $data['return'] = '';
    }
    $data = array('mauticform' => $data);
    // Change [path-to-mautic] to URL where your Mautic is
    $formUrl =  $host.'/form/submit?formId=' . $formId;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $formUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Forwarded-For: $ip"));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;     
}
?>