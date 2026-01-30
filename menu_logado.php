<header id="header" class="fixed-top d-flex align-items-center">
    <div class="container d-flex justify-content-between">

        <div class="logo d-flex align-items-center">
            <h1><a href="<?= $_SESSION['perfil_smo'] == 'admin' ? 'index.php' : 'distribuidos_om_1_fase.php' ?>"><i class="fa fa-stethoscope"></i> SiSel - SMO</a></h1>
        </div>

        <nav id="navbar" class="navbar">
            <ul>
                <?php if ($_SESSION['perfil_smo'] == 'admin') : ?>
                    <li class="<?= $_SESSION['perfil_smo'] != 'admin' ? 'menu-hidden' : '' ?>">
                        <a class="nav-link" href="index.php">Painel Inicial</a>
                    </li>
                <?php endif; ?>

                <?php if ($_SESSION['perfil_smo'] == 'operador') : ?>
                    <li>
                        <a class="nav-link" href="distribuidos_om_1_fase.php">Distribuídos OM 1ª Fase</a>
                    </li>

                    <li>
                        <a class="nav-link" href="revisao_medica.php">Revisão Médica</a>
                    </li>
                <?php endif; ?>

                <?php if ($_SESSION['perfil_smo'] == 'admin') : ?>

                    <li>
                        <a class="nav-link" href="obrigatorios.php">Obrigatórios</a>
                    </li>

                    <li>
                        <a class="nav-link" href="pre_distribuicao.php">Pré Distribuição MFDV</a>
                    </li>

                    <li>
                        <a class="nav-link" href="relatorios.php">Publicações e Documentos</a>
                    </li>

                    <li class="dropdown">
                        <a href="#">
                            <span>Cadastros</span>
                            <i class="bi bi-chevron-down"></i>
                        </a>
                        <ul>
                            <li><a href="obrigatorio_cadastra.php">Obrigatório</a></li>
                            <li><a href="usuarios.php">Usuário</a></li>
                            <li><a href="junta_saude_cadastra.php">JISE</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="#">
                            <span>Auditoria</span>
                            <i class="bi bi-chevron-down"></i>
                        </a>
                        <ul>
                            <li><a href="auditoria.php">Logs</a></li>
                            <li><a href="acessos.php">Acessos</a></li>
                        </ul>
                    </li>

                <?php endif; ?>

                <li>
                    <a class="nav-link" href="controller/logout.php">Sair</a>
                </li>

            </ul>

            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav>
    </div>
</header>