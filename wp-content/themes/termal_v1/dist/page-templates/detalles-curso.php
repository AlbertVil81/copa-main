<?php /* Template Name: Detalles Curso */ ?>
<?php $id = (isset($_GET['id'])) ? (string)trim($_GET['id']) : ''; ?>
<?php if (!isset($_SESSION['user_logged'])) { wp_redirect( get_bloginfo( 'url' ) . '/' ); } ?>
<?php date_default_timezone_set('America/Mexico_City'); ?>
<?php
    //Create JSON Request
    $array = array(
        'msg' => 'getCurso',
        'fields' => array(
            'curso_id' => $id
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
        $curso = $response_row['data'];
    }

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
        //var_dump($profesores);
    }

    //Create JSON Request
    $array = array(
        'msg' => 'getAlumnos',
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
        $alumnos = $response_row['data'];
        //var_dump($alumnos);
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
                        <a href="<?php bloginfo("url"); ?>/dashboard/cursos/"><p><i class="material-icons va">arrow_back</i> Volver</p></a>
                    </div>
                    <div class="add">
                        <p class="numero" id="nombre_alumno" style="margin-right: 12px;"><?php echo $alumno[0]['nombre']; ?></p>
                        <p class="numero" id="apellidos_alumno"><?php echo $alumno[0]['apellidos']; ?></p>
                    </div>
                </div>
                <div class="space20"></div>
                <p>Editar curso</p>
                <div class="row">
                    <div class="col s12 m12 l12">
                        <div class="card menu white">
                            <form id="formEditCurso" name="formEditCurso">
                                <div class="input-field col s6">
                                    <input placeholder="Nombre" id="nombre" name="nombre" type="text" class="validate" value="<?php echo $curso[0]['nombre']; ?>">
                                    <label for="nombre">Nombre</label>
                                </div>
                                <div class="input-field col s6">
                                    <input placeholder="Nota" id="nota" name="nota" type="text" class="validate" value="<?php echo $curso[0]['nota']; ?>">
                                    <label for="nota">Nota</label>
                                </div>
                                <div class="input-field col s12">
                                    <select class="browser-default" id="id_profesor" name="id_profesor">
                                        <option value="" disabled selected>Selecciona un profesor</option>
                                        <?php foreach ($profesores as $profesor) { ?>
                                            <option value="<?php echo $profesor['ID'] ?>" <?php if((int)$curso[0]['id_profesor_detalle'] == $profesor['ID']){ echo 'selected="selected"';} ?>><?php echo $profesor['nombre']." ".$profesor['apellidos']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="cta right">
                                    <input type="submit" id="btnEditCurso" name="btnEditCurso" class="btn" value="Guardar" />
                                    <input id="curso_id" name="curso_id" type="hidden" value="<?php echo $curso[0]['ID']; ?>" >
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php get_footer(); ?>