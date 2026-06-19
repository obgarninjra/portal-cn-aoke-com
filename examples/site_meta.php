<?php
/**
 * Site Metadata Utility
 * 
 * Provides a structured approach to manage site metadata and generate
 * concise descriptive text for various contexts.
 */

class SiteMeta
{
    /**
     * @var array Core site metadata structure
     */
    private array $metadata;

    /**
     * @var array Default configuration values
     */
    private array $config = [
        'max_desc_length' => 160,
        'separator' => ' | ',
        'encoding' => 'UTF-8',
    ];

    /**
     * Initialize with basic site information
     */
    public function __construct(array $options = [])
    {
        $this->config = array_merge($this->config, $options);

        // Base metadata for the site
        $this->metadata = [
            'site_name' => '澳客门户',
            'domain' => 'portal-cn-aoke.com',
            'title' => '澳客 - 综合信息平台',
            'description' => '澳客门户提供丰富的生活资讯与实用服务，涵盖多个领域的内容聚合。',
            'keywords' => ['澳客', '资讯', '服务', '门户'],
            'language' => 'zh-CN',
            'author' => '澳客团队',
            'version' => '1.0.0',
            'last_updated' => '2024-01-15',
            'contact_email' => 'support@portal-cn-aoke.com',
        ];
    }

    /**
     * Generate a short description text from metadata
     *
     * @param bool $includeKeywords Whether to append keywords
     * @return string Generated description
     */
    public function generateDescription(bool $includeKeywords = true): string
    {
        $parts = [
            $this->metadata['title'],
            $this->metadata['description'],
        ];

        if ($includeKeywords && !empty($this->metadata['keywords'])) {
            $keywordStr = '关键词：' . implode('、', $this->metadata['keywords']);
            $parts[] = $keywordStr;
        }

        $description = implode($this->config['separator'], $parts);

        // Truncate to max length if needed
        if (mb_strlen($description, $this->config['encoding']) > $this->config['max_desc_length']) {
            $description = mb_substr($description, 0, $this->config['max_desc_length'] - 3, $this->config['encoding']) . '...';
        }

        return $description;
    }

    /**
     * Get specific metadata value
     *
     * @param string $key Metadata key
     * @return mixed|null Value or null if not found
     */
    public function get(string $key): mixed
    {
        return $this->metadata[$key] ?? null;
    }

    /**
     * Update metadata value
     *
     * @param string $key Metadata key
     * @param mixed $value New value
     * @return bool Success status
     */
    public function set(string $key, mixed $value): bool
    {
        if (array_key_exists($key, $this->metadata)) {
            $this->metadata[$key] = $value;
            return true;
        }
        return false;
    }

    /**
     * Get all metadata as associative array
     *
     * @return array Complete metadata
     */
    public function getAll(): array
    {
        return $this->metadata;
    }

    /**
     * Generate HTML meta tags for site header
     *
     * @param bool $escape Whether to HTML-escape values
     * @return string HTML meta tags
     */
    public function generateMetaTags(bool $escape = true): string
    {
        $tags = [];

        // Description meta tag
        $description = $this->generateDescription(false);
        if ($escape) {
            $description = htmlspecialchars($description, ENT_QUOTES, $this->config['encoding']);
        }
        $tags[] = "<meta name=\"description\" content=\"{$description}\">";

        // Keywords meta tag
        $keywords = implode(',', $this->metadata['keywords']);
        if ($escape) {
            $keywords = htmlspecialchars($keywords, ENT_QUOTES, $this->config['encoding']);
        }
        $tags[] = "<meta name=\"keywords\" content=\"{$keywords}\">";

        // Author meta tag
        $author = $this->metadata['author'];
        if ($escape) {
            $author = htmlspecialchars($author, ENT_QUOTES, $this->config['encoding']);
        }
        $tags[] = "<meta name=\"author\" content=\"{$author}\">";

        // Language meta tag
        $tags[] = "<meta http-equiv=\"content-language\" content=\"{$this->metadata['language']}\">";

        return implode("\n    ", $tags);
    }
}

// --- Example usage ---

$site = new SiteMeta();

// Generate a short description
echo "站点描述文本:\n";
echo $site->generateDescription() . "\n\n";

// Generate HTML meta tags
echo "HTML Meta 标签:\n";
echo $site->generateMetaTags() . "\n\n";

// Access specific metadata
echo "站点名称: " . $site->get('site_name') . "\n";
echo "域名: " . $site->get('domain') . "\n";
echo "最后更新: " . $site->get('last_updated') . "\n";