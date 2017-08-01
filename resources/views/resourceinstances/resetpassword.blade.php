<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="../../favicon.ico">

        <title>Resetear Contrase&ntilde;a - FieldVision</title>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

        <!-- Mi estilos -->
        <style>
            body {
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #eee;
            }

            .form-signin {
            max-width: 330px;
            padding: 15px;
            margin: 0 auto;
            }
            .form-signin .form-signin-heading,
            .form-signin .checkbox {
            margin-bottom: 10px;
            }
            .form-signin .checkbox {
            font-weight: normal;
            }
            .form-signin .form-control {
            position: relative;
            height: auto;
            -webkit-box-sizing: border-box;
               -moz-box-sizing: border-box;
                    box-sizing: border-box;
            padding: 10px;
            font-size: 16px;
            }
            .form-signin .form-control:focus {
            z-index: 2;
            }
            .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
            }
            .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            }
        </style>
        
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        
        <!-- Mi Script -->
        <script>
            var bError = <?php echo $arrData['error'];?>;
            var strMsg = '<?php echo $arrData['msg'];?>';
            var strURLBase = '<?php echo $arrData['url'];?>';
            
            //Se verifica al cargar si hay error
            $( document ).ready(
                function ()
                {
                    if(bError){
                        var strTextButton = '<button class="btn btn-lg btn-primary btn-block" type="submit">Regresar</button>';
                        $('.container').html('<h2>' + strMsg + '</h2>' + '</br>' + strTextButton);
                    }
                }
            );
            
            //Se cambia el la contraseña
            function dChangePassword()
            {
                var strPassword = $( '#inputPassword' ).val();
                var strRepassword = $( '#inputPassword2' ).val();
                var strDivider = '####';
                var strURL = strURLBase;// + '/' + strDivider;
                
                
                if(strPassword == '' || strPassword != strRepassword){
                    $('#alert-container').html('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> <strong>Las contraseñas</strong> no coinciden o están vacías.</div>');
                    return;
                }else{
                    $('#alert-container').html('');
                }
                
                //Armamos el URL
                var strToken = strMsg;
                strURL = strURL.replace(strDivider, strToken);
                var objParam = { 'password': strPassword };
                
                //Armamos la solicitud (http://api.jquery.com/jquery.ajax/)
                var request = $.ajax({
                    dataType: 'json',
                    contentType: 'application/json; charset=UTF-8',
                    crossDomain: true,
                    url: strURL,
                    method: 'PUT',
                    processData: false,
                    data: JSON.stringify( objParam )
                });

                //Realizamos la solicitud
                request.done(function( objData ) {
                    var strTextButton = '<a href="http://datatraffic.com.co" class="alert-link">Regresar</a>';
                    if(!objData.error && objData.msg=='OK'){
                        $('.container').html('<div class="alert alert-success" role="alert"> Contraseña restaurada con <strong>Éxito!</strong>, para proseguir de click en el siguiente enlace:' + strTextButton + ' </div>');
                    }else{
                        $('.container').html('<div class="alert alert-danger" role="alert"> <strong>Error!</strong> Por favor contacte al administrador, para proseguir de click en el siguiente enlace: ' + strTextButton + ' </div>');
                    }
                });
                
                //En caso de un error inesperado
                request.fail(function( jqXHR, textStatus ) {
                    var strTextButton = '<a href="#" class="alert-link">Regresar</a>';
                    $('.container').html('<div class="alert alert-danger" role="alert"> <strong>Error!</strong> Por favor contacte al administrador, para proseguir de click en el siguiente enlace: ' + strTextButton + ' </div>');
                });
            }
        </script>
    </head>
    <body>

        <div class="container">
            <div id="alert-container"></div>
            <form class="form-signin">
                <h2 class="form-signin-heading">Restaurar su contrase&ntilde;a</h2>
                <label for="inputPassword" class="sr-only">Contrase&ntilde;a</label>
                <input type="password" id="inputPassword" class="form-control" placeholder="Contrase&ntilde;a" required="">
                <label for="inputPassword" class="sr-only">Repita la Contraseña</label>
                <input type="password" id="inputPassword2" class="form-control" placeholder="Repita su Contrase&ntilde;a" required="">
                <a class="btn btn-lg btn-primary btn-block"  onclick="dChangePassword(this);">Guardar</a>
                <a class="btn btn-lg btn-primary btn-block" href="http:\\datatraffic.com.co">Regresar</a>
            </form>
        </div> <!-- /container -->

    </body>
</html>

