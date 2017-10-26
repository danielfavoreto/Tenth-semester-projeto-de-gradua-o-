<?php
    if (isset($_SESSION["user"]) && isset($_SESSION["password"])) {
        header("Location: map.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-param" content="_csrf">
    <meta name="csrf-token" content="WlluWjAuZUhrMjEreGYoEQNoIBVJGjAGERoZPUh9FgRsazEFBBsIDg==">

    <script src="js/fonts.js"></script>
    
    <title>Login</title>
    
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/site.css" rel="stylesheet">
</head>

<body>
    
    <div class="wrap">
        
        <div class="containerLogin">
        
            <div class="central">
                
                <div id="containerFormLogin">

                    <div class="row">

                        <div id="loginTitle" class="col-xs-11">

                            <img src="img/brasao1.png" class = "center">

                            <h1>Login</h1>

                        </div>
                    </div>

                    <form id="login-form" class="form-horizontal" action="userAuth.php" method="post">

                        <div class="form-group field-loginform-login required">

                            <div class='row'> 

                                <div class='col-xs-11'>

                                    <input type="text" id="loginform-login" class="form-control" name="user" autofocus placeholder="Usuário" style="width: 300px;">

                                </div>
                            </div>
                        </div>

                        <div class="form-group field-loginform-password required">
        
                            <div class='row'>

                                <div class='col-xs-11'>

                                    <input type="password" id="loginform-password" class="form-control" name="password" placeholder="Senha" style="width: 300px;">

                                </div>
                            </div>
                        </div>
<!--
                        <div class="form-group field-loginform-rememberme">

                            <div class='row'>

                                <div class='col-xs-11'>

                                    <input type="hidden" name="LoginForm[rememberMe]" value="0"><input type="checkbox" id="loginform-rememberme" name="LoginForm[rememberMe]" value="1" checked> <label for="loginform-rememberme">Continuar conectado</label>

                                </div>
                            </div>
                        </div>-->

                        <div class="form-group">
                            <div class='row'>
                                <div class="col-xs-11">

                                    <button type="submit" id="btn-login" class="btn btn-success" name="login-button">
                                        <i class="fa fa-sign-in" aria-hidden="true">
                                        </i> Logar 
                                    </button>                        
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="error-summary" style="display:none">
                                    <b>Por favor, corrija os seguintes erros:</b><ul></ul>
                                </div>                    
                            </div>
                        </div>

                    </form>
                </div>
                
            </div>

        </div>
        
    </div>

<script src="js/jquery.js"></script>
<script src="js/yii.js"></script>
<script src="js/yii.validation.js"></script>
<script src="js/yii.activeForm.js"></script>
<script type="text/javascript">jQuery(document).ready(function () {
jQuery('#login-form').yiiActiveForm([{"id":"loginform-login","name":"login","container":".field-loginform-login","input":"#loginform-login","error":".help-block.help-block-error","validateOnChange":false,"validateOnBlur":false,"validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":"Usuário deve ser preenchido"});}},{"id":"loginform-password","name":"password","container":".field-loginform-password","input":"#loginform-password","error":".help-block.help-block-error","validateOnChange":false,"validateOnBlur":false,"validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":"Senha deve ser preenchida"});}},{"id":"loginform-rememberme","name":"rememberMe","container":".field-loginform-rememberme","input":"#loginform-rememberme","error":".help-block.help-block-error","validateOnChange":false,"validateOnBlur":false,"validate":function (attribute, value, messages, deferred, $form) {yii.validation.boolean(value, messages, {"trueValue":"1","falseValue":"0","message":"Remember Me must be either \"1\" or \"0\".","skipOnEmpty":1});}}], {"encodeErrorSummary":false});
});</script></body>
</html>