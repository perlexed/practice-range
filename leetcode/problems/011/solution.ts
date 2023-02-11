export function maxArea(height: number[]): number {
    const sortedHeights = [...height].sort((a, b) => a - b);
    const lastToHighestElement = sortedHeights[sortedHeights.length - 2];
    const minVolume = Math.min(
        sortedHeights[sortedHeights.length - 1],
        lastToHighestElement,
    );

    let currentRangeToCheck = height.length - 1;
    let maxVolume = 0;

    while (currentRangeToCheck >= 1) {
        if (currentRangeToCheck * minVolume < maxVolume) {
            break;
        }
        for (
            let rangeStartIndex = 0;
            rangeStartIndex + currentRangeToCheck < height.length;
            rangeStartIndex++
        ) {
            const currentHeight = Math.min(height[rangeStartIndex], height[rangeStartIndex + currentRangeToCheck]);
            const currentVolume = currentHeight * currentRangeToCheck;
            if (currentVolume > maxVolume) {
                maxVolume = currentVolume;
            }

        }
        currentRangeToCheck -= 1;
    }

    return maxVolume;
}
