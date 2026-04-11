<?php
/**
 * Class Futturu_HospedagemCloud_Data
 * Gerencia os dados dos planos, FAQs e configurações
 */

if (!defined('ABSPATH')) {
    exit;
}

class Futturu_HospedagemCloud_Data {

    /**
     * Construtor
     */
    public function __construct() {
        // Inicialização se necessário
    }

    /**
     * Obter categorias disponíveis
     */
    public function get_categories() {
        return array(
            'padrao' => '☁️ Clouds Padrão (Uso Geral)',
            'ram' => '🧠 Clouds Focados em Memória RAM',
            'cpu' => '⚙️ Clouds Focados em Processamento (CPU)',
            'email' => '📧 Clouds para E-mails'
        );
    }

    /**
     * Obter planos padrão
     * Nomes padronizados: "BR XGB" ou "USA XGB" conforme RAM
     */
    public function get_default_plans() {
        $plans = array();

        // ☁️ Clouds Padrão (Uso Geral) - Ordenados do mais barato para o mais caro
        $plans[] = array(
            'id' => 'usa-1g-ind',
            'categoria' => 'padrao',
            'nome' => 'USA 1GB Individual',
            'ram' => '1 GB',
            'cpu' => '1 Core',
            'disco' => '25 GB',
            'visualizacoes' => '100.000 (1 site)',
            'preco_mensal' => 89,
            'uso_indicado' => 'Sites pessoais'
        );
        $plans[] = array(
            'id' => 'usa-1g',
            'categoria' => 'padrao',
            'nome' => 'USA 1GB',
            'ram' => '1 GB',
            'cpu' => '1 Core',
            'disco' => '25 GB',
            'visualizacoes' => '100.000',
            'preco_mensal' => 119,
            'uso_indicado' => ''
        );
        $plans[] = array(
            'id' => 'br-1g-ind',
            'categoria' => 'padrao',
            'nome' => 'BR 1GB Individual',
            'ram' => '1 GB',
            'cpu' => '1 Core',
            'disco' => '25 GB',
            'visualizacoes' => '100.000 (1 site)',
            'preco_mensal' => 219,
            'uso_indicado' => 'Sites pessoais'
        );
        $plans[] = array(
            'id' => 'br-1g',
            'categoria' => 'padrao',
            'nome' => 'BR 1GB',
            'ram' => '1 GB',
            'cpu' => '1 Core',
            'disco' => '25 GB',
            'visualizacoes' => '100.000',
            'preco_mensal' => 239,
            'uso_indicado' => ''
        );
        $plans[] = array(
            'id' => 'usa-2g',
            'categoria' => 'padrao',
            'nome' => 'USA 2GB',
            'ram' => '2 GB',
            'cpu' => '1 Core',
            'disco' => '50 GB',
            'visualizacoes' => '300.000',
            'preco_mensal' => 229,
            'uso_indicado' => ''
        );
        $plans[] = array(
            'id' => 'br-2g',
            'categoria' => 'padrao',
            'nome' => 'BR 2GB',
            'ram' => '2 GB',
            'cpu' => '1 Core',
            'disco' => '50 GB',
            'visualizacoes' => '300.000',
            'preco_mensal' => 559,
            'uso_indicado' => ''
        );
        $plans[] = array(
            'id' => 'usa-4g',
            'categoria' => 'padrao',
            'nome' => 'USA 4GB',
            'ram' => '4 GB',
            'cpu' => '2 Cores',
            'disco' => '80 GB',
            'visualizacoes' => '500.000',
            'preco_mensal' => 439,
            'uso_indicado' => ''
        );
        $plans[] = array(
            'id' => 'br-4g',
            'categoria' => 'padrao',
            'nome' => 'BR 4GB',
            'ram' => '4 GB',
            'cpu' => '2 Cores',
            'disco' => '80 GB',
            'visualizacoes' => '500.000',
            'preco_mensal' => 1009,
            'uso_indicado' => ''
        );
        $plans[] = array(
            'id' => 'usa-8g',
            'categoria' => 'padrao',
            'nome' => 'USA 8GB',
            'ram' => '8 GB',
            'cpu' => '4 Cores',
            'disco' => '160 GB',
            'visualizacoes' => '1.000.000',
            'preco_mensal' => 799,
            'uso_indicado' => ''
        );
        $plans[] = array(
            'id' => 'br-8g',
            'categoria' => 'padrao',
            'nome' => 'BR 8GB',
            'ram' => '8 GB',
            'cpu' => '4 Cores',
            'disco' => '160 GB',
            'visualizacoes' => '1.000.000',
            'preco_mensal' => 1589,
            'uso_indicado' => ''
        );
        $plans[] = array(
            'id' => 'br-16g',
            'categoria' => 'padrao',
            'nome' => 'BR 16GB',
            'ram' => '16 GB',
            'cpu' => '6 Cores',
            'disco' => '320 GB',
            'visualizacoes' => '1.400.000',
            'preco_mensal' => 2969,
            'uso_indicado' => ''
        );
        $plans[] = array(
            'id' => 'usa-16g',
            'categoria' => 'padrao',
            'nome' => 'USA 16GB',
            'ram' => '16 GB',
            'cpu' => '6 Cores',
            'disco' => '320 GB',
            'visualizacoes' => '1.400.000',
            'preco_mensal' => 1629,
            'uso_indicado' => ''
        );
        $plans[] = array(
            'id' => 'usa-32g',
            'categoria' => 'padrao',
            'nome' => 'USA 32GB',
            'ram' => '32 GB',
            'cpu' => '8 Cores',
            'disco' => '640 GB',
            'visualizacoes' => '3.000.000',
            'preco_mensal' => 3129,
            'uso_indicado' => ''
        );
        $plans[] = array(
            'id' => 'usa-64g',
            'categoria' => 'padrao',
            'nome' => 'USA 64GB',
            'ram' => '64 GB',
            'cpu' => '12 Cores',
            'disco' => '1280 GB',
            'visualizacoes' => '6.000.000',
            'preco_mensal' => 6249,
            'uso_indicado' => ''
        );
        $plans[] = array(
            'id' => 'usa-128g',
            'categoria' => 'padrao',
            'nome' => 'USA 128GB',
            'ram' => '128 GB',
            'cpu' => '24 Cores',
            'disco' => '2560 GB',
            'visualizacoes' => '16.000.000',
            'preco_mensal' => 12629,
            'uso_indicado' => ''
        );
        $plans[] = array(
            'id' => 'usa-192g',
            'categoria' => 'padrao',
            'nome' => 'USA 192GB',
            'ram' => '192 GB',
            'cpu' => '32 Cores',
            'disco' => '3840 GB',
            'visualizacoes' => '24.000.000',
            'preco_mensal' => 18879,
            'uso_indicado' => ''
        );

        // 🧠 Clouds Focados em Memória RAM - Ordenados do mais barato para o mais caro
        $plans[] = array(
            'id' => 'usa-max-ram',
            'categoria' => 'ram',
            'nome' => 'USA Max RAM',
            'ram' => '24 GB',
            'cpu' => '1 Core',
            'disco' => '20 GB',
            'visualizacoes' => '',
            'preco_mensal' => 1299,
            'uso_indicado' => ''
        );
        $plans[] = array(
            'id' => 'usa-super-ram',
            'categoria' => 'ram',
            'nome' => 'USA Super RAM',
            'ram' => '48 GB',
            'cpu' => '2 Cores',
            'disco' => '40 GB',
            'visualizacoes' => '',
            'preco_mensal' => 2409,
            'uso_indicado' => ''
        );
        $plans[] = array(
            'id' => 'usa-hyper-ram',
            'categoria' => 'ram',
            'nome' => 'USA Hyper RAM',
            'ram' => '90 GB',
            'cpu' => '4 Cores',
            'disco' => '90 GB',
            'visualizacoes' => '',
            'preco_mensal' => 4739,
            'uso_indicado' => ''
        );
        $plans[] = array(
            'id' => 'usa-ultra-ram',
            'categoria' => 'ram',
            'nome' => 'USA Ultra RAM',
            'ram' => '150 GB',
            'cpu' => '8 Cores',
            'disco' => '200 GB',
            'visualizacoes' => '',
            'preco_mensal' => 8759,
            'uso_indicado' => ''
        );
        $plans[] = array(
            'id' => 'usa-elite-ram',
            'categoria' => 'ram',
            'nome' => 'USA Elite RAM',
            'ram' => '300 GB',
            'cpu' => '16 Cores',
            'disco' => '340 GB',
            'visualizacoes' => '',
            'preco_mensal' => 17579,
            'uso_indicado' => ''
        );

        // ⚙️ Clouds Focados em CPU - Ordenados do mais barato para o mais caro
        $plans[] = array(
            'id' => 'usa-max-cpu',
            'categoria' => 'cpu',
            'nome' => 'USA Max CPU',
            'ram' => '4 GB',
            'cpu' => '2 Cores',
            'disco' => '80 GB',
            'visualizacoes' => '',
            'preco_mensal' => 649,
            'uso_indicado' => ''
        );
        $plans[] = array(
            'id' => 'usa-super-cpu',
            'categoria' => 'cpu',
            'nome' => 'USA Super CPU',
            'ram' => '8 GB',
            'cpu' => '4 Cores',
            'disco' => '160 GB',
            'visualizacoes' => '',
            'preco_mensal' => 1199,
            'uso_indicado' => ''
        );
        $plans[] = array(
            'id' => 'usa-hyper-cpu',
            'categoria' => 'cpu',
            'nome' => 'USA Hyper CPU',
            'ram' => '16 GB',
            'cpu' => '8 Cores',
            'disco' => '320 GB',
            'visualizacoes' => '',
            'preco_mensal' => 2249,
            'uso_indicado' => ''
        );
        $plans[] = array(
            'id' => 'usa-ultra-cpu',
            'categoria' => 'cpu',
            'nome' => 'USA Ultra CPU',
            'ram' => '32 GB',
            'cpu' => '16 Cores',
            'disco' => '640 GB',
            'visualizacoes' => '',
            'preco_mensal' => 4499,
            'uso_indicado' => ''
        );
        $plans[] = array(
            'id' => 'usa-elite-cpu',
            'categoria' => 'cpu',
            'nome' => 'USA Elite CPU',
            'ram' => '64 GB',
            'cpu' => '32 Cores',
            'disco' => '1280 GB',
            'visualizacoes' => '',
            'preco_mensal' => 8879,
            'uso_indicado' => ''
        );
        $plans[] = array(
            'id' => 'usa-prestige-cpu',
            'categoria' => 'cpu',
            'nome' => 'USA Prestige CPU',
            'ram' => '96 GB',
            'cpu' => '48 Cores',
            'disco' => '1920 GB',
            'visualizacoes' => '',
            'preco_mensal' => 13149,
            'uso_indicado' => ''
        );

        // 📧 Clouds para E-mails - Ordenados do mais barato para o mais caro
        $plans[] = array(
            'id' => 'usa-email-20g',
            'categoria' => 'email',
            'nome' => 'USA Email 20GB',
            'ram' => '1 GB',
            'cpu' => '1 Core',
            'disco' => '20 GB',
            'visualizacoes' => '',
            'preco_mensal' => 129,
            'uso_indicado' => 'Pequenas Empresas'
        );
        $plans[] = array(
            'id' => 'usa-email-60g',
            'categoria' => 'email',
            'nome' => 'USA Email 60GB',
            'ram' => '1 GB',
            'cpu' => '1 Core',
            'disco' => '60 GB',
            'visualizacoes' => '',
            'preco_mensal' => 149,
            'uso_indicado' => 'Pequenas Empresas'
        );
        $plans[] = array(
            'id' => 'usa-email-100g',
            'categoria' => 'email',
            'nome' => 'USA Email 100GB',
            'ram' => '1 GB',
            'cpu' => '1 Core',
            'disco' => '100 GB',
            'visualizacoes' => '',
            'preco_mensal' => 209,
            'uso_indicado' => 'Pequenas Empresas'
        );
        $plans[] = array(
            'id' => 'usa-email-200g',
            'categoria' => 'email',
            'nome' => 'USA Email 200GB',
            'ram' => '1 GB',
            'cpu' => '1 Core',
            'disco' => '200 GB',
            'visualizacoes' => '',
            'preco_mensal' => 289,
            'uso_indicado' => 'Pequenas Empresas'
        );
        $plans[] = array(
            'id' => 'usa-email-300g',
            'categoria' => 'email',
            'nome' => 'USA Email 300GB',
            'ram' => '1 GB',
            'cpu' => '1 Core',
            'disco' => '300 GB',
            'visualizacoes' => '',
            'preco_mensal' => 359,
            'uso_indicado' => 'Pequenas Empresas'
        );
        $plans[] = array(
            'id' => 'usa-email-400g',
            'categoria' => 'email',
            'nome' => 'USA Email 400GB',
            'ram' => '2 GB',
            'cpu' => '1 Core',
            'disco' => '400 GB',
            'visualizacoes' => '',
            'preco_mensal' => 489,
            'uso_indicado' => 'Pequenas Empresas'
        );
        $plans[] = array(
            'id' => 'usa-email-500g',
            'categoria' => 'email',
            'nome' => 'USA Email 500GB',
            'ram' => '2 GB',
            'cpu' => '1 Core',
            'disco' => '500 GB',
            'visualizacoes' => '',
            'preco_mensal' => 549,
            'uso_indicado' => 'Pequenas Empresas'
        );
        $plans[] = array(
            'id' => 'usa-email-600g',
            'categoria' => 'email',
            'nome' => 'USA Email 600GB',
            'ram' => '2 GB',
            'cpu' => '1 Core',
            'disco' => '600 GB',
            'visualizacoes' => '',
            'preco_mensal' => 869,
            'uso_indicado' => 'Pequenas Empresas'
        );
        $plans[] = array(
            'id' => 'usa-email-700g',
            'categoria' => 'email',
            'nome' => 'USA Email 700GB',
            'ram' => '4 GB',
            'cpu' => '2 Cores',
            'disco' => '700 GB',
            'visualizacoes' => '',
            'preco_mensal' => 1419,
            'uso_indicado' => 'Pequenas Empresas'
        );
        $plans[] = array(
            'id' => 'usa-email-800g',
            'categoria' => 'email',
            'nome' => 'USA Email 800GB',
            'ram' => '4 GB',
            'cpu' => '2 Cores',
            'disco' => '800 GB',
            'visualizacoes' => '',
            'preco_mensal' => 1519,
            'uso_indicado' => 'Pequenas Empresas'
        );
        $plans[] = array(
            'id' => 'usa-email-900g',
            'categoria' => 'email',
            'nome' => 'USA Email 900GB',
            'ram' => '4 GB',
            'cpu' => '2 Cores',
            'disco' => '900 GB',
            'visualizacoes' => '',
            'preco_mensal' => 1629,
            'uso_indicado' => 'Pequenas Empresas'
        );
        $plans[] = array(
            'id' => 'usa-email-1000g',
            'categoria' => 'email',
            'nome' => 'USA Email 1000GB',
            'ram' => '4 GB',
            'cpu' => '2 Cores',
            'disco' => '1000 GB',
            'visualizacoes' => '',
            'preco_mensal' => 2049,
            'uso_indicado' => 'Pequenas Empresas'
        );

        return $plans;
    }

