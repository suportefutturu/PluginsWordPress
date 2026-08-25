# Lead Scoring Form - Plugin WordPress

## 🎯 Descrição

Plugin WordPress para formulário inteligente de **Lead Scoring** com classificação automática de prioridade. Este plugin substitui o genérico "Fale Conosco" por um formulário estratégico que aplica os gatilhos mentais de **Compromisso e Coerência**, permitindo que sua equipe classifique a prioridade de cada lead automaticamente.

## ✨ Funcionalidades

- **Formulário Inteligente**: Campos estratégicos que qualificam o lead durante o preenchimento
- **Lead Scoring Automático**: Sistema de pontuação baseado nas respostas (0-100 pontos)
- **Classificação de Prioridade**: 
  - 🔴 Alta Prioridade (70+ pontos)
  - 🟡 Média Prioridade (45-69 pontos)
  - 🟢 Baixa Prioridade (< 45 pontos)
- **Notificações por E-mail**: Template profissional HTML com todas as informações do lead
- **Painel Administrativo**: Visualize e gerencie todos os leads recebidos
- **Cores Personalizáveis**: Edite as cores principais para combinar com sua marca
- **Design Moderno e Responsivo**: Funciona perfeitamente em dispositivos móveis
- **Segurança**: Nonce verification e sanitização de dados

## 📋 Requisitos

- WordPress 5.0 ou superior
- PHP 7.4 ou superior
- jQuery (incluso no WordPress)

## 🚀 Instalação

1. Faça o upload da pasta `lead-scoring-form` para `/wp-content/plugins/`
2. Ative o plugin através do menu 'Plugins' no WordPress
3. Acesse **Lead Scoring → Configurações** para personalizar
4. Use o shortcode `[lead_scoring_form]` em qualquer página ou post

## 📝 Uso

### Shortcode Básico

```php
[lead_scoring_form]
```

### No Código do Tema

```php
<?php echo do_shortcode('[lead_scoring_form]'); ?>
```

### Em Templates PHP

```php
<?php echo LSF_Form::render_form(array()); ?>
```

## ⚙️ Configurações

Acesse **Lead Scoring → Configurações** no admin do WordPress para:

### E-mail de Destino
- Configure o e-mail que receberá as notificações (padrão: `suporte@futturu.com.br`)

### Textos Personalizáveis
- **Título do Formulário**: Título principal em H2 (padrão: "Pare de perder dinheiro com um site que não converte.")
- **Subtítulo**: Texto descritivo abaixo do título
- **Texto do Botão**: Texto do botão de envio (CTA)
- **Mensagem de Sucesso**: Mensagem exibida após o envio bem-sucedido

### Cores Personalizáveis
- **Cor Primária**: Cor principal dos elementos e gradientes
- **Cor Secundária**: Cor secundária para detalhes
- **Cor de Fundo**: Fundo do formulário
- **Cor do Texto**: Cor do texto principal
- **Cor do Botão**: Cor de fundo do botão de envio
- **Cor do Texto do Botão**: Cor do texto dentro do botão

## 🎯 Sistema de Pontuação

O lead scoring é calculado automaticamente com base nas respostas:

### Objetivo Principal (até 40 pontos)
- Atrair novos clientes e aumentar vendas: **40 pontos**
- Reformular um site atual: **35 pontos**
- Criar novo projeto do zero: **30 pontos**
- Apenas pesquisando preços: **10 pontos**

### Prazo Ideal (até 35 pontos)
- Imediato (essa semana): **35 pontos**
- 1 a 3 meses: **25 pontos**
- Sem prazo definido: **10 pontos**

### Modelo de Investimento (até 25 pontos)
- Pagamento Único: **25 pontos**
- WaaS - Mensalidade: **20 pontos**
- Ainda não sei: **15 pontos**

## 📊 Painel de Leads

Acesse **Lead Scoring → Leads Recebidos** para:

- Visualizar todos os leads em ordem cronológica
- Ver score e prioridade de cada lead
- Filtrar por paginação (20 leads por página)
- Excluir leads quando necessário

## 🎨 Estrutura de Arquivos

```
lead-scoring-form/
├── lead-scoring-form.php      # Arquivo principal do plugin
├── includes/
│   ├── class-lsf-admin.php    # Configurações administrativas
│   ├── class-lsf-form.php     # Manipulação do formulário
│   └── class-lsf-email.php    # Envio de e-mails
├── assets/
│   ├── css/
│   │   └── style.css          # Estilos frontend
│   └── js/
│       └── script.js          # JavaScript frontend
└── README.md                   # Esta documentação
```

## 📧 Template de E-mail

Os e-mails de notificação incluem:

- Cabeçalho com cor personalizada
- Badge de prioridade (Alta/Média/Baixa)
- Score de qualificação em destaque
- Todas as informações do lead formatadas
- Botão para contato via WhatsApp
- Rodapé informativo

## 🔒 Segurança

- Verificação de nonce em todos os formulários
- Sanitização de dados de entrada
- Escape de dados de saída
- Validação de campos obrigatórios
- Proteção contra spam básica

## 🌐 Tradução

O plugin está pronto para tradução. Os textos estão em português brasileiro com domínio `lead-scoring-form`.

Para adicionar traduções:

1. Crie a pasta `/languages/` no diretório do plugin
2. Use um plugin como Loco Translate para criar arquivos `.po` e `.mo`

## 🆘 Suporte

Para suporte técnico ou dúvidas:

- E-mail: suporte@futturu.com.br
- Documentação completa disponível no painel administrativo

## 📄 Licença

GPL v2 or later

## 👨‍💻 Desenvolvedor

**Futturu** - https://futturu.com.br

Especialistas em criação de sites em Belém.

---

*Este plugin foi desenvolvido para ajudar empresas a qualificarem melhor seus leads e aumentarem suas taxas de conversão.*
