<!DOCTYPE html>
<html>
    <head>
        <?php $bootstrap = "//{$_SERVER['HTTP_HOST']}/app/bootstrap"; ?>
        <?php echo $this->Html->charset(); ?>
        <title>BRITACAL</title>

	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <link rel="stylesheet" type="text/css" href="<?php echo $bootstrap; ?>/assets/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $bootstrap; ?>/assets/css/bootstrap-responsive.min.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $bootstrap; ?>/assets/css/chosen.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $bootstrap; ?>/assets/css/font-awesome.min.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $bootstrap; ?>/assets/css/ace.min.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $bootstrap; ?>/assets/css/ace-responsive.min.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $bootstrap; ?>/assets/css/ace-skins.min.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $bootstrap; ?>/assets/css/jquery.gritter.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $bootstrap; ?>/assets/css/datepicker.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $bootstrap; ?>/assets/css/bootstrap-timepicker.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $bootstrap; ?>/assets/css/daterangepicker.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $bootstrap; ?>/assets/css/colorpicker.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $bootstrap; ?>/assets/css/jquery-ui-1.10.3.custom.min.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $bootstrap; ?>/assets/css/colorbox.css" />

        <link rel="stylesheet" type="text/css" href="<?php echo $bootstrap; ?>/assets/js/DataTables/media/css/jquery.dataTables.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $bootstrap; ?>/assets/js/DataTables/extensions/ColVis/css/dataTables.colVis.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $bootstrap; ?>/assets/js/DataTables/extensions/TableTools/css/dataTables.tableTools.css" />
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.16/sorting/intl.js" />


        <script type="text/javascript" src="<?php echo $bootstrap; ?>/assets/js/jquery-2.0.3.min.js"></script>

        <script type="text/javascript" src="<?php echo $bootstrap; ?>/assets/js/ace-elements.min.js"></script>
        <script type="text/javascript" src="<?php echo $bootstrap; ?>/assets/js/ace.min.js"></script>

        <script type="text/javascript" src="<?php echo $bootstrap; ?>/assets/js/jquery-ui-1.10.3.custom.min.js"></script>
        <script type="text/javascript" src="<?php echo $bootstrap; ?>/assets/js/chosen.jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo $bootstrap; ?>/assets/js/jquery.gritter.min.js"></script>
        <script type="text/javascript" src="<?php echo $bootstrap; ?>/assets/js/date-time/bootstrap-datepicker.min.js"></script>
        <script type="text/javascript" src="<?php echo $bootstrap; ?>/assets/js/date-time/bootstrap-timepicker.min.js"></script>
        <script type="text/javascript" src="<?php echo $bootstrap; ?>/assets/js/bootstrap-colorpicker.min.js"></script>
        <script type="text/javascript" src="<?php echo $bootstrap; ?>/assets/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo $bootstrap; ?>/assets/js/bootbox.min.js"></script>

        <script type="text/javascript" src="<?php echo $bootstrap; ?>/assets/js/fuelux/fuelux.spinner.min.js"></script>
        <script type="text/javascript" src="<?php echo $bootstrap; ?>/assets/js/date-time/moment.min.js"></script>
        <script type="text/javascript" src="<?php echo $bootstrap; ?>/assets/js/date-time/daterangepicker.min.js"></script>
        <script type="text/javascript" src="<?php echo $bootstrap; ?>/assets/js/jquery.autosize-min.js"></script>
        <script type="text/javascript" src="<?php echo $bootstrap; ?>/assets/js/jquery.inputlimiter.1.3.1.min.js"></script>
        <script type="text/javascript" src="<?php echo $bootstrap; ?>/assets/js/jquery.maskedinput.min.js"></script>
        <script type="text/javascript" src="<?php echo $bootstrap; ?>/assets/js/jquery.colorbox-min.js"></script>
        <script type="text/javascript" src="<?php echo $bootstrap; ?>/assets/js/chosen.jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo $bootstrap; ?>/assets/js/jquery.price_format.2.0.min.js"></script>
        <script type="text/javascript" src="<?php echo $bootstrap; ?>/assets/js/DataTables/media/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="<?php echo $bootstrap; ?>/assets/js/DataTables/jquery.dataTables.columnFilter.js"></script>
        <script type="text/javascript" src="<?php echo $bootstrap; ?>/assets/js/DataTables/extensions/ColVis/js/dataTables.colVis.js"></script>
        <script type="text/javascript" src="<?php echo $bootstrap; ?>/assets/js/DataTables/extensions/TableTools/js/dataTables.tableTools.js"></script>
        <script type="text/javascript" src="<?php echo $bootstrap; ?>/assets/js/jquery.dataTables.bootstrap.js"></script>

        <?php
            echo $this->Html->css( array( 'style' ) );
            echo $this->Html->script( array( 'script' ) );
        ?>
        <script type="text/javascript">
            $(function() {
                $('.sair').click(function() {
                    bootbox.confirm("Sair do sistema?", function(result) {
                        if(result) {
                            location.href = '<?php echo $this->Html->url(array('controller' => 'usuarios', 'action' => 'logout'), true); ?>';
                        }
                    });
                });

                <?php
                    $msg = $this->Session->flash();
                    if ($msg) echo "bootbox.alert('{$msg}');";

                    $msg = $this->Session->flash('error');
                    if ($msg) echo "bootbox.alert('{$msg}');";
                ?>
            });
        </script>
    </head>

    <?php if ($this->Session->read('Auth.User.id')): ?>
    <body>
        <div class="navbar">
            <div class="navbar-inner">
                <div class="container-fluid header-color-green2">
                  <a href="#" class="brand">
        						<small>
        							<!-- <i class="icon-home home-icon"></i>
        							ALMOXARIFADO -->

                      <?php //echo $this->Html->image('logobritacal3.png', array('class' => 'logo')); ?>
        						</small>
        					</a>

                    <?php echo $this->Html->image('logobritacal3.png', array('class' => 'logo')); ?>

                    <ul class="nav ace-nav pull-right">
                        <li class="grey">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <i class="icon-tasks"></i>
                                <span class="badge badge-grey" id="alertNum"></span>
                            </a>

                            <ul class="pull-right dropdown-navbar dropdown-menu dropdown-caret dropdown-closer" id="alertList">
                                <li class="nav-header"> <i class="icon-ok"></i> <span id="alertTile">Nenhum evento</span> </li>
                            </ul>
                        </li>

                        <li class="light-red red">
                            <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                                    <?php echo $this->Html->image('avatar.png', array('class' => 'nav-user-photo')); ?>
                                <span class="user-info">
                                    <small>Ol&aacute;, <?php echo $this->Session->read('Auth.User.nome'); ?></small>
                                    <small><?php echo $this->Session->read('Perfil.nome'); ?></small>
                                </span>
                                <i class="icon-caret-down"></i>
                            </a>

                            <ul class="user-menu pull-right dropdown-menu dropdown-default dropdown-caret dropdown-closer">
                                <li class="alterar"><?php echo $this->Html->link('<i class="icon-user"></i> Alterar senha', array('controller' => 'usuarios', 'action' => 'alterarSenha'), array('escape'=>false)); ?></li>
                                <li class="divider"></li>
                                <li> <a class="sair"> <i class="icon-off"></i> Sair </a> </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="main-container container-fluid">
            <a class="menu-toggler" id="menu-toggler" href="#">
                <span class="menu-text"></span>
            </a>

            <div class="sidebar" id="sidebar">
