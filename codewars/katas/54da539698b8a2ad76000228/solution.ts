export function isValidWalk(walk: string[]) {
    const allowedTimeCount = 10;

    if (walk.length !== allowedTimeCount) {
        return false;
    }

    type Coordinates = {
        x: number,
        y: number,
    };

    const currentCoordinates: Coordinates = {
        x: 0,
        y: 0,
    };
    const directionsToCoordinatesMap: Record<string, Coordinates> = {
        n: {x: 1, y: 0},
        s: {x: -1, y: 0},
        w: {x: 0, y: -1},
        e: {x: 0, y: 1},
    };

    walk.forEach(direction => {
        const stepIncrement: Coordinates = directionsToCoordinatesMap[direction];
        currentCoordinates.x += stepIncrement.x;
        currentCoordinates.y += stepIncrement.y;
    });

    return currentCoordinates.x === 0 && currentCoordinates.y === 0;
}