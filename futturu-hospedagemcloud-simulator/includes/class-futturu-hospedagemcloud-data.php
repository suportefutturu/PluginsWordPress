<?php
/**
 * Classe de Dados do Simulador Futturu Cloud
 * Gerencia todos os dados dos planos, categorias, features e FAQs
 */

if (!defined('ABSPATH')) {
    exit;
}

class Futturu_HospedagemCloud_Data {

    /**
     * Construtor
     */
    public function __construct() {
        // Inicializa dados se necessário
    }

    /**
     * Retorna as categorias de planos
     */
    public static function get_categories() {
        return array(
            'padrao' => '☁️ Clouds Padrão (Uso Geral)',
            'ram' => '🧠 Clouds Focados em RAM',
            'cpu' => '⚙️ Clouds Focados em CPU',
            'email' => '📧 Clouds para E-mails'
        );
    }

    /**
     * Retorna os planos padrão hardcoded
     */
    public static function get_default_plans() {
        return array(
            // ☁️ Clouds Padrão (Uso Geral)
            array(
                'categoria' => 'padrao',
                'modelo' => 'Default USA1G Individual',
                'nome_exibido' => 'USA 1GB Individual',
                'ram' => '1 GB',
                'cpu' => '1 Core',
                'disco' => '25 GB',
                'visualizacoes' => '100.000 (1 site)',
                'preco_mensal' => 89,
                'uso_indicado' => ''
            ),
            array(
                'categoria' => 'padrao',
                'modelo' => 'Default USA1G',
                'nome_exibido' => 'USA 1GB',
                'ram' => '1 GB',
                'cpu' => '1 Core',
                'disco' => '25 GB',
                'visualizacoes' => '100.000',
                'preco_mensal' => 119,
                'uso_indicado' => ''
            ),
            array(
                'categoria' => 'padrao',
                'modelo' => 'BR1G Individual',
                'nome_exibido' => 'BR 1GB Individual',
                'ram' => '1 GB',
                'cpu' => '1 Core',
                'disco' => '25 GB',
                'visualizacoes' => '100.000 (1 site)',
                'preco_mensal' => 219,
                'uso_indicado' => ''
            ),
            array(
                'categoria' => 'padrao',
                'modelo' => 'Default USA2G',
                'nome_exibido' => 'USA 2GB',
                'ram' => '2 GB',
                'cpu' => '1 Core',
                'disco' => '50 GB',
                'visualizacoes' => '300.000',
                'preco_mensal' => 229,
                'uso_indicado' => ''
            ),
            array(
                'categoria' => 'padrao',
                'modelo' => 'BR1G',
                'nome_exibido' => 'BR 1GB',
                'ram' => '1 GB',
                'cpu' => '1 Core',
                'disco' => '25 GB',
                'visualizacoes' => '100.000',
                'preco_mensal' => 239,
                'uso_indicado' => ''
            ),
            array(
                'categoria' => 'padrao',
                'modelo' => 'Default USA4G',
                'nome_exibido' => 'USA 4GB',
                'ram' => '4 GB',
                'cpu' => '2 Cores',
                'disco' => '80 GB',
                'visualizacoes' => '500.000',
                'preco_mensal' => 439,
                'uso_indicado' => ''
            ),
            array(
                'categoria' => 'padrao',
                'modelo' => 'BR2G',
                'nome_exibido' => 'BR 2GB',
                'ram' => '2 GB',
                'cpu' => '1 Core',
                'disco' => '50 GB',
                'visualizacoes' => '300.000',
                'preco_mensal' => 559,
                'uso_indicado' => ''
            ),
            array(
                'categoria' => 'padrao',
                'modelo' => 'Default USA8G',
                'nome_exibido' => 'USA 8GB',
                'ram' => '8 GB',
                'cpu' => '4 Cores',
                'disco' => '160 GB',
                'visualizacoes' => '1.000.000',
                'preco_mensal' => 799,
                'uso_indicado' => ''
            ),
            array(
                'categoria' => 'padrao',
                'modelo' => 'BR4G',
                'nome_exibido' => 'BR 4GB',
                'ram' => '4 GB',
                'cpu' => '2 Cores',
                'disco' => '80 GB',
                'visualizacoes' => '500.000',
                'preco_mensal' => 1009,
                'uso_indicado' => ''
            ),
            array(
                'categoria' => 'padrao',
                'modelo' => 'BR8G',
                'nome_exibido' => 'BR 8GB',
                'ram' => '8 GB',
                'cpu' => '4 Cores',
                'disco' => '160 GB',
                'visualizacoes' => '1.000.000',
                'preco_mensal' => 1589,
                'uso_indicado' => ''
            ),
            array(
                'categoria' => 'padrao',
                'modelo' => 'Default USA16G',
                'nome_exibido' => 'USA 16GB',
                'ram' => '16 GB',
                'cpu' => '6 Cores',
                'disco' => '320 GB',
                'visualizacoes' => '1.400.000',
                'preco_mensal' => 1629,
                'uso_indicado' => ''
            ),
            array(
                'categoria' => 'padrao',
                'modelo' => 'BR16G',
                'nome_exibido' => 'BR 16GB',
                'ram' => '16 GB',
                'cpu' => '6 Cores',
                'disco' => '320 GB',
                'visualizacoes' => '1.400.000',
                'preco_mensal' => 2969,
                'uso_indicado' => ''
            ),
            array(
                'categoria' => 'padrao',
                'modelo' => 'Default USA32G',
                'nome_exibido' => 'USA 32GB',
                'ram' => '32 GB',
                'cpu' => '8 Cores',
                'disco' => '640 GB',
                'visualizacoes' => '3.000.000',
                'preco_mensal' => 3129,
                'uso_indicado' => ''
            ),
            array(
                'categoria' => 'padrao',
                'modelo' => 'Default USA64G',
                'nome_exibido' => 'USA 64GB',
                'ram' => '64 GB',
                'cpu' => '12 Cores',
                'disco' => '1280 GB',
                'visualizacoes' => '6.000.000',
                'preco_mensal' => 6249,
                'uso_indicado' => ''
            ),
            array(
                'categoria' => 'padrao',
                'modelo' => 'Default USA128G',
                'nome_exibido' => 'USA 128GB',
                'ram' => '128 GB',
                'cpu' => '24 Cores',
                'disco' => '2560 GB',
                'visualizacoes' => '16.000.000',
                'preco_mensal' => 12629,
                'uso_indicado' => ''
            ),
            array(
                'categoria' => 'padrao',
                'modelo' => 'Default USA192G',
                'nome_exibido' => 'USA 192GB',
                'ram' => '192 GB',
                'cpu' => '32 Cores',
                'disco' => '3840 GB',
                'visualizacoes' => '24.000.000',
                'preco_mensal' => 18879,
                'uso_indicado' => ''
            ),
            
            // 🧠 Clouds Focados em Memória RAM
            array(
                'categoria' => 'ram',
                'modelo' => 'Default USAMax RAM',
                'nome_exibido' => 'USA Max RAM',
                'ram' => '24 GB',
                'cpu' => '1 Core',
                'disco' => '20 GB',
                'visualizacoes' => '',
                'preco_mensal' => 1299,
                'uso_indicado' => ''
            ),
            array(
                'categoria' => 'ram',
                'modelo' => 'Default USASuper RAM',
                'nome_exibido' => 'USA Super RAM',
                'ram' => '48 GB',
                'cpu' => '2 Cores',
                'disco' => '40 GB',
                'visualizacoes' => '',
                'preco_mensal' => 2409,
                'uso_indicado' => ''
            ),
            array(
                'categoria' => 'ram',
                'modelo' => 'Default USAHyper RAM',
                'nome_exibido' => 'USA Hyper RAM',
                'ram' => '90 GB',
                'cpu' => '4 Cores',
                'disco' => '90 GB',
                'visualizacoes' => '',
                'preco_mensal' => 4739,
                'uso_indicado' => ''
            ),
            array(
                'categoria' => 'ram',
                'modelo' => 'Default USAUltra RAM',
                'nome_exibido' => 'USA Ultra RAM',
                'ram' => '150 GB',
                'cpu' => '8 Cores',
                'disco' => '200 GB',
                'visualizacoes' => '',
                'preco_mensal' => 8759,
                'uso_indicado' => ''
            ),
            array(
                'categoria' => 'ram',
                'modelo' => 'Default USAElite RAM',
                'nome_exibido' => 'USA Elite RAM',
                'ram' => '300 GB',
                'cpu' => '16 Cores',
                'disco' => '340 GB',
                'visualizacoes' => '',
                'preco_mensal' => 17579,
                'uso_indicado' => ''
            ),
            
            // ⚙️ Clouds Focados em Processamento (CPU)
            array(
                'categoria' => 'cpu',
                'modelo' => 'Default USAMax CPU',
                'nome_exibido' => 'USA Max CPU',
                'ram' => '4 GB',
                'cpu' => '2 Cores',
                'disco' => '80 GB',
                'visualizacoes' => '',
                'preco_mensal' => 649,
                'uso_indicado' => ''
            ),
            array(
                'categoria' => 'cpu',
                'modelo' => 'Default USASuper CPU',
                'nome_exibido' => 'USA Super CPU',
                'ram' => '8 GB',
                'cpu' => '4 Cores',
                'disco' => '160 GB',
                'visualizacoes' => '',
                'preco_mensal' => 1199,
                'uso_indicado' => ''
            ),
            array(
                'categoria' => 'cpu',
                'modelo' => 'Default USAHyper CPU',
                'nome_exibido' => 'USA Hyper CPU',
                'ram' => '16 GB',
                'cpu' => '8 Cores',
                'disco' => '320 GB',
                'visualizacoes' => '',
                'preco_mensal' => 2249,
                'uso_indicado' => ''
            ),
            array(
                'categoria' => 'cpu',
                'modelo' => 'Default USAUltra CPU',
                'nome_exibido' => 'USA Ultra CPU',
                'ram' => '32 GB',
                'cpu' => '16 Cores',
                'disco' => '640 GB',
                'visualizacoes' => '',
                'preco_mensal' => 4499,
                'uso_indicado' => ''
            ),
            array(
                'categoria' => 'cpu',
                'modelo' => 'Default USAElite CPU',
                'nome_exibido' => 'USA Elite CPU',
                'ram' => '64 GB',
                'cpu' => '32 Cores',
                'disco' => '1280 GB',
                'visualizacoes' => '',
                'preco_mensal' => 8879,
                'uso_indicado' => ''
            ),
            array(
                'categoria' => 'cpu',
                'modelo' => 'Default USAPrestige CPU',
                'nome_exibido' => 'USA Prestige CPU',
                'ram' => '96 GB',
                'cpu' => '48 Cores',
                'disco' => '1920 GB',
                'visualizacoes' => '',
                'preco_mensal' => 13149,
                'uso_indicado' => ''
            ),
            
            // 📧 Clouds para E-mails
            array(
                'categoria' => 'email',
                'modelo' => 'Default USAEmail 20G',
                'nome_exibido' => 'USA Email 20GB',
                'ram' => '1 GB',
                'cpu' => '1 Core',
                'disco' => '20 GB',
                'visualizacoes' => '',
                'preco_mensal' => 129,
                'uso_indicado' => 'Pequenas Empresas'
            ),
            array(
                'categoria' => 'email',
                'modelo' => 'Default USAEmail 60G',
                'nome_exibido' => 'USA Email 60GB',
                'ram' => '1 GB',
                'cpu' => '1 Core',
                'disco' => '60 GB',
                'visualizacoes' => '',
                'preco_mensal' => 149,
                'uso_indicado' => 'Pequenas Empresas'
            ),
            array(
                'categoria' => 'email',
                'modelo' => 'Default USAEmail 100G',
                'nome_exibido' => 'USA Email 100GB',
                'ram' => '1 GB',
                'cpu' => '1 Core',
                'disco' => '100 GB',
                'visualizacoes' => '',
                'preco_mensal' => 209,
                'uso_indicado' => 'Pequenas Empresas'
            ),
            array(
                'categoria' => 'email',
                'modelo' => 'Default USAEmail 200G',
                'nome_exibido' => 'USA Email 200GB',
                'ram' => '1 GB',
                'cpu' => '1 Core',
                'disco' => '200 GB',
                'visualizacoes' => '',
                'preco_mensal' => 289,
                'uso_indicado' => 'Pequenas Empresas'
            ),
            array(
                'categoria' => 'email',
                'modelo' => 'Default USAEmail 300G',
                'nome_exibido' => 'USA Email 300GB',
                'ram' => '1 GB',
                'cpu' => '1 Core',
                'disco' => '300 GB',
                'visualizacoes' => '',
                'preco_mensal' => 359,
                'uso_indicado' => 'Pequenas Empresas'
            ),
            array(
                'categoria' => 'email',
                'modelo' => 'Default USAEmail 400G',
                'nome_exibido' => 'USA Email 400GB',
                'ram' => '2 GB',
                'cpu' => '1 Core',
                'disco' => '400 GB',
                'visualizacoes' => '',
                'preco_mensal' => 489,
                'uso_indicado' => 'Pequenas Empresas'
            ),
            array(
                'categoria' => 'email',
                'modelo' => 'Default USAEmail 500G',
                'nome_exibido' => 'USA Email 500GB',
                'ram' => '2 GB',
                'cpu' => '1 Core',
                'disco' => '500 GB',
                'visualizacoes' => '',
                'preco_mensal' => 549,
                'uso_indicado' => 'Pequenas Empresas'
            ),
            array(
                'categoria' => 'email',
                'modelo' => 'Default USAEmail 600G',
                'nome_exibido' => 'USA Email 600GB',
                'ram' => '2 GB',
                'cpu' => '1 Core',
                'disco' => '600 GB',
                'visualizacoes' => '',
                'preco_mensal' => 869,
                'uso_indicado' => 'Pequenas Empresas'
            ),
            array(
                'categoria' => 'email',
                'modelo' => 'Default USAEmail 700G',
                'nome_exibido' => 'USA Email 700GB',
                'ram' => '4 GB',
                'cpu' => '2 Cores',
                'disco' => '700 GB',
                'visualizacoes' => '',
                'preco_mensal' => 1419,
                'uso_indicado' => 'Pequenas Empresas'
            ),
            array(
                'categoria' => 'email',
                'modelo' => 'Default USAEmail 800G',
                'nome_exibido' => 'USA Email 800GB',
                'ram' => '4 GB',
                'cpu' => '2 Cores',
                'disco' => '800 GB',
                'visualizacoes' => '',
                'preco_mensal' => 1519,
                'uso_indicado' => 'Pequenas Empresas'
            ),
            array(
                'categoria' => 'email',
                'modelo' => 'Default USAEmail 900G',
                'nome_exibido' => 'USA Email 900GB',
                'ram' => '4 GB',
                'cpu' => '2 Cores',
                'disco' => '900 GB',
                'visualizacoes' => '',
                'preco_mensal' => 1629,
                'uso_indicado' => 'Pequenas Empresas'
            ),
            array(
                'categoria' => 'email',
                'modelo' => 'Default USAEmail 1000G',
                'nome_exibido' => 'USA Email 1000GB',
                'ram' => '4 GB',
                'cpu' => '2 Cores',
                'disco' => '1000 GB',
                'visualizacoes' => '',
                'preco_mensal' => 2049,
                'uso_indicado' => 'Pequenas Empresas'
            )
        );
    }

