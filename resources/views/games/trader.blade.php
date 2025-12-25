<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>Crypto Trader Panic - BTC/USDT</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        body { background-color: #0b0e11; color: #eaecef; font-family: 'Inter', sans-serif; overflow: hidden; overscroll-behavior: none; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        
        /* CustomScroll */
        ::-webkit-scrollbar { width: 0px; background: transparent; }
        
        /* Animations */
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 10px rgba(240, 185, 11, 0.2); }
            50% { box-shadow: 0 0 25px rgba(240, 185, 11, 0.5); }
        }
        .animate-pulse-glow { animation: pulse-glow 2s infinite; }
        
        .crs-crosshair { cursor: crosshair; }
        
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    </style>
</head>

<body class="flex flex-col h-screen bg-[#0b0e11] text-[#eaecef]">

    <!-- NAVBAR -->
    <nav class="h-14 bg-[#181a20] border-b border-[#2b3139] flex items-center px-4 justify-between shrink-0 z-50">
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded bg-gradient-to-tr from-emerald-500 to-teal-400 text-[#0b0e11] font-black flex items-center justify-center text-lg shadow-[0_0_15px_rgba(16,185,129,0.4)]">C</div>
                <div class="flex flex-col leading-none">
                    <span class="font-black text-base tracking-tight text-white hidden md:block">Crypto Trader Panic</span>
                    <span class="font-bold text-[10px] text-slate-500 hidden md:block">ALGORITHM: BINOMO-V2</span>
                </div>
            </div>
            
            <div class="hidden md:flex items-center gap-3 border-l border-[#2b3139] pl-4 ml-2">
                <div class="flex flex-col">
                    <span class="font-bold text-sm text-white leading-none">BTC/USDT</span>
                    <span class="text-[10px] font-bold text-slate-500">Perpetual</span>
                </div>
                <span class="text-[10px] font-bold text-emerald-400 bg-emerald-400/10 px-1.5 py-0.5 rounded border border-emerald-400/20">+2.45%</span>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="bg-[#2b3139] px-4 py-1.5 rounded-lg flex items-center gap-3 border border-[#474d57]/50 shadow-inner">
                <div class="flex flex-col items-end leading-none">
                    <span class="text-[9px] text-slate-400 uppercase font-black tracking-widest mb-0.5">Live Account</span>
                    <span class="font-mono font-bold text-white text-sm" x-data x-text="'$' + (window.userBalance?.toLocaleString('en-US', {minimumFractionDigits: 2}) ?? '1,000.00')"></span>
                </div>
                <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
            </div>
            <a href="{{ route('homepage') }}" class="text-slate-400 hover:text-white px-3 py-1.5 hover:bg-[#2b3139] rounded text-xs font-bold transition-colors uppercase tracking-wider border border-transparent hover:border-white/10">Exit</a>
        </div>
    </nav>

    <!-- CONTENT -->
    <div x-data="proTrader()" x-init="initTrader()" x-cloak class="flex-grow flex overflow-hidden relative">

        <!-- LEFT: CHART AREA -->
        <div class="flex-grow flex flex-col relative bg-[#0b0e11] min-w-0">
            <!-- Toolbar -->
            <div class="h-9 border-b border-[#2b3139] flex items-center px-4 gap-4 text-[10px] font-bold text-slate-500 bg-[#0b0e11] shrink-0">
                <span class="hover:text-white cursor-pointer">Time</span>
                <span class="text-[#f0b90b] bg-[#f0b90b]/10 px-1.5 py-0.5 rounded cursor-pointer">Live</span>
                <span>1s</span>
                <span>15m</span>
                <div class="w-[1px] h-3 bg-[#2b3139]"></div>
                <span class="hover:text-white cursor-pointer flex items-center gap-1"><span class="text-xs">ƒx</span> Indicators</span>
            </div>

            <!-- Canvas Container -->
            <div id="chartContainer" class="flex-grow relative w-full h-full bg-[#0b0e11] crs-crosshair" 
                 @mousemove="updateCrosshair" 
                 @mouseleave="hideCrosshair">
                
                <canvas id="tradeCanvas" class="absolute inset-0 w-full h-full block"></canvas>
                
                <!-- HUD: Phase Status -->
                <div class="absolute top-6 left-1/2 transform -translate-x-1/2 flex flex-col items-center pointer-events-none z-20 w-auto">
                    <div class="backdrop-blur-xl border px-8 py-2 rounded-full shadow-[0_10px_40px_-5px_rgba(0,0,0,0.5)] flex items-center gap-6 transition-all duration-300 transform"
                         :class="phase === 'open' ? 'bg-[#1e2329]/80 border-emerald-500/40 shadow-[0_0_20px_rgba(16,185,129,0.1)]' : 'bg-[#1e2329]/80 border-amber-500/40 shadow-[0_0_20px_rgba(245,158,11,0.1)]'">
                        
                        <div class="flex flex-col items-end">
                            <span class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 mb-0.5">Market Status</span>
                            <div class="flex items-center gap-2">
                                <div class="w-1.5 h-1.5 rounded-full animate-pulse" :class="phase === 'open' ? 'bg-emerald-400' : 'bg-amber-400'"></div>
                                <span class="font-black text-sm tracking-tight whitespace-nowrap" 
                                      :class="phase === 'open' ? 'text-emerald-400' : 'text-amber-400'"
                                      x-text="phase === 'open' ? 'ORDER WINDOW' : 'AWAITING RESULT'"></span>
                            </div>
                        </div>
                        
                        <div class="w-[1px] h-8 bg-[#474d57]/50"></div>
                        
                        <div class="flex flex-col items-start min-w-[50px]">
                            <span class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 mb-0.5">Timer</span>
                            <span class="font-mono font-bold text-2xl text-white leading-none tracking-tighter" x-text="formatTimer()"></span>
                        </div>
                    </div>
                </div>

                <!-- Hover Info (Crosshair Label) -->
                <div x-show="crosshair.visible" 
                     class="absolute bg-[#1e2329] text-white text-[10px] font-mono px-2 py-0.5 rounded pointer-events-none border border-[#474d57] z-30"
                     :style="`left: ${crosshair.x + 10}px; top: ${crosshair.y - 10}px;`">
                    <span x-text="crosshair.price"></span>
                </div>
            </div>
        </div>

        <!-- RIGHT: ORDER PANEL -->
        <div class="w-[320px] 2xl:w-[360px] bg-[#181a20] border-l border-[#2b3139] flex flex-col shrink-0 z-30 relative shadow-[-10px_0_30px_rgba(0,0,0,0.3)]">
            
            <!-- Order Book (Visual Noise) -->
            <div class="h-[40vh] flex flex-col border-b border-[#2b3139]">
                <div class="h-8 flex items-center px-4 text-[10px] font-bold text-slate-500 justify-between bg-[#181a20]">
                    <span>Price(USDT)</span>
                    <span>Amount(BTC)</span>
                </div>
                
                <div class="flex-grow relative bg-[#0b0e11] overflow-hidden flex flex-col text-[10px] font-mono">
                    <!-- Sells -->
                    <div class="flex-1 overflow-hidden flex flex-col-reverse justify-start pb-1">
                        <template x-for="ask in asks" :key="ask.id">
                            <div class="flex justify-between px-4 py-[1px] relative items-center hover:bg-[#1e2329] transition-colors group">
                                <span class="text-[#f6465d] z-10 font-medium group-hover:text-white" x-text="ask.price.toFixed(2)"></span>
                                <span class="text-slate-500 z-10 group-hover:text-slate-300" x-text="ask.amount.toFixed(4)"></span>
                                <div class="absolute right-0 top-0 bottom-0 bg-[#f6465d]/10 transition-all duration-300" :style="'width: ' + (ask.amount * 60) + '%'"></div>
                            </div>
                        </template>
                    </div>
                    
                    <!-- Middle Ticker -->
                    <div class="h-12 flex items-center justify-between px-4 border-y border-[#2b3139] bg-[#14161b] shrink-0 z-20 shadow-lg">
                        <span class="text-2xl font-bold font-mono tracking-tight transition-colors duration-300" 
                              :class="lastPrice >= prevPrice ? 'text-[#0ecb81]' : 'text-[#f6465d]'"
                              x-text="lastPrice.toFixed(2)"></span>
                        <div class="flex flex-col items-end">
                             <span class="text-[10px] text-slate-500 font-bold">Mark Price</span>
                             <div class="flex items-center gap-1 text-xs font-bold" :class="lastPrice >= prevPrice ? 'text-emerald-500' : 'text-rose-500'">
                                 <span x-text="lastPrice >= prevPrice ? '↑' : '↓'"></span>
                                 <span>0.05%</span>
                             </div>
                        </div>
                    </div>

                    <!-- Buys -->
                    <div class="flex-1 overflow-hidden pt-1">
                        <template x-for="bid in bids" :key="bid.id">
                             <div class="flex justify-between px-4 py-[1px] relative items-center hover:bg-[#1e2329] transition-colors group">
                                <span class="text-[#0ecb81] z-10 font-medium group-hover:text-white" x-text="bid.price.toFixed(2)"></span>
                                <span class="text-slate-500 z-10 group-hover:text-slate-300" x-text="bid.amount.toFixed(4)"></span>
                                <div class="absolute right-0 top-0 bottom-0 bg-[#0ecb81]/10 transition-all duration-300" :style="'width: ' + (bid.amount * 60) + '%'"></div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Interaction Panel -->
            <div class="flex-grow p-5 bg-[#1e2329] flex flex-col gap-4 overflow-y-auto">
                
                <!-- Type Tabs -->
                <div class="flex bg-[#0b0e11] p-[3px] rounded-lg border border-[#2b3139]">
                    <button class="flex-1 py-2 rounded-md text-[11px] font-bold bg-[#2b3139] text-white shadow-sm transition-all">Turbo Mode</button>
                    <button class="flex-1 py-2 rounded-md text-[11px] font-bold text-slate-500 hover:text-white transition-all">Classic</button>
                </div>

                <!-- Input Group -->
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-[10px] font-bold text-slate-400 mb-2">
                            <span>Investment Amount</span>
                            <span class="text-emerald-400">Max: 5,000</span>
                        </div>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 w-10 flex items-center justify-center text-slate-500 border-r border-[#2b3139] font-bold text-sm">$</div>
                            <input type="number" x-model.number="betAmount" class="w-full bg-[#0b0e11] border border-[#2b3139] rounded-lg h-12 pl-12 pr-4 text-white font-mono font-bold text-lg focus:ring-2 focus:ring-[#f0b90b]/50 focus:border-[#f0b90b] transition-all outline-none">
                        </div>
                        
                        <!-- Quick Select -->
                        <div class="grid grid-cols-4 gap-2 mt-2">
                            <button @click="betAmount = 20" class="bg-[#2b3139] hover:bg-[#363c45] text-slate-300 text-[10px] font-bold py-1 rounded transition-colors">$20</button>
                            <button @click="betAmount = 50" class="bg-[#2b3139] hover:bg-[#363c45] text-slate-300 text-[10px] font-bold py-1 rounded transition-colors">$50</button>
                            <button @click="betAmount = 100" class="bg-[#2b3139] hover:bg-[#363c45] text-slate-300 text-[10px] font-bold py-1 rounded transition-colors">$100</button>
                            <button @click="betAmount = 500" class="bg-[#2b3139] hover:bg-[#363c45] text-white text-[10px] font-bold py-1 rounded transition-colors border border-slate-600">$500</button>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-[#2b3139]">
                        <div class="flex justify-between items-center mb-4">
                             <div class="text-xs font-bold text-slate-400">Profitability</div>
                             <div class="text-xl font-black text-emerald-400 drop-shadow-[0_0_8px_rgba(52,211,153,0.5)]">82%</div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <button @click="placeOrder('buy')" 
                                    :disabled="phase !== 'open' || myPosition"
                                    class="h-14 bg-[#0ecb81] hover:bg-[#0da86b] disabled:opacity-30 disabled:cursor-not-allowed rounded-xl text-white font-black text-lg shadow-[0_4px_0_#065f3d] active:shadow-none active:translate-y-[4px] flex flex-col items-center justify-center leading-none group transition-all relative overflow-hidden">
                                <span class="relative z-10 mb-1">HIGHER</span>
                                <span class="relative z-10 text-[9px] font-bold bg-black/20 px-2 rounded text-emerald-100">BUY GREEN</span>
                            </button>
                            
                            <button @click="placeOrder('sell')" 
                                    :disabled="phase !== 'open' || myPosition"
                                    class="h-14 bg-[#f6465d] hover:bg-[#d93a4e] disabled:opacity-30 disabled:cursor-not-allowed rounded-xl text-white font-black text-lg shadow-[0_4px_0_#8e1626] active:shadow-none active:translate-y-[4px] flex flex-col items-center justify-center leading-none group transition-all relative overflow-hidden">
                                <span class="relative z-10 mb-1">LOWER</span>
                                <span class="relative z-10 text-[9px] font-bold bg-black/20 px-2 rounded text-rose-100">SELL RED</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Position Card -->
                <div x-show="myPosition" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="bg-[#0b0e11] rounded-xl border p-4 shadow-lg mt-auto"
                     :class="myPosition?.type === 'buy' ? 'border-[#0ecb81]' : 'border-[#f6465d]'">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-[10px] font-bold uppercase text-slate-400">Open Position</span>
                        <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded text-white" 
                              :class="myPosition?.type === 'buy' ? 'bg-[#0ecb81]' : 'bg-[#f6465d]'"
                              x-text="myPosition?.type === 'buy' ? 'LONG' : 'SHORT'"></span>
                    </div>
                    <div class="flex justify-between items-end">
                        <div>
                             <div class="text-[10px] text-slate-500 font-bold mb-0.5">Entry</div>
                             <div class="font-mono text-sm text-white font-bold" x-text="myPosition?.entry.toFixed(2)"></div>
                        </div>
                        <div class="text-right">
                             <div class="text-[10px] text-slate-500 font-bold mb-0.5">PnL (Proj)</div>
                             <div class="font-mono text-lg font-black" 
                                  :class="(lastPrice - myPosition?.entry) * (myPosition?.type==='buy'?1:-1) > 0 ? 'text-[#0ecb81]' : 'text-[#f6465d]'">
                                  <span x-text="(lastPrice - myPosition?.entry) * (myPosition?.type==='buy'?1:-1) > 0 ? '+' : ''"></span>
                                  <span x-text="((lastPrice - myPosition?.entry) * (myPosition?.type==='buy'?1:-1)).toFixed(2)"></span>
                             </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- RESULT OVERLAY -->
        <div x-show="showResult" 
             style="display: none;"
             class="absolute inset-0 z-[60] flex items-center justify-center bg-black/40 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100">
            <div class="bg-[#1e2329] p-8 rounded-[32px] shadow-[0_20px_60px_rgba(0,0,0,0.5)] text-center transform scale-100 animate-bounce border-2 min-w-[320px]"
                 :class="lastWin ? 'border-[#0ecb81] shadow-[0_0_50px_rgba(16,185,129,0.2)]' : 'border-[#f6465d] shadow-[0_0_50px_rgba(246,70,93,0.2)]'">
                
                <div class="w-20 h-20 rounded-full mx-auto mb-4 flex items-center justify-center text-4xl shadow-inner bg-[#0b0e11]">
                    <span x-text="lastWin ? '💰' : '💀'"></span>
                </div>
                
                <h2 class="text-5xl font-black mb-1 tracking-tight" :class="lastWin ? 'text-[#0ecb81]' : 'text-[#f6465d]'" x-text="lastWin ? 'WINNER' : 'REKT'"></h2>
                <div class="text-slate-400 text-sm font-bold uppercase tracking-widest mb-6">Trade Result</div>
                
                <div class="bg-[#0b0e11] rounded-2xl p-4 border border-[#2b3139]">
                    <div class="flex justify-between text-xs text-slate-500 font-bold mb-1">
                        <span>Payout</span>
                        <span>Amount</span>
                    </div>
                    <div class="flex justify-between items-baseline">
                         <span class="font-mono font-bold text-3xl" :class="lastWin ? 'text-[#0ecb81]' : 'text-slate-300'" x-text="(lastWin ? '+' : '') + '$' + Math.abs(lastPnL + (lastWin ? myPosition?.amount : 0)).toFixed(2)"></span>
                         <span class="font-mono text-sm text-slate-500 line-through" x-show="!lastWin" x-text="'$' + myPosition?.amount"></span>
                    </div>
                </div>
                
                <button @click="showResult=false" class="mt-6 w-full py-4 bg-[#2b3139] hover:bg-[#363c45] rounded-xl text-white font-bold transition-colors">CONTINUE TRADING</button>
            </div>
        </div>

    </div>

    <!-- LOGIC -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('proTrader', () => ({
                // Game Config
                phase: 'open',
                timer: 10,
                balance: 1000,
                lastPrice: 65000.00,
                prevPrice: 65000.00,
                betAmount: 100,
                
                // Data
                candles: [],
                asks: [],
                bids: [],
                
                // State
                myPosition: null, // {type, entry, amount}
                showResult: false,
                lastWin: false,
                lastPnL: 0,
                crosshair: { x:0, y:0, visible:false, price:0 },
                
                // Render Config
                canvas: null,
                ctx: null,
                maxCandles: 70,

                initTrader() {
                    window.userBalance = this.balance;
                    this.canvas = document.getElementById('tradeCanvas');
                    
                    // Initial Setup
                    this.fillHistory();
                    this.generateOrderBook();
                    
                    // Resize Observer
                    new ResizeObserver(() => {
                         this.setupCanvas();
                         this.draw();
                    }).observe(document.getElementById('chartContainer'));

                    // Loops
                    this.startInternalLoops();
                    requestAnimationFrame(() => this.renderLoop());
                },

                setupCanvas() {
                    const container = this.canvas.parentElement;
                    const dpr = window.devicePixelRatio || 1;
                    this.canvas.width = container.clientWidth * dpr;
                    this.canvas.height = container.clientHeight * dpr;
                    this.ctx = this.canvas.getContext('2d');
                    this.ctx.scale(dpr, dpr);
                },

                fillHistory() {
                    // Pre-fill chart strictly to maxCandles
                    this.candles = [];
                    let p = this.lastPrice;
                    for(let i=0; i<this.maxCandles; i++) {
                        let o = p;
                        let c = o + (Math.random() - 0.5) * 40;
                        let h = Math.max(o,c) + Math.random() * 10;
                        let l = Math.min(o,c) - Math.random() * 10;
                        this.candles.push({o, h, l, c});
                        p = c;
                    }
                    this.lastPrice = p;
                },

                startInternalLoops() {
                    // 1. Timer & Phase Logic (1s)
                    setInterval(() => {
                        this.timer--;
                        if(this.phase === 'open' && this.timer <= 0) {
                            this.phase = 'locked';
                            this.timer = 20;
                        } else if(this.phase === 'locked' && this.timer <= 0) {
                            this.settle();
                            this.phase = 'open';
                            this.timer = 10;
                        }
                    }, 1000);

                    // 2. Candle Generation (Every 2s push new candle to keep moving)
                    setInterval(() => {
                         let o = this.lastPrice;
                         this.candles.push({o, h:o, l:o, c:o});
                         if(this.candles.length > this.maxCandles) this.candles.shift();
                    }, 2000);
                    
                    // 3. Order Book Update (Fast)
                    setInterval(() => this.generateOrderBook(), 800);
                },

                renderLoop() {
                    // Smooth Price Movement (Per Frame)
                    // We add 'micro' movements to lastPrice to simulate live ticker
                    let volatility = this.phase === 'locked' ? 2.5 : 0.8;
                    let noise = (Math.random() - 0.5) * volatility;
                    
                    this.prevPrice = this.lastPrice;
                    this.lastPrice += noise;
                    
                    // Update current live candle
                    let lastCandle = this.candles[this.candles.length-1];
                    lastCandle.c = this.lastPrice;
                    if(this.lastPrice > lastCandle.h) lastCandle.h = this.lastPrice;
                    if(this.lastPrice < lastCandle.l) lastCandle.l = this.lastPrice;

                    this.draw();
                    requestAnimationFrame(() => this.renderLoop());
                },

                draw() {
                    if(!this.ctx) return;
                    const w = this.canvas.width / (window.devicePixelRatio || 1);
                    const h = this.canvas.height / (window.devicePixelRatio || 1);
                    const ctx = this.ctx;
                    
                    ctx.clearRect(0, 0, w, h);
                    
                    // 1. Grid & Gradient Background
                    let gradient = ctx.createLinearGradient(0, 0, 0, h);
                    gradient.addColorStop(0, '#0b0e11');
                    gradient.addColorStop(1, '#15191f');
                    ctx.fillStyle = gradient;
                    ctx.fillRect(0,0,w,h);
                    
                    ctx.strokeStyle = '#2b3139';
                    ctx.beginPath();
                    // Dynamic Grid
                    const gridSize = 60;
                    for(let x=w%gridSize; x<w; x+=gridSize) { ctx.moveTo(x,0); ctx.lineTo(x,h); }
                    for(let y=h%gridSize; y<h; y+=gridSize) { ctx.moveTo(0,y); ctx.lineTo(w,y); }
                    ctx.stroke();

                    // 2. Calc Range
                    let min = Math.min(...this.candles.map(c=>c.l)) - 20;
                    let max = Math.max(...this.candles.map(c=>c.h)) + 20;
                    let range = max - min;
                    if(range < 1) range = 1;

                    // 3. Draw Candles
                    // Calculate width to EXACTLY fill the screen based on maxCandles
                    let totalPads = this.maxCandles + 1;
                    // width = N*candleW + (N+1)*spacing
                    // let ratio = 0.7 candle, 0.3 space
                    let unitW = w / this.maxCandles;
                    let candleW = unitW * 0.7;
                    let spacing = unitW * 0.3;

                    this.candles.forEach((c, i) => {
                        let isGreen = c.c >= c.o;
                        let color = isGreen ? '#0ecb81' : '#f6465d';
                        
                        let x = i * unitW + (spacing/2);
                        
                        let yH = h - ((c.h - min) / range) * h;
                        let yL = h - ((c.l - min) / range) * h;
                        let yO = h - ((c.o - min) / range) * h;
                        let yC = h - ((c.c - min) / range) * h;
                        
                        ctx.fillStyle = color;
                        ctx.strokeStyle = color;
                        
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
                        
                        // Glow for last candle
                        if(i === this.candles.length - 1) {
                             ctx.shadowBlur = 15;
                             ctx.shadowColor = color;
                             ctx.fillRect(x, top, candleW, height);
                             ctx.shadowBlur = 0;
                        }
                    });
                    
                    // 4. Current Price Line
                    let priceY = h - ((this.lastPrice - min) / range) * h;
                    
                    ctx.beginPath();
                    ctx.strokeStyle = 'rgba(255, 255, 255, 0.5)';
                    ctx.setLineDash([2, 4]);
                    ctx.moveTo(0, priceY);
                    ctx.lineTo(w, priceY);
                    ctx.stroke();
                    ctx.setLineDash([]);
                    
                    // Puck/Dot at end
                    ctx.beginPath();
                    ctx.arc(w - 60, priceY, 3, 0, Math.PI*2);
                    ctx.fillStyle = '#fff';
                    ctx.fill();
                    
                    // Tag
                    ctx.fillStyle = this.lastPrice >= this.prevPrice ? '#0ecb81' : '#f6465d';
                    ctx.fillRect(w - 70, priceY - 11, 70, 22);
                    ctx.fillStyle = '#fff';
                    ctx.font = 'bold 11px JetBrains Mono';
                    ctx.fillText(this.lastPrice.toFixed(2), w - 64, priceY + 4);

                    // 5. Crosshair
                    if(this.crosshair.visible) {
                        let cy = this.crosshair.y;
                        let cx = this.crosshair.x;
                        
                        ctx.strokeStyle = 'rgba(255, 255, 255, 0.4)';
                        ctx.setLineDash([4, 4]);
                        ctx.lineWidth = 1;
                        
                        ctx.beginPath();
                        ctx.moveTo(0, cy);
                        ctx.lineTo(w, cy);
                        ctx.moveTo(cx, 0);
                        ctx.lineTo(cx, h);
                        ctx.stroke();
                        ctx.setLineDash([]);
                        
                        // Calculate price from Y
                        // y = h - ((p - min)/range)*h => p = min + (h-y)/h * range
                        let priceAtMouse = min + ((h - cy)/h) * range;
                        this.crosshair.price = priceAtMouse.toFixed(2);
                    }
                    
                    // 6. Entry Line (if active)
                    if(this.myPosition) {
                         let entryY = h - ((this.myPosition.entry - min) / range) * h;
                         ctx.strokeStyle = '#3b82f6';
                         ctx.lineWidth = 2;
                         ctx.beginPath();
                         ctx.moveTo(0, entryY);
                         ctx.lineTo(w, entryY);
                         ctx.stroke();
                         
                         // Label logic... active trade
                    }
                },
                
                updateCrosshair(e) {
                    const rect = this.canvas.getBoundingClientRect();
                    this.crosshair.x = e.clientX - rect.left;
                    this.crosshair.y = e.clientY - rect.top;
                    this.crosshair.visible = true;
                },
                
                hideCrosshair() {
                    this.crosshair.visible = false;
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
                   let win = (this.myPosition.type === 'buy' && diff > 0) || (this.myPosition.type === 'sell' && diff < 0);
                   
                   this.lastWin = win;
                   if(win) {
                       let pnl = this.myPosition.amount * 0.82;
                       this.lastPnL = pnl;
                       this.balance += (this.myPosition.amount + pnl);
                   } else {
                       this.lastPnL = -this.myPosition.amount;
                   }
                   
                   window.userBalance = this.balance;
                   this.showResult = true; // Show popup
                   this.myPosition = null;
                },

                formatTimer() {
                    return `00:${this.timer.toString().padStart(2, '0')}`;
                },
                
                generateOrderBook() {
                    this.asks = Array.from({length: 12}, (_, i) => ({
                         id: 'a'+i,
                         price: this.lastPrice + (i*1.5) + Math.random(),
                         amount: Math.random() * 2
                    }));
                    this.bids = Array.from({length: 12}, (_, i) => ({
                         id: 'b'+i,
                         price: this.lastPrice - (i*1.5) - Math.random(),
                         amount: Math.random() * 2
                    }));
                }
            }));
        });
    </script>
</body>
</html>