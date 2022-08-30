<?php

namespace App\Enums;

enum FuelType: string {
    case nintyTwo = "92";
    case nintyFive = "95";
    case nintySeven = "97";
    case diesel = "diesel";
    case premium_diesel = "premium_diesel";
    case gas = "gas";
}