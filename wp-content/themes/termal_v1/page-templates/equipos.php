<?php /* Template Name: Equipos */ ?>
<?php date_default_timezone_set('America/Mexico_City'); ?>

<?php get_header(); ?>

<div class="container dashboard">
    <div class="row dashboard__hero">
        <div class="row">
            <div class="col s12 m12 l12">
                <div class="space30"></div>
                <h4 class="centered">Equipos participantes</h4>
                <div class="space30"></div>
                <ul class="collapsible">
                    <li>
                        <div class="collapsible-header"><i class="material-icons">people</i>Equipo 1</div>
                        <div class="collapsible-body">
                            <table class="striped">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Fecha de nacimiento</th>
                                        <th>Foto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Alberto Pérez</td>
                                        <td>28/01/1994</td>
                                        <td><img class="responsive-img jugador" src="<?php bloginfo("template_directory"); ?>/assets/img/jose-logo.png"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </li>
                    <li>
                        <div class="collapsible-header"><i class="material-icons">people</i>Equipo 2</div>
                        <div class="collapsible-body"><span>Lorem ipsum dolor sit amet.</span></div>
                    </li>
                    <li>
                        <div class="collapsible-header"><i class="material-icons">people</i>Equipo 3</div>
                        <div class="collapsible-body"><span>Lorem ipsum dolor sit amet.</span></div>
                    </li>
                    <li>
                        <div class="collapsible-header"><i class="material-icons">people</i>Equipo 4</div>
                        <div class="collapsible-body"><span>Lorem ipsum dolor sit amet.</span></div>
                    </li>
                    <li>
                        <div class="collapsible-header"><i class="material-icons">people</i>Equipo 5</div>
                        <div class="collapsible-body"><span>Lorem ipsum dolor sit amet.</span></div>
                    </li>
                    <li>
                        <div class="collapsible-header"><i class="material-icons">people</i>Equipo 6</div>
                        <div class="collapsible-body"><span>Lorem ipsum dolor sit amet.</span></div>
                    </li>
                    <li>
                        <div class="collapsible-header"><i class="material-icons">people</i>Equipo 7</div>
                        <div class="collapsible-body"><span>Lorem ipsum dolor sit amet.</span></div>
                    </li>
                    <li>
                        <div class="collapsible-header"><i class="material-icons">people</i>Equipo 8</div>
                        <div class="collapsible-body"><span>Lorem ipsum dolor sit amet.</span></div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
 </div>

<?php get_footer(); ?>