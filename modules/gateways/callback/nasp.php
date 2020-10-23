<?php
    /*
        Gateway NASP 
        Desenvolvido por Davi Souza contato@linknacional.com.br em 16/05/2011
        Versao: 2.2
        Empresa: LINK NACIONAL / MoIP Pagamentos
    */


// FUNCAO PARA SALVAR EM ARQUIVO debug.txt na pasta do callback para ajuda de verificações.
if (!function_exists('log_var')) {
	function log_var($var, $name='', $to_file=false){
    	if ($to_file==true) {
            $txt = @fopen('nasp-debug.txt','a');
            if ($txt){
                fwrite($txt, "-----------------------------------\n");
                fwrite($txt, $name."\n");
                fwrite($txt,  print_r($var, true)."\n");
                fclose($txt);//
            }
        } else {
             echo '<pre><b>'.$name.'</b>'.
                  print_r($var,true).'</pre>';
        }
      }
}
//www.linknacional.com.br/cliente/modules/gateways/callback/nasp.php

?>

<?php
// Require libraries needed for gateway module functions.
require_once __DIR__ . '/../../../init.php';
require_once __DIR__ . '/../../../includes/gatewayfunctions.php';
require_once __DIR__ . '/../../../includes/invoicefunctions.php';



// Detect module name from filename.
$gatewayModuleName = "whmcs_moip"; // basename(__FILE__, '.php');

// Fetch gateway configuration parameters.
$gatewayParams = getGatewayVariables($gatewayModuleName);

// Die if module is not active.
if (!$gatewayParams['type']) {
    die("Module Not Activated");
}

/*
    RETORNO DE VARIAVEIS DE $_POST
    [id_transacao] => 9149:6266
    [valor] => 3490
    [status_pagamento] => 3
    [status_data] => 2017/11/22-22:17:47
    [cod_moip] => 98495239
    [forma_pagamento] => 73
    [tipo_pagamento] => BoletoBancario
    [parcelas] => 1
    [recebedor_login] => linknacional
    [email_consumidor] => testemoip@moip.com.br
*/

// Retrieve data returned in payment gateway callback
$id_transacao = explode(":", $_POST['id_transacao']);

$success = $_POST['status_pagamento'];
$status = $_POST['status_pagamento'];

$invoiceId = $id_transacao[0];// ID DA INVOICE NO WHMCS

$transactionId = $_POST['cod_moip'];

$valor = $_POST['valor'];
$real = substr($valor,0,-2);
$cent = substr($valor,-2);
$paymentAmount = $real.".".$cent;

$paymentFee = 0; // PRECISA CALCULAR CUSTO DE PAGAMENTO $_POST["tipo_pagamento"];
$data_hora = $_POST['status_data'];//date("d/m/Y H:i:s");

$hash = $_GET["key"];
$transactionStatus = $success ? 'Success' : 'Failure';


// Se o valor pago for maior que o valor da invoice, manter o valor da invoice, para pagamento parcelados.
// Obter o valor da Invoice
use WHMCS\Database\Capsule;
try{
    $invoiceTotal = Capsule::table('tblinvoices')->select('total')->where('id', '=', $invoiceId)->first();
    if($invoiceTotal->total<$paymentAmount ){
        $paymentAmount = $invoiceTotal->total;
        log_var ("PAGAMENTO MAIOR QUE FATURA",$paymentAmount.'total pagamento | Invoice pagamento'.$invoiceTotal->total);
    }
}catch (\Exception $e) {
    log_var ("I couldn't get invoice number. {",$e->getMessage());
}

/// VERIFICAR SE RETORNA AS TAXAS DO MOIP
log_var("RETORNO NASP". $status,print_r($_POST, true), true);
/**
 * Validate callback authenticity.
 *
 * Most payment gateways provide a method of verifying that a callback
 * originated from them. In the case of our example here, this is achieved by
 * way of a shared secret which is used to build and compare a hash.
 */
