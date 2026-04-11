# Simulador de Hospedagem Cloud Futturu

Plugin WordPress para simulação de planos de hospedagem em nuvem com opções mensal e anual (10% de desconto no anual).

## 📋 Requisitos

- WordPress 5.0 ou superior
- PHP 7.4 ou superior

## 🚀 Instalação

1. Copie a pasta `futturu-hospedagemcloud-simulator` para `/wp-content/plugins/`
2. Ative o plugin no menu "Plugins" do WordPress
3. Use o shortcode `[futturu_hospedagemcloud_annual_monthly_sim]` em qualquer página ou post

## ⚙️ Configuração

Acesse **Configurações > Simulador Cloud Futturu** para:

- Gerenciar planos de hospedagem (nomes, preços, recursos)
- Editar FAQs
- Configurar desconto anual (padrão: 10%)
- Definir e-mail para cotações (padrão: suporte@futturu.com.br)
- Escolher visualização padrão (Mensal/Anual)

## 📱 Shortcode

```
[futturu_hospedagemcloud_annual_monthly_sim]
```

## ✨ Funcionalidades

- **Seletor de Recorrência**: Alternar entre planos mensais e anuais com destaque para 10% OFF no anual
- **4 Categorias de Planos**: 
  - ☁️ Clouds Padrão (Uso Geral)
  - 🧠 Clouds RAM
  - ⚙️ Clouds CPU
  - 📧 Clouds E-mail
- **Slider Horizontal**: Navegação suave entre planos
- **Modal de Informações**: Detalhes técnicos e funcionalidades
- **Formulário de Cotação**: Envio direto para e-mail configurado
- **FAQ Accordion**: Perguntas frequentes expansíveis
- **Design Responsivo**: Compatível com dispositivos móveis
- **Botões Verdes**: Otimizados para conversão

## 📁 Estrutura

```
futturu-hospedagemcloud-simulator/
├── assets/
│   ├── css/
│   │   ├── frontend.css
│   │   └── admin.css
│   └── js/
│       ├── frontend.js
│       └── admin.js
├── includes/
│   ├── class-futturu-hospedagemcloud-data.php
│   ├── class-futturu-hospedagemcloud-admin.php
│   └── class-futturu-hospedagemcloud-frontend.php
├── futturu-hospedagemcloud-simulator.php
└── README.md
```

## 🔒 Segurança

- Sanitização de todos os dados de entrada
- Validação de nonce AJAX
- Escaping de saída (XSS protection)
- Proteção CSRF

## 📄 Licença

GPL v2 or later

## 👥 Suporte

Para dúvidas ou suporte, entre em contato: suporte@futturu.com.br
