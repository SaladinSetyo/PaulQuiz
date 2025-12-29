# MANUAL UI WIRING GUIDE - REMAINING BUTTONS

Leverage buttons ✅ SUDAH WIRED! Tinggal yang ini:

## 1. TAB BUTTONS (Positions/Open Orders/Order History)
**Location:** Around line 153-161  
**Current:**
```html
<span class="text-[11px] font-bold text-[#f0b90b]...">Positions
```

**Replace with:**
```html
<span @click="switchTab('positions')"
    :class="activeTab === 'positions' ? 'text-[#f0b90b] border-b-2 border-[#f0b90b]' : 'text-slate-500 hover:text-white border-b-2 border-transparent'"
    class="text-[11px] font-bold h-full flex items-center px-1 cursor-pointer transition-colors">Positions
    <span x-text="myPosition ? '(1)' : '(0)'"></span></span>

<span @click="switchTab('orders')"
    :class="activeTab === 'orders' ? 'text-[#f0b90b] border-b-2 border-[#f0b90b]' : 'text-slate-500 hover:text-white border-b-2 border-transparent'"
    class="text-[11px] font-bold h-full flex items-center px-1 cursor-pointer transition-colors">Open Orders (0)</span>

<span @click="switchTab('history')"
    :class="activeTab === 'history' ? 'text-[#f0b90b] border-b-2 border-[#f0b90b]' : 'text-slate-500 hover:text-white border-b-2 border-transparent'"
    class="text-[11px] font-bold h-full flex items-center px-1 cursor-pointer transition-colors">Order History</span>
```

## 2. TIMEFRAME BUTTONS (Time/1s/15m/1H/4H)
**Find buttons with timeframe labels**
**Add:**
```html
<button @click="setTimeframe('1s')"
    :class="timeframe === '1s' ? 'text-[#f0b90b] font-bold' : 'text-slate-500 hover:text-white'"
    class="text-xs transition-colors">1s</button>

<button @click="setTimeframe('15m')"
    :class="timeframe === '15m' ? 'text-[#f0b90b] font-bold' : 'text-slate-500 hover:text-white'"
    class="text-xs transition-colors">15m</button>

<button @click="setTimeframe('1H')"
    :class="timeframe === '1H' ? 'text-[#f0b90b] font-bold' : 'text-slate-500 hover:text-white'"
    class="text-xs transition-colors">1H</button>

<button @click="setTimeframe('4H')"
    :class="timeframe === '4H' ? 'text-[#f0b90b] font-bold' : 'text-slate-500 hover:text-white'"
    class="text-xs transition-colors">4H</button>
```

## 3. LEADERBOARD POSITION FIX
Jika masih overlap, ubah di line ~713:
```html
<!-- Change top-40 to top-60 or higher -->
class="absolute left-0 top-60 -translate-x-full bg-[#f0b90b]..."
```

Atau buat draggable dengan Alpine.js x-drag plugin.

## VERIFICATION
Test setelah apply manual:
1. Leverage buttons - SHOULD WORK NOW (Spot/Cross/Iso)
2. Tabs - Visual switch when clicked
3. Timeframe - Candle speed changes dynamically
4. Leaderboard - No overlap with balance
