<?php
/**
 * Class Futuru_Cloud_Data
 * Handles all data operations for plans, categories, and features
 */

if (!defined('ABSPATH')) {
    exit;
}

class Futuru_Cloud_Data {
    
    /**
     * Get default categories
     */
    public static function get_default_categories() {
        return array(
            array(
                'id' => 'clouds-padrao',
                'name' => '☁️ Clouds Padrão (Uso Geral)',
                'slug' => 'clouds-padrao',
                'description' => 'Planos balanceados para uso geral com ótimo custo-benefício',
                'order' => 1
            ),
            array(
                'id' => 'clouds-ram',
                'name' => '🧠 Clouds Focados em RAM',
                'slug' => 'clouds-ram',
                'description' => 'Planos otimizados para aplicações que exigem alta memória',
                'order' => 2
            ),
            array(
                'id' => 'clouds-cpu',
                'name' => '⚙️ Clouds Focados em CPU',
                'slug' => 'clouds-cpu',
                'description' => 'Planos de alta performance para processamento intensivo',
                'order' => 3
            ),
            array(
                'id' => 'clouds-email',
                'name' => '📧 Clouds para E-mails',
                'slug' => 'clouds-email',
                'description' => 'Planos especializados para servidores de e-mail corporativo',
                'order' => 4
            )
        );
    }
    
    /**
     * Get default features/benefits
     */
    public static function get_default_features() {
        return array(
            array(
                'id' => 'https-automatico',
                'name' => 'HTTPS Automático (Let\'s Encrypt)',
                'description' => 'Certificado SSL gratuito instalado automaticamente',
                'icon' => 'lock'
            ),
            array(
                'id' => 'backup-automatico',
                'name' => 'Backup Automático',
                'description' => 'Backups diários automáticos e seguros',
                'icon' => 'database'
            ),
            array(
                'id' => 'cdn-cache',
                'name' => 'CDN + Cache Automática',
                'description' => 'Distribuição global de conteúdo e cache inteligente',
                'icon' => 'globe'
            ),
            array(
                'id' => 'pagespeed',
                'name' => 'Pagespeed Optimizer',
                'description' => 'Otimização automática de performance',
                'icon' => 'tachometer'
            ),
            array(
                'id' => 'firewall',
                'name' => 'Firewall e Proteção Anti-blocklist',
                'description' => 'Proteção avançada contra ataques e bloqueios',
                'icon' => 'shield'
            ),
            array(
                'id' => 'monitoramento',
                'name' => 'Monitoramento de Infraestrutura',
                'description' => 'Monitoramento 24/7 com alertas proativos',
                'icon' => 'eye'
            ),
            array(
                'id' => 'atualizacoes-seguranca',
                'name' => 'Atualizações de Segurança Automáticas',
                'description' => 'Patches de segurança aplicados automaticamente',
                'icon' => 'refresh'
            ),
            array(
                'id' => 'acesso-root',
                'name' => 'Possibilidade de Acesso Root',
                'description' => 'Acesso root disponível quando aplicável',
                'icon' => 'terminal'
            ),
            array(
                'id' => 'painel-controle',
                'name' => 'Painel de Controle Automatizado e Amigável',
                'description' => 'Interface intuitiva para gerenciamento completo',
                'icon' => 'dashboard'
            ),
            array(
                'id' => 'migracao-gratis',
                'name' => 'Migração de Sites Grátis',
                'description' => 'Transferência gratuita do seu site atual',
                'icon' => 'truck'
            ),
            array(
                'id' => 'limite-memoria',
                'name' => 'Definição de Limite de Memória',
                'description' => 'Configuração personalizada de limites de memória',
                'icon' => 'memory'
            ),
            array(
                'id' => 'smtp-configurado',
                'name' => 'Serviço de SMTP Pré-configurado',
                'description' => 'Servidor de e-mail pronto para uso',
                'icon' => 'envelope'
            ),
            array(
                'id' => 'headers-seguranca',
                'name' => 'Headers de Segurança Automáticos (HSTS, etc.)',
                'description' => 'Cabeçalhos de segurança configurados automaticamente',
                'icon' => 'header'
            ),
            array(
                'id' => 'banco-dados',
                'name' => 'Banco de Dados Incluso',
                'description' => 'MySQL/MariaDB gerenciado incluso',
                'icon' => 'database'
            ),
            array(
                'id' => 'dns-gerenciado',
                'name' => 'DNS Gerenciado',
                'description' => 'Gerenciamento de DNS simplificado',
                'icon' => 'sitemap'
            ),
            array(
                'id' => 'redimensionamento',
                'name' => 'Redimensionamento Automático',
                'description' => 'Escalabilidade automática onde aplicável',
                'icon' => 'expand'
            )
        );
    }
    
