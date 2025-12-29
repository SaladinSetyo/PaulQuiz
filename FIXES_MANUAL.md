# Quick Fixes for Remaining Issues

## Issue 1: Reset Button Not Working (Balance stays 0)

**Root Cause**: The `restartGame()` method might need to force Alpine reactivity update.

**MANUAL FIX** - Edit `trader.blade.php` around line 653-680:

Find the `restartGame()` method and ensure it looks like this:

```javascript
restartGame() {
    if (!confirm('Reset to $1,000? Your leaderboard best score will be preserved.')) return;
    
    // Clear localStorage
    localStorage.removeItem('traderGameState');
    
    // Force reset all values with Alpine reactivity
    this.balance = 1000;
    this.totalTrades = 0;
    this.winningTrades = 0;
    this.bestStreak = 0;
    this.currentStreak = 0;
    this.totalProfit = 0;
    this.showGameOverModal = false;
    this.myPosition = null;
    
    // Update global reference
    window.userBalance = 1000;
    
    // Force Alpine to detect changes
    this.$nextTick(() => {
        console.log('Reset complete - balance:', this.balance);
    });
    
    // Reset backend for auth users
    if (window.isAuthenticated) {
        fetch('/api/games/trader/reset', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).catch(err => console.error('Reset error:', err));
    }
},
```

## Issue 2: Check Button @click Handlers

Verify these buttons have the correct handlers:

### Leverage Buttons (around line 264-273):
```html
<button @click="setLeverage('spot')" ...>Spot</button>
<button @click="setLeverage('cross')" ...>Cross 3x</button>
<button @click="setLeverage('iso')" ...>Iso 10x</button>
```

### Timeframe Buttons (around line 127-138):
```html
<span @click="setTimeframe('1s')" ...>1s</span>
<span @click="setTimeframe('15m')" ...>15m</span>
<span @click="setTimeframe('1H')" ...>1H</span>
<span @click="setTimeframe('4H')" ...>4H</span>
```

### Tab Buttons (around line 152-161):
```html
<span @click="switchTab('positions')" ...>Positions</span>
<span @click="switchTab('orders')" ...>Open Orders</span>
<span @click="switchTab('history')" ...>Order History</span>
```

## Issue 3: Leaderboard Toggle Position

✅ **FIXED** - Moved to `bottom-20` (bottom-right corner) to avoid overlap with wallet UI.

## Testing Checklist:

1. **Reset Button**: 
   - Click refresh icon → Confirm dialog → Balance should show $1,000
   - Check console for "Reset complete" message
   
2. **Control Buttons**:
   - Click Cross 3x → Should highlight in gold
   - Click 15m → Candles should slow down
   - Click Positions tab → Should highlight
   
3. **Leaderboard**:
   - 🏆 button should be at bottom-right corner
   - Click to toggle panel open/close

## Debug Console Commands:

Open browser DevTools (F12) and run:

```javascript
// Check if Alpine component loaded
window.game

// Check current balance
window.game.balance

// Manually reset
window.game.restartGame()

// Check button methods exist
typeof window.game.setLeverage
typeof window.game.setTimeframe
typeof window.game.switchTab
```

If methods return 'undefined', the Alpine component didn't load correctly - check console for errors.
