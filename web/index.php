<?php
require_once ('classes/conexao.php');
$hoje = date('Y-m-d');
$hoje = $hoje . ' 00:00:00';
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

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Links da barra de navegação esquerda -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Links da barra de navegação direita -->
    <ul class="navbar-nav ml-auto">
      
      <!-- Notificações 
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">4</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">4 Notificações</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 novas mensagens
            <span class="float-right text-muted text-sm">3 min</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">Ver todas as notificações</a>
        </div>
      </li> -->
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
      <img src="dist/img/Logo.png" alt="Painel de Controle" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Painel de Controle</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar do painel do usuário -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      </div>

      <!-- Sidebar -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Menu Lateral -->
          <li class="nav-item has-treeview">
            <a href="index.php" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview">
            <a href="historico.php" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Histórico
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview">
            <a href="config.php" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Configuração
              </p>
            </a>
          </li>
      </nav>
      <!-- Fim Menu Lateral -->
    </div>
    <!-- Fim Sidebar -->
  </aside>

  <!-- Wrapper de conteúdo. Contém o conteúdo da página -->
  <div class="content-wrapper">
    <!-- Cabeçalho de conteúdo (cabeçalho da página) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <!-- Fim do cabeçalho -->

    <!-- Conteúdo Principal -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php  
				            $sql = "SELECT data, temp FROM leituras where cod = (select max(cod) from leituras)";
				            $termometros = mysqli_query($con, $sql);
			            	$row = mysqli_fetch_assoc($termometros);
				            echo number_format($row['temp'], 2);
				          ?><sup style="font-size: 20px">ºC</sup></h3>

                <p>Temperatura Atual</p>
              </div>
              <div class="icon">
                <i class="ion ion-thermometer size='64'"></i>
              </div>
              <!--<a href="historico.php" class="small-box-footer">Consulte outras datas <i class="fas fa-arrow-circle-right"></i></a>-->
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
			</div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- Fim do Conteúdo Principal -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Trabalho de Conclusão de Curso de <a href="#">Vitória Rodrigues Leonardo</a>.</strong>
    Todos os direitos reservados.
    <div class="float-right d-none d-sm-inline-block">
      <b>Versão</b> 1.0.0
    </div>
  </footer>

  <!-- Sidebar de controle -->
  <aside class="control-sidebar control-sidebar-dark">
  </aside>
  <!-- Fim Sidebar -->
</div>
<!-- Fim do wrapper -->

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
<!-- App -->
<script src="dist/js/adminlte.js"></script>
<!-- Dashboard demo -->
<script src="dist/js/pages/dashboard.js"></script>
<!-- De demostração -->
<script src="dist/js/demo.js"></script>
</body>
</html>