    /**
     * Obter FAQs padrão
     */
    public function get_default_faqs() {
        return array(
            array(
                'pergunta' => 'Existe alguma taxa de instalação?',
                'resposta' => 'Não! Nossa equipe realiza toda a configuração inicial sem custos adicionais.'
            ),
            array(
                'pergunta' => 'Posso mudar meu plano futuramente?',
                'resposta' => 'Sim! Você pode fazer upgrade ou downgrade do seu plano a qualquer momento, conforme a necessidade do seu projeto.'
            ),
            array(
                'pergunta' => 'Eu terei que migrar meu website?',
                'resposta' => 'Não se preocupe! Oferecemos migração gratuita de sites. Nossa equipe cuida de tudo para você.'
            ),
            array(
                'pergunta' => 'Quanto tempo duram os contratos?',
                'resposta' => 'Temos planos mensais com flexibilidade total e planos anuais com 10% de desconto. Sem fidelidade nos planos mensais.'
            ),
            array(
                'pergunta' => 'E se eu precisar de mais e-mail e CDN?',
                'resposta' => 'Nossos planos já incluem CDN automática e contas de e-mail configuráveis. Entre em contato para necessidades específicas.'
            ),
            array(
                'pergunta' => 'Quais as formas de pagamento?',
                'resposta' => 'Aceitamos cartão de crédito, boleto bancário e PIX. Planos anuais podem ser parcelados no cartão.'
            )
        );
    }

