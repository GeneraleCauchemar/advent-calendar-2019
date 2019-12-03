<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

function getUpdatedProgram(string $input)
{
    $array = explode(',', $input);
    $loop  = 0;

    do {
        $offset       = 0 + (4 * $loop);
        $updatedInput = returnOutput(array_slice($array, $offset, 4), $array);

        if (!empty($updatedInput)) {
            $position         = array_key_first($updatedInput);
            $value            = reset($updatedInput);
            $array[$position] = $value;
        }

        ++$loop;
    } while (!empty($updatedInput));

    return $array;
}

function returnOutput(array $instruction, array $array)
{
    $opcode = (int) reset($instruction);
    if (99 === $opcode) {
        return [];
    }

    $firstValue  = $array[(int) $instruction[1]];
    $secondValue = $array[(int) $instruction[2]];
    $position    = (int) $instruction[3];
    $result      = [];

    switch ($opcode) {
        case 1:
            $result[$position] = $firstValue + $secondValue;
            break;
        case 2:
            $result[$position] = $firstValue * $secondValue;
            break;
        default:
            echo 'Unknown opcode. Something probably went wrong (somewhere).';
            break;
    }

    return $result;
}

function updateValuesWithNounAndVerb(int $noun, int $verb, array $array)
{
    $array[1] = $noun;
    $array[2] = $verb;

    return $array;
}

$testStep = [
    '1,0,0,0,99'          => '2,0,0,0,99',
    '2,3,0,3,99'          => '2,3,0,6,99',
    '2,4,4,5,99,0'        => '2,4,4,5,99,9801',
    '1,1,1,4,99,5,6,0,99' => '30,1,1,4,2,5,6,0,99',
];

// Step 1 resolution testing
foreach ($testStep as $input => $output) {
    if ($output !== implode(',', getUpdatedProgram($input))) {
        echo
        sprintf(
            'Output should\'ve been %s but you found %s',
            $output,
            getUpdatedProgram($input)
        );
    }
}


$array = explode(',', file_get_contents('input.txt'));

// Step 1
$updatedProgram = getUpdatedProgram(implode(',', updateValuesWithNounAndVerb(12, 2, $array)));
$output1        = reset($updatedProgram);

// Step 2
for ($noun = 0; $noun <= 99; $noun++) {
    $stop = false;
    $verb = 0;

    for ($verb = 0; $verb <= 99; $verb++) {
        $updatedProgram = getUpdatedProgram(implode(',', updateValuesWithNounAndVerb($noun, $verb, $array)));

        if (19690720 === $updatedProgram[0]) {
            $stop = true;
            break;
        }
    }

    if ($stop) {
        break;
    }
}

// Displaying the results
echo sprintf(
    'First step: new value at address 0 is %s. 100 * $noun (%s) + $verb (%s) = %s',
    $output1,
    $noun,
    $verb,
    (100 * $noun + $verb)
);
