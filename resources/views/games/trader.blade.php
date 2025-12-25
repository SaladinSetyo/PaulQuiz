<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>Crypto Trader Panic - Pro</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        body { background-color: #0b0e11; color: #eaecef; font-family: 'Inter', sans-serif; overflow: hidden; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: #0b0e11; }
        ::-webkit-scrollbar-thumb { background: #2b3139; border-radius: 2px; }
        
        .crs-crosshair { cursor: crosshair; }
        
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    </style>
</head>

<body class="flex flex-col h-screen bg-[#0b0e11] text-[#eaecef] select-none">

    <!-- HEADER -->
    <header class="h-12 bg-[#181a20] border-b border-[#2b3139] flex items-center px-4 shrink-0 justify-between z-50">
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-2 mr-4 hover:opacity-80 transition-opacity">
                <div class="w-6 h-6 rounded bg-[#f0b90b] text-black font-black flex items-center justify-center text-sm shadow-lg shadow-amber-400/20">C</div>
                <span class="font-black text-base tracking-tight text-white hidden md:block">Trader Panic <span class="text-[#f0b90b] text-xs align-top">PRO</span></span>
            </div>
            
            <div class="hidden md:flex items-center gap-4 border-l border-[#2b3139] pl-6 text-xs">
                 <div class="flex items-center gap-2">
                     <span class="font-black text-base">BTC/USDT</span>
                     <span class="text-[10px] bg-[#2b3139] px-1 rounded text-emerald-400 border border-emerald-400/20">+2.45%</span>
                 </div>
                 
                 <div class="flex gap-6 font-medium">
                    <div class="flex flex-col leading-none gap-0.5">
                        <span class="text-slate-500 text-[9px] font-bold">Mark</span>
                        <span class="font-mono text-white" x-data x-text="window.game?.lastPrice?.toFixed(2) ?? '---'"></span>
                    </div>
                    <div class="flex flex-col leading-none gap-0.5 hidden lg:flex">
                         <span class="text-slate-500 text-[9px] font-bold">24h High</span>
                         <span class="font-mono text-slate-300">67,450.00</span>
                    </div>
                     <div class="flex flex-col leading-none gap-0.5 hidden lg:flex">
                         <span class="text-slate-500 text-[9px] font-bold">24h Vol</span>
                         <span class="font-mono text-slate-300">4.2B</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
             <!-- Status Pill -->
             <div class="bg-[#2b3139] rounded px-3 py-1 flex items-center gap-2 border border-[#474d57]/30" x-data>
                 <div class="w-1.5 h-1.5 rounded-full animate-pulse" :class="window.game?.phase === 'open' ? 'bg-emerald-500' : 'bg-amber-500'"></div>
                 <span class="font-mono font-bold text-white text-xs" x-text="window.game?.phase === 'open' ? 'TRADING OPEN' : 'LOCKED'"></span>
                 <span class="font-mono font-bold text-[#f0b90b] text-sm w-12 text-right" x-text="window.game?.formatTimer() ?? '00:00'"></span>
             </div>

             <div class="flex flex-col items-end leading-none mx-2">
                  <span class="text-[9px] text-slate-500 font-bold uppercase">Balance</span>
                  <span class="font-mono font-bold text-white text-sm" x-text="'$' + (window.userBalance?.toLocaleString('en-US') ?? '1,000')"></span>
             </div>
             
             <a href="{{ route('homepage') }}" class="text-slate-400 hover:text-white px-3 py-1 bg-[#2b3139] hover:bg-[#363c45] rounded text-[10px] font-bold border border-[#474d57]">EXIT</a>
        </div>
    </header>

    <!-- MAIN BODY -->
    <div x-data="proTrader()" x-init="initTrader()" x-cloak class="flex-grow flex overflow-hidden">
        
        <!-- LEFT PANEL: CHART (Takes available space) -->
        <div class="flex-grow flex flex-col min-w-0 bg-[#0b0e11] relative border-r border-[#2b3139]">
            
            <!-- Chart Toolbar -->
            <div class="h-8 border-b border-[#2b3139] flex items-center px-4 gap-3 text-[10px] font-bold text-slate-500 bg-[#0b0e11] shrink-0">
                <span class="text-white">Time</span>
                <span class="text-[#f0b90b] cursor-pointer">1s</span>
                <span class="hover:text-white cursor-pointer transition-colors">15m</span>
                <span class="hover:text-white cursor-pointer transition-colors">1H</span>
                <div class="w-[1px] h-3 bg-[#2b3139]"></div>
                <span class="hover:text-white cursor-pointer transition-colors">Indicators</span>
                <span class="hover:text-white cursor-pointer transition-colors">Display</span>
            </div>

            <!-- Canvas Area -->
            <div id="chartContainer" class="flex-grow relative w-full h-full bg-[#0b0e11] crs-crosshair"
                 @mousemove="updateCrosshair" 
                 @mouseleave="hideCrosshair">
                <canvas id="tradeCanvas" class="absolute inset-0 w-full h-full block"></canvas>
                
                <!-- Floating Price Label -->
                <div x-show="crosshair.visible" 
                     class="absolute bg-[#1e2329] text-white text-[10px] font-mono px-1.5 py-0.5 rounded pointer-events-none z-30 shadow border border-slate-600"
                     :style="`left: ${crosshair.x + 10}px; top: ${crosshair.y - 12}px;`">
                    <span x-text="crosshair.price"></span>
                </div>
            </div>

            <!-- Bottom Tabs: Positions -->
            <div class="h-[180px] border-t border-[#2b3139] bg-[#0b0e11] flex flex-col z-20">
                <div class="h-8 flex items-center px-4 gap-6 border-b border-[#2b3139] bg-[#14161b]">
                    <span class="text-[11px] font-bold text-[#f0b90b] border-b-2 border-[#f0b90b] h-full flex items-center cursor-pointer px-1">Positions <span x-text="myPosition ? '(1)' : '(0)'"></span></span>
                    <span class="text-[11px] font-bold text-slate-500 h-full flex items-center cursor-pointer hover:text-white px-1">Open Orders (0)</span>
                    <span class="text-[11px] font-bold text-slate-500 h-full flex items-center cursor-pointer hover:text-white px-1">Order History</span>
                </div>
                
                <div class="flex-grow p-0 overflow-y-auto custom-scrollbar">
                    <table class="w-full text-left font-mono" x-show="myPosition">
                         <thead class="text-[10px] text-slate-500 sticky top-0 bg-[#0b0e11]">
                            <tr>
                                <th class="px-4 py-2 font-normal">Symbol</th>
                                <th class="px-4 py-2 font-normal">Side</th>
                                <th class="px-4 py-2 font-normal">Size</th>
                                <th class="px-4 py-2 font-normal text-right">Entry Price</th>
                                <th class="px-4 py-2 font-normal text-right">Mark Price</th>
                                <th class="px-4 py-2 font-normal text-right">PnL</th>
                            </tr>
                        </thead>
                       <tbody class="text-xs text-white">
                            <tr class="bg-[#1e2329]/30 border-b border-[#2b3139]">
                                <td class="px-4 py-2 font-bold text-[#f0b90b]">BTCUSDT</td>
                                <td class="px-4 py-2 font-bold" :class="myPosition?.type==='buy' ? 'text-emerald-400' : 'text-rose-400'" x-text="myPosition?.type==='buy'?'Long':'Short'"></td>
                                <td class="px-4 py-2" x-text="myPosition?.amount"></td>
                                <td class="px-4 py-2 text-right" x-text="myPosition?.entry.toFixed(2)"></td>
                                <td class="px-4 py-2 text-right" x-text="lastPrice.toFixed(2)"></td>
                                <td class="px-4 py-2 font-bold text-right" :class="(lastPrice - myPosition?.entry)*(myPosition?.type==='buy'?1:-1) > 0 ? 'text-emerald-400' : 'text-rose-400'">
                                    <span x-text="((lastPrice - myPosition?.entry)*(myPosition?.type==='buy'?1:-1)).toFixed(2)"></span> USDT
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                     <div x-show="!myPosition" class="h-24 flex flex-col items-center justify-center text-slate-600 gap-2">
                        <span class="text-[10px] font-bold opacity-50">No Active Positions</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT PANEL: ORDER BOOK & ENTRY (Fixed Width) -->
        <div class="w-[300px] bg-[#181a20] flex flex-col shrink-0 z-40 relative">
            
            <!-- Order Book -->
            <div class="h-[50%] flex flex-col border-b border-[#2b3139] bg-[#181a20]">
                <div class="h-8 flex items-center px-3 justify-between bg-[#181a20]">
                     <span class="text-[11px] font-bold text-white">Order Book</span>
                </div>
                <div class="px-3 py-1 flex justify-between text-[9px] font-bold text-slate-500 uppercase tracking-wide">
                    <span>Price</span>
                    <span>Amount</span>
                    <span>Total</span>
                </div>
                
                <div class="flex-grow relative overflow-hidden flex flex-col font-mono text-[10px]">
                    <!-- Sells -->
                    <div class="flex-1 overflow-hidden flex flex-col-reverse justify-start pb-0.5">
                        <template x-for="ask in asks" :key="ask.id">
                            <div class="flex justify-between px-3 py-[1px] relative hover:bg-[#2b3139] cursor-pointer group">
                                <span class="text-[#f6465d] group-hover:text-white z-10" x-text="ask.price.toFixed(2)"></span>
                                <span class="text-slate-400 z-10" x-text="ask.amount.toFixed(3)"></span>
                                <span class="text-slate-600 z-10" x-text="(ask.price * ask.amount / 1000).toFixed(1)+'k'"></span>
                                <div class="absolute right-0 top-0 bottom-0 bg-[#f6465d]/10 transition-all" :style="'width: '+ (ask.amount*30) +'%'"></div>
                            </div>
                        </template>
                    </div>
                    
                    <!-- Spread/Ticker -->
                    <div class="h-9 flex items-center px-3 gap-2 border-y border-[#2b3139] bg-[#131519]">
                        <span class="text-lg font-bold tracking-tight" :class="lastPrice >= prevPrice ? 'text-[#0ecb81]' : 'text-[#f6465d]'" x-text="lastPrice.toFixed(2)"></span>
                        <span class="text-[9px] font-bold text-slate-500">Mark</span>
                    </div>
                    
                    <!-- Buys -->
                    <div class="flex-1 overflow-hidden pt-0.5">
                        <template x-for="bid in bids" :key="bid.id">
                             <div class="flex justify-between px-3 py-[1px] relative hover:bg-[#2b3139] cursor-pointer group">
                                <span class="text-[#0ecb81] group-hover:text-white z-10" x-text="bid.price.toFixed(2)"></span>
                                <span class="text-slate-400 z-10" x-text="bid.amount.toFixed(3)"></span>
                                <span class="text-slate-600 z-10" x-text="(bid.price * bid.amount / 1000).toFixed(1)+'k'"></span>
                                <div class="absolute right-0 top-0 bottom-0 bg-[#0ecb81]/10 transition-all" :style="'width: '+ (bid.amount*30) +'%'"></div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Order Entry Form -->
            <div class="flex-grow flex flex-col bg-[#1e2329]">
                <!-- Tabs -->
                <div class="flex bg-[#0b0e11] text-[10px] font-bold border-b border-[#2b3139]">
                    <button class="flex-1 py-2.5 text-[#f0b90b] bg-[#1e2329] border-t-2 border-[#f0b90b]">Spot</button>
                    <button class="flex-1 py-2.5 text-slate-500 hover:text-white hover:bg-[#1e2329] transition-colors">Cross 3x</button>
                    <button class="flex-1 py-2.5 text-slate-500 hover:text-white hover:bg-[#1e2329] transition-colors">Iso 10x</button>
                </div>
                
                <div class="p-4 flex flex-col gap-4">
                    <!-- Order Type -->
                    <div class="flex bg-[#2b3139] rounded-sm p-[2px]">
                        <button class="flex-1 py-1 rounded-sm text-[10px] font-bold bg-[#474d57] text-white shadow-sm">Limit</button>
                        <button class="flex-1 py-1 rounded-sm text-[10px] font-bold text-slate-400 hover:text-white">Market</button>
                        <button class="flex-1 py-1 rounded-sm text-[10px] font-bold text-slate-400 hover:text-white">Stop</button>
                    </div>

                    <!-- Inputs -->
                    <div class="flex flex-col gap-3">
                         <div class="flex justify-between text-[10px] font-bold text-slate-400">
                             <span>Avbl</span>
                             <span class="text-white"><span x-text="balance.toFixed(2)"></span> USDT</span>
                        </div>
                        
                        <!-- Price Input -->
                        <div class="relative flex items-center bg-[#2b3139] rounded border border-[#2b3139] hover:border-[#f0b90b] transition-colors h-9 group focus-within:border-[#f0b90b]">
                            <span class="pl-3 text-[10px] font-bold text-slate-400 group-focus-within:text-[#f0b90b]">Price</span>
                            <input type="text" disabled value="Market Price" class="flex-grow bg-transparent text-right pr-3 text-xs font-bold text-white outline-none">
                            <span class="pr-2 text-[10px] font-bold text-slate-500">USDT</span>
                        </div>

                        <!-- Amount Input -->
                        <div class="relative flex items-center bg-[#2b3139] rounded border border-[#2b3139] hover:border-[#f0b90b] transition-colors h-9 group focus-within:border-[#f0b90b]">
                            <span class="pl-3 text-[10px] font-bold text-slate-400 group-focus-within:text-[#f0b90b]">Amount</span>
                            <input type="number" x-model.number="betAmount" class="flex-grow bg-transparent text-right pr-3 text-sm font-bold text-white outline-none font-mono">
                            <span class="pr-2 text-[10px] font-bold text-slate-500">USDT</span>
                        </div>
                        
                         <!-- Slider -->
                        <div class="px-1 pt-1">
                            <input type="range" min="10" max="1000" step="10" x-model.number="betAmount" class="w-full h-1 bg-slate-600 rounded-lg appearance-none cursor-pointer accent-[#f0b90b]">
                            <div class="flex justify-between mt-1">
                                <span class="w-1 h-1 bg-slate-500 rounded-full cursor-pointer hover:scale-150 transition-transform"></span>
                                <span class="w-1 h-1 bg-slate-500 rounded-full cursor-pointer hover:scale-150 transition-transform"></span>
                                <span class="w-1 h-1 bg-slate-500 rounded-full cursor-pointer hover:scale-150 transition-transform"></span>
                                <span class="w-1 h-1 bg-slate-500 rounded-full cursor-pointer hover:scale-150 transition-transform"></span>
                                <span class="w-1 h-1 bg-slate-500 rounded-full cursor-pointer hover:scale-150 transition-transform"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-2 grid grid-cols-2 gap-2">
                         <!-- LOGIN TO TRADE PLACEHOLDER (Game mechanics enabled) -->
                         <!-- Real buttons -->
                         <button @click="placeOrder('buy')" :disabled="window.game?.phase!=='open' || myPosition"
                             class="py-2.5 rounded bg-[#0ecb81] hover:bg-[#0da86b] disabled:opacity-50 disabled:cursor-not-allowed text-white text-xs font-bold shadow-lg shadow-emerald-900/20 active:translate-y-[1px] transition-all">
                             Buy Long
                        </button>
                         <button @click="placeOrder('sell')" :disabled="window.game?.phase!=='open' || myPosition"
                             class="py-2.5 rounded bg-[#f6465d] hover:bg-[#d93a4e] disabled:opacity-50 disabled:cursor-not-allowed text-white text-xs font-bold shadow-lg shadow-rose-900/20 active:translate-y-[1px] transition-all">
                             Sell Short
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- RESULT POPUP -->
        <div x-show="showResult" style="display: none;" 
             class="absolute inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm pointer-events-none"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100">
            <div class="bg-[#1e2329] p-8 rounded-xl border border-[#474d57] text-center shadow-[0_0_50px_rgba(0,0,0,0.5)] transform scale-100 animate-bounce">
                <div class="text-5xl mb-3 filter drop-shadow-lg" x-text="lastWin ? '🚀' : '💥'"></div>
                <h2 class="text-3xl font-black text-white mb-2 tracking-tight" x-text="lastWin ? 'PROFIT' : 'LOSS'"></h2>
                <div class="p-3 bg-[#0b0e11] rounded border border-[#2b3139]">
                    <div class="text-xs text-slate-500 uppercase font-bold mb-1">PnL Realized</div>
                    <div class="text-2xl font-mono font-bold" 
                         :class="lastWin ? 'text-[#0ecb81]' : 'text-[#f6465d]'"
                         x-text="(lastWin ? '+' : '') + '$' + Math.abs(lastPnL).toFixed(2)"></div>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            window.game = {};
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
                crosshair: { x:0, y:0, visible:false, price:0 },
                
                canvas: null,
                ctx: null,
                maxCandles: 30, // EXTREME ZOOM

                initTrader() {
                    window.userBalance = this.balance;
                    window.game = this;
                    this.canvas = document.getElementById('tradeCanvas');
                    this.fillHistory();
                    this.generateOrderBook();
                    
                    new ResizeObserver(() => { this.setupCanvas(); this.draw(); }).observe(document.getElementById('chartContainer'));

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
                    this.candles = [];
                    let p = this.lastPrice;
                    for(let i=0; i<this.maxCandles; i++) {
                        let o = p;
                        let c = o + (Math.random() - 0.5) * 50; 
                        let h = Math.max(o,c) + Math.random() * 10;
                        let l = Math.min(o,c) - Math.random() * 10;
                        this.candles.push({o, h, l, c});
                        p = c;
                    }
                    this.lastPrice = p;
                },

                startInternalLoops() {
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

                    setInterval(() => {
                         let o = this.lastPrice;
                         this.candles.push({o, h:o, l:o, c:o});
                         if(this.candles.length > this.maxCandles) this.candles.shift();
                    }, 2000);
                    
                    setInterval(() => this.generateOrderBook(), 1000);
                },

                renderLoop() {
                    this.prevPrice = this.lastPrice;
                    let vol = this.phase === 'locked' ? 6 : 2; 
                    this.lastPrice += (Math.random() - 0.5) * vol;
                    
                    let lastCandle = this.candles[this.candles.length-1];
                    lastCandle.c = this.lastPrice;
                    if(this.lastPrice > lastCandle.h) lastCandle.h = this.lastPrice;
                    if(this.lastPrice < lastCandle.l) lastCandle.l = this.lastPrice;

                    this.draw();
                    requestAnimationFrame(() => this.renderLoop());
                },

                placeOrder(type) {
                    if(this.balance < this.betAmount) return;
                    this.balance -= this.betAmount;
                    window.userBalance = this.balance;
                    this.myPosition = { type: type, entry: this.lastPrice, amount: this.betAmount };
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
                   this.myPosition = null;
                   this.showResult = true;
                   setTimeout(() => this.showResult = false, 3000);
                },

                formatTimer() {
                    return `00:${this.timer.toString().padStart(2, '0')}`;
                },
                
                generateOrderBook() {
                    this.asks = Array.from({length: 12}, (_, i) => ({ id: 'a'+i, price: this.lastPrice + (i*2.5) + Math.random(), amount: Math.random() }));
                    this.bids = Array.from({length: 12}, (_, i) => ({ id: 'b'+i, price: this.lastPrice - (i*2.5) - Math.random(), amount: Math.random() }));
                },

                draw() {
                    if(!this.ctx) return;
                    const w = this.canvas.width / (window.devicePixelRatio || 1);
                    const h = this.canvas.height / (window.devicePixelRatio || 1);
                    const ctx = this.ctx;
                    
                    ctx.clearRect(0, 0, w, h);
                    
                    // BG
                    ctx.fillStyle = '#0b0e11';
                    ctx.fillRect(0,0,w,h);
                    
                    // Grid
                    ctx.strokeStyle = '#2b3139';
                    ctx.lineWidth = 1;
                    ctx.beginPath();
                    for(let x=w%80; x<w; x+=80) { ctx.moveTo(x,0); ctx.lineTo(x,h); }
                    for(let y=h%80; y<h; y+=80) { ctx.moveTo(0,y); ctx.lineTo(w,y); }
                    ctx.stroke();

                    // Scaler
                    let min = Math.min(...this.candles.map(c=>c.l)) - 10;
                    let max = Math.max(...this.candles.map(c=>c.h)) + 10;
                    let range = max - min; if(range<1) range=1;

                    // Draw Candles (BIG)
                    let unitW = w / this.maxCandles;
                    let candleW = unitW * 0.8; // Fatter
                    let spacing = unitW * 0.2;

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
                        ctx.lineWidth = 1;

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
                        
                        // Volume Bar (Opacity)
                        let volHeight = (Math.abs(c.c - c.o) / range) * h * 0.5 + 5;
                        ctx.globalAlpha = 0.2;
                        ctx.fillStyle = color;
                        ctx.fillRect(x, h - volHeight, candleW, volHeight);
                        ctx.globalAlpha = 1.0;
                    });
                    
                    // Price Line
                    let priceY = h - ((this.lastPrice - min) / range) * h;
                    ctx.strokeStyle = '#f0b90b';
                    ctx.setLineDash([2, 2]);
                    ctx.lineWidth = 1;
                    ctx.beginPath();
                    ctx.moveTo(0, priceY);
                    ctx.lineTo(w, priceY);
                    ctx.stroke();
                    ctx.setLineDash([]);
                    
                    // Price Bubble
                    ctx.fillStyle = '#f0b90b';
                    ctx.fillRect(w - 60, priceY - 10, 60, 20);
                    ctx.fillStyle = '#000';
                    ctx.font = 'bold 11px sans-serif';
                    ctx.fillText(this.lastPrice.toFixed(2), w - 54, priceY + 4);

                     // Crosshair
                    if(this.crosshair.visible) {
                        let cx = this.crosshair.x;
                        let cy = this.crosshair.y;
                        ctx.strokeStyle = '#fff';
                        ctx.setLineDash([4, 4]);
                        ctx.beginPath();
                        ctx.moveTo(0, cy);
                        ctx.lineTo(w, cy);
                        ctx.moveTo(cx, 0);
                        ctx.lineTo(cx, h);
                        ctx.stroke();
                        let priceAtMouse = min + ((h - cy)/h) * range;
                        this.crosshair.price = priceAtMouse.toFixed(2);
                    }
                }
            }));
        });
    </script>
</body>
</html>