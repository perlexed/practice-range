import {assert} from "chai";
import {myAtoi} from './solution';

describe('target function', function () {
    it('passes the examples', function () {
        assert.equal(myAtoi('42'), 42);
        assert.equal(myAtoi('   -42'), -42);
        assert.equal(myAtoi('4193 with words'), 4193);
        assert.equal(myAtoi('  0000000000012345678'), 12345678);
        assert.equal(myAtoi('00000-42a1234'), 0);
        assert.equal(myAtoi('2147483646'), 2147483646);
        assert.equal(myAtoi('-2147483648'), -2147483648);
        assert.equal(myAtoi('-2147483649'), -2147483648);
    });
});
