<?php /* Template Name: Detalles Membresía */ ?>
<?php $id = (isset($_GET['id'])) ? (string)trim($_GET['id']) : ''; ?>
<?php if (!isset($_SESSION['user_logged'])) { wp_redirect( get_bloginfo( 'url' ) . '/' ); } ?>
<?php date_default_timezone_set('America/Mexico_City'); ?>
<?php
    //Create JSON Request
    $array = array(
        'msg' => 'getMembresiaOnly',
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
        $membresia = $response_row['data'];
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
                        <ul><a class="selected" href="<?php bloginfo("url"); ?>/dashboard/membresias/"><i class="material-icons va">assignment_ind</i> Membresías</a></ul>
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
                        <a href="<?php bloginfo("url"); ?>/dashboard/membresias/"><p><i class="material-icons va">arrow_back</i> Volver</p></a>
                    </div>
                    <div class="add">
                        <p class="numero" id="nombre_alumno" style="margin-right: 12px;"><?php echo $membresia[0]['nombre']; ?></p>
                        <p class="numero" id="apellidos_alumno"><?php echo $alumno[0]['apellidos']; ?></p>
                    </div>
                </div>
                <div class="space20"></div>
                <p>Editar membresía</p>
                <div class="row">
                    <div class="col s12 m12 l12">
                        <div class="card menu white">
                            <form id="formEditMembresia" name="formEditMembresia">
                                <div class="input-field col s6">
                                    <input placeholder="Nombre" id="nombre" name="nombre" type="text" class="validate" value="<?php echo $membresia[0]['nombre']; ?>">
                                    <label for="nombre">Nombre</label>
                                </div>
                                <div class="input-field col s6">
                                    <input placeholder="Precio" id="precio" name="precio" type="text" class="validate" value="<?php echo $membresia[0]['precio']; ?>">
                                    <label for="precio">Precio</label>
                                </div>
                                <div class="input-field col s6">
                                    <input placeholder="Duración" id="duracion" name="duracion" type="text" class="validate" value="<?php echo $membresia[0]['duracion']; ?>">
                                    <label for="duracion">Duración</label>
                                </div>
                                <div class="input-field col s6">
                                    <input placeholder="Beneficios" id="beneficios" name="beneficios" type="text" class="validate" value="<?php echo $membresia[0]['beneficios']; ?>">
                                    <label for="nota">Beneficios</label>
                                </div>
                                <div class="cta right">
                                    <input type="submit" id="btnEditMembresia" name="btnEditMembresia" class="btn" value="Guardar" />
                                    <input id="membresia_id" name="membresia_id" type="hidden" value="<?php echo $membresia[0]['ID']; ?>" >
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php get_footer(); ?>