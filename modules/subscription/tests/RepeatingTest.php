<?php


class RepeatingTest extends Unittest_TestCase
{
    /** @test */
    public function it_can_calculate_its_price()
    {
        $subscription = $this->prophesize('Subscription');
        $subscription->price()->willReturn(1000);

        $repeating = new Subscription_Repeating($subscription->reveal(), 3);

        $this->assertEquals(3000, $repeating->price());
    }
}