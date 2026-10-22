<?php

use PHPUnit\Framework\TestCase as BaseTestCase;

class RuntimePolicyTest extends BaseTestCase
{
    public function testPatchBoundaries()
    {
        foreach (['8.2' => 35, '8.3' => 36, '8.4' => 27, '8.5' => 12] as $branch => $patch) {
            $this->assertFalse(dicewareRuntimeIsSupported($branch.'.'.($patch - 1)));
            $this->assertTrue(dicewareRuntimeIsSupported($branch.'.'.$patch));
            $this->assertTrue(dicewareRuntimeIsSupported($branch.'.'.($patch + 1)));
            $this->assertFalse(dicewareRuntimeIsSupported($branch.'.'.$patch.'RC1'));
        }
        foreach (['7.4.33', '8.0.30', '8.1.99', '8.6.0', '9.0.0', 'invalid'] as $version) {
            $this->assertFalse(dicewareRuntimeIsSupported($version));
        }
    }
}
