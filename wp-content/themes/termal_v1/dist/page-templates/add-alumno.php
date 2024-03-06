<?php /* Template Name: Agregar Alumno */ ?>
<?php if (!isset($_SESSION['user_logged'])) { wp_redirect( get_bloginfo( 'url' ) . '/' ); } ?>
<?php date_default_timezone_set('America/Mexico_City'); ?>
<?php
    //Create JSON Request
    $array = array(
        'msg' => 'getCursos',
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
        $cursos = $response_row['data'];
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
                        <ul><a class="selected" href="<?php bloginfo("url"); ?>/dashboard/alumnos/"><i class="material-icons va">group</i> Alumnos</a></ul>
                        <ul><a href="<?php bloginfo("url"); ?>/dashboard/profesores/"><i class="material-icons va">assignment_ind</i> Profesores</a></ul>
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
                <div class="flexing">
                    <div>
                        <a href="<?php bloginfo("url"); ?>/dashboard/alumnos/"><p><i class="material-icons va">arrow_back</i> Volver</p></a>
                    </div>
                    <div class="add">
                        <br>
                        <p><b>Agregar Alumno</b></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m12 l12">
                        <div class="card menu white">
                            <form id="formAddAlumno" name="formAddAlumno">
                                <div class="input-field col s6">
                                    <input placeholder="Nombre" id="nombre" name="nombre" type="text" class="validate">
                                    <label for="nombre">Nombre</label>
                                </div>
                                <div class="input-field col s6">
                                    <input placeholder="Apellidos" id="apellidos" name="apellidos" type="text" class="validate">
                                    <label for="apellidos">Apellidos</label>
                                </div>
                                <div class="input-field col s12">
                                    <input placeholder="johndoe@email.com" id="email" name="email" type="email" class="validate">
                                    <label for="email">Correo Electrónico</label>
                                </div>
                                <div class="input-field col s12">
                                    <select class="browser-default" id="curso" name="curso">
                                        <option value="" disabled selected>Selecciona un curso</option>
                                        <?php foreach ($cursos as $curso) { ?>
                                            <option value="<?php echo $curso['ID'] ?>"><?php echo $curso['nombre']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="input-field col s6">
                                    <input placeholder="Contacto de emergencia" id="contacto_emergencia" name="contacto_emergencia" type="text" class="validate">
                                    <label for="contacto_emergencia">Contacto de emergencia</label>
                                </div>
                                <div class="input-field col s6">
                                    <input placeholder="Teléfono" id="telefono" name="telefono" type="text" class="validate">
                                    <label for="telefono">Teléfono</label>
                                </div>
                                <div class="input-field col s6">
                                    <input placeholder="CURP" id="CURP" name="CURP" type="text" class="validate">
                                    <label for="CURP">CURP</label>
                                </div>
                                <div class="input-field col s6">
                                    <input placeholder="Personalidad" id="personalidad" name="personalidad" type="text" class="validate">
                                    <label for="personalidad">Personalidad</label>
                                </div>
                                <div class="input-field col s6">
                                    <input placeholder="Aprendizaje" id="aprendizaje" name="aprendizaje" type="text" class="validate">
                                    <label for="aprendizaje">Aprendizaje</label>
                                </div>
                                <div class="input-field col s6">
                                    <input placeholder="Fecha de Nacimiento" id="fecha_nac" name="fecha_nac" type="date" class="validate">
                                    <label for="fecha_nac">Fecha de Nacimiento</label>
                                </div>
                                <div class="input-field col s6">
                                    <input placeholder="Fecha de Ingreso" id="fecha_ingreso" name="fecha_ingreso" type="date" class="validate">
                                    <label for="fecha_ingreso">Fecha de Ingreso</label>
                                </div>
                                <!--
                                <div class="input-field col s6">
                                    <input placeholder="Fecha de Egreso" id="fecha_egreso" name="fecha_egreso" type="date" class="validate">
                                    <label for="fecha_egreso">Fecha de Egreso</label>
                                </div>
                                -->
                                <div class="input-field col s12">
                                    <input placeholder="Dirección" id="direccion" name="direccion" type="text" class="validate">
                                    <label for="Dirección">Dirección</label>
                                </div>
                                <div class="cta right">
                                    <input type="submit" id="btnAddAlumno" name="btnAddAlumno" class="btn" value="Guardar" />
                                    <input id="admin_id" name="admin_id" type="hidden" value="<?php echo $_SESSION['user']['ID']; ?>" >
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php get_footer(); ?>