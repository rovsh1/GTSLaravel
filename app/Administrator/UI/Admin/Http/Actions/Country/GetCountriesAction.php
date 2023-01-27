<?php

namespace GTS\Administrator\UI\Admin\Http\Actions\Country;

use GTS\Administrator\Infrastructure\Facade\Reference\CountryFacadeInterface;

class GetCountriesAction
{
    public function __construct(private CountryFacadeInterface $facade) { }

    public function handle()
    {
        $dto = new \stdClass();
        $dto->orderBy = 'id';
        $dto->sortOrder = 'desc';
        $dto->limit = 2;
        $dto->default = false;
        $dto->flag = 'ru';

        $result = $this->facade->search($dto);

        dd($result);
    }
}