<!--                <div style="height: 30px; padding: 10px 0 0 5px;" >
                    <small>&Uacute;ltimo acesso <?php //echo date("d/m/Y H:i", strtotime($this->Session->read('Auth.User.ultimoAcesso'))); ?></small>
                </div>-->

                <ul class="nav nav-list">
                    <?php
                        if ($this->Session->read('Menu')):
                            foreach ($this->Session->read('Menu') as $menu):
                                if(isset($menu['submenu'])):
                                    echo "<li class='" . ( $this->params['controller'] == $menu['params']['controller'] ? 'active open' : '') . "' >";
                                        echo "<a href='#' class='dropdown-toggle'> <i class='{$menu['icon']}'></i>{$menu['name']} <b class='arrow icon-angle-down'></b></a>";
                                        echo "<ul class='submenu'>";
                                            foreach ($menu['submenu'] as $submenu):
                                                echo "<li class='" . ( $this->params['action'] == $submenu['params']['action'] ? 'active' : '') . "'>". $this->Html->link("<i class='icon-double-angle-right'></i> {$submenu['name']}", array_merge($menu['params'], $submenu['params']), array('escape'=>false)) ."</li>";
                                            endforeach;
                                        echo "</ul>";
                                    echo "</li>";
                                else:
                                    if ($menu['view']):
                                        echo "<li class='" . ( $this->params['controller'] == $menu['params']['controller'] ? 'active' : '') . "' >" . $this->Html->link("<i class='{$menu['icon']}'></i>" . ($menu['name']), $menu['params'], array('escape'=>false)) . "</li>";
                                    endif;
                                endif;

                            endforeach;
                        endif;
                    ?>
                </ul>
            </div>

            <div class="main-content">
                <div class="breadcrumbs" id="breadcrumbs">
                    <ul class="breadcrumb">
                        <li> <i class="icon-home home-icon"></i> ALMOXARIFADO </li>
                    </ul>
                </div>

                <div class="page-content">
                        <?php echo $this->fetch('content'); ?>
                </div>
            </div>
        </div>
    </body>
    <?php else: ?>
    <body class="login-layout">
        <div class="main-container container-fluid">
            <div class="main-content">
                <div class="row-fluid">
                    <div class="span12">
                            <?php echo $this->fetch('content'); ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <?php endif; ?>
</html>
