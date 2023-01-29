<?php

namespace GTS\Administrator\Tests\Feature\Http\Admin\Controllers;

use GTS\Shared\Tests\AdminTestCase;

class CountryControllerTest extends AdminTestCase
{
    public function test_index()
    {
        $response = $this->get(route('country.index'));

        $response->assertStatus(200);
    }
}
