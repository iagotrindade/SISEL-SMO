<?php

//$BASE_URL = $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI'] . '?').'/';
$BASE_URL = $_SERVER['DOCUMENT_ROOT'] . "/smo";
//$BASE_URL = $_SERVER['DOCUMENT_ROOT'];
$pagina_atual = $_SERVER['REQUEST_URI'];

/////////////////////////////////////////////////////////////////

function saudacoes()
{
    $saudacao = "Boa noite";
    $hr = date(" H ");
    if ($hr >= 6 && $hr < 12 )
        $saudacao = "Bom dia";
    if($hr >= 12 && $hr < 19)
        $saudacao = "Boa tarde";

    return $saudacao;
}

function valida_cpf($cpf) 
{
    // verifica se e numerico
    if(!is_numeric($cpf)) {
    return false;
    }

    // verifica se esta usando a repeticao de um numero
    if( ($cpf == '11111111111') || 
        ($cpf == '22222222222') || 
        ($cpf == '33333333333') || 
        ($cpf == '44444444444') || 
        ($cpf == '55555555555') || 
        ($cpf == '66666666666') || 
        ($cpf == '77777777777') || 
        ($cpf == '88888888888') || 
        ($cpf == '99999999999') || 
        ($cpf == '00000000000') ) 
        {
            return false;
        }

    //PEGA O DIGITO VERIFIACADOR
    $dv_informado = substr($cpf, 9,2);

    for($i=0; $i<=8; $i++) 
    {
        $digito[$i] = substr($cpf, $i,1);
    }

    //CALCULA O VALOR DO 10º DIGITO DE VERIFICAÇÂO
    $posicao = 10;
    $soma = 0;

    for($i=0; $i<=8; $i++) 
    {
        $soma = $soma + $digito[$i] * $posicao;
        $posicao = $posicao - 1;
    }

    $digito[9] = $soma % 11;

    if($digito[9] < 2) 
    {
        $digito[9] = 0;
    } else 
    {
        $digito[9] = 11 - $digito[9];
    }

    //CALCULA O VALOR DO 11º DIGITO DE VERIFICAÇÃO
    $posicao = 11;
    $soma = 0;

    for ($i=0; $i<=9; $i++) 
    {
        $soma = $soma + $digito[$i] * $posicao;
        $posicao = $posicao - 1;
    }

    $digito[10] = $soma % 11;

    if ($digito[10] < 2) 
    {
        $digito[10] = 0;
    }
    else 
    {
        $digito[10] = 11 - $digito[10];
    }

    //VERIFICA SE O DV CALCULADO É IGUAL AO INFORMADO
    $dv = $digito[9] * 10 + $digito[10];
    if ($dv != $dv_informado) 
    {
        return false;
    }

    return true;
}

function dataExtenso($data)
{

    $meses = array(
        1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril', 5 => 'Maio', 6 => 'Junho',
        7 => 'Julho', 8 => 'Agosto', 9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
      );
      
      $data_parts = explode('/', $data);
      
      $dia = intval($data_parts[0]);
      $mes = intval($data_parts[1]);
      $ano = intval($data_parts[2]);
      
      $data = $dia . ' de ' . $meses[$mes] . ' de ' . $ano;

      return $data;

}

function removerCaracteresEspeciais($cpf) 
{
    // Remove espaços em branco, traços e pontos da string
    $string = str_replace([' ', '-', '.'], '', $cpf);
    return $string;
}

function filtra_campo_post($nome_campo)
{
    $campo = htmlspecialchars(trim(filter_input(INPUT_POST, $nome_campo, FILTER_DEFAULT)));
    if($campo == "" || $campo == null) 
        return null;
    return $campo;
}

function filtra_campo_get($nome_campo)
{
    $campo = htmlspecialchars(trim(filter_input(INPUT_GET, $nome_campo, FILTER_DEFAULT)));
    if($campo == "" || $campo == null) 
        return null;
    return $campo;
}

function getDiferencaDias($data_inicial, $data_final)
{
    $data_final = new DateTime(date($data_final));
    $data_fim_diferenca = new DateTime($data_inicial);
    $dateInterval = $data_final->diff($data_fim_diferenca);
    $dias_diferenca = $dateInterval->days;

    return $dias_diferenca; 
}

