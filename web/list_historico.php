<?php
require_once ('classes/conexao.php');
$data1 = $_POST['data1'];
$data2 = $_POST['data2'];
$sql = "SELECT data, temp FROM leituras WHERE data >= '$data1 00:00:00' AND data <= '$data2 23:59:59' ORDER BY data DESC"; 
$resultado = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Sistema de Monitoramento de Temperatura</title>
  <!-- Diga ao navegador para responder à largura da tela -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Fonte incrível -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus do Bbootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <!-- Estilo do tema -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Selecionador de Daterange -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- verão -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">
  <!-- Fonte: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
	<div class="content p-1">
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>Data / Hora</th>
						<th>Temperatura</th>
					</tr>
				</thead>
				<tbody>
				
					<?php
						while($row = mysqli_fetch_assoc($resultado))
						{
							$temp = $row['temp'];
							$data = $row['data'];
							$ano = substr($data,0,4);
							$mes = substr($data,5,2);
							$dia = substr($data,8,2);
							$hora = substr($data,-8);
							echo '<tr><td>' . $dia . '/' . $mes . '/' . $ano . ' ' . $hora . '</td>' . '</td>';
							echo '<td>' . $temp . '°C</td></tr>';
						}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolva o conflito na dica de ferramenta da interface do usuário jQuery com a dica de ferramenta Bootstrap -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
