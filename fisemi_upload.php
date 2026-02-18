<?php
include_once 'header.php';

if ($_SESSION['perfil_smo'] != "admin") {
    erro($BASE_URL, 2, 6549874960, $pagina_atual, "usuario!admin", "Não foi possível acessar a página!");
    exit();
}

if (!isset($_SESSION['mensagem'])) $_SESSION['mensagem'] = null;
?>

<main id="main">
    <section class="breadcrumbs">
        <div class="container">
            <div class="section-title">
                <h1><b><i class="fas fa-file-import"></i> Importar FISEMI via OCR</b></h1>
            </div>
        </div>
    </section>
</main>

<section class="contact">
    <div class="container">

        <!-- Upload -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-upload me-2"></i>Upload do PDF</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Selecione um PDF contendo as FISEMIs escaneadas (uma por página). O sistema irá extrair <strong>Nome Completo</strong> e <strong>CPF</strong> de cada página.</p>

                <div class="row align-items-end g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Arquivo PDF <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="input-pdf" accept="application/pdf">
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-primary w-100" id="btn-processar" disabled>
                            <i class="fas fa-cogs me-2"></i>Processar OCR
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-secondary w-100" id="btn-limpar" style="display:none;">
                            <i class="fas fa-eraser me-2"></i>Limpar
                        </button>
                    </div>
                </div>

                <!-- Progresso -->
                <div id="area-progresso" class="mt-4" style="display:none;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span id="texto-progresso" class="fw-semibold">Processando...</span>
                        <span id="contador-progresso" class="text-muted">0/0</span>
                    </div>
                    <div class="progress" style="height: 25px;">
                        <div id="barra-progresso" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;">0%</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resultados -->
        <div id="area-resultados" class="card shadow-sm mb-4" style="display:none;">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-table me-2"></i>Dados Extraídos - Revisão</h5>
                <div>
                    <span id="resumo-extracao" class="text-muted me-3"></span>
                    <button type="button" class="btn btn-sm btn-outline-secondary me-2" id="btn-selecionar-todos">
                        <i class="fas fa-check-double me-1"></i>Selecionar Todos
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:40px;" class="text-center"><input type="checkbox" id="check-todos" checked></th>
                                <th style="width:50px;" class="text-center">Pág</th>
                                <th>Nome Completo</th>
                                <th style="width:180px;">CPF</th>
                                <th style="width:100px;" class="text-center">Status</th>
                                <th style="width:80px;" class="text-center">Preview</th>
                            </tr>
                        </thead>
                        <tbody id="tabela-resultados"></tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="button" class="btn btn-success btn-lg" id="btn-cadastrar" disabled>
                    <i class="fas fa-user-plus me-2"></i>Cadastrar Selecionados (<span id="total-selecionados">0</span>)
                </button>
            </div>
        </div>

        <!-- Resultado do cadastro -->
        <div id="area-resultado-cadastro" class="card shadow-sm mb-4" style="display:none;">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>Resultado do Cadastro</h5>
            </div>
            <div class="card-body" id="conteudo-resultado-cadastro"></div>
        </div>

        <!-- Modal Preview -->
        <div class="modal fade" id="modal-preview" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Preview da Página <span id="modal-pagina-nr"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <canvas id="canvas-preview" style="max-width:100%; border:1px solid #ddd;"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<script>
