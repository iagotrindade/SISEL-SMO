# 🧩 Biblioteca de Componentes - SMO

Componentes prontos para usar nas páginas do sistema.

---

## 📦 Cards

### Card Básico
```html
<div class="card">
    <h4>Título do Card</h4>
    <p>Conteúdo do card aqui...</p>
</div>
```

### Card com Ícone
```html
<div class="icon-box">
    <div class="icon">
        <i class="bi bi-people-fill"></i>
    </div>
    <h4 class="title">
        <a href="#">Título do Card</a>
    </h4>
    <p class="description">Descrição ou número</p>
</div>
```

### Card Estatística Colorido
```html
<div class="icon-box" style="background: linear-gradient(135deg, rgba(0, 255, 136, 0.1), rgba(0, 204, 106, 0.05)); border-color: var(--accent-green);">
    <div class="icon" style="background: linear-gradient(135deg, var(--accent-green), #00cc6a);">
        <i class="bi bi-graph-up"></i>
    </div>
    <h4 class="title">Total de Registros</h4>
    <p class="description" style="color: var(--accent-green); font-size: 2.5rem;">
        1.234
    </p>
    <p style="color: var(--text-muted); font-size: 0.9rem;">
        <i class="bi bi-arrow-up me-1"></i> +15% este mês
    </p>
</div>
```

---

## 🔘 Botões

### Botão Primário
```html
<button type="submit" class="btn btn-primary">
    <i class="bi bi-check-circle me-2"></i>
    Salvar
</button>
```

### Botão Secundário
```html
<button type="button" class="btn btn-secondary">
    <i class="bi bi-x-circle me-2"></i>
    Cancelar
</button>
```

### Botão Perigo
```html
<button type="button" class="btn btn-danger">
    <i class="bi bi-trash me-2"></i>
    Excluir
</button>
```

### Botão com Loading
```html
<button type="submit" class="btn btn-primary" disabled>
    <i class="bi bi-hourglass-split me-2"></i>
    Processando...
</button>
```

### Grupo de Botões
```html
<div class="form-actions">
    <button type="button" class="btn btn-secondary">
        <i class="bi bi-x-circle me-2"></i>
        Cancelar
    </button>
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-check-circle me-2"></i>
        Confirmar
    </button>
</div>
```

---

## 📝 Formulários

### Input com Ícone
```html
<div class="form-group">
    <label for="nome">
        <i class="bi bi-person-fill"></i>
        Nome Completo
    </label>
    <div class="input-with-icon">
        <i class="bi bi-person"></i>
        <input type="text" id="nome" name="nome" class="form-control" placeholder="Digite seu nome">
    </div>
</div>
```

### Input Obrigatório
```html
<div class="form-group required">
    <label for="cpf">CPF</label>
    <input type="text" id="cpf" name="cpf" class="form-control" required>
</div>
```

### Select Simples
```html
<div class="form-group">
    <label for="status">
        <i class="bi bi-funnel"></i>
        Status
    </label>
    <select id="status" name="status" class="form-control">
        <option value="">Selecione...</option>
        <option value="ativo">Ativo</option>
        <option value="inativo">Inativo</option>
    </select>
</div>
```

### Select Múltiplo (Chosen)
```html
<div class="form-group">
    <label><i class="bi bi-tags"></i> Especialidades</label>
    <select name="especialidades[]" class="chosen-select" multiple style="width: 100%">
        <option value="">Selecione...</option>
        <option value="1">Cardiologia</option>
        <option value="2">Neurologia</option>
        <option value="3">Ortopedia</option>
    </select>
</div>
```

### Textarea
```html
<div class="form-group">
    <label for="observacoes">Observações</label>
    <textarea id="observacoes" name="observacoes" class="form-control" rows="4" placeholder="Digite suas observações..."></textarea>
</div>
```

---

## 📊 Tabelas

### Tabela Simples
```html
<div class="dataTables_wrapper">
    <table class="tabela-moderna">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>001</td>
                <td>João Silva</td>
                <td><span class="badge badge-success">Ativo</span></td>
                <td>
                    <a href="editar.php?id=1"><img src="imagens/editar.png" width="20"></a>
                    <a href="apagar.php?id=1"><img src="imagens/apagar.png" width="20"></a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
```

---

## 🏷️ Badges/Tags

