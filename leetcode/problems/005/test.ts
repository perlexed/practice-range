import {assert} from "chai";
import {longestPalindrome} from './solution';

describe('target function', function () {
    it('passes the examples', function () {
        assert.equal(longestPalindrome('acbbc'), 'cbbc');
        assert.equal(longestPalindrome('babad'), 'bab');
        assert.equal(longestPalindrome('cbbd'), 'bb');
    });
});
