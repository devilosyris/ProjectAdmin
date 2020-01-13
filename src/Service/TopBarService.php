<?php

namespace App\Service;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;

class TopBarService{

    private $user;

    public function __construct(TokenStorageInterface $tokenStorage, Environment $twig, $templatePath) {
        // $this->getUser() in Controller
        $this->user = $tokenStorage->getToken()->getUser();
        $this->twig = $twig;
        $this->templatePath = $templatePath;
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        $this->user = $user;

        return $this;
    }

    public function getTemplatePath() {
        return $this->templatePath;
    }

    public function setTemplatePath($templatePath) {
        $this->templatePath = $templatePath;

        return $this;
    }

    public function display() {
        $this->twig->display($this->templatePath, [
            'user' => $this->user,
        ]);
    }
}
