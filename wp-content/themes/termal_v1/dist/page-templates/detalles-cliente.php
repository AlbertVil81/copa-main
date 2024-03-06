<?php /* Template Name: Detalles Cliente */ ?>
<?php $id = (isset($_GET['id'])) ? (string)trim($_GET['id']) : ''; ?>
<?php if (!isset($_SESSION['user_logged'])) { wp_redirect( get_bloginfo( 'url' ) . '/' ); } ?>
<?php date_default_timezone_set('America/Mexico_City'); ?>
<?php
    //Create JSON Request
    $array = array(
        'msg' => 'getCliente',
        'fields' => array(
            'cliente_id' => $id
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
        $cliente = $response_row['data'];
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
        $membresias = $response_row['data'];
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
                <div class="flexing">
                    <div>
                        <a href="<?php bloginfo("url"); ?>/dashboard/clientes/"><p><i class="material-icons va">arrow_back</i> Volver</p></a>
                    </div>
                    <div class="add">
                        <br>
                        <p class="numero" id="nombre_alumno" style="margin-right: 12px;"><?php echo $cliente[0]['nombre']; ?></p>
                        <p class="numero" id="apellidos_alumno"><?php echo $cliente[0]['apellidos']; ?></p>
                    </div>
                </div>
                <div class="space20"></div>
                <div class="row">
                    <div class="col s12">
                        <ul class="tabs">
                            <li class="tab col s4"><a class="active" href="#test1">Datos cliente</a></li>
                        </ul>
                    </div>
                    <div id="test1" class="col s12">
                        <div class="row">
                            <div class="col s12 m12 l12">
                                <div class="card menu white">
                                    <form id="formEditCliente" name="formEditCliente">
                                        <div class="input-field col s6">
                                            <input placeholder="Nombre" id="nombre" name="nombre" type="text" class="validate" value="<?php echo $cliente[0]['nombre']; ?>">
                                            <label for="nombre">Nombre</label>
                                        </div>
                                        <div class="input-field col s6">
                                            <input placeholder="Apellidos" id="apellidos" name="apellidos" type="text" class="validate" value="<?php echo $cliente[0]['apellidos']; ?>">
                                            <label for="apellidos">Apellidos</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <input placeholder="johndoe@email.com" id="email" name="email" type="email" class="validate" value="<?php echo $cliente[0]['email']; ?>">
                                            <label for="email">Correo Electrónico</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <select class="browser-default" id="membresia" name="membresia">
                                                <option value="" disabled selected>Selecciona una membresía</option>
                                                <?php foreach ($membresias as $membresia) { ?>
                                                    <option value="<?php echo $membresia['ID'] ?>" <?php if((int)$cliente[0]['id_membresia'] == $membresia['ID']){ echo 'selected="selected"';} ?>><?php echo $membresia['nombre']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="input-field col s6">
                                            <?php
                                                $fechaAPI = $cliente[0]['vencimiento'];
                                                $fechaMostrar = strtr($fechaAPI, '/', '-');
                                                $fecha = date("Y-m-d", strtotime($fechaMostrar));
                                            ?>
                                            <input placeholder="Fecha de vencimiento" id="fecha_vencimiento" name="fecha_vencimiento" type="date" class="validate" value="<?php echo $fecha; ?>">
                                            <label for="fecha_vencimiento">Fecha de vencimiento</label>
                                        </div>
                                        <div class="cta right">
                                            <input type="submit" id="btnEditCliente" name="btnAddCliente" class="btn" value="Guardar" />
                                            <input id="cliente_id" name="cliente_id" type="hidden" value="<?php echo $cliente[0]['ID']; ?>" >
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php get_footer(); ?>