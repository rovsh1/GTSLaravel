<?php

namespace App\Hotel\Support\View\Grid;

use App\Hotel\Support\View\Form\FormBuilder;
use Gsdk\Grid\GridBuilder as Base;
use Gsdk\Grid\Paginator;

/**
 * @method static data(mixed $data)
 * @method static orderBy(string $name, string $order = 'asc')
 * @method static paginator(int|Paginator $paginator = null)
 * @method static text(string $name, array $options = [])
 * @method static email(string $name, array $options = [])
 * @method static phone(string $name, array $options = [])
 * @method static number(string $name, array $options = [])
 * @method static date(string $name, array $options = [])
 * @method static url(string $name, array $options = [])
 * @method static bookingStatus(string $name, array $options = [])
 */
class GridBuilder extends Base
{
    protected ?FormBuilder $searchForm = null;

    protected ?Quicksearch $quicksearch = null;

    public function getSearchCriteria(): array
    {
        $criteria = [];

        if ($this->searchForm) {
            $criteria = array_filter($this->searchForm->getData());
        }

        if ($this->quicksearch && ($term = $this->quicksearch->getTerm())) {
            $criteria['quicksearch'] = $term;
        }

        return $criteria;
    }

    public function enableQuicksearch(): static
    {
        $this->quicksearch = new Quicksearch();
        $this->quicksearch->submit();

        return $this;
    }

    public function getQuicksearch(): ?Quicksearch
    {
        return $this->quicksearch;
    }

    public function setSearchForm(FormBuilder $form): static
    {
        $this->searchForm = $form;
        $this->searchForm->submit();

        return $this;
    }

    public function getSearchForm(): ?FormBuilder
    {
        return $this->searchForm;
    }
}
