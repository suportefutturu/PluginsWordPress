<?php
/**
 * Futturu Cloud Data Class
 * Handles default data for plans, FAQs, and features
 */

if (!defined('ABSPATH')) {
    exit;
}

class Futturu_Cloud_Data {
    
    /**
     * Get default plans data
     */
    public static function get_default_plans() {
        $plans = array(
            // ☁️ Clouds Padrão (Uso Geral)
            array(
                'categoria' => '☁️ Clouds Padrão (Uso Geral)',
                'modelo' => 'Default USA192G',
                'ram' => '192 GB',
                'cpu' => '32 Cores',
                'disco' => '3840 GB',
                'visualizacoes' => '24.000.000',
                'preco_mensal' => 18879,
                'uso_indicado' => '',
                'is_individual' => false
            ),
            array(
                'categoria' => '☁️ Clouds Padrão (Uso Geral)',
                'modelo' => 'Default USA128G',
                'ram' => '128 GB',
                'cpu' => '24 Cores',
                'disco' => '2560 GB',
                'visualizacoes' => '16.000.000',
                'preco_mensal' => 12629,
                'uso_indicado' => '',
                'is_individual' => false
            ),
            array(
                'categoria' => '☁️ Clouds Padrão (Uso Geral)',
                'modelo' => 'Default USA64G',
                'ram' => '64 GB',
                'cpu' => '12 Cores',
                'disco' => '1280 GB',
                'visualizacoes' => '6.000.000',
                'preco_mensal' => 6249,
                'uso_indicado' => '',
                'is_individual' => false
            ),
            array(
                'categoria' => '☁️ Clouds Padrão (Uso Geral)',
                'modelo' => 'Default USA32G',
                'ram' => '32 GB',
                'cpu' => '8 Cores',
                'disco' => '640 GB',
                'visualizacoes' => '3.000.000',
                'preco_mensal' => 3129,
                'uso_indicado' => '',
                'is_individual' => false
            ),
            array(
                'categoria' => '☁️ Clouds Padrão (Uso Geral)',
                'modelo' => 'BR16G',
                'ram' => '16 GB',
                'cpu' => '6 Cores',
                'disco' => '320 GB',
                'visualizacoes' => '1.400.000',
                'preco_mensal' => 2969,
                'uso_indicado' => '',
                'is_individual' => false
            ),
            array(
                'categoria' => '☁️ Clouds Padrão (Uso Geral)',
                'modelo' => 'Default USA16G',
                'ram' => '16 GB',
                'cpu' => '6 Cores',
                'disco' => '320 GB',
                'visualizacoes' => '1.400.000',
                'preco_mensal' => 1629,
                'uso_indicado' => '',
                'is_individual' => false
            ),
            array(
                'categoria' => '☁️ Clouds Padrão (Uso Geral)',
                'modelo' => 'BR8G',
                'ram' => '8 GB',
                'cpu' => '4 Cores',
                'disco' => '160 GB',
                'visualizacoes' => '1.000.000',
                'preco_mensal' => 1589,
                'uso_indicado' => '',
                'is_individual' => false
            ),
            array(
                'categoria' => '☁️ Clouds Padrão (Uso Geral)',
                'modelo' => 'BR4G',
                'ram' => '4 GB',
                'cpu' => '2 Cores',
                'disco' => '80 GB',
                'visualizacoes' => '500.000',
                'preco_mensal' => 1009,
                'uso_indicado' => '',
                'is_individual' => false
            ),
            array(
                'categoria' => '☁️ Clouds Padrão (Uso Geral)',
                'modelo' => 'Default USA8G',
                'ram' => '8 GB',
                'cpu' => '4 Cores',
                'disco' => '160 GB',
                'visualizacoes' => '1.000.000',
                'preco_mensal' => 799,
                'uso_indicado' => '',
                'is_individual' => false
            ),
            array(
                'categoria' => '☁️ Clouds Padrão (Uso Geral)',
                'modelo' => 'BR2G',
                'ram' => '2 GB',
                'cpu' => '1 Core',
                'disco' => '50 GB',
                'visualizacoes' => '300.000',
                'preco_mensal' => 559,
                'uso_indicado' => '',
                'is_individual' => false
            ),
            array(
                'categoria' => '☁️ Clouds Padrão (Uso Geral)',
                'modelo' => 'Default USA4G',
                'ram' => '4 GB',
                'cpu' => '2 Cores',
                'disco' => '80 GB',
                'visualizacoes' => '500.000',
                'preco_mensal' => 439,
                'uso_indicado' => '',
                'is_individual' => false
            ),
            array(
                'categoria' => '☁️ Clouds Padrão (Uso Geral)',
                'modelo' => 'BR1G',
                'ram' => '1 GB',
                'cpu' => '1 Core',
                'disco' => '25 GB',
                'visualizacoes' => '100.000',
                'preco_mensal' => 239,
                'uso_indicado' => '',
                'is_individual' => false
            ),
            array(
                'categoria' => '☁️ Clouds Padrão (Uso Geral)',
                'modelo' => 'Default USA2G',
                'ram' => '2 GB',
                'cpu' => '1 Core',
                'disco' => '50 GB',
                'visualizacoes' => '300.000',
                'preco_mensal' => 229,
                'uso_indicado' => '',
                'is_individual' => false
            ),
            array(
                'categoria' => '☁️ Clouds Padrão (Uso Geral)',
                'modelo' => 'BR1G Individual',
                'ram' => '1 GB',
                'cpu' => '1 Core',
                'disco' => '25 GB',
                'visualizacoes' => '100.000 (1 site)',
                'preco_mensal' => 219,
                'uso_indicado' => '',
                'is_individual' => true
            ),
            array(
                'categoria' => '☁️ Clouds Padrão (Uso Geral)',
                'modelo' => 'Default USA1G',
                'ram' => '1 GB',
                'cpu' => '1 Core',
                'disco' => '25 GB',
                'visualizacoes' => '100.000',
                'preco_mensal' => 119,
                'uso_indicado' => '',
                'is_individual' => false
            ),
            array(
                'categoria' => '☁️ Clouds Padrão (Uso Geral)',
                'modelo' => 'Default USA1G Individual',
                'ram' => '1 GB',
                'cpu' => '1 Core',
                'disco' => '25 GB',
                'visualizacoes' => '100.000 (1 site)',
                'preco_mensal' => 89,
                'uso_indicado' => '',
                'is_individual' => true
            ),
            
            // 🧠 Clouds Focados em Memória RAM
            array(
                'categoria' => '🧠 Clouds Focados em Memória RAM',
                'modelo' => 'Default USAElite RAM',
                'ram' => '300 GB',
                'cpu' => '16 Cores',
                'disco' => '340 GB',
                'visualizacoes' => '',
                'preco_mensal' => 17579,
                'uso_indicado' => '',
                'is_individual' => false
            ),
            array(
                'categoria' => '🧠 Clouds Focados em Memória RAM',
                'modelo' => 'Default USAUltra RAM',
                'ram' => '150 GB',
                'cpu' => '8 Cores',
                'disco' => '200 GB',
                'visualizacoes' => '',
                'preco_mensal' => 8759,
                'uso_indicado' => '',
                'is_individual' => false
            ),
            array(
                'categoria' => '🧠 Clouds Focados em Memória RAM',
                'modelo' => 'Default USAHyper RAM',
                'ram' => '90 GB',
                'cpu' => '4 Cores',
                'disco' => '90 GB',
                'visualizacoes' => '',
                'preco_mensal' => 4739,
                'uso_indicado' => '',
                'is_individual' => false
            ),
            array(
                'categoria' => '🧠 Clouds Focados em Memória RAM',
                'modelo' => 'Default USASuper RAM',
                'ram' => '48 GB',
                'cpu' => '2 Cores',
                'disco' => '40 GB',
                'visualizacoes' => '',
                'preco_mensal' => 2409,
                'uso_indicado' => '',
                'is_individual' => false
            ),
            array(
                'categoria' => '🧠 Clouds Focados em Memória RAM',
                'modelo' => 'Default USAMax RAM',
                'ram' => '24 GB',
                'cpu' => '1 Core',
                'disco' => '20 GB',
                'visualizacoes' => '',
                'preco_mensal' => 1299,
                'uso_indicado' => '',
                'is_individual' => false
            ),
            
            // ⚙️ Clouds Focados em Processamento (CPU)
            array(
                'categoria' => '⚙️ Clouds Focados em Processamento (CPU)',
                'modelo' => 'Default USAPrestige CPU',
                'ram' => '96 GB',
                'cpu' => '48 Cores',
                'disco' => '1920 GB',
                'visualizacoes' => '',
                'preco_mensal' => 13149,
                'uso_indicado' => '',
                'is_individual' => false
            ),
            array(
                'categoria' => '⚙️ Clouds Focados em Processamento (CPU)',
                'modelo' => 'Default USAElite CPU',
                'ram' => '64 GB',
                'cpu' => '32 Cores',
                'disco' => '1280 GB',
                'visualizacoes' => '',
                'preco_mensal' => 8879,
                'uso_indicado' => '',
                'is_individual' => false
            ),
            array(
                'categoria' => '⚙️ Clouds Focados em Processamento (CPU)',
                'modelo' => 'Default USAUltra CPU',
                'ram' => '32 GB',
                'cpu' => '16 Cores',
                'disco' => '640 GB',
                'visualizacoes' => '',
                'preco_mensal' => 4499,
                'uso_indicado' => '',
                'is_individual' => false
            ),
            array(
                'categoria' => '⚙️ Clouds Focados em Processamento (CPU)',
                'modelo' => 'Default USAHyper CPU',
                'ram' => '16 GB',
                'cpu' => '8 Cores',
                'disco' => '320 GB',
                'visualizacoes' => '',
                'preco_mensal' => 2249,
                'uso_indicado' => '',
                'is_individual' => false
            ),
            array(
                'categoria' => '⚙️ Clouds Focados em Processamento (CPU)',
                'modelo' => 'Default USASuper CPU',
                'ram' => '8 GB',
                'cpu' => '4 Cores',
                'disco' => '160 GB',
                'visualizacoes' => '',
                'preco_mensal' => 1199,
                'uso_indicado' => '',
                'is_individual' => false
            ),
            array(
                'categoria' => '⚙️ Clouds Focados em Processamento (CPU)',
                'modelo' => 'Default USAMax CPU',
                'ram' => '4 GB',
                'cpu' => '2 Cores',
                'disco' => '80 GB',
                'visualizacoes' => '',
                'preco_mensal' => 649,
                'uso_indicado' => '',
                'is_individual' => false
            ),
            
            // 📧 Clouds para E-mails
            array(
                'categoria' => '📧 Clouds para E-mails',
                'modelo' => 'Default USAEmail 1000G',
                'ram' => '4 GB',
                'cpu' => '2 Cores',
                'disco' => '1000 GB',
                'visualizacoes' => '',
                'preco_mensal' => 2049,
                'uso_indicado' => 'Pequenas Empresas',
                'is_individual' => false
            ),
            array(
                'categoria' => '📧 Clouds para E-mails',
                'modelo' => 'Default USAEmail 900G',
                'ram' => '4 GB',
                'cpu' => '2 Cores',
                'disco' => '900 GB',
                'visualizacoes' => '',
                'preco_mensal' => 1629,
                'uso_indicado' => 'Pequenas Empresas',
                'is_individual' => false
            ),
            array(
                'categoria' => '📧 Clouds para E-mails',
                'modelo' => 'Default USAEmail 800G',
                'ram' => '4 GB',
                'cpu' => '2 Cores',
                'disco' => '800 GB',
                'visualizacoes' => '',
                'preco_mensal' => 1519,
                'uso_indicado' => 'Pequenas Empresas',
                'is_individual' => false
            ),
            array(
                'categoria' => '📧 Clouds para E-mails',
                'modelo' => 'Default USAEmail 700G',
                'ram' => '4 GB',
                'cpu' => '2 Cores',
                'disco' => '700 GB',
                'visualizacoes' => '',
                'preco_mensal' => 1419,
                'uso_indicado' => 'Pequenas Empresas',
                'is_individual' => false
            ),
            array(
                'categoria' => '📧 Clouds para E-mails',
                'modelo' => 'Default USAEmail 600G',
                'ram' => '2 GB',
                'cpu' => '1 Core',
                'disco' => '600 GB',
                'visualizacoes' => '',
                'preco_mensal' => 869,
                'uso_indicado' => 'Pequenas Empresas',
                'is_individual' => false
            ),
            array(
                'categoria' => '📧 Clouds para E-mails',
                'modelo' => 'Default USAEmail 500G',
                'ram' => '2 GB',
                'cpu' => '1 Core',
                'disco' => '500 GB',
                'visualizacoes' => '',
                'preco_mensal' => 549,
                'uso_indicado' => 'Pequenas Empresas',
                'is_individual' => false
            ),
            array(
                'categoria' => '📧 Clouds para E-mails',
                'modelo' => 'Default USAEmail 400G',
                'ram' => '2 GB',
                'cpu' => '1 Core',
                'disco' => '400 GB',
                'visualizacoes' => '',
                'preco_mensal' => 489,
                'uso_indicado' => 'Pequenas Empresas',
                'is_individual' => false
            ),
            array(
                'categoria' => '📧 Clouds para E-mails',
                'modelo' => 'Default USAEmail 300G',
                'ram' => '1 GB',
                'cpu' => '1 Core',
                'disco' => '300 GB',
                'visualizacoes' => '',
                'preco_mensal' => 359,
                'uso_indicado' => 'Pequenas Empresas',
                'is_individual' => false
            ),
            array(
                'categoria' => '📧 Clouds para E-mails',
                'modelo' => 'Default USAEmail 200G',
                'ram' => '1 GB',
                'cpu' => '1 Core',
                'disco' => '200 GB',
                'visualizacoes' => '',
                'preco_mensal' => 289,
                'uso_indicado' => 'Pequenas Empresas',
                'is_individual' => false
            ),
            array(
                'categoria' => '📧 Clouds para E-mails',
                'modelo' => 'Default USAEmail 100G',
                'ram' => '1 GB',
                'cpu' => '1 Core',
                'disco' => '100 GB',
                'visualizacoes' => '',
                'preco_mensal' => 209,
                'uso_indicado' => 'Pequenas Empresas',
                'is_individual' => false
            ),
            array(
                'categoria' => '📧 Clouds para E-mails',
                'modelo' => 'Default USAEmail 60G',
                'ram' => '1 GB',
                'cpu' => '1 Core',
                'disco' => '60 GB',
                'visualizacoes' => '',
                'preco_mensal' => 149,
                'uso_indicado' => 'Pequenas Empresas',
                'is_individual' => false
            ),
            array(
                'categoria' => '📧 Clouds para E-mails',
                'modelo' => 'Default USAEmail 20G',
                'ram' => '1 GB',
                'cpu' => '1 Core',
                'disco' => '20 GB',
                'visualizacoes' => '',
                'preco_mensal' => 129,
                'uso_indicado' => 'Pequenas Empresas',
                'is_individual' => false
            ),
        );
        
        return $plans;
    }
    
