import {assert} from "chai";
import {lengthOfLongestSubstring} from './solution';

describe('function', function () {
    it('passes base tests', function () {
        assert.equal(lengthOfLongestSubstring('abcabcbb'), 3);
        assert.equal(lengthOfLongestSubstring('bbbbb'), 1);
        assert.equal(lengthOfLongestSubstring('pwwkew'), 3);
        assert.equal(lengthOfLongestSubstring('bbtablud'), 6);
    });
});