function retira_acentos($string){
    return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e e i I o O u U n N"),$string);
}

function multiexplode ($delimiters,$string) 
{
    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return  $launch;
}

function trata_data_hora($data)
{
    if(!empty($data))
    {
        $data_troca_separador = str_replace('-','/',$data);
        $exploded = multiexplode(array("/","-",":"," "),$data_troca_separador);
        return $exploded[2]."/".$exploded[1]."/".$exploded[0] . " " . $exploded[3] . ":" . $exploded[4] . ":". $exploded[5];
    }
}

function trata_data($data)
{
    if(!empty($data))
    {
        $data_troca_separador = str_replace('-','/',$data);
        $exploded = multiexplode(array("/","-",":"," "),$data_troca_separador);
        return $exploded[2]."/".$exploded[1]."/".$exploded[0];
    }
}

function valida_data($data)
{
    $data_numero=str_replace('/','',$data);
    $data_numero=str_replace('-','',$data);

    if((int)$data_numero <= 0)
    {
        return false;
    }
    
    
    $data_troca_separador = str_replace('-','/',$data);
    
    $exploded = multiexplode(array("/","-",":"," "),$data_troca_separador);
    $dia = $exploded[0]; //string(2) "22"
    $mes = $exploded[1];  // string(2) "09"
    $ano = $exploded[2]; //string(4) "1991"
    
    
    if (strlen($dia) == 4) 
    {
        if($dia > 2040 || $dia < 1950 || $mes <1 || $mes > 12 || $ano<1 || $ano > 31)
            return false;
        else return true;
    }

    if (strlen($dia) == 2) 
    {   
        if($dia > 31 || $dia < 1 || $mes <1 || $mes > 12 || $ano<1950 || $ano > 2040)
            return false;
        else  return true;
    }
    return false; 
}



function getBrowser()
    {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version= "";
        $ub= "";

        $platform=   "SO Desconhecido";

        $os_array   =   array(
                        '/windows nt 10/i'     =>  'Windows 10',
                        '/windows nt 6.3/i'     =>  'Windows 8.1',
                        '/windows nt 6.2/i'     =>  'Windows 8',
                        '/windows nt 6.1/i'     =>  'Windows 7',
                        '/windows nt 6.0/i'     =>  'Windows Vista',
                        '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                        '/windows nt 5.1/i'     =>  'Windows XP',
                        '/windows xp/i'         =>  'Windows XP',
                        '/windows nt 5.0/i'     =>  'Windows 2000',
                        '/windows me/i'         =>  'Windows ME',
                        '/win98/i'              =>  'Windows 98',
                        '/win95/i'              =>  'Windows 95',
                        '/win16/i'              =>  'Windows 3.11',
                        '/macintosh|mac os x/i' =>  'Mac OS X',
                        '/mac_powerpc/i'        =>  'Mac OS 9',
                        '/linux/i'              =>  'Linux',
                        '/ubuntu/i'             =>  'Ubuntu',
                        '/iphone/i'             =>  'iPhone',
                        '/ipod/i'               =>  'iPod',
                        '/ipad/i'               =>  'iPad',
                        '/android/i'            =>  'Android',
                        '/blackberry/i'         =>  'BlackBerry',
                        '/webos/i'              =>  'Mobile'
                    );

                    foreach ($os_array as $regex => $value) 
                    { 
                        if (preg_match($regex, $u_agent)) 
                        {
                            $platform    =   $value;
                        }
                    }   

        // Next get the name of the useragent yes seperately and for good reason
        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        }
        elseif(preg_match('/Firefox/i',$u_agent))
        {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        }
        elseif(preg_match('/Chrome/i',$u_agent))
        {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        }
        elseif(preg_match('/Safari/i',$u_agent))
        {
            $bname = 'Apple Safari';
            $ub = "Safari";
        }
        elseif(preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Opera';
            $ub = "Opera";
        }
        elseif(preg_match('/Netscape/i',$u_agent))
        {
            $bname = 'Netscape';
            $ub = "Netscape";
        }

        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
        ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                $version= $matches['version'][0];
            }
            else {
                $version= $matches['version'][1];
            }
        }
        else {
            $version= $matches['version'][0];
        }

        // check if we have a number
        if ($version==null || $version=="") {$version="?";}

        return array(
            'userAgent' => $u_agent,
            'name'      => $bname,
            'version'   => $version,
            'platform'  => $platform,
            'pattern'   => $pattern
        );

    }