    /**
     * Get default FAQs
     */
    public static function get_default_faqs() {
        $faqs = array(
            array(
                'pergunta' => 'Existe alguma taxa de instalação?',
                'resposta' => 'Não! Não cobramos taxa de instalação ou configuração. Seu ambiente será provisionado automaticamente após a contratação.'
            ),
            array(
                'pergunta' => 'Posso mudar meu plano futuramente?',
                'resposta' => 'Sim! Você pode fazer upgrade ou downgrade do seu plano a qualquer momento, conforme a necessidade do seu projeto.'
            ),
            array(
                'pergunta' => 'Eu terei que migrar meu website?',
                'resposta' => 'Oferecemos migração gratuita de sites. Nossa equipe cuidará de toda a migração sem downtime para o seu site.'
            ),
            array(
                'pergunta' => 'Quanto tempo duram os contratos?',
                'resposta' => 'Temos planos mensais e anuais. O plano anual oferece 10% de desconto em relação ao mensal.'
            ),
            array(
                'pergunta' => 'E se eu precisar de mais e-mail e CDN?',
                'resposta' => 'Nossos planos já incluem CDN automática e configuração de e-mail. Caso precise de recursos adicionais, entre em contato para personalizarmos sua solução.'
            ),
            array(
                'pergunta' => 'Quais as formas de pagamento?',
                'resposta' => 'Aceitamos cartão de crédito, boleto bancário e PIX. Entre em contato para mais detalhes sobre condições especiais.'
            ),
        );
        
        return $faqs;
    }
    
