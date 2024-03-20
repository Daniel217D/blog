<?php

namespace DDaniel\Blog;

class Assets
{
    public function __construct(
        public readonly string $assets_path,
        public readonly string $assets_url
    ) {
    }

    public function add_css(string $file_name): void
    {
        $ver = filemtime("$this->assets_path/$file_name.css");

        if (! $ver) {
            return;
        }

        echo <<<HTML
<link rel="stylesheet" href="{$this->assets_url}/$file_name.css?ver=$ver">
HTML;
    }

    public function add_js(string $file_name): void
    {
        $ver = filemtime("$this->assets_path/$file_name.js");

        if (! $ver) {
            return;
        }

        echo <<<HTML
<script src="{$this->assets_url}/$file_name.js?ver=$ver"></script>
HTML;
    }
}
