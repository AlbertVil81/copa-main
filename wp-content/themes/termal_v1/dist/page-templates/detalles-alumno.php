<?php /* Template Name: Detalles Alumno */ ?>
<?php $id = (isset($_GET['id'])) ? (string)trim($_GET['id']) : ''; ?>
<?php if (!isset($_SESSION['user_logged'])) { wp_redirect( get_bloginfo( 'url' ) . '/' ); } ?>
<?php date_default_timezone_set('America/Mexico_City'); ?>
<?php
    //Create JSON Request
    $array = array(
        'msg' => 'getAlumno',
        'fields' => array(
            'alumno_id' => $id
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
        $alumno = $response_row['data'];
    }

    //Create JSON Request
    $array = array(
        'msg' => 'getPagosAlumno',
        'fields' => array(
            'alumno_id' => $id
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
        $pagos = $response_row['data'];
        $countPagos = $response_row['count'];
    }

    //Create JSON Request
    $array = array(
        'msg' => 'getCurso',
        'fields' => array(
            'curso_id' => $alumno[0]['id_curso_numero']
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
        $countCursos = $response_row['count'];
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
                        <p class="numero" id="nombre_alumno" style="margin-right: 12px;"><?php echo $alumno[0]['nombre']; ?></p>
                        <p class="numero" id="apellidos_alumno"><?php echo $alumno[0]['apellidos']; ?></p>
                    </div>
                </div>
                <div class="space20"></div>
                <div class="row">
                    <div class="col s12">
                        <ul class="tabs">
                            <li class="tab col s4"><a class="active" href="#test1">Datos alumno</a></li>
                            <li class="tab col s4"><a href="#test2">Cursos</a></li>
                            <li class="tab col s4"><a href="#test3">Pagos</a></li>
                        </ul>
                    </div>
                    <div id="test1" class="col s12">
                        <div class="row">
                            <div class="col s12 m12 l12">
                                <div class="card menu white">
                                    <form id="formEditAlumno" name="formEditAlumno">
                                        <div class="input-field col s6">
                                            <input placeholder="Nombre" id="nombre" name="nombre" type="text" class="validate" value="<?php echo $alumno[0]['nombre']; ?>">
                                            <label for="nombre">Nombre</label>
                                        </div>
                                        <div class="input-field col s6">
                                            <input placeholder="Apellidos" id="apellidos" name="apellidos" type="text" class="validate" value="<?php echo $alumno[0]['apellidos']; ?>">
                                            <label for="apellidos">Apellidos</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <input placeholder="johndoe@email.com" id="email" name="email" type="email" class="validate" value="<?php echo $alumno[0]['email']; ?>">
                                            <label for="email">Correo Electrónico</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <select class="browser-default" id="curso" name="curso">
                                                <option value="" disabled selected>Selecciona un curso</option>
                                                <?php foreach ($cursos as $curso) { ?>
                                                    <option value="<?php echo $curso['ID'] ?>" <?php if($alumno[0]['id_curso'] == $curso['nombre']){ echo 'selected="selected"';} ?>><?php echo $curso['nombre']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="input-field col s6">
                                            <input placeholder="Contacto de emergencia" id="contacto_emergencia" name="contacto_emergencia" type="text" class="validate" value="<?php echo $alumno[0]['contacto_de_emergencia']; ?>">
                                            <label for="contacto_emergencia">Contacto de emergencia</label>
                                        </div>
                                        <div class="input-field col s6">
                                            <input placeholder="Teléfono" id="telefono" name="telefono" type="text" class="validate" value="<?php echo $alumno[0]['telefono']; ?>">
                                            <label for="telefono">Teléfono</label>
                                        </div>
                                        <div class="input-field col s6">
                                            <input placeholder="CURP" id="CURP" name="CURP" type="text" class="validate" value="<?php echo $alumno[0]['curp']; ?>">
                                            <label for="CURP">CURP</label>
                                        </div>
                                        <div class="input-field col s6">
                                            <input placeholder="Personalidad" id="personalidad" name="personalidad" type="text" class="validate" value="<?php echo $alumno[0]['personalidad']; ?>">
                                            <label for="personalidad">Personalidad</label>
                                        </div>
                                        <div class="input-field col s6">
                                            <input placeholder="Aprendizaje" id="aprendizaje" name="aprendizaje" type="text" class="validate" value="<?php echo $alumno[0]['aprendizaje']; ?>">
                                            <label for="aprendizaje">Aprendizaje</label>
                                        </div>
                                        <div class="input-field col s6">
                                            <?php
                                                $fechaAPI = $alumno[0]['fecha_de_nacimiento'];
                                                $fechaMostrar = strtr($fechaAPI, '/', '-');
                                                $fecha = date("Y-m-d", strtotime($fechaMostrar));
                                            ?>
                                            <input placeholder="Fecha de Nacimiento" id="fecha_nac" name="fecha_nac" type="date" class="validate" value="<?php echo $fecha; ?>">
                                            <label for="fecha_nac">Fecha de Nacimiento</label>
                                        </div>
                                        <div class="input-field col s6">
                                            <?php
                                                $fechaAPI = $alumno[0]['fecha_de_ingreso'];
                                                $fechaMostrar = strtr($fechaAPI, '/', '-');
                                                $fecha = date("Y-m-d", strtotime($fechaMostrar));
                                            ?>
                                            <input placeholder="Fecha de Ingreso" id="fecha_ingreso" name="fecha_ingreso" type="date" class="validate" value="<?php echo $fecha; ?>">
                                            <label for="fecha_ingreso">Fecha de Ingreso</label>
                                        </div>
                                        <!--
                                        <div class="input-field col s6">
                                            <input placeholder="Fecha de Egreso" id="fecha_egreso" name="fecha_egreso" type="date" class="validate">
                                            <label for="fecha_egreso">Fecha de Egreso</label>
                                        </div>
                                        -->
                                        <div class="input-field col s12">
                                            <input placeholder="Dirección" id="direccion" name="direccion" type="text" class="validate" value="<?php echo $alumno[0]['domicilio']; ?>">
                                            <label for="Dirección">Dirección</label>
                                        </div>
                                        <div class="cta right">
                                            <input type="submit" id="btnEditAlumno" name="btnEditAlumno" class="btn" value="Guardar" />
                                            <input id="alumno_id" name="alumno_id" type="hidden" value="<?php echo $alumno[0]['ID']; ?>" >
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="test2" class="col s12">
                        <div class="row">
                            <div class="col s12 m12 l12">
                                <div class="card menu white">
                                    <?php if($countCursos > 0){ ?>
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>ID Curso</th>
                                                        <th>Nombre</th>
                                                        <th>Profesor</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <?php foreach($cursos as $curso){ ?>
                                                        <tr>
                                                            <td><?php echo $curso['ID'] ?></td>
                                                            <td><?php echo $curso['nombre'] ?></td>
                                                            <td><?php echo $curso['id_profesor'] ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                    <?php } else { ?>
                                        <p class="centered">Aún no hay cursos.</p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="test3" class="col s12">
                        <div class="row">
                            <div class="col s12 m12 l12">
                                <div class="card menu white">
                                    <?php if($countPagos > 0){ ?>
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Matrícula</th>
                                                    <th>Concepto</th>
                                                    <th>Cantidad</th>
                                                    <th>Forma de pago</th>
                                                    <th>Alumno o Profesor</th>
                                                    <th>Fecha</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php foreach($pagos as $pago){ ?>
                                                    <tr>
                                                        <td><?php echo $pago['ID'] ?></td>
                                                        <td><?php echo $pago['concepto'] ?></td>
                                                        <td><?php echo $pago['cantidad'] ?></td>
                                                        <td><?php echo $pago['forma_de_pago'] ?></td>
                                                        <td><?php echo $pago['id_alumno_profesor'] ?></td>
                                                        <td><?php echo $pago['fecha'] ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    <?php } else { ?>
                                        <p class="centered">Aún no hay pagos.</p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php get_footer(); ?>