# Simulador de Hospedagem na Nuvem Futturu

Plugin WordPress para simulação e comparação de planos de hospedagem em nuvem gerenciada, baseado nos dados do parceiro Cloudez.

## 📋 Descrição

O **Simulador de Hospedagem na Nuvem Futturu** permite que potenciais clientes explorem e comparem os planos de hospedagem em nuvem gerenciada, entendam o valor e benefícios da solução da Futturu, e sejam conduzidos a um CTA claro para solicitação de cotação.

### Funcionalidades Principais

- ✅ **4 Categorias de Planos**: Clouds Padrão, RAM, CPU e E-mails
- ✅ **38 Planos Pré-configurados**: Dados reais do parceiro Cloudez
- ✅ **Ordenação por Preço**: Do maior para o menor em cada categoria
- ✅ **Shortcode Flexível**: Use `[futturu_cloud_simulator]` em qualquer página
- ✅ **Design Responsivo**: Funciona em qualquer tema WordPress
- ✅ **Painel Administrativo**: CRUD completo para planos e categorias
- ✅ **Formulário de Cotação**: Envio direto para suporte@futturu.com.br
- ✅ **Benefícios em Destaque**: Ícones e descrições dos diferenciais Futturu
- ✅ **Planos em Destaque**: Badge "Mais Contratado" configurável
- ✅ **100% Open Source**: Sem dependências de bibliotecas pagas

## 🚀 Instalação

1. **Faça o upload do plugin**:
   - Copie a pasta `futturu-cloud-simulator` para `/wp-content/plugins/`
   - Ou faça upload via WordPress Admin → Plugins → Adicionar Novo → Enviar Plugin

2. **Ative o plugin**:
   - No menu lateral do WordPress, vá em **Plugins**
   - Encontre **Simulador Cloud Futturu** e clique em **Ativar**

3. **Configure (opcional)**:
   - Vá em **Configurações → Simulador Cloud Futturu**
   - Edite textos, CTAs e gerencie planos conforme necessário

4. **Use no seu site**:
   - Crie ou edite uma página
   - Insira o shortcode: `[futturu_cloud_simulator]`
   - Publique a página

## 📦 Estrutura do Plugin

```
futturu-cloud-simulator/
├── futturu-cloud-simulator.php    # Arquivo principal do plugin
├── admin/
│   └── class-fcs-admin.php        # Painel administrativo
├── includes/
│   ├── class-fcs-plans.php        # Gerenciamento de dados e banco
│   ├── class-fcs-frontend.php     # Renderização do simulador
│   └── class-fcs-ajax.php         # Processamento de formulários
├── assets/
│   ├── css/
│   │   ├── frontend.css           # Estilos do simulador
│   │   └── admin.css              # Estilos do admin
│   └── js/
│       ├── frontend.js            # Interatividade frontend
│       └── admin.js               # Funcionalidades admin
└── README.md                      # Esta documentação
```

## 🎯 Categorias de Planos

### ☁️ Clouds Padrão (Uso Geral)
Planos balanceados para uso geral, com opções no Brasil (BR) e EUA (Default USA).
- **16 planos** disponíveis
- Faixa de preço: R$ 89,00 - R$ 18.879,00
- Recursos: RAM, CPU, Disco SSD, Visualizações/mês

### 🧠 Clouds Focados em RAM
Planos otimizados para aplicações que demandam muita memória.
- **5 planos** disponíveis
- Faixa de preço: R$ 1.299,00 - R$ 17.579,00
- RAM: 24 GB - 300 GB

### ⚙️ Clouds Focados em CPU
Planos otimizados para processamento intensivo.
- **6 planos** disponíveis
- Faixa de preço: R$ 649,00 - R$ 13.149,00
- CPU: 2 - 48 Cores

### 📧 Clouds para E-mails
Planos especializados para servidores de e-mail.
- **12 planos** disponíveis
- Faixa de preço: R$ 129,00 - R$ 2.049,00
- Disco: 20 GB - 1000 GB

## ⚙️ Configuração

### Painel Administrativo

Acesse em **Configurações → Simulador Cloud Futturu**:

#### Aba "Planos"
- Visualizar todos os planos cadastrados
- Adicionar novo plano
- Editar plano existente
- Excluir plano
- Ativar/desativar plano
- Marcar como "Mais Contratado"

#### Aba "Categorias"
- Gerenciar categorias de planos
- Alterar ordem de exibição
- Ativar/desativar categorias

#### Aba "Configurações"
- **Texto Introdutório**: Mensagem inicial do simulador
- **Texto do CTA**: Chamada para ação final
- **E-mail de Destino**: Para onde vão as cotações (padrão: suporte@futturu.com.br)
- **Status do Plugin**: Ativar/desativar exibição do shortcode

## 🎨 Personalização

### Shortcodes Disponíveis

```php
// Shortcode básico
[futturu_cloud_simulator]

// Com parâmetros futuros (planejado)
[futturu_cloud_simulator category="clouds-padrao"]
[futturu_cloud_simulator show_benefits="false"]
```

### Benefícios Exibidos

O plugin destaca 6 benefícios principais da Futturu:

1. 🖥️ **Hospedagem Gerenciada** - Gerenciamos tudo para você
2. ⚡ **CDN e Otimizações** - Performance máxima
3. 💾 **Backups Automáticos** - Seus dados sempre seguros
4. 👁️ **Monitoramento 24/7** - Sempre online
5. 🔒 **Certificado SSL Grátis** - Segurança incluída
6. 🛟 **Suporte Técnico Especializado** - Equipe pronta para ajudar

## 📧 Formulário de Cotação

Quando um cliente clica em "Solicitar Cotação":

1. Um modal é aberto com formulário simplificado
2. Campos solicitados:
   - Nome completo
   - E-mail
   - Telefone/WhatsApp
   - Plano de interesse (preenchido automaticamente)
   - Mensagem opcional
3. Os dados são enviados para o e-mail configurado
4. Validação anti-spam inclusa

## 🗄️ Banco de Dados

O plugin cria duas tabelas no WordPress:

- `wp_fcs_plans` - Armazena os planos de hospedagem
- `wp_fcs_categories` - Armazena as categorias de planos

Os dados são preservados mesmo se o plugin for desativado. Para remover completamente, exclua as tabelas manualmente.

## 🔧 Desenvolvimento

### Requisitos Técnicos

- WordPress 5.0 ou superior
- PHP 7.4 ou superior
- MySQL 5.6 ou superior / MariaDB 10.1+
- Permissões de escrita no banco de dados

### Hooks e Filtros (Planejados)

```php
// Filtro para modificar preços
apply_filters('fcs_plan_price', $price, $plan);

// Ação após envio de cotação
do_action('fcs_quote_sent', $data);
```

### Adicionar Planos Personalizados

Via código, use:

```php
global $wpdb;
$wpdb->insert(
    $wpdb->prefix . 'fcs_plans',
    array(
        'model' => 'Seu Plano',
        'category_id' => 1,
        'ram' => '8GB',
        'cpu' => '4 Cores',
        'disk' => '160GB SSD',
        'views' => '1.000.000',
        'price' => 999.00,
        'details' => 'Descrição do plano',
        'is_featured' => 0,
        'is_active' => 1
    )
);
```

## 🤝 Parceria Cloudez

Este plugin utiliza dados oficiais do parceiro **Cloudez**, reforçando confiabilidade e tecnologia de ponta. Os planos são oferecidos em parceria, garantindo:

- Infraestrutura de alta qualidade
- Suporte especializado
- Tecnologia atualizada
- Confiabilidade comprovada

## 📞 Suporte

Para dúvidas, sugestões ou problemas:

- **E-mail**: suporte@futturu.com.br
- **Site**: [www.futturu.com.br](https://www.futturu.com.br)

## 📝 Changelog

### Versão 1.0.0
- Lançamento inicial
- 38 planos pré-configurados
- 4 categorias de planos
- Painel administrativo completo
- Formulário de cotação funcional
- Design responsivo e moderno

## 📄 Licença

Este plugin é open-source e distribuído sob a licença GPL v2 ou posterior.

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

---

**Desenvolvido com ❤️ pela equipe Futturu**
