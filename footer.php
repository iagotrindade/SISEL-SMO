<!-- ======= Footer ======= -->
<footer id="footer">
    <div class="container">
        <div class="row footer-row">

            <!-- Informações do Sistema -->
            <div class="col-md-4 mb-3 text-start">
                <h5 class="footer-title">
                    <i class="fas fa-stethoscope me-2"></i>
                    SiSel - SMO
                </h5>
                <p class="footer-text">
                    Sistema de Seleção Serviço Médico Obrigatório<br>
                </p>
            </div>

            <!-- Links Rápidos -->
            <div class="col-md-4 mb-3 text-center">
                <h5 class="footer-title justify-content-center">Links Rápidos</h5>
                <div class="footer-links">
                    <?php if(isset($_SESSION['id_usuario_smo'])): ?>
                        <?php if($_SESSION['perfil_smo'] == 'admin'): ?>
                            <a href="obrigatorios.php" class="footer-link">
                                <i class="fas fa-chevron-right me-1"></i>Início
                            </a>
                            <a href="relatorios.php" class="footer-link">
                                <i class="fas fa-chevron-right me-1"></i>Relatórios
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>

                    <a href="https://siscant.3rm.eb.mil.br/3rm.php" target="_blank" class="footer-link">
                        <i class="fas fa-chevron-right me-1"></i>SiSCanT
                    </a>

                    <a href="https://3rm.eb.mil.br" target="_blank" class="footer-link">
                        <i class="fas fa-chevron-right me-1"></i>3ª Região Militar
                    </a>
                </div>
            </div>

            <!-- Informações de Contato/Suporte -->
            <div class="col-md-4 mb-3 text-end">
                <h5 class="footer-title justify-content-end">Suporte</h5>
                <p class="footer-text">
                    <i class="fas fa-envelope me-2 footer-contact-icon"></i>
                    selecao_mfdv@3rm.eb.mil.br<br>
                    <i class="fas fa-phone me-2 footer-contact-icon"></i>
                    (51) 3220-6676
                </p>
            </div>
        </div>

        <!-- Linha divisória -->
        <div class="footer-divider"></div>

        <!-- Copyright -->
        <div class="footer-copyright">
            <div class="footer-copyright-inner">
                <p class="footer-copyright-text">
                    &copy; Copyright 2023-<?php echo date('Y'); ?> <strong><span class="footer-brand">SiSel - SMO</span></strong>
                </p>
                <p class="footer-version">
                    Todos os direitos reservados | Versão 1.0.0
                </p>
                <div class="footer-badges">
                    <span class="footer-badge">
                        <i class="fas fa-shield-alt me-1 icon-success"></i>
                        Seguro
                    </span>
                    <span class="footer-badge">
                        <i class="fas fa-tachometer-alt me-1 icon-info"></i>
                        Rápido
                    </span>
                    <span class="footer-badge">
                        <i class="fas fa-check-circle me-1 icon-accent"></i>
                        Confiável
                    </span>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- End Footer -->

<!-- Back to Top Button -->
<a href="#" class="back-to-top d-flex align-items-center justify-content-center" onclick="window.scrollTo({top: 0, behavior: 'smooth'}); return false;">
    <i class="fas fa-arrow-up"></i>
</a>

<style>
.back-to-top {
    position: fixed;
    bottom: 15px;
    right: 15px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #006400;
    color: #fff;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    z-index: 996;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}
.back-to-top:hover {
    background: #004d00;
    color: #fff;
}
.back-to-top.active {
    opacity: 1;
    visibility: visible;
}
</style>

<!-- SELECT MULTIPLO -->
<script src="assets/select_multiplo/chosen.jquery.js" type="text/javascript"></script>
<script src="assets/select_multiplo/prism.js" type="text/javascript" charset="utf-8"></script> 
<script src="assets/select_multiplo/init.js" type="text/javascript" charset="utf-8"></script> 

<!-- Vendor JS Files -->
<!-- AOS REMOVIDO - ROLLBACK: <script src="assets/vendor/aos/aos.js"></script> -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- GLightbox REMOVIDO - ROLLBACK: <script src="assets/vendor/glightbox/js/glightbox.min.js"></script> -->
<!-- Isotope REMOVIDO - ROLLBACK: <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script> -->
<!-- Swiper REMOVIDO - ROLLBACK: <script src="assets/vendor/swiper/swiper-bundle.min.js"></script> -->

<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>

<!-- SMO Modern Features -->
<script src="assets/js/smo-modern.js"></script>

<script>
    // Back to top button
    const backtotop = document.querySelector('.back-to-top');
    if (backtotop) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 100) {
                backtotop.classList.add('active');
            } else {
                backtotop.classList.remove('active');
            }
        });
    }

    // Smooth scroll para links âncora
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href !== '#') {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
</script>

</body>
</html>
