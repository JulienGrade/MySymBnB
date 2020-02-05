<?php

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;

class PaginationService {
   private $entityClass;
   private $limit = 10;
   private $currentPage = 1;
   private $manager;

   // Grace au constructor on a accès au manager de doctrine
   public function __construct(ObjectManager $manager)
   {
       $this->manager = $manager;
   }

   public function getPages(){
        // Connaitre le total des enregistrements de la table
       $repo = $this->manager->getRepository($this->entityClass);
       $total = count($repo->findAll());

       // On calcule le nb total de page et ceil fonction php arrondi au dessus en cas de 2,3 par ex
       // Faire la division, l'arrondi et le renvoyer
       $pages = ceil($total / $this->limit);

       return $pages;
   }

    public function getData(){
       // On calcule l'offest
        $offset = $this->currentPage * $this->limit - $this->limit;

       // Demander au repository de trouver les éléments
        $repo = $this->manager->getRepository($this->entityClass);
        $data = $repo->findBy([], [], $this->limit, $offset);
       // Envoyer les éléments
        return $data;
   }

   public function setPage($page){
       $this->currentPage = $page;
       return $this;
   }

   public function getPage(){
       return $this->currentPage;
   }

   public function setLimit($limit){
       $this->limit = $limit;

       return $this;
   }

   public function getLimit(){
       return $this->limit;
   }

   public function setEntityClass($entityClass){
       $this->entityClass = $entityClass;

       return $this;
   }

   public function getEntityClass(){
       return $this->entityClass;
   }
}