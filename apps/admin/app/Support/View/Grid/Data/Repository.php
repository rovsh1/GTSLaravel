<?php

namespace App\Admin\Support\View\Grid\Data;

use Gsdk\Grid\Data\DataInterface;
use Gsdk\Grid\Support\Sorting;
use Gsdk\Navigation\Paginator;

class Repository implements DataInterface
{
    private $data;

    public function __construct($repository) {}

    public function getQuery()
    {
        return $this->query;
    }

    public function paginator(Paginator $paginator): static
    {
        $paginator->query($this->query);
        return $this;
    }

    public function sorting(Sorting $sorting): static
    {
        $sorting->query($this->query);
        return $this;
    }

    public function get(): iterable
    {
        return $this->data ?? ($this->data = $this->query->get());
    }

    public function isEmpty(): bool
    {
        return $this->get()->isEmpty();
    }

    public function withCriteria(array $criteria) {}
}
