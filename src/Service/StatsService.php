<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class StatsService {
    private $manager;

    public function __construct(EntityManagerInterface $manager) {
        $this->manager = $manager;
    }

    public function getStats() {
        $customers = $this->getCountUsers();
        $invoices = $this->getCountInvoiceUnpaid();

        return compact('customers', 'invoices');
    }

    public function getCountUsers() {
      return $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u JOIN u.roles ru  WHERE ru.id = 4')->getSingleScalarResult();
    }

    public function getCountInvoiceUnpaid() {
        return $this->manager->createQuery('SELECT COUNT(i) FROM App\Entity\Invoice i WHERE i.paid = 0')->getSingleScalarResult();
    }
}