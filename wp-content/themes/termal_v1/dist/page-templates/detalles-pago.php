<?php /* Template Name: Detalles Pago */ ?>
<?php $id = (isset($_GET['id'])) ? (string)trim($_GET['id']) : ''; ?>
<?php if (!isset($_SESSION['user_logged'])) { wp_redirect( get_bloginfo( 'url' ) . '/' ); } ?>
<?php date_default_timezone_set('America/Mexico_City'); ?>
<?php
    //Create JSON Request
    $array = array(
        'msg' => 'getPago',
        'fields' => array(
            'pago_id' => $id
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
        $pago = $response_row['data'];
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
                        <ul><a class="selected" href="<?php bloginfo("url"); ?>/dashboard/pagos/"><i class="material-icons va">attach_money</i> Pagos</a></ul>
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
                        <a href="<?php bloginfo("url"); ?>/dashboard/pagos/"><p><i class="material-icons va">arrow_back</i> Volver</p></a>
                    </div>
                    <div class="add">
                        <br>
                        <p class="numero" id="nombre_alumno" style="margin-right: 12px;"><?php echo $alumno[0]['nombre']; ?></p>
                        <p class="numero" id="apellidos_alumno"><?php echo $alumno[0]['apellidos']; ?></p>
                    </div>
                </div>
                <div class="space20"></div>
                <p>Editar pago</p>
                <div class="row">
                    <div class="col s12 m12 l12">
                        <div class="card menu white">
                            <form id="formEditPago" name="formEditPago">
                                <div class="input-field col s12">
                                    <input placeholder="Concepto" id="concepto" name="concepto" type="text" class="validate" value="<?php echo $pago[0]['concepto']; ?>">
                                    <label for="concepto">Concepto</label>
                                </div>
                                <div class="input-field col s6">
                                    <input placeholder="Cantidad" id="cantidad" name="cantidad" type="number" class="validate" value="<?php echo $pago[0]['cantidad']; ?>">
                                    <label for="cantidad">Cantidad</label>
                                </div>
                                <div class="input-field col s6">
                                    <?php
                                        $fechaAPI = $pago[0]['fecha'];
                                        $fechaMostrar = strtr($fechaAPI, '/', '-');
                                        $fecha = date("Y-m-d", strtotime($fechaMostrar));
                                    ?>
                                    <input placeholder="Fecha" id="fecha" name="fecha" type="date" class="validate" value="<?php echo $fecha; ?>">
                                    <label for="fecha">Fecha</label>
                                </div>
                                <div class="input-field col s12">
                                    <select class="browser-default" id="forma_de_pago" name="forma_de_pago">
                                        <option value="" disabled selected>Selecciona una forma de pago</option>
                                        <option <?php if($pago[0]['forma_de_pago'] == "Efectivo"){ echo 'selected="selected"';} ?> value="Efectivo">Efectivo</option>
                                        <option <?php if($pago[0]['forma_de_pago'] == "Transferencia"){ echo 'selected="selected"';} ?>value="Transferencia">Transferencia</option>
                                    </select>
                                </div>
                                <div class="input-field col s12">
                                    <select class="browser-default" id="id_alumno_profesor" name="id_alumno_profesor">
                                        <option value="" disabled selected>Selecciona un alumno o profesor</option>
                                        <optgroup label="Profesores">
                                            <?php foreach ($profesores as $profesor) { ?>
                                                <option value="<?php echo $profesor['ID'] ?>" <?php if((int)$pago[0]['id_alumno_profesor'] == $profesor['ID']){ echo 'selected="selected"';} ?>><?php echo $profesor['nombre']." ".$profesor['apellidos']; ?></option>
                                            <?php } ?>
                                        </optgroup>
                                        <optgroup label="Alumnos">
                                            <?php foreach ($alumnos as $alumno) { ?>
                                                <option value="<?php echo $alumno['ID'] ?>" <?php if((int)$pago[0]['id_alumno_profesor'] == $alumno['ID']){ echo 'selected="selected"';} ?>><?php echo $alumno['nombre']." ".$alumno['apellidos']; ?></option>
                                            <?php } ?>
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="cta right">
                                    <input type="submit" id="btnEditPago" name="btnEditPago" class="btn" value="Editar" />
                                    <input id="post_id" name="post_id" type="hidden" value="<?php echo $pago[0]['ID']; ?>" >
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php get_footer(); ?>