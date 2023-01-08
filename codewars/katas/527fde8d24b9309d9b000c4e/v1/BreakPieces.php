<?php

const VERTEX_SYMBOL = '+';

class Vertex
{
    public int $x;
    public int $y;
    public bool $isReal;

    public function __construct(int $x, int $y, bool $isReal = true)
    {
        $this->x = $x;
        $this->y = $y;
        $this->isReal = $isReal;
    }

    public function __toString(): string
    {
        return $this->isReal
            ? "[$this->x,$this->y]"
            : "($this->x,$this->y)";
    }

    /**
     * @param Vertex[] $vertices
     * @return string[]
     */
    public static function convertArrayToStrings(array $vertices): array
    {
        return array_map(
            fn ($vertex) => (string) $vertex,
            $vertices
        );
    }

    /**
     * @example [ [0, 3], [2, 3, false] ] => [ new Vertex(0, 3), new Vertex(2, 3) ]
     * @param array $verticesAsArrays
     * @return self[]
     */
    public static function createFromSimpleArray(array $verticesAsArrays): array
    {
        return array_map(
            fn ($vertexAsArray) => new self($vertexAsArray[0], $vertexAsArray[1], $vertexAsArray[2] ?? true),
            $verticesAsArrays,
        );
    }

    public function isEqualTo(Vertex $vertex): bool
    {
        return $this->x === $vertex->x && $this->y === $vertex->y && $this->isReal === $vertex->isReal;
    }
}


class VerticesExtractor
{
    /**
     * @param string $puzzleAsString
     * @return Vertex[]
     */
    public static function extractVerticesFromPuzzle(string $puzzleAsString): array
    {
        $vertices = [];

        $puzzleLines = explode("\n", $puzzleAsString);

        foreach ($puzzleLines as $yIndex => $puzzleLine) {
            foreach (str_split($puzzleLine) as $xIndex => $puzzleSymbol) {
                if ($puzzleSymbol === VERTEX_SYMBOL) {
                    $vertices[] = new Vertex($xIndex, $yIndex);
                }
            }
        }

        return $vertices;
    }
}


class PolygonPlane
{
    public Vertex $vertex1;
    public Vertex $vertex2;

    public function __construct(Vertex $vertex1, Vertex $vertex2)
    {
        $vertices = [
            $vertex1,
            $vertex2,
        ];

        usort($vertices, function (Vertex $vertex1, Vertex $vertex2): int {
            $differenceByX = $vertex1->x - $vertex2->x;
            if ($differenceByX !== 0) {
                return $differenceByX;
            }

            return $vertex1->y - $vertex2->y;
        });

        $this->vertex1 = $vertices[0];
        $this->vertex2 = $vertices[1];
    }

    public function __toString(): string
    {
        return "<$this->vertex1,$this->vertex2>";
    }

    public function isEqualTo(PolygonPlane $plane): bool
    {
        return $this->vertex1->isEqualTo($plane->vertex1) && $this->vertex2->isEqualTo($plane->vertex2);
    }

    public function hasVertex(Vertex $vertex): bool
    {
        return $this->vertex1->isEqualTo($vertex) || $this->vertex2->isEqualTo($vertex);
    }

    public function isVertical(): bool
    {
        return $this->vertex1->x === $this->vertex2->x;
    }

    public function hasPoint(int $x, int $y): bool
    {
        if ($this->isVertical()) {
            $minY = min($this->vertex1->y, $this->vertex2->y);
            $maxY = max($this->vertex1->y, $this->vertex2->y);
            return $this->vertex1->x === $x
                && $y <= $maxY
                && $y >= $minY;
        } else {
            $minX = min($this->vertex1->x, $this->vertex2->x);
            $maxX = max($this->vertex1->x, $this->vertex2->x);
            return $this->vertex1->y === $y
                && $x <= $maxX
                && $x >= $minX;
        }
    }
}


class Polygon
{
    /**
     * @var PolygonPlane[]
     */
    public array $planes;

    /**
     * @param PolygonPlane[] $planes
     * @throws Exception
     */
    public function __construct(array $planes)
    {
        $this->planes = $this->sortPlanesAsChain($planes);
    }

