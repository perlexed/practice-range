<?php

use PHPUnit\Framework\TestCase;

/**
 * @link https://www.codewars.com/kata/52d1bd3694d26f8d6e0000d3/solutions/php
 */
class VigenereCipher {

    private string $key;
    private string $alphabet;

    public function __construct($key, $alphabet) {
        $this->key = $key;
        $this->alphabet = $alphabet;
    }

    public function encode(string $s): string
    {
        return $this->convertString($s, false);
    }

    public function decode(string $s): string {
        return $this->convertString($s, true);
    }

    private function convertString(string $inputString, bool $isDecode): string
    {
        $multipliedKeyString = $this->key;
        while (strlen($multipliedKeyString) < strlen($inputString)) {
            $multipliedKeyString .= $this->key;
        }

        $encoded = '';

        foreach (str_split($inputString) as $letterIndex => $letter) {
            if (strpos($this->alphabet, $letter) === false) {
                $encoded .= $letter;
                continue;
            }
            $keyLetter = $multipliedKeyString[$letterIndex];
            $keyLetterIndex = strpos($this->alphabet, $keyLetter);
            $sourceLetterIndex = strpos($this->alphabet, $letter);

            $alphabetLength = strlen($this->alphabet);

            if ($isDecode) {
                $encodedLetterIndex = $sourceLetterIndex - $keyLetterIndex;
                if ($encodedLetterIndex < 0) {
                    $encodedLetterIndex += $alphabetLength;
                }
            } else {
                $encodedLetterIndex = $keyLetterIndex + $sourceLetterIndex;
                if ($encodedLetterIndex >= $alphabetLength) {
                    $encodedLetterIndex -= $alphabetLength;
                }
            }

            $encoded .= $this->alphabet[$encodedLetterIndex];
        }

        return $encoded;
    }
}
class VigenereCipherTest extends TestCase {
    public function test1() {
        $c = new VigenereCipher('password', 'abcdefghijklmnopqrstuvwxyz');

        // a b c d e f g h i j k  l  m  n  o  p  q  r  s  t  u  v  w  x  y  z
        // 0 1 2 3 4 5 6 7 8 9 10 11 12 13 14 15 16 17 18 19 20 21 22 23 24 25


        $this->assertEquals('rovwsoiv', $c->encode('codewars'));
        $this->assertEquals('codewars', $c->decode('rovwsoiv'));

        $this->assertEquals('laxxhsj', $c->encode('waffles'));
        $this->assertEquals('waffles', $c->decode('laxxhsj'));

        $this->assertEquals('CODEWARS', $c->encode('CODEWARS'));
        $this->assertEquals('CODEWARS', $c->decode('CODEWARS'));
    }
}