    /**
     * Retorna os planos salvos ou os padrão
     */
    public static function get_plans() {
        $saved_plans = get_option('futturu_hospedagemcloud_plans');
        
        if (!is_array($saved_plans) || empty($saved_plans)) {
            return self::get_default_plans();
        }
        
        return $saved_plans;
    }

    /**
     * Retorna planos por categoria ordenados por preço (mais barato para mais caro)
     */
    public static function get_plans_by_category($category) {
        $plans = self::get_plans();
        
        if (!is_array($plans)) {
            return self::get_default_plans();
        }
        
        $filtered = array_filter($plans, function($plan) use ($category) {
            return isset($plan['categoria']) && $plan['categoria'] === $category;
        });
        
        // Ordena por preço crescente
        usort($filtered, function($a, $b) {
            $price_a = isset($a['preco_mensal']) ? floatval($a['preco_mensal']) : 0;
            $price_b = isset($b['preco_mensal']) ? floatval($b['preco_mensal']) : 0;
            return $price_a - $price_b;
        });
        
        return array_values($filtered);
    }

    /**
     * Retorna as funcionalidades/benefícios
     */
    public static function get_features() {
        return array(
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
            'DNS Gerenciado'
        );
    }

    /**
     * Retorna os FAQs
     */
    public static function get_faqs() {
        return array(
            array(
                'pergunta' => 'Existe alguma taxa de instalação?',
                'resposta' => 'Não! Não cobramos nenhuma taxa de instalação ou configuração. Todo o processo é gratuito.'
            ),
            array(
                'pergunta' => 'Posso mudar meu plano futuramente?',
                'resposta' => 'Sim! Você pode fazer upgrade ou downgrade do seu plano a qualquer momento, conforme a necessidade do seu projeto.'
            ),
            array(
                'pergunta' => 'Eu terei que migrar meu website?',
                'resposta' => 'Oferecemos migração gratuita de sites. Nossa equipe cuidará de toda a transferência sem downtime.'
            ),
            array(
                'pergunta' => 'Quanto tempo duram os contratos?',
                'resposta' => 'Trabalhamos com planos mensais e anuais. O plano anual oferece 10% de desconto e é recomendado para maior economia.'
            ),
            array(
                'pergunta' => 'E se eu precisar de mais e-mail e CDN?',
                'resposta' => 'Nossos planos já incluem recursos robustos de e-mail e CDN. Caso precise de algo adicional, podemos personalizar sua solução.'
            ),
            array(
                'pergunta' => 'Quais as formas de pagamento?',
                'resposta' => 'Aceitamos cartão de crédito, boleto bancário, PIX e transferência bancária. Entre em contato para mais detalhes.'
            )
        );
    }

