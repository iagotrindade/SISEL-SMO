<?php

    if(!isset($_SESSION)) session_start();

    include_once '../funcoes.php';
    include_once '../dao/conecta_banco.php';
    include_once '../models/Arquivo.php';
    include_once '../dao/ArquivoDAO.php';
    include_once '../dao/LogDAO.php';

    $logDAO = new logDAO($conexao);
    $arquivoDAO = new ArquivoDAO($conexao);

    $crip = filtra_campo_get('crip');
    $id_arquivo = (int)filtra_campo_get('id_arquivo');
    
    $arquivo_download = $arquivoDAO->findById($id_arquivo);
    
    if(!$arquivo_download)
    {
        erro($BASE_URL, 3, 23346457, $pagina_atual, "arquivo_nao_encontrado", "Não foi possível fazer o download do arquivo!");
        exit();
    }

    if($crip != hash('sha256', $arquivo_download->getNome()."crip_download_arquivo"))
    {
        erro($BASE_URL, 3, 67965746, $pagina_atual, "crip_invalida", "Não foi possível fazer o download do arquivo!");
        exit();
    }
    
    
     

    $nome_arquivo_original = $arquivo_download->getNome();
    
    $nome_arquivo_url = "../arquivos/".$nome_arquivo_original;
    
    //$nome_arquivo_url = '../arquivos/5fdb518ec3a5a1b9ea68b4881177e1f0--landing-strip-nazca-peru.jpg';
    //$nome_arquivo_original = '5fdb518ec3a5a1b9ea68b4881177e1f0--landing-strip-nazca-peru.jpg';
    
    /*
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream;");
    header("Content-Length:" . filesize($nome_arquivo));
    header("Content-disposition: attachment; filename=" . $nome_arquivo_original);
    header("Pragma: no-cache");
    header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
    header("Expires: 0");
    readfile($nome_arquivo);
    flush();
     * * 
 */
    
    header("Content-type: application/save");
    header("Content-Length:".filesize($nome_arquivo_url));
    header('Content-Disposition: attachment; filename="' . $nome_arquivo_original . '"');
    header('Expires: 0');
    header('Pragma: no-cache');
    readfile("$nome_arquivo_url");
 
    
    //$logDAO->insertLog(7989, "arquivo", $arquivo_download->getId(), "Fez download do arquivo ID: $id_arquivo ", "Fez download de um arquivo da denúncia ID: ".$arquivo_download->getIdDenuncia()." arquivo ID: $id_arquivo");

?>