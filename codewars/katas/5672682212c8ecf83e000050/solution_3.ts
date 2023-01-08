
interface IStorage {
    processed: number[],
    unprocessed: Set<number>,
}

const crossRunsStorage = {
    processed: [],
    unprocessed: new Set([1]),
};

const processNextSequence = function (sequenceStorage: IStorage): IStorage {
    const nextNumberToProcess = Math.min.apply( Math, [...Array.from(sequenceStorage.unprocessed)] );
    sequenceStorage.unprocessed.delete(nextNumberToProcess);

    if (nextNumberToProcess === undefined) {
        throw "unprocessed list cannot be empty";
    }

    [
        (2 * nextNumberToProcess) + 1,
        (3 * nextNumberToProcess) + 1,
    ].forEach((nextNumber: number) => {
        sequenceStorage.unprocessed.add(nextNumber);
    });

    sequenceStorage.processed.push(nextNumberToProcess);

    return sequenceStorage;
};

const getLinearPositionAt = function (storage: IStorage, position: number): number {
    for (let i = 0; i < position; i++) {
        processNextSequence(storage);
    }

    const unprocessedArray = Array.from(storage.unprocessed);
    unprocessedArray.sort((a: number, b: number) => a - b);
    return [...storage.processed, ...unprocessedArray][position];
}

export function dblLinear(n: number): number {
    return getLinearPositionAt(crossRunsStorage, n);
}