import {assert} from "chai";
import {reverse} from './solution';

describe('target function', function () {
    it('passes the examples', function () {
        assert.equal(reverse(123), 321);
        assert.equal(reverse(-123), -321);
        assert.equal(reverse(120), 21);
        assert.equal(reverse(2147483647), 0);
        assert.equal(reverse(1534236469), 0);
    });
});
