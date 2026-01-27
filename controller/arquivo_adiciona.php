<?php
if(!isset($_SESSION)) session_start();

include_once '../funcoes.php';
include_once '../dao/conecta_banco.php';
include_once '../dao/LogDAO.php';
include_once '../models/Arquivo.php';
include_once '../models/Obrigatorio.php';
include_once '../models/Om.php';
include_once '../dao/ArquivoDAO.php';
include_once '../dao/ObrigatorioDAO.php';

$logDAO = new LogDAO($conexao);
$arquivoDAO = new ArquivoDAO($conexao);
$obrigatorioDAO = new ObrigatorioDAO($conexao);

$arquivo = new Arquivo();

$crip = filtra_campo_post('crip');
$id_obrigatorio = filtra_campo_post('id_obrigatorio');
$label = filtra_campo_post('nome_arquivo');

if($crip != hash('sha256', $_SESSION['chave']."arquivo_obrigatorio"))
{
    erro($BASE_URL, 2, 23567457, $pagina_atual, "criptografia_invalida", "Não foi possível adicionar o arquivo!");
    exit();
}

$obrigatorio = $obrigatorioDAO->findByID($id_obrigatorio);


$arquivo->setIdobrigatorio($id_obrigatorio);
$arquivo->setLabel($label);

// Pasta onde o arquivo vai ser salvo
$_UP['pasta'] = '../arquivos/';

// Tamanho máximo do arquivo (em Bytes)
$_UP['tamanho'] = 1024 * 1024 * 8; // 8Mb

// Array com as extensões permitidas
//$_UP['extensoes'] = array('png','jpg','jpeg','pdf', 'gif','doc','docx','ppt','pptx','txt','mp3','ods','odt','csv');
$_UP['extensoes'] = array('pdf');

// Array com os tipos de erros de upload do PHP
$_UP['erros'][0] = 'nao_teve_erros';
$_UP['erros'][1] = 'arquivo>que_limite_PHP'; // 'O arquivo no upload é maior do que o limite do PHP';
$_UP['erros'][2] = 'arquivo>que_tamanho_no_HTML';//'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
$_UP['erros'][3] = 'upload_feito_parcialmente'; //'O upload do arquivo foi feito parcialmente';
$_UP['erros'][4] = 'upload_nao_realizado';//'Não foi feito o upload do arquivo';

if(isset($_FILES['arquivo']['name']) && $_FILES['arquivo']['name'] != "")
{
    $mimetype = mime_content_type($_FILES['arquivo']['tmp_name']);
    
   
    if($mimetype != 'application/pdf')
    {
        erro($BASE_URL, 2, 2367547, $pagina_atual, "!mimetype", "O arquivo deve estar no formato PDF!");
        exit();
    }
    
    $extensao = null;
    $nome_original = null;
    
    $datetime = date('Y-m-d H:i:s');
    
    $codigo_criptografar = rand(1, 10000).$datetime."arquivo";
    $nome_arquivo = hash('sha256', $codigo_criptografar); 
    
    if($_FILES['arquivo']['name'] != null) $arquivo->setNomeOriginal($_FILES['arquivo']['name']);
    // Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
    if ($_FILES['arquivo']['error'] != 0) 
    {
        erro($BASE_URL, 2, 37458, $pagina_atual, "erro_upload", $_UP['erros'][$_FILES['arquivo']['error']] . " | O arquivo não foi adicionada! O arquivo deve conter no máximo 8 MB e estar no formato indicado");
        exit();
    }

    // Faz a verificação da extensão do arquivo
    $aux = explode('.', $_FILES['arquivo']['name']);
    $extensao = strtolower(end($aux));
    
    if (array_search($extensao, $_UP['extensoes']) === false) 
    {
        erro($BASE_URL, 1, 347456435, $pagina_atual, "!mimetype", "O arquivo deve estar no formato indicado!");
        exit();
    }
    
    $arquivo->setFormato($extensao);

    // Faz a verificação do tamanho do arquivo
    if ($_UP['tamanho'] < $_FILES['arquivo']['size']) 
    {
        erro($BASE_URL, 1, 435745754, $pagina_atual, "tamanho_muito_grande", "O arquivo enviado é muito grande, envie arquivos de até 8 MB!");
        exit();
    }
    
    
    $arquivo->setTamanho((int)$_FILES['arquivo']['size']);
    
    $nome_abrev_arquivo = "_". strtoupper($extensao)."_";
            
    if($extensao == 'pdf' || $extensao == 'PDF') $nome_abrev_arquivo = "_PDF_";

    $tamanho_do_arquivo =  intval($_FILES['arquivo']['size']);
    $detalhamento = "Inseriu um arquivo ".$nome_abrev_arquivo." para a obrigatorio ID ".$id_obrigatorio;
    $nome_arquivo = $id_obrigatorio .$nome_abrev_arquivo. $nome_arquivo; 
    $nome_arquivo = $nome_arquivo.'.'.$extensao;
    
    $arquivo->setNome($nome_arquivo);
    
    if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $_UP['pasta'] . $nome_arquivo)) 
    {
        //$retorno = $arquivoDAO->insertArquivo($id_obrigatorio, $label, $nome_arquivo, $nome_original, $tamanho_do_arquivo, $extensao);
        $retorno = $arquivoDAO->insertArquivo($arquivo);
        
        if($retorno)
        {
            $last_id = (int)$retorno['id_adicionado'];
            $alteracoes_detalhadas =  print_r($retorno, true);
            $alteracao = "Adicionou um arquivo de ID: $last_id para o obrigatório de ID: $id_obrigatorio";
            
            $logDAO->insertLog(1002, "arquivo", $last_id, $alteracao, $alteracoes_detalhadas);
            $_SESSION['mensagem_arquivo'] = "Arquivo Adicionado com sucesso! <img src='imagens/ok.png' height='40px'>";

            $crip_url = hash('sha256', $id_obrigatorio. "criptografia");
            header ("Location: ../obrigatorio.php?crip=$crip_url&id_obrigatorio=$id_obrigatorio&aba=7");
        }
    }
    else 
    {
        erro($BASE_URL, 2, 235346346, $pagina_atual, "!moveu_arquivo_para_pasta", "O arquivo NÃO foi adicionado!");
        $conexao = null;
    }
}
else 
{
    erro($BASE_URL, 2, 5235346534, $pagina_atual, "!moveu_arquivo_para_pasta", "O arquivo NÃO foi adicionado!");
    $conexao = null;
}



?>