    /**
     * Obter funcionalidades/benefícios padrão
     */
    public function get_default_features() {
        return array(
            'HTTPS Automático (Let\'s Encrypt)',
            'Backup Automático',
            'CDN + Cache Automática',
            'Pagespeed Optimizer',
            'Firewall e Proteção Anti-blocklist',
            'Monitoramento de Infraestrutura 24/7',
            'Atualizações de Segurança Automáticas',
            'Acesso Root (onde aplicável)',
            'Painel de Controle Automatizado e Amigável',
            'Migração de Sites Grátis',
            'Definição de Limite de Memória',
            'Serviço de SMTP Pré-configurado',
            'Headers de Segurança Automáticos',
            'Banco de Dados Incluso',
            'DNS Gerenciado'
        );
    }

    /**
     * Obter planos por categoria (ordenados por preço crescente)
     */
    public function get_plans_by_category($category_slug) {
        $plans = get_option('futturu_hospedagemcloud_plans');
        
        // Validação robusta dos dados
        if (!is_array($plans) || empty($plans)) {
            $plans = $this->get_default_plans();
        }

        $filtered = array();
        foreach ($plans as $plan) {
            // Verificação segura de array e chave
            if (is_array($plan) && isset($plan['categoria']) && $plan['categoria'] === $category_slug) {
                $filtered[] = $plan;
            }
        }

        // Ordenar por preço (crescente)
        usort($filtered, function($a, $b) {
            $price_a = isset($a['preco_mensal']) ? floatval($a['preco_mensal']) : 0;
            $price_b = isset($b['preco_mensal']) ? floatval($b['preco_mensal']) : 0;
            return $price_a - $price_b;
        });

        return $filtered;
    }

    /**
     * Obter configurações
     */
    public function get_settings() {
        $defaults = array(
            'discount_rate' => 10,
            'default_view' => 'annual',
            'contact_email' => 'suporte@futturu.com.br'
        );
        $saved = get_option('futturu_hospedagemcloud_settings', array());
        
        if (!is_array($saved)) {
            $saved = array();
        }
        
        return wp_parse_args($saved, $defaults);
    }

    /**
     * Obter FAQs salvos
     */
    public function get_faqs() {
        $faqs = get_option('futturu_hospedagemcloud_faqs');
        
        if (!is_array($faqs) || empty($faqs)) {
            return $this->get_default_faqs();
        }
        
        return $faqs;
    }

    /**
     * Obter features salvas
     */
    public function get_features() {
        $features = get_option('futturu_hospedagemcloud_features');
        
        if (!is_array($features) || empty($features)) {
            return $this->get_default_features();
        }
        
        return $features;
    }
}
