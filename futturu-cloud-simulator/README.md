# Simulador de Hospedagem na Nuvem Futturu

Plugin WordPress para simulação e comparação de planos de hospedagem em nuvem gerenciada da Futturu (parceria Cloudez).

## Descrição

O **Simulador de Hospedagem na Nuvem Futturu** é um plugin completo que permite que potenciais clientes explorem, comparem e compreendam os planos de hospedagem em nuvem gerenciada oferecidos pela Futturu em parceria com a Cloudez.

### Funcionalidades Principais

- **Visualização de Planos**: Apresenta todos os planos de hospedagem organizados por categorias
- **Comparação Detalhada**: Mostra recursos técnicos (RAM, CPU, Disco SSD, Visualizações)
- **Modal de Detalhes**: Exibe todas as automações e benefícios inclusos em cada plano
- **Formulário de Cotação**: Captura leads interessados com formulário integrado
- **Painel Administrativo**: Gerencie planos, categorias, funcionalidades e configurações
- **Design Responsivo**: Funciona perfeitamente em desktops, tablets e mobile
- **Shortcode Fácil**: Use `[futturu_cloud_simulator]` em qualquer página ou post

### Categorias de Planos

1. **☁️ Clouds Padrão (Uso Geral)** - Planos balanceados para uso geral
2. **🧠 Clouds Focados em RAM** - Para aplicações que exigem alta memória
3. **⚙️ Clouds Focados em CPU** - Alta performance para processamento intensivo
4. **📧 Clouds para E-mails** - Especializados para servidores de e-mail corporativo

### Benefícios e Automações Incluídos

- HTTPS Automático (Let's Encrypt)
- Backup Automático
- CDN + Cache Automática
- Pagespeed Optimizer
- Firewall e Proteção Anti-DDoS
- Monitoramento 24/7
- Atualizações de Segurança Automáticas
- Painel de Controle Automatizado
- Migração de Sites Grátis
- Suporte Técnico Especializado
- E muito mais!

## Instalação

### Método 1: Upload Manual

1. Baixe o arquivo ZIP do plugin
2. No painel do WordPress, vá em **Plugins > Adicionar Novo**
3. Clique em **Enviar Plugin** e selecione o arquivo ZIP
4. Clique em **Instalar Agora** e depois **Ativar**

### Método 2: FTP

1. Extraia o arquivo ZIP do plugin
2. Faça upload da pasta `futturu-cloud-simulator` para `/wp-content/plugins/`
3. No painel do WordPress, vá em **Plugins**
4. Encontre "Simulador de Hospedagem na Nuvem Futturu" e clique em **Ativar**

## Uso

### Frontend (Para Visitantes)

Após ativar o plugin, use o shortcode em qualquer página ou post:

```
[futturu_cloud_simulator]
```

Os visitantes poderão:
- Navegar entre as categorias de planos
- Visualizar detalhes técnicos de cada plano
- Ver todas as automações e benefícios inclusos
- Enviar solicitação de cotação diretamente

### Backend (Para Administradores)

Acesse o painel administrativo em:
**Configurações > Simulador Cloud Futturu**

#### Abas do Painel

1. **Planos**: CRUD completo para gerenciar todos os planos
   - Adicionar novo plano
   - Editar plano existente
   - Excluir plano
   
2. **Categorias**: Visualização das categorias de planos

3. **Funcionalidades**: Lista de todas as automações e benefícios

4. **Configurações**: 
   - Ativar/desativar plugin
   - Editar texto introdutório
   - Configurar texto do CTA
   - Definir e-mail de destino das cotações

5. **Importar/Exportar**: 
   - Exportar dados em JSON
   - Importar dados de arquivo JSON

## Estrutura do Plugin

```
futturu-cloud-simulator/
├── futturu-cloud-simulator.php    # Arquivo principal do plugin
├── includes/
│   ├── class-futturu-cloud-data.php      # Dados dos planos e utilitários
│   ├── class-futturu-cloud-admin.php     # Painel administrativo
│   ├── class-futturu-cloud-frontend.php  # Renderização frontend
│   └── class-futturu-cloud-ajax.php      # Handlers AJAX
├── templates/
│   └── simulator.php              # Template do simulador
├── assets/
│   ├── css/
│   │   ├── frontend.css           # Estilos frontend
│   │   └── admin.css              # Estilos admin
│   └── js/
│       ├── frontend.js            # JavaScript frontend
│       └── admin.js               # JavaScript admin
└── README.md                      # Este arquivo
```

## Requisitos

- WordPress 5.0 ou superior
- PHP 7.4 ou superior
- jQuery (incluído no WordPress)

## Personalização

### Cores e Estilo

Edite o arquivo `assets/css/frontend.css` para personalizar:
- Cores da marca
- Tamanhos de fonte
- Espaçamentos
- Animações

### Textos

Todos os textos podem ser editados através do painel administrativo em:
**Configurações > Simulador Cloud Futturu > Configurações**

### Adicionar Novos Planos

1. Acesse **Configurações > Simulador Cloud Futturu**
2. Clique na aba **Planos**
3. Clique em **+ Adicionar Novo Plano**
4. Preencha os dados:
   - Nome do Modelo
   - Categoria
   - RAM (GB)
   - CPU Cores
   - Disco SSD (GB)
   - Visualizações/mês (opcional para e-mail)
   - Sites por Cloud
   - Preço Mensal (R$)
   - Funcionalidades associadas
5. Clique em **Salvar Plano**

## Segurança

O plugin implementa as seguintes práticas de segurança:

- Sanitização de todos os dados de entrada
- Escape de todos os dados de saída
- Nonce verification para formulários e AJAX
- Verificação de capacidades do usuário
- Validação de e-mail
- Proteção contra spam básica

## Compatibilidade

- ✅ Funciona com qualquer tema WordPress
- ✅ Compatível com page builders (Elementor, WPBakery, etc.)
- ✅ Responsivo (mobile-first)
- ✅ Acessível (WCAG 2.1)
- ✅ Open Source (GPL v2+)

## Suporte

Para suporte técnico ou dúvidas:

- **E-mail**: suporte@futturu.com.br
- **Website**: https://futturu.com.br

## Changelog

### Versão 1.0.0
- Lançamento inicial
- Todos os planos Cloudez pré-configurados
- Painel administrativo completo
- Formulário de cotação integrado
- Design moderno e responsivo

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

## Créditos

Desenvolvido para **Futturu** em parceria com **Cloudez**.

- Tecnologia: WordPress Plugin
- Design: Moderno, limpo e profissional
- Bibliotecas: Apenas open-source (jQuery, funções nativas do WordPress)

---

**Futturu** - Hospedagem em Nuvem Gerenciada de Alta Performance
