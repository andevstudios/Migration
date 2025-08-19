<?php
// Controle das variáveis de sessão e passagem de parâmetros
@session_start();

$matricula = isset($_SESSION["gmatric"]) ? $_SESSION["gmatric"] : (isset($_REQUEST["matricula"]) ? $_REQUEST["matricula"] : null);
$senha = isset($_SESSION["gsenha"]) ? $_SESSION["gsenha"] : (isset($_REQUEST["senha"]) ? $_REQUEST["senha"] : null);
$params = isset($_REQUEST["params"]) ? explode("||", $_REQUEST["params"]) : null; ;
$codigo_escola = isset($params[0]) && !empty($params[0]) ? $params[0] : null;
$data = isset($params[1]) && !empty($params[1]) ? substr($params[1], 0, 10) : null;

$titulo = "FREQUÊNCIA EXTRA CURRICULAR";
$namePage = "frequencia";

// Inclui o header
require_once "./includes/header.php";

// Busca as informações necessárias para página
try {
  if(is_null($matricula)) throw new Exception("Matrícula não encontrada!");
  if(is_null($codigo_escola)) throw new Exception("Código da escola não encontrado!");
  if(is_null($data)) throw new Exception("Data não encontrada!");

  $query = "SELECT ano_corrente,
              (SELECT numero_contrato FROM computex WHERE 1) AS numero_contrato,
              (SELECT tipo FROM alunos WHERE matricula = $matricula) AS tipo
          FROM monitmen WHERE 1; ";

  $result = mysqli_query($conn, $query);

  if(!$result) throw new Exception("Erro ao buscar informações necessárias para funcionamento da página: " . mysqli_error($conn));

  if($result -> num_rows === 0) throw new Exception("Informações necessárias para funcionanmento da página não encontradas");

  $row = $result -> fetch_assoc();

  // Define as variáveis
  $ano_corrente = $row["ano_corrente"]; $numero_contrato = str_pad($row["numero_contrato"], 3, "0", STR_PAD_LEFT); $tipo_user = $row["tipo"];

  // Valida as variáveis
  function verifyVariable($str) {
    return $str === null || trim($str) === "";
  }

  // Verifica as informações necessárias para o funcionamento da página
  if(verifyVariable($ano_corrente) || verifyVariable($numero_contrato) || verifyVariable($tipo_user)) throw new Exception("Há informações necessárias para funcionamento da página inválidas");

  // Verifica o acesso
  if($tipo_user !== "P") throw new Exception("Acesso não autorizado");

  $conn->close();
} catch (Exception $e) {
  echo '<div class="d-flex align-items-center justify-content-center vh-100">' . $e->getMessage() . '</div>';
  exit;
}

//HTML agora °-°
?>

