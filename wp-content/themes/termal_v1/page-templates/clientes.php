<?php /* Template Name: Clientes */ ?>
<?php if (!isset($_SESSION['user_logged'])) { wp_redirect( get_bloginfo( 'url' ) . '/' ); } ?>
<?php date_default_timezone_set('America/Mexico_City'); ?>
<?php
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
        $clientes = $response_row['data'];
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
                        <ul><a class="selected" href="<?php bloginfo("url"); ?>/dashboard/clientes/"><i class="material-icons va">group</i> Clientes</a></ul>
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
                <div class="row stickySearch">
                    <div class="input-field col s12 l9">
                        <i class="material-icons prefix">search</i>
                        <input id="searchAlumnos" type="text" class="validate">
                        <label for="searchAlumnos">Buscar cliente</label>
                        <input id="admin_id" name="admin_id" type="hidden" value="<?php echo $_SESSION['user']['ID']; ?>" >
                    </div>
                    <div class="input-field col s12 l3 duo">
                        <input type="submit" id="btnsearchAlumnos" name="btnsearchAlumnos" class="btn" value="Buscar" />
                        <input type="submit" id="btnLimpiarAlumnos" name="btnLimpiarAlumnos" class="btn" value="Limpiar" />
                    </div>
                </div>
                <div class="flexing">
                    <div>
                        <p><b>Clientes</b></p>
                    </div>
                    <div class="add">
                        <br>
                        <a href="<?php bloginfo("url"); ?>/dashboard/agregar-cliente/"><p>Alta de cliente <i class="material-icons">add_circle</i></p></a>
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
                                            <th>Nombre</th>
                                            <th>Apellidos</th>
                                            <th>Membresía</th>
                                            <th>Vencimiento</th>
                                            <th>Acciones</th>
                                        </tr>
                                        </thead>

                                            <tbody>
                                                    <?php foreach($clientes as $cliente){ ?>
                                                        <tr>
                                                            <td><?php echo $cliente['ID'] ?></td>
                                                            <td><?php echo $cliente['nombre'] ?></td>
                                                            <td><?php echo $cliente['apellidos'] ?></td>
                                                            <td><?php echo $cliente['id_membresia'] ?></td>
                                                            <td><?php echo $cliente['vencimiento'] ?></td>
                                                            <th>
                                                                <a href="<?php bloginfo("url"); ?>/dashboard/detalles-cliente/?id=<?php echo $cliente['ID']; ?>"><i class="material-icons">info</i></a>
                                                                <a target="_blank" href="<?php bloginfo("url"); ?>/dashboard/credencial-pdf//?id=<?php echo $cliente['ID']; ?>"><i class="material-icons">assignment_ind</i></a>
                                                            </th>
                                                        </tr>
                                                    <?php } ?>
                                            </tbody>
                                    </table>
                                <?php } else { ?>
                                    <p class="centered">Aún no hay clientes.</p>
                                <?php } ?>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>

<?php get_footer(); ?>