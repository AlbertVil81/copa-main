<?php /* Template Name: API */    
	
	date_default_timezone_set('America/Mexico_City');     
	//include_once( getcwd().'/wp-includes/PHPMailer/PHPMailer.php' );  
	//require_once(get_template_directory().'/vendor/phpmailer/phpmailer/src/PHPMailer.php');
	//require_once(get_template_directory().'/vendor/phpmailer/phpmailer/src/Exception.php')

	require_once(get_template_directory().'/vendor/autoload.php');
	//require_once(get_template_directory().'/vendor/phpmailer/phpmailer/src/Exception.php');
	//require_once(get_template_directory().'/vendor/phpmailer/phpmailer/src/PHPMailer.php');
	//require_once(get_template_directory().'/vendor/phpmailer/phpmailer/src/SMTP.php');

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	use PHPMailer\PHPMailer\SMTP;
	
	//Read Param Field    
	$json = (isset($_POST['param'])) ? $_POST['param'] : NULL; $output = FALSE;    
	$json = str_replace('\\', '', $json);     
	
	//Check JSON    
	if ($json != NULL){

        //Decode Data JSON        
		$json_decode = json_decode($json, true);         
		
		//Read Action JSON        
		$msg = (isset($json_decode['msg'])) ? (string)trim($json_decode['msg']) : '';         
		
		//Read Fields JSON        
		$fields = (isset($json_decode['fields'])) ? $json_decode['fields'] : array(); 

        //getURL        
		if ($msg == 'getURL')
		{
			//Build Response Array
			$array = array(
				'status' => (int)1,
				'msg' => 'success',
				'data' => get_bloginfo("url")
			);

			//Print JSON Array
			printJSON($array);
			$output = TRUE;
		}

		//Login
		if ($msg == 'login')
		{
			//Read Data
			$email = (isset($fields['email'])) ? (string)trim($fields['email']) : '';
			$password = (isset($fields['password'])) ? (string)trim($fields['password']) : '';

			//Verificamos
			if ($email && $password)
			{
				//Query User
				$args = array(
					'post_type' => 'administradores',
					'meta_query' => array(
						array(
							'key' => 'email',
							'value' => $email,
							'compare' => '='
						)
					)
				);
				$query = get_posts($args);
				
				//Check User exists
				if (count($query) > 0)
				{
					//Leemos el Objeto
					foreach ($query as $row) { }
					
					//Verificamos el Password
					if ($password == get_field("contrasena", $row->ID)) 
					{
						
						$data = array();
						$data['ID'] = $row->ID;
						$data['nombre'] = get_field("nombre", $row->ID);
						$data['apellidos'] = get_field("apellidos", $row->ID);
						$data['email'] = get_field("email", $row->ID);
						$data['gimnasio'] = get_field("gimnasio", $row->ID);
						$data['imagen'] = get_field("imagen", $row->ID);

						//Session Start
						$_SESSION['user_logged'] = true;
						$_SESSION['user'] = $data;
													
						//Build Response Array
						$array = array(
							'status' => (int)1,
							'msg' => 'success',
							'data' => $data
						);
	
						//Print JSON Array
						printJSON($array);
						$output = TRUE;
					}
					else
					{
						//Show Error
						$array = array(
							'status' => (int)0,
							'msg' => (string)'Contraseña incorrecta.'
						);
			
						//Print JSON Array
						printJSON($array);
						$output = TRUE;
					}
				}
				else
				{
					//Show Error
					$array = array(
						'status' => (int)0,
						'msg' => (string)'Este correo electrónico no está registrado.'
					);
		
					//Print JSON Array
					printJSON($array);
					$output = TRUE;
				}
			}
			else
			{
				//Show Error
				$array = array(
					'status' => (int)0,
					'msg' => (string)'Missing fields. Try again.'
				);
	
				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
		}

		//getClientes
		if ($msg == 'getClientes')
		{
			//Read Data
			$admin_id = (isset($fields['admin_id'])) ? (string)trim($fields['admin_id']) : '';

			function obtenerMembresia($id){
				$args = array(
					'post_type' => 'membresias',
					'p' => $id,
				);
				$query = get_posts($args);

				//Leemos el Objeto
				foreach ($query as $row) {
					$nombre = get_field("nombre", $row->ID);
				}
				return $nombre;
			}

			//Query User
			$args = array(
				'post_type' => 'clientes',
				'posts_per_page'   => -1,
				'meta_query' => array(
					array(
						'key' => 'admin_id',
						'value' => $admin_id,
						'compare' => '='
					)
				)
			);
			$query = get_posts($args);

			//Check User exists
			if (count($query) > 0)
			{
				//Leemos el Objeto
				foreach ($query as $row) {
					$data[] = array(
						'ID' => $row->ID,
						'nombre' =>get_field("nombre", $row->ID),
						'apellidos' => get_field("apellidos", $row->ID),
						'id_membresia' => obtenerMembresia(get_field("id_membresia", $row->ID)),
						'vencimiento' => get_field("vencimiento", $row->ID),
						'admin_id' => get_field("admin_id", $row->ID),
					);
				}
											
				//Build Response Array
				$array = array(
					'status' => (int)1,
					'msg' => 'success',
					'data' => $data,
					'count' => count($data)
				);

				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
			else
			{
				//Show Error
				$array = array(
					'status' => (int)0,
					'msg' => (string)'No hay clientes actualmente',
					'count' => 0
				);
	
				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
		}

		//getMembresiaOnly
		if ($msg == 'getMembresiaOnly')
		{
			//Read Data
			$membresia_id = (isset($fields['membresia_id'])) ? (string)trim($fields['membresia_id']) : '';

			//Query User
			$args = array(
				'post_type' => 'membresias',
				'p' => $membresia_id,
			);

			$query = get_posts($args);

			//Check User exists
			if (count($query) > 0)
			{
				//Leemos el Objeto
				foreach ($query as $row) {
					$data[] = array(
						'ID' => $row->ID,
						'nombre' =>get_field("nombre", $row->ID),
						'precio' => get_field("precio", $row->ID),
						'duracion' => get_field("duracion", $row->ID),
						'beneficios' => get_field("beneficios", $row->ID),
						'admin_id' => get_field("admin_id", $row->ID)
					);
				}
											
				//Build Response Array
				$array = array(
					'status' => (int)1,
					'msg' => 'success',
					'data' => $data,
					'count' => count($data)
				);

				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
			else
			{
				//Show Error
				$array = array(
					'status' => (int)0,
					'msg' => (string)'No hay membresías actualmente'
				);
	
				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
		}

		//createCliente
		if ($msg == 'createCliente')
		{
			//Read Data
			$admin_id = (isset($fields['admin_id'])) ? (string)trim($fields['admin_id']) : '';
			$nombre = (isset($fields['nombre'])) ? (string)trim($fields['nombre']) : '';
			$apellidos = (isset($fields['apellidos'])) ? (string)trim($fields['apellidos']) : '';
			$email = (isset($fields['email'])) ? (string)trim($fields['email']) : '';
			$membresia = (isset($fields['membresia'])) ? (string)trim($fields['membresia']) : '';
			$fecha_vencimiento = (isset($fields['fecha_vencimiento'])) ? (string)trim($fields['fecha_vencimiento']) : '';
			$fecha_vencimiento = date("Y/m/d", strtotime($fecha_vencimiento));
			$fecha_hoy = date("Y/m/d");

			function obtenerMembresiaCosto($id){
				$args = array(
					'post_type' => 'membresias',
					'p' => $id,
				);
				$query = get_posts($args);

				//Leemos el Objeto
				foreach ($query as $row) {
					$nombre = get_field("precio", $row->ID);
				}
				return $nombre;
			}

			//Verify
			if ($email)
			{
				//Query User
				$args = array(
					'post_type' => 'clientes',
					'meta_query' => array(
						'relation' => 'AND',
						array(
							'key' => 'email',
							'value' => $email,
							'compare' => '='
						),
						array(
							'key' => 'admin_id',
							'value' => $admin_id,
							'compare' => '='
						)
					)
				);
				$query = get_posts($args);
				
				//Check User
				if (count($query) == 0)
				{
					
					//Register User
					$my_post = array(
						'post_title'    => wp_strip_all_tags($nombre . ' ' . $apellidos . ' - ' . $email, true),
						'post_status'   => 'publish',
						'post_author'   => 1,
						'post_type'	  => 'clientes'
					);
				
					// Save Data
					$post_id = wp_insert_post( $my_post );
				
					//Verify
					if ($post_id != 0)
					{
						// Save Custom Fields
						if ( ! update_post_meta ($post_id, 'nombre', $nombre ) ) add_post_meta( $post_id, 'nombre', $nombre );
						if ( ! update_post_meta ($post_id, 'apellidos', $apellidos ) ) add_post_meta( $post_id, 'apellidos', $apellidos );
						if ( ! update_post_meta ($post_id, 'id_membresia', $membresia ) ) add_post_meta( $post_id, 'id_membresia', $membresia );
						if ( ! update_post_meta ($post_id, 'vencimiento', $fecha_vencimiento ) ) add_post_meta( $post_id, 'vencimiento', $fecha_vencimiento );
						if ( ! update_post_meta ($post_id, 'email', $email ) ) add_post_meta( $post_id, 'email', $email );
						if ( ! update_post_meta ($post_id, 'admin_id', $admin_id ) ) add_post_meta( $post_id, 'admin_id', $admin_id );
					}

					$cliente_id = $post_id;

					//Register Contrato
					$my_post = array(
						'post_title'    => wp_strip_all_tags($cliente_id . ' ' . $membresia . ' - ' . $fecha_hoy, true),
						'post_status'   => 'publish',
						'post_author'   => 1,
						'post_type'	  => 'contratos'
					);
				
					// Save Data
					$post_id = wp_insert_post( $my_post );
				
					//Verify
					if ($post_id != 0)
					{
						// Save Custom Fields
						if ( ! update_post_meta ($post_id, 'id_cliente', $cliente_id ) ) add_post_meta( $post_id, 'id_cliente', $cliente_id );
						if ( ! update_post_meta ($post_id, 'id_membresia', $membresia ) ) add_post_meta( $post_id, 'id_membresia', $membresia );
						if ( ! update_post_meta ($post_id, 'fecha', $fecha_hoy ) ) add_post_meta( $post_id, 'fecha', $fecha_hoy );
						if ( ! update_post_meta ($post_id, 'costo', obtenerMembresiaCosto($membresia) ) ) add_post_meta( $post_id, 'fecha', obtenerMembresiaCosto($membresia) );
						if ( ! update_post_meta ($post_id, 'admin_id', $admin_id ) ) add_post_meta( $post_id, 'admin_id', $admin_id );
					}

					//Create an instance; passing `true` enables exceptions
					$mail = new PHPMailer(true);

					try {
						//Server settings
						$mail->SMTPDebug = 0;                      //Enable verbose debug output
						$mail->isSMTP();                                            //Send using SMTP
						$mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
						$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
						$mail->Username   = 'villegas28194@gmail.com';                     //SMTP username
						$mail->Password   = 'oxtdmnjemcepuece';                               //SMTP password
						$mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
						$mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

						//Recipients
						$mail->setFrom('villegas28194@gmail.com', 'Equipo Redline');
						$mail->addAddress(''.$email.'', ''.$nombre.'');     //Add a recipient
						//$mail->addAddress('albertpv94@hotmail.com');               //Name is optional
						//$mail->addReplyTo('albertpv94@hotmail.com', 'Information');
						//$mail->addCC('albertpv94@hotmail.com');
						//$mail->addBCC('albertpv94@hotmail.com');

						//Attachments
						//$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
						//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

						//Content
						$mail->isHTML(true);                                  //Set email format to HTML
						$mail->CharSet = 'UTF-8';
						$mail->Subject = $nombre.' '.'¡Gracias por elegir Redline Project!';
						$mail->Body    = '
							<!doctype html>
								<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

								<head>
								<title>
								</title>
								<!--[if !mso]><!-->
								<meta http-equiv="X-UA-Compatible" content="IE=edge">
								<!--<![endif]-->
								<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
								<meta name="viewport" content="width=device-width, initial-scale=1">
								<style type="text/css">
									#outlook a {
									padding: 0;
									}

									body {
									margin: 0;
									padding: 0;
									-webkit-text-size-adjust: 100%;
									-ms-text-size-adjust: 100%;
									}

									table,
									td {
									border-collapse: collapse;
									mso-table-lspace: 0pt;
									mso-table-rspace: 0pt;
									}

									img {
									border: 0;
									height: auto;
									line-height: 100%;
									outline: none;
									text-decoration: none;
									-ms-interpolation-mode: bicubic;
									}

									p {
									display: block;
									margin: 13px 0;
									}
								</style>
								<!--[if mso]>
										<noscript>
										<xml>
										<o:OfficeDocumentSettings>
										<o:AllowPNG/>
										<o:PixelsPerInch>96</o:PixelsPerInch>
										</o:OfficeDocumentSettings>
										</xml>
										</noscript>
										<![endif]-->
								<!--[if lte mso 11]>
										<style type="text/css">
										.mj-outlook-group-fix { width:100% !important; }
										</style>
										<![endif]-->
								<!--[if !mso]><!-->
								<link href="https://fonts.googleapis.com/css2?family=Quicksand" rel="stylesheet" type="text/css">
								<style type="text/css">
									@import url(https://fonts.googleapis.com/css2?family=Quicksand);
								</style>
								<!--<![endif]-->
								<style type="text/css">
									@media only screen and (min-width:480px) {
									.mj-column-per-100 {
										width: 100% !important;
										max-width: 100%;
									}
									}
								</style>
								<style media="screen and (min-width:480px)">
									.moz-text-html .mj-column-per-100 {
									width: 100% !important;
									max-width: 100%;
									}
								</style>
								<style type="text/css">
								</style>
								</head>

								<body style="word-spacing:normal;">
								<div style="">
									<!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
									<div style="margin:0px auto;max-width:600px;">
									<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
										<tbody>
										<tr>
											<td style="direction:ltr;font-size:0px;padding:20px 0;text-align:center;">
											<!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:600px;" ><![endif]-->
											<div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
												<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
												<tbody>
													<tr>
													<td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
														<p style="border-top:solid 4px #D9D9D9;font-size:1px;margin:0px auto;width:100%;">
														</p>
														<!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" style="border-top:solid 4px #D9D9D9;font-size:1px;margin:0px auto;width:550px;" role="presentation" width="550px" ><tr><td style="height:0;line-height:0;"> &nbsp;
								</td></tr></table><![endif]-->
													</td>
													</tr>
													<tr>
													<td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
														<div style="font-family:Quicksand;font-size:32px;font-weight:700;line-height:1;text-align:left;color:#444242;">¡Bienvenido a Redline!</div>
													</td>
													</tr>
													<tr>
													<td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
														<div style="font-family:Quicksand;font-size:16px;font-weight:500;line-height:20px;text-align:left;color:#444242;">Gracias por elegirnos, puedes consultar tu credencial de acceso en el siguiente enlace:</div>
													</td>
													</tr>
													<tr>
													<td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
														<div style="font-family:Quicksand;font-size:30px;font-weight:700;line-height:1;text-align:left;color:#444242;"><a href="https://redline.joseperezmx.com/credencial-pdf/?id='.$cliente_id.'">Click aquí</a></div>
													</td>
													</tr>
													<tr>
													<td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
														<div style="font-family:Quicksand;font-size:16px;font-weight:500;line-height:1;text-align:left;color:#444242;">Si no puedes consultar tu credencial pregunta en recepción.</div>
													</td>
													</tr>
													<tr>
													<td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
														<div style="font-family:Quicksand;font-size:16px;font-weight:500;line-height:20px;text-align:left;color:#444242;">Saludos,<br /><b>Equipo Redline</b></div>
													</td>
													</tr>
													<tr>
													<td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
														<p style="border-top:solid 4px #D9D9D9;font-size:1px;margin:0px auto;width:100%;">
														</p>
														<!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" style="border-top:solid 4px #D9D9D9;font-size:1px;margin:0px auto;width:550px;" role="presentation" width="550px" ><tr><td style="height:0;line-height:0;"> &nbsp;
								</td></tr></table><![endif]-->
													</td>
													</tr>
													<tr>
													<td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
														<div style="font-family:Quicksand;font-size:16px;font-weight:500;line-height:20px;text-align:center;color:#444242;">Necesitas ayuda? Contáctanos <a href="mailto:villegas2894@gmail.com" style="color: #1B98E0 !important;">Redline Project</a></div>
													</td>
													</tr>
												</tbody>
												</table>
											</div>
											<!--[if mso | IE]></td></tr></table><![endif]-->
											</td>
										</tr>
										</tbody>
									</table>
									</div>
									<!--[if mso | IE]></td></tr></table><![endif]-->
								</div>
								</body>

								</html>
						';
						$mail->AltBody = '¡Gracias por elegir Redline Project!';
						if ($mail->Send())
						{
							$response = true;
						}
						else
						{
							$response = false;
						}
						//echo 'Message has been sent';
					} catch (Exception $e) {
						//echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
					}
					
					//Build Response Array
					$array = array(
						'status' => (int)1,
						'msg' => 'success',
					);
			
					//Print JSON Array
					printJSON($array);
					$output = TRUE;
				}
				else
				{
					//Show Error
					$array = array(
						'status' => (int)0,
						'msg' => (string)'Este cliente ya está registrado.'
					);
			
					//Print JSON Array
					printJSON($array);
					$output = TRUE;
				}
			}
			else
			{
				//Show Error
				$array = array(
					'status' => (int)0,
					'msg' => (string)'Faltan campos.'
				);
		
				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
			
		}

		//getCursos
		if ($msg == 'getMembresia')
		{
			//Read Data
			$admin_id = (isset($fields['admin_id'])) ? (string)trim($fields['admin_id']) : '';


			//Query User
			$args = array(
				'post_type' => 'membresias',
				'posts_per_page'   => -1,
				'meta_query' => array(
					array(
						'key' => 'admin_id',
						'value' => $admin_id,
						'compare' => '='
					)
				)
			);
			$query = get_posts($args);

			//Check User exists
			if (count($query) > 0)
			{
				//Leemos el Objeto
				foreach ($query as $row) {
					$data[] = array(
						'ID' => $row->ID,
						'nombre' =>get_field("nombre", $row->ID),
						'precio' => get_field("precio", $row->ID),
						'duracion' => get_field("duracion", $row->ID),
						'beneficios' => get_field("beneficios", $row->ID),
						'admin_id' => get_field("admin_id", $row->ID)
					);
				}
											
				//Build Response Array
				$array = array(
					'status' => (int)1,
					'msg' => 'success',
					'data' => $data,
					'count' => count($data)
				);

				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
			else
			{
				//Show Error
				$array = array(
					'status' => (int)0,
					'msg' => (string)'No hay membresías actualmente',
					'count' => count($data)
				);
	
				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
		}

		//createMembresia
		if ($msg == 'createMembresia')
		{
			//Read Data
			$admin_id = (isset($fields['admin_id'])) ? (string)trim($fields['admin_id']) : '';
			$nombre = (isset($fields['nombre'])) ? (string)trim($fields['nombre']) : '';
			$precio = (isset($fields['precio'])) ? (string)trim($fields['precio']) : '';
			$duracion = (isset($fields['duracion'])) ? (string)trim($fields['duracion']) : '';
			$beneficios = (isset($fields['beneficios'])) ? (string)trim($fields['beneficios']) : '';

			//Verify
			if ($nombre)
			{
				//Register User
				$my_post = array(
					'post_title'    => wp_strip_all_tags($nombre . ' ' . $precio, true),
					'post_status'   => 'publish',
					'post_author'   => 1,
					'post_type'	  => 'membresias'
				);
			
				// Save Data
				$post_id = wp_insert_post( $my_post );
			
				//Verify
				if ($post_id != 0)
				{
					// Save Custom Fields
					if ( ! update_post_meta ($post_id, 'nombre', $nombre ) ) add_post_meta( $post_id, 'nombre', $nombre );
					if ( ! update_post_meta ($post_id, 'precio', $precio ) ) add_post_meta( $post_id, 'precio', $precio );
					if ( ! update_post_meta ($post_id, 'duracion', $duracion ) ) add_post_meta( $post_id, 'duracion', $duracion );
					if ( ! update_post_meta ($post_id, 'beneficios', $beneficios ) ) add_post_meta( $post_id, 'beneficios', $beneficios );
					if ( ! update_post_meta ($post_id, 'admin_id', $admin_id ) ) add_post_meta( $post_id, 'admin_id', $admin_id );
				}
				
				//Build Response Array
				$array = array(
					'status' => (int)1,
					'msg' => 'success',
				);
		
				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
			else
			{
				//Show Error
				$array = array(
					'status' => (int)0,
					'msg' => (string)'Faltan campos.'
				);
		
				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
			
		}

		//getProfesores
		if ($msg == 'getProfesores')
		{
			//Read Data
			$admin_id = (isset($fields['admin_id'])) ? (string)trim($fields['admin_id']) : '';

			//Query User
			$args = array(
				'post_type' => 'profesores',
				'posts_per_page'   => -1,
				'meta_query' => array(
					array(
						'key' => 'admin_id',
						'value' => $admin_id,
						'compare' => '='
					)
				)
			);
			$query = get_posts($args);

			//Check User exists
			if (count($query) > 0)
			{
				//Leemos el Objeto
				foreach ($query as $row) {

					//Query User
					$argsCursos = array(
						'post_type' => 'cursos',
						'posts_per_page'   => -1,
						'meta_query' => array(
							array(
								'key' => 'id_profesor',
								'value' => $row->ID,
								'compare' => '='
							)
						)
					);
					$queryCursos = get_posts($argsCursos);

					foreach ($queryCursos as $rowCursos) {
						$union_cursos .= get_field("nombre", $rowCursos->ID) . ', ';
					}
					
					$data[] = array(
						'ID' => $row->ID,
						'nombre' =>get_field("nombre", $row->ID),
						'apellidos' => get_field("apellidos", $row->ID),
						'domicilio' => get_field("domicilio", $row->ID),
						'telefono' => get_field("telefono", $row->ID),
						'email' => get_field("email", $row->ID),
						'cursos' => $union_cursos,
						'admin_id' => get_field("admin_id", $row->ID),
					);
				}
											
				//Build Response Array
				$array = array(
					'status' => (int)1,
					'msg' => 'success',
					'data' => $data,
					'count' => count($data)
				);

				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
			else
			{
				//Show Error
				$array = array(
					'status' => (int)0,
					'msg' => (string)'No hay profesores actualmente',
					'count' => count($data)
				);
	
				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
		}

		//createAlumno
		if ($msg == 'createProfesor')
		{
			//Read Data
			$admin_id = (isset($fields['admin_id'])) ? (string)trim($fields['admin_id']) : '';
			$nombre = (isset($fields['nombre'])) ? (string)trim($fields['nombre']) : '';
			$apellidos = (isset($fields['apellidos'])) ? (string)trim($fields['apellidos']) : '';
			$email = (isset($fields['email'])) ? (string)trim($fields['email']) : '';
			$domicilio = (isset($fields['direccion'])) ? (string)trim($fields['direccion']) : '';
			$telefono = (isset($fields['telefono'])) ? (string)trim($fields['telefono']) : '';

			//Verify
			if ($email)
			{
				//Query User
				$args = array(
					'post_type' => 'profesores',
					'meta_query' => array(
						'relation' => 'AND',
						array(
							'key' => 'email',
							'value' => $email,
							'compare' => '='
						),
						array(
							'key' => 'admin_id',
							'value' => $admin_id,
							'compare' => '='
						)
					)
				);
				$query = get_posts($args);
				
				//Check User
				if (count($query) == 0)
				{
					
					//Register User
					$my_post = array(
						'post_title'    => wp_strip_all_tags($nombre . ' ' . $apellidos . ' - ' . $email, true),
						'post_status'   => 'publish',
						'post_author'   => 1,
						'post_type'	  => 'profesores'
					);
				
					// Save Data
					$post_id = wp_insert_post( $my_post );
				
					//Verify
					if ($post_id != 0)
					{
						// Save Custom Fields
						if ( ! update_post_meta ($post_id, 'nombre', $nombre ) ) add_post_meta( $post_id, 'nombre', $nombre );
						if ( ! update_post_meta ($post_id, 'apellidos', $apellidos ) ) add_post_meta( $post_id, 'apellidos', $apellidos );
						if ( ! update_post_meta ($post_id, 'email', $email ) ) add_post_meta( $post_id, 'email', $email );
						if ( ! update_post_meta ($post_id, 'domicilio', $domicilio ) ) add_post_meta( $post_id, 'domicilio', $domicilio );
						if ( ! update_post_meta ($post_id, 'telefono', $telefono ) ) add_post_meta( $post_id, 'telefono', $telefono );
						if ( ! update_post_meta ($post_id, 'admin_id', $admin_id ) ) add_post_meta( $post_id, 'admin_id', $admin_id );
					}
					
					//Build Response Array
					$array = array(
						'status' => (int)1,
						'msg' => 'success',
					);
			
					//Print JSON Array
					printJSON($array);
					$output = TRUE;
				}
				else
				{
					//Show Error
					$array = array(
						'status' => (int)0,
						'msg' => (string)'Este profesor ya está registrado.'
					);
			
					//Print JSON Array
					printJSON($array);
					$output = TRUE;
				}
			}
			else
			{
				//Show Error
				$array = array(
					'status' => (int)0,
					'msg' => (string)'Faltan campos.'
				);
		
				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
			
		}

		//getVisitas
		if ($msg == 'getVisitas')
		{
			//Read Data
			$admin_id = (isset($fields['admin_id'])) ? (string)trim($fields['admin_id']) : '';
			$fecha_inicio = (isset($fields['fecha_inicio'])) ? (string)trim($fields['fecha_inicio']) : '';
			$fecha_fin = (isset($fields['fecha_fin'])) ? (string)trim($fields['fecha_fin']) : '';
			if($fecha_inicio){
				$fecha_inicio = date("Y/m/d", strtotime($fecha_inicio));
			}
			if($fecha_fin){
				$fecha_fin = date("Y/m/d", strtotime($fecha_fin));
			}


			function obtenerMembresia($id){
				$args = array(
					'post_type' => 'membresias',
					'p' => $id,
				);
				$query = get_posts($args);

				//Leemos el Objeto
				foreach ($query as $row) {
					$nombre = get_field("nombre", $row->ID);
				}
				return $nombre;
			}

			function obtenerCliente($id){
				$args = array(
					'post_type' => 'clientes',
					'p' => $id,
				);
				$query = get_posts($args);

				//Leemos el Objeto
				foreach ($query as $row) {
					$nombre = get_field("nombre", $row->ID);
					$apellidos = get_field("apellidos", $row->ID);
				}
				return $nombre." ".$apellidos;
			}

			//Query User
			$args = array(
				'post_type' => 'visitas',
				'posts_per_page'   => -1,
				'meta_query' => array(
					array(
						'key' => 'admin_id',
						'value' => $admin_id,
						'compare' => '='
					)
				)
			);
			$query = get_posts($args);

			//Check User exists
			if (count($query) > 0)
			{
				//Leemos el Objeto
				foreach ($query as $row) {
					$data[] = array(
						'ID' => $row->ID,
						'membresia_id' =>obtenerMembresia(get_field("membresia_id", $row->ID)),
						'cliente_id' =>obtenerCliente(get_field("cliente_id", $row->ID)),
						'fecha' =>get_field("fecha", $row->ID),
						'admin_id' => get_field("admin_id", $row->ID),
					);
				}

				$tmp_data = array();

				foreach ($data as $row_data) {

					$fecha = date("Y/m/d", strtotime($row_data['fecha']));
			   
					if(($fecha >= $fecha_inicio) && ($fecha <= $fecha_fin)) {
						$tmp_data[] = $row_data;
					}
				}

				if(!$fecha_inicio && !$fecha_fin){
					$tmp_data = $data;
				}
											
				//Build Response Array
				$array = array(
					'status' => (int)1,
					'msg' => 'success',
					'data' => $tmp_data,
					'count' => count($tmp_data)
				);

				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
			else
			{
				//Show Error
				$array = array(
					'status' => (int)0,
					'msg' => (string)'No hay pagos actualmente'
				);
	
				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
		}

		//createPago
		if ($msg == 'addVisita')
		{
			//Read Data
			$id_registrar_visita = (isset($fields['id_registrar_visita'])) ? (string)trim($fields['id_registrar_visita']) : '';
			$fecha = (isset($fields['fecha'])) ? (string)trim($fields['fecha']) : '';
			$fecha = date("Y/m/d", strtotime($fecha));
			$admin_id = (isset($fields['admin_id'])) ? (string)trim($fields['admin_id']) : '';

			function obtenerMembresia($id){
				$args = array(
					'post_type' => 'clientes',
					'p' => $id,
				);
				$query = get_posts($args);

				//Leemos el Objeto
				foreach ($query as $row) {
					$id_membresia = get_field("id_membresia", $row->ID);
				}
				return $id_membresia;
			}

			$membresia_id = obtenerMembresia($id_registrar_visita);

			//Verify
			if ($id_registrar_visita)
			{
				//Query User
				$args = array(
					'post_type' => 'clientes',
					'p' => $id_registrar_visita,
				);

				$query = get_posts($args);
				
				//Check User exists
				if (count($query) > 0)
				{
					//Leemos el Objeto
					foreach ($query as $row) {
						$data[] = array(
							'ID' => $row->ID,
							'nombre' =>get_field("nombre", $row->ID),
							'apellidos' => get_field("apellidos", $row->ID),
							'id_membresia' => get_field("id_membresia", $row->ID),
							'vencimiento' => get_field("vencimiento", $row->ID),
							'admin_id' => get_field("admin_id", $row->ID),
						);
					}

					if(date("Y/m/d", strtotime($data[0]['vencimiento'])) >= date('Y/m/d')){
						//Register User
						$my_post = array(
							'post_title'    => wp_strip_all_tags($id_registrar_visita . '-' . $admin_id, true),
							'post_status'   => 'publish',
							'post_author'   => 1,
							'post_type'	  => 'visitas'
						);
					
						// Save Data
						$post_id = wp_insert_post( $my_post );
					
						//Verify
						if ($post_id != 0)
						{
							// Save Custom Fields
							if ( ! update_post_meta ($post_id, 'cliente_id', $id_registrar_visita ) ) add_post_meta( $post_id, 'cliente_id', $id_registrar_visita );
							if ( ! update_post_meta ($post_id, 'membresia_id', $membresia_id ) ) add_post_meta( $post_id, 'membresia_id', $membresia_id );
							if ( ! update_post_meta ($post_id, 'fecha', $fecha ) ) add_post_meta( $post_id, 'fecha', $fecha );
							if ( ! update_post_meta ($post_id, 'admin_id', $admin_id ) ) add_post_meta( $post_id, 'admin_id', $admin_id );
						}
						$generator = new Picqer\Barcode\BarcodeGeneratorHTML();

						//Proccess Data Filter
						$html = '';
						$html.= '<center>';
						$html.= '<div class="row">';
						$html.= '<div class="col m6 offset-m3">';
						$html.= '	<div class="card blue-grey darken-1">';
						$html.= '		<div class="card-content white-text">';
						$html.= '			<span class="card-title">Bienvenido</span>';
						$html.= '			<p>Gracias por elegir Redline Project</p>';
						$html.= '			<p>'.$data[0]['nombre'].'</p>';
						$html.= '			<p>Tu membresía vence: '.$data[0]['vencimiento'].'</p><br>';
						$html.= '			<p>'.$generator->getBarcode('081231723897', $generator::TYPE_CODE_128).'</p>';
						$html.= '		</div>';
						$html.= '		<div class="card-action">';
						$html.= '		<a href="#">Acceso permitido</a>';
						$html.= '		</div>';
						$html.= '	</div>';
						$html.= '	</div>';
						$html.= '</div>';
						$html.= '</center>';

						//Build Response Array
						$array = array(
							'status' => (int)1,
							'msg' => 'Bienvenido a Redline Project',
							'html' => $html,
						);

						//Print JSON Array
						printJSON($array);
						$output = TRUE;
					}
					else
					{
						$html.= '<div class="materialert error">';
						$html.= '	<div class="material-icons">error_outline</div>';
						$html.= '	Tu membresía está vencida, por favor renuevala con alguno de nuestros colaboradores.';
						$html.= '</div>';

						//Show Error
						$array = array(
							'status' => (int)0,
							'msg' => (string)'La membresía está vencida.',
							'html' => $html,
						);

						//Print JSON Array
						printJSON($array);
						$output = TRUE;
					}
				}
				else
				{
					//Show Error
					$array = array(
						'status' => (int)0,
						'msg' => (string)'Este cliente no está registrado.'
					);

					//Print JSON Array
					printJSON($array);
					$output = TRUE;
				}
				
			}
			else
			{
				//Show Error
				$array = array(
					'status' => (int)0,
					'msg' => (string)'Faltan campos.'
				);
		
				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
			
		}

		//getCliente
		if ($msg == 'getCliente')
		{
			//Read Data
			$cliente_id = (isset($fields['cliente_id'])) ? (string)trim($fields['cliente_id']) : '';

			function obtenerMembresia($id){
				$args = array(
					'post_type' => 'membresias',
					'p' => $id,
				);
				$query = get_posts($args);

				//Leemos el Objeto
				foreach ($query as $row) {
					$nombre = get_field("nombre", $row->ID);
				}
				return $nombre;
			}

			//Query User
			$args = array(
				'post_type' => 'clientes',
				'p' => $cliente_id,
			);

			$query = get_posts($args);

			//Check User exists
			if (count($query) > 0)
			{
				//Leemos el Objeto
				foreach ($query as $row) {
					$data[] = array(
						'ID' => $row->ID,
						'nombre' =>get_field("nombre", $row->ID),
						'apellidos' => get_field("apellidos", $row->ID),
						'email' => get_field("email", $row->ID),
						'id_membresia' => get_field("id_membresia", $row->ID),
						'vencimiento' => get_field("vencimiento", $row->ID),
						'admin_id' => get_field("admin_id", $row->ID),
						'membresia' => obtenerMembresia(get_field("id_membresia", $row->ID)),
					);
				}
											
				//Build Response Array
				$array = array(
					'status' => (int)1,
					'msg' => 'success',
					'data' => $data,
					'count' => count($data)
				);

				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
			else
			{
				//Show Error
				$array = array(
					'status' => (int)0,
					'msg' => (string)'No se encontró al cliente'
				);
	
				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
		}
		//getAlumno
		if ($msg == 'getProfesor')
		{
			//Read Data
			$profesor_id = (isset($fields['profesor_id'])) ? (string)trim($fields['profesor_id']) : '';

			//Query User
			$args = array(
				'post_type' => 'profesores',
				'p' => $profesor_id,
			);

			$query = get_posts($args);

			//Check User exists
			if (count($query) > 0)
			{
				//Leemos el Objeto
				foreach ($query as $row) {
					$data[] = array(
						'ID' => $row->ID,
						'nombre' =>get_field("nombre", $row->ID),
						'apellidos' => get_field("apellidos", $row->ID),
						'email' =>get_field("email", $row->ID),
						'telefono' => get_field("telefono", $row->ID),
						'domicilio' => get_field("domicilio", $row->ID),
						'admin_id' => get_field("admin_id", $row->ID),
					);
				}
											
				//Build Response Array
				$array = array(
					'status' => (int)1,
					'msg' => 'success',
					'data' => $data,
					'count' => count($data)
				);

				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
			else
			{
				//Show Error
				$array = array(
					'status' => (int)0,
					'msg' => (string)'No se encontró al alumno'
				);
	
				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
		}

		//getPagosAlumno
		if ($msg == 'getPagosAlumno')
		{
			//Read Data
			$alumno_id = (isset($fields['alumno_id'])) ? (string)trim($fields['alumno_id']) : '';

			function obtenerNombre($id){
				$args = array(
					'post_type' => 'profesores',
					'p' => $id,
				);
				$query = get_posts($args);

				if(count($query) > 0){
					//Leemos el Objeto
					foreach ($query as $row) {
						$nombre = get_field("nombre", $row->ID);
						$apellidos = get_field("apellidos", $row->ID);
					}
				} else {
					$args = array(
						'post_type' => 'alumnos',
						'p' => $id,
					);
					$query = get_posts($args);

					//Leemos el Objeto
					foreach ($query as $row) {
						$nombre = get_field("nombre", $row->ID);
						$apellidos = get_field("apellidos", $row->ID);
					}
				}
				return $nombre." ".$apellidos;
			}

			//Query User
			$args = array(
				'post_type' => 'pagos',
				'posts_per_page'   => -1,
				'meta_query' => array(
					array(
						'key' => 'id_alumno_profesor',
						'value' => $alumno_id,
						'compare' => '='
					)
				)
			);
			$query = get_posts($args);

			//Check User exists
			if (count($query) > 0)
			{
				//Leemos el Objeto
				foreach ($query as $row) {
					$data[] = array(
						'ID' => $row->ID,
						'concepto' =>get_field("concepto", $row->ID),
						'cantidad' =>get_field("cantidad", $row->ID),
						'forma_de_pago' =>get_field("forma_de_pago", $row->ID),
						'id_alumno_profesor' => obtenerNombre(get_field("id_alumno_profesor", $row->ID)),
						'fecha' =>get_field("fecha", $row->ID),
						'admin_id' => get_field("admin_id", $row->ID),
					);
				}
											
				//Build Response Array
				$array = array(
					'status' => (int)1,
					'msg' => 'success',
					'data' => $data,
					'count' => count($data)
				);

				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
			else
			{
				//Show Error
				$array = array(
					'status' => (int)0,
					'msg' => (string)'No hay pagos actualmente'
				);
	
				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
		}

		//getCursos
		if ($msg == 'getCurso')
		{
			//Read Data
			$curso_id = (isset($fields['curso_id'])) ? (string)trim($fields['curso_id']) : '';

			function obtenerProfesor($id){
				$args = array(
					'post_type' => 'profesores',
					'p' => $id,
				);
				$query = get_posts($args);

				//Leemos el Objeto
				foreach ($query as $row) {
					$nombre = get_field("nombre", $row->ID);
					$apellidos = get_field("apellidos", $row->ID);
				}
				return $nombre." ".$apellidos;
			}

			//Query User
			$args = array(
				'post_type' => 'cursos',
				'p' => $curso_id,
			);

			$query = get_posts($args);

			//Check User exists
			if (count($query) > 0)
			{
				//Leemos el Objeto
				foreach ($query as $row) {
					$data[] = array(
						'ID' => $row->ID,
						'nombre' =>get_field("nombre", $row->ID),
						'id_profesor' => obtenerProfesor(get_field("id_profesor", $row->ID)),
						'id_profesor_detalle' => get_field("id_profesor", $row->ID),
						'admin_id' => get_field("admin_id", $row->ID),
						'nota' => get_field("nota", $row->ID)
					);
				}
											
				//Build Response Array
				$array = array(
					'status' => (int)1,
					'msg' => 'success',
					'data' => $data,
					'count' => count($data)
				);

				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
			else
			{
				//Show Error
				$array = array(
					'status' => (int)0,
					'msg' => (string)'No hay cursos actualmente'
				);
	
				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
		}

		//getCursoProfesor
		if ($msg == 'getCursoProfesor')
		{
			//Read Data
			$profesor_id = (isset($fields['profesor_id'])) ? (string)trim($fields['profesor_id']) : '';

			function obtenerProfesor($id){
				$args = array(
					'post_type' => 'profesores',
					'p' => $id,
				);
				$query = get_posts($args);

				//Leemos el Objeto
				foreach ($query as $row) {
					$nombre = get_field("nombre", $row->ID);
					$apellidos = get_field("apellidos", $row->ID);
				}
				return $nombre." ".$apellidos;
			}

			//Query User
			$args = array(
				'post_type' => 'cursos',
				'posts_per_page'   => -1,
				'meta_query' => array(
					array(
						'key' => 'id_profesor',
						'value' => $profesor_id,
						'compare' => '='
					)
				)
			);
			$query = get_posts($args);

			//Check User exists
			if (count($query) > 0)
			{
				//Leemos el Objeto
				foreach ($query as $row) {
					$data[] = array(
						'ID' => $row->ID,
						'nombre' =>get_field("nombre", $row->ID),
						'id_profesor' => obtenerProfesor(get_field("id_profesor", $row->ID)),
						'admin_id' => get_field("admin_id", $row->ID),
					);
				}
											
				//Build Response Array
				$array = array(
					'status' => (int)1,
					'msg' => 'success',
					'data' => $data,
					'count' => count($data)
				);

				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
			else
			{
				//Show Error
				$array = array(
					'status' => (int)0,
					'msg' => (string)'No hay cursos actualmente'
				);
	
				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
		}

		//editCliente
		if ($msg == 'editCliente')
		{
			//Read Data
			$post_id = (isset($fields['cliente_id'])) ? (string)trim($fields['cliente_id']) : '';
			$nombre = (isset($fields['nombre'])) ? (string)trim($fields['nombre']) : '';
			$apellidos = (isset($fields['apellidos'])) ? (string)trim($fields['apellidos']) : '';
			$email = (isset($fields['email'])) ? (string)trim($fields['email']) : '';
			$membresia = (isset($fields['membresia'])) ? (string)trim($fields['membresia']) : '';
			$fecha_vencimiento = (isset($fields['fecha_vencimiento'])) ? (string)trim($fields['fecha_vencimiento']) : '';
			$fecha_vencimiento = date("Y/m/d", strtotime($fecha_vencimiento));
			$fecha_hoy = date("Y/m/d");

			function obtenerMembresiaCosto($id){
				$args = array(
					'post_type' => 'membresias',
					'p' => $id,
				);
				$query = get_posts($args);

				//Leemos el Objeto
				foreach ($query as $row) {
					$nombre = get_field("precio", $row->ID);
				}
				return $nombre;
			}

			function obtenerAdminID($id){
				$args = array(
					'post_type' => 'clientes',
					'p' => $id,
				);
				$query = get_posts($args);

				//Leemos el Objeto
				foreach ($query as $row) {
					$nombre = get_field("admin_id", $row->ID);
				}
				return $nombre;
			}

			//Verify
			if ($email)
			{
				//Query User
				$args = array(
					'post_type' => 'clientes',
					'p' => $post_id,
				);
				$query = get_posts($args);
				
				//Check User
				if (count($query) > 0)
				{
					
					//Verify
					if ($post_id != 0)
					{
						// Save Custom Fields
						if ( ! update_post_meta ($post_id, 'nombre', $nombre ) ) add_post_meta( $post_id, 'nombre', $nombre );
						if ( ! update_post_meta ($post_id, 'apellidos', $apellidos ) ) add_post_meta( $post_id, 'apellidos', $apellidos );
						if ( ! update_post_meta ($post_id, 'email', $email ) ) add_post_meta( $post_id, 'email', $email );
						if ( ! update_post_meta ($post_id, 'id_membresia', $membresia ) ) add_post_meta( $post_id, 'id_membresia', $membresia );
						if ( ! update_post_meta ($post_id, 'vencimiento', $fecha_vencimiento ) ) add_post_meta( $post_id, 'vencimiento', $fecha_vencimiento );
					}

					$cliente_id = $post_id;

					//Register Contrato
					$my_post = array(
						'post_title'    => wp_strip_all_tags($cliente_id . ' - ' . $membresia . ' - ' . $fecha_hoy, true),
						'post_status'   => 'publish',
						'post_author'   => 1,
						'post_type'	  => 'contratos'
					);
				
					// Save Data
					$post_id = wp_insert_post( $my_post );
				
					//Verify
					if ($post_id != 0)
					{
						// Save Custom Fields
						if ( ! update_post_meta ($post_id, 'id_cliente', $cliente_id ) ) add_post_meta( $post_id, 'id_cliente', $cliente_id );
						if ( ! update_post_meta ($post_id, 'id_membresia', $membresia ) ) add_post_meta( $post_id, 'id_membresia', $membresia );
						if ( ! update_post_meta ($post_id, 'fecha', $fecha_hoy ) ) add_post_meta( $post_id, 'fecha', $fecha_hoy );
						if ( ! update_post_meta ($post_id, 'costo', obtenerMembresiaCosto($membresia) ) ) add_post_meta( $post_id, 'fecha', obtenerMembresiaCosto($membresia) );
						if ( ! update_post_meta ($post_id, 'admin_id', obtenerAdminID($cliente_id) ) ) add_post_meta( $post_id, 'admin_id', obtenerAdminID($cliente_id) );
					}
					
					//Build Response Array
					$array = array(
						'status' => (int)1,
						'msg' => 'Datos del cliente actualizados con exito.'
					);
			
					//Print JSON Array
					printJSON($array);
					$output = TRUE;
				}
				else
				{
					//Show Error
					$array = array(
						'status' => (int)0,
						'msg' => (string)'Error al actualizar datos.'
					);
			
					//Print JSON Array
					printJSON($array);
					$output = TRUE;
				}
			}
			else
			{
				//Show Error
				$array = array(
					'status' => (int)0,
					'msg' => (string)'Faltan campos.'
				);
		
				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
			
		}

		//editProfesor
		if ($msg == 'editProfesor')
		{
			//Read Data
			$post_id = (isset($fields['profesor_id'])) ? (string)trim($fields['profesor_id']) : '';
			$nombre = (isset($fields['nombre'])) ? (string)trim($fields['nombre']) : '';
			$apellidos = (isset($fields['apellidos'])) ? (string)trim($fields['apellidos']) : '';
			$email = (isset($fields['email'])) ? (string)trim($fields['email']) : '';
			$domicilio = (isset($fields['direccion'])) ? (string)trim($fields['direccion']) : '';
			$telefono = (isset($fields['telefono'])) ? (string)trim($fields['telefono']) : '';

			//Verify
			if ($email)
			{
				//Query User
				$args = array(
					'post_type' => 'profesores',
					'p' => $post_id,
				);
				$query = get_posts($args);
				
				//Check User
				if (count($query) > 0)
				{
				
					//Verify
					if ($post_id != 0)
					{
						// Save Custom Fields
						if ( ! update_post_meta ($post_id, 'nombre', $nombre ) ) add_post_meta( $post_id, 'nombre', $nombre );
						if ( ! update_post_meta ($post_id, 'apellidos', $apellidos ) ) add_post_meta( $post_id, 'apellidos', $apellidos );
						if ( ! update_post_meta ($post_id, 'email', $email ) ) add_post_meta( $post_id, 'email', $email );
						if ( ! update_post_meta ($post_id, 'domicilio', $domicilio ) ) add_post_meta( $post_id, 'domicilio', $domicilio );
						if ( ! update_post_meta ($post_id, 'telefono', $telefono ) ) add_post_meta( $post_id, 'telefono', $telefono );
					}
					
					//Build Response Array
					$array = array(
						'status' => (int)1,
						'msg' => 'Dato del profesor actualizados con exito.',
					);
			
					//Print JSON Array
					printJSON($array);
					$output = TRUE;
				}
				else
				{
					//Show Error
					$array = array(
						'status' => (int)0,
						'msg' => (string)'Error al actualizar datos.'
					);
			
					//Print JSON Array
					printJSON($array);
					$output = TRUE;
				}
			}
			else
			{
				//Show Error
				$array = array(
					'status' => (int)0,
					'msg' => (string)'Faltan campos.'
				);
		
				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
			
		}

		//monthlySales
		if ($msg == 'monthlySales')
		{
			//Read Data
			$admin_id = (isset($fields['admin_id'])) ? (string)trim($fields['admin_id']) : '';

			//Query User
			$args = array(
				'post_type' => 'pagos',
				'posts_per_page'   => -1,
				'meta_query' => array(
					array(
						'key' => 'admin_id',
						'value' => $admin_id,
						'compare' => '='
					)
				)
			);
			$query = get_posts($args);

			//Check User exists
			if (count($query) > 0)
			{
				//Leemos el Objeto
				foreach ($query as $row) { 
					$mes_actual = date('m/Y'); 
					$fecha_cumple = get_field("fecha", $row->ID); 
					$fecha_cumple = date("d/m/Y", strtotime($fecha_cumple));
					$fecha_cumple = substr($fecha_cumple, 3);
					if ($mes_actual == $fecha_cumple){

						$data[] = array(
							'ID' => $row->ID,
							'concepto' =>get_field("concepto", $row->ID),
							'cantidad' => get_field("cantidad", $row->ID),
							'fecha' => get_field("fecha", $row->ID),
							'forma_de_pago' => get_field("forma_de_pago", $row->ID),
							'admin_id' => get_field("admin_id", $row->ID)
						);
					} 

				}
											
				//Build Response Array
				$array = array(
					'status' => (int)1,
					'msg' => 'success',
					'data' => $data,
					'count' => count($data),
					'mes_actual' => $mes_actual,
					'fecha_cumple' => $fecha_cumple,
				);

				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
			else
			{
				//Show Error
				$array = array(
					'status' => (int)0,
					'msg' => (string)'No hay ventas mensuales'
				);
	
				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
		}

		//getPago
		if ($msg == 'getPago')
		{
			//Read Data
			$pago_id = (isset($fields['pago_id'])) ? (string)trim($fields['pago_id']) : '';

			function obtenerCurso($id){
				$args = array(
					'post_type' => 'cursos',
					'p' => $id,
				);
				$query = get_posts($args);

				//Leemos el Objeto
				foreach ($query as $row) {
					$nombre = get_field("nombre", $row->ID);
				}
				return $nombre;
			}

			//Query User
			$args = array(
				'post_type' => 'pagos',
				'p' => $pago_id,
			);

			$query = get_posts($args);

			//Check User exists
			if (count($query) > 0)
			{
				//Leemos el Objeto
				foreach ($query as $row) {
					$data[] = array(
						'ID' => $row->ID,
						'concepto' =>get_field("concepto", $row->ID),
						'fecha' =>get_field("fecha", $row->ID),
						'cantidad' =>get_field("cantidad", $row->ID),
						'forma_de_pago' =>get_field("forma_de_pago", $row->ID),
						'id_alumno_profesor' =>get_field("id_alumno_profesor", $row->ID),
					);
				}
											
				//Build Response Array
				$array = array(
					'status' => (int)1,
					'msg' => 'success',
					'data' => $data,
					'count' => count($data)
				);

				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
			else
			{
				//Show Error
				$array = array(
					'status' => (int)0,
					'msg' => (string)'No se encontró el pago.'
				);
	
				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
		}

		//editPago
		if ($msg == 'editPago')
		{
			//Read Data
			$post_id = (isset($fields['post_id'])) ? (string)trim($fields['post_id']) : '';
			$concepto = (isset($fields['concepto'])) ? (string)trim($fields['concepto']) : '';
			$cantidad = (isset($fields['cantidad'])) ? (string)trim($fields['cantidad']) : '';
			$id_alumno_profesor = (isset($fields['id_alumno_profesor'])) ? (string)trim($fields['id_alumno_profesor']) : '';
			$forma_de_pago = (isset($fields['forma_de_pago'])) ? (string)trim($fields['forma_de_pago']) : '';

			$fecha = (isset($fields['fecha'])) ? (string)trim($fields['fecha']) : '';
			$fecha = date("Y/m/d", strtotime($fecha));

			//Verify
			if ($concepto)
			{
				//Query User
				$args = array(
					'post_type' => 'pagos',
					'p' => $post_id,
				);
				$query = get_posts($args);
				
				//Check User
				if (count($query) > 0)
				{
					//Verify
					if ($post_id != 0)
					{
						// Save Custom Fields
						if ( ! update_post_meta ($post_id, 'concepto', $concepto ) ) add_post_meta( $post_id, 'concepto', $concepto );
						if ( ! update_post_meta ($post_id, 'cantidad', $cantidad ) ) add_post_meta( $post_id, 'cantidad', $cantidad );
						if ( ! update_post_meta ($post_id, 'id_alumno_profesor', $id_alumno_profesor ) ) add_post_meta( $post_id, 'id_alumno_profesor', $id_alumno_profesor );
						if ( ! update_post_meta ($post_id, 'forma_de_pago', $forma_de_pago ) ) add_post_meta( $post_id, 'forma_de_pago', $forma_de_pago );
						if ( ! update_post_meta ($post_id, 'fecha', $fecha ) ) add_post_meta( $post_id, 'fecha', $fecha );
					}
				}
				
				//Build Response Array
				$array = array(
					'status' => (int)1,
					'msg' => 'success',
				);
		
				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
			else
			{
				//Show Error
				$array = array(
					'status' => (int)0,
					'msg' => (string)'Faltan campos.'
				);
		
				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
			
		}

		//editMembresia
		if ($msg == 'editMembresia')
		{
			//Read Data
			$post_id = (isset($fields['membresia_id'])) ? (string)trim($fields['membresia_id']) : '';
			$nombre = (isset($fields['nombre'])) ? (string)trim($fields['nombre']) : '';
			$precio = (isset($fields['precio'])) ? (string)trim($fields['precio']) : '';
			$duracion = (isset($fields['duracion'])) ? (string)trim($fields['duracion']) : '';
			$beneficios = (isset($fields['beneficios'])) ? (string)trim($fields['beneficios']) : '';

			//Verify
			if ($nombre)
			{
				//Query User
				$args = array(
					'post_type' => 'membresias',
					'p' => $post_id,
				);
				$query = get_posts($args);
				
				//Check User
				if (count($query) > 0)
				{
					//Verify
					if ($post_id != 0)
					{
						// Save Custom Fields
						if ( ! update_post_meta ($post_id, 'nombre', $nombre ) ) add_post_meta( $post_id, 'nombre', $nombre );
						if ( ! update_post_meta ($post_id, 'precio', $precio ) ) add_post_meta( $post_id, 'precio', $precio );
						if ( ! update_post_meta ($post_id, 'duracion', $duracion ) ) add_post_meta( $post_id, 'duracion', $duracion );
						if ( ! update_post_meta ($post_id, 'beneficios', $beneficios ) ) add_post_meta( $post_id, 'beneficios', $beneficios );
					}
				}
				
				//Build Response Array
				$array = array(
					'status' => (int)1,
					'msg' => 'Membresía actualizada con éxito.',
				);
		
				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
			else
			{
				//Show Error
				$array = array(
					'status' => (int)0,
					'msg' => (string)'Faltan campos.'
				);
		
				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
			
		}

		//misAlumnos
		if ($msg == 'misAlumnosSearch')
		{
			//Read Data
			$admin_id = (isset($fields['admin_id'])) ? (string)trim($fields['admin_id']) : '';
			$keyword = (isset($fields['keyword'])) ? (string)trim($fields['keyword']) : '';
			
			function obtenerMembresia($id){
				$args = array(
					'post_type' => 'membresias',
					'p' => $id,
				);
				$query = get_posts($args);

				//Leemos el Objeto
				foreach ($query as $row) {
					$nombre = get_field("nombre", $row->ID);
				}
				return $nombre;
			}

			//Query User
			$args = array(
				'post_type' => 'clientes',
				'posts_per_page'   => -1,
				'meta_query' => array(
					array(
						'key' => 'admin_id',
						'value' => $admin_id,
						'compare' => '='
					)
				)
			);
			$query = get_posts($args);

			//Check User exists
			if (count($query) > 0)
			{
				//Leemos el Objeto
				foreach ($query as $row) {
					$data[] = array(
						'ID' => $row->ID,
						'nombre' =>get_field("nombre", $row->ID),
						'apellidos' => get_field("apellidos", $row->ID),
						'id_membresia' => obtenerMembresia(get_field("id_membresia", $row->ID)),
						'vencimiento' => get_field("vencimiento", $row->ID),
						'admin_id' => get_field("admin_id", $row->ID),
					);
				}

				$tmp_data = array();
				$supertext = "";

				foreach ($data as $row_data) {
					$supertext = $row_data['nombre'].$row_data['apellidos'];

					$posicion_coincidencia = strpos(strtolower($supertext), strtolower($keyword));
					if ($posicion_coincidencia === false) {
					} else {
						$tmp_data[] = $row_data;
					}
				}

				if(!$keyword){
					$tmp_data = $data;
				}

				//Proccess Data Filter
				$html = '';	
				$html.= '<table class="highlight">';
				$html.= '<thead>';
					$html.= '<tr>';
						$html.= '<th>ID</th>';
						$html.= '<th>Nombre</th>';
						$html.= '<th>Apellidos</th>';
						$html.= '<th>Membresía</th>';
						$html.= '<th>Vencimiento</th>';
						$html.= '<th>Acciones</th>';
					$html.= '</tr>';
				$html.= '</thead>';
				foreach ($tmp_data as $row_tmp_data) {
					$html.= '<tbody>';
						$html.= '<tr>';
							$html.= '<td>'.$row_tmp_data['ID'].'</td>';
							$html.= '<td>'.$row_tmp_data['nombre'].'</td>';
							$html.= '<td>'.$row_tmp_data['apellidos'].'</td>';
							$html.= '<td>'.$row_tmp_data['id_membresia'].'</td>';
							$html.= '<td>'.$row_tmp_data['vencimiento'].'</td>';
							$html.= '<td>';
								$html.= '<a href="'.get_bloginfo("url").'/dashbaord/detalles-cliente/?id='.$row_tmp_data['ID'].'"><i class="material-icons">info</i></a>';
							$html.= '</td>';
						$html.= '</tr>';
					$html.= '</tbody>';
				}
				$html.= '</table>';

				//Build Response Array
				$array = array(
					'status' => (int)1,
					'msg' => 'success',
					'data' => $tmp_data,
					'html' => $html,
					'count' => count($tmp_data)
				);

				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
			else
			{
				//Show Error
				$array = array(
					'status' => (int)0,
					'msg' => (string)'No se encontraron alumnos'
				);
	
				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
		}

		//misProfesores
		if ($msg == 'misProfesoresSearch')
		{
			//Read Data
			$admin_id = (isset($fields['admin_id'])) ? (string)trim($fields['admin_id']) : '';
			$keyword = (isset($fields['keyword'])) ? (string)trim($fields['keyword']) : '';
			
			//Query User
			$args = array(
				'post_type' => 'profesores',
				'posts_per_page'   => -1,
				'meta_query' => array(
					array(
						'key' => 'admin_id',
						'value' => $admin_id,
						'compare' => '='
					)
				)
			);
			$query = get_posts($args);

			//Check User exists
			if (count($query) > 0)
			{

				//Leemos el Objeto
				foreach ($query as $row) {

					//Query User
					$argsCursos = array(
						'post_type' => 'cursos',
						'posts_per_page'   => -1,
						'meta_query' => array(
							array(
								'key' => 'id_profesor',
								'value' => $row->ID,
								'compare' => '='
							)
						)
					);
					$queryCursos = get_posts($argsCursos);

					foreach ($queryCursos as $rowCursos) {
						$union_cursos .= get_field("nombre", $rowCursos->ID) . ', ';
					}
					
					$data[] = array(
						'ID' => $row->ID,
						'nombre' =>get_field("nombre", $row->ID),
						'apellidos' => get_field("apellidos", $row->ID),
						'domicilio' => get_field("domicilio", $row->ID),
						'telefono' => get_field("telefono", $row->ID),
						'email' => get_field("email", $row->ID),
						'cursos' => $union_cursos,
						'admin_id' => get_field("admin_id", $row->ID),
					);
				}

				$tmp_data = array();
				$supertext = "";

				foreach ($data as $row_data) {
					$supertext = $row_data['nombre'].$row_data['apellidos'];

					$posicion_coincidencia = strpos(strtolower($supertext), strtolower($keyword));
					if ($posicion_coincidencia === false) {
					} else {
						$tmp_data[] = $row_data;
					}
				}

				if(!$keyword){
					$tmp_data = $data;
				}

				//Proccess Data Filter
				$html = '';	
				$html.= '<table class="highlight">';
				$html.= '<thead>';
					$html.= '<tr>';
						$html.= '<th>Matrícula</th>';
						$html.= '<th>Nombre</th>';
						$html.= '<th>Apellidos</th>';
						$html.= '<th>Domicilio</th>';
						$html.= '<th>Teléfono</th>';
						$html.= '<th>Email</th>';
						$html.= '<th>Cursos</th>';
						$html.= '<th>Acciones</th>';
					$html.= '</tr>';
				$html.= '</thead>';
				foreach ($tmp_data as $row_tmp_data) {
					$html.= '<tbody>';
						$html.= '<tr>';
							$html.= '<td>'.$row_tmp_data['ID'].'</td>';
							$html.= '<td>'.$row_tmp_data['nombre'].'</td>';
							$html.= '<td>'.$row_tmp_data['apellidos'].'</td>';
							$html.= '<td>'.$row_tmp_data['domicilio'].'</td>';
							$html.= '<td>'.$row_tmp_data['telefono'].'</td>';
							$html.= '<td>'.$row_tmp_data['email'].'</td>';
							$html.= '<td>'.$row_tmp_data['cursos'].'</td>';
							$html.= '<td>';
								$html.= '<a href="'.get_bloginfo("url").'/dashbaord/detalles-profesor/?id='.$row_tmp_data['ID'].'"><i class="material-icons">edit</i></a>';
							$html.= '</td>';
						$html.= '</tr>';
					$html.= '</tbody>';
				}
				$html.= '</table>';

				//Build Response Array
				$array = array(
					'status' => (int)1,
					'msg' => 'success',
					'data' => $tmp_data,
					'html' => $html,
					'count' => count($tmp_data)
				);

				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
			else
			{
				//Show Error
				$array = array(
					'status' => (int)0,
					'msg' => (string)'No se encontraron profesores.'
				);
	
				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
		}

		//misPagos
		if ($msg == 'misPagosSearch')
		{
			//Read Data
			$admin_id = (isset($fields['admin_id'])) ? (string)trim($fields['admin_id']) : '';
			$keyword = (isset($fields['keyword'])) ? (string)trim($fields['keyword']) : '';

			function obtenerNombre($id){
				$args = array(
					'post_type' => 'profesores',
					'p' => $id,
				);
				$query = get_posts($args);

				if(count($query) > 0){
					//Leemos el Objeto
					foreach ($query as $row) {
						$nombre = get_field("nombre", $row->ID);
						$apellidos = get_field("apellidos", $row->ID);
					}
				} else {
					$args = array(
						'post_type' => 'alumnos',
						'p' => $id,
					);
					$query = get_posts($args);

					//Leemos el Objeto
					foreach ($query as $row) {
						$nombre = get_field("nombre", $row->ID);
						$apellidos = get_field("apellidos", $row->ID);
					}
				}
				return $nombre." ".$apellidos;
			}
			
			//Query User
			$args = array(
				'post_type' => 'pagos',
				'posts_per_page'   => -1,
				'meta_query' => array(
					array(
						'key' => 'admin_id',
						'value' => $admin_id,
						'compare' => '='
					)
				)
			);
			$query = get_posts($args);

			//Check User exists
			if (count($query) > 0)
			{
				//Leemos el Objeto
				foreach ($query as $row) {
					$data[] = array(
						'ID' => $row->ID,
						'concepto' =>get_field("concepto", $row->ID),
						'cantidad' =>get_field("cantidad", $row->ID),
						'forma_de_pago' =>get_field("forma_de_pago", $row->ID),
						'id_alumno_profesor' => obtenerNombre(get_field("id_alumno_profesor", $row->ID)),
						'fecha' =>get_field("fecha", $row->ID),
						'admin_id' => get_field("admin_id", $row->ID),
					);
				}

				$tmp_data = array();
				$supertext = "";

				foreach ($data as $row_data) {
					$supertext = $row_data['concepto'].$row_data['id_alumno_profesor'];

					$posicion_coincidencia = strpos(strtolower($supertext), strtolower($keyword));
					if ($posicion_coincidencia === false) {
					} else {
						$tmp_data[] = $row_data;
					}
				}

				if(!$keyword){
					$tmp_data = $data;
				}

				//Proccess Data Filter
				$html = '';	
				$html.= '<table class="highlight">';
				$html.= '<thead>';
					$html.= '<tr>';
						$html.= '<th>Matrícula</th>';
						$html.= '<th>Concepto</th>';
						$html.= '<th>Cantidad</th>';
						$html.= '<th>Forma de pago</th>';
						$html.= '<th>Alumno o profesor</th>';
						$html.= '<th>Fecha</th>';
						$html.= '<th>Acciones</th>';
					$html.= '</tr>';
				$html.= '</thead>';
				foreach ($tmp_data as $row_tmp_data) {
					$html.= '<tbody>';
						$html.= '<tr>';
							$html.= '<td>'.$row_tmp_data['ID'].'</td>';
							$html.= '<td>'.$row_tmp_data['concepto'].'</td>';
							$html.= '<td>'.$row_tmp_data['cantidad'].'</td>';
							$html.= '<td>'.$row_tmp_data['forma_de_pago'].'</td>';
							$html.= '<td>'.$row_tmp_data['id_alumno_profesor'].'</td>';
							$html.= '<td>'.$row_tmp_data['fecha'].'</td>';
							$html.= '<td>';
								$html.= '<a href="'.get_bloginfo("url").'/dashbaord/detalles-pago/?id='.$row_tmp_data['ID'].'"><i class="material-icons">edit</i></a>';
							$html.= '</td>';
						$html.= '</tr>';
					$html.= '</tbody>';
				}
				$html.= '</table>';

				//Build Response Array
				$array = array(
					'status' => (int)1,
					'msg' => 'success',
					'data' => $tmp_data,
					'html' => $html,
					'count' => count($tmp_data)
				);

				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
			else
			{
				//Show Error
				$array = array(
					'status' => (int)0,
					'msg' => (string)'No se encontraron alumnos'
				);
	
				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
		}

		//misCursos
		if ($msg == 'misCursosSearch')
		{
			//Read Data
			$admin_id = (isset($fields['admin_id'])) ? (string)trim($fields['admin_id']) : '';
			$keyword = (isset($fields['keyword'])) ? (string)trim($fields['keyword']) : '';

			function obtenerProfesor($id){
				$args = array(
					'post_type' => 'profesores',
					'p' => $id,
				);
				$query = get_posts($args);

				//Leemos el Objeto
				foreach ($query as $row) {
					$nombre = get_field("nombre", $row->ID);
					$apellidos = get_field("apellidos", $row->ID);
				}
				return $nombre." ".$apellidos;
			}
			
			//Query User
			$args = array(
				'post_type' => 'membresias',
				'posts_per_page'   => -1,
				'meta_query' => array(
					array(
						'key' => 'admin_id',
						'value' => $admin_id,
						'compare' => '='
					)
				)
			);
			$query = get_posts($args);

			//Check User exists
			if (count($query) > 0)
			{
				//Leemos el Objeto
				foreach ($query as $row) {
					$data[] = array(
						'ID' => $row->ID,
						'nombre' =>get_field("nombre", $row->ID),
						'precio' => get_field("precio", $row->ID),
						'duracion' => get_field("duracion", $row->ID),
						'beneficios' => get_field("beneficios", $row->ID),
						'admin_id' => get_field("admin_id", $row->ID)
					);
				}

				$tmp_data = array();
				$supertext = "";

				foreach ($data as $row_data) {
					$supertext = $row_data['nombre'].$row_data['duracion'];

					$posicion_coincidencia = strpos(strtolower($supertext), strtolower($keyword));
					if ($posicion_coincidencia === false) {
					} else {
						$tmp_data[] = $row_data;
					}
				}

				if(!$keyword){
					$tmp_data = $data;
				}

				//Proccess Data Filter
				$html = '';	
				$html.= '<table class="highlight">';
				$html.= '<thead>';
					$html.= '<tr>';
						$html.= '<th>ID</th>';
						$html.= '<th>Nombre</th>';
						$html.= '<th>Precio</th>';
						$html.= '<th>Duración (días)</th>';
						$html.= '<th>Beneficios</th>';
						$html.= '<th>Acciones</th>';
					$html.= '</tr>';
				$html.= '</thead>';
				foreach ($tmp_data as $row_tmp_data) {
					$html.= '<tbody>';
						$html.= '<tr>';
							$html.= '<td>'.$row_tmp_data['ID'].'</td>';
							$html.= '<td>'.$row_tmp_data['nombre'].'</td>';
							$html.= '<td>'.$row_tmp_data['precio'].'</td>';
							$html.= '<td>'.$row_tmp_data['duracion'].'</td>';
							$html.= '<td>'.$row_tmp_data['beneficios'].'</td>';
							$html.= '<td>';
								$html.= '<a href="'.get_bloginfo("url").'/dashbaord/detalles-membresia/?id='.$row_tmp_data['ID'].'"><i class="material-icons">info</i></a>';
							$html.= '</td>';
						$html.= '</tr>';
					$html.= '</tbody>';
				}
				$html.= '</table>';

				//Build Response Array
				$array = array(
					'status' => (int)1,
					'msg' => 'success',
					'data' => $tmp_data,
					'html' => $html,
					'count' => count($tmp_data)
				);

				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
			else
			{
				//Show Error
				$array = array(
					'status' => (int)0,
					'msg' => (string)'No se encontraron membresías'
				);
	
				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
		}

		//getAlumnosGrupo
		if ($msg == 'getAlumnosGrupo')
		{
			//Read Data
			$admin_id = (isset($fields['admin_id'])) ? (string)trim($fields['admin_id']) : '';
			$id_curso = (isset($fields['id_curso'])) ? (string)trim($fields['id_curso']) : '';

			function obtenerCurso($id){
				$args = array(
					'post_type' => 'cursos',
					'p' => $id,
				);
				$query = get_posts($args);

				//Leemos el Objeto
				foreach ($query as $row) {
					$nombre = get_field("nombre", $row->ID);
				}
				return $nombre;
			}

			//Query User
			$args = array(
				'post_type' => 'alumnos',
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key' => 'admin_id',
						'value' => $admin_id,
						'compare' => '='
					),
					array(
						'key' => 'id_curso',
						'value' => $id_curso,
						'compare' => '='
					)
				)
			);
			$query = get_posts($args);

			//Check User exists
			if (count($query) > 0)
			{
				//Leemos el Objeto
				foreach ($query as $row) {
					$data[] = array(
						'ID' => $row->ID,
						'nombre' =>get_field("nombre", $row->ID),
						'apellidos' => get_field("apellidos", $row->ID),
						'fecha_de_ingreso' => get_field("fecha_de_ingreso", $row->ID),
						'fecha_de_egreso' => get_field("fecha_de_egreso", $row->ID),
						'id_curso' => obtenerCurso(get_field("id_curso", $row->ID)),
						'domicilio' => get_field("domicilio", $row->ID),
						'telefono' => get_field("telefono", $row->ID),
						'contacto' => get_field("contacto", $row->ID),
						'contacto_de_emergencia' => get_field("contacto_de_emergencia", $row->ID),
						'fecha_de_nacimiento' => get_field("fecha_de_nacimiento", $row->ID),
						'curp' => get_field("curp", $row->ID),
						'facturacion' => get_field("facturacion", $row->ID),
						'personalidad' => get_field("personalidad", $row->ID),
						'aprendizaje' => get_field("aprendizaje", $row->ID),
						'telefono' => get_field("telefono", $row->ID),
						'admin_id' => get_field("admin_id", $row->ID),
					);
				}
											
				//Build Response Array
				$array = array(
					'status' => (int)1,
					'msg' => 'success',
					'data' => $data,
					'count' => count($data)
				);

				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
			else
			{
				//Show Error
				$array = array(
					'status' => (int)0,
					'msg' => (string)'No hay alumnos actualmente',
					'count' => 0
				);
	
				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
		}

		// updateAccointerAvatar
		if ($msg == 'updateAccointerAvatar')
		{
			//Read Values
			$post_id = (isset($fields['post_id'])) ? (string)trim($fields['post_id']) : '';
			$avatar_file = (isset($_FILES['avatar'])) ? $_FILES['avatar'] : array();

			//Upload Image into WordPress
			$upload = wp_upload_bits($avatar_file["name"], null, file_get_contents($avatar_file["tmp_name"]));

			if ( ! $upload_file['error'] ) {
				$post_id = $post_id;
				$filename = $upload['file'];
				$wp_filetype = wp_check_filetype($filename, null);
				$attachment_data = array();
				$attachment = array(
					'post_mime_type' => $wp_filetype['type'],
					'post_title' => sanitize_file_name($filename),
					'post_content' => '',
					'post_status' => 'inherit'
				);
	
				$attachment_id = wp_insert_attachment( $attachment, $filename, $post_id );
	
				if ( ! is_wp_error( $attachment_id ) ) {
					require_once(ABSPATH . 'wp-admin/includes/image.php');
	
					$attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
					wp_update_attachment_metadata( $attachment_id, $attachment_data );
					set_post_thumbnail( $post_id, $attachment_id );
				}
			}

			$foto = $upload['url'];
			if ( ! update_post_meta ($post_id, 'imagen', $foto ) ) add_post_meta( $post_id, 'avatar', $foto );
			$_SESSION['user']['imagen'] = $foto;
			
			//Build Response Array
			$array = array(
				'status' => (int)1,
				'msg' => 'success',
				'data' => array(
					'post_id' => $post_id,
					'candidate_id' => $candidate_id,
					'url' => $upload['url']
				)
			);
			
			//Print JSON Array
			printJSON($array);
			$output = TRUE;
		}

		//getAdmin
		if ($msg == 'getAdmin')
		{
			//Read Data
			$admin_id = (isset($fields['admin_id'])) ? (string)trim($fields['admin_id']) : '';

			//Query User
			$args = array(
				'post_type' => 'administradores',
				'p' => $admin_id,
			);

			$query = get_posts($args);

			//Check User exists
			if (count($query) > 0)
			{
				//Leemos el Objeto
				foreach ($query as $row) {
					$data[] = array(
						'ID' => $row->ID,
						'nombre' =>get_field("nombre", $row->ID),
						'apellidos' => get_field("apellidos", $row->ID),
						'contrasena' => get_field("contrasena", $row->ID),
						'email' => get_field("email", $row->ID),
						'escuela' => get_field("gimnasio", $row->ID),
						'telefono' => get_field("telefono", $row->ID),
						'imagen' => get_field("imagen", $row->ID),
					);
				}
											
				//Build Response Array
				$array = array(
					'status' => (int)1,
					'msg' => 'success',
					'data' => $data,
					'count' => count($data)
				);

				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
			else
			{
				//Show Error
				$array = array(
					'status' => (int)0,
					'msg' => (string)'No hay administradores actualmente',
					'count' => 0
				);
	
				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
		}

		//updateDatosPersonales       
		if ($msg == 'updateDatosPersonales')
		{
			//Read Data
			$post_id = (isset($fields['post_id'])) ? (string)trim($fields['post_id']) : '';
			$first_name = (isset($fields['first_name'])) ? (string)trim($fields['first_name']) : '';
			$last_name = (isset($fields['last_name'])) ? (string)trim($fields['last_name']) : '';
			$escuela = (isset($fields['escuela'])) ? (string)trim($fields['escuela']) : '';
			$password = (isset($fields['password'])) ? (string)trim($fields['password']) : '';

			//Verificamos
			if ($post_id)
			{
				//Query User
				$args = array(
					'post_type' => 'administradores',
					'p' => $post_id,
				);

				$query = get_posts($args);
				
				//Check User exists
				if (count($query) > 0)
				{
					//Leemos el Objeto
					foreach ($query as $row) { }
					
					if ( ! update_post_meta ($post_id, 'nombre', $first_name ) ) add_post_meta( $post_id, 'nombre', $first_name );
					if ( ! update_post_meta ($post_id, 'apellidos', $last_name ) ) add_post_meta( $post_id, 'apellidos', $last_name );
					if ( ! update_post_meta ($post_id, 'gimnasio', $escuela ) ) add_post_meta( $post_id, 'gimnasio', $escuela );
					if ( ! update_post_meta ($post_id, 'contrasena', $password ) ) add_post_meta( $post_id, 'contrasena', $password );

					//Session Start
					$_SESSION['user']['nombre'] = $first_name;
					$_SESSION['user']['apellidos'] = $last_name;
					$_SESSION['user']['gimnasio'] = $escuela;
												
					//Build Response Array
					$array = array(
						'status' => (int)1,
						'msg' => 'success',
						'relocate' => $one
					);

					//Print JSON Array
					printJSON($array);
					$output = TRUE;
				}
				else
				{
					//Show Error
					$array = array(
						'status' => (int)0,
						'msg' => (string)'Este correo electrónico no está registrado.'
					);
		
					//Print JSON Array
					printJSON($array);
					$output = TRUE;
				}
			}
			else
			{
				//Show Error
				$array = array(
					'status' => (int)0,
					'msg' => (string)'Error al actualizar datos.'
				);
		
				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}

		}

		//monthlySalesChartv2
		if ($msg == 'monthlySalesChartv2')
		{
			//Read Data
			$admin_id = (isset($fields['admin_id'])) ? (string)trim($fields['admin_id']) : '';
			$year = "2024";

			//Query User
			$args = array(
				'post_type' => 'contratos',
				'posts_per_page'   => -1,
				'meta_query' => array(
					array(
						'key' => 'admin_id',
						'value' => $admin_id,
						'compare' => '='
					)
				)
			);
			$query = get_posts($args);

			//Check User exists
			if (count($query) > 0)
			{
				$totalVentas = 0;

				//Leemos el Objeto
				foreach ($query as $row) { 
					$fecha_mes = get_field("fecha", $row->ID); 
					$fecha_mes = date("d/m/Y", strtotime($fecha_mes));
					$fecha_mes = substr($fecha_mes, 3);

					$totalVentas += get_field("costo", $row->ID);

					if ("01/".$year."" == $fecha_mes){

						$dataEnero[] = array(
							'costo' => get_field("costo", $row->ID),
						);
					}
					if ("02/".$year."" == $fecha_mes){

						$dataFebrero[] = array(
							'costo' => get_field("costo", $row->ID),
						);
					} 
					if ("03/".$year."" == $fecha_mes){

						$dataMarzo[] = array(
							'costo' => get_field("costo", $row->ID),
						);
					} 
					if ("04/".$year."" == $fecha_mes){

						$dataAbril[] = array(
							'costo' => get_field("costo", $row->ID),
						);
					} 
					if ("05/".$year."" == $fecha_mes){

						$dataMayo[] = array(
							'costo' => get_field("costo", $row->ID),
						);
					} 
					if ("06/".$year."" == $fecha_mes){

						$dataJunio[] = array(
							'costo' => get_field("costo", $row->ID),
						);
					} 
					if ("07/".$year."" == $fecha_mes){

						$dataJulio[] = array(
							'costo' => get_field("costo", $row->ID),
						);
					} 
					if ("08/".$year."" == $fecha_mes){

						$dataAgosto[] = array(
							'costo' => get_field("costo", $row->ID),
						);
					} 
					if ("09/".$year."" == $fecha_mes){

						$dataSeptiembre[] = array(
							'costo' => get_field("costo", $row->ID),
						);
					} 
					if ("10/".$year."" == $fecha_mes){

						$dataOctubre[] = array(
							'costo' => get_field("costo", $row->ID),
						);
					} 
					if ("11/".$year."" == $fecha_mes){

						$dataNoviembre[] = array(
							'costo' => get_field("costo", $row->ID),
						);
					} 
					if ("12/".$year."" == $fecha_mes){

						$dataDiciembre[] = array(
							'costo' => get_field("costo", $row->ID),
						);
					}  

				}

				$totalVentasEnero = 0;
				$totalVentasFebrero = 0;
				$totalVentasMarzo = 0;
				$totalVentasAbril = 0;
				$totalVentasMayo = 0;
				$totalVentasJunio = 0;
				$totalVentasJulio = 0;
				$totalVentasAgosto = 0;
				$totalVentasSeptiembre = 0;
				$totalVentasOctubre = 0;
				$totalVentasNoviembre = 0;
				$totalVentasDiciembre = 0;

				$contEnero = 0;
				$contFebrero = 0;
				$contMarzo = 0;
				$contAbril = 0;
				$contMayo = 0;
				$contJunio = 0;
				$contJulio = 0;
				$contAgosto = 0;
				$contSeptiembre = 0;
				$contOctubre = 0;
				$contNoviembre = 0;
				$contDiciembre = 0;

				foreach($dataEnero as $enero){
					$totalVentasEnero += $enero['costo'];
					$contEnero++;
				}

				foreach($dataFebrero as $febrero){
					$totalVentasFebrero += $febrero['costo'];
					$contFebrero++;
				}

				foreach($dataMarzo as $marzo){
					$totalVentasMarzo += $marzo['costo'];
					$contMarzo++;
				}

				foreach($dataAbril as $abril){
					$totalVentasAbril += $abril['costo'];
					$contAbril++;
				}

				foreach($dataMayo as $mayo){
					$totalVentasMayo += $mayo['costo'];
					$contMayo++;
				}

				foreach($dataJunio as $junio){
					$totalVentasJunio += $junio['costo'];
					$contJunio++;
				}

				foreach($dataJulio as $julio){
					$totalVentasJulio += $julio['costo'];
					$contJulio++;
				}

				foreach($dataAgosto as $agosto){
					$totalVentasAgosto += $agosto['costo'];
					$contAgosto++;
				}

				foreach($dataSeptiembre as $septiembre){
					$totalVentasSeptiembre += $septiembre['costo'];
					$contSeptiembre++;
				}

				foreach($dataOctubre as $octubre){
					$totalVentasOctubre += $octubre['costo'];
					$contOctubre++;
				}

				foreach($dataNoviembre as $noviembre){
					$totalVentasNoviembre += $noviembre['costo'];
					$contNoviembre++;
				}

				foreach($dataDiciembre as $diciembre){
					$totalVentasDiciembre += $diciembre['costo'];
					$contDiciembre++;
				}
											
				//Build Response Array
				$array = array(
					'status' => (int)1,
					'msg' => 'success',
					'totalVentasEnero' => $totalVentasEnero,
					'totalVentasFebrero' => $totalVentasFebrero,
					'totalVentasMarzo' => $totalVentasMarzo,
					'totalVentasAbril' => $totalVentasAbril,
					'totalVentasMayo' => $totalVentasMayo,
					'totalVentasJunio' => $totalVentasJunio,
					'totalVentasJulio' => $totalVentasJulio,
					'totalVentasAgosto' => $totalVentasAgosto,
					'totalVentasSeptiembre' => $totalVentasSeptiembre,
					'totalVentasOctubre' => $totalVentasOctubre,
					'totalVentasNoviembre' => $totalVentasNoviembre,
					'totalVentasDiciembre' => $totalVentasDiciembre,
					'totalVentas' => $totalVentas,
					'contEnero' => $contEnero,
					'contFebrero' => $contFebrero,
					'contMarzo' => $contMarzo,
					'contAbril' => $contAbril,
					'contMayo' => $contMayo,
					'contJunio' => $contJunio,
					'contJulio' => $contJulio,
					'contAgosto' => $contAgosto,
					'contSeptiembre' => $contSeptiembre,
					'contOctubre' => $contOctubre,
					'contNoviembre' => $contNoviembre,
					'contDiciembre' => $contDiciembre,
				);

				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
			else
			{
				//Show Error
				$array = array(
					'status' => (int)0,
					'msg' => (string)'No hay gráficas para mostrar.'
				);
	
				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
		}

		//ventasSearch
		if ($msg == 'ventasSearch')
		{
			//Read Data
			$company_id = (isset($fields['company_id'])) ? (string)trim($fields['company_id']) : '';
			$fecha_inicio = (isset($fields['fecha_inicio'])) ? (string)trim($fields['fecha_inicio']) : '';
			$fecha_fin = (isset($fields['fecha_fin'])) ? (string)trim($fields['fecha_fin']) : '';
			if($fecha_inicio){
				$fecha_inicio = date("Y/m/d", strtotime($fecha_inicio));
			}
			if($fecha_fin){
				$fecha_fin = date("Y/m/d", strtotime($fecha_fin));
			}

			function obtenerMembresia($id){
				$args = array(
					'post_type' => 'membresias',
					'p' => $id,
				);
				$query = get_posts($args);

				//Leemos el Objeto
				foreach ($query as $row) {
					$nombre = get_field("nombre", $row->ID);
				}
				return $nombre;
			}

			function obtenerCliente($id){
				$args = array(
					'post_type' => 'clientes',
					'p' => $id,
				);
				$query = get_posts($args);

				//Leemos el Objeto
				foreach ($query as $row) {
					$nombre = get_field("nombre", $row->ID);
					$apellidos = get_field("apellidos", $row->ID);
				}
				return $nombre." ".$apellidos;
			}

			//Query User
			$args = array(
				'post_type' => 'visitas',
				'posts_per_page'   => -1,
				'meta_query' => array(
					array(
						'key' => 'admin_id',
						'value' => $company_id,
						'compare' => '='
					)
				)
			);
			$query = get_posts($args);

			//Check User exists
			if (count($query) > 0)
			{
				//Leemos el Objeto
				foreach ($query as $row) {
					$data[] = array(
						'ID' => $row->ID,
						'membresia_id' =>obtenerMembresia(get_field("membresia_id", $row->ID)),
						'cliente_id' =>obtenerCliente(get_field("cliente_id", $row->ID)),
						'fecha' =>get_field("fecha", $row->ID),
						'admin_id' => get_field("admin_id", $row->ID),
					);
				}

				$tmp_data = array();

				foreach ($data as $row_data) {

					$fecha = date("Y/m/d", strtotime($row_data['fecha']));
			
					if(($fecha >= $fecha_inicio) && ($fecha <= $fecha_fin)) {
						$tmp_data[] = $row_data;
					}
				}

				if(!$fecha_inicio && !$fecha_fin){
					$tmp_data = $data;
				}

				//Proccess Data Filter
				$html = '';
				$html.= '<table class="highlight">';	
						$html.= '<thead>';
							$html.= '<tr>';
								$html.= '<th>ID</th>';
								$html.= '<th>Cliente</th>';
								$html.= '<th>Membresía</th>';
								$html.= '<th>Fecha</th>';
							$html.= '</tr>';
						$html.= '</thead>';
				foreach ($tmp_data as $row_tmp_data) {
						$html.= '<tbody>';
							$html.= '<tr>';
								$html.= '<td>'.$row_tmp_data['ID'].'</td>';
								$html.= '<td>'.$row_tmp_data['cliente_id'].'</td>';
								$html.= '<td>'.$row_tmp_data['membresia_id'].'</td>';

								//Formatear fecha
								$fechaAPI = $row_tmp_data['fecha'];
								$fechaMostrar = strtr($fechaAPI, '/', '-');
								$fecha = date("d-m-Y", strtotime($fechaMostrar));

								$html.= '<td>'.$fecha.'</td>';
							$html.= '</tr>';
						$html.= '</tbody>';
				}
				$html.= '</table>';

				//Build Response Array
				$array = array(
					'status' => (int)1,
					'msg' => 'success',
					'data' => $tmp_data,
					'html' => $html,
					'fecha inicio' => $fecha_inicio,
					'fecha fin' => $fecha_fin,
					'count' => count($tmp_data)
				);

				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
			else
			{
				//Show Error
				$array = array(
					'status' => (int)0,
					'msg' => (string)'No hay visitas actualmente'
				);
	
				//Print JSON Array
				printJSON($array);
				$output = TRUE;
			}
		}
	}
	else
	{
		//Show Error
		$array = array(
			'status' => (int)0,
			'msg' => (string)'API Call Invalid.'
		);

		//Print JSON Array
		printJSON($array);
		$output = TRUE;
	}

	//Check Output
	if (!$output)
	{
		//Show Error
		$array = array(
			'status' => (int)0,
			'msg' => (string)'API Error.'
		);

		//Print JSON Array
		printJSON($array);
		$output = TRUE;
	}
?>