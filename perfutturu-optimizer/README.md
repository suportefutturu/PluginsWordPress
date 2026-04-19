# Perfutturu - Otimizador de Performance Profundo

**Versão:** 1.0.0  
**Autor:** Futturu  
**Licença:** GPL v2 or later

## Descrição

O **Perfutturu** é um plugin WordPress premium focado em otimização profunda de performance. Diferente de plugins de cache tradicionais, o Perfutturu age diretamente na estrutura e no carregamento do código, otimizando a stack tecnológica do seu site para alcançar melhores pontuações nos Core Web Vitals (LCP, INP, CLS).

### Principais Benefícios

- **Redução de até 40% no TTFB** (Time to First Byte)
- **Melhoria significativa nos Core Web Vitals**
- **Controle granular de scripts e estilos**
- **Otimização para hospedagens gerenciadas Cloudez/Futturu**
- **Código leve que não adiciona carga ao site**

## Funcionalidades

### 1. Script Manager (Gestor de Scripts e Estilos)

Controle preciso de quais scripts (CSS e JS) carregam onde e quando:

- Liste todos os scripts enqueued no site
- Desabilite scripts sitewide ou por condições específicas
- Adicione `defer` ou `async` a scripts específicos
- Minifique CSS e JS individuais
- Controle por tipo de post, ID específico, página inicial, etc.

### 2. Otimização de CSS

- Minificação automática de todos os arquivos CSS
- Geração de CSS crítico (Critical CSS)
- Combinação opcional de arquivos CSS
- Cache de CSS minificado

### 3. Otimização de JavaScript

- Minificação de arquivos JavaScript
- Deferral e delaying de JS não-crítico
- Carregamento sob demanda (lazy-loading JS)
- Remoção de jQuery Migrate

### 4. Otimização de Imagens

- Lazy loading nativo com inteligência para LCP
- Pré-carregamento de imagens críticas
- Adição de `fetchpriority="high"` para imagem LCP
- Garantia de dimensões (width/height) para evitar CLS
- Lazy loading para iframes (YouTube, Vimeo)

### 5. Controle de Fontes Web

- Hospedagem local de Google Fonts
- Aplicação automática de `font-display: swap`
- Pré-carregamento de fontes críticas
- Preconnect para font.gstatic.com

### 6. Limpeza de Código

- Remoção de emojis do WordPress
- Remoção de embeds não utilizados
- Remoção de dashicons para visitantes
- Limpeza de meta tags desnecessárias
- Remoção de links RSD, WLW, shortlink
- Limpeza de revisões e transients do banco de dados

### 7. Resource Hints

- DNS Prefetch configurável
- Preconnect para domínios externos
- Preload de recursos críticos

### 8. Core Web Vitals

- Dashboard educativo sobre LCP, FID/INP, CLS
- Recomendações baseadas nas otimizações ativas
- Links para ferramentas de medição (PageSpeed, GTmetrix)

## Instalação

### Requisitos Mínimos

- WordPress 5.8 ou superior
- PHP 7.4 ou superior
- Permissões de escrita para `/wp-content/uploads/`

### Passos de Instalação

1. Faça o upload da pasta `perfutturu-optimizer` para `/wp-content/plugins/`
2. Ative o plugin através do menu "Plugins" no WordPress
3. Acesse **Perfutturu > Configurações** para configurar as otimizações
4. Use o **Script Manager** para controle granular de scripts

### Configuração Inicial Recomendada

Após ativar o plugin:

1. **Dashboard**: Verifique o status das otimizações
2. **Configurações**: 
   - Mantenha CSS/JS/HTML minification ativados
   - Ative lazy loading de imagens
   - Configure pré-carregamento de imagens críticas se necessário
3. **Script Manager**: 
   - Identifique scripts não utilizados
   - Desabilite scripts condicionalmente por página
4. **Core Web Vitals**: 
   - Entenda as métricas
   - Monitore após aplicar otimizações

## Estrutura do Plugin

```
perfutturu-optimizer/
├── perfutturu-optimizer.php    # Arquivo principal do plugin
├── admin/
│   ├── class-perfutturu-admin.php
│   ├── class-perfutturu-script-manager.php
│   └── views/
│       ├── dashboard.php
│       ├── script-manager.php
│       ├── settings.php
│       └── cwv.php
├── includes/
│   ├── class-perfutturu-css-optimizer.php
│   ├── class-perfutturu-js-optimizer.php
│   ├── class-perfutturu-image-optimizer.php
│   ├── class-perfutturu-font-optimizer.php
│   ├── class-perfutturu-cleanup.php
│   └── class-perfutturu-cache.php
├── assets/
│   ├── css/
│   │   └── admin.css
│   └── js/
│       └── admin.js
└── languages/
    └── perfutturu.pot
```

