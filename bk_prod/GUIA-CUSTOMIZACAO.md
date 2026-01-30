# 🎨 Guia Rápido de Customização - SMO

## Alterando Cores do Tema

Todas as cores estão centralizadas no arquivo `assets/css/style-dark-modern.css` usando variáveis CSS.

### 📍 Localização: Linhas 1-40 do arquivo

```css
:root {
  /* Cores que você pode alterar facilmente */
  --accent-green: #00ff88;      /* ← Mude aqui a cor verde principal */
  --accent-blue: #4a9eff;       /* ← Mude aqui a cor azul */
  --accent-gold: #ffa834;       /* ← Mude aqui a cor dourada */
  
  /* Fundos escuros */
  --primary-dark: #0a0e27;      /* ← Fundo principal (mais escuro) */
  --secondary-dark: #1a1f3a;    /* ← Fundo dos cards */
  
  /* ... outras variáveis ... */
}
```

---

## 🎨 Exemplos de Paletas Alternativas

### Opção 1: Verde Militar Tradicional
```css
--accent-green: #4caf50;
--accent-blue: #2196f3;
--accent-gold: #ff9800;
```

### Opção 2: Azul Corporativo
```css
--accent-green: #00bcd4;
--accent-blue: #3f51b5;
--accent-gold: #ffc107;
```

### Opção 3: Roxo Moderno
```css
--accent-green: #9c27b0;
--accent-blue: #673ab7;
--accent-gold: #ff5722;
```

### Opção 4: Verde Escuro Militar
```css
--accent-green: #66bb6a;
--accent-blue: #42a5f5;
--accent-gold: #ffa726;
```

---

## 🔧 Como Aplicar uma Mudança de Cor

### Passo a Passo:

1. **Abra o arquivo**
   ```
   C:\laragon\www\smo\assets\css\style-dark-modern.css
   ```

2. **Localize a seção `:root {` (logo no início)**

3. **Altere as variáveis desejadas**
   ```css
   --accent-green: #SUA_COR_AQUI;
   ```

4. **Salve o arquivo**

5. **Atualize o navegador** (Ctrl+F5 para forçar)

---

## 🎯 Customizações Mais Comuns

### Mudar o Espaçamento Geral
```css
/* Encontre no arquivo */
--border-radius: 8px;        /* ← Arredondamento dos cantos */
--border-radius-lg: 12px;    /* ← Arredondamento maior */
```

### Mudar Velocidade das Animações
```css
--transition-fast: 0.15s ease;   /* ← Mais rápido ou lento */
--transition-base: 0.3s ease;    /* ← Transição padrão */
```

### Mudar Sombras
```css
--shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.3);
--shadow-md: 0 4px 6px rgba(0, 0, 0, 0.4);
--shadow-lg: 0 10px 20px rgba(0, 0, 0, 0.5);
```

---

## 🖼️ Mudando o Logo

### Passo 1: Substituir a imagem
```
Substitua o arquivo: C:\laragon\www\smo\imagens\pi_preto.png
```

### Passo 2: Ajustar tamanho (se necessário)
No arquivo `assets/css/style-dark-modern.css`, procure por:
```css
#header .logo img {
  max-height: 40px;  /* ← Ajuste aqui */
}
```

---

## 🔤 Mudando a Fonte

### Opção 1: Escolher outra fonte do Google Fonts

1. Visite: https://fonts.google.com
2. Escolha uma fonte (ex: Poppins, Roboto, Montserrat)
3. No `header.php`, substitua a linha:
   ```html
   <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
   ```
   Por:
   ```html
   <link href="https://fonts.googleapis.com/css2?family=NOME_DA_FONTE:wght@400;500;600;700;800&display=swap" rel="stylesheet">
   ```

4. No `style-dark-modern.css`, mude:
   ```css
   body {
     font-family: 'NOME_DA_FONTE', sans-serif;  /* ← Aqui */
   }
   ```

---

