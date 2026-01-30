<header id="header" class="fixed-top d-flex align-items-center">
    <div class="container d-flex justify-content-between">

        <div class="logo">
          <h1><a href="obrigatorios.php">SiSel MFDV - SMO</a></h1>
        </div>

        <nav id="navbar" class="navbar">
            <ul>
                <li <?php if($_SESSION['perfil_smo'] != 'operador') echo " hidden " ?>><a class="nav-link scrollto" href="distribuidos_om_1_fase.php">Distribuídos OM 1ª Fase</a></li>
                <li><a hidden class="nav-link scrollto" href="revisao_medica.php">Revisão Médica</a></li> 
                <li <?php if($_SESSION['perfil_smo'] != 'admin') echo " hidden " ?>><a class="nav-link scrollto" href="obrigatorios.php">Início</a></li>
                <li class="dropdown" <?php if($_SESSION['perfil_smo'] != 'admin') echo " hidden " ?>><a href="#"><span>Cadastros</span> <i class="bi bi-chevron-down"></i></a>
                    <ul>
                        <li><a href="obrigatorio_cadastra.php">Obrigatório</a></li>
                        <li><a href="usuarios.php">Usuário</a></li>
                    </ul>
                </li>
                <li <?php if($_SESSION['perfil_smo'] != 'admin') echo " hidden " ?>><a class="nav-link scrollto" href="pre_distribuicao.php">Pré Distribuição MFDV</a></li>
                <li><a hidden class="nav-link scrollto" href="acessos.php">Acessos</a></li>
                <li class="dropdown" <?php if($_SESSION['perfil_smo'] != 'admin') echo " hidden " ?>><a href="#"><span>Inspeções de Saúde</span> <i class="bi bi-chevron-down"></i></a>
                <ul>
                    <li><a href="lista_presenca.php">Lista de Presença</a></li>
                        <li><a href="relatorio_comparecimento.php">Relatório de Inspecionados</a></li>
                        <li><a href="junta_saude_cadastra.php">Junta de Saúde</a></li>
                    </ul>   
                <li <?php if($_SESSION['perfil_smo'] != 'admin') echo " hidden " ?>><a class="nav-link scrollto" href="auditoria.php">Auditoria</a></li>
                <li><a class="nav-link scrollto" href="controller/logout.php">LogOut</a></li>
                
            </ul>
            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav>
    </div>
</header>