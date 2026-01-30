/**
 * SMO - Scripts Modernos para Interface
 * Funcionalidades JavaScript sem quebrar funcionalidades existentes
 */

(function ($) {
    'use strict';

    // ===== INICIALIZAÇÃO =====
    $(document).ready(function () {
        initModernFeatures();
        initTableEnhancements();
        initFormEnhancements();
        initFilterSystem();
    });

    // ===== RECURSOS MODERNOS =====
    function initModernFeatures() {

        // Smooth scroll (seguro)
        $(document).on('click', 'a[href^="#"]', function (e) {
            const href = $(this).attr('href');

            if (href && href !== '#' && href.length > 1) {
                const target = document.getElementById(href.substring(1));

                if (target) {
                    e.preventDefault();
                    $('html, body').animate({
                        scrollTop: $(target).offset().top - 80
                    }, 600);
                }
            }
        });

        // Auto-hide de alertas
        setTimeout(function () {
            $('.alert-success, font[color="green"]').fadeOut('slow', function () {
                $(this).remove();
            });
        }, 5000);

        // Mobile menu toggle
        $(document).on('click', '.mobile-nav-toggle', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const $navbar = $('#navbar');

            $navbar.toggleClass('navbar');
            $(this).toggleClass('bi-list bi-x');

            // força exibição do menu
            if ($navbar.hasClass('navbar-mobile')) {
                $navbar.find('ul').first().slideDown(200);
                $navbar.find('ul').first().toggleClass('flex-column');
            } else {
                $navbar.find('ul').first().slideUp(200);
                $navbar.find('ul').first().toggleClass('flex-column');
            }
        });
        

        // Dropdown mobile
        $(document).on('click', '.navbar .dropdown > a', function (e) {
            if ($('#navbar').hasClass('navbar-mobile') && $(this).next().length) {
                e.preventDefault();
                $(this).next().slideToggle(300);
                $(this).parent().toggleClass('active');
            }
        });
    }


    // ===== MELHORIAS DE TABELA =====
    function initTableEnhancements() {
        if ($.fn.DataTable) {
            $.extend(true, $.fn.dataTable.defaults, {
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json',
                    searchPlaceholder: 'Pesquisar...',
                    search: '<i class="bi bi-search"></i>',
                    lengthMenu: 'Exibir _MENU_ registros por página',
                    info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                    infoEmpty: 'Nenhum registro encontrado',
                    infoFiltered: '(filtrado de _MAX_ registros)',
                    zeroRecords: 'Nenhum registro encontrado',
                    emptyTable: 'Nenhum dado disponível na tabela',
                    paginate: {
                        first: '<i class="bi bi-chevron-double-left"></i>',
                        previous: '<i class="bi bi-chevron-left"></i>',
                        next: '<i class="bi bi-chevron-right"></i>',
                        last: '<i class="bi bi-chevron-double-right"></i>'
                    }
                },
                responsive: true,
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
                dom: '<"datatable-header"<"row"<"col-sm-6"l><"col-sm-6"f>>>rt<"datatable-footer"<"row"<"col-sm-6"i><"col-sm-6"p>>>',
                initComplete: function () {
                    const $table = $(this);

                    // evita wrap duplicado
                    if (!$table.parent().hasClass('table-loading-wrapper')) {
                        $table.wrap('<div class="table-loading-wrapper"></div>');
                    }

                    $table.closest('.dataTables_wrapper')
                        .find('.dataTables_filter input')
                        .attr('placeholder', 'Buscar...');
                }
            });

            $('.tabela-moderna').each(function () {
                if (!$.fn.DataTable.isDataTable(this)) {
                    $(this).DataTable();
                }
            });
        }

        // Hover visual
        $('table').on('mouseenter', 'tbody tr', function () {
            $(this).addClass('row-hover');
        }).on('mouseleave', 'tbody tr', function () {
            $(this).removeClass('row-hover');
        });

        // Loading apenas em navegação real
        $('table').on('click', 'a[href], button', function () {
            const $btn = $(this);
            const href = $btn.attr('href');

            if (!$btn.data('no-loading') && href && !href.startsWith('#')) {
                showLoading();
                setTimeout(hideLoading, 3000); // fallback
            }
        });
    }

    // ===== MELHORIAS DE FORMULÁRIO =====
    function initFormEnhancements() {

        // Prevenir envio duplo
        $('form').on('submit', function () {
            const $form = $(this);
            const $submitBtn = $form.find('button[type="submit"], input[type="submit"]');

            if ($form.data('submitting')) return false;

            $form.data('submitting', true);

            if ($submitBtn.length) {
                if (!$submitBtn.data('original-html')) {
                    $submitBtn.data('original-html', $submitBtn.html());
                }

                $submitBtn.prop('disabled', true)
                    .html('<i class="bi bi-hourglass-split me-2"></i>Processando...');
            }

            // fallback reativação
            setTimeout(function () {
                $form.data('submitting', false);

                if ($submitBtn.length) {
                    $submitBtn.prop('disabled', false)
                        .html($submitBtn.data('original-html'));
                }
            }, 10000);
        });

        // Auto-resize textarea
        $('textarea').each(function () {
            this.style.height = this.scrollHeight + 'px';
        }).on('input', function () {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });

        // Confirmação de exclusão (legacy + novo padrão)
        $(document).on('click', 'a[href*="apaga"], button[name*="apaga"], [data-confirm-delete]', function (e) {
            if (!confirm('Tem certeza que deseja excluir este item?')) {
                e.preventDefault();
                return false;
            }
        });

        // Contador de caracteres
        $('input[maxlength], textarea[maxlength]').each(function () {
            const $input = $(this);
            const maxLength = parseInt($input.attr('maxlength'), 10);
            const $counter = $('<span class="char-counter"></span>');

            $input.after($counter);

            function updateCounter() {
                const remaining = maxLength - $input.val().length;
                $counter.text(remaining + ' caracteres restantes')
                    .css('color', remaining < 10 ? 'var(--danger)' : 'var(--text-muted)');
            }

            $input.on('input', updateCounter);
            updateCounter();
        });
    }

    // ===== SISTEMA DE FILTROS =====
    function initFilterSystem() {

        $('.toggle-filters-btn').on('click', function () {
            $(this).toggleClass('collapsed');
            $(this).next('.filters-collapse').toggleClass('show');
            $(this).find('i').toggleClass('bi-chevron-up bi-chevron-down');
        });

        updateFilterCount();

        $('.filtros-container select, .filtros-container input').on('change input', updateFilterCount);

        $('.btn-clear-filters').on('click', function (e) {
            e.preventDefault();
            clearAllFilters();
        });

        $(document).on('click', '.active-filter-tag i', function () {
            $(this).parent().fadeOut(300, function () {
                $(this).remove();
                updateFilterCount();
            });
        });
    }

    function updateFilterCount() {
        let activeFilters = 0;

        $('.chosen-select').each(function () {
            const value = $(this).val();
            if (Array.isArray(value)) {
                activeFilters += value.filter(v => v).length;
            } else if (value) {
                activeFilters++;
            }
        });

        $('.filtros-container input[type="text"], .filtros-container input[type="date"]').each(function () {
            if ($(this).val()) activeFilters++;
        });

        const $badge = $('.filter-badge');

        if (activeFilters > 0) {
            if (!$badge.length) {
                $('.toggle-filters-btn, .filtros-container h3')
                    .append('<span class="filter-badge">' + activeFilters + '</span>');
            } else {
                $badge.text(activeFilters);
            }
        } else {
            $badge.remove();
        }
    }

    function clearAllFilters() {
        $('.chosen-select').val('').trigger('chosen:updated');
        $('.filtros-container input[type="text"], .filtros-container input[type="date"]').val('');

        $('.active-filter-tag').fadeOut(300, function () {
            $(this).remove();
        });

        updateFilterCount();
        showToast('Filtros limpos com sucesso', 'info');
    }

    // ===== UTILITÁRIOS =====
    function showLoading() {
        if (!$('#loading-overlay').length) {
            $('body').append('<div id="loading-overlay" class="loading-overlay"><div class="loading-spinner"></div></div>');
        }
        $('#loading-overlay').fadeIn(200);
    }

    function hideLoading() {
        $('#loading-overlay').fadeOut(200);
    }

    function showToast(message, type = 'success') {
        const icons = {
            success: 'bi-check-circle-fill',
            error: 'bi-x-circle-fill',
            warning: 'bi-exclamation-triangle-fill',
            info: 'bi-info-circle-fill'
        };

        const $toast = $('<div class="custom-toast toast-' + type + '"></div>');
        const $icon = $('<i class="bi ' + icons[type] + '"></i>');
        const $text = $('<span></span>').text(message); // escape XSS

        $toast.append($icon).append($text);
        $('body').append($toast);

        setTimeout(() => $toast.addClass('show'), 10);

        setTimeout(() => {
            $toast.removeClass('show');
            setTimeout(() => $toast.remove(), 300);
        }, 3000);
    }

    // Namespace global
    window.SMO = {
        showLoading,
        hideLoading,
        showToast,
        updateFilterCount
    };

})(jQuery);