<?php /* Template Name: Dashboard */ ?>
<?php if (!isset($_SESSION['user_logged'])) { wp_redirect( get_bloginfo( 'url' ) . '/' ); } ?>
<?php date_default_timezone_set('America/Mexico_City'); ?>
<?php
	require_once(get_template_directory().'/vendor/autoload.php');
    //Create JSON Request
    $array = array(
        'msg' => 'getVisitas',
        'fields' => array(
            'admin_id' => $_SESSION['user']['ID'],
            'fecha_inicio' => date('Y-01-01'),
            'fecha_fin' => date('Y-12-31')
        )
    );
    $json_array = json_encode($array);

    //Request Call
    $response = callAPI($json_array);
    $response_row = json_decode($response, true);

    //Check Request Call Status
    if ((int)$response_row['status'] == 1)
    {
        //Read Jobs
        $profesores = $response_row['count'];
    }else{
        $profesores = 0;
    }

    //Create JSON Request
    $array = array(
        'msg' => 'getClientes',
        'fields' => array(
            'admin_id' => $_SESSION['user']['ID']
        )
    );
    $json_array = json_encode($array);

    //Request Call
    $response = callAPI($json_array);
    $response_row = json_decode($response, true);

    //Check Request Call Status
    if ((int)$response_row['status'] == 1)
    {
        //Read Jobs
        $alumnos = $response_row['count'];
    } else{
        $alumnos = 0;
    }

    //Create JSON Request
    $array = array(
        'msg' => 'getMembresia',
        'fields' => array(
            'admin_id' => $_SESSION['user']['ID']
        )
    );
    $json_array = json_encode($array);

    //Request Call
    $response = callAPI($json_array);
    $response_row = json_decode($response, true);

    //Check Request Call Status
    if ((int)$response_row['status'] == 1)
    {
        //Read Jobs
        $cursos = $response_row['count'];
    }else{
        $cursos = 0;
    }

    //Create JSON Request
    $array = array(
        'msg' => 'monthlySales',
        'fields' => array(
            'admin_id' => $_SESSION['user']['ID']
        )
    );
    $json_array = json_encode($array);

    //Request Call
    $response = callAPI($json_array);
    $response_row = json_decode($response, true);
    
    //Check Request Call Status
    if ((int)$response_row['status'] == 1)
    {
        //Read Jobs
        $monthly_sales = $response_row['data'];
    }

    //Create JSON Request
    $array = array(
        'msg' => 'monthlySalesChartv2',
        'fields' => array(
            'admin_id' => $_SESSION['user']['ID'],
            'year' => '2024'
        )
    );
    $json_array = json_encode($array);

    //Request Call
    $response = callAPI($json_array);
    $response_row = json_decode($response, true);
    
    //Check Request Call Status
    if ((int)$response_row['status'] == 1)
    {
        //Read Jobs
        $monthlySalesChart = $response_row;
    }
?>