function erro($BASE_URL, $nivel, $codigo, $arquivo, $descricao, $retorno_usuario)
{
    require $BASE_URL . '/dao/conecta_banco.php';
    include_once $BASE_URL . '/dao/LogDAO.php';

    $id_usuario_erro = null;
    if(isset($_SESSION['id_usuario_smo']))
        $id_usuario_erro = $_SESSION['id_usuario_smo'];

    $logDAO = new logDAO($conexao);

    $_SESSION['erro_retorno_usuario'] = $retorno_usuario;
    $_SESSION['erro_codigo'] = $codigo;

    // Armazena página de retorno baseado no estado de login
    if(isset($_SESSION['id_usuario_smo']) && (int)$_SESSION['id_usuario_smo'] > 0) {
        // Usuário logado: volta para página inicial do perfil
        if(isset($_SESSION['perfil_smo']) && $_SESSION['perfil_smo'] == 'operador') {
            $_SESSION['erro_pagina_retorno'] = '/smo/distribuidos_om_1_fase.php';
        } else {
            $_SESSION['erro_pagina_retorno'] = '/smo/obrigatorios.php';
        }
    } else {
        // Usuário não logado: volta para login
        $_SESSION['erro_pagina_retorno'] = '/smo/login.php';
    }

    $resultado = $logDAO->insertErro($nivel, $codigo, $arquivo, $descricao, $retorno_usuario);

    echo '<meta http-equiv="refresh" content="0; URL=/smo/erro.php">';
    exit();
}


/////////////////////////////////////////////////////////////////
// HISTÓRICO DETALHADO - Diff campo a campo
/////////////////////////////////////////////////////////////////

/**
 * Retorna mapeamento de nomes de campos do Obrigatório para rótulos em português
 */
function getLabelsObrigatorio()
{
    return [
        'nome_completo' => 'Nome Completo',
        'cpf' => 'CPF',
        'telefone' => 'Telefone',
        'mail' => 'E-mail',
        'estado_civil' => 'Estado Civil',
        'data_nascimento' => 'Data de Nascimento',
        'nome_pai' => 'Nome do Pai',
        'nome_mae' => 'Nome da Mãe',
        'nacionalidade' => 'Nacionalidade',
        'naturalidade' => 'Naturalidade',
        'identidade' => 'Identidade',
        'dependentes' => 'Dependentes',
        'endereco' => 'Endereço',
        'prioridade_forca' => 'Prioridade Força',
        'voluntario' => 'Voluntário',
        'documento_militar' => 'Documento Militar',
        'numero_documento_militar' => 'Nº Documento Militar',
        'data_expedicao' => 'Data Expedição',
        'forca' => 'Força',
        'nome_instituicao_ensino' => 'Instituição de Ensino',
        'ano_formacao' => 'Ano de Formação',
        'formacao' => 'Formação',
        'ano_residencia_espe_1' => 'Ano Residência Esp. 1',
        'ano_residencia_espe_2' => 'Ano Residência Esp. 2',
        'ano_residencia_espe_3' => 'Ano Residência Esp. 3',
        'cidade_instituicao_ensino' => 'Cidade Instituição',
        'jise' => 'JISE',
        'cid_jise' => 'CID JISE',
        'data_exame_medico' => 'Data Exame Médico',
        'observacao_jise' => 'Observação JISE',
        'jisr' => 'JISR',
        'cid_jisr' => 'CID JISR',
        'data_jisr' => 'Data JISR',
        'jise_a_1' => 'JISE A1',
        'cid_jise_a_1' => 'CID JISE A1',
        'data_jise_a_1' => 'Data JISE A1',
        'observacao_jise_a_1' => 'Observação JISE A1',
        'data_selecao_geral' => 'Data Seleção Geral',
        'data_comparecimento_selecao_geral' => 'Data Comparecimento Sel. Geral',
        'data_comparecimento_designacao' => 'Data Comparecimento Designação',
        'data_proxima_apresentacao' => 'Data Próxima Apresentação',
        'situacao_militar' => 'Situação Militar',
        'solicitou_adiamento' => 'Solicitou Adiamento',
        'inicio_adiamento' => 'Início Adiamento',
        'fim_adiamento' => 'Fim Adiamento',
        'especialidade_adiamento' => 'Especialidade Adiamento',
        'transferencia_fisemi' => 'Transferência FISEMI',
        'rm_origem_fisemi' => 'RM Origem FISEMI',
        'rm_destino_fisemi' => 'RM Destino FISEMI',
        'numero_acao' => 'Número da Ação',
        'transitou_julgado' => 'Transitou em Julgado',
        'data_liminar' => 'Data Liminar',
        'favoravel' => 'Favorável',
        'convocado' => 'Convocado',
        'distribuicao' => 'Distribuição',
        'om_1_fase' => 'OM 1ª Fase',
        'compareceu_designacao' => 'Compareceu Designação',
        'local_compareceu_designacao' => 'Local Compareceu Designação',
        'data_selecao_complementar' => 'Data Seleção Complementar',
        'resultado_revisao_medica_complementar' => 'Resultado Revisão Médica Compl.',
        'resultado_isgr' => 'Resultado ISGR',
        'data_incorporacao' => 'Data Incorporação',
        'om_2_fase' => 'OM 2ª Fase',
        'observacao' => 'Observação',
        'especialidade_1' => 'Especialidade 1',
        'especialidade_2' => 'Especialidade 2',
        'especialidade_3' => 'Especialidade 3',
        'data_revisao_medica' => 'Data Revisão Médica',
        'cid_revisao_medica' => 'CID Revisão Médica',
        'obs_revisao_medica' => 'Obs. Revisão Médica',
        'incorporacao' => 'Incorporação',
        'bar_om_1_fase' => 'BAR OM 1ª Fase',
        'data_isgr' => 'Data ISGR',
        'cid_isgr' => 'CID ISGR',
        'observacao_isgr' => 'Observação ISGR',
    ];
}

