<?php /* Template Name: Visitas */ ?>
<?php if (!isset($_SESSION['user_logged'])) { wp_redirect( get_bloginfo( 'url' ) . '/' ); } ?>
<?php date_default_timezone_set('America/Mexico_City'); ?>
<?php
    //Create JSON Request
    $array = array(
        'msg' => 'getVisitas',
        'fields' => array(
            'admin_id' => $_SESSION['user']['ID'],
            'fecha_inicio' => date('Y-m-01'),
            'fecha_fin' => date('Y-m-t')
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
        $visitas = $response_row['data'];
        $count = $response_row['count'];
    }
?>

<?php get_header(); ?>
    <div class="dashboard">
        <div class="row dashboard__alumnos">
            <div class="col s12 m12 l2 stickyfix hide-on-med-and-down">
                <div class="menu-left card">
                    <div class="menu-header logo">
                        <img src="<?php bloginfo("template_directory"); ?>/assets/img/redline-logo.png" alt="" class="responsive-img sac-login">
                    </div>
                    <div class="links hide-on-med-and-down">
                        <div class="sensor"><ul><a href="<?php bloginfo("url"); ?>/dashboard/"><i class="material-icons va">dashboard</i> Dashboard</a></ul></div>
                        <ul><a href="<?php bloginfo("url"); ?>/dashboard/clientes/"><i class="material-icons va">group</i> Clientes</a></ul>
                        <ul><a href="<?php bloginfo("url"); ?>/dashboard/membresias/"><i class="material-icons va">assignment_ind</i> Membresías</a></ul>
                        <ul><a class="selected" href="<?php bloginfo("url"); ?>/dashboard/visitas/"><i class="material-icons va">attach_money</i> Visitas</a></ul>
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
                <div class="row stickySearch">
                <div class="input-field col s6 l4">
                        <i class="material-icons prefix">search</i>
                        <input placeholder="Fecha de inicio" id="fecha_inicio" name="fecha_inicio" type="date" class="validate" value="<?php echo date('Y-m-01'); ?>">
                        <label for="apellidos">Fecha inicio</label>
                    </div>
                    <div class="input-field col s6 l4">
                        <input placeholder="Fecha fin" id="fecha_fin" name="fecha_fin" type="date" class="validate" value="<?php echo date('Y-m-t');  ?>">
                        <label for="apellidos">Fecha fin</label>
                    </div>
                    <input id="company_id" name="company_id" type="hidden" value="<?php echo $_SESSION['user']['ID']; ?>" >
                    <div class="input-field col s12 l3 duo">
                        <input type="submit" id="btnsearchVentas" name="btnsearchVentas" class="btn" value="Filtrar" />
                        <input type="submit" id="btnLimpiarVentas" name="btnLimpiarVentas" class="btn" value="Limpiar" />
                    </div>
                </div>
                <div class="flexing">
                    <div>
                        <p><b>Visitas</b></p>
                    </div>
                    <div class="add">
                        <br>
                        <a href="<?php bloginfo("url"); ?>/dashboard/registrar-visita/"><p>Registrar visitas <i class="material-icons">add_circle</i></p></a>
                    </div>
                </div>
                <div class="row">
                        <div class="loader_portal centered">
                            <div class="preloader-wrapper active">
                                <div class="spinner-layer spinner-green-only">
                                    <div class="circle-clipper left">
                                        <div class="circle"></div>
                                    </div>
                                    <div class="gap-patch">
                                        <div class="circle"></div>
                                    </div>
                                    <div class="circle-clipper right">
                                        <div class="circle"></div>
                                    </div>
                                </div>
                                <div class="space20"></div>
                            </div>
                        </div>
                        <div class="col s12 m12 l12">
                            <div class="results">
                                <?php if($count > 0){ ?>
                                    <table>
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Cliente</th>
                                            <th>Membresía</th>
                                            <th>Fecha</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                            <?php foreach($visitas as $visita){ ?>
                                                <tr>
                                                    <td><?php echo $visita['ID'] ?></td>
                                                    <td><?php echo $visita['cliente_id'] ?></td>
                                                    <td><?php echo $visita['membresia_id'] ?></td>
                                                    <td><?php echo $visita['fecha'] ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                <?php } else { ?>
                                    <p class="centered">Aún no hay visitas.</p>
                                <?php } ?>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>

<?php get_footer(); ?>