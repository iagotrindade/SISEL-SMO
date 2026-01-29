<?php
include_once 'funcoes.php';

// INICIALIZAÇÃO DA SESSÃO E SEGURANÇA
// Tempo em segundos (exemplo: 4 hora)
$lifetime = 14400; // 14400 = 4h

// Define o tempo de vida do cookie de sessão
ini_set('session.cookie_lifetime', $lifetime);

// Define o tempo de vida da sessão no servidor
ini_set('session.gc_maxlifetime', $lifetime);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// GERAÇÃO DE CHAVE DE SEGURANÇA
$rand = rand(100, 10000);
$codigo_criptografar = $rand . time() . "siscob";
$codigo_chave = substr(md5($codigo_criptografar), 0, 20);
$_SESSION['chave'] = $codigo_chave;

// INFORMAÇÕES DA PÁGINA
$pagina_acessada = $_SERVER['PHP_SELF'];
$endereco_completo = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

// VERIFICAÇÃO DE TROCA DE SENHA OBRIGATÓRIA
if (isset($_SESSION['id_usuario_smo']) && $_SESSION['trocar_senha_smo'] == true && $pagina_acessada != "/smo/altera_senha.php") {
    session_destroy();
    header("Location: index.php");
    exit();
}

// REGISTRO DE LOG DE ACESSO
include_once $BASE_URL . '/dao/conecta_banco.php';
include_once $BASE_URL . '/dao/LogDAO.php';
$logDAO = new logDAO($conexao);
$insere_log = $logDAO->insertAcessoPagina($pagina_acessada, $endereco_completo);
?>

<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Language" content="pt-BR">
    <meta name="description" content="Sistema de Gerenciamento MFDV - Serviço Militar">
    <meta name="author" content="SMO">

    <!-- Segurança -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com; script-src 'self' 'unsafe-inline' 'unsafe-eval'; font-src 'self' https://cdnjs.cloudflare.com;">

    <title>SiS MFDV - SMO</title>


    <!-- Vendor CSS -->
    <!-- AOS REMOVIDO - ROLLBACK: <link href="assets/vendor/aos/aos.css" rel="stylesheet"> -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bibliotecas JavaScript -->
    <script src="libs/jquery-3.6.0.min.js"></script>
    <script src="libs/bootstrap.min.js"></script>

    <!-- Select Multiplo -->
    <link rel="stylesheet" href="assets/select_multiplo/chosen.css">

    <!-- DataTables -->
    <link href="libs/responsive.dataTables.min.css" rel="stylesheet">
    <link href="libs/jquery.dataTables.min.css" rel="stylesheet">
    <script src="libs/jquery.dataTables.min.js"></script>
    <script src="libs/dataTables.responsive.min.js"></script>

    <!-- Máscaras -->
    <script src="libs/jquery.maskedinput.js"></script>
    <!-- maskMoney REMOVIDO - ROLLBACK: <script src="libs/maskMoney.js"></script> -->

    <!-- CSS padrão -->
    <link href="assets/css/style.css" rel="stylesheet">

    <!-- CSS para Filtros e Forms -->
    <link href="assets/css/style-filters.css" rel="stylesheet">

    <!-- Theme Stylesheet será carregado dinamicamente pelo theme-switcher.js -->
    <script>
        // Carrega o tema inicial antes da página renderizar
        (function() {
            const THEME_KEY = 'smo-theme-preference';
            const savedTheme = localStorage.getItem(THEME_KEY) || 'light';
            const themeFile = savedTheme === 'dark' ?
                'assets/css/style-dark-modern.css' :
                'assets/css/style-light-modern.css';

            const link = document.createElement('link');
            link.id = 'theme-stylesheet';
            link.rel = 'stylesheet';
            link.href = themeFile;
            document.head.appendChild(link);

        })();
    </script>

    <script>
        // Configurações iniciais quando o documento está pronto
        $(document).ready(function() {
            // Aplica máscaras nos campos
            aplicarMascaras();

            // Configurações de acessibilidade
            configurarAcessibilidade();

            // Previne envio duplo de formulários
            prevenirEnvioDuplo();
        });

        // Função para aplicar todas as máscaras
        function aplicarMascaras() {
            $("input[name*='cpf']").mask("999.999.999-99");
            $("input[name*='data']").mask("99/99/9999");
            $("input[name*='ano']").mask("9999");
            $("input[name*='telefone']").mask("(99) 99999-9999");
            $("input[name*='cep']").mask("99999-999");

            // Máscara monetária se necessário
            // $("input[name*='valor']").maskMoney({
            //     showSymbol: true, 
            //     symbol: "R$ ", 
            //     decimal: ",", 
            //     thousands: "."
            // });
        }

        // Funções de foco para melhor usabilidade
        function cursorCPF() {
            const input = document.getElementById('cpf');
            if (input) {
                input.focus();
                input.setSelectionRange(0, 0);
            }
        }

        function cursorDataNascimento() {
            const input = document.getElementById('data_nascimento');
            if (input) {
                input.focus();
                input.setSelectionRange(0, 0);
            }
        }

        // Configurações de acessibilidade
        function configurarAcessibilidade() {
            // Adiciona labels para elementos sem texto visível
            $('i[aria-hidden="true"]').each(function() {
                if (!$(this).attr('aria-label')) {
                    const title = $(this).attr('title');
                    if (title) {
                        $(this).attr('aria-label', title);
                    }
                }
            });
        }

        // Previne envio duplo de formulários
        function prevenirEnvioDuplo() {
            $('form').on('submit', function() {
                const btnSubmit = $(this).find('button[type="submit"], input[type="submit"]');
                btnSubmit.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Processando...');

                // Reativa o botão após 5 segundos (caso haja erro no envio)
                setTimeout(function() {
                    btnSubmit.prop('disabled', false).html('Enviar');
                }, 5000);
            });
        }

        // Função para mostrar loading
        function mostrarLoading() {
            $('#loadingOverlay').fadeIn();
        }

        // Função para esconder loading
        function esconderLoading() {
            $('#loadingOverlay').fadeOut();
        }

        // Tratamento de erros global
        window.addEventListener('error', function(e) {
            console.error('Erro capturado:', e.error);
            esconderLoading();
        });
    </script>
</head>

<body class="d-flex flex-column min-vh-100">
    <!-- Navegação por teclado para acessibilidade -->
    <a href="#main-content" class="sr-only sr-only-focusable">Pular para o conteúdo principal</a>

    <a name="topo" id="topo"></a>

    <?php
    // Inclui o menu apropriado baseado no estado de login
    if (isset($_SESSION['id_usuario_smo']) && (int)$_SESSION['id_usuario_smo'] > 0 && $pagina_acessada != "/smo/altera_senha.php") {
        include_once './menu_logado.php';
    } else {
        include_once './menu.php';
    }
    ?>

    <!-- Conteúdo Principal -->
    <main id="main-content" class="flex-grow-1">