### Badge de Status
```html
<span class="badge badge-success">Aprovado</span>
<span class="badge badge-warning">Pendente</span>
<span class="badge badge-danger">Reprovado</span>
<span class="badge badge-info">Em Análise</span>
```

### Tag de Filtro Ativo
```html
<div class="active-filters-container">
    <div class="active-filter-tag">
        Especialidade: Cardiologia
        <i class="bi bi-x-lg"></i>
    </div>
    <div class="active-filter-tag">
        Status: Ativo
        <i class="bi bi-x-lg"></i>
    </div>
</div>
```

---

## ⚠️ Alertas e Mensagens

### Alerta de Sucesso
```html
<div class="alert alert-success" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i>
    Operação realizada com sucesso!
</div>
```

### Alerta de Erro
```html
<div class="alert alert-danger" role="alert">
    <i class="bi bi-x-circle-fill me-2"></i>
    Erro ao processar a operação.
</div>
```

### Alerta de Aviso
```html
<div class="alert alert-warning" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>
    Atenção: Verifique os dados antes de continuar.
</div>
```

### Alerta de Info
```html
<div class="alert alert-info" role="alert">
    <i class="bi bi-info-circle-fill me-2"></i>
    Informação importante sobre o sistema.
</div>
```

---

## 📑 Seção de Filtros

### Container de Filtros Expansível
```html
<div class="filtros-container">
    <h3>
        <i class="bi bi-funnel-fill"></i>
        Filtros Avançados
    </h3>
    
    <form action="" method="get">
        <div class="row">
            <div class="col-md-6 form-group">
                <label><i class="bi bi-calendar"></i> Data Inicial</label>
                <input type="date" name="data_inicio" class="form-control">
            </div>
            
            <div class="col-md-6 form-group">
                <label><i class="bi bi-calendar"></i> Data Final</label>
                <input type="date" name="data_fim" class="form-control">
            </div>
        </div>
        
        <div class="form-actions">
            <button type="reset" class="btn btn-clear-filters">
                <i class="bi bi-x-circle me-2"></i>
                Limpar Filtros
            </button>
            <button type="submit" class="btn btn-apply-filters">
                <i class="bi bi-funnel me-2"></i>
                Aplicar Filtros
            </button>
        </div>
    </form>
</div>
```

---

## 📋 Lista de Informações

### Lista com Ícones
```html
<div style="display: flex; flex-direction: column; gap: 1rem;">
    <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: var(--gray-dark); border-radius: var(--border-radius);">
        <span style="color: var(--text-secondary);">
            <i class="bi bi-person me-2"></i>Nome:
        </span>
        <strong style="color: var(--text-primary)">João Silva</strong>
    </div>
    
    <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: var(--gray-dark); border-radius: var(--border-radius);">
        <span style="color: var(--text-secondary);">
            <i class="bi bi-envelope me-2"></i>Email:
        </span>
        <strong style="color: var(--text-primary)">joao@exemplo.com</strong>
    </div>
</div>
```

---

## 🎯 Breadcrumbs

### Breadcrumbs Padrão
```html
<section class="breadcrumbs">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h1>
                <i class="bi bi-file-text me-3"></i>
                <b>Título da Página</b>
            </h1>
            <p style="color: var(--text-secondary); margin-top: 1rem;">
                Descrição ou subtítulo da página
            </p>
        </div>
    </div>
</section>
```

---

## 🎨 Grid de Ações Rápidas

### Grid 3 Colunas
```html
<div class="row">
    <div class="col-md-4 mb-3">
        <a href="cadastrar.php" class="btn btn-primary w-100" style="padding: 1.5rem; display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
            <i class="bi bi-plus-circle-fill" style="font-size: 2rem;"></i>
            <span>Cadastrar</span>
        </a>
    </div>
    
    <div class="col-md-4 mb-3">
        <a href="listar.php" class="btn btn-secondary w-100" style="padding: 1.5rem; display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
            <i class="bi bi-list-ul" style="font-size: 2rem;"></i>
            <span>Listar</span>
        </a>
    </div>
    
    <div class="col-md-4 mb-3">
        <a href="relatorios.php" class="btn btn-secondary w-100" style="padding: 1.5rem; display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
            <i class="bi bi-file-earmark-text" style="font-size: 2rem;"></i>
            <span>Relatórios</span>
        </a>
    </div>
</div>
```

