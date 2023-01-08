<?php

use PHPUnit\Framework\TestCase;

require './BreakPieces.php';


class BreakPiecesTest extends TestCase
{
    /**
     * @test
     */
    public function simpleTest() {
        $shape = implode("\n", [
            "+------------+",
            "|            |",
            "|            |",
            "|            |",
            "+------+-----+",
            "|      |     |",
            "|      |     |",
            "+------+-----+",
        ]);
        $expected = [
            implode(
                "\n",
                [
                    "+------------+",
                    "|            |",
                    "|            |",
                    "|            |",
                    "+------------+"
                ]
            ),
            implode(
                "\n",
                [
                    "+------+",
                    "|      |",
                    "|      |",
                    "+------+"
                ]
            ),
            implode(
                "\n",
                [
                    "+-----+",
                    "|     |",
                    "|     |",
                    "+-----+"
                ]
            )
        ];
        $actual = (new BreakPieces())->process($shape);
        sort($actual);
        sort($expected);
        $this->assertEquals(json_encode($expected), json_encode($actual));
    }

    public function test2()
    {
        $shape = implode("\n", [
            "+-------------------+--+",
            "|                   |  |",
            "|                   |  |",
            "|  +----------------+  |",
            "|  |                   |",
            "|  |                   |",
            "+--+-------------------+",
        ]);

        $actual = (new BreakPieces())->process($shape);

        var_dump($actual);





    }

    public function testVerticesExtractor()
    {
        $sourceString = implode(
            "\n",
            [
                "+---+",
                "|   |",
                "|   |",
                "+---+"
            ]
        );

        $this->assertVerticesEqual(
            Vertex::createFromSimpleArray([
                [0, 0],
                [4, 0],
                [4, 3],
                [0, 3],
            ]),
            VerticesExtractor::extractVerticesFromPuzzle($sourceString),
        );
    }

    /**
     * @param Vertex[] $verticesExpected
     * @param Vertex[] $verticesActual
     * @return void
     */
    private function assertVerticesEqual(array $verticesExpected, array $verticesActual): void
    {
        $verticesExpectedStrings = Vertex::convertArrayToStrings($verticesExpected);
        $verticesActualStrings = Vertex::convertArrayToStrings($verticesActual);

        sort($verticesExpectedStrings);
        sort($verticesActualStrings);

        $this->assertEquals($verticesExpectedStrings, $verticesActualStrings);
    }

    public function testComplementWithVirtualVertices()
    {
        $this->assertVerticesEqual(
            Vertex::createFromSimpleArray([
                [0 ,0],
                [1 ,0],
                [0 ,2],
                [1 ,2, false],
            ]),
            complementPolygonsVerticesWithVirtualVertices(
                Vertex::createFromSimpleArray([
                    [0 ,0],
                    [1 ,0],
                    [0 ,2],
                ])
            ),
        );
    }

    public function testGetPolygonsFromVertices()
    {
        $vertices = Vertex::createFromSimpleArray([
            [0, 0],
            [1, 0],
            [1, 1],
            [0, 1, false],
            [1, 2],
            [0, 2],
        ]);

        $expectedPolygons = [
            new Polygon([
                new PolygonPlane(new Vertex(0, 0), new Vertex(1, 0)),
                new PolygonPlane(new Vertex(1, 0), new Vertex(1, 1)),
                new PolygonPlane(new Vertex(1, 1), new Vertex(0, 1, false)),
                new PolygonPlane(new Vertex(0, 1, false), new Vertex(0, 0)),
            ]),
            new Polygon([
                new PolygonPlane(new Vertex(0, 1, false), new Vertex(1, 1)),
                new PolygonPlane(new Vertex(1, 1), new Vertex(1, 2)),
                new PolygonPlane(new Vertex(1, 2), new Vertex(0, 2)),
                new PolygonPlane(new Vertex(0, 2), new Vertex(0, 1, false)),
            ]),
        ];

        $actualPolygons = getPolygonsFromVertices($vertices);

        $this->assertCount(count($expectedPolygons), $actualPolygons);

        foreach ($actualPolygons as $actualPolygonIndex => $actualPolygon) {
            $this->assertEquals($expectedPolygons[$actualPolygonIndex], $actualPolygon);
        }
    }

    public function testGetVirtualPlanes()
    {
        $polygons = [
            new Polygon([
                new PolygonPlane(new Vertex(0, 0), new Vertex(1, 0)),
                new PolygonPlane(new Vertex(0, 1, false), new Vertex(0, 0)),
            ]),
            new Polygon([
                new PolygonPlane(new Vertex(0, 0), new Vertex(1, 0)),
                new PolygonPlane(new Vertex(0, 1), new Vertex(0, 0)),
            ]),
            new Polygon([
                new PolygonPlane(new Vertex(0, 0, false), new Vertex(1, 0)),
                new PolygonPlane(new Vertex(0, 1), new Vertex(0, 0)),
            ]),
        ];

        $expectedVirtualPlanes = [
            new PolygonPlane(new Vertex(0, 1, false), new Vertex(0, 0)),
            new PolygonPlane(new Vertex(0, 0, false), new Vertex(1, 0)),
        ];

        $this->assertEquals($expectedVirtualPlanes, getVirtualPlanesOfPolygons($polygons));
    }
}