<?php get_header(); ?>
    <div class="dashboard">
        <div class="row dashboard__hero">
            <div class="col s12 m12 l2 stickyfix hide-on-med-and-down">
                <div class="menu-left card">
                    <div class="menu-header logo">
                        <img src="<?php bloginfo("template_directory"); ?>/assets/img/redline-logo.png" alt="" class="responsive-img sac-login">
                    </div>
                    <div class="links hide-on-med-and-down">
                        <div class="sensor"><ul><a href="<?php bloginfo("url"); ?>/dashboard/" class="selected"><i class="material-icons va">dashboard</i> Dashboard</a></ul></div>
                        <ul><a href="<?php bloginfo("url"); ?>/dashboard/clientes/"><i class="material-icons va">group</i> Clientes</a></ul>
                        <ul><a href="<?php bloginfo("url"); ?>/dashboard/membresias/"><i class="material-icons va">assignment_ind</i> Membresías</a></ul>
                        <ul><a href="<?php bloginfo("url"); ?>/dashboard/visitas/"><i class="material-icons va">attach_money</i> Visitas</a></ul>
                        <li class="divider"></li>
                        <ul><a href="<?php bloginfo("url"); ?>/dashboard/perfil/"><i class="material-icons va">child_care</i> Mi perfil</a></ul>
                    </div>
                    <div class="space20"></div>
                    <ul class="hide-on-med-and-down"><a href="<?php bloginfo("url"); ?>/logout/"><i class="material-icons va">exit_to_app</i> Cerrar sesión</a></ul>
                    <div class="footer-side">
                        <p>Redline</p>
                        <p>Todos los derechos reservados © 2024.</p>
                    </div>
                </div>
            </div>
            <div class="col s12 m12 l10 p-right">
                <div class="space20"></div>
                <div class="row">
                    <div class="chip right">
                        <img src="<?php echo $_SESSION['user']['imagen']; ?>" alt="Contact Person">
                        <?php echo $_SESSION['user']['gimnasio'];?>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m12 l8">
                        <div class="card menu">
                            <div class="card-header">
                                <p>Bienvenido <?php echo $_SESSION['user']['nombre'];?></p>
                            </div>
                            <div class="card-p10">
                                <?php $hoy = date("F j, Y"); ?>
                                <p><b><?php echo $hoy; ?></b></p>
                                <p>Gracias por elegirnos como tu software para ayudarte a administrar tu gimnasio.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col s12 m12 l4">
                        <div class="card menu">
                            <div class="card-header">
                                <p>Ingresos</p>
                            </div>
                            <div class="card-p10">
                                <p class="centered numero"><?php echo '$'.number_format($monthlySalesChart['totalVentas'],2,'.',','); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m12 l8 offset-l2">
                        <div class="col s6 m6 l4">
                            <a href="<?php bloginfo("url"); ?>/dashboard/clientes/">
                                <div class="card rapido">
                                    <p class="datos"><?php echo $alumnos; ?></p>
                                    <div class="space20"></div>
                                    <p>Clientes</p>
                                </div>
                            </a>
                        </div>
                        <div class="col s6 m6 l4">
                            <a href="<?php bloginfo("url"); ?>/dashboard/visitas/">
                                <div class="card rapido">
                                    <p class="datos"><?php echo $profesores; ?></p>
                                    <div class="space20"></div>
                                    <p>Visitas</p>
                                </div>
                            </a>
                        </div>
                        <div class="col s6 m6 l4">
                            <a href="<?php bloginfo("url"); ?>/dashboard/membresias/">
                                <div class="card rapido">
                                    <p class="datos"><?php echo $cursos; ?></p>
                                    <div class="space20"></div>
                                    <p>Membresías</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m12 l6">
                        <div class="card menu" style="height: auto !important;">
                            <table class="highlight">
                                <thead>
                                    <tr>
                                        <th>Mes</th>
                                        <th>Número de ventas</th>
                                        <th>Importe de ventas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Enero</td>
                                        <td><?php echo $monthlySalesChart['contEnero']; ?></td>
                                        <td><?php echo '$'.number_format($monthlySalesChart['totalVentasEnero'],2,'.',','); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Febrero</td>
                                        <td><?php echo $monthlySalesChart['contFebrero']; ?></td>
                                        <td><?php echo '$'.number_format($monthlySalesChart['totalVentasFebrero'],2,'.',','); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Marzo</td>
                                        <td><?php echo $monthlySalesChart['contMarzo']; ?></td>
                                        <td><?php echo '$'.number_format($monthlySalesChart['totalVentasMarzo'],2,'.',','); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Abril</td>
                                        <td><?php echo $monthlySalesChart['contAbril']; ?></td>
                                        <td><?php echo '$'.number_format($monthlySalesChart['totalVentasAbril'],2,'.',','); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Mayo</td>
                                        <td><?php echo $monthlySalesChart['contMayo']; ?></td>
                                        <td><?php echo '$'.number_format($monthlySalesChart['totalVentasMayo'],2,'.',','); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Junio</td>
                                        <td><?php echo $monthlySalesChart['contJunio']; ?></td>
                                        <td><?php echo '$'.number_format($monthlySalesChart['totalVentasJunio'],2,'.',','); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Julio</td>
                                        <td><?php echo $monthlySalesChart['contJulio']; ?></td>
                                        <td><?php echo '$'.number_format($monthlySalesChart['totalVentasJulio'],2,'.',','); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Agosto</td>
                                        <td><?php echo $monthlySalesChart['contAgosto']; ?></td>
                                        <td><?php echo '$'.number_format($monthlySalesChart['totalVentasAgosto'],2,'.',','); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Septiembre</td>
                                        <td><?php echo $monthlySalesChart['contSeptiembre']; ?></td>
                                        <td><?php echo '$'.number_format($monthlySalesChart['totalVentasSeptiembre'],2,'.',','); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Octubre</td>
                                        <td><?php echo $monthlySalesChart['contOctubre']; ?></td>
                                        <td><?php echo '$'.number_format($monthlySalesChart['totalVentasOctubre'],2,'.',','); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Noviembre</td>
                                        <td><?php echo $monthlySalesChart['contNoviembre']; ?></td>
                                        <td><?php echo '$'.number_format($monthlySalesChart['totalVentasNoviembre'],2,'.',','); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Diciembre</td>
                                        <td><?php echo $monthlySalesChart['contDiciembre']; ?></td>
                                        <td><?php echo '$'.number_format($monthlySalesChart['totalVentasDiciembre'],2,'.',','); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col s12 m12 l6">
                        <div class="card menu">
                            <div class="card-header">
                                <p><b>Contáctanos</b></p>
                            </div>
                            <div class="card-p10 centered">
                                <div class="space20"></div>
                                <a href="https://www.facebook.com/sacposmx" target="_blank"><img src="<?php bloginfo("template_directory"); ?>/assets/img/fb.png" class="responsive-img"></a>
                                <a href="https://www.instagram.com/sacposmx/" target="_blank"><img src="<?php bloginfo("template_directory"); ?>/assets/img/insta.png" class="responsive-img"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php get_footer(); ?>