    /**
     * @param PolygonPlane[] $planes
     * @return PolygonPlane[]
     * @throws Exception
     */
    private function sortPlanesAsChain(array $planes): array
    {
        $firstRandomPlane = $planes[0];
        $firstRandomVertex = $firstRandomPlane->vertex2;
        $planesInChain = [$firstRandomPlane];

        $planesNotInChain = array_values(
            array_filter(
                $planes,
                fn($plane) => !$firstRandomPlane->isEqualTo($plane),
            )
        );

        $lastPlaneInChain = $firstRandomPlane;
        $lastVertexInChain = $firstRandomVertex;

        do {
            $adjacentPlanes = [];

            foreach ($planesNotInChain as $planeIndex => $plane) {
                if ($plane->vertex1->isEqualTo($lastVertexInChain) || $plane->vertex2->isEqualTo($lastVertexInChain)) {
                    $adjacentPlanes[] = $plane;
                    array_splice($planesNotInChain, $planeIndex, 1);
                    break;
                }
            }

            if (count($adjacentPlanes) !== 1) {
                throw new Exception("Plane $lastPlaneInChain with vertex $lastVertexInChain has not 1, but " . count($adjacentPlanes) . " adjacent planes");
            }

            $lastPlaneInChain = $adjacentPlanes[0];
            $planesInChain[] = $lastPlaneInChain;
            $lastVertexInChain = $lastPlaneInChain->vertex1->isEqualTo($lastVertexInChain)
                ? $lastPlaneInChain->vertex2
                : $lastPlaneInChain->vertex1;
        } while (count($planesNotInChain));

        return $planesInChain;
    }

    public function __toString()
    {
        $planesAsStrings = array_map(
            fn($plane) => (string)$plane,
            $this->planes,
        );

        sort($planesAsStrings, SORT_STRING);

        return "{" . join(',', $planesAsStrings) . "}";
    }

    public function hasPlane(PolygonPlane $targetPlane): bool
    {
        foreach ($this->planes as $plane) {
            if ((string)$plane === (string)$targetPlane) {
                return true;
            }
        }

        return false;
    }

    public function isEqualTo(self $polygon): bool
    {
        $selfPlanes = array_map(
            fn($plane) => (string)$plane,
            $this->planes,
        );

        $targetPlanes = array_map(
            fn($plane) => (string)$plane,
            $polygon->planes,
        );

        return array_diff($selfPlanes, $targetPlanes) === array_diff($targetPlanes, $selfPlanes);
    }

    public static function mergeByPlane(self $polygon1, self $polygon2, PolygonPlane $commonPlane): self
    {
        $allPolygonsPlanes = array_merge($polygon1->planes, $polygon2->planes);

        $mergedPolygonPlanes = array_values(array_filter(
            $allPolygonsPlanes,
            function ($plane) use ($commonPlane) {
                return !$plane->hasVertex($commonPlane->vertex1) && !$plane->hasVertex($commonPlane->vertex2);
            }
        ));

        foreach ([$commonPlane->vertex1, $commonPlane->vertex2] as $jointVertex) {
            $jointPlanes = array_values(array_filter(
                $allPolygonsPlanes,
                function ($plane) use ($jointVertex, $commonPlane) {
                    return $plane->hasVertex($jointVertex) && !$plane->isEqualTo($commonPlane);
                }
            ));

            if (count($jointPlanes) !== 2) {
                throw new Exception("For the $jointVertex there must be 2 joint planes, but got " . count($jointPlanes));
            }

            $jointPlanesVertices = [
                $jointPlanes[0]->vertex1,
                $jointPlanes[0]->vertex2,
                $jointPlanes[1]->vertex1,
                $jointPlanes[1]->vertex2,
            ];

            /** @var Vertex[] $newPlaneVertices */
            $newPlaneVertices = array_values(array_filter(
                $jointPlanesVertices,
                fn(Vertex $vertex) => !$vertex->isEqualTo($jointVertex),
            ));

            if (count($newPlaneVertices) !== 2) {
                throw new Exception("New plane for $jointVertex must have 2 vertices, but got " . count($newPlaneVertices));
            }

            $mergedPolygonPlanes[] = new PolygonPlane($newPlaneVertices[0], $newPlaneVertices[1]);
        }

        return new Polygon($mergedPolygonPlanes);
    }

