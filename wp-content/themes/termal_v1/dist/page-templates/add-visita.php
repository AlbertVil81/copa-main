<?php /* Template Name: Agregar Visita */ ?>
<?php if (!isset($_SESSION['user_logged'])) { wp_redirect( get_bloginfo( 'url' ) . '/' ); } ?>
<?php date_default_timezone_set('America/Mexico_City'); ?>
<?php
    //Create JSON Request

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
                        <ul><a href="<?php bloginfo("url"); ?>/dashboard/membresias/"><i class="material-icons va">assignment_ind</i> Membresías</a></ul>
                        <ul><a class="selected" href="<?php bloginfo("url"); ?>/dashboard/visitas/"><i class="material-icons va">attach_money</i> Visitas</a></ul>
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
                        <a href="<?php bloginfo("url"); ?>/dashboard/visitas/"><p><i class="material-icons va">arrow_back</i> Volver</p></a>
                    </div>
                    <div class="add">
                        <br>
                        <p><b>Registrar visita</b></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m12 l12">
                        <div class="card menu white">
                            <form id="formAddVisita" name="formAddVisita">
                                <div class="input-field col s3">
                                </div>
                                <div class="input-field col s6">
                                    <input placeholder="" id="id_registrar_visita" name="id_registrar_visita" type="text" class="validate" autofocus>
                                    <label for="id_registrar_visita">ID</label>
                                </div>
                                <div class="input-field col s3">
                                    <?php $fechaActual = date('Y-m-d'); ?>
                                    <input placeholder="Fecha" id="fecha" name="fecha" type="hidden" class="validate" value="<?php echo $fechaActual; ?>" disabled>
                                </div>
                                <br>
                                <div class="cta centered">
                                    <input type="submit" id="btnAddVisita" name="btnAddVisita" class="btn" value="Registrar" />
                                    <input id="admin_id" name="admin_id" type="hidden" value="<?php echo $_SESSION['user']['ID']; ?>" >
                                </div>
                            </form>
                        </div>
                        <div class="card menu white">
                            <div class="results">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php get_footer(); ?>