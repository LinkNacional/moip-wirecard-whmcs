<?php
    /*
        Gateway
        Desenvolvido por: Davi Souza
        Versao: v1.7
        Companhia: LINK NACIONAL
		Codificação: UTF-8
    */
if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

/**
 * Define module related meta data.
 *
 * Values returned here are used to determine module related capabilities and
 * settings.
 *
 * @see https://developers.whmcs.com/payment-gateways/meta-data-params/
 *
 * @return array
 */
function whmcs_moip_MetaData()
{
    return array(
        'DisplayName' => 'Moip WHMCS',
        'APIVersion' => '1.1', // Use API Version 1.1
        'DisableLocalCredtCardInput' => true,
        'TokenisedStorage' => false,
    );
}

    //$variavel_whmcs_moip = getGatewayVariables("whmcs_moip");

	//$token = $params['token_moip'];
	//$chave = $params['key_moip'];

    function whmcs_moip_config() {
		$key_whmcs_moip = rand(0000,9999);
		//$key_whmcs_salvo =  $variavel_whmcs_moip['key_whmcs'];
		$whmcs_moip_configarray = array(
		 "FriendlyName" => array("Type" => "System", "Value"=>"MOIP Modulo"),
		 "id_carteira" => array("FriendlyName" => "E-Mail*", "Type" => "text", "Size" => "50", "Name" => "id_carteira", "Description" => "E-mail cadastrado com o MoIP"),

		 "usuario_moip" => array(
		 	"FriendlyName" => "Usuario Moip*", 
		 	"Type" => "text", 
		 	"Size" => "50", 
		 	"Name" => "usuario_moip", 
		 	"Description" => "Usuario de login do MoIP"),
		
		 "token_moip" => array(
		 	"FriendlyName" => "Token Moip", 
		 	"Type" => "text", 
		 	"Size" => "80", 
		 	"Name" => "token_moip", 
		 	"Description" => "Informar o Token Moip"),

		 "key_moip" => array(
		 	"FriendlyName" => "Chave Moip", 
		 	"Type" => "text", 
		 	"Size" => "80", 
		 	"Name" => "key_moip", 
		 	"Description" => "Informar a Chave Moip"),


		 'cpfMoip' => array(
            'FriendlyName' => 'CPF',
            'Type' => 'dropdown',
            'Options' =>get_whmcs_moip_customfield_id(),
            'Description' => 'Campo personalizado de CPF'
        ),
        'cnpj' => array(
            'FriendlyName' => 'CNPJ Data',
            'Type' => 'dropdown',
            'Options' =>get_whmcs_moip_customfield_id(),
            'Description' => 'Campo personalizado de CNPJ',
        ),

        'pessoa_tipo' => array(
            'FriendlyName' => 'Tipo pessoa',
            'Type' => 'dropdown',
            'Options' =>get_whmcs_moip_customfield_id(),
            'Description' => 'Campo personalizado de pessoa jurídica',
        ),



		 "url_logo_boleto" => array("FriendlyName" => "Logomarca Boleto", "Type" => "text", "Size" => "50", "Name" => "url_logo_boleto", "Description" => "Informe a URL com http:// Tamanho: 75x40"),
		 "layout" => array("FriendlyName" => "Layout", "Type" => "text", "Size" => "20", "Description" => "Insira o nome do layout criado em sua conta MoIP, caso nao haja essa informacao o layout utilizado sera o defalt (padrao) MoIP.\n<br>Para criar ou alterar um layout acesse sua conta MoIP no menu \"Meus Dados\" >> \" Preferencias \" >> \" Layout personalizado da pagina de pagamento \"."),
		 "logo_boleto" => array("FriendlyName" => "Logo no Boleto", "Type" => "text", "Value" => "https://www.linknacional.com.br/images/logo-linknacional-quadrada.jpg", "Size" => "60", "Description" => "Ex: https://www.linknacional.com.br/images/logo-linknacional-quadrada.jpg"),
		 "instrucao_1" => array("FriendlyName" => "Instruções do Boleto 1", "Type" => "text", "Size" => "50", "Description" => "Mensagem Personalizada no Boleto Linha 1"),
		 "instrucao_2" => array("FriendlyName" => "Instruções do Boleto 2", "Type" => "text", "Size" => "50", "Description" => "Mensagem Personalizada no Boleto Linha 2"),
		 "dias_corridos" => array("FriendlyName" => "Dias para Vencimento", "Type" => "text", "Size" => "50", "Value" => "5", "Description" => "Quantidade de dias corridos para vencimento do boleto."),
		 //"url_botao" => array("FriendlyName" => "Url do botão", "Type" => "text", "Size" => "50", "Description" => "Insira a URL do botão personalizado, coloque http://"),
		 "texto_botao" => array("FriendlyName" => "Texto do botão", "Type" => "text", "Size" => "50", "Description" => "Descrição da imagem ao passar o mouse, Ex: Pagar Agora"),
		 "key_whmcs" => array("FriendlyName" => "Chave de seguranca MoIP*", "Type" => "text", "Size" => "4", "Value" => "$key_whmcs_moip", "Description" => "Chave se Segurança, digite sua chave no campo (essa chave deve ser criada por você aleatoriamente), pegue o chave de seguranca e insira na URL de notificacao no campo abaixo <br>Ex: http://www.seudominio.com/whmcs/modules/gateways/moip/nasp.php?key=SUA_CHAVE"),
		 "url_notificacao" => array("FriendlyName" => "URL de Notificação*", "Type" => "text", "Size" => "50", "Description" => "Insira seu URL de notificação, utilizando a mesma chave acima. <br>Ex: http://www.seudominio.com/whmcs/modules/gateways/moip/nasp.php?key=SUA_CHAVE"),
		 "instrucoes" => array("FriendlyName" => "Instrucoes", "Type" => "yesno", "Description" => "1° - Acesse sua conta MoIP e verifique se a ferramenta de \"Integracao HTML\" esta habilitada. \n<br>Caso nao esteja, clique no link presente em sua conta para ativar a ferramenta. \n<br> 2° - Preencha os capos acima, onde os que possuem * sao de preenchimento obrigatorio. ", )
		);
		return $whmcs_moip_configarray;
	}

    function whmcs_moip_link($params){

    	$erro = false;

		//print_r($params);
		if(date("Y-m-d") < $params['dueDate']){
		   $xmlDueDate = "<DataVencimento>".$params['dueDate']."T04:00:00.0-03:00</DataVencimento>";
	   }else{
		 $xmlDueDate = "<DiasExpiracao Tipo='Corridos'>".$params['dias_corridos']."</DiasExpiracao>";
	   }

	   /// TRATAR DADOS CLIENTES
	   $myclientcustomfields = array();
		foreach($params["clientdetails"]["customfields"] as $key => $value){
			$myclientcustomfields[$value['id']] = $value['value'];
		}

		$tipo_pessoa =   $myclientcustomfields[$params['pessoa_tipo']];
		$cnpj_pessoa =   $myclientcustomfields[$params['cnpj']];
		$cpf_pessoa  =   $myclientcustomfields[$params['cpfMoip']];

		//echo "CPF".$cnpj_pessoa;

	   //$tipo_pessoa = $params["clientdetails"]["customfields"][$params['pessoa_tipo']];// = Pessoa Jurídica, Pessoa Física, Estrangeiro

			//echo "DOC:".$tipo_pessoa;
	   if($tipo_pessoa == "Pessoa Jurídica" && $cnpj_pessoa != ""){
		   	$doc_tipo ="CNPJ";
		   	$doc_numero = $cnpj_pessoa;
		   	$nome = $params['companyname'];
		   	//echo "DOC".$numero_doc.$nome;
		   	//print_r($params);
	   }elseif($tipo_pessoa == "Estrangeiro"){
	   	
	   }else{
		   	$doc_tipo ="CPF";
		   	$doc_numero = $cpf_pessoa;
		   	$nome = $params['clientdetails']['firstname'].' '.$params['clientdetails']['lastname'];
		   	//echo "DOC".$doc_numero;
	   }
	   $phoneTratado = str_replace(" ","",str_replace("-","",$params['clientdetails']['phonenumber']));
	   //echo "phone".$phoneTratado;

	   if(strlen($phoneTratado) <= 9){
	   		/// ERRO TELEFONE
	   		$erro = true;
	   		$ddd = "O seu telefone está incompleto, verifique se inseriu o DDD. ";
	   }

	   ///NUMERO ENDERECO CLIENTE EXEMPLO: Rua Alvares Cabral, 54 obter apenas o 54
	   $numero = explode(",", $params['clientdetails']['address1']);
	   $numero = explode(" ", trim($numero[1]));

	   if($params['clientdetails']['address2'] == ""){
		   $bairro = "Centro";
	   }else{
		 	 $bairro  = $params['clientdetails']['address2'];
	   }


	   $xml="<EnviarInstrucao>
					<InstrucaoUnica>
						<!-- *********** NAO ALTERAR DADOS OBRIGATORIOS *********** -->
						<URLRetorno>".$params['systemurl'].'viewinvoice.php?id='.$params['invoiceid']."</URLRetorno>
						<URLNotificacao>".$params['url_notificacao']."</URLNotificacao>
						<Razao>".$params['description']."</Razao>
						<Valores>
							 <Valor moeda='BRL'>".$params['amount']."</Valor>
						</Valores>
						<IdProprio>".$params['invoiceid'].":".rand(0000,9999)."</IdProprio>
						<PagamentoDireto>
							<Forma>BoletoBancario</Forma>
						</PagamentoDireto>
						<Pagador>
							<Nome>".htmlspecialchars ($nome)."</Nome>
							<LoginMoIP>".$params['usuario_moip']."</LoginMoIP>
							<Email>".$params['clientdetails']['email']."</Email>
							<TelefoneCelular>".trim($phoneTratado)."</TelefoneCelular>
							<Apelido>".$params['clientdetails']['firstname']."</Apelido>
							<Identidade Tipo='".$doc_tipo."'>".$doc_numero."</Identidade>
							<EnderecoCobranca>
								<Logradouro>".$params['clientdetails']['address1']."</Logradouro>
								<Numero>".$numero[0]."</Numero>
								<Complemento></Complemento>
								<Bairro>".$bairro."</Bairro>
								<Cidade>".$params['clientdetails']['city']."</Cidade>
								<Estado>".$params['clientdetails']['state']."</Estado>
								<Pais>".$params['clientdetails']['country']."</Pais>
								<CEP>".trim(str_replace("-","",$params['clientdetails']['postcode']))."</CEP>
								<TelefoneFixo>".trim($phoneTratado)."</TelefoneFixo>
							</EnderecoCobranca>
						</Pagador>
						<!-- *********** DADOS RECOMENDADOS *********** -->
						<Boleto>
							 ".$xmlDueDate."
							 <Instrucao1>".$params['instrucao_1']."</Instrucao1>
							 <Instrucao2>".$params['instrucao_2']."</Instrucao2>
                  			 <URLLogo>".$params['logo_boleto']."</URLLogo>
						</Boleto>
						<Recebedor>
							<LoginMoIP>".$params['usuario_moip']."</LoginMoIP>
							<Apelido>".$params['usuario_moip']."</Apelido>
						</Recebedor>
					</InstrucaoUnica>
				</EnviarInstrucao>";
			$resposta = whmcs_moip_enviaInstrucao($params['token_moip'].":".$params['key_moip'],$xml);
			//echo var_dump($xml);
			//echo var_dump($xmlRetorno);


			// SAND BOX: https://desenvolvedor.moip.com.br/sandbox/Instrucao.do?token=
			if($resposta['erro'] != "" || $erro == true){
				return $ddd. '<a href="'. $params['systemurl'] .'/clientarea.php?action=details">Verifique seus dados</a> ou selecione outra forma de pagamento.';
			}else{

				print_r($xmlRetorno);
				if (extension_loaded('simplexml')) {

    				$xmlRetorno = new SimpleXMLElement($resposta['resposta']);

					return '<a href="https://www.moip.com.br/Instrucao.do?token='.$xmlRetorno->Resposta->Token.' " class="btn btn-danger">'.$params['texto_botao'] .'</a>';

				} else{ echo "Habilite a extensão simplexml no PHP. ";}    
				
			}

    }

	function whmcs_moip_enviaInstrucao($auth,$xml){
		$url = 'https://www.moip.com.br/ws/alpha/EnviarInstrucao/Unica'; //URL SANDBOX: 'https://desenvolvedor.moip.com.br/sandbox/ws/alpha/EnviarInstrucao/Unica';
		$header[] = "Authorization: Basic " . base64_encode($auth);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL,$url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_USERPWD, $auth);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0");
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$ret = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		// Mostrar possíveis erros.
		//var_dump(array('resposta'=>$ret,'erro'=>$err));
		return array('resposta'=>$ret,'erro'=>$err);
 	}


function get_whmcs_moip_customfield_id(){
    $fields = mysql_query("SELECT id, fieldname FROM tblcustomfields WHERE type = 'client';");
    if (!$fields) {
        return array('0' => 'database error');
    }elseif (mysql_num_rows($fields) >= 1) {
        $dropFieldArray = array('0' => 'selecione um campo');
        while ($field = mysql_fetch_assoc($fields)) {
        // the dropdown field type renders a select menu of options
        $dropFieldArray[$field['id']] = $field['fieldname'];
        }
       return $dropFieldArray;
    } else {
        return array('0' => 'nothing to show');
    }
}

    //$GATEWAYMODULE['moipname'] = 'whmcs_moip';
    //$GATEWAYMODULE['moipvisiblename'] = 'whmcs_moip';
    //$GATEWAYMODULE['moiptype'] = 'Invoices';
?>