    /**
     * Salva os planos no banco de dados
     */
    public static function save_plans($plans) {
        if (!is_array($plans)) {
            return false;
        }
        
        // Sanitiza os dados
        $sanitized = array();
        foreach ($plans as $plan) {
            if (!is_array($plan)) {
                continue;
            }
            
            $sanitized[] = array(
                'categoria' => sanitize_text_field(isset($plan['categoria']) ? $plan['categoria'] : ''),
                'modelo' => sanitize_text_field(isset($plan['modelo']) ? $plan['modelo'] : ''),
                'nome_exibido' => sanitize_text_field(isset($plan['nome_exibido']) ? $plan['nome_exibido'] : ''),
                'ram' => sanitize_text_field(isset($plan['ram']) ? $plan['ram'] : ''),
                'cpu' => sanitize_text_field(isset($plan['cpu']) ? $plan['cpu'] : ''),
                'disco' => sanitize_text_field(isset($plan['disco']) ? $plan['disco'] : ''),
                'visualizacoes' => sanitize_text_field(isset($plan['visualizacoes']) ? $plan['visualizacoes'] : ''),
                'preco_mensal' => floatval(isset($plan['preco_mensal']) ? $plan['preco_mensal'] : 0),
                'uso_indicado' => sanitize_text_field(isset($plan['uso_indicado']) ? $plan['uso_indicado'] : '')
            );
        }
        
        update_option('futturu_hospedagemcloud_plans', $sanitized);
        return true;
    }

