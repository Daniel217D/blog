<?php

declare(strict_types=1);

namespace DDaniel\Blog;

use DateTimeImmutable;
use DDaniel\Blog\Entities\BaseEntity;
use DDaniel\Blog\Entities\Post;
use DDaniel\Blog\Entities\Tag;
use DDaniel\Blog\Enums\Entity;

final class SitemapGenerator
{
    private DateTimeImmutable $lastSiteUpdate;

    public function __construct()
    {
        $this->lastSiteUpdate = app()->em->getRepository(Post::class)->findOneBy([], ['updatedTime' => 'DESC'])->getUpdatedTime();
    }

    public function setHeaders(): void {
        header("Content-Type: text/xml");
    }

    public function printXml(): void {
        echo $this->generate();
    }

    public function generate(): string {
        return sprintf(
            "%s\n%s\n%s",
        '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd http://www.google.com/schemas/sitemap-image/1.1 http://www.google.com/schemas/sitemap-image/1.1/sitemap-image.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">',
                $this->generateUrls(),
                '</urlset>'
        );
    }

    private function generateUrls(): string {
        $posts = $this->generateEntityUrls(app()->em->getRepository(Post::class)->findAll());
        $tags = $this->generateEntityUrls(app()->em->getRepository(Tag::class)->findAll());



        return <<<XML
{$this->generateUrlTag( app()->home_url, $this->lastSiteUpdate )}
{$posts}
{$tags}
XML;

    }

    /**
     * @param  BaseEntity[]  $entities
     *
     * @return string
     */
    private function generateEntityUrls(array $entities): string {
        return implode(
            "\n",
            array_map(fn(BaseEntity $entity) => $this->generateEntityUrl($entity), $entities)
        );
    }

    private function generateEntityUrl(BaseEntity $entity): string {
        return $this->generateUrlTag(
            app()->router->getUrlForEntityFrontend($entity),
            $entity->getUpdatedTime()
        );
    }

    private function generateUrlTag(string $url, DateTimeImmutable $datetime): string {
        return <<<XML
	<url>
		<loc>$url</loc>
		<lastmod>{$datetime->format('c')}</lastmod>
	</url>
XML;

    }
}