<?php

use PHPUnit\Framework\TestCase;

require './BreakPieces.php';


class VertexText extends TestCase
{
    public function testToStringConversion()
    {
        $this->assertEquals(
            [
                '[0,1]',
                '[4,5]',
                '(3,3)',
            ],
            Vertex::convertArrayToStrings(
                Vertex::createFromSimpleArray([
                    [0, 1],
                    [4, 5],
                    [3, 3, false],
                ])
            ),
        );
    }

    public function testCreationFromArray()
    {
        $this->assertEquals(
            [
                new Vertex(2, 3),
                new Vertex(1, 2),
                new Vertex(3, 4, false),
            ],
            Vertex::createFromSimpleArray([
                [2, 3],
                [1, 2, true],
                [3, 4, false],
            ]),
        );
    }

    public function testEqualityCheck()
    {
        $equalVertices = Vertex::createFromSimpleArray([
            [1, 2, false],
            [1, 2, false],
        ]);
        $this->assertTrue($equalVertices[0]->isEqualTo($equalVertices[1]));

        $nonEqualByRealVertices = Vertex::createFromSimpleArray([
            [1, 2, false],
            [1, 2],
        ]);
        $this->assertFalse($nonEqualByRealVertices[0]->isEqualTo($nonEqualByRealVertices[1]));

        $nonEqualByCoordinateVertices = Vertex::createFromSimpleArray([
            [1, 2, false],
            [1, 3, false],
        ]);
        $this->assertFalse($nonEqualByCoordinateVertices[0]->isEqualTo($nonEqualByCoordinateVertices[1]));
    }
}