/**
 * Compara estado antes/depois de um Obrigatório e retorna JSON com as mudanças
 * @param array $antes Estado antes da edição (via toArray())
 * @param array $depois Estado depois da edição (via toArray())
 * @return string|null JSON com as mudanças ou null se não houve alteração
 */
function gerarDiffObrigatorio($antes, $depois)
{
    $labels = getLabelsObrigatorio();
    $changes = [];

    foreach ($depois as $field => $newValue) {
        $oldValue = $antes[$field] ?? null;

        // Normaliza valores para comparação (null e string vazia são iguais)
        $oldNorm = ($oldValue === null || $oldValue === '') ? '' : (string)$oldValue;
        $newNorm = ($newValue === null || $newValue === '') ? '' : (string)$newValue;

        if ($oldNorm !== $newNorm) {
            $changes[] = [
                'field' => $field,
                'label' => $labels[$field] ?? $field,
                'old' => $oldNorm,
                'new' => $newNorm
            ];
        }
    }

    if (empty($changes)) {
        return null;
    }

    return json_encode(['changes' => $changes], JSON_UNESCAPED_UNICODE);
}

/////////////////////////////////////////////////////////////////
// EXPORTAÇÃO MULTI-FORMATO (PDF, Word)
/////////////////////////////////////////////////////////////////

/**
 * Converte caminhos relativos de imagens (../imagens/*) para base64 data URIs.
 * Necessário porque os arquivos .doc/.odt baixados não têm acesso ao servidor.
 */
function converterImagensParaBase64($html)
{
    return preg_replace_callback(
        '/(<img[^>]*?)src=[\'"]\.\.\/imagens\/([^"\']+)[\'"]/i',
        function ($matches) {
            $imagePath = __DIR__ . DIRECTORY_SEPARATOR . 'imagens' . DIRECTORY_SEPARATOR . $matches[2];
            if (file_exists($imagePath)) {
                $imageData = base64_encode(file_get_contents($imagePath));
                $ext = strtolower(pathinfo($matches[2], PATHINFO_EXTENSION));
                $mimeTypes = ['png' => 'image/png', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'gif' => 'image/gif'];
                $mime = $mimeTypes[$ext] ?? 'image/png';
                return $matches[1] . 'src="data:' . $mime . ';base64,' . $imageData . '"';
            }
            return $matches[0];
        },
        $html
    );
}

