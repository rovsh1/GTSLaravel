<?php

namespace GTS\Services\Traveline\Infrastructure\Api\Hotel\Search;

interface ApiInterface
{

    public function getRoomsAndRatePlans(int $hotelId);

}
