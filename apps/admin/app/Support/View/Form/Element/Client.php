<?php

namespace App\Admin\Support\View\Form\Element;

use App\Admin\Models\Client\Client as Model;

class Client extends BaseSelect
{
    public function __construct(string $name, array $options = [])
    {
        parent::__construct($name, $options);

        $clientsQuery = Model::orderBy('name');
        $onlyWithOrders = $options['onlyWithOrders'] ?? false;
        if ($onlyWithOrders) {
            $clientsQuery->whereHasActiveOrders();
        }

        $this->setItems($clientsQuery->get());
    }
}