/**
 * Exporta HTML como documento Word (.doc)
 */
function outputComoDocumento($html, $formato, $nomeArquivo, $config = [])
{
    if ($formato === 'doc' || $formato === 'docx') {
        outputComoWord($html, $nomeArquivo, $config);
    }
}

/**
 * Converte HTML simples de header/footer (divs com texto, <br>, bordas) para OOXML WordprocessingML.
 * Suporta {PAGENO} e {nbpg} convertidos em campos PAGE/NUMPAGES do Word.
 */
function gerarOoxmlDeHtml($html, $comPaginacao = false)
{
    $ooxml = '';

    // Extrair cada <div> como um parágrafo separado
    preg_match_all('/<div\b([^>]*)>(.*?)<\/div>/si', $html, $divs, PREG_SET_ORDER);

    if (empty($divs)) {
        $texto = htmlspecialchars(trim(strip_tags($html)), ENT_XML1, 'UTF-8');
        if (!empty($texto)) {
            $ooxml .= '<w:p><w:r><w:t xml:space="preserve">' . $texto . '</w:t></w:r></w:p>';
        }
        return $ooxml;
    }

    foreach ($divs as $div) {
        $attrs = $div[1];
        $conteudo = $div[2];

        // Extrair style do atributo
        $style = '';
        if (preg_match('/style=[\'"]([^\'"]*)[\'"]/', $attrs, $m)) $style = $m[1];

        // Propriedades CSS
        $align = 'left';
        if (preg_match('/text-align:\s*center/i', $style)) $align = 'center';
        elseif (preg_match('/text-align:\s*right/i', $style)) $align = 'right';

        $hasBorder = (bool)preg_match('/border/i', $style);

        $color = '000000';
        if (preg_match('/(?:^|;|\s)color:\s*#([0-9A-Fa-f]{6})/i', $style, $m)) $color = $m[1];

        $fontSize = 16; // 8pt = 16 half-points
        if (preg_match('/font-size:\s*(\d+)\s*pt/i', $style, $m)) $fontSize = intval($m[1]) * 2;

        $isItalic = (bool)preg_match('/font-style:\s*italic/i', $style);

        // Run properties comuns (Times New Roman explícito para não usar Calibri padrão)
        $rPr = '<w:rPr>';
        $rPr .= '<w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman" w:cs="Times New Roman"/>';
        $rPr .= '<w:sz w:val="' . $fontSize . '"/><w:szCs w:val="' . $fontSize . '"/>';
        if ($color !== '000000') $rPr .= '<w:color w:val="' . $color . '"/>';
        if ($isItalic) $rPr .= '<w:i/>';
        $rPr .= '</w:rPr>';

        // Paragraph properties
        $pPr = '<w:pPr><w:jc w:val="' . $align . '"/>';
        if ($hasBorder) {
            $pPr .= '<w:pBdr>';
            $pPr .= '<w:top w:val="single" w:sz="4" w:space="1" w:color="' . $color . '"/>';
            $pPr .= '<w:left w:val="single" w:sz="4" w:space="4" w:color="' . $color . '"/>';
            $pPr .= '<w:bottom w:val="single" w:sz="4" w:space="1" w:color="' . $color . '"/>';
            $pPr .= '<w:right w:val="single" w:sz="4" w:space="4" w:color="' . $color . '"/>';
            $pPr .= '</w:pBdr>';
        }
        $pPr .= '</w:pPr>';

        $temPaginacao = $comPaginacao && (strpos($conteudo, '{PAGENO}') !== false || strpos($conteudo, '{nbpg}') !== false);

        $ooxml .= '<w:p>' . $pPr;

        if ($temPaginacao) {
            // Texto com campos de paginação
            $texto = trim(strip_tags(preg_replace('/<br\s*\/?>/i', ' ', $conteudo)));
            $partes = preg_split('/(\{PAGENO\}|\{nbpg\})/', $texto, -1, PREG_SPLIT_DELIM_CAPTURE);

            foreach ($partes as $parte) {
                if ($parte === '{PAGENO}' || $parte === '{nbpg}') {
                    $campo = $parte === '{PAGENO}' ? ' PAGE ' : ' NUMPAGES ';
                    $ooxml .= '<w:r>' . $rPr . '<w:fldChar w:fldCharType="begin"/></w:r>';
                    $ooxml .= '<w:r>' . $rPr . '<w:instrText xml:space="preserve">' . $campo . '</w:instrText></w:r>';
                    $ooxml .= '<w:r>' . $rPr . '<w:fldChar w:fldCharType="separate"/></w:r>';
                    $ooxml .= '<w:r>' . $rPr . '<w:t>1</w:t></w:r>';
                    $ooxml .= '<w:r>' . $rPr . '<w:fldChar w:fldCharType="end"/></w:r>';
                } elseif (!empty(trim($parte))) {
                    $ooxml .= '<w:r>' . $rPr . '<w:t xml:space="preserve">' . htmlspecialchars(trim($parte), ENT_XML1, 'UTF-8') . '</w:t></w:r>';
                }
            }
        } else {
            // Texto normal, dividido por <br>
            $linhas = preg_split('/<br\s*\/?>/i', $conteudo);
            foreach ($linhas as $i => $linha) {
                $texto = htmlspecialchars(trim(strip_tags($linha)), ENT_XML1, 'UTF-8');
                if (!empty($texto)) {
                    if ($i > 0) $ooxml .= '<w:r><w:br/></w:r>';
                    $ooxml .= '<w:r>' . $rPr . '<w:t xml:space="preserve">' . $texto . '</w:t></w:r>';
                }
            }
        }

        $ooxml .= '</w:p>';
    }

    return $ooxml;
}