    /**
     * Get default plans from the official Cloudez table
     */
    public static function get_default_plans() {
        $plans = array();
        
        // ☁️ Clouds Padrão (Uso Geral)
        $padrao_plans = array(
            array('modelo' => 'Default USA192G', 'ram' => 192, 'cpu' => 32, 'disco' => 3840, 'visualizacoes' => 24000000, 'sites' => 'Múltiplos sites', 'preco' => 18879, 'categoria' => 'clouds-padrao'),
            array('modelo' => 'Default USA128G', 'ram' => 128, 'cpu' => 24, 'disco' => 2560, 'visualizacoes' => 16000000, 'sites' => 'Múltiplos sites', 'preco' => 12629, 'categoria' => 'clouds-padrao'),
            array('modelo' => 'Default USA64G', 'ram' => 64, 'cpu' => 12, 'disco' => 1280, 'visualizacoes' => 6000000, 'sites' => 'Múltiplos sites', 'preco' => 6249, 'categoria' => 'clouds-padrao'),
            array('modelo' => 'Default USA32G', 'ram' => 32, 'cpu' => 8, 'disco' => 640, 'visualizacoes' => 3000000, 'sites' => 'Múltiplos sites', 'preco' => 3129, 'categoria' => 'clouds-padrao'),
            array('modelo' => 'BR16G', 'ram' => 16, 'cpu' => 6, 'disco' => 320, 'visualizacoes' => 1400000, 'sites' => 'Múltiplos sites', 'preco' => 2969, 'categoria' => 'clouds-padrao'),
            array('modelo' => 'Default USA16G', 'ram' => 16, 'cpu' => 6, 'disco' => 320, 'visualizacoes' => 1400000, 'sites' => 'Múltiplos sites', 'preco' => 1629, 'categoria' => 'clouds-padrao'),
            array('modelo' => 'BR8G', 'ram' => 8, 'cpu' => 4, 'disco' => 160, 'visualizacoes' => 1000000, 'sites' => 'Múltiplos sites', 'preco' => 1589, 'categoria' => 'clouds-padrao'),
            array('modelo' => 'BR4G', 'ram' => 4, 'cpu' => 2, 'disco' => 80, 'visualizacoes' => 500000, 'sites' => 'Múltiplos sites', 'preco' => 1009, 'categoria' => 'clouds-padrao'),
            array('modelo' => 'Default USA8G', 'ram' => 8, 'cpu' => 4, 'disco' => 160, 'visualizacoes' => 1000000, 'sites' => 'Múltiplos sites', 'preco' => 799, 'categoria' => 'clouds-padrao'),
            array('modelo' => 'BR2G', 'ram' => 2, 'cpu' => 1, 'disco' => 50, 'visualizacoes' => 300000, 'sites' => 'Múltiplos sites', 'preco' => 559, 'categoria' => 'clouds-padrao'),
            array('modelo' => 'Default USA4G', 'ram' => 4, 'cpu' => 2, 'disco' => 80, 'visualizacoes' => 500000, 'sites' => 'Múltiplos sites', 'preco' => 439, 'categoria' => 'clouds-padrao'),
            array('modelo' => 'BR1G', 'ram' => 1, 'cpu' => 1, 'disco' => 25, 'visualizacoes' => 100000, 'sites' => 'Múltiplos sites', 'preco' => 239, 'categoria' => 'clouds-padrao'),
            array('modelo' => 'Default USA2G', 'ram' => 2, 'cpu' => 1, 'disco' => 50, 'visualizacoes' => 300000, 'sites' => 'Múltiplos sites', 'preco' => 229, 'categoria' => 'clouds-padrao'),
            array('modelo' => 'BR1G Individual', 'ram' => 1, 'cpu' => 1, 'disco' => 25, 'visualizacoes' => 100000, 'sites' => '1 site (Individual)', 'preco' => 219, 'categoria' => 'clouds-padrao'),
            array('modelo' => 'Default USA1G', 'ram' => 1, 'cpu' => 1, 'disco' => 25, 'visualizacoes' => 100000, 'sites' => 'Múltiplos sites', 'preco' => 119, 'categoria' => 'clouds-padrao'),
            array('modelo' => 'Default USA1G Individual', 'ram' => 1, 'cpu' => 1, 'disco' => 25, 'visualizacoes' => 100000, 'sites' => '1 site (Individual)', 'preco' => 89, 'categoria' => 'clouds-padrao'),
        );
        
        foreach ($padrao_plans as $plan) {
            $plan['id'] = sanitize_title($plan['modelo']);
            $plan['features'] = array_keys(self::get_default_features());
            $plans[] = $plan;
        }
        
        // 🧠 Clouds Focados em RAM
        $ram_plans = array(
            array('modelo' => 'Default USAElite RAM', 'ram' => 300, 'cpu' => 16, 'disco' => 340, 'visualizacoes' => null, 'sites' => 'Múltiplos sites', 'preco' => 17579, 'categoria' => 'clouds-ram'),
            array('modelo' => 'Default USAUltra RAM', 'ram' => 150, 'cpu' => 8, 'disco' => 200, 'visualizacoes' => null, 'sites' => 'Múltiplos sites', 'preco' => 8759, 'categoria' => 'clouds-ram'),
            array('modelo' => 'Default USAHyper RAM', 'ram' => 90, 'cpu' => 4, 'disco' => 90, 'visualizacoes' => null, 'sites' => 'Múltiplos sites', 'preco' => 4739, 'categoria' => 'clouds-ram'),
            array('modelo' => 'Default USASuper RAM', 'ram' => 48, 'cpu' => 2, 'disco' => 40, 'visualizacoes' => null, 'sites' => 'Múltiplos sites', 'preco' => 2409, 'categoria' => 'clouds-ram'),
            array('modelo' => 'Default USAMax RAM', 'ram' => 24, 'cpu' => 1, 'disco' => 20, 'visualizacoes' => null, 'sites' => 'Múltiplos sites', 'preco' => 1299, 'categoria' => 'clouds-ram'),
        );
        
        foreach ($ram_plans as $plan) {
            $plan['id'] = sanitize_title($plan['modelo']);
            $plan['features'] = array_keys(self::get_default_features());
            $plans[] = $plan;
        }
        
        // ⚙️ Clouds Focados em CPU
        $cpu_plans = array(
            array('modelo' => 'Default USAPrestige CPU', 'ram' => 96, 'cpu' => 48, 'disco' => 1920, 'visualizacoes' => null, 'sites' => 'Múltiplos sites', 'preco' => 13149, 'categoria' => 'clouds-cpu'),
            array('modelo' => 'Default USAElite CPU', 'ram' => 64, 'cpu' => 32, 'disco' => 1280, 'visualizacoes' => null, 'sites' => 'Múltiplos sites', 'preco' => 8879, 'categoria' => 'clouds-cpu'),
            array('modelo' => 'Default USAUltra CPU', 'ram' => 32, 'cpu' => 16, 'disco' => 640, 'visualizacoes' => null, 'sites' => 'Múltiplos sites', 'preco' => 4499, 'categoria' => 'clouds-cpu'),
            array('modelo' => 'Default USAHyper CPU', 'ram' => 16, 'cpu' => 8, 'disco' => 320, 'visualizacoes' => null, 'sites' => 'Múltiplos sites', 'preco' => 2249, 'categoria' => 'clouds-cpu'),
            array('modelo' => 'Default USASuper CPU', 'ram' => 8, 'cpu' => 4, 'disco' => 160, 'visualizacoes' => null, 'sites' => 'Múltiplos sites', 'preco' => 1199, 'categoria' => 'clouds-cpu'),
            array('modelo' => 'Default USAMax CPU', 'ram' => 4, 'cpu' => 2, 'disco' => 80, 'visualizacoes' => null, 'sites' => 'Múltiplos sites', 'preco' => 649, 'categoria' => 'clouds-cpu'),
        );
        
        foreach ($cpu_plans as $plan) {
            $plan['id'] = sanitize_title($plan['modelo']);
            $plan['features'] = array_keys(self::get_default_features());
            $plans[] = $plan;
        }
        
        // 📧 Clouds para E-mails
        $email_plans = array(
            array('modelo' => 'Default USAEmail 1000G', 'ram' => 4, 'cpu' => 2, 'disco' => 1000, 'visualizacoes' => null, 'sites' => 'Pequenas Empresas', 'preco' => 2049, 'categoria' => 'clouds-email'),
            array('modelo' => 'Default USAEmail 900G', 'ram' => 4, 'cpu' => 2, 'disco' => 900, 'visualizacoes' => null, 'sites' => 'Pequenas Empresas', 'preco' => 1629, 'categoria' => 'clouds-email'),
            array('modelo' => 'Default USAEmail 800G', 'ram' => 4, 'cpu' => 2, 'disco' => 800, 'visualizacoes' => null, 'sites' => 'Pequenas Empresas', 'preco' => 1519, 'categoria' => 'clouds-email'),
            array('modelo' => 'Default USAEmail 700G', 'ram' => 4, 'cpu' => 2, 'disco' => 700, 'visualizacoes' => null, 'sites' => 'Pequenas Empresas', 'preco' => 1419, 'categoria' => 'clouds-email'),
            array('modelo' => 'Default USAEmail 600G', 'ram' => 2, 'cpu' => 1, 'disco' => 600, 'visualizacoes' => null, 'sites' => 'Pequenas Empresas', 'preco' => 869, 'categoria' => 'clouds-email'),
            array('modelo' => 'Default USAEmail 500G', 'ram' => 2, 'cpu' => 1, 'disco' => 500, 'visualizacoes' => null, 'sites' => 'Pequenas Empresas', 'preco' => 549, 'categoria' => 'clouds-email'),
            array('modelo' => 'Default USAEmail 400G', 'ram' => 2, 'cpu' => 1, 'disco' => 400, 'visualizacoes' => null, 'sites' => 'Pequenas Empresas', 'preco' => 489, 'categoria' => 'clouds-email'),
            array('modelo' => 'Default USAEmail 300G', 'ram' => 1, 'cpu' => 1, 'disco' => 300, 'visualizacoes' => null, 'sites' => 'Pequenas Empresas', 'preco' => 359, 'categoria' => 'clouds-email'),
            array('modelo' => 'Default USAEmail 200G', 'ram' => 1, 'cpu' => 1, 'disco' => 200, 'visualizacoes' => null, 'sites' => 'Pequenas Empresas', 'preco' => 289, 'categoria' => 'clouds-email'),
            array('modelo' => 'Default USAEmail 100G', 'ram' => 1, 'cpu' => 1, 'disco' => 100, 'visualizacoes' => null, 'sites' => 'Pequenas Empresas', 'preco' => 209, 'categoria' => 'clouds-email'),
            array('modelo' => 'Default USAEmail 60G', 'ram' => 1, 'cpu' => 1, 'disco' => 60, 'visualizacoes' => null, 'sites' => 'Pequenas Empresas', 'preco' => 149, 'categoria' => 'clouds-email'),
            array('modelo' => 'Default USAEmail 20G', 'ram' => 1, 'cpu' => 1, 'disco' => 20, 'visualizacoes' => null, 'sites' => 'Pequenas Empresas', 'preco' => 129, 'categoria' => 'clouds-email'),
        );
        
        foreach ($email_plans as $plan) {
            $plan['id'] = sanitize_title($plan['modelo']);
            $plan['features'] = array_keys(self::get_default_features());
            $plans[] = $plan;
        }
        
        return $plans;
    }
    
