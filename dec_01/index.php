<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

$testStep1 = [
    12     => 2,
    14     => 2,
    1969   => 654,
    100756 => 33583,
];

$testStep2 = [
    14     => 2,
    1969   => 966,
    100756 => 50346,
];

// Step 1 resolution testing
foreach ($testStep1 as $mass => $fuel) {
    if ($fuel !== getFuelMass($mass)) {
        echo
        sprintf(
            'Fuel for a module with a mass of %s should be %s but the measured value is %s',
            $mass,
            $fuel,
            getFuelMass($mass)
        );
    }
}

// Step 2 resolution testing
foreach ($testStep2 as $mass => $fuel) {
    if ($fuel !== getFuelMass($mass, true)) {
        echo
        sprintf(
            'Fuel for a module with a mass of %s should be %s but the measured value is %s',
            $mass,
            $fuel,
            getFuelMass($mass, true)
        );
    }
}

$massesInput         = explode(PHP_EOL, file_get_contents('input.txt'));
$requiredFuel1stStep = 0;
$requiredFuel2ndStep = 0;

foreach ($massesInput as $mass) {
    if (0 === (int) $mass) {
        continue;
    }

    $requiredFuel1stStep += getFuelMass((int) $mass);
    $requiredFuel2ndStep += getFuelMass((int) $mass, true);
}

echo sprintf(
    'Only taking into account fuel for each module, we\'ll need %s units of fuel. Now taking into account fuel for each module and every volume of fuel, we\'ll need %s units of fuel in total.',
    $requiredFuel1stStep,
    $requiredFuel2ndStep
);

function getFuelMass(int $mass, bool $measureAdditionalFuel = false)
{
    $fuel = measureFuelMass($mass);

    if ($measureAdditionalFuel) {
        $moreFuel = $fuel;

        while ($moreFuel > 0) {
            $moreFuel = measureFuelMass($moreFuel);
            $fuel     += $moreFuel;
        }
    }

    return $fuel;
}

function measureFuelMass($value)
{
    $result = (int) (floor($value / 3) - 2);

    return 0 < $result ? $result : 0;
}

// 1 = 3234871
// 2 = 4849444