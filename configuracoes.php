<?php
    session_start();
    $sessaoUsuario = $_SESSION["user"];
    $sessaoSenha = $_SESSION["password"];
    if (!isset($sessaoUsuario) || !isset($sessaoSenha)) {
        header("Location: login.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
    
        <title> Configurações </title>
        
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/site.css" rel="stylesheet">
        <style>
            #w0{
                background-color: #0E2F44;
            }
        </style>
    </head>

    <body>
        
        <div class="wrap">
            <nav id="w0" class="navbar-inverse navbar-fixed-top navbar" role="navigation">
                <div class="container">

                    <!-- Div usada para categorizar as ocorrencias -->
                    <div id="painel-preencher-ocorrencia"> </div>

                    <div id = "w0-collapse" class ="collapse navbar-collapse">
                        <ul class="navbar-nav navbar-center nav">
                            <li>
                                <a href="#"> <img src= "img/conf.png"> </a>
                            </li>                           
                            <li>
                                <a href="relatorio.php"> <img src = "img/report.png"> </a>
                            </li>
                            <li>
                                <a href="logout.php"> <img src="img/exit.png"> </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
                
            <div class="container">
                
                <h1> Tempo de permanência dos marcadores no mapa </h1>

                <form id="formConfiguracaoUsuario" action = "map.php" method="post">
                    <div class='row'>
                        <div class='col-md-8'>
                            <div class="form-group field-usuario-tempopermanenciamarcadores required">
                                <label class="control-label" for="usuario-tempopermanenciamarcadores">Tempo de Permanência dos Marcadores no Mapa</label>
                                <input type="number" id="usuario-tempopermanenciamarcadores" class="form-control" name="tempoPermanenciaMarcadores" min = "1" value="">

                                <div class="help-block"> </div>
                            </div>
                            <p> A partir do momento em que um alerta for gerado, este será o tempo <code>(em horas)</code> em que o mesmo permanecerá visível no mapa.</p>
                        </div>
                    </div>

                    <div class='row'>
                        <div class='col-md-12'>
                            <button type="submit" class="btn btn-primary" onclick="updateConfiguracao()">Salvar </button>
                            <button type="submit" class="btn btn-danger" style="margin-left:15px;" onclick="updateConfiguracao(1000)">Restaurar Configuração Original</button>
                        </div>
                    </div>
                </form>

                <div class='row'>
                    <hr>
                </div>

                <h1>Bloqueios</h1>

                <table class="table tabela">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Login</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td colspan='8'>Não existem usuários bloqueados.</td></tr>        </tbody>
                </table>
                
            </div>
                
        </div>

<!--            <div id="modalConfiguracoesSistema" class="fade modal" role="dialog" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <b>Configurações</b>
                        </div>      
                        <div class="modal-body">
                            <form id="formConfiguracao" action="/web/index.php" method="get">
                                <input type="hidden" name="r" value="configuracao/update-configuracao">
                                <div class='row'>
                                    <div class='col-md-6'>
                                        <div class="form-group field-configuracao-tempoesperaparanovoalerta required">
                                            <label class="control-label" for="configuracao-tempoesperaparanovoalerta">Tempo de Espera para um novo Alerta</label>
                                            <input type="time" id="configuracao-tempoesperaparanovoalerta" class="form-control" name="tempoEsperaParaNovoAlerta" value="00:01:00" step="1">
                                            <div class="help-block"></div>
                                        </div>
                                        <p> Tempo mínimo que um usuário deverá aguardar se quiser emitir um outro alerta (no caso de ele já haver emitido um).</p>
                                    </div>
                                    <div class='col-md-6'>
                                        <div class="form-group field-configuracao-toleranciaalertasfalsos required">
                                            <label class="control-label" for="configuracao-toleranciaalertasfalsos">Tolerância de Alertas Falsos</label>
                                            <input type="number" id="configuracao-toleranciaalertasfalsos" class="form-control" name="toleranciaAlertasFalsos" value="1" min="0">
                                            <div class="help-block"></div>
                                        </div>
                                        <p> Quantidade máxima de Alertas falsos permitida por usuário. Se esta quantidade for ultrapassada, o usuário será bloqueado.</p>
                                    </div>
                                </div>
                                <div class='row'> 
                                    <hr>
                                </div>
                                <div class='row'>
                                    <div class='col-md-12'>
                                        <button type="submit" class="btn btn-primary">Salvar</button>
                                        <a class="btn btn-danger" href="/web/index.php?r=configuracao%2Frestaurar-configuracao" style="margin-left:15px;">Restaurar Configurações Originais</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div id="modalConfiguracoesUsuario" class="fade modal" role="dialog" tabindex="-1">
                <div class="modal-dialog ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <b>Minhas Preferências</b>
                        </div>
                        <div class="modal-body">
                            <form id="formConfiguracaoUsuario" action="/web/index.php" method="get">
                                <input type="hidden" name="r" value="usuario/update-configuracao">
                                <div class='row'>
                                    <div class='col-md-8'>
                                        <div class="form-group field-usuario-tempopermanenciamarcadores required">
                                            <label class="control-label" for="usuario-tempopermanenciamarcadores">Tempo de Permanência dos Marcadores no Mapa</label>
                                            <input type="number" id="usuario-tempopermanenciamarcadores" class="form-control" name="tempoPermanenciaMarcadores" value="1000">

                                            <div class="help-block"> </div>
                                        </div>
                                        <p> A partir do momento em que um alerta for gerado, este será o tempo <code>(em horas)</code> em que o mesmo permanecerá visível no mapa.</p>
                                    </div>
                                    <div class='col-md-4'>
                                        <label>Zoom do Mapa</label>
                                        <input form ='formConfiguracaoUsuario' id='inputZoomMapa' name='zoomMapa' type='range' min='0' max='20' value='16'>
                                        <p id='labelZoomMapa' style='text-align:center;'>16</p>
                                    </div>
                                </div>
                                <div class='row'>
                                    <hr>
                                </div>
                                <div class='row'>
                                    <div class='col-md-6'>
                                        <div class="form-group field-usuario-janelaalertas required">

                                            <input type="hidden" name="janelaAlertas" value="0"><label><input type="checkbox" id="usuario-janelaalertas" name="janelaAlertas" value="1" checked> Exibir janela de alertas</label>

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class='col-md-6'>
                                        <div class="form-group field-usuario-exibiralertasatendidos required">

                                            <input type="hidden" name="exibirAlertasAtendidos" value="0">
                                            <label>
                                                <input type="checkbox" id="usuario-exibiralertasatendidos" name="exibirAlertasAtendidos" value="1" checked> Exibir alertas atendidos
                                            </label>

                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class='row'>
                                    <hr>
                                </div>
                                <div class='row'>
                                    <div class='col-md-12'>
                                        <button type="submit" class="btn btn-primary">Salvar </button>
                                        <a class="btn btn-danger" href="/web/index.php?r=usuario%2Frestaurar-configuracao" style="margin-left:15px;">Restaurar Configurações Originais</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>-->

        <script src="js/jquery.js"></script>
        <script src="js/yii.js"></script>
        <script src="js/yii.validation.js"></script>
        <script src="js/yii.activeForm.js"></script>
        <script>

            function updateConfiguracao(tempo){

                if (tempo == null || !tempo){ // salvar valor diferente do default

                    var tempo = document.getElementById("usuario-tempopermanenciamarcadores").value;

                    tempo = Math.abs(tempo);

                    localStorage.setItem("tempo", tempo);
                }
                else { // valor default

                    localStorage.setItem("tempo", "1000");
                }
            }

            document.getElementById("usuario-tempopermanenciamarcadores").value = localStorage.getItem("tempo");

            jQuery('#formConfiguracaoUsuario').yiiActiveForm([{"id":"usuario-tempopermanenciamarcadores","name":"tempoPermanenciaMarcadores","container":".field-usuario-tempopermanenciamarcadores","input":"#usuario-tempopermanenciamarcadores","validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":"Informe o tempo de permanência dos marcadores no mapa (em horas)"});yii.validation.number(value, messages, {"pattern":/^\s*[+-]?\d+\s*$/,"message":"Forneça um número inteiro para este campo","skipOnEmpty":1});}}], []);
        </script>
    </body>
</html>
