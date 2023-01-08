<?php

use PHPUnit\Framework\TestCase;

require './BreakPieces.php';


class PlaneTest extends TestCase
{
    public function testEqualityCheck()
    {
        $equalPlanes = [
            new PolygonPlane(new Vertex(0, 1, false), new Vertex(0, 2)),
            new PolygonPlane(new Vertex(0, 2), new Vertex(0, 1, false)),
        ];
        $this->assertTrue($equalPlanes[0]->isEqualTo($equalPlanes[1]));

        $nonEqualPlanes = [
            new PolygonPlane(new Vertex(0, 1, false), new Vertex(0, 2)),
            new PolygonPlane(new Vertex(1, 2), new Vertex(0, 1, false)),
        ];
        $this->assertFalse($nonEqualPlanes[0]->isEqualTo($nonEqualPlanes[1]));
    }

    public function testHasVertexCheck()
    {
        $plane = new PolygonPlane(new Vertex(0, 1, false), new Vertex(0, 2));

        $this->assertTrue($plane->hasVertex(new Vertex(0, 2)));
        $this->assertFalse($plane->hasVertex(new Vertex(0, 0)));
    }

    public function testIsVertical()
    {
        $plane = new PolygonPlane(new Vertex(0, 1, false), new Vertex(0, 2));
        $this->assertTrue($plane->isVertical());

        $plane = new PolygonPlane(new Vertex(1, 1, false), new Vertex(0, 2));
        $this->assertFalse($plane->isVertical());
    }

    public function testHasPointCheck()
    {
        $plane = new PolygonPlane(new Vertex(0, 0), new Vertex(0, 2));
        $this->assertTrue($plane->hasPoint(0, 0));
        $this->assertTrue($plane->hasPoint(0, 1));
        $this->assertFalse($plane->hasPoint(0, 3));

        $plane = new PolygonPlane(new Vertex(0, 0), new Vertex(2, 0));
        $this->assertTrue($plane->hasPoint(1, 0));
        $this->assertFalse($plane->hasPoint(3, 0));
    }
}