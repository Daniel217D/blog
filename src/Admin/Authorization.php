<?php

namespace DDaniel\Blog\Admin;

use DDaniel\Blog\Exceptions\AuthorizationException;
use DDaniel\Blog\Models\Author;
use Doctrine\ORM\Exception\ORMException;
use Exception;

class Authorization
{
    private readonly string $cookieName;

    private Password $password;

    public function __construct()
    {
        $this->password   = new Password();
        $this->cookieName = md5('author_'. app()->site_url);
    }


    public function isAuthorized(): false|Author
    {
        try {
            return $this->verifyCookie();
        } catch (Exception) {
            return false;
        }
    }

    /**
     * @throws AuthorizationException
     */
    public function authorize(string $email, string $password): Author
    {
        $author = $this->getAuthor($email, $password);
        $this->setCookie($author);

        return $author;
    }

    public function vanish()
    {
        $this->removeCookie();
    }

    /**
     * @throws AuthorizationException
     */
    private function getAuthor(string $email, string $password): Author
    {
        $author = app()
            ->entity_manager
            ->getRepository(Author::class)
            ->findOneBy(array(
                'email' => $email,
            ));

        if (null === $author || ! $this->password->verify($password, $author->getPassword())) {
            throw new AuthorizationException('Email not found');
        }

        return $author;
    }

    /**
     * @return Author
     * @throws AuthorizationException
     */
    private function verifyCookie(): Author
    {
        if ( ! isset($_COOKIE[$this->cookieName])) {
            throw new AuthorizationException('Author not logged in');
        }

        $id = explode('_', $_COOKIE[$this->cookieName])[0];

        if ( ! isset($id)) {
            throw new AuthorizationException('Author not logged in');
        }

        try {
            $author = app()
                ->entity_manager
                ->find(Author::class, (int)$id);
        } catch (ORMException $e) {
            throw new AuthorizationException('Author not logged in');
        }

        if (null === $author) {
            throw new AuthorizationException('Author not logged in');
        }

        if ($this->generateAuthCookieValue($author) !== $_COOKIE[$this->cookieName]) {
            throw new AuthorizationException('Author not logged in');
        }

        return $author;
    }

    private function generateAuthCookieValue(Author $author): string
    {
        return $author->getId().'_'.hash('md5', $author->getEmail().$author->getPassword());
    }

    /**
     * @throws AuthorizationException
     */
    private function setCookie(Author $author): void
    {
        $res = setcookie(
            name: $this->cookieName,
            value: $this->generateAuthCookieValue($author),
            expires_or_options: time() + 60 * 60 * 24 * 30,
            secure: true,
            httponly: true
        );

        if ( ! $res) {
            throw new AuthorizationException('Unable to set cookies');
        }
    }

    private function removeCookie(): void
    {
        setcookie(
            name: $this->cookieName,
            expires_or_options: -1
        );

        unset($_COOKIE[$this->cookieName]);
    }
}