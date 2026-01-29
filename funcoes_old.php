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

    // Garante que a sessão está ativa
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $logDAO = new LogDAO($conexao);

    $_SESSION['erro_retorno_usuario'] = $retorno_usuario;
    $_SESSION['erro_codigo'] = $codigo;

    $logDAO->insertErro($nivel, $codigo, $arquivo, $descricao, $retorno_usuario);

    header('Location: /smo/erro.php');
    exit();
}


?>