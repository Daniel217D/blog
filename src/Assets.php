<?php

declare(strict_types=1);

namespace DDaniel\Blog;

class Assets
{
    public function __construct(
        public readonly string $assets_path,
        public readonly string $assets_url
    ) {
    }

    public function getCssUrl($file_name): string
    {
        return "$this->assets_url/$file_name.css";
    }

    public function addCss(string $file_name): void
    {
        $ver = filemtime("$this->assets_path/$file_name.css");

        if ($ver === false) {
            return;
        }

        $file_url = $this->getCssUrl($file_name);

        echo <<<HTML
<link rel="stylesheet" href="$file_url?ver=$ver">
HTML;
    }

    public function addJs(string $file_name): void
    {
        $ver = filemtime("$this->assets_path/$file_name.js");

        if ($ver === false) {
            return;
        }

        echo <<<HTML
<script src="{$this->assets_url}/$file_name.js?ver=$ver"></script>
HTML;
    }
}