## Uso do Script Manager

O Script Manager é a funcionalidade mais poderosa do Perfutturu:

1. Acesse **Perfutturu > Script Manager**
2. A lista de scripts será carregada automaticamente
3. Clique em "Configurar" em qualquer script
4. Escolha as ações:
   - **Desabilitar**: Remove completamente o script
   - **Defer**: Carrega após o HTML parsing
   - **Async**: Carrega assincronamente
5. Defina condições:
   - Sitewide (todo o site)
   - Página inicial
   - Posts/Páginas específicas
   - IDs específicos

### Exemplo de Uso

Para melhorar a performance de uma landing page:

1. Identifique scripts do formulário de contato
2. Desabilite-os em todas as páginas exceto na página de contato
3. Identifique scripts de sliders/carrosséis
4. Desabilite-os em páginas que não usam esses recursos

## Melhores Práticas

### Para Melhor LCP (Largest Contentful Paint)

- Pré-carregue a hero image
- Não aplique lazy loading nas primeiras 2-3 imagens
- Minifique CSS crítico
- Use CDN para imagens

### Para Melhor INP (Interaction to Next Paint)

- Defer scripts não-críticos
- Minimize JavaScript na thread principal
- Remova jQuery Migrate se não necessário
- Quebre tarefas longas de JS

### Para Melhor CLS (Cumulative Layout Shift)

- Sempre inclua width e height em imagens
- Reserve espaço para anúncios e embeds
- Use font-display: swap para fontes
- Evite conteúdo dinâmico acima da dobra

## Compatibilidade

O Perfutturu foi testado e é compatível com:

- **Temas**: GeneratePress, Astra, Kadence, OceanWP, Twenty Twenty-*
- **Page Builders**: Elementor, Gutenberg, Beaver Builder
- **Plugins de Cache**: WP Rocket, W3 Total Cache, LiteSpeed Cache
- **E-commerce**: WooCommerce (com configurações específicas)
- **Hospedagens**: Cloudez, Futturu, Kinsta, WP Engine

## Integração com Hospedagem Cloudez/Futturu

O Perfutturu é otimizado para potencializar os recursos da hospedagem Cloudez:

- Aproveita o cache de servidor já existente
- Complementa a CDN integrada
- Otimiza a stack PHP/Nginx já configurada
- Reduz a carga no servidor através de menos requisições

## Solução de Problemas

### Site quebrou após ativar otimização

1. Acesse via FTP/SFTP
2. Renomeie a pasta do plugin para desativá-lo
3. Ou adicione ao `wp-config.php`:
   ```php
   define('PERFUTTURU_DISABLED', true);
   ```

### Conflito com outro plugin

1. Ative o Modo de Teste no Perfutturu
2. Desative otimizações uma por uma
3. Identifique o conflito
4. Use o Script Manager para excluir o script conflitante

### Cache não está sendo limpo

1. Vá em **Perfutturu > Dashboard**
2. Clique em "Limpar Cache"
3. Se persistir, limpe manualmente em `/wp-content/uploads/perfutturu-cache/`

## FAQ

### O Perfutturu substitui plugins de cache?

Não necessariamente. O Perfutturu complementa plugins de cache focando em otimizações que eles não fazem. Use ambos para melhor resultado.

### Posso usar com WP Rocket?

Sim! O Perfutturu foca em otimizações de código enquanto o WP Rocket foca em cache. Eles trabalham bem juntos.

### O plugin afeta o SEO?

Positivamente! Melhor performance resulta em melhor experiência do usuário, que é fator de ranqueamento do Google.

### É seguro remover emojis?

Sim, a menos que você use emojis no conteúdo. Mesmo assim, emojis Unicode padrão funcionarão normalmente.

## Changelog

### Versão 1.0.0
- Lançamento inicial
- Script Manager completo
- Otimização de CSS/JS/HTML
- Lazy loading de imagens e iframes
- Otimização de fontes
- Limpeza de código WordPress
- Dashboard Core Web Vitals
- Painel administrativo completo

## Créditos

Desenvolvido pela **Futturu** com foco em performance máxima para WordPress.

### Referências

- [WordPress Performance Checklist](https://make.wordpress.org/core/handbook/docs/performance-checklist/)
- [Google Web Vitals](https://web.dev/vitals/)
- [Cloudez Documentation](https://cloudez.io/docs/)

## Suporte

Para suporte técnico:

- Email: suporte@futturu.com.br
- Site: https://futturu.com.br
- Documentação: https://futturu.com.br/perfutturu/docs

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

---

**Perfutturu** - Potencializando sua hospedagem Cloudez/Futturu com otimizações profundas de performance.
