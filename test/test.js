var assert = require('assert');
// var Calculator = require('../js/adminNotifyEditor');

// const aNE = new AdminNotifyEditor();

describe('Calculator', function() {
  describe('add', function() {
    it('1 + 1 should return 2', function() {
      assert.equal(calc.add(1, 1), 2);
    });
    
     it('1 + 2 should return 3', function() {
      assert.equal(calc.add(1, 2), 3);
    });
    
     it('1 + 3 should return 4', function() {
      assert.equal(calc.add(1, 3), 4);
    });
    
  });
});