<?php

include_once 'new_index_header.php';

$sCodigoDaEscola = @$_SESSION["gCodigoDaEscola"];
$vmatric = $_SESSION["gmatric"];
$vano = trim($_SESSION["gano"]);
$codigoContrato = @$_SESSION["gNumeroContrato"];
$tipo_user = $_SESSION["gtipo"];  // Tipo de usuário (Aluno ou Professor ou Funcionário)
$tipo_acesso = $_SESSION["gtipoUsuario"]; // Tipo acesso Aluno, Pai, mãe, responsável

if (isset($_REQUEST['menu'])) {
    $_SESSION["menu"] = $_REQUEST['menu'];
}

//Testando se tem logo para escola específica, se não bota o logo padrão
$logotipoStr = sizeof(explode(":", $sCodigoDaEscola)) == 2 ? "imagens/logotipo" . explode(":", $sCodigoDaEscola)[1] . ".png" : "imagens/logotipo.png";
$logotipoStr = file_exists($logotipoStr) ? $logotipoStr : "imagens/logotipo.png";

$tam_img = getimagesize($logotipoStr);
$v_base = $tam_img[0];
$v_altura = $tam_img[1];
$aumentaBase = 0;

//Para digitacao infantil texto
$vetapa = @$_SESSION["getapa"];

if ($v_altura > 90) {
    while ($v_altura > 90) {
        $v_base -= $v_base * 0.01;
        $v_altura -= $v_altura * 0.01;
    }
    $v_base = (int)$v_base;
} else {
    $v_base = 90;
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="shortcut icon" type="image/png" href="images/icone_300.png"/>

    <title>Gestor Escolar</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/4-col-portfolio.css" rel="stylesheet">
    <link href="./sti_web/simple-calendar-year/b-year-calendar.min.css" rel="stylesheet">
    <script defer src="js/fontawesome-all.js"></script>

    <style>
        .bg-dark {
            background: <?php echo $cor_hex; ?> !important;
            -webkit-box-shadow: 0px 4px 7px 2px rgba(0, 0, 0, 0.15);
            -moz-box-shadow: 0px 4px 7px 2px rgba(0, 0, 0, 0.15);
            box-shadow: 0px 4px 7px 2px rgba(0, 0, 0, 0.15);
        }

        .modal-profile .row-color {
            background: <?php echo $cor_hex; ?> !important;
            color: white;
            display: flex;
            align-items: center;
            border-radius: 5px;
        }

        .modal-profile .row-color h3 {
            margin-bottom: 0px;
            padding: 16px;
            font-size: 0.9em;
            font-weight: bold;
        }

        .modal-profile h6 {
            color: <?php echo $cor_hex; ?>;
        }

        .labels-school {
            padding-left: <?php echo $v_base . "px"; ?>;
            color: white !important;
			font-size: 12px;
        }

        .jqyc-year-chooser {
            display: none;
        }

        .jqyc-th {
            width: 14.28%;
        }

        .jqyc-month {
            height: auto !important;
            display: flex;
            flex-direction: column !important;
            /*justify-content: space-between !important;*/
        }

        .jqyc-months {
            margin-top: 0px !important;
        }

        .jqyc-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0px 40px 16px 40px;
            border-bottom: solid 0.5px #ddd;
            text-align: center;
        }

        .year-label-month {
            margin-bottom: 0px;
            font-size: 0.6em;
            color: #aaaaaa;
        }

        #calendar {
            width: 100% !important;
        }

		#calendarEC {
            width: 100% !important;
        }

        #calendar .jqyc {
            margin-top: 0px;
            margin-bottom: 0px;
        }

        .dayOff {
            color: #ddd;
        }

        .dayOff:hover {
            background: white;
            color: #ddd;
            cursor: default;
        }

        .dayChecked {
            color: <?php echo $cor_hex; ?>;
            font-weight: bold;
        }

        .dayChecked:hover {
            background: <?php echo $cor_hex; ?>;
            border-radius: 10px;
            color: white;
            cursor: pointer;

        }

        .dayChecked.selected {
            background: <?php echo $cor_hex; ?>;
            border-radius: 10px;
            color: white;
            cursor: pointer;
        }

        .jqyc-table {
            margin-bottom: 0px;
        }

        .btn-go-to-frequence {
            background: <?php echo $cor_hex; ?>;
            color: white;
        }

		
        .btnEtapa {
            border: 1px solid;
            border-radius: 4px;
            padding: 5px;
            cursor: pointer;
            margin-left: 5px;
        }
        .etapaclicl {
            background: <?php echo $cor_hex; ?>;
            color: white;
        }
		.card-body {
   			text-align: center;

		}

		.container {
			min-width: 400px;
			margin: 0 auto; 
		}

		@media screen and (min-width: 400px) {
			.container {
				min-width: 100%; /* Para telas menores, remova o limite de largura */
			}
		}


		@media screen and (max-width: 1000px) and (min-width: 500px)  {
			.card-title {
				flex-grow: 1;
				font-size: 2.4vw !important;
				/* word-break: keep-all; */
				word-wrap: normal; 
				margin-left: -28px;
				margin-right: -28px;
				padding: 0;	
				/* white-space: nowrap; */

			}
			.dropdown-toggle {
				font-size: 2vw; 
			}
			
		} 
		
		@media screen and (max-width: 500px) and (min-width: 300px)  {
			.card-title {
				flex-grow: 1;
				font-size: 2.4vw !important;
				/* word-break: keep-all; */
				word-wrap: normal; 
				margin-left: -28px;
				margin-right: -28px;
				padding: 0;	
				/* white-space: nowrap; */

			}
			
			.dropdown-toggle {
				font-size: 2.8vw; 
			}
			
			.navbar-brand{
				max-width: 180px !important; /* Ajuste conforme necessário */
				word-wrap: break-word !important;
				 white-space: normal;
			}
			
			.white-bar{
				display: block;
				background-color: #FFF;
				color: #000;
				width: 100%;
				margin-top: 5px !important;
				height: 35px;
				position: fixed;
				z-index: 1000;
				text-align: center;
			}
		} 
		
		@media screen and (max-width: 300px) and (min-width: 100px)  {
			.card-title {
				flex-grow: 1;
				font-size: 2.5vw !important;
				margin-left: -28px;
				margin-right: -28px;
				padding: 0;	
				/*white-space: nowrap;*/
				word-wrap: normal; 
			}
			
			.dropdown-toggle {
				font-size: 0.8rem; 
			}

		} 

		@media screen and (max-width: 990px) {
			.logo{
				margin-left: 0px !important;
				height: 70px;
			} 

			.row {
				padding-top: 50px;
			}

			.labels-school{
				margin-left: -70px !important;
				padding-top: 0px;
				
			}
			
			.labels-school a {
				font-weight: bold;
				font-size: 1em;
			}

			.white-bar{
				display: block;
				background-color: #FFF;
				color: #000;
				width: 100%;
				margin-top: -10px !important;
				height: 35px;
				position: fixed;
				z-index: 1000;
				text-align: center;
			}

			.btn-secondary {
				color: #808080;
				background-color: #6c757d;
				/* border-color: #6c757d; */
			}

			.dropdown-student button:hover{
				color: #212121 !important;
				border-bottom: solid 1.5px white !important;
			}

			/* .collapse{
				display: block ;
			} */

			#btnSignOut{
				display: block;
				color: #FFF;
				position: absolute;
      			top: 50%;
				transform: translateY(-50%);
				right: 5px;
			}
			
		}

		@media screen and (min-width: 1000px) {
			.white-bar{
				display: none;
			}
			#btnSignOut{
				display: none;
			}
		}
		
		.container,
		.white-bar,
		.navbar{
			min-width: 400px;
		}
    </style>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <div id="left-nav-component" class="left-nav-bar">
            <i class="fa fa-arrow-left" style="color: white"></i>
            <img class="logo" src=<?php echo $logotipoStr ?> alt="">
            <div class="labels-school">
                <a class="navbar-brand" href="#"><?php if ($codigoContrato == 5) $sNomeDaEscola=""; echo($sNomeDaEscola); ?></a>
            </div>
        </div>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <div class="dropdown dropdown-student">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="modal" data-target="#profileModal" aria-haspopup="true" aria-expanded="false">
                            <?php echo $vuser . utf8_decode($naoMatriculado); ?>&nbsp;<i class="fa fa-user"></i>
                        </button>
                    </div>
                </li>
                <li>
                    <button class="btn btn-secondary btn-sign-out" style="padding-top: 10px;" type="button" onclick="location.href = 'logout.php';">
                        <i class="fa fa-sign-out-alt"></i>
                    </button>
                </li>
            </ul>
        </div>
		<button class="btn btn-secondary btn-sign-out" id="btnSignOut" type="button" onclick="location.href = 'logout.php';">
                <i class="fa fa-sign-out-alt"></i>
        </button>
    </div>
