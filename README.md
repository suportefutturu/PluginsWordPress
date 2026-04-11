# Simulador de Hospedagem na Nuvem Futturu

Plugin WordPress para simulação e comparação de planos de hospedagem em nuvem gerenciada, em parceria com Cloudez.

## Descrição

O **Simulador de Hospedagem na Nuvem Futturu** é um plugin completo que permite que potenciais clientes explorem e comparem planos de hospedagem em nuvem, entendam o valor e benefícios da solução da Futturu, e sejam conduzidos a um CTA claro para solicitar cotação.

## Funcionalidades

### Frontend (Simulador)

- **Seção de Introdução**: Texto explicativo sobre o simulador
- **Benefícios em Destaque**: 6 cards destacando os principais benefícios:
  - Hospedagem Gerenciada
  - CDN e Otimizações
  - Backups Automáticos
  - Monitoramento 24/7
  - Certificado SSL Grátis
  - Suporte Técnico Especializado
- **Navegação por Categorias**:
  - ☁️ Clouds Padrão (Uso Geral)
  - 🧠 Clouds Focados em RAM
  - ⚙️ Clouds Focados em CPU
  - 📧 Clouds para E-mails
- **Tabela de Planos**: Exibição clara de recursos (RAM, CPU, Disco SSD, Visualizações)
- **Planos em Destaque**: Badge "Mais Contratado" para planos recomendados
- **Modal de Detalhes**: Informações adicionais de cada plano
- **Formulário de Cotação**: Modal com formulário para solicitação de contato
- **Design Responsivo**: Funciona em qualquer dispositivo

### Backend (Painel Administrativo)

- **Gerenciar Planos**: CRUD completo para planos de hospedagem
- **Gerenciar Categorias**: Adicionar/editar/remover categorias
- **Configurações**: Editar textos introdutórios, CTAs e e-mail de destino
- **Shortcode**: Instruções de uso do shortcode

## Instalação

1. Faça o upload da pasta `futturu-cloud-simulator` para o diretório `/wp-content/plugins/`
2. Ative o plugin através do menu 'Plugins' no WordPress
3. Acesse **Configurações > Simulador Cloud Futturu** para configurar

## Uso

### Shortcode

Para exibir o simulador em qualquer página ou post, use o shortcode:

```
[futturu_cloud_simulator]
```

### Passos para usar:

1. Crie uma nova página no WordPress
2. Adicione um bloco de Shortcode
3. Cole `[futturu_cloud_simulator]`
4. Publique a página

## Configuração

Acesse o painel administrativo em **Configurações > Simulador Cloud Futturu**:

### Aba Planos
- Visualize todos os planos cadastrados
- Adicione novos planos com modelo, categoria, recursos e preço
- Edite ou exclua planos existentes
- Marque planos como "Destaque" para aparecerem com badge especial

### Aba Categorias
- Gerencie as categorias de planos
- Defina ordem de exibição
- Adicione ícones (emojis) para cada categoria

### Aba Configurações
- **Texto Introdutório**: Mensagem exibida no topo do simulador
- **Texto do CTA Global**: Mensagem após a tabela de planos
- **E-mail para Contato**: E-mail que receberá as solicitações (padrão: suporte@futturu.com.br)
- **Plugin Ativo**: Ativar/desativar o shortcode

## Dados dos Planos Inclusos

O plugin já inclui planos pré-configurados:

### Clouds Padrão
- Série BR: BR1G, BR2G, BR4G, BR8G, BR16G
- Série USA: Default USA1G até Default USA192G

### Clouds Focados em RAM
- Elite RAM 32GB, 64GB
- Ultra RAM 128GB, 256GB

### Clouds Focados em CPU
- Prestige CPU 16 Cores
- Elite CPU 24, 32, 64 Cores

### Clouds para E-mails
- Email 100G, 250G, 500G, 750G, 1000G

## Requisitos

- WordPress 5.0 ou superior
- PHP 7.4 ou superior
- MySQL 5.6 ou superior

## Estrutura de Arquivos

```
futturu-cloud-simulator/
├── futturu-cloud-simulator.php    # Arquivo principal do plugin
├── admin/
│   └── class-fcs-admin.php        # Lógica do painel administrativo
├── includes/
│   ├── class-fcs-plans.php        # Gerenciamento de dados dos planos
│   ├── class-fcs-frontend.php     # Renderização do frontend
│   └── class-fcs-ajax.php         # Handler AJAX para formulários
├── assets/
│   ├── css/
│   │   ├── frontend.css           # Estilos do simulador
│   │   └── admin.css              # Estilos do admin
│   └── js/
│       ├── frontend.js            # JavaScript do frontend
│       └── admin.js               # JavaScript do admin
└── README.md                      # Este arquivo
```

## Segurança

- Sanitização de todos os dados de entrada
- Validação de nonces WordPress
- Proteção contra CSRF
- Escape de dados na saída
- Validação de e-mail

## Envio de Formulário

Quando um visitante preenche o formulário de cotação:

1. Os dados são validados no frontend e backend
2. Um e-mail é enviado para o endereço configurado
3. Os dados são salvos no banco de dados (tabela `wp_fcs_quotes`)
4. O usuário recebe confirmação de envio

## Personalização

### Cores
Edite o arquivo `assets/css/frontend.css` para personalizar as cores conforme a identidade visual da Futturu.

### Planos
Adicione ou edite planos através do painel administrativo ou modifique os dados padrão em `includes/class-fcs-plans.php`.

### Textos
Todos os textos podem ser editados na aba "Configurações" do painel administrativo.

## Licença

GPL v2 or later

## Suporte

Para suporte técnico, entre em contato:
- E-mail: suporte@futturu.com.br

## Parceiro

Planos oferecidos em parceria com **Cloudez** - Tecnologia de ponta e confiabilidade para o seu projeto.
