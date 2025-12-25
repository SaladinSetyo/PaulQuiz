<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pintu Pro Trader - BTC/USDT</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        body { background-color: #0b0e11; color: #eaecef; font-family: 'Inter', sans-serif; }
        .font-mono { font-family: 'Roboto Mono', monospace; }
        
        /* Pintu Pro Scrollbar */
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: #0b0e11; }
        ::-webkit-scrollbar-thumb { background: #2b3139; border-radius: 2px; }
        
        .order-book-row:hover { background-color: #1e2329; }
        .blink-green { animation: blinkG 0.5s; }
        .blink-red { animation: blinkR 0.5s; }
        
        @keyframes blinkG { 0% { background-color: rgba(16, 185, 129, 0.2); } 100% { background-color: transparent; } }
        @keyframes blinkR { 0% { background-color: rgba(244, 63, 94, 0.2); } 100% { background-color: transparent; } }
    </style>
</head>

<body class="flex flex-col h-screen overflow-hidden bg-[#0b0e11] text-[#eaecef]">

    <!-- NAVBAR -->
    <nav class="h-14 bg-[#181a20] border-b border-[#2b3139] flex items-center px-4 justify-between shrink-0 z-50">
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded bg-white text-black font-black flex items-center justify-center text-lg">P</div>
                <span class="font-bold text-lg tracking-tight">Pro</span>
            </div>
            
            <!-- Asset Info -->
            <div class="hidden md:flex flex-col">
                <div class="flex items-center gap-2">
                    <span class="font-bold text-lg">BTC/USDT</span>
                    <span class="text-xs text-emerald-400 bg-emerald-400/10 px-1 rounded">+2.4%</span>
                </div>
                <span class="text-xs text-slate-400">Bitcoin</span>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="bg-[#2b3139] px-4 py-1.5 rounded flex items-center gap-3">
                <span class="text-xs text-slate-400 uppercase font-bold">Balance</span>
                <span class="font-mono font-bold text-white">$<span x-data x-text="window.userBalance?.toFixed(2) ?? '1,000.00'"></span></span>
            </div>
            <a href="{{ route('homepage') }}" class="text-slate-400 hover:text-white px-3 py-1.5 hover:bg-[#2b3139] rounded text-sm font-bold transition-colors">Exit</a>
        </div>
    </nav>

    <!-- CONTENT -->
    <div x-data="proTrader()" x-init="initTrader()" x-cloak class="flex-grow flex overflow-hidden relative">

        <!-- LEFT: CHART AREA -->
        <div class="flex-grow flex flex-col relative bg-[#0b0e11]">
            <!-- Chart Toolbar -->
            <div class="h-10 border-b border-[#2b3139] flex items-center px-4 gap-4 text-xs font-bold text-slate-400">
                <span class="text-white cursor-pointer hover:bg-[#2b3139] px-2 py-1 rounded">Time</span>
                <span class="cursor-pointer hover:bg-[#2b3139] px-2 py-1 rounded text-[#f0b90b]">15m</span>
                <span class="cursor-pointer hover:bg-[#2b3139] px-2 py-1 rounded">1H</span>
                <span class="cursor-pointer hover:bg-[#2b3139] px-2 py-1 rounded">4H</span>
                <div class="w-[1px] h-4 bg-[#2b3139]"></div>
                <span class="cursor-pointer hover:bg-[#2b3139] px-2 py-1 rounded">Indicators</span>
            </div>

            <!-- Canvas Container -->
            <div class="flex-grow relative w-full h-full cursor-crosshair">
                <canvas id="tradeCanvas" class="absolute inset-0 w-full h-full"></canvas>
                
                <!-- Game Phase HUD overlay on Chart -->
                <div class="absolute top-4 left-1/2 transform -translate-x-1/2 flex flex-col items-center pointer-events-none">
                    
                    <!-- Phase Badge -->
                    <div class="bg-[#1e2329]/90 backdrop-blur border border-[#474d57] px-6 py-2 rounded-full shadow-lg mb-4 flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full animate-pulse" 
                             :class="phase === 'open' ? 'bg-emerald-500' : 'bg-rose-500'"></div>
                        <span class="font-mono font-bold text-lg" 
                              :class="phase === 'open' ? 'text-emerald-400' : 'text-rose-400'"
                              x-text="phase === 'open' ? 'MARKET OPEN' : 'MARKET CLOSED'"></span>
                        <div class="w-[1px] h-4 bg-[#474d57]"></div>
                        <span class="font-mono font-bold text-white text-xl w-12 text-center" x-text="formatTimer()"></span>
                    </div>

                    <!-- Notification Toast -->
                    <div x-show="phase === 'locked'" 
                         x-transition:enter="transition ease-out duration-300 transform"
                         x-transition:enter-start="opacity-0 -translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="bg-[#1e2329]/90 backdrop-blur px-4 py-2 rounded text-slate-300 text-xs font-bold border border-rose-500/30">
                        Trading Locked - Wait for Settlement
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT: ORDER PANEL + ORDER BOOK -->
        <div class="w-[320px] bg-[#181a20] border-l border-[#2b3139] flex flex-col shrink-0 z-20">
            
            <!-- Order Book (Top Half) -->
            <div class="flex-grow flex flex-col min-h-0 border-b border-[#2b3139]">
                <div class="h-8 flex items-center px-4 text-xs font-bold text-slate-500 justify-between">
                    <span>Price(USDT)</span>
                    <span>Amount(BTC)</span>
                </div>
                <div class="overflow-hidden relative flex-grow text-xs font-mono">
                    <!-- Sells (Red) -->
                    <div class="flex flex-col-reverse justify-end h-1/2 overflow-hidden pb-1">
                        <template x-for="ask in asks" :key="ask.price">
                            <div class="flex justify-between px-4 py-0.5 cursor-pointer order-book-row hover:bg-[#2b3139]">
                                <span class="text-[#f6465d]" x-text="ask.price.toFixed(2)"></span>
                                <span class="text-slate-300" x-text="ask.amount.toFixed(4)"></span>
                            </div>
                        </template>
                    </div>
                    
                    <!-- Current Price -->
                    <div class="h-10 flex items-center justify-center border-y border-[#2b3139] bg-[#0b0e11]">
                        <span class="text-xl font-bold font-mono" 
                              :class="lastPrice >= prevPrice ? 'text-[#0ecb81]' : 'text-[#f6465d]'"
                              x-text="lastPrice.toFixed(2)"></span>
                        <svg x-show="lastPrice >= prevPrice" class="w-4 h-4 text-[#0ecb81] ml-1" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                        <svg x-show="lastPrice < prevPrice" class="w-4 h-4 text-[#f6465d] ml-1" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                    </div>

                    <!-- Buys (Green) -->
                    <div class="h-1/2 overflow-hidden pt-1">
                        <template x-for="bid in bids" :key="bid.price">
                            <div class="flex justify-between px-4 py-0.5 cursor-pointer order-book-row hover:bg-[#2b3139]">
                                <span class="text-[#0ecb81]" x-text="bid.price.toFixed(2)"></span>
                                <span class="text-slate-300" x-text="bid.amount.toFixed(4)"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Trading Panel (Bottom Half) -->
            <div class="p-4 bg-[#1e2329]">
                <!-- Tabs -->
                <div class="flex bg-[#0b0e11] p-1 rounded mb-4">
                    <button class="flex-1 py-1.5 rounded text-sm font-bold bg-[#2b3139] text-white">Spot</button>
                    <button class="flex-1 py-1.5 rounded text-sm font-bold text-slate-500 hover:text-white">Cross 3x</button>
                </div>

                <!-- Inputs -->
                <div class="space-y-3 mb-6">
                    <div>
                        <div class="flex justify-between text-xs text-slate-400 mb-1">
                            <span>Avbl</span>
                            <span><span x-text="balance.toFixed(2)"></span> USDT</span>
                        </div>
                        <div class="relative">
                             <div class="absolute inset-y-0 left-3 flex items-center text-xs font-bold text-slate-400">Price</div>
                             <input type="text" disabled value="Market" class="w-full bg-[#2b3139] border border-transparent rounded h-10 pl-14 pr-3 text-right text-sm font-bold text-white focus:outline-none focus:border-[#f0b90b]">
                        </div>
                    </div>

                    <div>
                        <div class="relative">
                             <div class="absolute inset-y-0 left-3 flex items-center text-xs font-bold text-slate-400">Amount</div>
                             <input type="number" x-model="betAmount" class="w-full bg-[#2b3139] border border-transparent rounded h-10 pl-16 pr-10 text-right text-sm font-bold text-white focus:outline-none focus:border-[#f0b90b]">
                             <div class="absolute inset-y-0 right-3 flex items-center text-xs font-bold text-slate-400">USDT</div>
                        </div>
                    </div>
                    
                    <!-- Slider -->
                    <input type="range" class="w-full h-1 bg-[#474d57] rounded-lg appearance-none cursor-pointer accent-[#f0b90b]">
                </div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-2 gap-3">
                    <button @click="placeOrder('buy')" 
                            :disabled="phase !== 'open' || myPosition"
                            class="h-12 bg-[#0ecb81] hover:bg-[#0da86b] disabled:opacity-50 disabled:cursor-not-allowed rounded text-white font-bold text-sm transition-colors flex flex-col items-center justify-center leading-none shadow-lg">
                        <span class="text-base">Buy Long</span>
                    </button>
                    
                    <button @click="placeOrder('sell')" 
                            :disabled="phase !== 'open' || myPosition"
                            class="h-12 bg-[#f6465d] hover:bg-[#d93a4e] disabled:opacity-50 disabled:cursor-not-allowed rounded text-white font-bold text-sm transition-colors flex flex-col items-center justify-center leading-none shadow-lg">
                        <span class="text-base">Sell Short</span>
                    </button>
                </div>

                 <!-- Users Position Info -->
                <div x-show="myPosition" class="mt-4 p-3 bg-[#0b0e11] rounded border border-[#2b3139]">
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-slate-400">Entry Price</span>
                        <span class="font-mono text-white" x-text="myPosition?.entry.toFixed(2)"></span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-400">PnL (Est)</span>
                        <span class="font-mono font-bold" 
                            :class="(lastPrice - myPosition?.entry) * (myPosition?.type==='buy'?1:-1) > 0 ? 'text-[#0ecb81]' : 'text-[#f6465d]'">
                            <span x-text="(lastPrice - myPosition?.entry) * (myPosition?.type==='buy'?1:-1) > 0 ? '+' : ''"></span>
                            <span x-text="((lastPrice - myPosition?.entry) * (myPosition?.type==='buy'?1:-1)).toFixed(2)"></span>
                        </span>
                    </div>
                </div>

            </div>
        </div>

        <!-- Result Overlay (Absolute Center) -->
        <div x-show="showResult" 
             style="display: none;"
             class="absolute inset-0 z-50 flex items-center justify-center pointer-events-none">
            <div class="bg-[#1e2329] border border-[#474d57] p-8 rounded-xl shadow-2xl text-center transform scale-100 animate-bounce">
                <div class="text-5xl mb-2" x-text="lastWin ? '💰' : '📉'"></div>
                <h2 class="text-3xl font-black mb-1" :class="lastWin ? 'text-[#0ecb81]' : 'text-[#f6465d]'" x-text="lastWin ? 'Win' : 'Loss'"></h2>
                <div class="text-white font-mono font-bold text-xl" x-text="(lastWin ? '+' : '-') + '$' + lastPnL.toFixed(2)"></div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('proTrader', () => ({
                // Game Config
                phase: 'open', // 'open' (10s), 'locked' (20s)
                timer: 10,
                balance: 1000,
                lastPrice: 43500.00,
                prevPrice: 43500.00,
                betAmount: 100,
                
                // Candles Data
                candles: [], // {t, o, h, l, c}
                asks: [],
                bids: [],
                
                // User State
                myPosition: null, // {type: 'buy'|'sell', entry: 123}
                showResult: false,
                lastWin: false,
                lastPnL: 0,
                
                // Internals
                interval: null,
                canvas: null,
                ctx: null,

                initTrader() {
                    window.userBalance = this.balance;
                    this.canvas = document.getElementById('tradeCanvas');
                    this.setupCanvas();
                    this.generateHistory();
                    this.generateOrderBook();
                    this.startLoop();
                    
                    window.addEventListener('resize', () => { this.setupCanvas(); this.draw(); });
                },

                setupCanvas() {
                    if(!this.canvas) return;
                    const dpr = window.devicePixelRatio || 1;
                    const rect = this.canvas.parentElement.getBoundingClientRect();
                    this.canvas.width = rect.width * dpr;
                    this.canvas.height = rect.height * dpr;
                    this.ctx = this.canvas.getContext('2d');
                    this.ctx.scale(dpr, dpr);
                },

                generateHistory() {
                    let p = this.lastPrice;
                    let t = Date.now() - (60 * 1000);
                    for(let i=0; i<60; i++) {
                        let o = p;
                        let c = o + (Math.random() - 0.5) * 20;
                        let h = Math.max(o, c) + Math.random() * 5;
                        let l = Math.min(o, c) - Math.random() * 5;
                        this.candles.push({t, o, h, l, c});
                        p = c;
                        t += 1000;
                    }
                    this.lastPrice = p;
                },

                generateOrderBook() {
                    this.asks = [];
                    this.bids = [];
                    for(let i=0; i<8; i++) {
                        this.asks.push({ price: this.lastPrice + (i*2) + Math.random(), amount: Math.random() * 2 });
                        this.bids.push({ price: this.lastPrice - (i*2) - Math.random(), amount: Math.random() * 2 });
                    }
                },

                formatTimer() {
                    return `00:${this.timer.toString().padStart(2, '0')}`;
                },

                startLoop() {
                    if(this.interval) clearInterval(this.interval);
                    
                    this.interval = setInterval(() => {
                        this.tick();
                    }, 1000);
                },

                tick() {
                    // Timer Logic
                    this.timer--;
                    
                    // State Transitions
                    if(this.phase === 'open' && this.timer <= 0) {
                        this.phase = 'locked';
                        this.timer = 20; // 20s locked
                    } else if(this.phase === 'locked' && this.timer <= 0) {
                        this.settle();
                        this.phase = 'open';
                        this.timer = 10; // 10s open
                    }

                    // Market Movement (Simulated Tick)
                    this.prevPrice = this.lastPrice;
                    let move = (Math.random() - 0.5) * 15;
                    // Bias if 'locked' to create suspense? No, keep random.
                    this.lastPrice += move;
                    
                    // Update Candle
                    let lastCandle = this.candles[this.candles.length-1];
                    lastCandle.c = this.lastPrice;
                    if(this.lastPrice > lastCandle.h) lastCandle.h = this.lastPrice;
                    if(this.lastPrice < lastCandle.l) lastCandle.l = this.lastPrice;
                    
                    // New Candle every second? No, keeping it realistic-ish.
                    // Let's add new candle every 5 ticks.
                    if(Date.now() % 5 === 0 || this.phaseTransitioned) { // Simple hack
                        // Push new
                        let o = this.lastPrice;
                        this.candles.push({t: Date.now(), o, h:o, l:o, c:o});
                        if(this.candles.length > 80) this.candles.shift();
                    }

                    // Orderbook shimmy
                    this.generateOrderBook(); // Naive refresh
                    
                    this.draw();
                },

                placeOrder(type) {
                    if(this.phase !== 'open') return;
                    if(this.balance < this.betAmount) return;
                    
                    this.balance -= parseInt(this.betAmount);
                    window.userBalance = this.balance;
                    
                    this.myPosition = {
                        type: type,
                        entry: this.lastPrice,
                        amount: parseInt(this.betAmount)
                    };
                },

                settle() {
                    if(!this.myPosition) return;
                    
                    let diff = this.lastPrice - this.myPosition.entry;
                    let win = false;
                    
                    if(this.myPosition.type === 'buy' && diff > 0) win = true;
                    if(this.myPosition.type === 'sell' && diff < 0) win = true;
                    
                    this.lastWin = win;
                    if(win) {
                        let profit = this.myPosition.amount * 1.82; // 82% return
                        this.lastPnL = profit - this.myPosition.amount;
                        this.balance += profit;
                    } else {
                        this.lastPnL = this.myPosition.amount;
                    }
                    
                    window.userBalance = this.balance;
                    this.showResult = true;
                    setTimeout(() => { this.showResult = false; }, 3000);
                    
                    this.myPosition = null;
                },

                draw() {
                    const ctx = this.ctx;
                    const w = this.canvas.width / (window.devicePixelRatio || 1);
                    const h = this.canvas.height / (window.devicePixelRatio || 1);
                    
                    ctx.clearRect(0, 0, w, h);
                    
                    // Grid
                    ctx.strokeStyle = '#2b3139';
                    ctx.lineWidth = 1;
                    ctx.beginPath();
                    // Vertical
                    for(let x=0; x<w; x+=60) { ctx.moveTo(x, 0); ctx.lineTo(x, h); }
                    // Horizontal
                    for(let y=0; y<h; y+=60) { ctx.moveTo(0, y); ctx.lineTo(w, y); }
                    ctx.stroke();
                    
                    // Calc Scale
                    let min = Infinity, max = -Infinity;
                    this.candles.forEach(c => {
                        if(c.l < min) min = c.l;
                        if(c.h > max) max = c.h;
                    });
                    let pad = (max - min) * 0.2;
                    min -= pad; max += pad;
                    let range = max - min;
                    
                    // Draw Candles
                    let candleW = (w / 80) * 0.7;
                    let spacing = (w / 80) * 0.3;
                    
                    this.candles.forEach((c, i) => {
                        let isGreen = c.c >= c.o;
                        ctx.fillStyle = isGreen ? '#0ecb81' : '#f6465d';
                        ctx.strokeStyle = isGreen ? '#0ecb81' : '#f6465d';
                        
                        let x = i * (candleW + spacing) + spacing;
                        let yO = h - ((c.o - min) / range) * h;
                        let yC = h - ((c.c - min) / range) * h;
                        let yH = h - ((c.h - min) / range) * h;
                        let yL = h - ((c.l - min) / range) * h;
                        
                        // Wick
                        ctx.beginPath();
                        ctx.moveTo(x + candleW/2, yH);
                        ctx.lineTo(x + candleW/2, yL);
                        ctx.stroke();
                        
                        // Body
                        let top = Math.min(yO, yC);
                        let height = Math.abs(yO - yC);
                        if(height < 1) height = 1;
                        ctx.fillRect(x, top, candleW, height);
                    });
                    
                    // Current Price Line
                    let lastY = h - ((this.lastPrice - min) / range) * h;
                    ctx.strokeStyle = '#f0b90b'; // Binance Yellow / Pintu Gold
                    ctx.setLineDash([4, 4]);
                    ctx.beginPath();
                    ctx.moveTo(0, lastY);
                    ctx.lineTo(w, lastY);
                    ctx.stroke();
                    ctx.setLineDash([]);
                    
                    // Entry Line (if active)
                    if(this.myPosition) {
                        let entryY = h - ((this.myPosition.entry - min) / range) * h;
                        ctx.strokeStyle = '#3b82f6';
                        ctx.lineWidth = 1;
                        ctx.beginPath();
                        ctx.moveTo(0, entryY);
                        ctx.lineTo(w, entryY);
                        ctx.stroke();
                        
                        // Badge
                        ctx.fillStyle = '#3b82f6';
                        ctx.fillRect(w - 60, entryY - 10, 60, 20);
                        ctx.fillStyle = '#fff';
                        ctx.font = '10px sans-serif';
                        ctx.fillText('ENTRY', w - 50, entryY + 4);
                    }
                }
            }));
        });
    </script>
</body>
</html>