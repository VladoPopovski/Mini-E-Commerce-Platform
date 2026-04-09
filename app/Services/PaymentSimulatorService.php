<?php

namespace App\Services;

class PaymentSimulatorService
{
    public function process(float $total, string $paymentMethod): bool
    {
        // Orders over $999 fail — deterministic rule per AGENT.md
        return $total <= 999;
    }
}