    /**
     * Reseta para os planos padrão
     */
    public static function reset_to_defaults() {
        delete_option('futturu_hospedagemcloud_plans');
        return true;
    }

    /**
     * Retorna configurações globais
     */
    public static function get_settings() {
        $defaults = array(
            'desconto_anual' => 10,
            'email_destino' => 'suporte@futturu.com.br',
            'texto_introducao' => 'Descubra o plano de hospedagem em nuvem ideal para o seu projeto. Escolha o período de pagamento: Economize 10% contratando anualmente ou pague mensalmente com flexibilidade. Nossa parceria com a Cloudez oferece servidores de alto desempenho na nuvem com gerenciamento completo e suporte técnico especializado.'
        );
        
        $saved = get_option('futturu_hospedagemcloud_settings', array());
        return wp_parse_args($saved, $defaults);
    }

    /**
     * Salva configurações globais
     */
    public static function save_settings($settings) {
        if (!is_array($settings)) {
            return false;
        }
        
        $sanitized = array(
            'desconto_anual' => intval(isset($settings['desconto_anual']) ? $settings['desconto_anual'] : 10),
            'email_destino' => sanitize_email(isset($settings['email_destino']) ? $settings['email_destino'] : 'suporte@futturu.com.br'),
            'texto_introducao' => wp_kses_post(isset($settings['texto_introducao']) ? $settings['texto_introducao'] : '')
        );
        
        update_option('futturu_hospedagemcloud_settings', $sanitized);
        return true;
    }
}