## 📊 Mudando Tamanho dos Cards no Dashboard

No arquivo `tela_inicial.php`, localize:
```html
<div class="col-md-4 mb-4">  <!-- ← Mude para col-md-3 (4 cards) ou col-md-6 (2 cards) -->
```

**Opções:**
- `col-md-3` = 4 cards por linha
- `col-md-4` = 3 cards por linha (atual)
- `col-md-6` = 2 cards por linha

---

## 🎨 Deixar Menos Escuro (Tema Mid-Dark)

No `style-dark-modern.css`, mude:

```css
:root {
  --primary-dark: #1a1f3a;      /* Era: #0a0e27 */
  --secondary-dark: #252b42;    /* Era: #1a1f3a */
  --gray-dark: #2d3349;         /* Era: #1c2128 */
}
```

---

## 🎨 Deixar Mais Escuro (Tema Ultra Dark)

```css
:root {
  --primary-dark: #000000;      /* Preto total */
  --secondary-dark: #0d1117;    /* Quase preto */
  --gray-dark: #161b22;         /* Cinza muito escuro */
}
```

---

## 🔲 Remover Arredondamento (Estilo Quadrado)

```css
:root {
  --border-radius: 0px;         /* Era: 8px */
  --border-radius-lg: 0px;      /* Era: 12px */
}
```

---

## 🌈 Adicionar Mais Cores de Status

No arquivo `style-dark-modern.css`, adicione:

```css
:root {
  --purple: #9c27b0;
  --orange: #ff9800;
  --pink: #e91e63;
}

/* Use assim */
.badge-purple {
  background: rgba(156, 39, 176, 0.2);
  color: var(--purple);
}
```

---

## 📱 Ajustar Breakpoint Mobile

Para mudar quando o menu vira hambúrguer:

```css
@media (max-width: 991px) {  /* ← Mude para 768px ou 1200px */
  .mobile-nav-toggle {
    display: block;
  }
}
```

---

## 🎯 Dicas de Customização

### ✅ Faça:
- Sempre teste após cada mudança
- Faça backup antes de alterar
- Use Ctrl+F5 para forçar atualização
- Mude uma variável por vez

### ❌ Evite:
- Remover variáveis CSS existentes
- Alterar nomes de classes
- Modificar o JavaScript sem conhecimento
- Quebrar seletores CSS

---

## 🔄 Como Reverter uma Mudança

Se algo der errado:

1. **Ctrl+Z** no editor
2. Ou restaure de backup
3. Ou copie novamente o arquivo original de `style-dark-modern.css`

---

## 💡 Exemplos Práticos

### Exemplo 1: Tema Verde Militar Suave
```css
:root {
  --primary-dark: #0f1e0f;
  --secondary-dark: #1a2e1a;
  --accent-green: #66bb6a;
  --accent-blue: #4caf50;
  --accent-gold: #8bc34a;
}
```

### Exemplo 2: Tema Azul Marinha
```css
:root {
  --primary-dark: #0a1628;
  --secondary-dark: #112240;
  --accent-green: #64ffda;
  --accent-blue: #4fc3f7;
  --accent-gold: #ffd740;
}
```

### Exemplo 3: Tema Cinza Neutro
```css
:root {
  --primary-dark: #1a1a1a;
  --secondary-dark: #2d2d2d;
  --accent-green: #00e676;
  --accent-blue: #00b0ff;
  --accent-gold: #ffd600;
}
```

---

## 🎨 Ferramenta Útil: Escolher Cores

Use esta ferramenta para encontrar cores:
- **Coolors.co** - https://coolors.co
- **Adobe Color** - https://color.adobe.com
- **Material Design Colors** - https://materialui.co/colors

---

## 📞 Precisa de Ajuda?

Se precisar de mais customizações específicas, me avise! Posso criar:
- Novas variações de cor
- Layouts alternativos
- Componentes personalizados
- Temas completamente diferentes

---

**Divirta-se customizando!** 🎨✨