<main class="container-main">
  <section class="AdjustPosition">
    <section class="d-none" id="turmas-page">
      <!-- Header -->
      <div class="row g-3 align-items-end mb-3">
        <div class="col-md-auto">
          <label for="filterSchool" class="form-label fw-bold">Escola:</label>
          <select class="form-control" id="filterSchool" disabled></select>
        </div>
        <div class="col-4 col-md-auto">
          <label for="filterSerie" class="form-label fw-bold">Série:</label>
          <select class="form-select" id="filterSerie"></select>
        </div>
        <div class="col-4 col-md-auto">
          <label for="filterTurno" class="form-label fw-bold">Turno:</label>
          <select class="form-select" id="filterTurno"></select>
        </div>
        <div class="col-4 col-md-auto">
          <label for="filterTurm" class="form-label fw-bold">Turma:</label>
          <select class="form-select" id="filterTurm"></select>
        </div>
        <div class="col col-md-auto">
          <label for="filterDisc" class="form-label fw-bold">Disciplina:</label>
          <select class="form-select" id="filterDisc"></select>
        </div>
        <div class="col-auto col-md">
          <div class="d-flex justify-content-md-end">
            <button type="button" class="btn bg-cor-escola" id="btn-dailyClass-filter">Diário de classe (filtro)</button>
          </div>
        </div>
      </div>

      <!-- Turmas -->
      <div class="row g-3 align-items-center">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped table-hover">
                  <thead>
                    <tr class="text-center">
                      <th scope="col">Horário</th>
                      <th scope="col">Grau Série</th>
                      <th scope="col">Turno</th>
                      <th scope="col">Turma</th>
                      <th scope="col">Etapa</th>
                      <th scope="col">Disciplina</th>
                      <th scope="col" class="copyTd">Ações</th>
                      <th scope="col" class="copyTd"></th>
                    </tr>
                  </thead>
                  <tbody id="turmas-table-body"></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </section>

  <section class="d-none" id="alunos-page">
    <!-- Header -->
    <div class="row g-3 align-items-center justify-content-between mb-3">
      <div class="col-auto">
        <button type="button" id="btn-back-page" class="btn btn-light"><i class="bi bi-arrow-left fs-5"></i></button>
      </div>
      <div class="col-auto">
        <div class="card">
          <div class="card-body">
            <div class="d-flex fs-6 gap-1">
              <p class="mb-0">Presenças: <span class="fw-bold" id="total-presence">0</span></p>
              <p class="mb-0">Faltas: <span class="fw-bold" id="total-fouls">0</span></p>
              <p class="mb-0">Justificadas: <span class="fw-bold" id="total-justified">0</span></p>
              <p class="mb-0">P/T: <span class="fw-bold" id="porcentage">0</span>%</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabela -->
    <div class="row g-3 align-items-center mb-1">
      <div class="col-12">
        <div class="table-responsive">
          <div class="card">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped table-hover">
                  <thead>
                    <tr class="align-middle">
                      <th class="w-1" scope="col">Foto</th>
                      <th scope="col" class="text-center">Seq.</th>
                      <th scope="col">Aluno</th>
                      <th scope="col" class="text-center">
                        <div class="d-flex flex-column gap-1 text-center">
                          <div class="d-flex justify-content-center gap-1 align-items-center">
                            <button type="button" id="btn-all-presence" class="btn btn-outline-success">P</button>
                            <button type="button" id="btn-all-foul" class="btn btn-outline-danger">F</button>
                          </div>
                          <p class="small mb-0">*Duplo clique para falta justificada</p>
                        </div>
                      </th>
                    </tr>
                  </thead>
                  <tbody id="alunos-table-body"></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <button type="button" class="d-none button-fixed" id="btn-save-frequencies" title="Salvar frequência">
    <i class="bi bi-floppy fs-4 d-flex"></i>
  </button>
</main>

