jQuery(document).ready(function($) {
    // Auto Init
    M.AutoInit();

    //Read Base URL
    var base_url = $('#base_url').val();

    //Calcular fechas membresias

    function convertDate(date) {
        var yyyy = date.getFullYear().toString();
        var mm = (date.getMonth()+1).toString();
        var dd  = date.getDate().toString();
      
        var mmChars = mm.split('');
        var ddChars = dd.split('');
      
        return yyyy + '-' + (mmChars[1]?mm:"0"+mmChars[0]) + '-' + (ddChars[1]?dd:"0"+ddChars[0]);
    }

    $('#membresia').on('change', function() {
        console.log("cambio membresia");
        var membresia_id = $('#membresia').val();
        console.log(membresia_id);

        var param = '{"msg": "getMembresiaOnly","fields": {"membresia_id": "' + membresia_id + '"}}';

        function sumarDias(fecha, dias){
            fecha.setDate(fecha.getDate() + dias);
            return fecha;
        }

        //Request API
        $.post(base_url + '/api', { param: param }).done(function( data ) {

            //Check Result
            if (data.status == 1)
            {
                var d = new Date();
                if(data.data[0].duracion == 1){
                    var dateVencimiento = d;
                } else {
                    var dateVencimiento = sumarDias(d, parseInt(data.data[0].duracion));
                }
                $('#fecha_vencimiento').val(convertDate(dateVencimiento));
            }
            else
            {
                //Show Error
                M.toast({html: data.msg});
            }
        });

    });

    //searchAlumnos click
    $('#btnsearchAlumnos').on('click', function(e) {
        $(this).trigger("enterKey");
        var keyword = $('#searchAlumnos').val();
        searchAlumnos();
    });

    //searchAlumnos click
    $('#btnLimpiarAlumnos').on('click', function(e) {
        limpiarAlumnos();
    });

    //Function to Search
    function searchAlumnos() 
    {
        var keyword = $('#searchAlumnos').val();
        var admin_id = $('#admin_id').val();

        var param = '{"msg": "misAlumnosSearch","fields": {"admin_id": "' + admin_id + '", "keyword": "' + keyword + '"}}';

        $('.results').fadeOut(0);
        $('.loader_portal').fadeIn('slow');

        console.log(param);

        //Request API
        $.post(base_url + '/api', { param: param }).done(function( data ) {

            //Check Result
            if (data.status == 1)
            {
                $('.results').html(data.html);
                $('.loader_portal').fadeOut(0);
                $('.results').fadeIn('slow');
                if (data.count=='0') { M.toast({html: 'No hay resultados.'}); }
            }
            else
            {
                //Show Error
                M.toast({html: data.msg});
            }
        });
    }

    //Function to Search Limpiar
    function limpiarAlumnos() 
    {
        var admin_id = $('#admin_id').val();
        $("#searchAlumnos").val("");

        var keyword = "";
        var param = '{"msg": "misAlumnosSearch","fields": {"admin_id": "' + admin_id + '", "keyword": "' + keyword + '"}}';

        $('.results').fadeOut(0);
        $('.loader_portal').fadeIn('slow');

        //Request API
        $.post(base_url + '/api', { param: param }).done(function( data ) {

            //Check Result
            if (data.status == 1)
            {
                $('.results').html(data.html);
                $('.loader_portal').fadeOut(0);
                $('.results').fadeIn('slow');
                if (data.count=='0') { M.toast({html: 'No hay resultados.'}); }
            }
            else
            {
                //Show Error
                M.toast({html: data.msg});
            }
        });
    }

    //searchProfesores click
    $('#btnsearchProfesores').on('click', function(e) {
        $(this).trigger("enterKey");
        var keyword = $('#searchProfesores').val();
        searchProfesores();
    });

    //searchProfesores click
    $('#btnLimpiarProfesores').on('click', function(e) {
        limpiarProfesores();
    });

    //Function to Search
    function searchProfesores() 
    {
        var keyword = $('#searchProfesores').val();
        var admin_id = $('#admin_id').val();

        var param = '{"msg": "misProfesoresSearch","fields": {"admin_id": "' + admin_id + '", "keyword": "' + keyword + '"}}';

        $('.results').fadeOut(0);
        $('.loader_portal').fadeIn('slow');

        console.log(param);

        //Request API
        $.post(base_url + '/api', { param: param }).done(function( data ) {

            //Check Result
            if (data.status == 1)
            {
                $('.results').html(data.html);
                $('.loader_portal').fadeOut(0);
                $('.results').fadeIn('slow');
                if (data.count=='0') { M.toast({html: 'No hay resultados.'}); }
            }
            else
            {
                //Show Error
                M.toast({html: data.msg});
            }
        });
    }

    //Function to Search Limpiar
    function limpiarProfesores() 
    {
        var admin_id = $('#admin_id').val();
        $("#searchProfesores").val("");

        var keyword = "";
        var param = '{"msg": "misProfesoresSearch","fields": {"admin_id": "' + admin_id + '", "keyword": "' + keyword + '"}}';

        $('.results').fadeOut(0);
        $('.loader_portal').fadeIn('slow');

        //Request API
        $.post(base_url + '/api', { param: param }).done(function( data ) {

            //Check Result
            if (data.status == 1)
            {
                $('.results').html(data.html);
                $('.loader_portal').fadeOut(0);
                $('.results').fadeIn('slow');
                if (data.count=='0') { M.toast({html: 'No hay resultados.'}); }
            }
            else
            {
                //Show Error
                M.toast({html: data.msg});
            }
        });
    }

    //searchVentas click
    $('#btnsearchVentas').on('click', function(e) {
        $(this).trigger("enterKey");
        var fecha_inicio = $('#fecha_inicio').val();
        var fecha_fin = $('#fecha_fin').val();
        searchVentas();
    });

    //searchInventario click
    $('#btnLimpiarVentas').on('click', function(e) {
        limpiarVentas();
    });

    //Function to Search Ventas
    function searchVentas() 
    {
        var fecha_inicio = $('#fecha_inicio').val();
        var fecha_fin = $('#fecha_fin').val();
        var company_id = $('#company_id').val();

        var param = '{"msg": "ventasSearch","fields": {"company_id": "' + company_id + '", "fecha_inicio": "' + fecha_inicio + '", "fecha_fin": "' + fecha_fin + '"}}';
        $('.results').fadeOut(0);
        $('.loader_portal').fadeIn('slow');

        //Request API
        $.post(base_url + '/api', { param: param }).done(function( data ) {

            //Check Result
            if (data.status == 1)
            {
                $('.results').html(data.html);
                $('.loader_portal').fadeOut(0);
                $('.results').fadeIn('slow');
                if (data.count=='0') { M.toast({html: 'No hay resultados.'}); }
            }
            else
            {
                //Show Error
                M.toast({html: data.msg});
            }
        });
    }

    //Function to Search Ventas
    function limpiarVentas() 
    {
        var fecha_inicio = "";
        var fecha_fin = "";
        var company_id = $('#company_id').val();

        var param = '{"msg": "ventasSearch","fields": {"company_id": "' + company_id + '", "fecha_inicio": "' + fecha_inicio + '", "fecha_fin": "' + fecha_fin + '"}}';
        $('.results').fadeOut(0);
        $('.loader_portal').fadeIn('slow');

        //Request API
        $.post(base_url + '/api', { param: param }).done(function( data ) {

            //Check Result
            if (data.status == 1)
            {
                $('.results').html(data.html);
                $('.loader_portal').fadeOut(0);
                $('.results').fadeIn('slow');
                if (data.count=='0') { M.toast({html: 'No hay resultados.'}); }
            }
            else
            {
                //Show Error
                M.toast({html: data.msg});
            }
        });
    }

    //searchPagos click
    $('#btnsearchPagos').on('click', function(e) {
        $(this).trigger("enterKey");
        var keyword = $('#searchPagos').val();
        searchPagos();
    });

    //searchPagos click
    $('#btnLimpiarPagos').on('click', function(e) {
        limpiarPagos();
    });

    //Function to Search
    function searchPagos() 
    {
        var keyword = $('#searchPagos').val();
        var admin_id = $('#admin_id').val();

        var param = '{"msg": "misPagosSearch","fields": {"admin_id": "' + admin_id + '", "keyword": "' + keyword + '"}}';

        $('.results').fadeOut(0);
        $('.loader_portal').fadeIn('slow');

        console.log(param);

        //Request API
        $.post(base_url + '/api', { param: param }).done(function( data ) {

            //Check Result
            if (data.status == 1)
            {
                $('.results').html(data.html);
                $('.loader_portal').fadeOut(0);
                $('.results').fadeIn('slow');
                if (data.count=='0') { M.toast({html: 'No hay resultados.'}); }
            }
            else
            {
                //Show Error
                M.toast({html: data.msg});
            }
        });
    }

    //Function to Search Limpiar
    function limpiarPagos() 
    {
        var admin_id = $('#admin_id').val();
        $("#searchPagos").val("");

        var keyword = "";
        var param = '{"msg": "misPagosSearch","fields": {"admin_id": "' + admin_id + '", "keyword": "' + keyword + '"}}';

        $('.results').fadeOut(0);
        $('.loader_portal').fadeIn('slow');

        //Request API
        $.post(base_url + '/api', { param: param }).done(function( data ) {

            //Check Result
            if (data.status == 1)
            {
                $('.results').html(data.html);
                $('.loader_portal').fadeOut(0);
                $('.results').fadeIn('slow');
                if (data.count=='0') { M.toast({html: 'No hay resultados.'}); }
            }
            else
            {
                //Show Error
                M.toast({html: data.msg});
            }
        });
    }

    //searchCursos click
    $('#btnsearchCursos').on('click', function(e) {
        $(this).trigger("enterKey");
        var keyword = $('#searchCursos').val();
        searchCursos();
    });

    //searchCursos click
    $('#btnLimpiarCursos').on('click', function(e) {
        limpiarCursos();
    });

    //Function to Search
    function searchCursos() 
    {
        var keyword = $('#searchCursos').val();
        var admin_id = $('#admin_id').val();

        var param = '{"msg": "misCursosSearch","fields": {"admin_id": "' + admin_id + '", "keyword": "' + keyword + '"}}';

        $('.results').fadeOut(0);
        $('.loader_portal').fadeIn('slow');

        console.log(param);

        //Request API
        $.post(base_url + '/api', { param: param }).done(function( data ) {

            //Check Result
            if (data.status == 1)
            {
                $('.results').html(data.html);
                $('.loader_portal').fadeOut(0);
                $('.results').fadeIn('slow');
                if (data.count=='0') { M.toast({html: 'No hay resultados.'}); }
            }
            else
            {
                //Show Error
                M.toast({html: data.msg});
            }
        });
    }

    //Function to Search Limpiar
    function limpiarCursos() 
    {
        var admin_id = $('#admin_id').val();
        $("#searchCursos").val("");

        var keyword = "";
        var param = '{"msg": "misCursosSearch","fields": {"admin_id": "' + admin_id + '", "keyword": "' + keyword + '"}}';

        $('.results').fadeOut(0);
        $('.loader_portal').fadeIn('slow');

        //Request API
        $.post(base_url + '/api', { param: param }).done(function( data ) {

            //Check Result
            if (data.status == 1)
            {
                $('.results').html(data.html);
                $('.loader_portal').fadeOut(0);
                $('.results').fadeIn('slow');
                if (data.count=='0') { M.toast({html: 'No hay resultados.'}); }
            }
            else
            {
                //Show Error
                M.toast({html: data.msg});
            }
        });
    }

    if( $('.login').length ){
        //formTalentLogin validate
        $('#formLogin').validate({
           lang: 'en',
           rules: {
               email: {
                   required: true
               },
               password: {
                   required: true
               }
           },
           errorPlacement: function(error,element) {
               return true;
           },
           errorClass : 'error'
       });
       //formTalentSignIn click
       $('.cta #btnLogin').on('click', function(e) {
           e.preventDefault();

           if ($('#formLogin').valid())
           {
               //Leemos los Valores
               var email = $('#email').val();
               var password = $('#password').val();
               var param = '{"msg": "login","fields": {"email": "' + email + '", "password": "' + password + '"}}';

               $('.cta #btnLogin').prop( 'disabled', true );
               $('.cta #btnLogin').val('Iniciando...');

               //Request API
               $.post(base_url + '/api', { param: param }).done(function( data ) {
                   
                   //Check Result
                   if (data.status == 1)
                   {
                       //Show Success
                       window.location.href = base_url + "/dashboard/";
                   }
                   else
                   {
                       //Show Error
                       M.toast({html: data.msg});
                   }
                   
                   //Enable Button
                   $('.cta #btnLogin').prop( 'disabled', false );
                   $('.cta #btnLogin').val('Iniciar Sesión');
               });
           }
           return false;
       });
    }

    if( $('.dashboard').length ){

        //formAddCliente validate
        $('#formAddCliente').validate({
            lang: 'en',
            rules: {
                nombre: {
                    required: true
                },
                apellidos: {
                    required: true
                },
                email: {
                    required: true
                },
                membresia: {
                    required: true
                },
                fecha_vencimiento: {
                    required: true
                },
            },
            errorPlacement: function(error,element) {
                return true;
            },
            errorClass : 'error'
        });

        //formTalentSignIn click
        $('.cta #btnAddCliente').on('click', function(e) {
            e.preventDefault();

            if ($('#formAddCliente').valid())
            {
                //Leemos los Valores
                var nombre = $('#nombre').val();
                var apellidos = $('#apellidos').val();
                var email = $('#email').val();
                var membresia = $('#membresia').val();
                var fecha_vencimiento = $('#fecha_vencimiento').val();
                var admin_id = $('#admin_id').val();

                var param = '{"msg": "createCliente","fields": {"nombre": "' + nombre + '", "apellidos": "' + apellidos + '", "email": "' + email + '", "membresia": "' + membresia + '", "fecha_vencimiento": "' + fecha_vencimiento + '", "admin_id": "' + admin_id + '" }}';
                console.log(param);
                $('.cta #btnAddCliente').prop( 'disabled', true );
                $('.cta #btnAddCliente').val('Procesando...');

                //Request API
                $.post(base_url + '/api', { param: param }).done(function( data ) {
                    
                    //Check Result
                    if (data.status == 1)
                    {
                        //Show Success
                        window.location.href = base_url + "/dashboard/clientes/";
                    }
                    else
                    {
                        //Show Error
                        M.toast({html: data.msg});
                    }
                    
                    //Enable Button
                    $('.cta #btnAddCliente').prop( 'disabled', false );
                    $('.cta #btnAddCliente').val('Guardar');
                });
            }
            return false;
        });

        //formAddMembresia validate
        $('#formAddMembresia').validate({
            lang: 'en',
            rules: {
                nombre: {
                    required: true
                },
                precio: {
                    required: true
                },
                duracion: {
                    required: true
                },
                beneficios: {
                    required: true
                },
            },
            errorPlacement: function(error,element) {
                return true;
            },
            errorClass : 'error'
        });

        //btnAddMembresia click
        $('.cta #btnAddMembresia').on('click', function(e) {
            e.preventDefault();

            if ($('#formAddMembresia').valid())
            {
                //Leemos los Valores
                var nombre = $('#nombre').val();
                var precio = $('#precio').val();
                var duracion = $('#duracion').val();
                var beneficios = $('#beneficios').val();
                var admin_id = $('#admin_id').val();

                var param = '{"msg": "createMembresia","fields": {"nombre": "' + nombre + '","precio": "' + precio + '","duracion": "' + duracion + '","beneficios": "' + beneficios + '","admin_id": "' + admin_id + '"}}';

                $('.cta #btnAddMembresia').prop( 'disabled', true );
                $('.cta #btnAddMembresia').val('Procesando...');

                //Request API
                $.post(base_url + '/api', { param: param }).done(function( data ) {
                    
                    //Check Result
                    if (data.status == 1)
                    {
                        //Show Success
                        window.location.href = base_url + "/membresias/";
                    }
                    else
                    {
                        //Show Error
                        M.toast({html: data.msg});
                    }
                    
                    //Enable Button
                    $('.cta #btnAddMembresia').prop( 'disabled', false );
                    $('.cta #btnAddMembresia').val('Guardar');
                });
            }
            return false;
        });

        //formAddProfesor validate
        $('#formAddProfesor').validate({
            lang: 'en',
            rules: {
                nombre: {
                    required: true
                },
                apellidos: {
                    required: true
                },
                email: {
                    required: true
                },
                telefono: {
                    required: true
                },
                direccion: {
                    required: true
                },
            },
            errorPlacement: function(error,element) {
                return true;
            },
            errorClass : 'error'
        });

        //formTalentSignIn click
        $('.cta #btnAddProfesor').on('click', function(e) {
            e.preventDefault();

            if ($('#formAddProfesor').valid())
            {
                //Leemos los Valores
                var nombre = $('#nombre').val();
                var apellidos = $('#apellidos').val();
                var email = $('#email').val();
                var telefono = $('#telefono').val();
                var direccion = $('#direccion').val();
                var admin_id = $('#admin_id').val();

                var param = '{"msg": "createProfesor","fields": {"nombre": "' + nombre + '","apellidos": "' + apellidos + '","email": "' + email + '","direccion": "' + direccion + '","telefono": "' + telefono + '","admin_id": "' + admin_id + '"}}';

                $('.cta #btnAddProfesor').prop( 'disabled', true );
                $('.cta #btnAddProfesor').val('Procesando...');

                //Request API
                $.post(base_url + '/api', { param: param }).done(function( data ) {
                    
                    //Check Result
                    if (data.status == 1)
                    {
                        //Show Success
                        window.location.href = base_url + "/dashboard/profesores/";
                    }
                    else
                    {
                        //Show Error
                        M.toast({html: data.msg});
                    }
                    
                    //Enable Button
                    $('.cta #btnAddProfesor').prop( 'disabled', false );
                    $('.cta #btnAddProfesor').val('Guardar');
                });
            }
            return false;
        });

        //formAddPago validate
        $('#formAddPago').validate({
            lang: 'en',
            rules: {
                concepto: {
                    required: true
                },
                cantidad: {
                    required: true
                },
                fecha: {
                    required: true
                },
                id_alumno_profesor: {
                    required: true
                },
                forma_de_pago: {
                    required: true
                },
            },
            errorPlacement: function(error,element) {
                return true;
            },
            errorClass : 'error'
        });

        //formTalentSignIn click
        $('.cta #btnAddPago').on('click', function(e) {
            e.preventDefault();

            if ($('#formAddPago').valid())
            {
                //Leemos los Valores
                var concepto = $('#concepto').val();
                var cantidad = $('#cantidad').val();
                var fecha = $('#fecha').val();
                var id_alumno_profesor = $('#id_alumno_profesor').val();
                var forma_de_pago = $('#forma_de_pago').val();
                var admin_id = $('#admin_id').val();

                var param = '{"msg": "createPago","fields": {"concepto": "' + concepto + '","cantidad": "' + cantidad + '","fecha": "' + fecha + '","id_alumno_profesor": "' + id_alumno_profesor + '","forma_de_pago": "' + forma_de_pago + '","admin_id": "' + admin_id + '"}}';

                $('.cta #btnAddPago').prop( 'disabled', true );
                $('.cta #btnAddPago').val('Procesando...');

                //Request API
                $.post(base_url + '/api', { param: param }).done(function( data ) {
                    
                    //Check Result
                    if (data.status == 1)
                    {
                        //Show Success
                        window.location.href = base_url + "/dashboard/pagos/";
                    }
                    else
                    {
                        //Show Error
                        M.toast({html: data.msg});
                    }
                    
                    //Enable Button
                    $('.cta #btnAddPago').prop( 'disabled', false );
                    $('.cta #btnAddPago').val('Guardar');
                });
            }
            return false;
        });

        //formEditAlumno validate
        $('#formEditCliente').validate({
            lang: 'en',
            rules: {
                nombre: {
                    required: true
                },
                apellidos: {
                    required: true
                },
                email: {
                    required: true
                },
                membresia: {
                    required: true
                },
                fecha_vencimiento: {
                    required: true
                },
            },
            errorPlacement: function(error,element) {
                return true;
            },
            errorClass : 'error'
        });

        //formTalentSignIn click
        $('.cta #btnEditCliente').on('click', function(e) {
            e.preventDefault();

            if ($('#formEditCliente').valid())
            {
                //Leemos los Valores
                var nombre = $('#nombre').val();
                var apellidos = $('#apellidos').val();
                var email = $('#email').val();
                var membresia = $('#membresia').val();
                var fecha_vencimiento = $('#fecha_vencimiento').val();
                var cliente_id = $('#cliente_id').val();

                var param = '{"msg": "editCliente","fields": {"nombre": "' + nombre + '", "apellidos": "' + apellidos + '", "email": "' + email + '", "membresia": "' + membresia + '", "fecha_vencimiento": "' + fecha_vencimiento + '", "cliente_id": "' + cliente_id + '" }}';
                console.log(param);

                $('.cta #btnEditCliente').prop( 'disabled', true );
                $('.cta #btnEditCliente').val('Procesando...');

                //Request API
                $.post(base_url + '/api', { param: param }).done(function( data ) {
                    
                    //Check Result
                    if (data.status == 1)
                    {
                        //Show Success
                        //window.location.href = base_url + "/alumnos/";
                        M.toast({html: data.msg});
                    }
                    else
                    {
                        //Show Error
                        M.toast({html: data.msg});
                    }
                    
                    //Enable Button
                    $('.cta #btnEditCliente').prop( 'disabled', false );
                    $('.cta #btnEditCliente').val('Guardar');
                });
            }
            return false;
        });

        //formEditProfesor validate
        $('#formEditProfesor').validate({
            lang: 'en',
            rules: {
                nombre: {
                    required: true
                },
                apellidos: {
                    required: true
                },
                email: {
                    required: true
                },
                telefono: {
                    required: true
                },
                direccion: {
                    required: true
                },
            },
            errorPlacement: function(error,element) {
                return true;
            },
            errorClass : 'error'
        });

        //formTalentSignIn click
        $('.cta #btnEditProfesor').on('click', function(e) {
            e.preventDefault();

            if ($('#formEditProfesor').valid())
            {
                //Leemos los Valores
                var nombre = $('#nombre').val();
                var apellidos = $('#apellidos').val();
                var email = $('#email').val();
                var telefono = $('#telefono').val();
                var direccion = $('#direccion').val();
                var profesor_id = $('#profesor_id').val();

                var param = '{"msg": "editProfesor","fields": {"nombre": "' + nombre + '","apellidos": "' + apellidos + '","email": "' + email + '","direccion": "' + direccion + '","telefono": "' + telefono + '","profesor_id": "' + profesor_id + '"}}';
                console.log(param);
                $('.cta #btnEditProfesor').prop( 'disabled', true );
                $('.cta #btnEditProfesor').val('Procesando...');

                //Request API
                $.post(base_url + '/api', { param: param }).done(function( data ) {
                    
                    //Check Result
                    if (data.status == 1)
                    {
                        //Show Success
                        //window.location.href = base_url + "/profesores/";
                        M.toast({html: data.msg});
                    }
                    else
                    {
                        //Show Error
                        M.toast({html: data.msg});
                    }
                    
                    //Enable Button
                    $('.cta #btnEditProfesor').prop( 'disabled', false );
                    $('.cta #btnEditProfesor').val('Guardar');
                });
            }
            return false;
        });

        //formAddVisita validate
        $('#formAddVisita').validate({
            lang: 'en',
            rules: {
                id_registrar_visita: {
                    required: true
                },
            },
            errorPlacement: function(error,element) {
                return true;
            },
            errorClass : 'error'
        });

        //formTalentSignIn click
        $('.cta #btnAddVisita').on('click', function(e) {
            e.preventDefault();

            if ($('#formAddVisita').valid())
            {
                //Leemos los Valores
                var id_registrar_visita = $('#id_registrar_visita').val();
                var fecha = $('#fecha').val();
                var admin_id = $('#admin_id').val();

                var param = '{"msg": "addVisita","fields": {"id_registrar_visita": "' + id_registrar_visita + '","admin_id": "' + admin_id + '", "fecha": "' + fecha + '"}}';
                console.log(param);

                $('.cta #btnAddVisita').prop( 'disabled', true );
                $('.cta #btnAddVisita').val('Procesando...');

                //Request API
                $.post(base_url + '/api', { param: param }).done(function( data ) {
                    
                    //Check Result
                    if (data.status == 1)
                    {
                        //Show Success
                        //window.location.href = base_url + "/dashboard/pagos/";
                        M.toast({html: data.msg});
                        $('.results').html(data.html);
                        setTimeout(function() {
                            location.reload();
                        },4000);
                        //setTimeout($('.results').html(""), 10000);
                    }
                    else
                    {
                        //Show Error
                        M.toast({html: data.msg});
                        $('.cta #btnAddVisita').prop( 'disabled', false );
                        $('#id_registrar_visita').val("");
                    }
                    
                    //Enable Button
                    //$('.cta #btnAddVisita').prop( 'disabled', false );
                    $('.cta #btnAddVisita').val('Registrar');
                });
            }
            return false;
        });

        //formEditMembresia validate
        $('#formEditMembresia').validate({
            lang: 'en',
            rules: {
                nombre: {
                    required: true
                },
                precio: {
                    required: true
                },
                duracion: {
                    required: true
                },
                beneficios: {
                    required: true
                },
            },
            errorPlacement: function(error,element) {
                return true;
            },
            errorClass : 'error'
        });

        //formTalentSignIn click
        $('.cta #btnEditMembresia').on('click', function(e) {
            e.preventDefault();

            if ($('#formEditMembresia').valid())
            {
                //Leemos los Valores
                var nombre = $('#nombre').val();
                var precio = $('#precio').val();
                var duracion = $('#duracion').val();
                var beneficios = $('#beneficios').val();
                var membresia_id = $('#membresia_id').val();

                var param = '{"msg": "editMembresia","fields": {"nombre": "' + nombre + '","precio": "' + precio + '","duracion": "' + duracion + '","beneficios": "' + beneficios + '","membresia_id": "' + membresia_id + '"}}';
                console.log(param);
                $('.cta #btnEditMembresia').prop( 'disabled', true );
                $('.cta #btnEditMembresia').val('Procesando...');

                //Request API
                $.post(base_url + '/api', { param: param }).done(function( data ) {
                    
                    //Check Result
                    if (data.status == 1)
                    {
                        //Show Success
                        //window.location.href = base_url + "/dashboard/pagos/";
                        M.toast({html: data.msg});
                    }
                    else
                    {
                        //Show Error
                        M.toast({html: data.msg});
                    }
                    
                    //Enable Button
                    $('.cta #btnEditMembresia').prop( 'disabled', false );
                    $('.cta #btnEditMembresia').val('Guardar');
                });
            }
            return false;
        });

        $(document).on('keyup', '#nombre', function() {
            console.log($(this).val());
            $('#nombre_alumno').text($(this).val());
        });
        $(document).on('keyup', '#apellidos', function() {
            console.log($(this).val());
            $('#apellidos_alumno').text($(this).val());
        });

        $(document).on('keyup', '#nombre', function() {
            console.log($(this).val());
            $('#nombre_profesor').text($(this).val());
        });
        $(document).on('keyup', '#apellidos', function() {
            console.log($(this).val());
            $('#apellidos_profesor').text($(this).val());
        });
    }

    var avatarSwap = function () {
        var $this = $('.company_logo .avatar');
        var newSource = $this.data('alt-src');
        $this.data('alt-src', $this.attr('src'));
        $this.attr('src', newSource);
      }
      $('.company_logo').hover(avatarSwap, avatarSwap);
    
      $('.company_logo .circle').on('click', function(e) {
        e.preventDefault();
        
        $('.company_logo #avatar_file').trigger('click');
        
        return false;
      });
    
      $('.company_logo #avatar_file').on('change', function(e) {
        e.preventDefault();
        
        var file = $(this);
        var post_id = $('#post_id').val();
        var type = file["type"];
        var size = file[0].files[0].size;
        var ext = $(this).val().split('.').pop().toLowerCase();
        //Check Extension
        if (ext == 'png' || ext == 'jpg' || ext == 'jpeg' || ext == 'zip' || ext == 'rar' || ext == '´pdf' || ext == 'doc')
        {
          //Check Size
          if ((size/1024) < 1024)
          {
            M.toast({html: 'Actualizando avatar...'});
            
            var param = '{"msg": "updateAccointerAvatar","fields": {"post_id": "' + post_id + '"}}';
            var fd = new FormData();    
            fd.append( 'avatar', file[0].files[0]);
            fd.append( 'param', param );
            
            $.ajax({
              url: base_url + '/api',
              data: fd,
              processData: false,
              contentType: false,
              type: 'POST',
              success: function(data) {
                console.log(data);
                  //Check Result
                if (data.status == 1)
                {
                  //Update Image
                  $('.avatar-mi-cuenta').attr("src", data.data.url);
                  $('.img-title-bar').attr("src", data.data.url);
                  $('.img-header-bar').attr("src", data.data.url);
                  M.toast({html: 'Foto de perfil actualizada.'});
                }
                else
                {
                  //Show Error
                  M.toast({html: data.msg});
                }
              }
            });
          }
          else
          {
            //Show Error
            M.toast({html: 'El tamaño máximo de la imagen de perfil es de 1MB.'});
          }
        } 
        else
        {
          //Show Error
          M.toast({html: 'Selecciona un archivo de imagen válido (.png o .jpg).'});
        }
        
        return false;
      });

        //formUpdateInstitution validate
        $('#formUpdateInstitution').validate({
            lang: 'en',
            rules: {
                first_name: {
                        required: true
                    },
                last_name: {
                    required: true
                },
                escuela: {
                    required: true
                },
                password: {
                    required: true
                },
            },
            errorPlacement: function(error,element) {
                return true;
            },
            errorClass : 'error'
        });

        //btnGuardarPersonales click
        $('.cta #btnUpdateInstitution').on('click', function(e) {
        e.preventDefault();

            if ($('#formUpdateInstitution').valid())
            {
            //Leemos los Valores
            var post_id = $('#post_id').val();
            var first_name = $('#first_name').val();
            var last_name = $('#last_name').val();
            var escuela = $('#escuela').val();
            var password = $('#password').val();


            var param = '{"msg": "updateDatosPersonales","fields": {"post_id": "' + post_id + '", "first_name": "' + first_name + '", "last_name": "' + last_name + '", "escuela": "' + escuela + '", "password": "' + password + '"}}';
            //Verificamos el Correo Electrónico
            $('.cta #btnUpdateInstitution').prop( 'disabled', true );
            $('.cta #btnUpdateInstitution').val('Procesando...');

            //Request API
            $.post(base_url + '/api', { param: param }).done(function( data ) {
                
                //Check Result
                if (data.status == 1)
                {
                //Show Success
                M.toast({html: 'Datos personales actualizados'});
                if (data.relocate == 1)
                {
                    location.reload();
                }
                }
                else
                {
                //Show Error
                M.toast({html: data.msg});
                }
                
                //Enable Button
                $('.cta #btnUpdateInstitution').prop( 'disabled', false );
                $('.cta #btnUpdateInstitution').val('Guardar');
            });

            }
            return false;
        });
});