    public function getAsAscii($padRelative = false): string
    {
        $vertexToStartMinmaxingWith = $this->planes[0]->vertex1;
        $minX = $vertexToStartMinmaxingWith->x;
        $minY = $vertexToStartMinmaxingWith->y;
        $maxX = $vertexToStartMinmaxingWith->x;
        $maxY = $vertexToStartMinmaxingWith->y;

        foreach ($this->planes as $plane) {
            foreach ([$plane->vertex1, $plane->vertex2] as $vertex) {
                /** @var Vertex $vertex */
                if ($vertex->x < $minX) {
                    $minX = $vertex->x;
                } elseif ($vertex->x > $maxX) {
                    $maxX = $vertex->x;
                }

                if ($vertex->y < $minY) {
                    $minY = $vertex->y;
                } elseif ($vertex->y > $maxY) {
                    $maxY = $vertex->y;
                }
            }
        }

        $polygonRows = [];

        $getSymbolForPointOnPlane = function (int $x, int $y, PolygonPlane $plane): string {
            foreach ([$plane->vertex1, $plane->vertex2] as $vertex) {
                /** @var Vertex $vertex */
                $isPointOnVertex = $vertex->x === $x && $vertex->y === $y;
                if ($isPointOnVertex) {
                    return $vertex->isReal ? '+' : '*';
                }
            }

            if ($plane->isVertical()) {
                return '|';
            }

            return '-';
        };

        for ($y = $minY; $y <= $maxY; $y++) {
            $polygonRowSymbols = [];
            for ($x = $minX; $x <= $maxX; $x++) {
                $polygonRowSymbol = ' ';
                foreach ($this->planes as $plane) {
                    if ($plane->hasPoint($x, $y)) {
                        $polygonRowSymbol = $getSymbolForPointOnPlane($x, $y, $plane);
                        break;
                    }
                }
                $polygonRowSymbols[] = $polygonRowSymbol;
            }
            $rowString = rtrim(join('', $polygonRowSymbols));
            $polygonRows[] = $padRelative
                ? BreakPieces . phpstr_repeat(' ', $minX)
                : $rowString;
        }

        $verticalPadding = $padRelative
            ? str_repeat("\n", $minY)
            : '';

        return $verticalPadding . join("\n", $polygonRows);
    }
}

/**
 * @param Vertex[] $vertices
 * @return Vertex[] vertices with virtual vertices
 */
function complementPolygonsVerticesWithVirtualVertices(array $vertices): array
{
    $xCoordinates = [];
    $yCoordinates = [];

    foreach ($vertices as $vertex) {
        if (!in_array($vertex->x, $xCoordinates)) {
            $xCoordinates[] = $vertex->x;
        }
        if (!in_array($vertex->y, $yCoordinates)) {
            $yCoordinates[] = $vertex->y;
        }
    }

    $realVerticesAsStrings = array_map(
        fn ($vertex) => (string) $vertex,
        $vertices,
    );

    $virtualVertices = [];

    foreach ($yCoordinates as $yCoordinate) {
        foreach ($xCoordinates as $xCoordinate) {
            $vertex = new Vertex($xCoordinate, $yCoordinate);
            if (!in_array((string) $vertex, $realVerticesAsStrings)) {
                $vertex->isReal = false;
                $virtualVertices[] = $vertex;
            }
        }
    }

    return array_merge($vertices, $virtualVertices);
}

/**
 * @param Vertex[] $vertices
 * @return Polygon[]
 */