(function() {
    // Configura PDF.js worker
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'libs/pdfjs/pdf.worker.min.js';

    var pdfDoc = null;
    var resultados = [];
    var paginasCanvas = {};

    // Elementos
    var inputPdf = document.getElementById('input-pdf');
    var btnProcessar = document.getElementById('btn-processar');
    var btnLimpar = document.getElementById('btn-limpar');
    var btnCadastrar = document.getElementById('btn-cadastrar');
    var areaProgresso = document.getElementById('area-progresso');
    var areaResultados = document.getElementById('area-resultados');

    // Habilita botão ao selecionar arquivo
    inputPdf.addEventListener('change', function() {
        btnProcessar.disabled = !this.files.length;
    });

    // Processar OCR
    btnProcessar.addEventListener('click', async function() {
        var file = inputPdf.files[0];
        if (!file) return;

        btnProcessar.disabled = true;
        btnLimpar.style.display = 'inline-block';
        areaProgresso.style.display = 'block';
        areaResultados.style.display = 'none';
        resultados = [];
        paginasCanvas = {};

        try {
            // Carrega PDF
            document.getElementById('texto-progresso').textContent = 'Carregando PDF...';
            var arrayBuffer = await file.arrayBuffer();
            pdfDoc = await pdfjsLib.getDocument({ data: arrayBuffer }).promise;
            var totalPaginas = pdfDoc.numPages;

            document.getElementById('contador-progresso').textContent = '0/' + totalPaginas;

            // Inicializa Tesseract worker (baixa worker + WASM core + dados do idioma via CDN)
            document.getElementById('texto-progresso').textContent = 'Inicializando OCR (baixando dados do idioma português, aguarde...)';
            console.log('Tesseract: criando worker...');

            var worker = await Tesseract.createWorker('por', 1, {
                logger: function(m) {
                    console.log('Tesseract:', m.status, m.progress);
                    if (m.status) {
                        var statusMap = {
                            'loading tesseract core': 'Carregando motor OCR...',
                            'initializing tesseract': 'Inicializando Tesseract...',
                            'loading language traineddata': 'Baixando dados do idioma português...',
                            'loaded language traineddata': 'Idioma carregado!',
                            'initializing api': 'Inicializando API...',
                            'recognizing text': 'Reconhecendo texto...'
                        };
                        var texto = statusMap[m.status] || m.status;
                        if (m.progress && m.progress > 0) {
                            texto += ' (' + Math.round(m.progress * 100) + '%)';
                        }
                        document.getElementById('texto-progresso').textContent = texto;
                    }
                }
            });

            console.log('Tesseract: worker criado com sucesso!');

            // Processa cada página
            for (var i = 1; i <= totalPaginas; i++) {
                atualizarProgresso(i, totalPaginas, 'Processando página ' + i + ' de ' + totalPaginas + '...');

                var page = await pdfDoc.getPage(i);
                var viewport = page.getViewport({ scale: 2.0 }); // 2x para melhor OCR

                var canvas = document.createElement('canvas');
                canvas.width = viewport.width;
                canvas.height = viewport.height;
                var ctx = canvas.getContext('2d');

                await page.render({ canvasContext: ctx, viewport: viewport }).promise;
                paginasCanvas[i] = canvas;

                // OCR na página renderizada
                console.log('Tesseract: reconhecendo página ' + i + '...');
                var resultado = await worker.recognize(canvas);
                var textoOcr = resultado.data.text;
                console.log('Tesseract: página ' + i + ' - texto extraído (' + textoOcr.length + ' chars)');

                // Extrai dados
                var dados = extrairDados(textoOcr);
                dados.pagina = i;
                dados.textoOcr = textoOcr;
                resultados.push(dados);
            }

            await worker.terminate();

            atualizarProgresso(totalPaginas, totalPaginas, 'Concluído!');
            exibirResultados();

        } catch (err) {
            console.error('Erro no processamento OCR:', err);
            var msgErro = (err && err.message) ? err.message : (typeof err === 'string' ? err : JSON.stringify(err));
            document.getElementById('texto-progresso').textContent = 'Erro: ' + msgErro;
            document.getElementById('barra-progresso').classList.add('bg-danger');
            document.getElementById('barra-progresso').classList.remove('progress-bar-animated');
        }

        btnProcessar.disabled = false;
    });

    // Extrai Nome e CPF do texto OCR
    function extrairDados(texto) {
        var cpf = '';
        var nome = '';

        // Extrai CPF - padrão XXX.XXX.XXX-XX (com variações de OCR)
        var regexCpf = /(\d{3})[.\s,]?(\d{3})[.\s,]?(\d{3})[-.\s,]?(\d{2})/;
        var matchCpf = texto.match(regexCpf);
        if (matchCpf) {
            cpf = matchCpf[1] + '.' + matchCpf[2] + '.' + matchCpf[3] + '-' + matchCpf[4];
        }

        // Extrai Nome - busca após "NOME" no texto
        var linhas = texto.split('\n').map(function(l) { return l.trim(); }).filter(function(l) { return l.length > 0; });

        for (var i = 0; i < linhas.length; i++) {
            var linha = linhas[i];
            // Procura linha que contém "NOME" (label do campo)
            if (/\bNOME\b/i.test(linha)) {
                // Tenta extrair o valor após "NOME" na mesma linha
                var aposNome = linha.replace(/.*NOME\s*[:.]?\s*/i, '').trim();
                if (aposNome.length > 5) {
                    nome = aposNome.toUpperCase();
                    break;
                }
                // Se não encontrou na mesma linha, pega a próxima
                if (i + 1 < linhas.length && linhas[i + 1].length > 5) {
                    nome = linhas[i + 1].toUpperCase();
                    break;
                }
            }
        }

        // Fallback: primeira sequência longa em maiúsculas (provável nome)
        if (!nome) {
            for (var j = 0; j < linhas.length; j++) {
                var l = linhas[j];
                if (l.length > 10 && l === l.toUpperCase() && /^[A-ZÀÁÂÃÉÊÍÓÔÕÚÇ\s]+$/.test(l) && l.split(/\s+/).length >= 3) {
                    nome = l;
                    break;
                }
            }
        }

        // Status
        var status = 'erro';
        if (nome && cpf) status = 'ok';
        else if (nome || cpf) status = 'parcial';

        return { nome: nome, cpf: cpf, status: status };
    }

    // Atualiza barra de progresso
    function atualizarProgresso(atual, total, texto) {
        var pct = Math.round((atual / total) * 100);
        document.getElementById('barra-progresso').style.width = pct + '%';
        document.getElementById('barra-progresso').textContent = pct + '%';
        document.getElementById('texto-progresso').textContent = texto;
        document.getElementById('contador-progresso').textContent = atual + '/' + total;
    }

    // Exibe tabela de resultados
    function exibirResultados() {
        var tbody = document.getElementById('tabela-resultados');
        tbody.innerHTML = '';

        var okCount = 0, parcialCount = 0, erroCount = 0;

        resultados.forEach(function(r, idx) {
            var statusBadge = '';
            if (r.status === 'ok') { statusBadge = '<span class="badge bg-success">OK</span>'; okCount++; }
            else if (r.status === 'parcial') { statusBadge = '<span class="badge bg-warning text-dark">Parcial</span>'; parcialCount++; }
            else { statusBadge = '<span class="badge bg-danger">Erro</span>'; erroCount++; }

            var checked = r.status !== 'erro' ? 'checked' : '';

            var tr = document.createElement('tr');
            tr.setAttribute('data-idx', idx);
            tr.innerHTML =
                '<td class="text-center"><input type="checkbox" class="check-item" ' + checked + ' data-idx="' + idx + '"></td>' +
                '<td class="text-center">' + r.pagina + '</td>' +
                '<td><input type="text" class="form-control form-control-sm input-nome" value="' + (r.nome || '') + '" data-idx="' + idx + '" placeholder="Nome não encontrado"></td>' +
                '<td><input type="text" class="form-control form-control-sm input-cpf" value="' + (r.cpf || '') + '" data-idx="' + idx + '" placeholder="CPF não encontrado"></td>' +
                '<td class="text-center">' + statusBadge + '</td>' +
                '<td class="text-center"><button class="btn btn-sm btn-outline-primary btn-preview" data-pagina="' + r.pagina + '" title="Ver página"><i class="fas fa-eye"></i></button></td>';
            tbody.appendChild(tr);
        });

        document.getElementById('resumo-extracao').textContent =
            okCount + ' OK, ' + parcialCount + ' parcial, ' + erroCount + ' erro — Total: ' + resultados.length + ' páginas';

        areaResultados.style.display = 'block';
        atualizarContadorSelecionados();
    }

    // Atualiza contador de selecionados
    function atualizarContadorSelecionados() {
        var checks = document.querySelectorAll('.check-item:checked');
        var total = checks.length;
        document.getElementById('total-selecionados').textContent = total;
        btnCadastrar.disabled = total === 0;
    }

    // Checkbox geral
    document.getElementById('check-todos').addEventListener('change', function() {
        var checked = this.checked;
        document.querySelectorAll('.check-item').forEach(function(cb) { cb.checked = checked; });
        atualizarContadorSelecionados();
    });

    // Selecionar Todos (botão)
    document.getElementById('btn-selecionar-todos').addEventListener('click', function() {
        document.querySelectorAll('.check-item').forEach(function(cb) { cb.checked = true; });
        document.getElementById('check-todos').checked = true;
        atualizarContadorSelecionados();
    });

    // Delegação de eventos na tabela
    document.getElementById('tabela-resultados').addEventListener('change', function(e) {
        if (e.target.classList.contains('check-item')) {
            atualizarContadorSelecionados();
        }
        if (e.target.classList.contains('input-nome')) {
            resultados[e.target.dataset.idx].nome = e.target.value;
        }
        if (e.target.classList.contains('input-cpf')) {
            resultados[e.target.dataset.idx].cpf = e.target.value;
        }
    });

    document.getElementById('tabela-resultados').addEventListener('input', function(e) {
        if (e.target.classList.contains('input-nome')) {
            resultados[e.target.dataset.idx].nome = e.target.value;
        }
        if (e.target.classList.contains('input-cpf')) {
            resultados[e.target.dataset.idx].cpf = e.target.value;
        }
    });

    // Preview
    document.getElementById('tabela-resultados').addEventListener('click', function(e) {
        var btn = e.target.closest('.btn-preview');
        if (!btn) return;
        var pagina = parseInt(btn.dataset.pagina);
        var canvasOrigem = paginasCanvas[pagina];
        if (!canvasOrigem) return;

        var canvasPreview = document.getElementById('canvas-preview');
        canvasPreview.width = canvasOrigem.width;
        canvasPreview.height = canvasOrigem.height;
        canvasPreview.getContext('2d').drawImage(canvasOrigem, 0, 0);
        document.getElementById('modal-pagina-nr').textContent = pagina;
        new bootstrap.Modal(document.getElementById('modal-preview')).show();
    });

    // Limpar
    btnLimpar.addEventListener('click', function() {
        inputPdf.value = '';
        resultados = [];
        paginasCanvas = {};
        pdfDoc = null;
        areaProgresso.style.display = 'none';
        areaResultados.style.display = 'none';
        document.getElementById('area-resultado-cadastro').style.display = 'none';
        btnProcessar.disabled = true;
        btnLimpar.style.display = 'none';
        document.getElementById('barra-progresso').style.width = '0%';
    });

    // Cadastrar em lote
    btnCadastrar.addEventListener('click', function() {
        var selecionados = [];
        document.querySelectorAll('.check-item:checked').forEach(function(cb) {
            var idx = parseInt(cb.dataset.idx);
            var nome = document.querySelector('.input-nome[data-idx="' + idx + '"]').value.trim();
            var cpf = document.querySelector('.input-cpf[data-idx="' + idx + '"]').value.trim();
            if (nome && cpf) {
                selecionados.push({ nome: nome, cpf: cpf });
            }
        });

        if (selecionados.length === 0) {
            alert('Nenhum registro válido selecionado (Nome e CPF são obrigatórios).');
            return;
        }

        if (!confirm('Cadastrar ' + selecionados.length + ' obrigatório(s)?')) return;

        btnCadastrar.disabled = true;
        btnCadastrar.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Cadastrando...';

        $.ajax({
            url: 'controller/fisemi_cadastra_lote.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                obrigatorios: selecionados,
                crip: '<?php echo hash("sha256", $_SESSION["chave"] . "fisemi_lote"); ?>'
            }),
            success: function(response) {
                exibirResultadoCadastro(response);
            },
            error: function(xhr) {
                alert('Erro ao cadastrar: ' + xhr.statusText);
            },
            complete: function() {
                btnCadastrar.disabled = false;
                btnCadastrar.innerHTML = '<i class="fas fa-user-plus me-2"></i>Cadastrar Selecionados (<span id="total-selecionados">' + selecionados.length + '</span>)';
            }
        });
    });

    // Exibe resultado do cadastro
    function exibirResultadoCadastro(resp) {
        var html = '<div class="row g-3 mb-3">';
        html += '<div class="col-md-4"><div class="alert alert-success mb-0 text-center"><h3 class="mb-0">' + (resp.cadastrados || 0) + '</h3><small>Cadastrados</small></div></div>';
        html += '<div class="col-md-4"><div class="alert alert-warning mb-0 text-center"><h3 class="mb-0">' + (resp.duplicados || 0) + '</h3><small>Já existiam</small></div></div>';
        html += '<div class="col-md-4"><div class="alert alert-danger mb-0 text-center"><h3 class="mb-0">' + (resp.erros || 0) + '</h3><small>Erros</small></div></div>';
        html += '</div>';

        if (resp.detalhes && resp.detalhes.length > 0) {
            html += '<table class="table table-sm"><thead><tr><th>Nome</th><th>CPF</th><th>Resultado</th></tr></thead><tbody>';
            resp.detalhes.forEach(function(d) {
                var badgeClass = d.status === 'cadastrado' ? 'success' : (d.status === 'duplicado' ? 'warning' : 'danger');
                html += '<tr><td>' + d.nome + '</td><td>' + d.cpf + '</td><td><span class="badge bg-' + badgeClass + '">' + d.status + '</span></td></tr>';
            });
            html += '</tbody></table>';
        }

        document.getElementById('conteudo-resultado-cadastro').innerHTML = html;
        document.getElementById('area-resultado-cadastro').style.display = 'block';
    }
})();
</script>

<?php include_once 'footer.php'; ?>