$secretKey = $gatewayParams['key_whmcs'];//key_whmcs
if ($hash != md5($invoiceId . $transactionId . $paymentAmount . $secretKey)) {
    $transactionStatus = 'Hash Verification Failure';
    $success = false;
    log_var ("break",'Hash Verification Failure');
}
/**
 * Validate Callback Invoice ID.
 *
 * Checks invoice ID is a valid invoice number. Note it will count an
 * invoice in any status as valid.
 *
 * Performs a die upon encountering an invalid Invoice ID.
 *
 * Returns a normalised invoice ID.
 *
 * @param int $invoiceId Invoice ID
 * @param string $gatewayName Gateway Name
 */
$invoiceId = checkCbInvoiceID($invoiceId, $gatewayParams['name']);

/**
 * Check Callback Transaction ID.
 *
 * Performs a check for any existing transactions with the same given
 * transaction number.
 *
 * Performs a die upon encountering a duplicate.
 *
 * @param string $transactionId Unique Transaction ID
 */
checkCbTransID($transactionId);
/**
 * Log Transaction.
 *
 * Add an entry to the Gateway Log for debugging purposes.
 *
 * The debug data can be a string or an array. In the case of an
 * array it will be
 *
 * @param string $gatewayName        Display label
 * @param string|array $debugData    Data to log
 * @param string $transactionStatus  Status
 */

 /*
Código  Status  		Descrição 
1  		autorizado  	Pagamento já foi realizado porém ainda não foi creditado na Carteira MoIP recebedora (devido ao floating da forma de pagamento) 
2		iniciado		Pagamento está sendo realizado ou janela do navegador foi fechada (pagamento abandonado) 
3  		boleto impresso	Boleto foi impresso e ainda não foi pago 
4  		concluido  		Pagamento já foi realizado e dinheiro já foi creditado na Carteira MoIP recebedora 
5 		cancelado 		Pagamento foi cancelado pelo pagador, instituição de pagamento, MoIP ou recebedor antes de ser concluído 
6 		em análise  	Pagamento foi realizado com cartão de crédito e autorizado, porém está em análise pela Equipe MoIP. Não existe garantia de que será concluído 
*/
		if($status == "3"){
			logTransaction($gatewayParams["name"],$_POST,"Boleto foi impresso e ainda não foi pago"); # Save to Gateway Log: name, data array, status
			log_var ("Status [".$status."] Transação Aguardando", "Boleto foi impresso e ainda não foi pago. Retorno de dados MoIP, Pedido: ".$invoiceId . "Sucess:".print_r($success,true)."Data: ".$data_hora, true);
		}
		if($status == "4"){
			logTransaction($gatewayParams["name"],$_POST,"Concluído");
			//log_var ("Status [".$status."] Transação Concluída", "valor pago pelo cliente e identificado pelo MoIP. ", "Retorno de dados MoIP, Pedido: ".$invoiceid."Data: ".$data_hora, true);
		} 
		if($status == "5"){
			logTransaction($gatewayParams["name"],$_POST,"Pagamento foi cancelado pelo pagador, instituição de pagamento, MoIP ou recebedor antes de ser concluído");
			log_var ("Status [".$status."] Transação Cancelada", "Pagamento foi cancelado pelo pagador, instituição de pagamento, MoIP ou recebedor antes de ser concluído. Retorno de dados MoIP, Pedido: ".$invoiceid."Data: ".$data_hora, true);
		}
		if($status == "6"){
			logTransaction($gatewayParams["name"],$_POST,"Pagamento foi emitido, porém está em análise. Não existe garantia de que será concluído");
			log_var ("Status [".$status."] Transação Analisando", "Pagamento foi emitido, porém está em análise. Não existe garantia de que será concluído. Pedido: ".$invoiceId."Data: ".$data_hora, true);
		}


		if($status == "1"){
			log_var ("Status [".$status."] ", "INVOICE ID: ".$invoiceId . " VERIFICAR VARIAVEL Sucess:".print_r($success,true)."Data: ".$data_hora, true);
            $statusInvoice = Capsule::table('tblinvoices')->select('status')->where('id', '=', $invoiceId)->first();
            if($statusInvoice->status == "Unpaid"){
			 addInvoicePayment($invoiceId,$transactionId, $paymentAmount,"",$gatewayParams["name"]);
            }
            $status = '0';
		}
?>