function getPolygonsFromVertices(array $vertices): array
{
    /** @var Polygon[] $polygons */
    $polygons = [];

    $xCoordinates = [];
    $yCoordinates = [];

    foreach ($vertices as $vertex) {
        if (!in_array($vertex->x, $xCoordinates)) {
            $xCoordinates[] = $vertex->x;
        }
        if (!in_array($vertex->y, $yCoordinates)) {
            $yCoordinates[] = $vertex->y;
        }
    }

    sort($xCoordinates);
    sort($yCoordinates);

    $getVertexByCoordinates = function (int $x, int $y) use ($vertices) {
        foreach ($vertices as $vertex) {
            if ($vertex->x === $x && $vertex->y === $y) {
                return $vertex;
            }
        }

        return null;
    };

    for ($xCoordinateIndex = 0; $xCoordinateIndex < count($xCoordinates) - 1; $xCoordinateIndex++) {
        for ($yCoordinateIndex = 0; $yCoordinateIndex < count($yCoordinates) - 1; $yCoordinateIndex++) {
            $vertex1 = $getVertexByCoordinates($xCoordinates[$xCoordinateIndex], $yCoordinates[$yCoordinateIndex]);
            $vertex2 = $getVertexByCoordinates($xCoordinates[$xCoordinateIndex + 1], $yCoordinates[$yCoordinateIndex]);
            $vertex3 = $getVertexByCoordinates($xCoordinates[$xCoordinateIndex + 1], $yCoordinates[$yCoordinateIndex + 1]);
            $vertex4 = $getVertexByCoordinates($xCoordinates[$xCoordinateIndex], $yCoordinates[$yCoordinateIndex + 1]);

            $polygons[] = new Polygon([
                new PolygonPlane($vertex1, $vertex2),
                new PolygonPlane($vertex2, $vertex3),
                new PolygonPlane($vertex3, $vertex4),
                new PolygonPlane($vertex4, $vertex1),
            ]);
        }
    }

    return $polygons;
}


/**
 * @param Polygon[] $polygons
 * @return PolygonPlane[]
 */
function getVirtualPlanesOfPolygons(array $polygons): array
{
    $virtualPlanes = [];

    foreach ($polygons as $polygon) {
        foreach ($polygon->planes as $plane) {
            if (!$plane->vertex1->isReal || !$plane->vertex2->isReal) {
                $virtualPlanes[] = $plane;
            }
        }
    }

    return $virtualPlanes;
}


/**
 * @param Polygon[] $polygons
 * @return Polygon[]
 */
function mergePolygonsWithVirtualVertices(array $polygons): array
{
    do {
        foreach ($polygons as $polygon) {
            echo "\n--------polygon start---------\n" . $polygon->getAsAscii(true) . "\n--------polygon end---------\n";
        }
        $virtualPlanes = getVirtualPlanesOfPolygons($polygons);
        $virtualPlanesExist = count($virtualPlanes) > 0;
        foreach ($virtualPlanes as $plane) {
            echo "$plane\n";
        }

        foreach ($virtualPlanes as $virtualPlane) {
            $polygonsWithPlane = [];
            $polygonsWithoutPlane = [];

            foreach ($polygons as $polygon) {
                if ($polygon->hasPlane($virtualPlane)) {
                    $polygonsWithPlane[] = $polygon;
                } else {
                    $polygonsWithoutPlane[] = $polygon;
                }
            }

            if (count($polygonsWithPlane) === 2) {
                $mergedPolygon = Polygon::mergeByPlane($polygonsWithPlane[0], $polygonsWithPlane[1], $virtualPlane);
                $polygons = array_merge(
                    $polygonsWithoutPlane,
                    [$mergedPolygon],
                );
                break;
            }

        }
    } while ($virtualPlanesExist);

    return $polygons;
}


class BreakPieces
{
    public function process($shape) {
        echo "Extracting vertices...\n";
        $puzzleVertices = VerticesExtractor::extractVerticesFromPuzzle($shape);
        echo "Complementing vertices...\n";
        $realAndVirtualVertices = complementPolygonsVerticesWithVirtualVertices($puzzleVertices);
        echo "Creating polygons...\n";
        $allPolygons = getPolygonsFromVertices($realAndVirtualVertices);

//        foreach ($allPolygons as $polygon) {
////            echo "$polygon\n";
//            echo $polygon->getAsAscii() . "\n\n";
//        }

//        return [];

        echo "Merging polygons...\n";
        $realPolygons = mergePolygonsWithVirtualVertices($allPolygons);

        return array_map(
            fn (Polygon $polygon) => $polygon->getAsAscii(),
            $realPolygons,
        );
    }
}