/**
 * Adiciona propriedades CSS inline a uma tag HTML.
 * Estilos da classe vão ANTES dos inline existentes, para que inline tenha precedência.
 */
function adicionarStyleInline($tagHtml, $novoEstilo)
{
    if (preg_match('/style=[\'"]([^\'"]*)[\'"]/', $tagHtml, $m)) {
        // Class styles primeiro, inline existente depois (inline vence em caso de conflito)
        $combinado = $novoEstilo . ' ' . rtrim($m[1], '; ') . ';';
        return preg_replace('/style=[\'"][^\'"]*[\'"]/', 'style="' . $combinado . '"', $tagHtml);
    }
    return preg_replace('/>$/', ' style="' . $novoEstilo . '">', $tagHtml);
}

/**
 * Converte CSS de <style> blocks para inline styles nos elementos HTML.
 * Necessário porque o Word (altChunk) não processa <style> blocks.
 *
 * Suporta:
 * - Seletores de elemento: body { ... }
 * - Seletores de classe simples: .classname { ... }
 * - Seletores compostos: .parent child { ... } (ex: .tabela-principal th)
 */
function inlineCssParaWord($html)
{
    // 1. Extrair regras CSS dos blocos <style>
    $cssRules = [];
    $htmlLimpo = preg_replace_callback(
        '/<style[^>]*>(.*?)<\/style>/si',
        function ($matches) use (&$cssRules) {
            preg_match_all('/([^{]+)\{([^}]+)\}/s', $matches[1], $rules, PREG_SET_ORDER);
            foreach ($rules as $rule) {
                $cssRules[] = [
                    'seletor' => trim($rule[1]),
                    'props' => trim(preg_replace('/\s+/', ' ', $rule[2]))
                ];
            }
            return '';
        },
        $html
    );

    if (empty($cssRules)) return $html;

    $bodyStyle = '';

    foreach ($cssRules as $rule) {
        $sel = $rule['seletor'];
        $props = rtrim($rule['props'], '; ') . ';';

        // Seletor body → será aplicado como div wrapper
        if ($sel === 'body') {
            $bodyStyle = $props;
            continue;
        }

        // Seletor de classe simples: .classname
        if (preg_match('/^\.([a-zA-Z0-9_-]+)$/', $sel, $m)) {
            $cls = preg_quote($m[1], '/');
            $htmlLimpo = preg_replace_callback(
                '/(<\w+\b[^>]*?\bclass=[\'"]' . $cls . '[\'"][^>]*?)>/si',
                function ($matches) use ($props) {
                    return adicionarStyleInline($matches[0], $props);
                },
                $htmlLimpo
            );
            continue;
        }

        // Seletor composto: .parent child (ex: .tabela-principal th)
        if (preg_match('/^\.([a-zA-Z0-9_-]+)\s+(\w+)$/', $sel, $m)) {
            $parentCls = preg_quote($m[1], '/');
            $childTag = $m[2];

            // Encontrar blocos do elemento pai e aplicar estilos nos filhos
            $htmlLimpo = preg_replace_callback(
                '/<(\w+)\b([^>]*?\bclass=[\'"]' . $parentCls . '[\'"][^>]*)>(.*?)<\/\1>/si',
                function ($matches) use ($childTag, $props) {
                    $parentOpen = '<' . $matches[1] . $matches[2] . '>';
                    $content = $matches[3];
                    $parentClose = '</' . $matches[1] . '>';

                    // Aplicar estilo em todas as tags filhas do tipo especificado
                    $ct = preg_quote($childTag, '/');
                    $content = preg_replace_callback(
                        '/(<' . $ct . '\b[^>]*?)>/si',
                        function ($cm) use ($props) {
                            return adicionarStyleInline($cm[0], $props);
                        },
                        $content
                    );

                    return $parentOpen . $content . $parentClose;
                },
                $htmlLimpo
            );
            continue;
        }
    }

    // Envolver com estilos do body
    if ($bodyStyle) {
        $htmlLimpo = '<div style="' . $bodyStyle . '">' . $htmlLimpo . '</div>';
    }

    return $htmlLimpo;
}

