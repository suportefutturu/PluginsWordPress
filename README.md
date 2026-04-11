# Simulador de Site Profissional Futturu

Plugin WordPress para gerar protótipos visuais personalizados de sites profissionais, otimizados e com foco em conversão, criados e hospedados pela Futturu. O objetivo é demonstrar valor, gerar engajamento e capturar leads qualificados interessados em contratar o serviço real de criação e hospedagem da Futturu.

## Descrição

O **Simulador de Site Profissional Futturu** permite que visitantes do seu site WordPress:

1. **Escolham o tipo de site** desejado (Site Profissional, Empresa de Serviços, Restaurante/Delivery, Catálogo Digital, etc.)
2. **Preencham informações do negócio** (nome, categoria, localidade, serviços, telefone, endereço)
3. **Visualizem um preview dinâmico** de como seria seu site profissional
4. **Enviem uma solicitação de proposta** personalizada

## Funcionalidades

### Frontend (Simulador)

- **Interface multi-step** com indicador de progresso visual
- **Cards interativos** para seleção do tipo de site
- **Formulário de informações** do negócio com validação em tempo real
- **Preview dinâmico** do site com:
  - Header personalizado com nome do negócio
  - Hero section com título e subtítulo
  - Seção "Sobre" com texto gerado automaticamente via templates
  - Seção "Serviços" com itens baseados na categoria
  - Seção "Contato" com formulário simulado, WhatsApp e mapa
  - Footer com badges "Otimizado para Google" e "Hospedado na Nuvem Futturu"
- **Resumo do projeto** antes do envio
- **Máscaras de telefone** no formato brasileiro
- **Design responsivo** e moderno
- **Animações suaves** entre etapas

### Backend (Painel Administrativo)

Acesse em **Configurações > Simulador Site Profissional** para:

- Ativar/Desativar o plugin
- Editar tipos de site disponíveis
- Editar categorias/setores
- Configurar templates de descrição para o preview
- Definir e-mail de destino dos leads
- Personalizar texto do CTA final
- Editar assunto do e-mail

## Instalação

1. Faça o upload da pasta `futturu-prof-site-sim` para `/wp-content/plugins/`
2. Ative o plugin através do menu 'Plugins' no WordPress
3. Acesse **Configurações > Simulador Site Profissional** para configurar
4. Use o shortcode `[futturu_prof_site_sim]` em qualquer página ou post

## Uso

### Shortcode Básico

```
[futturu_prof_site_sim]
```

### Shortcode com Parâmetros

```
[futturu_prof_site_sim show_title="false" cta_text="Entre em Contato"]
```

#### Parâmetros Disponíveis

| Parâmetro | Tipo | Padrão | Descrição |
|-----------|------|--------|-----------|
| `show_title` | boolean | `true` | Exibir ou ocultar o título do simulador |
| `cta_text` | string | "Solicite uma Proposta Personalizada" | Texto do botão de envio |

## Estrutura de Arquivos

```
futturu-prof-site-sim/
├── futturu-prof-site-sim.php    # Arquivo principal do plugin
├── includes/
│   ├── class-futturu-pss-admin.php    # Funcionalidades do admin
│   ├── class-futturu-pss-frontend.php # Renderização do frontend
│   └── class-futturu-pss-ajax.php     # Handlers AJAX para submission
├── assets/
│   ├── css/
│   │   └── frontend.css       # Estilos do simulador
│   └── js/
│       └── frontend.js        # JavaScript do simulador
└── README.md                  # Este arquivo
```

## Templates de Descrição

Os templates usam placeholders que são substituídos dinamicamente:

- `{nome}` - Nome do negócio/profissional
- `{categoria}` - Categoria/setor selecionado
- `{localidade}` - Cidade/região informada
- `{servicos}` - Principal serviço/produto oferecido

### Exemplo de Template

```
{nome} é referência em {categoria} na região de {localidade}. Oferecemos soluções personalizadas em {servicos} com qualidade e profissionalismo.
```

## Tipos de Site Padrão

- **professional** - Site Profissional (Advogados, Médicos, Engenheiros...)
- **services** - Site para Empresa de Serviços
- **restaurant** - Site para Restaurante ou Delivery
- **catalog** - Catálogo Digital / Loja Virtual Básica
- **other** - Outro

## Categorias/Setores Padrão

Advocacia, Medicina, Engenharia, Restaurante, Delivery, Consultoria, Oficina, Comércio, Educação, Saúde, Tecnologia, Beleza, Fitness, Imobiliário, Outros

## Envio de Leads

Quando um usuário envia o formulário:

1. Os dados são validados no frontend e backend
2. Um e-mail HTML formatado é enviado para o e-mail configurado
3. O e-mail inclui todas as informações do projeto e dados do cliente
4. O lead recebe uma mensagem de confirmação

### Dados Coletados

- Tipo de site selecionado
- Informações do negócio (nome, categoria, localidade, serviços, telefone, endereço)
- Dados do lead (nome, telefone, e-mail, mensagem)

## Requisitos

- WordPress 5.0 ou superior
- PHP 7.2 ou superior
- jQuery (incluído no WordPress)

## Compatibilidade

- Funciona com qualquer tema WordPress
- Totalmente responsivo (mobile, tablet, desktop)
- Compatível com plugins de cache
- Não conflita com outros plugins

## Segurança

- Validação de nonce em todos os formulários
- Sanitização de todos os dados de entrada
- Escaping de todos os dados de saída
- Verificação de capacidades do usuário no admin

## Personalização

### Cores

Edite o arquivo `assets/css/frontend.css` para personalizar as cores:

```css
/* Cor primária (azul) */
.futturu-pss-btn-primary {
    background: #2563eb; /* Altere para sua cor */
}

/* Cor do header */
.futturu-pss-header h2 {
    color: #1e40af; /* Altere para sua cor */
}
```

### Adicionar Novos Tipos de Site

No painel administrativo, adicione no formato:

```
slug|Nome Display
```

Exemplo:
```
ecommerce|Loja Virtual Completa
portfolio|Portfólio para Criativos
```

## Suporte

Para suporte técnico ou dúvidas:

- E-mail: suporte@futturu.com.br
- Website: https://futturu.com.br

## Changelog

### Versão 1.0.0
- Lançamento inicial
- Simulador multi-step funcional
- Preview dinâmico de sites
- Captura de leads via e-mail
- Painel administrativo completo
- Design responsivo e moderno

## Licença

GPL v2 or later

## Créditos

Desenvolvido por **Futturu** - Hospedagem Cloud Gerenciada

https://futturu.com.br