    /**
     * Get default features/benefits
     */
    public static function get_default_features() {
        $features = array(
            'HTTPS Automático (Let\'s Encrypt)',
            'Backup Automático',
            'CDN + Cache Automática',
            'Pagespeed Optimizer',
            'Firewall e Proteção Anti-blocklist',
            'Monitoramento de Infraestrutura',
            'Atualizações de Segurança Automáticas',
            'Possibilidade de Acesso Root (onde aplicável)',
            'Painel de Controle Automatizado e Amigável',
            'Migração de Sites Grátis',
            'Definição de Limite de Memória',
            'Serviço de SMTP Pré-configurado',
            'Headers de Segurança Automáticos',
            'Banco de Dados Incluso',
            'DNS Gerenciado',
            'Hospedagem Gerenciada',
            'Certificado SSL Grátis',
            'Suporte Técnico Especializado',
        );
        
        return $features;
    }
    
    /**
     * Get unique categories from plans
     */
    public static function get_categories($plans = null) {
        if ($plans === null) {
            $plans = get_option('futturu_cloud_plans', self::get_default_plans());
        }
        
        $categories = array();
        foreach ($plans as $plan) {
            if (!in_array($plan['categoria'], $categories)) {
                $categories[] = $plan['categoria'];
            }
        }
        
        return $categories;
    }
    
    /**
     * Get plans by category
     */
    public static function get_plans_by_category($category, $plans = null) {
        if ($plans === null) {
            $plans = get_option('futturu_cloud_plans', self::get_default_plans());
        }
        
        $filtered_plans = array();
        foreach ($plans as $plan) {
            if ($plan['categoria'] === $category) {
                $filtered_plans[] = $plan;
            }
        }
        
        return $filtered_plans;
    }
}
