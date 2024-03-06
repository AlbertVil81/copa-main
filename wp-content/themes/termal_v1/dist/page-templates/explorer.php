<?php /* Template Name: API / Explorer */ ?>
<!DOCTYPE html>
<html lang="es-ES">
	<head>
		<meta charset="UTF-8">
		<title><?php bloginfo("name"); ?> // API Explorer</title>
		<meta name="robots" content="noindex,nofollow">
		<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
		<style>
			pre {outline: 1px solid #ccc; padding: 5px; margin: 5px; }
			.string { color: green; }
			.number { color: darkorange; }
			.boolean { color: blue; }
			.null { color: magenta; }
			.key { color: red; }
		</style>
		<script type="text/javascript">
			$( document ).ready(function() {
				
				//Impresion
				function output(inp) {
					$( "pre" ).remove();
				    document.body.appendChild(document.createElement('pre')).innerHTML = inp;
				}
				
				//Highlight JSON
				function syntaxHighlight(json) {
				    json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
				    return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
				        var cls = 'number';
				        if (/^"/.test(match)) {
				            if (/:$/.test(match)) {
				                cls = 'key';
				            } else {
				                cls = 'string';
				            }
				        } else if (/true|false/.test(match)) {
				            cls = 'boolean';
				        } else if (/null/.test(match)) {
				            cls = 'null';
				        }
				        return '<span class="' + cls + '">' + match + '</span>';
				    });
				}
				
				//Btn SENDPOST Click
				$('#sendPost').on('click', function(e) {
					e.preventDefault();
					
					//Leemos la Variable
					var param = $('#param').val();
					var baseURL = $('#baseURL').val();
					
					//Verificamos que no este vacia
					if ($.trim(param) != '')
					{
						//Texto de Cargado
						$('.result').html('Working...');
						$( "pre" ).remove();
						
						//Llamada via POST
						$.post( baseURL+"/api", { param: param })
							.done(function( data ) {
								var json = JSON.stringify(data, undefined, 4);
								$('.result').html('<h4>JSON Response</h4>');
								output(syntaxHighlight(json));
							})
							.fail(function(xhr, ajaxOptions, thrownError) {
								$('.result').html(xhr.responseText);
							});
					}
					else
					{
						//Error
						alert('Empty String. Try again.');
					}
				});
				
			});
		</script>
	</head>
	<body>
		<center>
		<form action="<?=site_url()?>/api" name="json" id="json" method="post" >
			<textarea rows="20" id="param" name="param" style="width:700px; resize:none;"></textarea>
			<br />
			<input id="sendPost" name="sendPost" type="submit" value="Send" />
			<input type="hidden" id="baseURL" name="baseURL" value="<?php echo site_url(); ?>" />
		</form>
		</center>
		<hr />
		<div class="result"></div>
	</body>
</html>