export function findMedianSortedArrays(nums1: number[], nums2: number[]): number {
    const nums1length = nums1.length;
    const nums2length = nums2.length;

    // Helper function to process degenerate case when one array is empty
    const getMedianOfArray = function (targetArray: number[]): number {
        const targetArrayLength = targetArray.length;

        if (targetArrayLength === 1) {
            return targetArray[0];
        }

        const halfArrayLength = targetArrayLength / 2;
        const isOdd = halfArrayLength % 1 > 0;

        if (isOdd) {
            return targetArray[Math.floor(halfArrayLength)];
        }

        const middleElements = [
            targetArray[halfArrayLength - 1],
            targetArray[halfArrayLength],
        ];

        return (middleElements[0] + middleElements[1]) / 2;
    }

    if (nums1length === 0 && nums2length === 0) {
        throw Error('there should be at least 1 element in any array');
    } else if (nums1length === 0) {
        return getMedianOfArray(nums2);
    } else if (nums2length === 0) {
        return getMedianOfArray(nums1);
    }

    const halfTotalLength = (nums1length + nums2length) / 2;
    const isTotalLengthOdd = halfTotalLength % 1 > 0;
    const numberOfElementsToCheck = isTotalLengthOdd
        ? Math.ceil(halfTotalLength)
        : halfTotalLength + 1;

    // These are init values, which will be overwritten
    let previous = 0;
    let current = 0;

    for (
        let mergedArrayIndex = 0;
        mergedArrayIndex < numberOfElementsToCheck;
        mergedArrayIndex++
    ) {
        previous = current

        if (nums1.length === 0) {
            current = nums2.shift() ?? 0;
        } else if (nums2.length === 0) {
            current = nums1.shift() ?? 0
        } else {
            current = nums1[0] <= nums2[0]
                ? nums1.shift() ?? 0
                : nums2.shift() ?? 0;
        }
    }
    return isTotalLengthOdd
        ? current
        : (previous + current) / 2;
}
