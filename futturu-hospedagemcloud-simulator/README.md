# Simulador de Hospedagem na Nuvem Futturu - Anual ou Mensal

Plugin WordPress para simulação de planos de hospedagem em nuvem com opção de visualização mensal ou anual (10% OFF no plano anual). Desenvolvido em parceria com a Cloudez.

## 📋 Requisitos

- WordPress 5.0 ou superior
- PHP 7.4 ou superior
- jQuery (incluído no WordPress)

## 🚀 Instalação

1. Faça o upload da pasta `futturu-hospedagemcloud-simulator` para o diretório `/wp-content/plugins/` do seu WordPress
2. Ative o plugin através do menu "Plugins" no painel administrativo do WordPress
3. Acesse **Configurações > Simulador Cloud Futturu** para configurar os planos, FAQs e funcionalidades

## 📱 Uso

### Shortcode

Para exibir o simulador em qualquer página, post ou widget, utilize o shortcode:

```
[futturu_hospedagemcloud_annual_monthly_sim]
```

### Exemplo de uso em template PHP

```php
<?php echo do_shortcode('[futturu_hospedagemcloud_annual_monthly_sim]'); ?>
```

## ⚙️ Configuração

Acesse o painel administrativo em **Configurações > Simulador Cloud Futturu** para:

### 1. Gerenciar Planos
- Edite os planos de hospedagem por categoria
- Ajuste preços mensais (em R$)
- Configure recursos (RAM, CPU, Disco SSD, Visualizações)
- Defina uso indicado para cada plano

### 2. Gerenciar FAQs
- Adicione, edite ou remova perguntas frequentes
- Personalize as respostas

### 3. Gerenciar Funcionalidades
- Configure a lista de benefícios e funcionalidades técnicas
- Adicione ou remova itens conforme necessário

### 4. Configurações Gerais
- **Desconto Anual (%)**: Percentual de desconto para pagamento anual (padrão: 10%)
- **E-mail para CTA**: E-mail que receberá as solicitações de cotação (padrão: suporte@futturu.com.br)
- **Texto Introdutório**: Texto de apresentação do simulador
- **Texto do Botão CTA**: Texto personalizado para o botão de call-to-action

## 🎯 Funcionalidades

### Frontend
- **Seletor de Recorrência**: Toggle entre planos mensais e anuais com destaque para o desconto anual
- **Navegação por Categorias**: 
  - ☁️ Clouds Padrão (Uso Geral)
  - 🧠 Clouds Focados em Memória RAM
  - ⚙️ Clouds Focados em Processamento (CPU)
  - 📧 Clouds para E-mails
- **Slider de Planos**: Navegação horizontal para comparar planos dentro de cada categoria
- **Preços Dinâmicos**: Atualização instantânea ao alternar entre mensal/anual
- **Modal de Detalhes**: Informações completas sobre recursos e funcionalidades
- **Formulário de Cotação**: Envio de solicitações diretamente para o e-mail configurado
- **FAQ Accordion**: Seção de perguntas frequentes expansível
- **Design Responsivo**: Compatível com dispositivos móveis e desktop

### Backend
- Painel administrativo intuitivo com abas
- CRUD completo para planos, FAQs e funcionalidades
- Configurações personalizáveis
- Validação e sanitização de dados

## 📧 Formulário de Cotação

O formulário coleta as seguintes informações:
- Nome Completo
- E-mail
- Telefone/WhatsApp
- Plano de Interesse
- Recorrência (Mensal/Anual)
- Mensagem (opcional)

Os dados são enviados por e-mail para o endereço configurado no painel administrativo.

## 🎨 Personalização de Estilo

O plugin utiliza classes CSS prefixadas com `fcs-` para facilitar a personalização:

```css
/* Exemplo de personalização */
.futturu-hospedagemcloud-simulator {
    /* Suas customizações */
}
```

Arquivos CSS:
- `assets/css/frontend.css` - Estilos do frontend
- `assets/css/admin.css` - Estilos do painel administrativo

## 🔒 Segurança

- Sanitização de todos os dados de entrada
- Validação de nonce para requisições AJAX
- Proteção contra CSRF
- Escaping de saída para prevenir XSS

## 📁 Estrutura de Arquivos

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
│   ├── class-futturu-hospedagemcloud-admin.php
│   ├── class-futturu-hospedagemcloud-data.php
│   └── class-futturu-hospedagemcloud-frontend.php
├── futturu-hospedagemcloud-simulator.php
└── README.md
```

## 🔄 Atualização de Dados

Para atualizar os planos com base na tabela oficial da Cloudez:

1. Acesse **Configurações > Simulador Cloud Futturu**
2. Navegue até a aba **Planos**
3. Edite os valores conforme necessário
4. Clique em **Salvar Planos**

## 🌐 Internacionalização

O plugin está preparado para internacionalização (i18n) utilizando text domain `futturu-hospedagemcloud-sim`.

## 📝 Licença

GPL v2 or later

## 👥 Suporte

Para suporte técnico ou dúvidas, entre em contato:
- E-mail: suporte@futturu.com.br

## 🤝 Parceria

Este plugin foi desenvolvido para a **Futturu** em parceria com a **Cloudez**, oferecendo soluções de hospedagem em nuvem gerenciada de alto desempenho.

---

**Versão**: 1.0.0  
**Última Atualização**: 2024
