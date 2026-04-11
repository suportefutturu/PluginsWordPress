# Simulador de Hospedagem na Nuvem Futturu

Plugin WordPress para simulação de planos de hospedagem em nuvem gerenciada com foco em economia e escalabilidade. Parceria Cloudez.

## Descrição

O **Simulador de Hospedagem na Nuvem Futturu** é um plugin completo que permite aos potenciais clientes explorarem e compreenderem os planos de hospedagem em nuvem gerenciada da Futturu (parceria Cloudez), com foco especial em planos iniciais econômicos e a possibilidade de escalabilidade futura.

## Funcionalidades

### Frontend (via Shortcode)

- **Seção de Introdução**: Apresentação dos benefícios da hospedagem cloud Futturu/Cloudez
- **Micro-Quiz Educacional**: Questionário interativo para recomendar o plano ideal baseado no tráfego do site
- **Tabela de Planos**: Visualização completa dos planos com recursos, preços e caminho de escalabilidade
- **Simulação de Crescimento**: Timeline visual mostrando a evolução de planos ao longo do tempo
- **Comparação com Hospedagem Compartilhada**: Destaque das vantagens da cloud hospedagem
- **Formulário de Contato**: Modal para solicitação de cotação com envio de e-mail para suporte@futturu.com.br

### Backend (Painel Administrativo)

- **Gerenciamento de Planos**: CRUD completo para planos de hospedagem
- **Gerenciamento de Perfis**: Configuração de perfis de tráfego para recomendação
- **Configuração de Textos**: Personalização de todas as mensagens exibidas
- **Configurações Gerais**: E-mail de destino, ativação/desativação do plugin
- **Instruções de Uso**: Shortcode e guia de implementação

## Requisitos

- WordPress 5.0 ou superior
- PHP 7.4 ou superior
- jQuery (incluído no WordPress)

## Instalação

1. Faça o upload da pasta `futturu-cloud-scalable-simulator` para o diretório `/wp-content/plugins/`
2. Ative o plugin através do menu 'Plugins' no WordPress
3. Acesse **Configurações > Simulador Cloud Futturu** para configurar os planos e textos
4. Use o shortcode `[futturu_cloud_scalable_simulator]` em qualquer página ou post

## Uso

### Shortcode

```
[futturu_cloud_scalable_simulator]
```

Insira este shortcode em qualquer página, post ou widget do WordPress para exibir o simulador.

### Configuração Inicial

1. **Planos**: Configure os planos oferecidos (BR1G, BR2G, BR4G, BR8G, BR16G)
2. **Perfis**: Defina os perfis de tráfego para recomendação automática
3. **Textos**: Personalize as mensagens de introdução, quiz e CTAs
4. **E-mail**: Configure o e-mail que receberá as solicitações de cotação

## Estrutura de Arquivos

```
futturu-cloud-scalable-simulator/
├── futturu-cloud-scalable-sim.php    # Arquivo principal do plugin
├── includes/
│   ├── class-futturu-cloud-simulator-admin.php     # Classe administrativa
│   ├── class-futturu-cloud-simulator-frontend.php  # Classe frontend
│   └── class-futturu-cloud-simulator-ajax.php      # Handlers AJAX
├── assets/
│   ├── css/
│   │   └── futturu-cloud-simulator.css   # Estilos do simulador
│   └── js/
│       └── futturu-cloud-simulator.js    # JavaScript do simulador
└── README.md                           # Este arquivo
```

## Planos Incluídos (Padrão)

| Plano | RAM | CPU | Disco | Visualizações/mês | Sites | Preço |
|-------|-----|-----|-------|-------------------|-------|-------|
| BR1G | 1 GB | 1 Core | 25 GB SSD | 100.000 | 1-2 | R$ 239,00 |
| BR2G | 2 GB | 2 Cores | 40 GB SSD | 200.000 | 2-4 | R$ 479,00 |
| BR4G | 4 GB | 4 Cores | 80 GB SSD | 400.000 | 4-8 | R$ 1.009,00 |
| BR8G | 8 GB | 6 Cores | 160 GB SSD | 800.000 | 8-15 | R$ 1.589,00 |
| BR16G | 16 GB | 8 Cores | 320 GB SSD | 1.500.000+ | 15-30 | R$ 2.899,00 |

## Recursos Técnicos

- **Sanitização de Dados**: Todos os inputs são sanitizados antes de serem processados
- **Proteção CSRF**: Nonces do WordPress utilizados em todos os formulários
- **Responsivo**: Design adaptável para dispositivos móveis e desktop
- **Acessibilidade**: HTML semântico e labels apropriados
- **Open Source**: Utiliza apenas funções e bibliotecas open-source

## Personalização

### Cores

O plugin utiliza uma paleta de cores moderna com gradiente roxo/azul. Para personalizar, edite o arquivo `assets/css/futturu-cloud-simulator.css`.

### Textos

Todos os textos podem ser personalizados através do painel administrativo em **Configurações > Simulador Cloud Futturu**.

### E-mail de Destino

Por padrão, as solicitações são enviadas para `suporte@futturu.com.br`. Este e-mail pode ser alterado nas configurações do plugin.

## Suporte

Para suporte técnico ou dúvidas sobre o plugin, entre em contato:

- **E-mail**: suporte@futturu.com.br
- **Website**: https://futturu.com.br

## Licença

GPL v2 or later

## Créditos

Desenvolvido para Futturu em parceria com Cloudez.

## Changelog

### Versão 1.0.0
- Lançamento inicial do plugin
- Implementação do simulador de planos
- Micro-quiz educacional
- Tabela de planos com caminho de escalabilidade
- Formulário de contato com envio de e-mail
- Painel administrativo completo