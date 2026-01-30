<!-- ======= Footer ======= -->
<footer id="footer" style="margin-top: 4rem;">
    <div class="container">
        <div class="row" style="padding: 2rem 0;">
            
            <!-- Informações do Sistema -->
            <div class="col-md-4 mb-3" style="text-align: left;">
                <h5 style="color: var(--text-primary); font-weight: 700; margin-bottom: 1rem; display: flex; align-items: center;">
                    <i class="bi bi-shield-check me-2" style="color: var(--accent-green);"></i>
                    SiSel - SMO
                </h5>
                <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.6;">
                    Sistema de Seleção Serviço Militar Obrigatório<br>
                </p>
            </div>

            <!-- Links Rápidos -->
            <div class="col-md-4 mb-3" style="text-align: center;">
                <h5 style="color: var(--text-primary); font-weight: 700; margin-bottom: 1rem;">Links Rápidos</h5>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <?php if(isset($_SESSION['id_usuario_smo'])): ?>
                        <?php if($_SESSION['perfil_smo'] == 'admin'): ?>
                            <a href="obrigatorios.php" style="color: var(--text-secondary); font-size: 0.9rem; transition: var(--transition-fast);" onmouseover="this.style.color='var(--accent-green)'" onmouseout="this.style.color='var(--text-secondary)'">
                                <i class="bi bi-chevron-right me-1"></i>Início
                            </a>
                            <a href="relatorios.php" style="color: var(--text-secondary); font-size: 0.9rem; transition: var(--transition-fast);" onmouseover="this.style.color='var(--accent-green)'" onmouseout="this.style.color='var(--text-secondary)'">
                                <i class="bi bi-chevron-right me-1"></i>Relatórios
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>

                    <a href="https://siscant.3rm.eb.mil.br/3rm.php" target="_blank" style="color: var(--text-secondary); font-size: 0.9rem; transition: var(--transition-fast);" onmouseover="this.style.color='var(--accent-green)'" onmouseout="this.style.color='var(--text-secondary)'">
                        <i class="bi bi-chevron-right me-1"></i>SiSCanT
                    </a>

                    <a href="https://3rm.eb.mil.br" target="_blank" style="color: var(--text-secondary); font-size: 0.9rem; transition: var(--transition-fast);" onmouseover="this.style.color='var(--accent-green)'" onmouseout="this.style.color='var(--text-secondary)'">
                        <i class="bi bi-chevron-right me-1"></i>3ª Região Militar
                    </a>
                </div>
            </div>

            <!-- Informações de Contato/Suporte -->
            <div class="col-md-4 mb-3" style="text-align: right;">
                <h5 style="color: var(--text-primary); font-weight: 700; margin-bottom: 1rem;">Suporte</h5>
                <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.6;">
                    <i class="bi bi-envelope me-2" style="color: var(--accent-blue);"></i>
                    selecao_mfdv@3rm.eb.mil.br<br>
                    <i class="bi bi-telephone me-2" style="color: var(--accent-blue);"></i>
                    (51) 3220-6676
                </p>
            </div>
        </div>

        <!-- Linha divisória -->
        <div style="border-top: 1px solid var(--border-color); margin: 1rem 0;"></div>

        <!-- Copyright -->
        <div class="copyright" style="padding: 1.5rem 0; text-align: center;">
            <div style="display: flex; flex-direction: column; gap: 0.5rem; align-items: center;">
                <p style="margin: 0; color: var(--text-secondary); font-size: 0.9rem;">
                    &copy; Copyright 2023-<?php echo date('Y'); ?> <strong><span style="color: var(--accent-green);">SiSel - SMO</span></strong>
                </p>
                <p style="margin: 0; color: var(--text-muted); font-size: 0.85rem;">
                    Todos os direitos reservados | Versão 1.0.0
                </p>
                <div style="display: flex; gap: 1rem; margin-top: 0.5rem;">
                    <span style="color: var(--text-muted); font-size: 0.8rem; display: flex; align-items: center;">
                        <i class="bi bi-shield-lock-fill me-1" style="color: var(--success);"></i>
                        Seguro
                    </span>
                    <span style="color: var(--text-muted); font-size: 0.8rem; display: flex; align-items: center;">
                        <i class="bi bi-speedometer2 me-1" style="color: var(--info);"></i>
                        Rápido
                    </span>
                    <span style="color: var(--text-muted); font-size: 0.8rem; display: flex; align-items: center;">
                        <i class="bi bi-check-circle-fill me-1" style="color: var(--accent-green);"></i>
                        Confiável
                    </span>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- End Footer -->

<!-- Back to Top Button -->
<a href="#topo" class="back-to-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
</a>

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

<!-- Theme Switcher -->
<script src="assets/js/theme-switcher.js"></script>

<!-- Script para Back to Top -->
<script>
    // Back to top button
    let backtotop = document.querySelector('.back-to-top');
    if (backtotop) {
        const toggleBacktotop = () => {
            if (window.scrollY > 100) {
                backtotop.classList.add('active');
            } else {
                backtotop.classList.remove('active');
            }
        }
        window.addEventListener('load', toggleBacktotop);
        window.addEventListener('scroll', toggleBacktotop);
    }

    // Smooth scroll para links âncora
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href !== '#' && href !== '#topo') {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            } else if (href === '#topo') {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Inicializa AOS (Animate On Scroll)
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            mirror: false
        });
    }
</script>

</body>
</html>