    /**
     * Get all plans
     */
    public static function get_plans() {
        return get_option('futturu_cloud_plans', self::get_default_plans());
    }
    
    /**
     * Get plan by ID
     */
    public static function get_plan($id) {
        $plans = self::get_plans();
        foreach ($plans as $plan) {
            if ($plan['id'] === $id) {
                return $plan;
            }
        }
        return null;
    }
    
    /**
     * Get plans by category
     */
    public static function get_plans_by_category($category_slug) {
        $plans = self::get_plans();
        $filtered = array_filter($plans, function($plan) use ($category_slug) {
            return $plan['categoria'] === $category_slug;
        });
        return array_values($filtered);
    }
    
    /**
     * Get all categories
     */
    public static function get_categories() {
        return get_option('futturu_cloud_categories', self::get_default_categories());
    }
    
    /**
     * Get all features
     */
    public static function get_features() {
        return get_option('futturu_cloud_features', self::get_default_features());
    }
    
    /**
     * Get settings
     */
    public static function get_settings() {
        $defaults = array(
            'intro_text' => 'Descubra o plano de hospedagem em nuvem ideal para o seu projeto com a Futturu. Nossa parceria com a Cloudez oferece servidores de alto desempenho na nuvem (USA/Dallas e Newark), com gerenciamento completo, automações inteligentes e suporte técnico especializado, tudo para que você se preocupe apenas com o seu negócio.',
            'cta_text' => 'Solicitar Cotação',
            'cta_email' => 'suporte@futturu.com.br',
            'plugin_enabled' => true
        );
        return get_option('futturu_cloud_settings', $defaults);
    }
    
    /**
     * Format price to Brazilian Real
     */
    public static function format_price($price) {
        return 'R$ ' . number_format($price, 2, ',', '.');
    }
    
    /**
     * Format large numbers (views, disk, etc.)
     */
    public static function format_number($number, $type = 'general') {
        if ($number === null) {
            return 'N/A';
        }
        
        if ($type === 'views') {
            if ($number >= 1000000) {
                return number_format($number / 1000000, 1, ',', '.') . 'M';
            } elseif ($number >= 1000) {
                return number_format($number / 1000, 0, ',', '.') . 'K';
            }
        } elseif ($type === 'storage') {
            if ($number >= 1000) {
                return number_format($number / 1000, 0, ',', '.') . 'TB';
            }
            return $number . 'GB';
        } elseif ($type === 'memory') {
            return $number . 'GB';
        } elseif ($type === 'cpu') {
            return $number . ($number > 1 ? ' Cores' : ' Core');
        }
        
        return number_format($number, 0, ',', '.');
    }
}
