<?php
#Bot DogeToLino V1.0 Creado por: Steven Cabrera Londoño (CTO) 
//Zona horaria
date_default_timezone_set('America/Bogota');

$botToken = "816955695:AAF7lSVYdPR_j9Q40hHgA8ixNF06Z1Kdz0Y";
$website = "https://api.telegram.org/bot".$botToken;

$update = file_get_contents('php://input');
$update = json_decode($update,TRUE);

$chatId = $update["message"]["chat"]["id"];
$chatType = $update["message"]["chat"]["type"];
$message = $update["message"]["text"];
$username = $update["message"]["chat"]["first_name"];

//include 'prueba_log.php';

/*
----estilos del texto----

sin html:
*bold text*
_italic text_
[inline URL](http://www.example.com/)
[inline mention of a user](tg://user?id=123456789)
`inline fixed-width code`
```block_language
pre-formatted fixed-width code block
```
*/

//keyboard inicial

$bot_token = $botToken; // Telegram bot token
$chat_id = $chatId; // dont forget about TELEGRAM CHAT ID
$reply = "Actualizando...";
$url = "https://api.telegram.org/bot$bot_token/sendMessage";

$keyboard = array(

"keyboard" => 

	array(

		array(

			array("text" => "/transacciones"),

			array("text" => "/invertir",),

			array("text" => "/saldo",)

		),
		array(

			array("text" => "/"),

			array("text" => "/perfil",),

			array("text" => "/",)

		),
		array(

			array("text" => "/ayuda"),

			array("text" => "/menu",),

			array("text" => "/FAQ",)

		)
	),

"one_time_keyboard" => false, // Can be FALSE (hide keyboard after click)
"resize_keyboard" => true // Can be FALSE (vertical resize)

);


$postfields = array(

	'chat_id' => "$chat_id",
	'text' => "$reply",
	'reply_markup' => json_encode($keyboard)

);

print_r($postfields);

if (!$curld = curl_init()) {exit;}

curl_setopt($curld, CURLOPT_POST, true);
curl_setopt($curld, CURLOPT_POSTFIELDS, $postfields);
curl_setopt($curld, CURLOPT_URL,$url);
curl_setopt($curld, CURLOPT_RETURNTRANSFER, true);

$output = curl_exec($curld);

curl_close ($curld);

// Fin keyboard inicial


//lee opcion de proceso del usuario

include 'conection_direccion.php';

while ($columna = mysqli_fetch_array( $resultado )){

		$paso = $columna['opcion'];

}

include 'close.php';

//dirige al usuario según su elección
/*
opcion 0: Leer el comando que envia el usuario
opcion 1:
opcion 2:
opcion 3:

*/
if ($paso == 0) {

	Leersms ($message, $chatId, $username);

} else {

	$salir = "/cancelar";

	if ($message != $salir) {

		actualizar_dir ($message, $chatId);

	}

	include 'conection_direccion.php';

		$opcion = 0;

		$insertar = "UPDATE telegram SET opcion = '$opcion' WHERE usuariot = '$chatId'";

		mysqli_query($conexion, $insertar);

	include 'close.php';

	$response = "/corregir | /menu";

	sendMessage($chatId, $response);

}


function crear_usuario($chatId, $username){

	include 'conection_direccion.php';

		$usuariot = $chatId;

		$pass = 0;

		$otro = $username;

		$ingreso = date('Y-m-d h:i:s');

		$doge = 0;

		$direccion = 0;

		$opcion = 0;

		$insertar = "INSERT INTO telegram (usuariot, pass, otro, ingreso, doge, direccion, opcion) VALUES ('$usuariot', '$pass', '$otro', '$ingreso', '$doge', '$direccion', '$opcion')";

		mysqli_query($conexion, $insertar);

	include 'close.php';

	$response = "Usuario creado exitosamente";

	sendMessage($chatId, $response);

}


function actualizar_dir ($message, $chatId){

	//ingresamos la dirección de pago en la base de datos

		include 'conection_direccion.php';

			$direccion = $message;

			$insertar = "UPDATE telegram SET direccion = '$direccion' WHERE usuariot = '$chatId'";

			mysqli_query($conexion, $insertar);

		include 'close.php';

		$response = "tu dirección de pago es:";

		sendMessage($chatId, $response);

		//se vuelve a consultar la base actualizada

		include 'conection_direccion.php';

			while ($columna = mysqli_fetch_array( $resultado )){

			 	$response = $columna['direccion'];

			 	sendMessage($chatId, $response);

			}

		include 'close.php';

}


