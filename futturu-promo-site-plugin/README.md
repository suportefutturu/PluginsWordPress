# Futturu Promoção - Site Institucional Profissional

Plugin WordPress para promoção de criação de Sites Institucionais Profissionais com captura de leads e confirmação de pagamento via PIX.

## 📋 Descrição

Este plugin cria uma landing page de alta conversão para promover a criação de sites institucionais profissionais por um preço promocional. Inclui:

- **Timer de contagem regressiva** para criar urgência
- **Geração automática de QR Code PIX** (padrão EMVCo)
- **Formulário de captura de leads** com validação
- **Upload de comprovante de pagamento**
- **Envio de e-mail automático** para a equipe
- **Integração com WhatsApp** para envio de comprovantes
- **Painel administrativo completo** para configuração

## 🚀 Instalação

1. Faça o upload da pasta `futturu-promo-site-plugin` para `/wp-content/plugins/`
2. Ative o plugin através do menu 'Plugins' no WordPress
3. Acesse **Configurações > Promoção Futturu** para configurar

## ⚙️ Configuração

### Passo 1: Acessar o Painel Administrativo

1. No admin do WordPress, vá em **Configurações > Promoção Futturu**
2. Configure todas as opções conforme necessário

### Passo 2: Configurar Valores

| Campo | Descrição | Valor Padrão |
|-------|-----------|--------------|
| Status da Promoção | Ativar ou desativar a promoção | Ativo |
| Preço Normal | Preço original do serviço | R$ 2.500 |
| Preço Promocional | Preço com desconto | R$ 1.500 |
| Primeira Parcela | Valor para garantir a vaga | R$ 500 |
| Data/Hora Limite | Quando a promoção termina | +24 horas |

### Passo 3: Configurar Pagamento

| Campo | Descrição | Exemplo |
|-------|-----------|---------|
| Chave PIX | Sua chave PIX (e-mail, CPF, CNPJ ou telefone) | pix@futturu.com.br |
| WhatsApp | Número com código do país | 5591993100621 |
| QR Code (Opcional) | Upload de imagem QR Code estática | - |

### Passo 4: Configurar E-mail

| Campo | Descrição | Padrão |
|-------|-----------|--------|
| E-mail para Leads | Onde receber os dados dos clientes | suporte@futturu.com.br |

### Passo 5: Personalizar Textos

Personalize os textos exibidos na página:
- Título Principal
- Subtítulo
- Mensagem de Sucesso

## 📱 Como Usar

### Opção 1: Shortcode

Use o shortcode em qualquer página ou post:

```
[futturu_promo_site]
```

### Opção 2: URL Dedicada

Acesse diretamente pela URL:

```
https://seudominio.com/promocao-site-institucional/
```

## 🎨 Funcionalidades do Frontend

### Seção Hero (Impacto Inicial)
- Título chamativo com o valor promocional
- Comparação de preços (De/Por)
- Timer de contagem regressiva
- Botão CTA principal
- Prova social (prêmios, estatísticas)

### Seção de Benefícios
- 6 cards com benefícios do serviço
- Ícones ilustrativos
- Comparação de valores

### Seção de Pagamento
- Instruções passo a passo
- Opção PIX com QR Code gerado automaticamente
- Opção de parcelamento
- Formulário de confirmação
- Botão para enviar comprovante via WhatsApp

### Formulário de Captura
- Nome Completo*
- E-mail*
- Telefone*
- Nome da Empresa
- Mensagem (opcional)
- Forma de Pagamento (PIX/Cartão)
- Valor Pago
- Upload de Comprovante

## 🔧 Funcionalidades do Backend

### Configurações da Promoção
- Ativar/Desativar promoção
- Definir preços (normal, promocional, primeira parcela)
- Configurar data/hora limite
- Resetar timer para 24h

### Configurações de Pagamento
- Chave PIX
- Número do WhatsApp
- Upload de QR Code estático

### Configurações de E-mail
- E-mail de destino para leads
- Personalização de mensagens

## 📊 Captura de Leads

Os leads são armazenados de duas formas:

1. **E-mail**: Envio automático para o e-mail configurado
2. **Banco de Dados**: Armazenado nas opções do WordPress (`futturu_promo_leads`)

### Recuperar Leads Salvos

```php
$leads = get_option('futturu_promo_leads', array());
foreach ($leads as $lead) {
    echo $lead['name'] . ' - ' . $lead['email'];
}
```

## 🔐 Segurança

- Validação de nonce para todas as requisições AJAX
- Sanitização de todos os inputs
- Validação de tipo e tamanho de arquivo (máx. 5MB)
- Apenas imagens JPG/PNG permitidas para comprovantes

## 🌐 Geração de QR Code PIX

O plugin gera automaticamente um QR Code PIX no padrão EMVCo quando:
- Uma chave PIX é configurada
- Não há imagem de QR Code estática carregada

O QR Code inclui:
- Chave PIX
- Valor da transação
- Identificador único (TXID)
- CRC16 para validação

## 📝 Requisitos

- WordPress 5.0 ou superior
- PHP 7.0 ou superior
- jQuery (incluído no WordPress)
- Permissão de upload de arquivos

## 🛠️ Personalização

### Cores e Estilos

Edite o arquivo `assets/css/frontend.css` para personalizar:

```css
.futturu-promo-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.futturu-promo-btn-primary {
    background: #ff6b6b;
}
```

### Adicionar Mais Campos

Edite `includes/class-futturu-promo-frontend.php` para adicionar campos ao formulário.

## 🐛 Solução de Problemas

### O timer não aparece
Verifique se a data/hora limite está configurada corretamente no painel administrativo.

### QR Code não é gerado
Certifique-se de que a chave PIX está configurada no formato correto.

### E-mails não estão chegando
Verifique as configurações de SMTP do seu WordPress.

### Upload de arquivo falha
Verifique as permissões da pasta `wp-content/uploads` e o limite `upload_max_filesize` no php.ini.

## 📄 Licença

GPL v2 or later

## 👥 Suporte

Para dúvidas ou problemas:
- E-mail: suporte@futturu.com.br
- WhatsApp: +55 91 99310-0621

## 🔄 Changelog

### Versão 1.0.0
- Lançamento inicial
- Geração de QR Code PIX
- Formulário de captura
- Painel administrativo
- Timer de contagem regressiva
- Integração com WhatsApp
