<?php /* Template Name: Grupo */ ?>
<?php $id = (isset($_GET['id'])) ? (string)trim($_GET['id']) : ''; ?>
<?php if (!isset($_SESSION['user_logged'])) { wp_redirect( get_bloginfo( 'url' ) . '/' ); } ?>
<?php date_default_timezone_set('America/Mexico_City'); ?>
<?php
    //Create JSON Request
    $array = array(
        'msg' => 'getAlumnosGrupo',
        'fields' => array(
            'admin_id' => $_SESSION['user']['ID'],
            'id_curso' => $id

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
        $alumnos = $response_row['data'];
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
                        <ul><a href="<?php bloginfo("url"); ?>/dashboard/profesores/"><i class="material-icons va">assignment_ind</i> Profesores</a></ul>
                        <ul><a href="<?php bloginfo("url"); ?>/dashboard/pagos/"><i class="material-icons va">attach_money</i> Pagos</a></ul>
                        <ul><a class="selected" href="<?php bloginfo("url"); ?>/dashboard/cursos/"><i class="material-icons va">folder</i> Cursos</a></ul>
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
                <div class="flexing">
                    <div>
                        <a href="<?php bloginfo("url"); ?>/dashboard/profesores/"><p><i class="material-icons va">arrow_back</i> Volver</p></a>
                    </div>
                    <div class="add">
                        <p><b>Curso: <?php foreach($alumnos as $alumno){ echo $alumno["id_curso"]; break;} ?></b></p>
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
                                            <th>Curso</th>
                                            <th>Acciones</th>
                                        </tr>
                                        </thead>

                                            <tbody>
                                                    <?php foreach($alumnos as $alumno){ ?>
                                                        <tr>
                                                            <td><?php echo $alumno['ID'] ?></td>
                                                            <td><?php echo $alumno['nombre'] ?></td>
                                                            <td><?php echo $alumno['apellidos'] ?></td>
                                                            <td><?php echo $alumno['id_curso'] ?></td>
                                                            <th><a href="<?php bloginfo("url"); ?>/dashboard/detalles-alumno/?id=<?php echo $alumno['ID']; ?>"><i class="material-icons">info</i></a></th>
                                                        </tr>
                                                    <?php } ?>
                                            </tbody>
                                    </table>
                                <?php } else { ?>
                                    <p class="centered">Aún no hay alumnos.</p>
                                <?php } ?>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>

<?php get_footer(); ?>