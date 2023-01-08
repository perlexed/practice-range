
export type Partition = number[][];
type PartitionProducts = number[];
type PartitionProductsStats = {
    range: number,
    average: string,
    median: string,
};


/**
 * https://www.codewars.com/kata/55cf3b567fc0e02b0b00000b/train/typescript
 */
export class G964
{
    private static partitionsCache: {[key: string]: Partition} = {};

    public static part = (n: number): string => {
        const partition = G964.getPartition(n);
        const partitionProducts = G964.getPartitionProducts(partition);
        const stats = G964.getPartitionProductsStats(partitionProducts);

        return `Range: ${stats.range} Average: ${stats.average} Median: ${stats.median}`;
    }

    private static getPartition(number: number): Partition
    {
        const numberKey = number.toString();
        if (Object.keys(G964.partitionsCache).indexOf(numberKey) !== -1) {
            return G964.partitionsCache[numberKey];
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

        // Since we checked for 1 and 2 numbers, we're sure that the number is greater than 2
        for (let maxAddend = 2; maxAddend < number; maxAddend++) {
            const remainingNumber = number - maxAddend;
            const addendPartition = G964.getPartition(remainingNumber)
                .filter(partitionElement => partitionElement[0] <= maxAddend)
                .map(partitionElement => [maxAddend, ...partitionElement]);

            addendPartition.forEach(partitionElement => {
                partition.push(partitionElement);
            });
        }

        G964.partitionsCache[numberKey] = partition;

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
