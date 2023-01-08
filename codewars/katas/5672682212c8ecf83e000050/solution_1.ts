
interface IStorage {
    processed: number[],
    unprocessed: number[],
}

const crossRunsStorage = {
    processed: [],
    unprocessed: [1],
};

const numberSort = (a: number, b: number) => a - b;

const processNextSequence = function (sequenceStorage: IStorage): IStorage {
    // counters.processNextSequence++;

    const nextNumberToProcess = sequenceStorage.unprocessed.shift();

    if (nextNumberToProcess === undefined) {
        throw "unprocessed list cannot be empty";
    }

    [
        (2 * nextNumberToProcess) + 1,
        (3 * nextNumberToProcess) + 1,
    ].forEach((nextNumber: number) => {
        if (!sequenceStorage.unprocessed.includes(nextNumber)) {
            sequenceStorage.unprocessed.push(nextNumber);
        }
    });

    sequenceStorage.processed.push(nextNumberToProcess);
    sequenceStorage.unprocessed
        .sort(numberSort);

    return sequenceStorage;
};

const getPositionAt = function (storage: IStorage, position: number): number {
    // counters.getPositionAt++;

    return [...storage.processed, ...storage.unprocessed].sort(numberSort)[position];
}


const areThereNumbersToProcess = function (storage: IStorage, position: number): boolean {
    // counters.areThereNumbersToProcess++;

    const possibleNextNumber = 2 * storage.unprocessed[0] + 1;
    return getPositionAt(storage, position) > possibleNextNumber;
}

const getLinearPositionAt = function (storage: IStorage, position: number): number {
    while (storage.unprocessed.length + storage.processed.length < position + 1) {
        processNextSequence(storage);
    }

    while (areThereNumbersToProcess(storage, position)) {
        processNextSequence(storage);
    }

    return getPositionAt(storage, position);
}

export function dblLinear(n: number): number {
    const linearPosition = getLinearPositionAt(crossRunsStorage, n);
    console.log(process.hrtime());
    // console.log(counters);
    return linearPosition;
}