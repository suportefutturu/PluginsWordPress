# Simulador de Hospedagem na Nuvem Futturu

Plugin WordPress para simulação de planos de hospedagem em nuvem gerenciada com foco em economia e escalabilidade. Parceria Cloudez.

## Descrição

O plugin **Simulador de Hospedagem na Nuvem Futturu** permite que potenciais clientes explorem e compreendam os planos de hospedagem em nuvem gerenciada da Futturu, com foco especial em planos iniciais econômicos e a possibilidade de escalabilidade futura.

### Funcionalidades

- **Micro-Quiz Educacional**: Direciona usuários para o plano ideal baseado no tráfego do site
- **Tabela de Planos Econômicos & Escaláveis**: Mostra recursos, preços e caminho de upgrade
- **Timeline de Crescimento**: Visualização do caminho de escalabilidade (BR1G → BR4G → BR8G)
- **Comparação com Hospedagem Compartilhada**: Destaca vantagens da cloud gerenciada
- **Formulário de Contato Modal**: Captura leads e envia para suporte@futturu.com.br
- **Painel Administrativo Completo**: Gerencie planos, perfis, textos e configurações

## Requisitos

- WordPress 5.0 ou superior
- PHP 7.4 ou superior
- jQuery (incluído no WordPress)

## Instalação

1. Faça o upload da pasta `futturu-cloud-scalable-simulator` para o diretório `/wp-content/plugins/`
2. Ative o plugin através do menu 'Plugins' no WordPress
3. Acesse **Configurações > Simulador Cloud Futturu** para configurar os planos e opções
4. Use o shortcode `[futturu_cloud_scalable_simulator]` em qualquer página ou post

## Uso

### Shortcode

Insira o shortcode em qualquer página ou post:

```
[futturu_cloud_scalable_simulator]
```

### Configuração Inicial

Após ativar o plugin:

1. Acesse **Configurações > Simulador Cloud Futturu** no menu administrativo
2. Configure os planos de hospedagem (já incluídos: BR1G, BR2G, BR4G, BR8G, BR16G)
3. Ajuste os perfis de uso conforme necessário
4. Personalize os textos introdutórios e CTAs
5. Verifique se o e-mail de destino está correto (padrão: suporte@futturu.com.br)

## Estrutura de Arquivos

```
futturu-cloud-scalable-simulator/
├── futturu-cloud-scalable-sim.php      # Arquivo principal do plugin
├── includes/
│   ├── class-futturu-cloud-simulator-admin.php     # Painel administrativo
│   ├── class-futturu-cloud-simulator-frontend.php  # Renderização do shortcode
│   └── class-futturu-cloud-simulator-ajax.php      # Handlers AJAX
├── assets/
│   ├── css/
│   │   └── futturu-cloud-simulator.css             # Estilos frontend/admin
│   └── js/
│       └── futturu-cloud-simulator.js              # JavaScript para interatividade
└── README.md                                       # Este arquivo
```

## Planos Incluídos (Dados Cloudez)

| Plano  | RAM   | CPU      | Disco SSD | Visualizações/mês | Sites    | Preço/mês |
|--------|-------|----------|-----------|-------------------|----------|-----------|
| BR1G   | 1 GB  | 1 Core   | 25 GB     | 100.000           | 1-2      | R$ 239    |
| BR2G   | 2 GB  | 2 Cores  | 40 GB     | 200.000           | 2-4      | R$ 479    |
| BR4G   | 4 GB  | 4 Cores  | 80 GB     | 400.000           | 4-8      | R$ 1.009  |
| BR8G   | 8 GB  | 6 Cores  | 160 GB    | 800.000           | 8-15     | R$ 1.589  |
| BR16G  | 16 GB | 8 Cores  | 320 GB    | 1.500.000+        | 15-30    | R$ 2.899  |

## Perfis de Uso

O plugin inclui 4 perfis pré-configurados:

1. **Site Novo ou Institucional Simples** (até 100k visualizações) → Recomenda BR1G
2. **Pequeno Negócio com Tráfego Moderado** (100k-300k) → Recomenda BR2G
3. **Negócio em Crescimento ou E-commerce Leve** (300k-500k) → Recomenda BR4G
4. **Tráfego Alto ou E-commerce Pesado** (500k+) → Recomenda BR8G

## Personalização

### Editar Planos

No painel administrativo, você pode:

- Adicionar, editar ou excluir planos
- Alterar recursos (RAM, CPU, disco)
- Modificar preços
- Definir caminho de escalabilidade (próximo plano sugerido)

### Editar Textos

Personalize:

- Título e texto introdutório
- Pergunta do micro-quiz
- Mensagens dos CTAs
- Título da tabela de planos

### Configurações

- Ativar/desativar o simulador
- Alterar e-mail de destino dos formulários

## Segurança

- Sanitização de todos os dados de entrada
- Proteção CSRF com nonces WordPress
- Validação de e-mail nos formulários
- Verificação de permissões administrativas

## Envio de Formulário

Os dados do formulário de contato são enviados via:

- **Destinatário**: suporte@futturu.com.br (configurável)
- **Método**: wp_mail() do WordPress
- **Dados incluídos**: Nome, e-mail, telefone, perfil de tráfego, plano selecionado, mensagem

## Design e Responsividade

O plugin utiliza:

- Design moderno e profissional
- Totalmente responsivo (mobile-first)
- Cores alinhadas à identidade Futturu/Cloudez
- Ícones e elementos visuais intuitivos

## Suporte Técnico

Para dúvidas ou problemas:

- E-mail: suporte@futturu.com.br
- Documentação completa disponível no painel administrativo

## Licença

GPL v2 ou posterior

## Créditos

Desenvolvido para **Futturu** em parceria com **Cloudez**.

---

**Versão**: 1.0.0  
**Autor**: Futturu  
**Website**: https://futturu.com.br
