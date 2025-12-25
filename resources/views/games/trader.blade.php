<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crypto Trader Panic - BTC/USDT</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        body { background-color: #0b0e11; color: #eaecef; font-family: 'Inter', sans-serif; overflow: hidden; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        
        /* Custom inputs */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
          -webkit-appearance: none; 
          margin: 0; 
        }
        
        .order-book-row:hover { background-color: #1e2329; }
    </style>
</head>

<body class="flex flex-col h-screen bg-[#0b0e11] text-[#eaecef]">

    <!-- NAVBAR -->
    <nav class="h-14 bg-[#181a20] border-b border-[#2b3139] flex items-center px-4 justify-between shrink-0 z-50">
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded bg-emerald-500 text-[#0b0e11] font-black flex items-center justify-center text-lg shadow-lg shadow-emerald-500/20">C</div>
                <span class="font-black text-lg tracking-tight text-white hidden md:block">Crypto Trader Panic</span>
            </div>
            
            <div class="flex flex-col border-l border-[#2b3139] pl-6">
                <div class="flex items-center gap-2">
                    <span class="font-bold text-sm text-white">BTC/USDT</span>
                    <span class="text-[10px] font-bold text-emerald-400 bg-emerald-400/10 px-1.5 py-0.5 rounded">+2.45%</span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="bg-[#2b3139] px-4 py-1.5 rounded flex items-center gap-3 border border-[#474d57]">
                <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Balance</span>
                <span class="font-mono font-bold text-white text-sm">$<span x-data x-text="window.userBalance?.toLocaleString('en-US', {minimumFractionDigits: 2}) ?? '1,000.00'"></span></span>
            </div>
            <a href="{{ route('homepage') }}" class="text-slate-400 hover:text-white px-3 py-1.5 hover:bg-[#2b3139] rounded text-xs font-bold transition-colors uppercase tracking-wider">Exit</a>
        </div>
    </nav>

    <!-- CONTENT -->
    <div x-data="proTrader()" x-init="initTrader()" x-cloak class="flex-grow flex overflow-hidden relative">

        <!-- LEFT: CHART AREA -->
        <div class="flex-grow flex flex-col relative bg-[#0b0e11] min-w-0">
            <!-- Toolbar -->
            <div class="h-10 border-b border-[#2b3139] flex items-center px-4 gap-4 text-[11px] font-bold text-slate-500 bg-[#0b0e11] shrink-0 overflow-x-auto whitespace-nowrap">
                <span class="text-slate-300">Time</span>
                <span class="text-[#f0b90b]">1s</span>
                <span>15m</span>
                <span>1H</span>
                <span>4H</span>
                <div class="w-[1px] h-3 bg-[#2b3139]"></div>
                <span>Indicators</span>
            </div>

            <!-- Canvas Container -->
            <div id="chartContainer" class="flex-grow relative w-full h-full bg-[#0b0e11]">
                <canvas id="tradeCanvas" class="absolute inset-0 w-full h-full block"></canvas>
                
                <!-- HUD -->
                <div class="absolute top-4 left-1/2 transform -translate-x-1/2 flex flex-col items-center pointer-events-none z-10 w-full px-4">
                    <div class="backdrop-blur-md border px-6 py-2 rounded-full shadow-2xl flex items-center gap-4"
                         :class="phase === 'open' ? 'bg-[#1e2329]/90 border-emerald-500/30' : 'bg-[#1e2329]/90 border-rose-500/30'">
                        
                        <div class="flex flex-col items-end">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Status</span>
                            <span class="font-black text-xs md:text-sm tracking-tight whitespace-nowrap" 
                                  :class="phase === 'open' ? 'text-emerald-400' : 'text-rose-400'"
                                  x-text="phase === 'open' ? 'TRADING OPEN' : 'MARKET LOCKED'"></span>
                        </div>
                        
                        <div class="w-[1px] h-6 bg-[#474d57]"></div>
                        
                        <div class="flex flex-col items-start w-14">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Timer</span>
                            <span class="font-mono font-bold text-xl text-white leading-none" x-text="formatTimer()"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT: ORDER PANEL -->
        <div class="w-[300px] md:w-[340px] bg-[#181a20] border-l border-[#2b3139] flex flex-col shrink-0 z-20 overflow-y-auto custom-scrollbar">
            
            <!-- Dynamic Order Book -->
            <div class="flex-grow flex flex-col min-h-[300px] border-b border-[#2b3139]">
                <div class="h-8 flex items-center px-4 text-[10px] font-bold text-slate-500 justify-between bg-[#181a20]">
                    <span>Price(USDT)</span>
                    <span>Amount(BTC)</span>
                </div>
                
                <div class="flex-grow relative bg-[#0b0e11] overflow-hidden flex flex-col">
                    <!-- Sells -->
                    <div class="flex-1 overflow-hidden flex flex-col-reverse justify-start pb-1">
                        <template x-for="ask in asks" :key="ask.id">
                            <div class="flex justify-between px-4 py-[2px] order-book-row relative h-[18px] items-center">
                                <span class="text-[#f6465d] text-[11px] font-mono z-10" x-text="ask.price.toFixed(2)"></span>
                                <span class="text-slate-400 text-[11px] font-mono z-10" x-text="ask.amount.toFixed(4)"></span>
                                <div class="absolute right-0 top-0 bottom-0 bg-[#f6465d]/10" :style="'width: ' + (ask.amount * 40) + '%'"></div>
                            </div>
                        </template>
                    </div>
                    
                    <!-- Middle Price -->
                    <div class="h-10 flex items-center justify-center border-y border-[#2b3139] bg-[#181a20] shrink-0 z-20">
                        <span class="text-xl font-bold font-mono tracking-tight" 
                              :class="lastPrice >= prevPrice ? 'text-[#0ecb81]' : 'text-[#f6465d]'"
                              x-text="lastPrice.toFixed(2)"></span>
                    </div>

                    <!-- Buys -->
                    <div class="flex-1 overflow-hidden pt-1">
                        <template x-for="bid in bids" :key="bid.id">
                             <div class="flex justify-between px-4 py-[2px] order-book-row relative h-[18px] items-center">
                                <span class="text-[#0ecb81] text-[11px] font-mono z-10" x-text="bid.price.toFixed(2)"></span>
                                <span class="text-slate-400 text-[11px] font-mono z-10" x-text="bid.amount.toFixed(4)"></span>
                                <div class="absolute right-0 top-0 bottom-0 bg-[#0ecb81]/10" :style="'width: ' + (bid.amount * 40) + '%'"></div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Trading Panel -->
            <div class="p-4 bg-[#1e2329] shrink-0">
                <div class="flex bg-[#0b0e11] p-[2px] rounded mb-4 border border-[#2b3139]">
                    <button class="flex-1 py-1.5 rounded text-xs font-bold bg-[#2b3139] text-white shadow">Market</button>
                    <button class="flex-1 py-1.5 rounded text-xs font-bold text-slate-500">Limit</button>
                </div>

                <div class="mb-4">
                    <div class="flex justify-between text-[10px] font-bold text-slate-400 mb-1">
                        <span>Avbl</span>
                        <span class="text-white font-mono"><span x-text="balance.toFixed(2)"></span> USDT</span>
                    </div>

                    <!-- Input Group -->
                    <div class="relative group bg-[#0b0e11] border border-[#2b3139] rounded overflow-hidden flex items-center h-10">
                        <span class="pl-3 text-xs font-bold text-slate-500 whitespace-nowrap shrink-0">Amount</span>
                        <input type="number" x-model.number="betAmount" class="flex-grow bg-transparent border-none text-right text-sm font-bold text-white focus:ring-0 px-2 font-mono h-full w-20">
                        <span class="pr-3 text-xs font-bold text-white shrink-0">USDT</span>
                    </div>

                    <!-- Slider -->
                    <div class="mt-3 px-1">
                        <input type="range" min="10" max="1000" step="10" x-model="betAmount" 
                               class="w-full h-1 bg-[#2b3139] rounded-lg appearance-none cursor-pointer accent-[#f0b90b]">
                    </div>
                </div>

                <!-- Buttons -->
                <div class="grid grid-cols-2 gap-3 mb-2">
                    <button @click="placeOrder('buy')" 
                            :disabled="phase !== 'open' || myPosition"
                            class="h-10 bg-[#0ecb81] hover:bg-[#0da86b] disabled:opacity-50 disabled:grayscale rounded text-white font-bold text-sm shadow-[0_3px_0_#0aa86b] active:shadow-none active:translate-y-[3px] flex items-center justify-center gap-1">
                        <span>Buy Long</span>
                    </button>
                    
                    <button @click="placeOrder('sell')" 
                            :disabled="phase !== 'open' || myPosition"
                            class="h-10 bg-[#f6465d] hover:bg-[#d93a4e] disabled:opacity-50 disabled:grayscale rounded text-white font-bold text-sm shadow-[0_3px_0_#d13045] active:shadow-none active:translate-y-[3px] flex items-center justify-center gap-1">
                        <span>Sell Short</span>
                    </button>
                </div>
                
                 <!-- Position Card -->
                <div x-show="myPosition" class="mt-3 p-3 bg-[#0b0e11] rounded border border-[#2b3139] text-xs">
                    <div class="flex justify-between mb-1">
                        <span class="text-slate-500">Entry</span>
                        <span class="font-mono" x-text="myPosition?.entry.toFixed(2)"></span>
                    </div>
                     <div class="flex justify-between">
                        <span class="text-slate-500">PnL</span>
                        <span class="font-mono font-bold" 
                              :class="(lastPrice - myPosition?.entry) * (myPosition?.type==='buy'?1:-1) > 0 ? 'text-[#0ecb81]' : 'text-[#f6465d]'">
                             <span x-text="(lastPrice - myPosition?.entry) * (myPosition?.type==='buy'?1:-1) > 0 ? '+' : ''"></span>
                             <span x-text="((lastPrice - myPosition?.entry) * (myPosition?.type==='buy'?1:-1)).toFixed(2)"></span>
                        </span>
                    </div>
                </div>

            </div>
        </div>

        <!-- RESULT POPUP -->
        <div x-show="showResult" 
             style="display: none;"
             class="absolute inset-0 z-50 flex items-center justify-center pointer-events-none bg-black/20 backdrop-blur-[1px]">
            <div class="bg-[#1e2329] border-2 p-6 rounded-xl shadow-2xl text-center animate-bounce min-w-[280px]"
                 :class="lastWin ? 'border-[#0ecb81]' : 'border-[#f6465d]'">
                <div class="text-5xl mb-2" x-text="lastWin ? '🚀' : '🔻'"></div>
                <h2 class="text-3xl font-black mb-1 text-white" x-text="lastWin ? 'PROFIT' : 'LOSS'"></h2>
                <div class="font-mono font-bold text-xl" 
                     :class="lastWin ? 'text-[#0ecb81]' : 'text-[#f6465d]'"
                     x-text="(lastWin ? '+' : '-') + '$' + Math.abs(lastPnL).toFixed(2)"></div>
            </div>
        </div>

    </div>

    <!-- LOGIC -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('proTrader', () => ({
                phase: 'open',
                timer: 10,
                balance: 1000,
                lastPrice: 65000.00,
                prevPrice: 65000.00,
                betAmount: 100,
                
                candles: [],
                asks: [],
                bids: [],
                myPosition: null,
                showResult: false,
                lastWin: false,
                lastPnL: 0,
                
                canvas: null,
                ctx: null,
                resizeObserver: null,

                initTrader() {
                    window.userBalance = this.balance;
                    
                    // Setup Canvas with ResizeObserver for robustness
                    this.canvas = document.getElementById('tradeCanvas');
                    const container = document.getElementById('chartContainer');
                    
                    this.resizeObserver = new ResizeObserver(() => {
                        this.setupCanvas();
                        this.draw();
                    });
                    this.resizeObserver.observe(container);
                    
                    // Generate Initial Data
                    this.generateHistory();
                    this.generateOrderBook();
                    
                    // Start Loop
                    requestAnimationFrame(() => this.loop());
                    setInterval(() => this.logicTick(), 1000);
                    setInterval(() => this.generateOrderBook(), 1500);
                },

                setupCanvas() {
                    if(!this.canvas) return;
                    const container = this.canvas.parentElement;
                    const dpr = window.devicePixelRatio || 1;
                    
                    this.canvas.width = container.clientWidth * dpr;
                    this.canvas.height = container.clientHeight * dpr;
                    
                    this.ctx = this.canvas.getContext('2d');
                    this.ctx.scale(dpr, dpr);
                },

                generateHistory() {
                    let p = this.lastPrice;
                    // Generate 60 candles
                    for(let i=0; i<60; i++) {
                        let o = p;
                        let c = o + (Math.random() - 0.5) * 50;
                        let h = Math.max(o, c) + Math.random() * 10;
                        let l = Math.min(o, c) - Math.random() * 10;
                        this.candles.push({ o, h, l, c });
                        p = c;
                    }
                    this.lastPrice = p;
                },

                generateOrderBook() {
                    this.asks = Array.from({length: 10}, (_, i) => ({
                        id: 'a'+i, 
                        price: this.lastPrice + (i*2) + Math.random(), 
                        amount: Math.random()
                    }));
                    this.bids = Array.from({length: 10}, (_, i) => ({
                        id: 'b'+i, 
                        price: this.lastPrice - (i*2) - Math.random(), 
                        amount: Math.random()
                    }));
                },

                formatTimer() {
                    return `00:${this.timer.toString().padStart(2, '0')}`;
                },

                // Main Logic Loop (1s Tick)
                logicTick() {
                    this.timer--;
                    
                    if(this.phase === 'open' && this.timer <= 0) {
                        this.phase = 'locked';
                        this.timer = 20;
                    } else if(this.phase === 'locked' && this.timer <= 0) {
                        this.settle();
                        this.phase = 'open';
                        this.timer = 10;
                    }
                    
                    // Add Candle every 3s
                    if(this.timer % 3 === 0) {
                        let o = this.lastPrice;
                        this.candles.push({ o, h:o, l:o, c:o });
                        if(this.candles.length > 70) this.candles.shift();
                    }
                },

                // Animation Loop (60fps)
                loop() {
                    // Update Price (Random Walk)
                    this.prevPrice = this.lastPrice;
                    let vol = this.phase === 'locked' ? 5 : 2; // More volatile when locked
                    this.lastPrice += (Math.random() - 0.5) * vol;
                    
                    // Update Last Candle
                    let lc = this.candles[this.candles.length-1];
                    lc.c = this.lastPrice;
                    if(this.lastPrice > lc.h) lc.h = this.lastPrice;
                    if(this.lastPrice < lc.l) lc.l = this.lastPrice;

                    this.draw();
                    requestAnimationFrame(() => this.loop());
                },

                placeOrder(type) {
                    if(this.balance < this.betAmount) return;
                    this.balance -= this.betAmount;
                    window.userBalance = this.balance;
                    
                    this.myPosition = {
                        type: type,
                        entry: this.lastPrice,
                        amount: this.betAmount
                    };
                },

                settle() {
                    if(!this.myPosition) return;
                    
                    let diff = this.lastPrice - this.myPosition.entry;
                    let win = (this.myPosition.type === 'buy' && diff > 0) || 
                              (this.myPosition.type === 'sell' && diff < 0);
                    
                    this.lastWin = win;
                    if(win) {
                        let profit = this.myPosition.amount * 1.8; // 80% Profit
                        this.lastPnL = profit - this.myPosition.amount;
                        this.balance += profit;
                    } else {
                        this.lastPnL = -this.myPosition.amount;
                    }
                    
                    window.userBalance = this.balance;
                    this.showResult = true;
                    setTimeout(() => { this.showResult = false; }, 3000);
                    this.myPosition = null;
                },

                draw() {
                    if(!this.ctx || !this.canvas) return;
                    const w = this.canvas.width / (window.devicePixelRatio || 1);
                    const h = this.canvas.height / (window.devicePixelRatio || 1);
                    const ctx = this.ctx;
                    
                    ctx.clearRect(0, 0, w, h);
                    
                    // Grid
                    ctx.strokeStyle = '#2b3139';
                    ctx.lineWidth = 1;
                    ctx.beginPath();
                    for(let x=0; x<w; x+=60) { ctx.moveTo(x,0); ctx.lineTo(x,h); }
                    for(let y=0; y<h; y+=60) { ctx.moveTo(0,y); ctx.lineTo(w,y); }
                    ctx.stroke();

                    // Candles
                    let min = Math.min(...this.candles.map(c=>c.l));
                    let max = Math.max(...this.candles.map(c=>c.h));
                    let range = max - min + 10; // padding
                    min -= 5;
                    
                    let candleW = (w / 80) * 0.7;
                    let spacing = (w / 80) * 0.3;
                    
                    this.candles.forEach((c, i) => {
                        let isGreen = c.c >= c.o;
                        ctx.fillStyle = isGreen ? '#0ecb81' : '#f6465d';
                        ctx.strokeStyle = isGreen ? '#0ecb81' : '#f6465d';
                        
                        let x = i * (candleW + spacing) + spacing + 10;
                        let yH = h - ((c.h - min) / range) * h;
                        let yL = h - ((c.l - min) / range) * h;
                        let yO = h - ((c.o - min) / range) * h;
                        let yC = h - ((c.c - min) / range) * h;
                        
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
                    let priceY = h - ((this.lastPrice - min) / range) * h;
                    ctx.strokeStyle = '#f0b90b';
                    ctx.setLineDash([4, 4]);
                    ctx.beginPath();
                    ctx.moveTo(0, priceY);
                    ctx.lineTo(w, priceY);
                    ctx.stroke();
                    ctx.setLineDash([]);
                    
                    // Bubble
                    ctx.fillStyle = '#f0b90b';
                    ctx.fillRect(w - 70, priceY - 10, 70, 20);
                    ctx.fillStyle = '#000';
                    ctx.font = 'bold 11px sans-serif';
                    ctx.fillText(this.lastPrice.toFixed(2), w - 65, priceY + 4);
                }
            }));
        });
    </script>
</body>
</html>