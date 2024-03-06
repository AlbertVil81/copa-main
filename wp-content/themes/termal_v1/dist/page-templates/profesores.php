<?php /* Template Name: Profesores */ ?>
<?php if (!isset($_SESSION['user_logged'])) { wp_redirect( get_bloginfo( 'url' ) . '/' ); } ?>
<?php date_default_timezone_set('America/Mexico_City'); ?>
<?php
    //Create JSON Request
    $array = array(
        'msg' => 'getProfesores',
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
        $profesores = $response_row['data'];
        $count = $response_row['count'];
    }
?>

<?php get_header(); ?>
    <div class="dashboard">
        <div class="row dashboard__alumnos">
            <div class="col s12 m12 l2 stickyfix hide-on-med-and-down">
                <div class="menu-left card">
                    <div class="menu-header logo">
                        <i class="material-icons medium">school</i>
                    </div>
                    <div class="links hide-on-med-and-down">
                        <div class="sensor"><ul><a href="<?php bloginfo("url"); ?>/dashboard/"><i class="material-icons va">dashboard</i> Dashboard</a></ul></div>
                        <ul><a href="<?php bloginfo("url"); ?>/dashboard/alumnos/"><i class="material-icons va">group</i> Alumnos</a></ul>
                        <ul><a class="selected" href="<?php bloginfo("url"); ?>/dashboard/profesores/"><i class="material-icons va">assignment_ind</i> Profesores</a></ul>
                        <ul><a href="<?php bloginfo("url"); ?>/dashboard/pagos/"><i class="material-icons va">attach_money</i> Pagos</a></ul>
                        <ul><a href="<?php bloginfo("url"); ?>/dashboard/cursos/"><i class="material-icons va">folder</i> Cursos</a></ul>
                        <ul><a href="<?php bloginfo("url"); ?>/dashboard/clases/" class="tooltipped" data-position="bottom" data-tooltip="Próximamente"><i class="material-icons va">folder_shared</i> Clases</a></ul>
                        <li class="divider"></li>
                        <ul><a href="<?php bloginfo("url"); ?>/dashboard/perfil/"><i class="material-icons va">child_care</i> Mi perfil</a></ul>
                    </div>
                    <div class="space20"></div>
                    <ul class="hide-on-med-and-down"><a href="<?php bloginfo("url"); ?>/logout/"><i class="material-icons va">exit_to_app</i> Cerrar sesión</a></ul>
                    <div class="footer-side">
                        <p>Locker</p>
                        <p>Todos los derechos reservados © 2023.</p>
                    </div>
                </div>
            </div>
            <div class="col s12 m12 l10 p-right">
                <div class="space20"></div>
                <div class="row">
                    <div class="chip right">
                        <img src="<?php echo $_SESSION['user']['imagen']; ?>" alt="Contact Person">
                        <?php echo $_SESSION['user']['escuela'];?>
                    </div>
                </div>
                <div class="row stickySearch">
                    <div class="input-field col s12 l9">
                        <i class="material-icons prefix">search</i>
                        <input id="searchProfesores" type="text" class="validate">
                        <label for="searchProfesores">Buscar profesor</label>
                        <input id="admin_id" name="admin_id" type="hidden" value="<?php echo $_SESSION['user']['ID']; ?>" >
                    </div>
                    <div class="input-field col s12 l3 duo">
                        <input type="submit" id="btnsearchProfesores" name="btnsearchProfesores" class="btn" value="Buscar" />
                        <input type="submit" id="btnLimpiarProfesores" name="btnLimpiarProfesores" class="btn" value="Limpiar" />
                    </div>
                </div>
                <div class="flexing">
                    <div>
                        <p><b>Profesores</b></p>
                    </div>
                    <div class="add">
                        <br>
                        <a href="<?php bloginfo("url"); ?>/dashboard/agregar-profesor/"><p>Agregar Profesor <i class="material-icons">add_circle</i></p></a>
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
                                            <th>Matrícula</th>
                                            <th>Nombre</th>
                                            <th>Apellidos</th>
                                            <th>Domicilio</th>
                                            <th>Teléfono</th>
                                            <th>Email</th>
                                            <th>Cursos</th>
                                            <th>Acciones</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                            <?php foreach($profesores as $profesor){ ?>
                                                <tr>
                                                    <td><?php echo $profesor['ID'] ?></td>
                                                    <td><?php echo $profesor['nombre'] ?></td>
                                                    <td><?php echo $profesor['apellidos'] ?></td>
                                                    <td><?php echo $profesor['domicilio'] ?></td>
                                                    <td><?php echo $profesor['telefono'] ?></td>
                                                    <td><?php echo $profesor['email'] ?></td>
                                                    <td><?php echo $profesor['cursos'] ?></td>
                                                    <th><a href="<?php bloginfo("url"); ?>/dashboard/detalles-profesor/?id=<?php echo $profesor['ID']; ?>"><i class="material-icons">info</i></a></th>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                <?php } else { ?>
                                    <p class="centered">Aún no hay profesores.</p>
                                <?php } ?>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>

<?php get_footer(); ?>