</nav>

<div class="white-bar">
	<div class="dropdown dropdown-student">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="modal" data-target="#profileModal" aria-haspopup="true" aria-expanded="false">
            <?php echo $vuser . $naoMatriculado; ?>&nbsp;<i class="fa fa-user"></i>
        </button>
     </div>
</div> 

<div class="container"> <!-- ////////////////////////\/OBJETIVO\///////////////////////// -->
    <div class="row">
        <?php include('new_index_menu.php'); ?> <!-- Principal -->

		<!-- TESTE -->
		<div class="col-lg-3 col-md-3 col-3 portfolio-item">
			<div class="card h-100" onclick="menuClick(18);">
				<a href="#">
					<img class="card-img-top" src="new_cor_btn.php?imagem=images/icons/frequencia_icon.png&rgb=255,128,0," alt>
				</a>
				
				<div class="card-body">
					<h4 class="card-title"> Freq. Extra Curricular</h4>
				</div>
			</div>
		</div>

    </div>
    <!-- Modal Profile -->
    <div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle" style="font-weight: bold">Dados do Usu&aacute;rio</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body modal-profile">
                    <div class="container-fluid">
                        <?php if ($vtipo == "A") { ?>
                            <!-- Bloco somente para Alunos -->
                            <div class="row row-color">
                                <h3> Lota&ccedil;&atilde;o:&nbsp;<?php echo "Escola&nbsp;" . $sCodigoDaEscola . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . substr($v_grau_serie, 3) . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $v_turma . "&nbsp;&nbsp;&nbsp;&nbsp;" . $v_turno; ?></h3>
                            </div>
                        <?php } ?>
                        <div class="row">
                            <div class="col-md-4 image-profile">
                                <img src="./imagens/fotos/<?php echo(file_exists("imagens/fotos/" . $matricula . ".jpg") ? $matricula : "00000000") ?>.jpg"
                                     class="img-thumbnail" width=150px>
                            </div>
                            <div class="col-md-8 flex-col" style="margin-top: 20px;">
                                <div class="row">
                                    <div class="col-md-12 flex-col">
                                        <h6>Nome</h6>
                                        <p><?php echo $vuser . utf8_decode($naoMatriculado); ?>&nbsp;</p>
                                        <h6>Email</h6>
                                        <p><?php echo $aluno_email; ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 flex-col">
                                        <h6>Matr&iacute;cula</h6>
                                        <p><?php echo $matricula; ?></p>
                                    </div>
                                    <div class="col-md-4 flex-col">
                                        <h6>Nascimento</h6>
                                        <p><?php echo $aluno_nascimento; ?></p>
                                    </div>
                                    <div class="col-md-4 flex-col">
                                        <h6>Sexo</h6>
                                        <p><?php echo $aluno_sexo; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row margin-top-32">
                            <div class="col-md-12">
                                <h6>Endere&ccedil;o</h6>
                                <p><?php echo $aluno_endereco; ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <h6>CEP</h6>
                                <p><?php echo $aluno_cep; ?></p>
                            </div>
                            <div class="col-md-2">
                                <h6>UF</h6>
                                <p><?php echo $aluno_uf; ?></p>
                            </div>
                            <div class="col-md-3">
                                <h6>Cidade</h6>
                                <p><?php echo $aluno_cidade; ?></p>
                            </div>
                            <div class="col-md-3" style="padding-left:0px;margin-left:-5px;">
                                <h6>Bairro</h6>
                                <p><?php echo $aluno_bairro; ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <h6>Telefone</h6>
                                <p><?php echo $aluno_telefone; ?></p>
                            </div>
                            <div class="col-md-4">
                                <h6>Celular</h6>
                                <p><?php echo $aluno_celular; ?></p>
                            </div>
                        </div>
                        <?php if ($vtipo == "A") { ?>
                            <!-- Bloco somente para Alunos -->
                            <div class="row row-color margin-top-32">
                                <h3>Dados do Respons&aacute;vel</h3>
                            </div>
                            <div class="row margin-top-32">
                                <div class="col-md-4">
                                    <h6>Nome</h6>
                                    <p><?php echo $responsavel_nome; ?>&nbsp;</p>
                                </div>
                                <div class="col-md-4">
                                    <h6>CPF</h6>
                                    <p><?php echo $responsavel_cpf; ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h6>Email</h6>
                                    <p><?php echo $responsavel_email; ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <h6>Endere&ccedil;o</h6>
                                    <p><?php echo $responsavel_endereco; ?></p>
                                </div>

                                <div class="col-md-auto">
                                    <h6>Bairro</h6>
                                    <p><?php echo $responsavel_bairro; ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <h6>CEP</h6>
                                    <p><?php echo $responsavel_cep; ?></p>
                                </div>
                                <div class="col-md-4">
                                    <h6>Cidade</h6>
                                    <p><?php echo $responsavel_cidade; ?></p>
                                </div>
                                <div class="col-md-4">
                                    <h6>UF</h6>
                                    <p><?php echo $responsavel_uf; ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <h6>Telefone</h6>
                                    <p><?php echo $responsavel_telefone; ?></p>
                                </div>
                                <div class="col-md-4">
                                    <h6>Celular</h6>
                                    <p><?php echo $responsavel_celular; ?></p>
                                </div>
                            </div>
                            <div class="row row-color margin-top-32">
                                <h3>Dados do Pai</h3>
                            </div>
                            <div class="row margin-top-32">
                                <div class="col-md-4">
                                    <h6>Nome</h6>
                                    <p><?php echo $pai_nome; ?></p>
                                </div>
                                <div class="col-md-4">
                                    <h6>CPF</h6>
                                    <p><?php echo $pai_cpf; ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h6>Email</h6>
                                    <p><?php echo $pai_email; ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <h6>Endere&ccedil;o</h6>
                                    <p><?php echo $pai_endereco; ?></p>
                                </div>
                                <div class="col-md-auto">
                                    <h6>Bairro</h6>
                                    <p><?php echo $pai_bairro; ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <h6>CEP</h6>
                                    <p><?php echo $pai_cep; ?></p>
                                </div>
                                <div class="col-md-4">
                                    <h6>Cidade</h6>
                                    <p><?php echo $pai_cidade; ?></p>
                                </div>
                                <div class="col-md-4">
                                    <h6>UF</h6>
                                    <p><?php echo $pai_uf; ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <h6>Telefone</h6>
                                    <p><?php echo $pai_telefone; ?></p>
                                </div>
                                <div class="col-md-4">
                                    <h6>Celular</h6>
                                    <p><?php echo $pai_celular; ?></p>
                                </div>
                            </div>
                            <div class="row row-color margin-top-32">
                                <h3>Dados da M&atilde;e</h3>
                            </div>
                            <div class="row margin-top-32">
                                <div class="col-md-4">
                                    <h6>Nome</h6>
                                    <p><?php echo $mae_nome; ?>&nbsp;</p>
                                </div>
                                <div class="col-md-4">
                                    <h6>CPF</h6>
                                    <p><?php echo $mae_cpf; ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h6>Email</h6>
                                    <p><?php echo $mae_email; ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <h6>Endere&ccedil;o</h6>
                                    <p><?php echo $mae_endereco; ?></p>
                                </div>
                                <div class="col-md-auto">
                                    <h6>Bairro</h6>
                                    <p><?php echo $mae_bairro; ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <h6>CEP</h6>
                                    <p><?php echo $mae_cep; ?></p>
                                </div>
                                <div class="col-md-4">
                                    <h6>Cidade</h6>
                                    <p><?php echo $mae_cidade; ?></p>
                                </div>
                                <div class="col-md-4">
                                    <h6>UF</h6>
                                    <p><?php echo $mae_uf; ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <h6>Telefone</h6>
                                    <p><?php echo $mae_telefone; ?></p>
                                </div>
                                <div class="col-md-4">
                                    <h6>Celular</h6>
                                    <p><?php echo $mae_celular; ?></p>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Alterar Senha -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Alterar senha</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="formsenha" method="post" action="" id="formsenha">
					<div class="alert alert-danger">
						<p style="margin-bottom: 0px">A nova senha deve ter entre 5 e 8 caracteres</p>
					</div>
					<div class="form-group">
						<label for="actual_password">Senha atual</label>
						<input type="password" class="form-control" id="actual_password" maxlength="8">
						<input id="senhaoculta" name="senhaoculta" type="hidden" value="<?php echo $esenha; ?>"/>
						<input id="matriculaoculta" name="matriculaoculta" type="hidden" value="<?php echo $vmatric; ?>"/>
						<!--<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>-->
					</div>
					<div class="form-group">
						<label for="new_password">Nova senha</label>
						<input type="password" class="form-control" id="new_password" maxlength="8">
						<!--<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>-->
					</div>
					<div class="form-group">
						<label for="confirm_new_password">Confirmar nova senha</label>
						<input type="password" class="form-control" id="confirm_new_password" maxlength="8">
					</div>
					<div class="form-check">
						<input type="checkbox" class="form-check-input" onclick="showPasswords();" id="show_passwords">
						<label class="form-check-label" for="show_passwords">Mostrar senhas</label>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary">Alterar</button>
					</div>
					<div id="loading" align="center">
						<img id="loader" src="imagens/loading.gif"/>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="pick-boletim" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Selecione a etapa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id='etapa_select' class="d-flex justify-content-center">

                </div>
                <div id="sbolt" style="display: none;text-align: center;">
                    <h4>Nenhum boletim dispon&iacute;vel</h4>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn etapaclicl" id="btnetapa" onclick="abriboleim();">Continuar</button>
            </div>
        </div>

    </div>
</div>

<!-- Frequência Escolar -->
<div class="modal fade" id="pick-date-frequence" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Selecione a escola e a data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row p-0 g-3 align-items-center mb-3">
            <div class="col-auto">
              <label for="inputState" class="form-label mb-0">Escola:</label>
            </div>
            <div class="col">
              <select id="inputState" class="form-control"></select>
            </div>
        </div>
        <div class="input-group date" id="datetimepicker1">
          <div id="calendar"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-go-to-frequence disabled" id="btn-go-to-frequence" data-date="">Continuar</button>
      </div>
    </div>
  </div>
</div>

<!-- Frequência Extra Curricular -->
<div class="modal fade" id="pick-date-frequenceEC" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Selecione a escola e a data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row p-0 g-3 align-items-center mb-3">
            <div class="col-auto">
              <label for="inputStateEC" class="form-label mb-0">Escola:</label>
            </div>
            <div class="col">
              <select id="inputStateEC" class="form-control"></select>
            </div>
        </div>
        <div class="input-group date" id="datetimepicker1">
          <div id="calendarEC"></div>
        </div>
      </div>
      <div class="modal-footer">
		 <button class="btn btn-go-to-frequenceEC disabledEC" id="btn-go-to-frequenceEC" data-dateEC="">Continuar</button>
      </div>
    </div>
  </div>
</div>

</div>
	<script src="vendor/jquery/jquery.min.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script type="text/javascript" src="./sti_web/datepicker/moment.min.js"></script>
	<script src="./sti_web/simple-calendar-year/b-year-calendar.js"></script>
	<script type="text/javascript" src="js/jquery.validate.js"></script>
	<script>

		var mData = '';

		function menuClick(option) {
			switch (option) {
				case 3:
					getEtapasboletim()
					$('#pick-boletim').modal('show');
				break;
				case 17:

					$('#calendar').calendar({
						showHeaders: true,
						startYear: <?php echo substr($vano, 0, 4); ?>,
						cols: 12,
						colsSm: 12,
						colsMd: 12,
						colsLg: 12,
						colsXl: 12,
						l10n: {
							jan: "Janeiro",
							feb: "Fevereiro",
							mar: "Março",
							apr: "Abril",
							may: "Maio",
							jun: "Junho",
							jul: "Julho",
							aug: "Agosto",
							sep: "Setembro",
							oct: "Outubro",
							nov: "Novembro",
							dec: "Dezembro",
							su: 'Dom',
							mn: "Seg",
							tu: "Ter",
							we: 'Qua',
							th: 'Qui',
							fr: 'Sex',
							sa: 'Sáb',
						}
					});
					$("#Bcalendar").attr("onclick","goToClassesFrequence(this.id)");
					$("#pick-date-frequence").modal();
					doEstruturesMonths();
					showJustOneMonth(getNumberActualMonth());
					//self.location.href = "webview.php?link=frequencia_turmas_web.php?menu=17';"
				break;

				case 18:

					$('#calendarEC').calendar({
						showHeaders: true,
						startYear: <?php echo substr($vano, 0, 4); ?>,
						cols: 12,
						colsSm: 12,
						colsMd: 12,
						colsLg: 12,
						colsXl: 12,
						l10n: {
							jan: "Janeiro",
							feb: "Fevereiro",
							mar: "Março",
							apr: "Abril",
							may: "Maio",
							jun: "Junho",
							jul: "Julho",
							aug: "Agosto",
							sep: "Setembro",
							oct: "Outubro",
							nov: "Novembro",
							dec: "Dezembro",
							su: 'Dom',
							mn: "Seg",
							tu: "Ter",
							we: 'Qua',
							th: 'Qui',
							fr: 'Sex',
							sa: 'Sáb',
						}
					});
					$("#Bcalendar").attr("onclick","goToClassesFrequence(this.id)");
					$("#pick-date-frequenceEC").modal();
					doEstruturesMonthsEC();
					showJustOneMonth(getNumberActualMonth());
					//self.location.href = "webview.php?link=frequencia_turmas_web.php?menu=18';"
				break;

				case 91:

					$('#calendar').calendar({
						showHeaders: true,
						cols: 12,
						colsSm: 12,
						colsMd: 12,
						colsLg: 12,
						colsXl: 12,
						l10n: {
							jan: "Janeiro",
							feb: "Fevereiro",
							mar: "Março",
							apr: "Abril",
							may: "Maio",
							jun: "Junho",
							jul: "Julho",
							aug: "Agosto",
							sep: "Setembro",
							oct: "Outubro",
							nov: "Novembro",
							dec: "Dezembro",
							su: 'Dom',
							mn: "Seg",
							tu: "Ter",
							we: 'Qua',
							th: 'Qui',
							fr: 'Sex',
							sa: 'Sáb',
						}
					});
					$("#Bcalendar").attr("onclick","goToClassesSTI(this.id)");
					$("#pick-date-frequence").modal();
					doEstruturesMonths();
					showJustOneMonth(getNumberActualMonth());

				break;

				case 14:
					const params = new URLSearchParams({
            matricula_aluno: "<?php echo $matricula; ?>",
            etapa: "<?php echo substr($vetapa, 0, 1); ?>",
            nome_aluno: "<?php echo trim($vuser . utf8_decode($naoMatriculado)); ?>",
            serie: "<?php echo trim(substr($v_grau_serie, 3)); ?>",
            turma: "<?php echo substr($v_turma, 6); ?>",
            turno_curto: "<?php echo substr($v_turno, 0, 1); ?>",
            ano: "<?php echo str_replace('/', '', $vano); ?>",
            grau_serie: "<?php echo substr($v_grau_serie, 0, 2); ?>",
            turno: "<?php echo $v_turno; ?>"
          });
          const url = "infantil_aluno_texto_view.php?" + params.toString();
          window.open(url);
        break;

			}
			return false;
		}

		function htmlToElement(html) {
			var template = document.createElement('template');
			html = html.trim(); // Never return a text node of whitespace as the result
			template.innerHTML = html;
			return template.content.firstChild;
		}

		function getNumberActualMonth() {
			var d = new Date();
			return (d.getMonth() + 1);
		}

		function getWeekDay(d, m, y) {

			var intMonth = parseInt(m) - 1;
			var dataOn = new Date(y, intMonth, d, 0, 0, 0, 0)
			return dataOn.getDay();
		}

		function getNameMonth(m) {
			var month = new Array();
			month[0] = "Janeiro";
			month[1] = "Fevereiro";
			month[2] = "Março";
			month[3] = "Abril";
			month[4] = "Maio";
			month[5] = "Junho";
			month[6] = "Julho";
			month[7] = "Agosto";
			month[8] = "Setembro";
			month[9] = "Outubro";
			month[10] = "Novembro";
			month[11] = "Dezembro";
			var n = month[m];
			return n;
		}

		function getSimpleNameActualMonth() {
			var d = new Date();
			var month = new Array();
			month[0] = "jan";
			month[1] = "feb";
			month[2] = "mar";
			month[3] = "apr";
			month[4] = "may";
			month[5] = "jun";
			month[6] = "jul";
			month[7] = "aug";
			month[8] = "sep";
			month[9] = "oct";
			month[10] = "nov";
			month[11] = "dec";
			var n = month[d.getMonth()];
			return n;
		}

		function beforeMonth(month) {
			showJustOneMonth(month - 1);
		}

		function nextMonth(month) {
			showJustOneMonth(month + 1);
		}

		function encode_utf8(s) {
			return unescape(encodeURIComponent(s));
		}

		function decode_utf8(s) {
			return decodeURIComponent(escape(s));
		}

		//////////////////////////---------priori---------///////////////////////////////////////////

    	// Ao clicar no botão de continuar na janela modal da frequência, a função abaixo é chamada para redirecionar para a página de frequência
		function goToFrequencePage() {
      	const codigo_escola = $("#inputState").val();
      	const date = $("#btn-go-to-frequence").attr("data-date");
      
       	// '||' é o separador de parâmetros na URL pois infelizmente somos reféns do 'webview.php';
			self.location.href = `webview.php?link=frequencia.php?params=${codigo_escola}||${date}`;
		}

		function goToFrequenceECpage() {
      	const codigo_escolaEC = $("#inputStateEC").val();
      	const dateEC = $("#btn-go-to-frequenceEC").attr("data-dateEC");
      
       	// '||' é o separador de parâmetros na URL pois infelizmente somos reféns do 'webview.php';
			self.location.href = `webview.php?link=frequencia_ec.php?params=${codigo_escolaEC}||${dateEC}`;
		}


		///////////////////////////---------priori-----------//////////////////////////////////////////

		function goToClassesSTI(id) {
			var splittedDate = id.split("-")
			var startAno = <?php echo substr($vano, 0, 4); ?>;
			var key = getWeekDay(splittedDate[0], splittedDate[1], startAno);
			var turmas = mData.dias_turmas
			var entries = Object.entries(turmas);
			var classesToSend = '';
			//console.log("KEY: " + key);
			for (var i = 0; i < entries.length; i++) {
				var objArray = entries[i];
				if (objArray[0] == key) {
					classesToSend = objArray[1];
					break;
				}
			}

			var monthParam = splittedDate[1];
			if (monthParam.length == 1) {
				monthParam = "0" + monthParam;
			}
			var startAno = <?php echo substr($vano, 0, 4); ?>;
			var dataParam = startAno + "-" + monthParam + "-" + splittedDate[0];

			localStorage.setItem("turmas", JSON.stringify(classesToSend));
			localStorage.setItem("etapa", mData.etapa);
			self.location.href = "webview.php?link=sti_view.php?data_f=" + dataParam;
		}

		///FREQUENCIA TRADICIONAL
		function showJustOneMonth(month) {
			$(" .jqyc-month").css("display", "none");
			$(" .jqyc-month[data-month='" + month + "']").css("display", "flex");
			var labelMonth = getNameMonth(parseInt(month) - 1);
			var actualYear = <?php echo substr($vano, 0, 4); ?>;

			if (month == 1) {
				$(".jqyc-header").html("<i class='fas fa-arrow-left action-month' onclick='beforeMonth(" + month + ")' style='cursor: pointer; visibility: hidden'></i><div><div>" + decode_utf8(labelMonth) + "</div><p class='year-label-month'>" + actualYear + "</p></div><i onclick='nextMonth(" + month + ")' style='cursor: pointer' class='fas fa-arrow-right'></i>")
			} else if (month == 12) {
				$(".jqyc-header").html("<i class='fas fa-arrow-left action-month' onclick='beforeMonth(" + month + ")' style='cursor: pointer'></i><div><div>" + decode_utf8(labelMonth) + "</div><p class='year-label-month'>" + actualYear + "</p></div><i onclick='nextMonth(" + month + ")' style='cursor: pointer; visibility: hidden;' class='fas fa-arrow-right'></i>")
			} else {
				$(".jqyc-header").html("<i class='fas fa-arrow-left action-month' onclick='beforeMonth(" + month + ")' style='cursor: pointer'></i><div><div>" + decode_utf8(labelMonth) + "</div><p class='year-label-month'>" + actualYear + "</p></div><i onclick='nextMonth(" + month + ")' style='cursor: pointer;' class='fas fa-arrow-right'></i>")
			}
		}

		//FREQUENCIA TRADICIONAL
		async function doEstruturesMonths() {
			var elementsHeaders = document.getElementsByClassName("jqyc-header");
			var elementsMonths = document.getElementsByClassName("jqyc-month");

			for (var i = 0; i < elementsMonths.length; i++) {
				var ulMarkedDays = "<ul id='days-list-month-" + (i + 1) + "' class='invisible days-list-month days-list-month-" + (i + 1) + "'>";
				ulMarkedDays += "</ul>";

				var element_html = htmlToElement(ulMarkedDays);
				$(elementsMonths[i]).append(element_html)
			}

			$(".jqyc-month").css('height', 'auto');

			//Dando um pai para a table
			var elementsTablesMonths = document.getElementsByClassName("jqyc-table");
			for (var i = 0; i < elementsTablesMonths.length; i++) {
				var elementHeader = elementsHeaders[i];
				var elementTable = elementsTablesMonths[i];

				var parent = elementTable.parentNode;
				var wrapper = document.createElement('div');
				parent.replaceChild(wrapper, elementHeader);
				wrapper.appendChild(elementHeader);
				parent.replaceChild(wrapper, elementTable);
				wrapper.appendChild(elementTable);

			}

			var elementsDaysWeekMonths = document.getElementsByClassName("jqyc-th");
			for (var i = 0; i < elementsDaysWeekMonths.length; i++) {
				$(elementsDaysWeekMonths[i]).html(decode_utf8($(elementsDaysWeekMonths[i]).html()))
				//console.log($(elementsDaysWeekMonths[i]).html())
			}

      // Adiciona a classe dayOff para todos os dias
			$(".jqyc-not-empty-td").addClass("dayOff");

      // Busca as escolas do professor
      const escolas = await getSchoolsProf();

      // Monta o select de escolas na janela modal da frequencia
      mountSelectEscolas(escolas);

      // Busca as datas que o professor tem aula com base na primeira escola recebida
      const data = await getDates(escolas[0].codigo_escola);

			markAllDays(data);
		}

		// Função Estrutura do EC -----------------------------///////////////////////////////////////
		async function doEstruturesMonthsEC() {
			var elementsHeaders = document.getElementsByClassName("jqyc-header");
			var elementsMonths = document.getElementsByClassName("jqyc-month");

			for (var i = 0; i < elementsMonths.length; i++) {
				var ulMarkedDays = "<ul id='days-list-month-" + (i + 1) + "' class='invisible days-list-month days-list-month-" + (i + 1) + "'>";
				ulMarkedDays += "</ul>";

				var element_html = htmlToElement(ulMarkedDays);
				$(elementsMonths[i]).append(element_html)
			}

			$(".jqyc-month").css('height', 'auto');

			//Dando um pai para a table
			var elementsTablesMonths = document.getElementsByClassName("jqyc-table");
			for (var i = 0; i < elementsTablesMonths.length; i++) {
				var elementHeader = elementsHeaders[i];
				var elementTable = elementsTablesMonths[i];

				var parent = elementTable.parentNode;
				var wrapper = document.createElement('div');
				parent.replaceChild(wrapper, elementHeader);
				wrapper.appendChild(elementHeader);
				parent.replaceChild(wrapper, elementTable);
				wrapper.appendChild(elementTable);

			}

			var elementsDaysWeekMonths = document.getElementsByClassName("jqyc-th");
			for (var i = 0; i < elementsDaysWeekMonths.length; i++) {
				$(elementsDaysWeekMonths[i]).html(decode_utf8($(elementsDaysWeekMonths[i]).html()))
				//console.log($(elementsDaysWeekMonths[i]).html())
			}

      // Adiciona a classe dayOff para todos os dias
			$(".jqyc-not-empty-td").addClass("dayOff");

      // Busca as escolas do professor
      const escolas = await getSchoolsProf();

      // Monta o select de escolas na janela modal da frequencia
      mountSelectEscolasEC(escolas);

      // Busca as datas que o professor tem aula com base na primeira escola recebida
      const data = await getDates(escolas[0].codigo_escola);

			markAllDays(data);
		}

		/////////////////////////////////---------------//////////////////////////////////

    // Evento de clique ao marcar um dia no calendário da frequência
		$(document).on("click", ".jqyc-td.dayChecked", function (event) {
        const year = $(this).data("year");
        const month = $(this).data("month");
		const day = $(this).data("day-of-month");

			$(".jqyc-td.dayChecked").removeClass("selected");
			$(".jqyc-not-empty-td.jqyc-day-" + day + ".jqyc-day-of-" + month + "-month").addClass("selected");

		const monthFormated = String($(this).data("month")).padStart(2, '0');
		const dayFormated = String($(this).data("day-of-month")).padStart(2, '0');
		const date = `${year}-${monthFormated}-${dayFormated}`;
		const dateEC = `${year}-${monthFormated}-${dayFormated}`;

			$("#btn-go-to-frequence").removeClass("disabled").attr("data-date", date).off("click").on("click", () => goToFrequencePage());
			$("#btn-go-to-frequenceEC").removeClass("disabledEC").attr("data-dateEC", dateEC).off("click").on("click", () => goToFrequenceECpage());
		})

    // Função na qual monta o select de escolas na janela modal da frequencia
    function mountSelectEscolas(data){
      $("#inputState").empty();
      data.forEach(escola => {
        $("#inputState").append(`<option value="${escola.codigo_escola}">${escola.codigo_escola} - ${escola.nome_fantasia}</option>`);
      });

      $("#inputState").off("change").on("change", async function(){
        const codigo_escola = $(this).val();
        const data = await getDates(codigo_escola);
        markAllDays(data);
      });
		}

	function mountSelectEscolasEC(dataEC){
	  $("#inputStateEC").empty();
	  dataEC.forEach(escola => {
		$("#inputStateEC").append(`<option value="${escola.codigo_escola}">${escola.codigo_escola} - ${escola.nome_fantasia}</option>`);
	  });

	  $("#inputStateEC").off("change").on("change", async function(){
		const codigo_escola = $(this).val();
		const data = await getDates(codigo_escola);
		markAllDays(data);
	  });
	}

    // Ao clicar no menu da frequência, a função abaixo é chamada para buscar as escolas do professor
    async function getSchoolsProf() {
      try {
        const prof_matricula = "<?php echo $vmatric; ?>";

        const response = await fetch(`./Controller/frequencia_controller.php?action=getSchoolsProf&matricula=${prof_matricula}`, {
          method: "GET",
          headers: {
            "Content-Type": "application/json"
          }
        });

        const data = await response.json();

        if(!response.ok) throw new Error(data.errorLog ?? data.message ?? 'Exceção não tratada');

        return data;
      } catch (err) {
        console.error(`Erro ao buscar as escolas do professor: ${err.message}`);
      }
    }

    // Retorna as datas que o professor tem aula com base na escola informada
    async function getDates(codigo_escola){
      try {
        const prof_matricula = "<?php echo $vmatric; ?>";

        const response = await fetch(`./Controller/frequencia_controller.php?action=getDates&matricula=${prof_matricula}&codigo_escola=${codigo_escola}`, {
          method: "GET",
          headers: {
            "Content-Type": "application/json"
          }
        });

        const data = await response.json();

        if(!response.ok) throw new Error(data.errorLog ?? data.message ?? 'Exceção não tratada');

        return data;
      } catch (err) {
        console.error(`Erro ao buscar as datas que o professor tem aula com base na escola '${codigo_escola}': ${err.message}`);
      }
    }

    // Função na qual marca o dia no calendário
		function markDay(day, month) {
			//console.log(day + "/" + month)
			if (day.startsWith("0")) {
				day = day.substring(1);
			}
			if (month.startsWith("0")) {
				month = month.substring(1);
			}
			$(".jqyc-not-empty-td.jqyc-day-" + day + ".jqyc-day-of-" + month + "-month").removeClass("dayOff");
			$(".jqyc-not-empty-td.jqyc-day-" + day + ".jqyc-day-of-" + month + "-month").addClass("dayChecked");
		}
		
    // Função na qual marca todos os dias que o professor tem aula
	function markAllDays(data) {
		$('.dayChecked').addClass('dayOff');
		$('.dayChecked').removeClass('dayChecked');

      data.forEach(data => {
        const [ year, month, day ] = data.split("-");
        markDay(day, month);
      });
		}

		function mountEtapas(etapa) {
			var itens = document.getElementById("etapa_select");
			while (itens.firstChild) {
				itens.removeChild(itens.firstChild);
			}
			for (let index = 0; index < etapa.length; index++) {
				const element = etapa[index];
				//var estapas = "<div onclick='selectetapa(" + element.etapa + ")' id='et" + element.etapa + "' class= 'btnEtapa'>" + element.nome_longo + " </div>";
				var estapas = "<div onclick=selectetapa('" + element.etapa + "') id='et" + element.etapa + "' class= 'btnEtapa'>" + element.nome_longo + " </div>";
				var element_html = htmlToElement(estapas);
				$("#etapa_select").append(element_html)
			}
		}

		function selectetapa(etapa) {
			$(".btnEtapa").removeClass("etapaclicl");
			$("#et" + etapa).addClass("etapaclicl");
			$("#btnetapa").attr("onclick", "abriboleim('" + etapa + "')");
		}

    function getEtapasboletim() {
			$.ajax({
				type: "GET",
				url: "./ws_controller.php",
				data: {
					action: 'getEtapasboletim',
					matricula: '<?php echo $vmatric; ?>',
				},
				success: function (data) {
					var etapa = JSON.parse(data);
					if (etapa.length > 0) {

						mountEtapas(etapa)
					} else {
						$("#sbolt").show();
						$("#btnetapa").hide();
					}
				},
				error: function (xhr, status, error) {
					var err = eval("(" + xhr.responseText + ")");
					alert(err.Message);
				}
			});
		}

		function abriboleim(etapa) {
			if (etapa) {
				//self.location.href = "webview.php?link=boletim_view30.php?etapa=" + etapa;
				self.location.href = "webview.php?link=boletim_view.php?etapa=" + etapa;
			} else {
				alert("selecione uma etapa para abrir o boletim")
			}
		}

		function dataAtualFormatada() {
			var data = new Date(),
				dia = data.getDate().toString(),
				diaF = (dia.length == 1) ? '0' + dia : dia,
				mes = (data.getMonth() + 1).toString(), //+1 pois no getMonth Janeiro começa com zero.
				mesF = (mes.length == 1) ? '0' + mes : mes,
				anoF = data.getFullYear();
			return diaF + "/" + mesF + "/" + anoF;
		}

		window.onload = function () {
			getPixPagamentos();
		}

		function getPixPagamentos() {
			$.ajax({
				type: "GET",
				url: "pix_pgto_matricula.php",

				success: function (data) {
				},
				error: function (xhr, status, error) {
					ret = false;
					var err = eval("(" + xhr.responseText + ")");
					alert(err.Message);
				}
			});
		}

		function showPasswords() {
			var actual_password = document.getElementById("actual_password");
			var new_password = document.getElementById("new_password");
			var confirm_new_password = document.getElementById("confirm_new_password");
			if (actual_password.type === "password") {
				actual_password.type = "text";
				new_password.type = "text";
				confirm_new_password.type = "text";
			} else {
				actual_password.type = "password";
				new_password.type = "password";
				confirm_new_password.type = "password";
			}
		}
	</script>
	<script type="text/javascript">
		jQuery.validator.addMethod("diferente", function (value, element, param) {
			return value != $(param).val();
		}, "Diferencie Senha de Matrícula");

		$(document).ready(function () {
			$('#formsenha').ajaxStart(function () {
				$('#loading').show();
			});
			$('#formsenha').ajaxStop(function () {
				$('#loading').hide();
			});
			$('#formsenha').validate({
				rules: {
					actual_password: {
						required: true,
						equalTo: "#senhaoculta"
					},
					new_password: {
						required: true,
						minlength: 5,
						diferente: "#matriculaoculta"
					},
					confirm_new_password: {
						required: true,
						equalTo: "#new_password"
					}
				},
				messages: {
					actual_password: {
						required: 'Digite a senha atual',
						equalTo: 'Senha atual inválida'
					},
					new_password: {
						required: 'Digite a nova senha',
						minlength: 'Digite no mínimo 5 caracteres'
					},
					confirm_new_password: {
						required: 'Confirme a nova senha',
						equalTo: 'Nova senha diferente'
					}
				},
				submitHandler: function (form) {
					var dados = $(form).serialize();
					$.ajax({
						type: "POST",
						url: "alunos_senha_rec.php",
						data: dados,
						success: function (data) {
							alert(data);
							$(form)[0].reset();
						}
					});
					return false;
				}
			});
		});
	</script>
</body>

</html>  