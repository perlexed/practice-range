
interface IStorage {
    seq: number[],
    indicesToProcess: number[],
}

const crossRunsStorage = {
    seq: [1],
    indicesToProcess: [0],
};

const numberSort = (a: number, b: number) => a - b;

const processNextSequence = function (sequenceStorage: IStorage): IStorage {
    const nextNumberIndex = sequenceStorage.indicesToProcess.shift();

    if (nextNumberIndex === undefined) {
        throw "unprocessed list cannot be empty";
    }

    const nextNumberToProcess = sequenceStorage.seq[nextNumberIndex];

    [
        (2 * nextNumberToProcess) + 1,
        (3 * nextNumberToProcess) + 1,
    ].forEach((nextNumber: number) => {
        if (!sequenceStorage.seq.includes(nextNumber)) {
            sequenceStorage.seq.push(nextNumber);
            sequenceStorage.indicesToProcess.push(sequenceStorage.seq.length - 1);
        }
    });

    return sequenceStorage;
};

const getLinearPositionAt = function (storage: IStorage, position: number): number {
    for (let i = 0; i < position * 4; i++) {
        processNextSequence(storage);
        // console.log(storage);
    }

    const sortedSeq = [...storage.seq].sort(numberSort);

    // console.log(storage.seq);

    return sortedSeq[position];
}

export function dblLinear(n: number): number {
    return getLinearPositionAt(crossRunsStorage, n);
}