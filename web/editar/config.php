<?php
require_once ('../classes/conexao.php');
//Verifica se as Variáveis existem
if(isset($_POST['t_minimo']) && isset($_POST['t_maximo']))
{
	$minimo = $_POST['t_minimo'];
	$maximo = $_POST['t_maximo'];
	//faz update no banco
	$sql = "UPDATE config SET min = $minimo, max = $maximo WHERE cod = 1";
	if(mysqli_query($con, $sql))
	{
		echo "
		<meta http-equiv=REFRESH content='0;URL=../config.php' />
		<script type='text/javascript'>
				alert('Configurações editadas com sucesso');
		</script>
		";
	}
}
else
{
	echo "
		<meta http-equiv=REFRESH content='0;URL=../config.php' />
		<script type='text/javascript'>
				alert('Erro inesperado');
		</script>
		";
}

?>