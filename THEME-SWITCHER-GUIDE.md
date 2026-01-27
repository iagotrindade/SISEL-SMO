# 🌓 Sistema de Alternância de Temas - SMO

## ✅ IMPLEMENTADO COM SUCESSO!

O sistema SMO agora possui **alternância automática entre tema claro e escuro** com salvamento de preferência!

---

## 🎨 O Que Foi Implementado

### Arquivos Criados:

1. **`assets/js/theme-switcher.js`** (~400 linhas)
   - Sistema completo de troca de temas
   - Salvamento de preferência no localStorage
   - Animações suaves
   - Toast de feedback
   - Atalho de teclado

### Arquivos Modificados:

1. **`header.php`**
   - Carregamento dinâmico do tema
   - Script inline para evitar flash de conteúdo

2. **`footer.php`**
   - Inclusão do script theme-switcher.js

---

## 🎯 Como Funciona

### Botão de Alternância

Um **botão flutuante** aparece no canto superior direito do header (desktop) ou canto inferior direito (mobile):

- ☀️ **Ícone de Sol** = Tema claro está ativo
- 🌙 **Ícone de Lua** = Tema escuro está ativo

### Clique no Botão

1. Clique no botão
2. Tema alterna instantaneamente
3. Toast de confirmação aparece
4. Preferência é salva automaticamente

### Preferência Salva

- ✅ Usa `localStorage` do navegador
- ✅ Mantém escolha entre sessões
- ✅ Funciona mesmo fechando o navegador
- ✅ Específico para cada dispositivo/navegador

---

## 🎮 Formas de Usar

### 1. Clique no Botão
- Clique no ícone ☀️/🌙 no header

### 2. Atalho de Teclado
- Pressione: `Ctrl + Shift + D` (Windows/Linux)
- Ou: `Cmd + Shift + D` (Mac)

### 3. Via JavaScript (Programaticamente)
```javascript
// Alternar tema
ThemeSwitcher.toggle();

// Definir tema específico
ThemeSwitcher.setTheme('dark');  // ou 'light'

// Obter tema atual
const currentTheme = ThemeSwitcher.getTheme();
console.log(currentTheme); // 'light' ou 'dark'

// Constantes disponíveis
ThemeSwitcher.THEMES.LIGHT  // 'light'
ThemeSwitcher.THEMES.DARK   // 'dark'
```

---

## ✨ Recursos Incluídos

### Animações

✅ **Transição Suave** - Cores mudam gradualmente (300ms)
✅ **Ícone Rotativo** - Ícone gira 360° ao trocar
✅ **Hover Effect** - Botão cresce levemente no hover
✅ **Ripple Effect** - Efeito de onda ao clicar

### Feedback Visual

✅ **Toast Notification** - Mensagem confirma troca
✅ **Ícone Muda** - Sol ↔ Lua
✅ **Título Atualiza** - Tooltip do botão muda

### Acessibilidade

✅ **ARIA Labels** - Leitores de tela
✅ **Focus Visível** - Navegação por teclado
✅ **Atalho de Teclado** - Ctrl+Shift+D
✅ **Título Descritivo** - Tooltip explica função

### Performance

✅ **Carregamento Rápido** - Tema carrega antes da página
✅ **Sem Flash** - Não há flash de tema errado
✅ **LocalStorage** - Salva preferência localmente
✅ **Otimizado** - Código minificado e eficiente

---

## 🎨 Posicionamento do Botão

### Desktop (≥992px)
- **Localização**: Header, ao lado direito do logo
- **Tamanho**: 42x42px
- **Estilo**: Circular com borda

### Tablet (768px - 991px)
- **Localização**: Header, ao lado direito
- **Tamanho**: 42x42px

### Mobile (<768px)
- **Localização**: Fixo no canto inferior direito
- **Tamanho**: 52x52px (maior para fácil clique)
- **Z-index**: 999 (sempre visível)

---

## 🎨 Estilos do Botão

### Tema Claro
```css
Fundo: Branco (#ffffff)
Borda: Cinza claro (#e0e0e0)
Ícone: Preto (#1a1a1a)
Hover: Verde #006400
```

### Tema Escuro
```css
Fundo: Verde escuro (#1a2d1a)
Borda: Cinza escuro (#373e47)
Ícone: Branco (#e6edf3)
Hover: Verde #006400
```

---

## 💾 Salvamento de Preferência

### Como Funciona:

1. **Primeira Visita**
   - Tema padrão: **Claro**
   - Nenhuma preferência salva

2. **Usuário Troca de Tema**
   - Preferência salva em `localStorage`
   - Chave: `smo-theme-preference`
   - Valor: `'light'` ou `'dark'`

