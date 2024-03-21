<?php

declare(strict_types=1);

namespace DDaniel\Blog;

class Templates
{
    public function __construct(
        private string $templates_path
    ) {
    }

    /**
     * Check if template exists in templates directory
     *
     * @param  string $template template path.
     *
     * @return bool
     */
    public function exists(string $template): bool
    {
        if (! str_ends_with($template, '.php')) {
            $template .= '.php';
        }

        return file_exists($this->templates_path . $template);
    }

    /**
     * Include template from templates directory
     *
     * @param string $template Template name.
     * @param  array  $args     Arguments.
     * @param  bool  $echo     Return or echo. Echo by default.
     *
     * @return string
     */
    public function include(string $template, array $args = array(), bool $echo = true): string
    {
        if (! str_ends_with($template, '.php')) {
            $template .= '.php';
        }

        if ($this->exists($template)) {
            ob_start();

            extract($args);
            /**
             * @psalm-suppress UnresolvableInclude
             */
            include $this->templates_path . $template;

            $template_body = ob_get_clean();
        } elseif (defined('WP_DEBUG_DISPLAY') && WP_DEBUG_DISPLAY) {
            $template_body = "Template '$template' not found";
        } else {
            $template_body = '';
        }

        if ($echo) {
            echo $template_body;
        }

        return $template_body;
    }
}