/**
 * Gera arquivo DOCX real (Office Open XML) com headers/footers nativos e paginação dinâmica.
 * Usa altChunk para embutir o conteúdo HTML dentro do DOCX.
 */
function outputComoWord($html, $nomeArquivo, $config = [])
{
    $orientacao = $config['orientacao'] ?? 'portrait';
    $mt = $config['margin_top'] ?? '25mm';
    $mb = $config['margin_bottom'] ?? '28mm';
    $ml = $config['margin_left'] ?? '10mm';
    $mr = $config['margin_right'] ?? '10mm';
    $headerHtml = $config['header_html'] ?? '';
    $footerHtml = $config['footer_html'] ?? '';

    $timestamp = time();
    $nomeCompleto = $nomeArquivo . '_' . $timestamp . '.doc';

    // Converter mm para twips (1 inch = 1440 twips, 1 inch = 25.4mm)
    $fator = 1440 / 25.4;
    $mtTwips = round(floatval(str_replace('mm', '', $mt)) * $fator);
    $mbTwips = round(floatval(str_replace('mm', '', $mb)) * $fator);
    $mlTwips = round(floatval(str_replace('mm', '', $ml)) * $fator);
    $mrTwips = round(floatval(str_replace('mm', '', $mr)) * $fator);
    $hdrMargin = round(5 * $fator);  // 5mm para header
    $ftrMargin = round(8 * $fator);  // 8mm para footer

    // Tamanho A4 em twips
    $pgW = $orientacao === 'landscape' ? 16838 : 11906;
    $pgH = $orientacao === 'landscape' ? 11906 : 16838;
    $orientAttr = $orientacao === 'landscape' ? ' w:orient="landscape"' : '';

    // Preparar HTML: converter imagens para base64, width nas tabelas
    $html = converterImagensParaBase64($html);
    $html = preg_replace('/<table\b((?![^>]*\bwidth=)[^>]*)>/i', '<table width="100%"$1>', $html);

    // Converter CSS de <style> para inline (Word altChunk não processa <style> blocks)
    $htmlInline = inlineCssParaWord($html);

    // Propagar font-family para todos os elementos com style inline
    // Word não herda font-family de elementos pai no altChunk, precisa ser explícito
    $htmlInline = preg_replace_callback(
        '/style=[\'"]([^\'"]*)[\'"]/',
        function ($m) {
            if (stripos($m[1], 'font-family') === false) {
                return 'style="font-family: \'Times New Roman\', Times, serif; ' . $m[1] . '"';
            }
            return 'style="' . $m[1] . '"';
        },
        $htmlInline
    );

    // Montar HTML completo para altChunk
    $altHtml = "<!DOCTYPE html>\n<html>\n<head>\n<meta charset=\"UTF-8\">\n";
    $altHtml .= "</head>\n<body>\n" . $htmlInline . "\n</body>\n</html>";

    // Criar arquivo DOCX (ZIP com XML)
    $tempFile = tempnam(sys_get_temp_dir(), 'docx_');
    $zip = new ZipArchive();
    if ($zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        echo '<h3>Erro: não foi possível criar o arquivo DOCX.</h3>';
        exit();
    }

    // IDs de relacionamentos
    $relId = 1;
    $chunkId = 'rId' . $relId++;
    $hdrId = $headerHtml ? 'rId' . $relId++ : '';
    $ftrId = $footerHtml ? 'rId' . $relId++ : '';

    // === [Content_Types].xml ===
    $ct = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
    $ct .= '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">';
    $ct .= '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>';
    $ct .= '<Default Extension="xml" ContentType="application/xml"/>';
    $ct .= '<Default Extension="html" ContentType="text/html"/>';
    $ct .= '<Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>';
    if ($hdrId) $ct .= '<Override PartName="/word/header1.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.header+xml"/>';
    if ($ftrId) $ct .= '<Override PartName="/word/footer1.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.footer+xml"/>';
    $ct .= '</Types>';
    $zip->addFromString('[Content_Types].xml', $ct);

    // === _rels/.rels ===
    $rr = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
    $rr .= '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">';
    $rr .= '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>';
    $rr .= '</Relationships>';
    $zip->addFromString('_rels/.rels', $rr);

    // === word/_rels/document.xml.rels ===
    $dr = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
    $dr .= '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">';
    $dr .= '<Relationship Id="' . $chunkId . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/aFChunk" Target="afchunk.html"/>';
    if ($hdrId) $dr .= '<Relationship Id="' . $hdrId . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/header" Target="header1.xml"/>';
    if ($ftrId) $dr .= '<Relationship Id="' . $ftrId . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/footer" Target="footer1.xml"/>';
    $dr .= '</Relationships>';
    $zip->addFromString('word/_rels/document.xml.rels', $dr);

    // === word/document.xml ===
    $doc = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
    $doc .= '<w:document xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"';
    $doc .= ' xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">';
    $doc .= '<w:body>';
    $doc .= '<w:altChunk r:id="' . $chunkId . '"/>';
    $doc .= '<w:sectPr>';
    if ($hdrId) $doc .= '<w:headerReference w:type="default" r:id="' . $hdrId . '"/>';
    if ($ftrId) $doc .= '<w:footerReference w:type="default" r:id="' . $ftrId . '"/>';
    $doc .= '<w:pgSz w:w="' . $pgW . '" w:h="' . $pgH . '"' . $orientAttr . '/>';
    $doc .= '<w:pgMar w:top="' . $mtTwips . '" w:right="' . $mrTwips . '" w:bottom="' . $mbTwips . '" w:left="' . $mlTwips . '"';
    $doc .= ' w:header="' . $hdrMargin . '" w:footer="' . $ftrMargin . '"/>';
    $doc .= '</w:sectPr>';
    $doc .= '</w:body></w:document>';
    $zip->addFromString('word/document.xml', $doc);

    // === word/afchunk.html (conteúdo HTML embutido) ===
    $zip->addFromString('word/afchunk.html', "\xEF\xBB\xBF" . $altHtml);

    // === word/header1.xml (OOXML nativo - repete em todas as páginas) ===
    if ($hdrId) {
        $hdr = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
        $hdr .= '<w:hdr xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">';
        $hdr .= gerarOoxmlDeHtml($headerHtml, false);
        $hdr .= '</w:hdr>';
        $zip->addFromString('word/header1.xml', $hdr);
    }

    // === word/footer1.xml (OOXML nativo - repete em todas as páginas com paginação) ===
    if ($ftrId) {
        $ftr = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
        $ftr .= '<w:ftr xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">';
        $ftr .= gerarOoxmlDeHtml($footerHtml, true);
        $ftr .= '</w:ftr>';
        $zip->addFromString('word/footer1.xml', $ftr);
    }

    $zip->close();

    // Enviar arquivo Word (.doc é DOCX internamente, Word detecta o formato automaticamente)
    header("Content-Type: application/msword");
    header("Content-Disposition: attachment; filename=\"{$nomeCompleto}\"");
    header("Content-Length: " . filesize($tempFile));
    header("Pragma: no-cache");
    header("Expires: 0");

    readfile($tempFile);
    unlink($tempFile);
}

?>