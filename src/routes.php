<?php

/**
 * @var Router $this
 */



use DDaniel\Blog\Articles\Article;
use DDaniel\Blog\Articles\Articles;
use DDaniel\Blog\PageController;
use DDaniel\Blog\Router;

$this->add_route('home', 'GET', '/', function () {

    $this->render_page(new PageController(title: 'Блог Даниила Дубченко', description: 'Блог Даниила Дубченко о web-разработке. Бекенд на Node.js и PHP. Фронт на чем угодно только не jQuery', content: ( new Articles($_GET['s'] ?? '') )->get_content_html()));
});
$this->add_route('article', 'GET', '/{slug}', function (array $params) {

    $this->render_page(new PageController(title: $params['slug'], content: Article::foundOrFail($params['slug'])->get_content_html(), type: 'article'));
});
$this->add_route('article_api', 'GET', '/api/articles/{slug}', function (array $params) {

    $content = Article::foundOrFail($params['slug'])->get_content_html();
    $this->send_json(array(
        'success' => true,
        'content' => $content,
    ));
});
