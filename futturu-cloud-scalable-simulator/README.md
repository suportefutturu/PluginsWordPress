# Simulador de Hospedagem na Nuvem Futturu

Plugin WordPress para simulação de planos de hospedagem em nuvem com foco em conversão, economia e escalabilidade. Desenvolvido em parceria com a tecnologia Cloudez.

## 🚀 Funcionalidades

### Frontend (Shortcode)
- **Micro-Quiz Educacional**: Questionário interativo que recomenda o plano ideal baseado no tráfego do site
- **Tabela de Planos**: Cards modernos mostrando recursos, preços e caminho de escalabilidade
- **Comparação**: Seção comparativa entre Cloud Server vs Hospedagem Compartilhada
- **Timeline de Crescimento**: Visualização do caminho de upgrade (BR1G → BR4G → BR8G)
- **Modal de Contato**: Formulário de captura de leads com envio automático por e-mail
- **Design Responsivo**: Totalmente adaptável para mobile, tablet e desktop

### Backend (Painel Administrativo)
- **Gerenciamento de Planos**: CRUD completo para planos de hospedagem
- **Perfis de Uso**: Configuração de perfis de tráfego para recomendação automática
- **Textos Personalizáveis**: Edição de todos os textos exibidos no simulador
- **Configuração de CTA**: Personalização de chamadas para ação e e-mail de destino
- **Ativar/Desativar**: Controle para habilitar ou ocultar o simulador

## 📋 Requisitos

- WordPress 5.0 ou superior
- PHP 7.4 ou superior
- jQuery (incluído no WordPress)

## 📦 Instalação

1. Faça upload da pasta `futturu-cloud-scalable-simulator` para `/wp-content/plugins/`
2. Ative o plugin no menu "Plugins" do WordPress
3. Acesse **Configurações > Simulador Cloud Futturu** para configurar
4. Use o shortcode `[futturu_cloud_scalable_simulator]` em qualquer página ou post

## 🔧 Uso do Shortcode

```php
[futturu_cloud_scalable_simulator]
```

Insira este shortcode em qualquer página, post ou widget do WordPress para exibir o simulador completo.

## ⚙️ Configuração Inicial

Após ativar o plugin, acesse o painel administrativo:

1. **Planos**: Configure os planos oferecidos (já inclui 5 planos padrão baseados na Cloudez)
2. **Perfis**: Defina os perfis de tráfego para o quiz de recomendação
3. **Textos**: Personalize mensagens, benefícios e comparações
4. **CTA**: Configure o texto dos botões e e-mail de destino dos leads
5. **Configurações**: Ative/desative o simulador ou resete para padrões

## 📊 Planos Incluídos (Padrão)

| Plano | RAM | CPU | SSD | Visitas/mês | Preço |
|-------|-----|-----|-----|-------------|-------|
| BR1G - Inicial | 1 GB | 1 vCPU | 25 GB | 100K | R$ 239/mês |
| BR2G - Crescimento | 2 GB | 2 vCPU | 50 GB | 200K | R$ 479/mês |
| BR4G - Profissional | 4 GB | 4 vCPU | 80 GB | 400K | R$ 1.009/mês |
| BR8G - Avançado | 8 GB | 8 vCPU | 160 GB | 800K | R$ 1.589/mês |
| BR16G - Enterprise | 16 GB | 16 vCPU | 320 GB | 1.5M | R$ 2.899/mês |

## 🎯 Foco em Conversão

O plugin foi desenvolvido com técnicas de conversão:

- **Jornada Guiada**: Quiz inicial direciona para o plano ideal
- **Prova Social**: Badges de "Mais Popular" nos planos destacados
- **Escassez Inteligente**: Comparação com limitações da hospedagem compartilhada
- **Caminho Claro**: Timeline visual mostra evolução possível
- **CTA Estratégico**: Múltiplos pontos de contato com formulários simplificados
- **Foco em Economia**: Mensagens destacam começar pequeno e escalar sob demanda

## 🔒 Segurança

- Sanitização de todos os dados de entrada
- Validação de nonces WordPress para AJAX
- Proteção contra XSS com `esc_html()`, `esc_attr()`, `esc_textarea()`
- Verificação de permissões com `current_user_can()`
- Validação de e-mail e campos obrigatórios no formulário

## 📁 Estrutura de Arquivos

```
futturu-cloud-scalable-simulator/
├── futturu-cloud-scalable-sim.php    # Plugin principal
├── README.md                         # Esta documentação
├── includes/
│   ├── class-futturu-cloud-simulator-admin.php      # Painel admin
│   └── class-futturu-cloud-simulator-frontend.php   # Renderizador frontend
└── assets/
    ├── css/
    │   └── futturu-cloud-simulator.css              # Estilos
    └── js/
        └── futturu-cloud-simulator.js               # JavaScript
```

## 🛠️ Personalização

### Cores (CSS Variables)

Edite no arquivo CSS:

```css
:root {
    --fcs-primary: #2563eb;      /* Cor principal */
    --fcs-secondary: #0ea5e9;    /* Cor secundária */
    --fcs-accent: #10b981;       /* Cor de destaque */
    --fcs-dark: #1e293b;         /* Texto escuro */
}
```

### Adicionar Novos Planos

Via painel admin ou programaticamente:

```php
update_option('futturu_cloud_plans', array(
    array(
        'id' => 'novo-plano',
        'name' => 'Nome do Plano',
        'category' => 'starter',
        'ram' => 2,
        'cpu' => 2,
        'disk' => 50,
        'views' => 200000,
        'sites' => '2-4',
        'price' => 479.00,
        'next_plan' => 'proximo-plano',
        'featured' => false,
        'description' => 'Descrição do plano'
    )
));
```

## 📧 Envio de Leads

Os formulários enviam e-mails para o endereço configurado no painel admin (padrão: `suporte@futturu.com.br`).

**Dados enviados:**
- Nome completo
- E-mail
- Telefone/WhatsApp
- Perfil de tráfego selecionado
- Plano de interesse
- Mensagem personalizada

## 🔄 Atualização de Dados

Para atualizar preços ou recursos:

1. Acesse **Configurações > Simulador Cloud Futturu**
2. Vá até a aba **Planos**
3. Clique em **Editar** no plano desejado
4. Modifique os valores e salve

## 🆘 Suporte Técnico

- **Documentação**: Consulte este README
- **E-mail**: suporte@futturu.com.br
- **Painel Admin**: Configurações > Simulador Cloud Futturu

## 📄 Licença

GPL v2 or later - https://www.gnu.org/licenses/gpl-2.0.html

## 👥 Créditos

Desenvolvido para **Futturu** utilizando infraestrutura **Cloudez**.

---

**Versão**: 2.0.0  
**Última Atualização**: 2024
