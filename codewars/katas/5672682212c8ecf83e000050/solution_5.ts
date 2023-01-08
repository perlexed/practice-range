
enum MultiplierTagEnum {
    X2 = 'x2',
    X3 = 'x3',
}

interface IStorage {
    total: {
        seq: number[],
        max: number,
    },
    [MultiplierTagEnum.X2]: {
        seq: number[],
        indexMin: number,
    },
    [MultiplierTagEnum.X3]: {
        seq: number[],
        indexMin: number,
    },
}

const crossRunsStorage = {
    total: {
        seq: [1],
        max: 1,
    },
    [MultiplierTagEnum.X2]: {
        seq: [1],
        indexMin: 0,
    },
    [MultiplierTagEnum.X3]: {
        seq: [1],
        indexMin: 0,
    }
};

enum ProcessNumbersOrderEnum {
    X2First,
    SmallestFirst,
}

const numberSort = (a: number, b: number) => a - b;

const processNextSequence = function (storage: IStorage, numbersOrder: ProcessNumbersOrderEnum): IStorage {
    const shouldUseX2 = numbersOrder === ProcessNumbersOrderEnum.X2First
        || storage.x2.seq[storage.x2.indexMin] <= storage.x3.seq[storage.x3.indexMin];
    const sequenceToProcess = shouldUseX2 ? storage.x2 : storage.x3;

    const nextNumberToProcess = sequenceToProcess.seq.splice(sequenceToProcess.indexMin, 1)[0];

    const expressionMultiplier = shouldUseX2 ? 2 : 3;
    const expressionResult = expressionMultiplier * nextNumberToProcess + 1;

    [MultiplierTagEnum.X2, MultiplierTagEnum.X3].forEach(sequenceTag => {
        // @todo potential speed loss
        if (!storage[sequenceTag].seq.includes(expressionResult)) {
            storage[sequenceTag].seq.push(expressionResult);
        }
    })

    sequenceToProcess.indexMin = sequenceToProcess.seq.indexOf(Math.min(...sequenceToProcess.seq));

    if (!storage.total.seq.includes(expressionResult)) {
        storage.total.seq.push(expressionResult);
        storage.total.max = Math.max(...storage.total.seq);
    }

    return storage;
};

const getAtPosition = function (storage: IStorage, position: number) {
    const orderedSequence = storage.total.seq.sort(numberSort);
    return orderedSequence[position];
}

const getLinearPositionAt = function (storage: IStorage, position: number): number {
    while (storage.total.seq.length < position + 1) {
        processNextSequence(storage, ProcessNumbersOrderEnum.SmallestFirst);
    }

    while (storage.total.max > (2 * storage[MultiplierTagEnum.X2].seq[storage[MultiplierTagEnum.X2].indexMin] + 1)) {
        processNextSequence(storage, ProcessNumbersOrderEnum.X2First);
    }

    return getAtPosition(storage, position);
}

export function dblLinear(n: number): number {
    return getLinearPositionAt(crossRunsStorage, n);
}