<?php

require_once("../src/Dataclass.php");

use Dataclasses\Dataclass;

enum PaymentStatus
{
    case Completed;
    case Pending;
    case Failed;
    case Cancelled;
}

class Payment extends Dataclass
{
    public int $accountId;
    public int $transactionId;
    public float $amount;
    public PaymentStatus $paymentStatus;
}

// ..

$payment = new Payment([
    'accountId' => 123,
    'transactionId' => 100002,
    'amount' => 24.99,
    'paymentStatus' => 'Pending'
]);

var_dump($payment);