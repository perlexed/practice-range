import {assert} from "chai";
import {intToRoman} from './solution';

describe('target function', function () {
    it('passes the examples', function () {
        assert.equal(intToRoman(3), 'III');
        assert.equal(intToRoman(58), 'LVIII');
        assert.equal(intToRoman(1994), 'MCMXCIV');
    });
});
