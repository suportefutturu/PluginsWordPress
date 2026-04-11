# Simulador de Hospedagem na Nuvem Futturu - Anual ou Mensal

Plugin WordPress para simulação e comparação de planos de hospedagem em nuvem da Futturu (parceria Cloudez), com opção de visualização mensal ou anual (10% de desconto no anual).

## 📋 Descrição

Este plugin permite que potenciais clientes explorem, comparem e compreendam os planos de hospedagem em nuvem gerenciada da Futturu, com a possibilidade de visualizar os preços mensalmente ou anualmente, destacando claramente o desconto de 10% no plano anual.

### Funcionalidades Principais

- **Seletor de Recorrência**: Alterne entre preços mensais e anuais com destaque para economia de 10%
- **4 Categorias de Planos**: Clouds Padrão, RAM, CPU e E-mails
- **Slider Horizontal**: Navegação intuitiva entre planos
- **Ordenação Automática**: Planos exibidos do mais barato para o mais caro
- **Modal de Informações**: Detalhes técnicos completos de cada plano
- **Formulário de Cotação**: Envio direto para suporte@futturu.com.br
- **FAQ Integrado**: Perguntas frequentes em formato accordion
- **Painel Admin Completo**: Gerenciamento de todos os planos e configurações

## 🚀 Instalação

1. Faça upload da pasta `futturu-hospedagemcloud-simulator` para `/wp-content/plugins/`
2. Ative o plugin no menu "Plugins" do WordPress
3. Use o shortcode `[futturu_hospedagemcloud_annual_monthly_sim]` em qualquer página ou post

## 📖 Uso

### Shortcode

```php
[futturu_hospedagemcloud_annual_monthly_sim]
```

### Configurações

Acesse **Configurações > Simulador Cloud Futturu** para:

- Editar planos (nome, recursos, preços)
- Configurar percentual de desconto anual
- Definir e-mail de destino das cotações
- Personalizar texto introdutório
- Restaurar planos padrão

## 📁 Estrutura do Plugin

```
futturu-hospedagemcloud-simulator/
├── assets/
│   ├── css/
│   │   ├── frontend.css      # Estilos do simulador
│   │   └── admin.css         # Estilos do admin
│   └── js/
│       ├── frontend.js       # JavaScript do frontend
│       └── admin.js          # JavaScript do admin
├── includes/
│   ├── class-futturu-hospedagemcloud-data.php      # Dados dos planos
│   ├── class-futturu-hospedagemcloud-admin.php     # Painel admin
│   └── class-futturu-hospedagemcloud-frontend.php  # Renderização
├── futturu-hospedagemcloud-simulator.php           # Arquivo principal
├── README.md                                       # Esta documentação
├── LICENSE                                         # Licença GPL v2+
└── .gitignore
```

## 🔧 Dados dos Planos

O plugin inclui 51 planos pré-configurados:

### ☁️ Clouds Padrão (16 planos)
De USA 1GB Individual (R$ 89/mês) até USA 192GB (R$ 18.879/mês)

### 🧠 Clouds Focados em RAM (5 planos)
De USA Max RAM (R$ 1.299/mês) até USA Elite RAM (R$ 17.579/mês)

### ⚙️ Clouds Focados em CPU (6 planos)
De USA Max CPU (R$ 649/mês) até USA Prestige CPU (R$ 13.149/mês)

### 📧 Clouds para E-mails (12 planos)
De USA Email 20GB (R$ 129/mês) até USA Email 1000GB (R$ 2.049/mês)

## 🎨 Recursos de Design

- **Design Moderno**: Interface limpa e profissional
- **Totalmente Responsivo**: Funciona em mobile, tablet e desktop
- **Botões Verdes de Conversão**: Apenas "Solicitar Cotação" em verde
- **Badge "Recomendado"**: Destaque visual para planos anuais
- **Animações Suaves**: Transições elegantes em todas as interações

## 🔒 Segurança

- Sanitização de todos os inputs
- Validação de nonce AJAX
- Escape de outputs (XSS protection)
- Proteção CSRF
- Validação de capacidades do usuário

## 📝 Requisitos

- WordPress 5.0 ou superior
- PHP 7.4 ou superior
- jQuery (incluído no WordPress)

## 🌐 Tecnologias

- **Backend**: PHP, WordPress API
- **Frontend**: HTML5, CSS3, Vanilla JavaScript (jQuery)
- **Ajax**: WordPress AJAX API
- **Licença**: GPL v2+

## 📧 Suporte

Para dúvidas ou suporte técnico, entre em contato:
- E-mail: suporte@futturu.com.br

## 📄 Licença

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

## 🔄 Changelog

### Versão 1.0.0
- Lançamento inicial
- 51 planos pré-configurados
- Seletor mensal/anual com 10% de desconto
- Slider horizontal funcional
- Modal de informações e cotação
- FAQ accordion
- Painel admin completo
- Totalmente responsivo

---

**Desenvolvido por Futturu** - Hospedagem em Nuvem Gerenciada
