<?php /* Template Name: Perfil */ ?>
<?php if (!isset($_SESSION['user_logged'])) { wp_redirect( get_bloginfo( 'url' ) . '/' ); } ?>
<?php date_default_timezone_set('America/Mexico_City'); ?>
<?php
    //Create JSON Request
    $array = array(
        'msg' => 'getAdmin',
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
        $admin = $response_row['data'];
    }
?>

<?php get_header(); ?>
    <div class="dashboard">
        <div class="row dashboard__hero">
            <div class="col s12 m12 l2 stickyfix hide-on-med-and-down">
                <div class="menu-left card">
                    <div class="menu-header logo">
                        <img src="<?php bloginfo("template_directory"); ?>/assets/img/redline-logo.png" alt="" class="responsive-img sac-login">
                    </div>
                    <div class="links hide-on-med-and-down">
                        <div class="sensor"><ul><a href="<?php bloginfo("url"); ?>/dashboard/"><i class="material-icons va">dashboard</i> Dashboard</a></ul></div>
                        <ul><a href="<?php bloginfo("url"); ?>/dashboard/clientes/"><i class="material-icons va">group</i> Clientes</a></ul>
                        <ul><a href="<?php bloginfo("url"); ?>/dashboard/membresias/"><i class="material-icons va">assignment_ind</i> Membresías</a></ul>
                        <ul><a href="<?php bloginfo("url"); ?>/dashboard/visitas/"><i class="material-icons va">attach_money</i> Visitas</a></ul>
                        <li class="divider"></li>
                        <ul><a class="selected" href="<?php bloginfo("url"); ?>/dashboard/perfil/"><i class="material-icons va">child_care</i> Mi perfil</a></ul>
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
                <div class="row">
                    <div class="col s12 m12 l8">
                        <div class="card menu" style="height: 100%;">
                            <div class="card-header">
                                <p>Mis datos</p>
                            </div>
                            <div class="card-p10">
                                <div class="row">
                                    <div class="col s12 m12 l3 centered">
                                        <br>
                                        <div class="company_logo relative">
                                            <img class="avatar circle avatar-mi-cuenta" data-alt-src="<?php bloginfo("template_directory") ?>/assets/img/cambiar_foto.svg" src="<?php echo $_SESSION['user']['imagen']; ?>">
										    <input type="file" id="avatar_file" name="avatar_file" />
                                            <input type="hidden" id="post_id" name="post_id" value="<?php echo $_SESSION['user']['ID']; ?>" />
                                        </div>
                                    </div>
                                    <div class="col s12 m12 l9">
                                        <form id="formUpdateInstitution" name="formUpdateInstitution">
                                            <div class="row no-mb mright">
                                                <div class="input-field col s6">
                                                    <input id="first_name" name="first_name" type="text" class="validate input-field" value="<?php echo $admin[0]["nombre"]; ?>">
                                                    <label for="first_name">Nombre</label>
                                                </div>
                                                <div class="input-field col s6">
                                                    <input id="last_name" name="last_name" type="text" class="validate input-field" value="<?php echo $admin[0]["apellidos"]; ?>">
                                                    <label for="last_name">Apellidos</label>
                                                </div>
                                            </div>
                                            <div class="row no-mb mright">
                                                <div class="input-field col s12">
                                                    <input id="escuela" name="escuela" type="text" class="validate input-field" value="<?php echo $admin[0]["escuela"]; ?>">
                                                    <label for="escuela">Gimnasio</label>
                                                </div>
                                            </div>
                                            <div class="row no-mb mright">
                                                <div class="input-field col s12 tooltipped" ata-position="bottom" data-tooltip="Para cambiar tu correo electrónico contacta a soporte">
                                                    <input disabled id="email" type="email" class="validate" value="<?php echo $admin[0]["email"]; ?>">
                                                    <label for="email">Correo electrónico</label>
                                                </div>
                                            </div>
                                            <div class="row no-mb mright">
                                                <div class="input-field col s12">
                                                    <input id="password" type="password" class="validate" value="<?php echo $admin[0]["contrasena"]; ?>">
                                                    <label for="password">Contraseña</label>
                                                </div>
                                            </div>
                                            <div class="cta right">
                                                <input type="submit" id="btnUpdateInstitution" name="btnUpdateInstitution" class="btn" value="Actualizar" />
                                                <input id="admin_id" name="admin_id" type="hidden" value="<?php echo $_SESSION['user']['ID']; ?>" >
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
    </div>

<?php get_footer(); ?>