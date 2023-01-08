
export type Partition = number[][];
type PartitionProducts = number[];
type PartitionProductsStats = {
    range: number,
    average: string,
    median: string,
};


/**
 * @example for (11,3) will return [3, 3, 3, 2]
 * @param number
 * @param maxAddend
 */
const splitNumberByAddend = function (number: number, maxAddend: number): number[]
{
    if (maxAddend === 0 || number === 0) {
        throw new Error('You cannot pass zero in splitNumberByAddend()');
    }

    if (number < maxAddend) {
        throw new Error('Addend should be lesser than the number to split');
    }

    const multiplier = Math.floor(number / maxAddend);

    return [
        ...Array(multiplier).fill(maxAddend),
        number - maxAddend * multiplier,
    ].filter((addend: number) => addend > 0)
}

/**
 * https://www.codewars.com/kata/55cf3b567fc0e02b0b00000b/train/typescript
 */
export class G964 {
    public static part = (n: number): string => {
        const partition = G964.getPartition(n);
        const partitionProducts = G964.getPartitionProducts(partition);
        const stats = G964.getPartitionProductsStats(partitionProducts);

        return `Range: ${stats.range} Average: ${stats.average} Median: ${stats.median}`;
    }

    private static partitionsCache: {[contextNumber: number]: Partition} = {};

    private static getPartition(number: number): Partition
    {
        // Check cache first
        if (Object.keys(G964.partitionsCache).includes(number.toString())) {
            return G964.partitionsCache[number];
        }

        // Return basic cases so we don't have to add checks for these numbers later
        switch (number) {
            case 1:
                return [[1]];
            case 2:
                return [[2], [1, 1]];
        }

        // Add trivial addends right away
        const partition: Partition = [
            Array(number).fill(1),
            [number],
        ];

        for (let maxAddend = 2; maxAddend < number; maxAddend++) {
            const addendPartition = G964.getPartitionElementsByAddend(number, maxAddend);
            addendPartition.forEach(partitionElement => {
                partition.push(partitionElement);
            });
        }

        return partition;
    }

    private static getPartitionElementsByAddend(number: number, addend: number): number[][]
    {
        const splitByMaxAddends = splitNumberByAddend(number, addend);
        const partition: number[][] = [];

        const splitByMaxAddendsNoHead = splitByMaxAddends.slice(1);

        const addendsPartitions = splitByMaxAddendsNoHead.map(addend => G964.getPartition(addend));

        const addendsPartitionsCombinations = getCombinations(addendsPartitions);

        const flattenedAndSortedCombinations = addendsPartitionsCombinations
            .map(combination => combination.reduce((flattened, combinationElement) => flattened.concat(combinationElement), []))
            .map(combination => combination.sort((a, b) => b - a));

        const uniqueCombinations = flattenedAndSortedCombinations.reduce((uniqueCombinations: number[][], contextCombination) => {
            const identicalCombination = uniqueCombinations.find(combination => areArraysEqual(combination, contextCombination));
            if (!identicalCombination) {
                uniqueCombinations.push(contextCombination);
            }
            return uniqueCombinations;
        }, []);

        uniqueCombinations.forEach(uniqueCombination => {
            partition.push([addend, ...uniqueCombination]);
        });

        return partition;
    }


    private static getPartitionProducts(partition: Partition): PartitionProducts {
        return partition
            .map(partitionElement => {
                return partitionElement.reduce((product, partitionElementNumber) => product * partitionElementNumber, 1);
            })
            .filter(function(elem, pos,arr) {
                return arr.indexOf(elem) == pos;
            })
            .sort((a, b) => a - b);
    }

    private static getPartitionProductsStats(partitionProducts: PartitionProducts): PartitionProductsStats
    {
        const productsSum = partitionProducts.reduce((sum, product) => sum + product, 0);

        return {
            average: (productsSum / partitionProducts.length).toFixed(2),
            median: getMedianForArray(partitionProducts).toFixed(2),
            range: partitionProducts[partitionProducts.length - 1] - partitionProducts[0],
        };
    }
}

const areArraysEqual = function (array1: number[], array2: number[]): boolean
{
    if (array1.length !== array2.length) {
        return false;
    }

    for (let index = 0; index < array1.length; index++) {
        if (array1[index] !== array2[index]) {
            return false;
        }
    }

    return true;
}

const getMedianForArray = function(values: number[]) {
    if(values.length ===0) return 0;

    values.sort(function(a,b){
        return a-b;
    });

    var half = Math.floor(values.length / 2);

    if (values.length % 2)
        return values[half];

    return (values[half - 1] + values[half]) / 2.0;
};

/**
 * From the given sets of elements get all the combination of the sets elements
 * @example see tests
 * @param sets
 */
export function getCombinations<T>(sets: T[][]): T[][]
{
    type SetElementCounter = {
        currentIndex: number,
        maxIndex: number,
    }

    const setCounters: SetElementCounter[] = sets.map(set => ({
        currentIndex: 0,
        maxIndex: set.length - 1,
    }));

    const setsCombinations: T[][] = [];

    const increaseCounter = function (): boolean {
        for (let partitionIndexId = 0; partitionIndexId < setCounters.length; partitionIndexId++)
        {
            const currentPartitionIndex = setCounters[partitionIndexId];
            if (currentPartitionIndex.currentIndex < currentPartitionIndex.maxIndex) {
                currentPartitionIndex.currentIndex++;
                return true;
            }

            const isNextPartitionIndexExists = partitionIndexId < setCounters.length - 1;

            if (!isNextPartitionIndexExists) {
                return false;
            }

            currentPartitionIndex.currentIndex = 0;
        }

        return true;
    }

    const getCombinationByIndices = function (): T[]
    {
        return setCounters.reduce((combination: T[], setCounter: SetElementCounter, index: number) => {
            const currentSet = sets[index];
            const currentSetElement = currentSet[setCounter.currentIndex];
            combination.push(currentSetElement);

            return combination;
        }, []);
    }

    let areAllIndicesMaxed = false;

    do {
        const combination = getCombinationByIndices();
        setsCombinations.push(combination);
        areAllIndicesMaxed = !increaseCounter();
    } while (!areAllIndicesMaxed)

    return setsCombinations;
}