function abrir_DTF ($chatId){


	//aca ingresamos el registro para crear un nuevo DTF

		include 'conection_DTF.php';

		$insertar = "UPDATE DTF SET direccion = '$message' WHERE usuariot = '$chatId'";

		mysqli_query($conexion, $insertar);

		include 'close.php';

		$response = "tu ID de transacción es:";
		sendMessage($chatId, $response);

		include 'conection_DTF.php';

		while ($columna = mysqli_fetch_array( $resultado ))

			{

			 $response = $columna['id'];
			 sendMessage($chatId, $response);

			}

		include 'close.php';

		$response = "con este ID puedes hacer reclamos sobre las consignaciones o depositos";
		sendMessage($chatId, $response);

}

function Leersms ($message, $chatId, $username){

switch ($message) {

	case '/invertir':

		$response = "---------------- 
/miDogecoin
---------------- 
/abrirDTF
---------------- 
/cerrarDTF
---------------- ";

		sendMessage($chatId, $response);

	break;

	case '/saldo':

		include 'conection_direccion.php';
			while ($columna = mysqli_fetch_array( $resultado )){
					$doge = $columna['doge'];
				}
		include 'close.php';

		$response = "Tu saldo es: *".$doge."* DOGE";
		sendMessage($chatId, $response);

	break;


	case '/miDogecoin':

		$response = "Este es el saldo actual de tu inversión:";
		sendMessage($chatId, $response);

		include 'conection_direccion.php';
			while ($columna = mysqli_fetch_array( $resultado ))
				{
					//$response = $columna['doge'];
					$doge = $columna['doge'];
			 		//sendMessage($chatId, $response);
				}
		include 'close.php';

		$response = $doge." DOGECOIN";
		sendMessage($chatId, $response);

		$response = "----------------
*Esta es la dirección que usaremos para enviarte el DOGECOIN cuando finalice el plazo de tu DTF*
----------------
/verDireccion
----------------
/actualizarDireccion
----------------";
		sendMessage($chatId, $response);

	break;

	case '/abrirDTF':

	
		
		$response = "Lo primero que debes hacer es enviar tu inversión a esta dirección:";
		sendMessage($chatId, $response);

		$response = "D6Q8kdXupNx86X3A9u7vDffgHfYRcQsLAJ";
		sendMessage($chatId, $response);

		$response = "una vez realizada la transacción reportarlo en el /canal_de_pagos con el monto en Dolares que enviaste, el hash , con esto ya estaras participando como uno de nuestros inversores. recuerda que tus ganancias seran pagadas a fin de cada mes. te aseguramos la ganancia del 2% Mensual Sobre tu Inversión";
		sendMessage($chatId, $response);

		$response = "Una vez realizado el deposito, copie el Hash de la transacción:";
		sendMessage($chatId, $response);

		break;

	case '/FreeWallet:':

		$response = "Es de las mejores billeteras online que se pueden utilizar en el celular ya tiene costo 0 a la hora de enviar transacciones entre lis mismos usuarios de FreeWallet:";
		sendMessage($chatId, $response);

		$response = "https://freewallet.org/id/dogetolino/doge";
		sendMessage($chatId, $response);

	break;

	case '/corregir':
		$response = "Vuelve a ingresar tu dirección DOGE o tu Correo de /FreeWallet: ";
		sendMessage($chatId, $response);
		include 'conection_direccion.php';
			$opcion = 3;
			$insertar = "UPDATE telegram SET opcion = '$opcion' WHERE usuariot = '$chatId'";
			mysqli_query($conexion, $insertar);


			while ($columna = mysqli_fetch_array( $resultado ))
			{
				$paso = $columna['opcion'];
			}
		include 'close.php';

	break;


	case '/actualizarDireccion':

		$response = "*Recuerda que esta es la Dirección DOGECOIN que usaremos para realizar los depositos de tu inversion*";
		sendMessage($chatId, $response);

		$response = "Ingresa tu dirección DOGE o tu Correo si tienes billetera FreeWallet: ";
		sendMessage($chatId, $response);

		include 'conection_direccion.php';
			$opcion = 3;
			$insertar = "UPDATE telegram SET opcion = '$opcion' WHERE usuariot = '$chatId'";
			mysqli_query($conexion, $insertar);


			while ($columna = mysqli_fetch_array( $resultado ))
			{
				$paso = $columna['opcion'];
			 /*$response = $paso;
			 sendMessage($chatId, $response);*/
			}
		include 'close.php';

		$response = "/cancelar";
		sendMessage($chatId, $response);

		break;

	case '/verDireccionDeposito':

		include 'conection_direccion.php';
		while ($columna = mysqli_fetch_array( $resultado ))
			{
				$cosa = $columna['direccion'];
			 $response = $cosa;
			 sendMessage($chatId, $response);
			}
		include 'close.php';
		$response = "Es tu direccion de pago DOGE registrada en nuestra plataforma";
		sendMessage($chatId, $response);

		$response = "/actualizar";
		sendMessage($chatId, $response);

		break;

	case '/perfil':

		include 'conection_direccion.php';
		while ($columna = mysqli_fetch_array( $resultado ))
			{

			 $response = $columna['usuariot']." | ".$columna['direccion']." | ".$columna['opcion']." | ".$columna['doge'];
			 sendMessage($chatId, $response);
			}
		include 'close.php';

		break;


	case '/fechaingreso':

		include 'conection_direccion.php';
		while ($columna = mysqli_fetch_array( $resultado ))
			{
				$cosa = $columna['ingreso'];
			 $response = date($cosa);
			 sendMessage($chatId, $response);
			}
		include 'close.php';
		$response = "Es el día y la hora en que comenzaste a usar nuestro sistema";
		sendMessage($chatId, $response);

		break;

	case '/start':

		include 'conection_direccion.php';
		while ($columna = mysqli_fetch_array( $resultado ))
			{
				$id = $columna['usuariot'];
			 
			 //sendMessage($chatId, $id);
			}
		include 'close.php';

		if ($id == $chatId) {
			 //sendMessage($chatId, $id);

			$response = "hola ".$username.", ya estabas registrado en nuestro sistema, gracias por volver (:D) estamos muy felices de tenerte de vuelta.";
			sendMessage($chatId, $response);

			$response = "/menu";
			sendMessage($chatId, $response);
		}else{
			//sendMessage($chatId, $id);
			$response = "Hola ".$username." como es primer vez que nos visitas te estamos registrando en nuestro sistema";
			sendMessage($chatId, $response);

			crear_usuario($chatId, $username);

			$response = "/menu";
			sendMessage($chatId, $response);
		}




		break;

	case '/menu':
		$response = "/ayuda - No sé que hacer!!!
		/ayuda1 - como ganan dinero
		/ayuda2 - como se que me pagarán
		/que_es - Un Bot creado en telegram
		/quienes_son - Una Comunidad
		/precio - cuanto vale un Doge
		/apoyar - visitando esta pagina nos apoyas :D
		/invertir - ¿Como puedo invertir en esta iniciativa?
		/retirar - quiero sacar mi inversión
		/act_direccion - establece una dirección de deposito
		/ver_direccion_deposito - mira tu dirección DOGE
		/consigue_doge - aquí puedes conseguir DOGE gratis
		/sya - tu sorpresa: NO ENTRAR";
		sendMessage($chatId, $response);
		break;

	case '/ayuda':
		$response = "Somos los lideres inversores en criptomonedas en Colombia, que buscan el beneficio de la comunidad pensando en la vejez y las personas vulnerables.";
		sendMessage($chatId, $response);
		break;

	case '/ayuda1':
		$response = "Invertimos principalmente en tres fuentes: 1. un bot que vende y compra acciones de criptomonedas a los mejores precios. 2. Mantenemos criptomonedas bloqueadas que generan ingresos pasivos. 3. invertimos en equipo de mineria para mantener la red LINO. /ayuda2";
		sendMessage($chatId, $response);
		break;

	case '/ayuda2':
		$response = "Es más efectivo actuar en comunidad hacer aumentar el capital neto común, el objetivo es: "."
		"."1. reunir los fondos en común respetando el porsentaje de inversión"."
		"."2. invertirlo en las maneras mensionadas en /ayuda1 para lograr el mayor beneficio en común y que sea distribuido de una manera equitativa."."
		"."3. puedes revisar tu capital invertido en el comando /invertir";
		sendMessage($chatId, $response);
		break;

	case '/quees':
		$response = "Un bot mediador que te ayuda a llevar el control de tu acuerdo de inversion con nosotros DOGE TO LINO COMUNNITY";
		sendMessage($chatId, $response);
		break;

	case '/quienesson':
		$response = "Un grupo de personas hispano hablantes que unidos ayudan a mantener la nueva red LINO en funcionamiento y reciben recompensas de ello y las comparten con la comunidad ;)";
		sendMessage($chatId, $response);
		break;

	case '/web':
		$response = "Gracias por ser tan buena persona y querer apoyar a este proyecto, es muy sencillo simplemente visita la siguiente pagina que veras abajo:";
		sendMessage($chatId, $response);
		$response = "https://dogelino.000webhostapp.com/";
		sendMessage($chatId, $response);
		$response = "Gracias por ser tan Genial ;)";
		sendMessage($chatId, $response);
		break;

	case '/sya':
		$response = "Te quiero mi amor";
		sendMessage($chatId, $response);
		break;

	case 'doge gratis':
		$response = "Hay diversas maneras de conseguir dogecoin, una de las mas faciles y seguras es la de ver publicidad a cambio de pequeños pagos en DOGE y esta es una manera real de conseguir capital, si quieres conseguir dogecoin gratis ingresa a DogeClick Bot y que te comiencen a pagar por ver peblublicidad ;)";
		sendMessage($chatId, $response);
		$response = "https://t.me/Dogecoin_click_bot?start=crJg";
		sendMessage($chatId, $response);
		break;

	case '/retirar':
		$response = "A la hora de retirar te contaré como funcionan las cosas, cuando hacemos una inversión en LINO bloqueando 1000 LINO lo mas rapido que podemos desbloquearlo es en 3 meses, entonces como te pagaremos? te pagaremos con las ganancias de los bots de trading que es lo que nos asegura que siempre haya suficiente DOGE para pagarle a todos aquellos que confian en nuestra iniciativa. Bueno y para pagarte y que no nos cueste mucho mas bien que nos cueste 0 DOGE utilizamos FreeWallet, así que te invitamos a que uses FreeWallet para poder realizar los pagos con 0 Fee ;)";
		sendMessage($chatId, $response);

		$response = "https://freewallet.org/id/dogetolino/doge";
		sendMessage($chatId, $response);
		
		break;

	case '/precio':
		$response = "El Precio Actual de Dogecoin (XDG) en Pesos colombianos (COP) es:";
		sendMessage($chatId, $response);
		include 'precio dogecoin.php';
		sendMessage($chatId, $rest1);
		sendMessage($chatId, $rest2);
		sendMessage($chatId, $rest3);
		break;

	case 'Si':
		$response = "vale, busca dentro de mis opciones si no encuentras lo que buscabas contactanos: sevenupsoyo12@yahoo.com siempre respondo ;) Att: Steven SEO";
		sendMessage($chatId, $response);
		break;

	case 'si':
		$response = "vale, busca dentro de mis opciones si no encuentras lo que buscabas contactanos: sevenupsoyo12@yahoo.com siempre respondo ;) Att: Steven SEO";
		sendMessage($chatId, $response);
		break;

	case 'Hola':
		$response = "Hola, espero que estes muy bien :D";
		sendMessage($chatId, $response);
		break;

	case 'Andrea':
		$response = "Es la Mujer más hermosa en mi vida, te quiero mucho mi *Amor*.";
		sendMessage($chatId, $response);
		break;

	case 'Usuarios':

		include 'conection.php';

		$cont = 0;
		while ($columna = mysqli_fetch_array( $resultado ))
			{
				$cont = $cont+1;
				$cosa = $columna['usuariot'];
			 $response = $cosa;
			 sendMessage($chatId, $response);
			}
			include 'close.php';
		$response = "en total de la plataforma somos:";
		sendMessage($chatId, $response);

		$response = $cont;
		sendMessage($chatId, $response);

		$response = "Gracias por estar con nosotros";
		sendMessage($chatId, $response);
		
		break;

	default:
		sendMessage2($chatId);
	break;
	}
}





function sendMessage($chatId, $response){
	
	$url = $GLOBALS['website'].'/sendMessage?chat_id='.$chatId.'&parse_mode=Markdown&text='.urlencode($response);
	file_get_contents($url);
}

function sendMessage2($chatId){

		$response = "*¿Algo más que pueda hacer por ti?*";
		sendMessage($chatId, $response);
}

?>
