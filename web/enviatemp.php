<?php
require_once ('classes/conexao.php');
//Verifica se a variável temp existe
if(isset($_GET['temp']))
{
	$temp = $_GET['temp'];
	// se a temperatura estiver -127 o sensor não está conectado ou com defeito
	if($temp != -127)
	{
		// insere no banco a temperatura
		$sql = "INSERT INTO leituras (temp) VALUES ($temp)";
		mysqli_query($con, $sql);
		
		// verifica se a temperatura está fora do configurado
		$sql = "SELECT min, max FROM config WHERE cod = 1";
		$resultado = mysqli_query($con, $sql);
		$row = mysqli_fetch_assoc($resultado);
		$min = $row['min'];
		$max = $row['max'];
		if($temp < $min || $temp > $max)
			echo 'sim'; // retorna sim para a placa
		else
			echo 'nao'; // retorna nao para a placa
	}
	else
		echo 'erro';    // retorna erro para a placa
}
?>