<div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="passwordModalLabel">Informe sua senha</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3 align-items-center">
          <div class="col-12">
            <label for="solicitPassword" class="form-label fw-bold">Senha:</label>
            <input type="password" class="form-control" id="solicitPassword" placeholder="Senha" maxlength="8" />
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn bg-cor-escola" id="btn-confirm-presencies">Enviar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="atestadoModal" tabindex="-1" aria-labelledby="atestadoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="atestadoModalLabel">Atestado</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3 align-items-center">
          <div class="col-12 text-center">
            <p class="fs-5 mb-0">
              Aluno(a) com atestado médico entre os dias
              <span class="fw-bold" id="days-atestado"></span>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="dailyClassModal" tabindex="-1" aria-labelledby="dailyClassLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="dailyClassLabel">Criar diário de classe</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3 align-items-center mb-3">
          <div class="col-12 col-md-auto">
            <label for="input-dc-prof" class="form-label fw-bold">Professor:</label>
            <select class="form-control" id="input-dc-prof" disabled></select>
          </div>
        </div>
        <div class="row g-3 align-items-center mb-3">
          <div class="col-4 col-md-auto">
            <label for="select-dc-year" class="form-label fw-bold">Ano: <span class="text-danger">*</span></label>
            <select name="select-dc-year" id="select-dc-year" class="form-control" disabled>
              <option value="<?php echo $ano_corrente; ?>" selected><?php echo $ano_corrente; ?></option>
            </select>
          </div>
          <div class="col-4 col-md-auto">
            <label for="select-dc-school" class="form-label fw-bold">Escola: <span class="text-danger">*</span></label>
            <select name="select-dc-school" id="select-dc-school" class="form-select"></select>
          </div>
          <div class="col-4 col-md-auto">
            <label for="select-dc-etapa" class="form-label fw-bold">Etapa: <span class="text-danger">*</span></label>
            <select name="select-dc-etapa" id="select-dc-etapa" class="form-select"></select>
          </div>
          <div class="col-12 col-md-auto">
            <label for="select-dc-serie" class="form-label fw-bold">Grau Série: <span class="text-danger">*</span></label>
            <div class="d-flex align-items-end gap-2">
              <select name="select-dc-serieInicial" id="select-dc-serieInicial" class="form-select"></select>
              <p class="fs-6 mb-0">a</p>
              <select name="select-dc-serieFinal" id="select-dc-serieFinal" class="form-select"></select>
            </div>
          </div>
          <div class="col-12 col-md-auto">
            <label for="select-dc-classe" class="form-label fw-bold">Turma: <span class="text-danger">*</span></label>
            <div class="d-flex align-items-end gap-2">
              <select name="select-dc-classeInicial" id="select-dc-classeInicial" class="form-select"></select>
              <p class="fs-6 mb-0">a</p>
              <select name="select-dc-classeFinal" id="select-dc-classeFinal" class="form-select"></select>
            </div>
          </div>   
        </div>
        <div class="row g-3 align-items-center mb-3">
          <div class="col-12 col-md-auto">
            <label for="input-extrasLines" class="form-label fw-bold">Linhas extras:</label>
            <input type="number" class="form-control" id="input-extrasLines" min="0" />
          </div>
          <div class="col-12 col-md">
            <label for="select-dc-disc" class="form-label fw-bold">Disciplina: <span class="text-danger">*</span></label>
            <div class="d-flex align-items-end gap-2">
              <select name="select-dc-discInicial" id="select-dc-discInicial" class="form-select"></select>
              <p class="fs-6 mb-0">a</p>
              <select name="select-dc-discFinal" id="select-dc-discFinal" class="form-select"></select>
            </div>
          </div>
        </div>
        <div class="row g-3 gap-md-5">
          <div class="col-12 col-md-auto" id="container-dc-turno">
            <label for="select-dc-turno" class="form-label fw-bold">Turno: <span class="text-danger">*</span></label>
            <div class="row g-3 align-items-center mb-3 mb-md-1">
              <div class="col-12">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="M" id="check-turno-m">
                  <label class="form-check-label" for="check-turno-m">Manhã</label>
                </div>
              </div>
            </div>
            <div class="row g-3 align-items-center mb-3 mb-md-1">
              <div class="col-12">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="T" id="check-turno-t">
                  <label class="form-check-label" for="check-turno-t">Tarde</label>
                </div>
              </div>
            </div>
            <div class="row g-3 align-items-center">
              <div class="col-12">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="N" id="check-turno-n">
                  <label class="form-check-label" for="check-turno-n">Noite</label>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md" id="container-dc-status">
            <label for="select-dc-status" class="form-label fw-bold">Status: <span class="text-danger">*</span></label>
            <div class="row g-3 align-items-center mb-3 mb-md-0">
              <div class="col-12 col-md-4">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="A" id="check-status-a">
                  <label class="form-check-label" for="check-status-a">Aprovado</label>
                </div>
              </div>
              <div class="col-12 col-md-4">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="R" id="check-status-r">
                  <label class="form-check-label" for="check-status-r">Reprovado</label>
                </div>
              </div>
              <div class="col-12 col-md-4">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="N" id="check-status-n">
                  <label class="form-check-label" for="check-status-n">Não matriculados</label>
                </div>
              </div>
            </div>
            <div class="row g-3 align-items-center mb-3 mb-md-0">
              <div class="col-12 col-md-4">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="C" id="check-status-c">
                  <label class="form-check-label" for="check-status-c">Cursando</label>
                </div>
              </div>
              <div class="col-12 col-md-4">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="T" id="check-status-t">
                  <label class="form-check-label" for="check-status-t">Transferido</label>
                </div>
              </div>
              <div class="col-12 col-md-4">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="P" id="check-status-p">
                  <label class="form-check-label" for="check-status-p">Progressão parcial</label>
                </div>
              </div>
            </div>
            <div class="row g-3 align-items-center">
              <div class="col-12 col-md-4">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="U" id="check-status-u">
                  <label class="form-check-label" for="check-status-u">Recuperação</label>
                </div>
              </div>
              <div class="col-12 col-md-4">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="D" id="check-status-d">
                  <label class="form-check-label" for="check-status-d">Desistente</label>
                </div>
              </div>
              <div class="col-12 col-md-4">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="L" id="check-status-l">
                  <label class="form-check-label" for="check-status-l">Recuperação paralela</label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn bg-cor-escola" id="btn-create-dailyClassFilter">Criar diário</button>
      </div>
    </div>
  </div>
</div>

<script>
    var matricula = <?php echo $matricula; ?>;
    var senha = '<?php echo $senha; ?>';
    var codigo_escola = <?php echo $codigo_escola; ?>;
    var dateFreq = '<?php echo $data; ?>';
    var ano_corrente = <?php echo $ano_corrente; ?>;
    var numero_contrato = <?php echo $numero_contrato; ?>;
    var tipo_user = '<?php echo $tipo_user; ?>';
</script>

<?php
require_once "./includes/footer.php"; 
?>