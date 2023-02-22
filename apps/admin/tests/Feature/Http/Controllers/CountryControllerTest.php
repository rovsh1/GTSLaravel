<?php

namespace apps\admin\tests\Feature\Http\Controllers;

use Module\Shared\Tests\AdminTestCase;

class CountryControllerTest extends AdminTestCase
{
    public function test_index()
    {
        $response = $this->get(route('country.index'));

        $response->assertStatus(200);
    }
}