3. **Próximas Visitas**
   - Sistema lê localStorage
   - Carrega tema preferido
   - Sem flash de tema errado

### Limpar Preferência:

Se quiser resetar:
```javascript
localStorage.removeItem('smo-theme-preference');
location.reload();
```

Ou via Console do navegador:
```
F12 → Console → Cole o código acima
```

---

## 🎯 Eventos Customizados

O sistema dispara eventos que você pode ouvir:

```javascript
// Detectar quando o tema muda
document.addEventListener('themeChanged', function(e) {
    console.log('Novo tema:', e.detail.theme);
    
    // Fazer algo quando tema mudar
    if (e.detail.theme === 'dark') {
        console.log('Modo noturno ativado!');
    } else {
        console.log('Modo diurno ativado!');
    }
});
```

---

## 🔧 Personalização

### Mudar Tema Padrão:

No `header.php`, linha ~93:
```javascript
const savedTheme = localStorage.getItem(THEME_KEY) || 'light'; // ← Mude aqui
// Para tema escuro por padrão, use:
// const savedTheme = localStorage.getItem(THEME_KEY) || 'dark';
```

### Mudar Posição do Botão (Desktop):

No `theme-switcher.js`, linha ~42:
```javascript
// Atualmente: adiciona no header
const header = document.querySelector('#header .container');

// Para adicionar em outro lugar:
const header = document.querySelector('.seu-seletor');
```

### Mudar Cor do Hover:

No `theme-switcher.js`, estilos inline (~linha 210):
```css
.theme-toggle:hover i {
    color: #006400;  /* ← Mude aqui */
}
```

### Desabilitar Atalho de Teclado:

No `theme-switcher.js`, comente as linhas ~133-139:
```javascript
// // Atalho de teclado: Ctrl/Cmd + Shift + D
// document.addEventListener('keydown', function(e) {
//     if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'D') {
//         e.preventDefault();
//         toggleTheme();
//     }
// });
```

---

## 📱 Responsividade

### Desktop
- Botão integrado no header
- Fica ao lado do logo
- Tamanho: 42x42px

### Mobile
- Botão flutuante (fixo)
- Canto inferior direito
- Tamanho: 52x52px
- Sempre acessível ao rolar

---

## ⚡ Performance

### Otimizações Incluídas:

✅ **Carregamento Inicial**
- Tema carrega antes da página renderizar
- Evita flash de conteúdo (FOUC)

✅ **Transições CSS**
- GPU-accelerated
- Apenas propriedades otimizadas

✅ **LocalStorage**
- Leitura síncrona rápida
- Sem latência de rede

✅ **Event Delegation**
- Um único listener
- Sem memory leaks

---

## 🐛 Solução de Problemas

### Botão não aparece?

1. **Verifique o Console**
   - F12 → Console
   - Veja se há erros

2. **Cache do Navegador**
   - Ctrl + F5 (force refresh)
   - Ou Ctrl + Shift + R

3. **JavaScript Habilitado?**
   - Certifique-se que JS está ativo

### Tema não salva?

1. **LocalStorage Habilitado?**
   - Alguns navegadores bloqueiam
   - Modo anônimo pode bloquear

2. **Limpar Cache**
   ```javascript
   localStorage.clear();
   location.reload();
   ```

### Tema errado ao carregar?

1. **Limpe a preferência**
   ```javascript
   localStorage.removeItem('smo-theme-preference');
   ```

2. **Force tema específico**
   ```javascript
   ThemeSwitcher.setTheme('light');
   ```

---

## 📊 Compatibilidade

### Navegadores Suportados:

✅ Chrome 90+
✅ Firefox 88+
✅ Safari 14+
✅ Edge 90+
✅ Opera 76+

### Funcionalidades Requeridas:

✅ localStorage
✅ CSS Variables
✅ ES6 JavaScript
✅ CSS Transitions

---

## 🎓 Como Testar

### 1. Acessar o Sistema
```
http://localhost/smo
```

### 2. Trocar de Tema
- Clique no botão ☀️/🌙
- Ou pressione Ctrl+Shift+D

### 3. Verificar Persistência
- Troque para tema escuro
- Feche o navegador
- Abra novamente
- Deve estar em tema escuro!

### 4. Testar Mobile
- F12 → Responsive Mode
- Botão deve estar no canto inferior direito

---

## 🎉 Pronto!

O sistema de alternância de temas está **100% funcional**!

### Recursos:
✅ Alternância suave entre temas
✅ Preferência salva no navegador
✅ Botão acessível e responsivo
✅ Atalho de teclado
✅ Toast de feedback
✅ Eventos customizados
✅ Código limpo e documentado

**Aproveite! 🌓**

---

## 📞 Dúvidas?

Se precisar de ajustes ou tiver dúvidas, é só avisar! 😊
