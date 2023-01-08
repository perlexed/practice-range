<?php

use PHPUnit\Framework\TestCase;

require './BreakPieces.php';


class PolygonTest extends TestCase
{
    public function testSortingOnCreation()
    {
        $orderedPlanes = [
            new PolygonPlane(new Vertex(0, 1), new Vertex(1, 0, false)),
            new PolygonPlane(new Vertex(1, 0, false), new Vertex(1, 1)),
            new PolygonPlane(new Vertex(1, 1), new Vertex(0, 1)),
        ];

        $polygon = new Polygon($orderedPlanes);

        foreach ($polygon->planes as $planeIndex => $plane) {
            $this->assertTrue($plane->isEqualTo($orderedPlanes[$planeIndex]));
        }

        $unorderedPlanes = [
            new PolygonPlane(new Vertex(0, 1), new Vertex(1, 0, false)),
            new PolygonPlane(new Vertex(1, 1), new Vertex(0, 1)),
            new PolygonPlane(new Vertex(1, 0, false), new Vertex(1, 1)),
        ];

        $polygon = new Polygon($unorderedPlanes);
        $this->assertTrue($polygon->planes[0]->isEqualTo($unorderedPlanes[0]));
        $this->assertTrue($polygon->planes[1]->isEqualTo($unorderedPlanes[2]));
        $this->assertTrue($polygon->planes[2]->isEqualTo($unorderedPlanes[1]));
    }

    public function testPolygonEqualityComparison()
    {
        $equalPolygons = [
            new Polygon([
                new PolygonPlane(new Vertex(0, 0), new Vertex(1, 0)),
                new PolygonPlane(new Vertex(1, 0), new Vertex(1, 1)),
                new PolygonPlane(new Vertex(1, 1), new Vertex(0, 1, false)),
                new PolygonPlane(new Vertex(0, 1, false), new Vertex(0, 0)),
            ]),
            new Polygon([
                new PolygonPlane(new Vertex(1, 0), new Vertex(1, 1)),
                new PolygonPlane(new Vertex(0, 0), new Vertex(1, 0)),
                new PolygonPlane(new Vertex(1, 1), new Vertex(0, 1, false)),
                new PolygonPlane(new Vertex(0, 1, false), new Vertex(0, 0)),
            ]),
        ];

        $this->assertTrue($equalPolygons[0]->isEqualTo($equalPolygons[0]));
        $this->assertTrue($equalPolygons[0]->isEqualTo($equalPolygons[1]));
        $this->assertTrue($equalPolygons[1]->isEqualTo($equalPolygons[0]));

        $differentPolygons = [
            new Polygon([
                new PolygonPlane(new Vertex(0, 0), new Vertex(1, 0)),
                new PolygonPlane(new Vertex(1, 0), new Vertex(1, 1)),
                new PolygonPlane(new Vertex(1, 1), new Vertex(0, 1, false)),
                new PolygonPlane(new Vertex(0, 1, false), new Vertex(0, 0)),
            ]),
            new Polygon([
                new PolygonPlane(new Vertex(1, 0), new Vertex(1, 1)),
                new PolygonPlane(new Vertex(0, 0), new Vertex(1, 0)),
                new PolygonPlane(new Vertex(1, 1), new Vertex(0, 1, false)),
                new PolygonPlane(new Vertex(0, 1), new Vertex(0, 0)),
            ]),
        ];

        $this->assertTrue($differentPolygons[0]->isEqualTo($differentPolygons[0]));
        $this->assertFalse($differentPolygons[0]->isEqualTo($differentPolygons[1]));
        $this->assertFalse($differentPolygons[1]->isEqualTo($differentPolygons[0]));
    }

    public function testMergeByPlane()
    {
        $polygon1 = new Polygon([
            new PolygonPlane(new Vertex(0, 0), new Vertex(1, 0)),
            new PolygonPlane(new Vertex(1, 0), new Vertex(1, 1)),
            new PolygonPlane(new Vertex(1, 1), new Vertex(0, 1)),
            new PolygonPlane(new Vertex(0, 1), new Vertex(0, 0)),
        ]);
        $polygon2 = new Polygon([
            new PolygonPlane(new Vertex(0, 1), new Vertex(1, 1)),
            new PolygonPlane(new Vertex(1, 1), new Vertex(1, 2)),
            new PolygonPlane(new Vertex(1, 2), new Vertex(0, 2)),
            new PolygonPlane(new Vertex(0, 2), new Vertex(0, 1)),
        ]);
        $commonPlane = new PolygonPlane(new Vertex(0, 1), new Vertex(1, 1));

        $mergedPolygon = Polygon::mergeByPlane($polygon1, $polygon2, $commonPlane);

        $expectedPolygon = new Polygon([
            new PolygonPlane(new Vertex(0, 0), new Vertex(1, 0)),
            new PolygonPlane(new Vertex(1, 0), new Vertex(1, 2)),
            new PolygonPlane(new Vertex(1, 2), new Vertex(0, 2)),
            new PolygonPlane(new Vertex(0, 2), new Vertex(0, 0)),
        ]);

        $this->assertTrue($mergedPolygon->isEqualTo($expectedPolygon));
    }

    public function testAsciiPrinting()
    {
        $polygon1 = new Polygon([
            new PolygonPlane(new Vertex(0, 0), new Vertex(1, 0)),
            new PolygonPlane(new Vertex(1, 0), new Vertex(1, 1)),
            new PolygonPlane(new Vertex(1, 1), new Vertex(0, 1)),
            new PolygonPlane(new Vertex(0, 1), new Vertex(0, 0)),
        ]);

        $this->assertEquals("++\n++", $polygon1->getAsAscii());

        $polygon2 = new Polygon([
            new PolygonPlane(new Vertex(4, 1), new Vertex(6, 1)),
            new PolygonPlane(new Vertex(6, 1), new Vertex(6, 4)),
            new PolygonPlane(new Vertex(6, 4), new Vertex(4, 4)),
            new PolygonPlane(new Vertex(4, 4), new Vertex(4, 1)),
        ]);

        $this->assertEquals("+-+\n| |\n| |\n+-+", $polygon2->getAsAscii());
    }

    public function testMerging()
    {
        $polygon1 = new Polygon([
            new PolygonPlane(new Vertex(0, 0), new Vertex(1, 0)),
            new PolygonPlane(new Vertex(1, 0), new Vertex(1, 1, false)),
            new PolygonPlane(new Vertex(1, 1, false), new Vertex(0, 1, false)),
            new PolygonPlane(new Vertex(0, 1, false), new Vertex(0, 0)),
        ]);
        $polygon2 = new Polygon([
            new PolygonPlane(new Vertex(0, 1, false), new Vertex(1, 1, false)),
            new PolygonPlane(new Vertex(1, 1, false), new Vertex(1, 2)),
            new PolygonPlane(new Vertex(1, 2), new Vertex(0, 2)),
            new PolygonPlane(new Vertex(0, 2), new Vertex(0, 1, false)),
        ]);

        $mergedPolygons = mergePolygonsWithVirtualVertices([$polygon1, $polygon2]);

        $expectedPolygon = new Polygon([
            new PolygonPlane(new Vertex(0, 0), new Vertex(1, 0)),
            new PolygonPlane(new Vertex(1, 0), new Vertex(1, 2)),
            new PolygonPlane(new Vertex(1, 2), new Vertex(0, 2)),
            new PolygonPlane(new Vertex(0, 2), new Vertex(0, 0)),
        ]);

        $this->assertCount(1, $mergedPolygons);
        $this->assertTrue($expectedPolygon->isEqualTo($mergedPolygons[0]));
    }
}