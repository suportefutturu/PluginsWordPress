# PopUp CTA Promocional Futturu

Plugin WordPress para exibição de popups promocionais altamente configuráveis, desenvolvido para a Futturu.

## Descrição

O **PopUp CTA Promocional Futturu** é um plugin leve e moderno que permite exibir popups promocionais em seu site WordPress com foco em conversão. O plugin oferece ampla personalização de conteúdo, aparência e regras de exibição, garantindo uma experiência não intrusiva para os visitantes.

## Funcionalidades

### Conteúdo Personalizável
- Título do popup
- Texto de apoio/descrição
- Botão CTA (Call to Action) principal com link configurável
- Botão opcional "Não, obrigado"
- Sanitização de todos os dados para segurança

### Aparência Moderna
- Design limpo com cantos arredondados e sombras suaves
- Largura ajustável (Pequeno, Médio, Grande ou Personalizado)
- Altura máxima personalizável
- Cores customizáveis:
  - Fundo do popup
  - Texto
  - Botão CTA (fundo e texto)
  - Botão de fechar
- Fontes personalizáveis (tipo, tamanho e peso)
- Efeito blur no fundo da página (com intensidade ajustável)
- Animações de abertura (Fade In, Slide Up, Slide Down, Zoom In)

### Regras de Exibição Flexíveis
- **Páginas de exibição:**
  - Todas as páginas
  - Apenas em Posts
  - Apenas em Páginas
  - Páginas específicas (seleção múltipla)
  - Exceto páginas específicas
  - Categorias específicas
  
- **Tempo de exibição:**
  - Imediatamente
  - Após X segundos
  - Após rolar Y% da página
  - Exit-intent (ao tentar sair da página)

- **Controle de frequência:**
  - Mostrar em todas as visitas
  - Uma vez por sessão (cookie de sessão)
  - Uma vez por X dias (cookie persistente)
  - Máximo de N visualizações por visitante

### Acessibilidade
- Focus trap (preso dentro do popup enquanto aberto)
- Fechamento com tecla ESC
- Navegação por teclado completa
- Suporte a leitores de tela (ARIA labels)
- Suporte a modo de alto contraste
- Suporte a redução de movimento

### Responsividade
- Totalmente responsivo para dispositivos móveis
- Touch support para fechamento ao tocar fora do popup

## Requisitos

- WordPress 5.0 ou superior
- PHP 7.4 ou superior
- jQuery (incluído no WordPress)

## Instalação

1. Faça o download do plugin
2. Extraia o arquivo ZIP
3. Envie a pasta `futturu-popup-cta` para o diretório `/wp-content/plugins/` do seu site WordPress
4. No painel administrativo do WordPress, vá em **Plugins** e clique em **Ativar** no plugin "PopUp CTA Promocional Futturu"

### Instalação via Painel Administrativo

1. No painel do WordPress, vá em **Plugins > Adicionar Novo**
2. Clique em **Enviar Plugin**
3. Selecione o arquivo ZIP do plugin
4. Clique em **Instalar Agora** e depois em **Ativar**

## Configuração

Após ativar o plugin:

1. Acesse **Configurações > PopUp Promocional Futturu** no menu lateral do WordPress
2. Configure as opções conforme necessário:

### Conteúdo do PopUp
- **Ativar/Desativar:** Habilita ou desabilita a exibição do popup
- **Título:** Texto principal do popup (ex: "OFERTA ESPECIAL")
- **Texto de Apoio:** Mensagem promocional descritiva
- **Texto do CTA Principal:** Texto do botão de ação principal
- **Link do CTA Principal:** URL para onde o botão CTA levará o usuário
- **Botão "Não, obrigado":** Ativa/desativa o botão secundário de fechamento
- **Texto do Botão "Não, obrigado":** Texto personalizado para o botão de fechamento

