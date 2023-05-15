<?php

namespace tests;

class CustomerTest extends Base
{
    public function testCustomer()
    {
        $customer = $this->service->whoAreMeInfo();
        $this->assertNotEmpty($customer->login);
    }
}