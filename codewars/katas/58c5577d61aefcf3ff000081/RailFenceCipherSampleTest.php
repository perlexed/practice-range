<?php

/**
 * @link https://www.codewars.com/kata/58c5577d61aefcf3ff000081/solutions/php
 */

function getNextRailId(&$currentRailId, &$isDirectionDown, $numberRails) {
    $isMaxRail = ($isDirectionDown && $currentRailId === ($numberRails - 1))
        || (!$isDirectionDown && $currentRailId === 0);

    if ($isMaxRail) {
        $isDirectionDown = !$isDirectionDown;
    }

    $currentRailId = $isDirectionDown ? $currentRailId + 1 : $currentRailId - 1;
}

function getRailsStrings(string $string, int $numberRails): array
{
    $railsStrings = array_fill(0, $numberRails, '');

    $currentRailId = 0;
    $isDirectionDown = true;

    foreach (str_split($string) as $letter) {
        $railsStrings[$currentRailId] .= $letter;
        getNextRailId($currentRailId, $isDirectionDown, $numberRails);
    }

    return $railsStrings;
}

function encodeRailFenceCipher(string $string, int $numberRails): string
{
    return join('', getRailsStrings($string, $numberRails));
}

function decodeRailFenceCipher(string $string, int $numberRails): string
{
    $sourceStringLength = strlen($string);

    if (!$sourceStringLength) {
        return '';
    }

    $railsStrings = getRailsStrings($string, $numberRails);
    $railsStringsLengths = array_map(fn($railString) => strlen($railString), $railsStrings);

    $encryptedRailsStrings = array_map(function ($stringLength) use (&$string) {
        $encryptedRailString = substr($string, 0, $stringLength);
        $string = substr_replace($string, '', 0, $stringLength);
        return $encryptedRailString;
    }, $railsStringsLengths);

    $decodedString = '';
    $currentRailId = 0;
    $isDirectionDown = true;

    do {
        $encryptedRailString = $encryptedRailsStrings[$currentRailId];
        $decodedString .= $encryptedRailString[0];
        $encryptedRailsStrings[$currentRailId] = substr_replace($encryptedRailString, '', 0, 1);

        getNextRailId($currentRailId, $isDirectionDown, $numberRails);
    } while (strlen($decodedString) < $sourceStringLength);

    return $decodedString;
}


class RailFenceCipherSampleTest extends PHPUnit\Framework\TestCase {
    public function testSample() {
        $this->assertEquals("Hoo!el,Wrdl l", encodeRailFenceCipher("Hello, World!", 3));
        $this->assertEquals("Hello, World!", decodeRailFenceCipher("Hoo!el,Wrdl l", 3));

        $this->assertEquals(encodeRailFenceCipher("", 3), "");
        $this->assertEquals(decodeRailFenceCipher("", 3), "");

        $this->assertEquals(encodeRailFenceCipher("WEAREDISCOVEREDFLEEATONCE", 3), "WECRLTEERDSOEEFEAOCAIVDEN");
        $this->assertEquals(decodeRailFenceCipher("WECRLTEERDSOEEFEAOCAIVDEN", 3), "WEAREDISCOVEREDFLEEATONCE");
    }
}