### Aparência do PopUp
- **Largura:** Pequeno (400px), Médio (500px), Grande (600px) ou Personalizado
- **Altura Máxima:** Defina uma altura máxima personalizada (opcional)
- **Cores:** Personalize todas as cores do popup usando o seletor de cores
- **Fonte:** Escolha a família, tamanho e peso da fonte
- **Blur no Fundo:** Ative o efeito de desfoque na página de fundo
- **Intensidade do Blur:** Ajuste a intensidade do efeito blur (0-20px)
- **Animação:** Ative/desative animações de abertura
- **Tipo de Animação:** Escolha entre Fade In, Slide Up, Slide Down ou Zoom In

### Regras de Exibição
- **Páginas de Exibição:** Defina onde o popup será mostrado
- **Tempo de Exibição:** Escolha quando o popup deve aparecer
- **Atraso (segundos):** Tempo de espera antes de mostrar o popup
- **Porcentagem de Scroll:** Porcentagem da página que deve ser rolada
- **Controle de Frequência:** Defina com que frequência o popup será exibido
- **Dias para Mostrar Novamente:** Número de dias entre exibições
- **Número Máximo de Visualizações:** Limite de vezes que o popup será mostrado

3. Clique em **Salvar Configurações** para aplicar as alterações

### Preview
Use o botão **Ver Preview** na página de configurações para visualizar como o popup ficará com suas configurações atuais.

## Uso

O plugin funciona automaticamente após a configuração. O popup será exibido nas páginas definidas nas regras de exibição, respeitando as configurações de frequência e tempo.

### Cookies Utilizados

O plugin utiliza os seguintes cookies para controle de frequência:

- `futturu_popup_session`: Cookie de sessão para controle de uma exibição por sessão
- `futturu_popup_last_shown`: Timestamp da última exibição (para controle por dias)
- `futturu_popup_view_count`: Contador de visualizações (para limite máximo)

## Estrutura de Arquivos

```
futturu-popup-cta/
├── futturu-popup-cta.php          # Arquivo principal do plugin
├── includes/
│   ├── class-futturu-popup-admin.php      # Classe de administração
│   ├── class-futturu-popup-frontend.php   # Classe de frontend
│   └── class-futturu-popup-template.php   # Template HTML do popup
├── assets/
│   ├── css/
│   │   └── futturu-popup.css              # Estilos do popup
│   └── js/
│       ├── futturu-popup.js               # JavaScript do frontend
│       └── futturu-popup-admin.js         # JavaScript do admin
└── README.md                              # Este arquivo
```

## Hooks e Filtros

O plugin pode ser estendido através de hooks do WordPress:

### Ações
- `futturu_popup_before_render`: Executado antes de renderizar o popup
- `futturu_popup_after_render`: Executado após renderizar o popup

### Filtros
- `futturu_popup_options`: Filtra as opções do popup antes de serem aplicadas
- `futturu_popup_should_display`: Filtra a decisão de exibir o popup

## Segurança

O plugin implementa as melhores práticas de segurança do WordPress:

- Sanitização de todos os dados de entrada
- Escape de todos os dados de saída
- Nonces para validação de formulários AJAX
- Verificação de capacidades do usuário
- Validação de URLs

## Compatibilidade

- Testado com temas WordPress padrão (Twenty Twenty-One, Twenty Twenty-Two, Twenty Twenty-Three)
- Compatível com page builders populares (Elementor, Divi, Beaver Builder)
- Funciona com plugins de cache (WP Rocket, W3 Total Cache, etc.)

## Performance

- Código otimizado para mínimo impacto na performance
- CSS e JS carregados apenas quando necessário
- Sem dependências externas
- Cookies leves para controle de frequência

## Licença

Este plugin é licenciado sob GPL v2 ou posterior.

```
Copyright (C) 2024 Futturu

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

## Suporte

Para suporte técnico, dúvidas ou sugestões, entre em contato através do site da Futturu.

## Changelog

### Versão 1.0.0
- Lançamento inicial do plugin
- Funcionalidades completas de configuração de conteúdo, aparência e regras de exibição
- Suporte a múltiplos gatilhos de exibição (imediato, delay, scroll, exit-intent)
- Controle de frequência via cookies
- Animações suaves e efeito blur
- Totalmente responsivo e acessível
- Painel administrativo completo com preview

## Créditos

Desenvolvido por Futturu - Soluções em Tecnologia.