---

## 💬 Toast Notifications (JavaScript)

### Usando JavaScript
```javascript
// Sucesso
SMO.showToast('Operação realizada com sucesso!', 'success');

// Erro
SMO.showToast('Erro ao processar', 'error');

// Aviso
SMO.showToast('Atenção necessária', 'warning');

// Info
SMO.showToast('Informação importante', 'info');
```

---

## ⏳ Loading Overlay (JavaScript)

```javascript
// Mostrar loading
SMO.showLoading();

// Esconder loading
SMO.hideLoading();

// Exemplo completo
SMO.showLoading();
fetch('api.php')
    .then(response => response.json())
    .then(data => {
        SMO.hideLoading();
        SMO.showToast('Dados carregados!', 'success');
    })
    .catch(error => {
        SMO.hideLoading();
        SMO.showToast('Erro ao carregar', 'error');
    });
```

---

## 🎨 Ícones Bootstrap Icons

Principais ícones úteis:

```html
<!-- Ações -->
<i class="bi bi-plus-circle-fill"></i>        <!-- Adicionar -->
<i class="bi bi-pencil-fill"></i>             <!-- Editar -->
<i class="bi bi-trash-fill"></i>              <!-- Excluir -->
<i class="bi bi-eye-fill"></i>                <!-- Visualizar -->
<i class="bi bi-search"></i>                  <!-- Buscar -->
<i class="bi bi-funnel-fill"></i>             <!-- Filtrar -->

<!-- Status -->
<i class="bi bi-check-circle-fill"></i>       <!-- Sucesso -->
<i class="bi bi-x-circle-fill"></i>           <!-- Erro -->
<i class="bi bi-exclamation-triangle-fill"></i> <!-- Aviso -->
<i class="bi bi-info-circle-fill"></i>        <!-- Info -->

<!-- Navegação -->
<i class="bi bi-arrow-left"></i>              <!-- Voltar -->
<i class="bi bi-arrow-right"></i>             <!-- Avançar -->
<i class="bi bi-house-fill"></i>              <!-- Home -->
<i class="bi bi-gear-fill"></i>               <!-- Configurações -->

<!-- Documentos -->
<i class="bi bi-file-earmark-text"></i>       <!-- Documento -->
<i class="bi bi-file-earmark-pdf"></i>        <!-- PDF -->
<i class="bi bi-download"></i>                <!-- Download -->
<i class="bi bi-upload"></i>                  <!-- Upload -->

<!-- Pessoas -->
<i class="bi bi-person-fill"></i>             <!-- Pessoa -->
<i class="bi bi-people-fill"></i>             <!-- Pessoas -->
<i class="bi bi-person-badge"></i>            <!-- Badge -->

<!-- Outros -->
<i class="bi bi-calendar-event"></i>          <!-- Calendário -->
<i class="bi bi-clock-fill"></i>              <!-- Relógio -->
<i class="bi bi-shield-check"></i>            <!-- Segurança -->
<i class="bi bi-graph-up"></i>                <!-- Gráfico -->
```

Ver todos em: https://icons.getbootstrap.com/

---

## 📱 Grid Responsivo

```html
<!-- 4 colunas em desktop, 2 em tablet, 1 em mobile -->
<div class="row">
    <div class="col-12 col-md-6 col-lg-3">Coluna 1</div>
    <div class="col-12 col-md-6 col-lg-3">Coluna 2</div>
    <div class="col-12 col-md-6 col-lg-3">Coluna 3</div>
    <div class="col-12 col-md-6 col-lg-3">Coluna 4</div>
</div>

<!-- 3 colunas em desktop, 1 em mobile -->
<div class="row">
    <div class="col-12 col-md-4">Coluna 1</div>
    <div class="col-12 col-md-4">Coluna 2</div>
    <div class="col-12 col-md-4">Coluna 3</div>
</div>
```

---

## 🎯 Dicas de Uso

1. **Sempre use ícones** para melhor UX
2. **Mantenha consistência** nas cores e espaçamentos
3. **Teste responsividade** em mobile
4. **Use animações com moderação**
5. **Prefira componentes prontos** desta biblioteca

---

**Use e abuse destes componentes para manter a consistência visual!** 🎨
