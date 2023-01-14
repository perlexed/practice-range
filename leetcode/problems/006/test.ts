import {assert} from "chai";
import {convert} from './solution';

describe('target function', function () {
    it('passes the examples', function () {
        assert.equal(convert('PAYPALISHIRING', 3), 'PAHNAPLSIIGYIR');
        assert.equal(convert('PAYPALISHIRING', 4), 'PINALSIGYAHRPI');
        assert.equal(convert('PAYPALISHIRING', 5), 'PHASIYIRPLIGAN');
    });
});
