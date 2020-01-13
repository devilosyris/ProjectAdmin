<?php 
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

class PaginationService {
    private $entityClass;
    private $filter;
    private $limit = 10;

    public function __construct(RequestStack $request, EntityManagerInterface $manager, Environment $twig, $templatePath) {
        
      $this->currentPage = $request->getCurrentRequest()->attributes->get('page');
      $this->route = $request->getCurrentRequest()->attributes->get('_route');
      $this->manager = $manager;
      $this->twig = $twig;
      $this->templatePath = $templatePath;
    }

    public function getPage() {
        return $this->currentPage;
    }

    public function getLimit() {
        return $this->limit;
    }
    public function setLimit($limit) {
        $this->limit = $limit;
        return $this;
    }

    public function getEntityClass() {
        return $this->entityClass;
    }
    public function setEntityClass($entityClass) {
        $this->entityClass = $entityClass;
        return $this;
    }

    public function getDataTotal() {
        if($this->filter){
            $data = count($this->manager->getRepository($this->entityClass)->findBy($this->filter));
        }else {
          $data = count($this->manager->getRepository($this->entityClass)->findAll());
        }
        return $data;
    }

    public function getTotalPage() {
        return ceil($this->getDataTotal()/ $this->limit);
    }

    public function getData() {
      $offset = $this->currentPage * $this->limit - $this->limit;
      if($this->filter) {
        $data = $this->manager->getRepository($this->entityClass)->findBy($this->filter, [], $this->limit, $offset);
      } else{
        $data = $this->manager->getRepository($this->entityClass)->findBy([], [], $this->limit, $offset);
      }
      
      return $data;
        
    }
    public function getFilter() {
        return $this->filter;
    }

    public function setFilter($filter) {
        $this->filter = $filter;

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
            'pages' => $this->getTotalPage(),
            'page' => $this->getPage(),
            'route' => $this->route,
        ]);
    }
}