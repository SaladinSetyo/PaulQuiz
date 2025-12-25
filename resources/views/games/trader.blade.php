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
        
        /* Scrollbar */
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: #0b0e11; }
        ::-webkit-scrollbar-thumb { background: #2b3139; border-radius: 2px; }
        
        .crs-crosshair { cursor: crosshair; }
        
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        
        /* Neon Glows */
        .glow-text-green { text-shadow: 0 0 10px rgba(14, 203, 129, 0.4); }
        .glow-text-red { text-shadow: 0 0 10px rgba(246, 70, 93, 0.4); }
    </style>
</head>

<body class="flex flex-col h-screen bg-[#0b0e11] text-[#eaecef] select-none">

    <!-- HEADER: Pro Stats Bar -->
    <header class="h-14 bg-[#181a20] border-b border-[#2b3139] flex items-center px-4 shrink-0 justify-between z-50">
        <div class="flex items-center gap-6">
            <!-- Logo area -->
            <div class="flex items-center gap-2 mr-4 cursor-pointer hover:opacity-80 transition-opacity">
                <div class="w-8 h-8 rounded bg-[#f0b90b] text-black font-black flex items-center justify-center text-lg">C</div>
                <span class="font-black text-lg tracking-tight text-white hidden md:block">Trader Panic</span>
            </div>
            
            <!-- Pair Info -->
            <div class="hidden md:flex items-center gap-4 border-l border-[#2b3139] pl-6">
                <div>
                     <div class="flex items-center gap-2">
                         <span class="font-black text-lg">BTC/USDT</span>
                         <span class="text-[10px] bg-[#2b3139] px-1 rounded text-slate-400">Perp</span>
                     </div>
                     <a href="#" class="text-[10px] font-bold text-slate-500 underline decoration-slate-600">Bitcoin Index</a>
                </div>
                
                <!-- Stats -->
                <div class="flex gap-6 text-xs font-medium ml-4">
                    <div class="flex flex-col">
                        <span class="text-slate-500 text-[10px] font-bold">Mark Price</span>
                        <span class="font-mono" :class="lastPrice >= prevPrice ? 'text-emerald-400' : 'text-rose-400'" x-data x-text="window.game?.lastPrice?.toFixed(2) ?? '---'"></span>
                    </div>
                    <div class="flex flex-col hidden lg:flex">
                        <span class="text-slate-500 text-[10px] font-bold">24h Change</span>
                        <span class="font-mono text-emerald-400">+2.45%</span>
                    </div>
                    <div class="flex flex-col hidden lg:flex">
                         <span class="text-slate-500 text-[10px] font-bold">24h High</span>
                         <span class="font-mono text-slate-200">67,450.00</span>
                    </div>
                    <div class="flex flex-col hidden lg:flex">
                         <span class="text-slate-500 text-[10px] font-bold">24h Vol(USDT)</span>
                         <span class="font-mono text-slate-200">4.2B</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4">
             <!-- Game Status Widget (Integrated) -->
             <div class="bg-[#2b3139] rounded-lg px-3 py-1 flex items-center gap-3 border border-[#474d57]/50" x-data>
                 <div class="flex flex-col items-end">
                     <span class="text-[9px] font-bold uppercase text-slate-400 tracking-widest" x-text="window.game?.phase === 'open' ? 'TRADING' : 'LOCKED'"></span>
                     <span class="font-mono font-bold text-white text-sm leading-none" x-text="window.game?.formatTimer() ?? '00:00'"></span>
                 </div>
                 <div class="w-2 h-2 rounded-full animate-pulse" :class="window.game?.phase === 'open' ? 'bg-emerald-500' : 'bg-amber-500'"></div>
             </div>

             <div class="bg-[#2b3139] px-3 py-1.5 rounded flex flex-col items-end leading-none border border-[#474d57]/50 min-w-[100px]">
                  <span class="text-[9px] text-slate-400 font-bold uppercase">Cash Balance</span>
                  <span class="font-mono font-bold text-white" x-text="'$' + (window.userBalance?.toLocaleString('en-US') ?? '1,000')"></span>
             </div>
             
             <a href="{{ route('homepage') }}" class="text-slate-400 hover:text-white transition-colors">
                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
             </a>
        </div>
    </header>

    <!-- CONTENT BODY -->
    <div x-data="proTrader()" x-init="initTrader()" x-cloak class="flex-grow flex overflow-hidden">
        
        <!-- LEFT: Chart & Footer -->
        <div class="flex-grow flex flex-col min-w-0 bg-[#0b0e11]">
            
            <!-- Toolbar -->
            <div class="h-10 border-b border-[#2b3139] flex items-center px-4 gap-4 text-xs font-bold text-slate-400 bg-[#0b0e11]">
                <span class="text-white hover:bg-[#2b3139] px-2 py-1 rounded cursor-pointer">Time</span>
                <span class="text-[#f0b90b] hover:bg-[#2b3139] px-2 py-1 rounded cursor-pointer">1s</span>
                <span class="hover:bg-[#2b3139] px-2 py-1 rounded cursor-pointer">15m</span>
                <span class="hover:bg-[#2b3139] px-2 py-1 rounded cursor-pointer">1H</span>
                <span class="hover:bg-[#2b3139] px-2 py-1 rounded cursor-pointer">4H</span>
                <div class="w-[1px] h-4 bg-[#2b3139]"></div>
                <span class="hover:bg-[#2b3139] px-2 py-1 rounded cursor-pointer">Original</span>
                <span class="hover:bg-[#2b3139] px-2 py-1 rounded cursor-pointer">TradingView</span>
                <div class="flex-grow"></div>
                <span class="text-[10px] uppercase tracking-wider text-emerald-500 flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Connected</span>
            </div>

            <!-- Chart Canvas -->
            <div id="chartContainer" class="flex-grow relative w-full h-full bg-[#0b0e11] crs-crosshair"
                 @mousemove="updateCrosshair" 
                 @mouseleave="hideCrosshair">
                <canvas id="tradeCanvas" class="absolute inset-0 w-full h-full block"></canvas>
                
                <!-- Crosshair Label -->
                <div x-show="crosshair.visible" 
                     class="absolute bg-[#2b3139] text-white text-[10px] font-mono px-1.5 py-0.5 rounded pointer-events-none z-30 shadow-md border border-[#474d57]"
                     :style="`left: ${crosshair.x + 10}px; top: ${crosshair.y - 10}px;`">
                    <span x-text="crosshair.price"></span>
                </div>
                
                <!-- Central Notification (Subtle) -->
                <div x-show="phase==='locked'" 
                     class="absolute top-4 left-1/2 transform -translate-x-1/2 bg-[#1e2329]/80 backdrop-blur border border-amber-500/30 px-4 py-1 rounded text-amber-500 text-xs font-bold shadow-lg pointer-events-none">
                     ⚠️ MARKET LOCKED - AWAITING SETTLEMENT
                </div>
            </div>

            <!-- Footer: Positions -->
            <div class="h-[200px] border-t border-[#2b3139] bg-[#0b0e11] flex flex-col">
                <div class="h-9 flex items-center px-4 gap-6 border-b border-[#2b3139] bg-[#181a20]">
                    <span class="text-xs font-bold text-[#f0b90b] border-b-2 border-[#f0b90b] h-full flex items-center cursor-pointer">Positions <span x-text="myPosition ? '(1)' : '(0)'"></span></span>
                    <span class="text-xs font-bold text-slate-500 h-full flex items-center cursor-pointer hover:text-white">Open Orders (0)</span>
                    <span class="text-xs font-bold text-slate-500 h-full flex items-center cursor-pointer hover:text-white">Order History</span>
                </div>
                
                <div class="flex-grow p-0 overflow-y-auto">
                    <!-- Empty State -->
                    <div x-show="!myPosition" class="h-full flex flex-col items-center justify-center text-slate-600">
                        <svg class="w-10 h-10 mb-2 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        <span class="text-xs font-bold">No Open Positions</span>
                    </div>
                    
                    <!-- Active Position Row -->
                    <table x-show="myPosition" class="w-full text-left font-mono">
                        <thead class="text-[10px] text-slate-500 bg-[#0b0e11]">
                            <tr>
                                <th class="px-4 py-2 font-normal">Symbol</th>
                                <th class="px-4 py-2 font-normal">Side</th>
                                <th class="px-4 py-2 font-normal">Size</th>
                                <th class="px-4 py-2 font-normal">Entry Price</th>
                                <th class="px-4 py-2 font-normal">Mark Price</th>
                                <th class="px-4 py-2 font-normal">PnL (ROE%)</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs text-white">
                            <tr class="bg-[#1e2329]/50 border-b border-[#2b3139]">
                                <td class="px-4 py-3 font-bold">BTCUSDT Perpectual</td>
                                <td class="px-4 py-3 font-bold" :class="myPosition?.type==='buy' ? 'text-emerald-400' : 'text-rose-400'" x-text="myPosition?.type==='buy'?'Long':'Short'"></td>
                                <td class="px-4 py-3" x-text="myPosition?.amount"></td>
                                <td class="px-4 py-3" x-text="myPosition?.entry.toFixed(2)"></td>
                                <td class="px-4 py-3" x-text="lastPrice.toFixed(2)"></td>
                                <td class="px-4 py-3 font-bold" :class="(lastPrice - myPosition?.entry)*(myPosition?.type==='buy'?1:-1) > 0 ? 'text-emerald-400' : 'text-rose-400'">
                                    <span x-text="((lastPrice - myPosition?.entry)*(myPosition?.type==='buy'?1:-1)).toFixed(2)"></span> USDT
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- RIGHT: Order Panel -->
        <div class="w-[320px] bg-[#181a20] border-l border-[#2b3139] flex flex-col shrink-0 z-40">
            
            <!-- Top: Order Book -->
            <div class="h-[45%] flex flex-col border-b border-[#2b3139] bg-[#181a20]">
                <div class="h-8 flex items-center px-3 justify-between bg-[#181a20]">
                    <div class="flex gap-2">
                         <span class="block w-4 h-4 bg-slate-700 rounded-sm"></span> <!-- Icon placeholders -->
                         <span class="block w-4 h-4 bg-slate-700 rounded-sm"></span>
                    </div>
                </div>
                <div class="px-3 py-1 flex justify-between text-[10px] font-bold text-slate-500">
                    <span>Price(USDT)</span>
                    <span>Amount(BTC)</span>
                    <span>Total</span>
                </div>
                
                <div class="flex-grow relative overflow-hidden flex flex-col font-mono text-[10px]">
                    <!-- Sells -->
                    <div class="flex-1 overflow-hidden flex flex-col-reverse pb-1">
                        <template x-for="ask in asks" :key="ask.id">
                            <div class="flex justify-between px-3 py-[1px] relative hover:bg-[#2b3139] cursor-pointer">
                                <span class="text-[#f6465d] z-10" x-text="ask.price.toFixed(2)"></span>
                                <span class="text-slate-400 z-10" x-text="ask.amount.toFixed(3)"></span>
                                <span class="text-slate-500 z-10" x-text="(ask.price * ask.amount).toFixed(0)"></span>
                                <div class="absolute right-0 top-0 bottom-0 bg-[#f6465d]/10" :style="'width: '+ (ask.amount*40) +'%'"></div>
                            </div>
                        </template>
                    </div>
                    
                    <!-- Middle -->
                    <div class="h-10 flex items-center px-3 gap-2 border-y border-[#2b3139] bg-[#0b0e11]">
                        <span class="text-lg font-bold" :class="lastPrice >= prevPrice ? 'text-[#0ecb81]' : 'text-[#f6465d]'" x-text="lastPrice.toFixed(2)"></span>
                        <span class="text-[10px] font-bold text-slate-500">Mark</span>
                    </div>
                    
                    <!-- Buys -->
                    <div class="flex-1 overflow-hidden pt-1">
                        <template x-for="bid in bids" :key="bid.id">
                             <div class="flex justify-between px-3 py-[1px] relative hover:bg-[#2b3139] cursor-pointer">
                                <span class="text-[#0ecb81] z-10" x-text="bid.price.toFixed(2)"></span>
                                <span class="text-slate-400 z-10" x-text="bid.amount.toFixed(3)"></span>
                                <span class="text-slate-500 z-10" x-text="(bid.price * bid.amount).toFixed(0)"></span>
                                <div class="absolute right-0 top-0 bottom-0 bg-[#0ecb81]/10" :style="'width: '+ (bid.amount*40) +'%'"></div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Bottom: Order Form -->
            <div class="flex-grow flex flex-col bg-[#1e2329]">
                <div class="flex bg-[#0b0e11] text-[11px] font-bold">
                    <button class="flex-1 py-3 text-[#f0b90b] border-t-2 border-[#f0b90b] bg-[#1e2329]">Spot</button>
                    <button class="flex-1 py-3 text-slate-500 hover:text-white">Cross 3x</button>
                    <button class="flex-1 py-3 text-slate-500 hover:text-white">Isolated</button>
                </div>
                
                <div class="p-4 flex flex-col gap-4">
                    <div class="flex bg-[#2b3139] rounded p-0.5">
                        <button class="flex-1 py-1.5 rounded text-[10px] font-bold bg-[#474d57] text-white">Limit</button>
                        <button class="flex-1 py-1.5 rounded text-[10px] font-bold hover:text-white text-slate-400">Market</button>
                        <button class="flex-1 py-1.5 rounded text-[10px] font-bold hover:text-white text-slate-400">Stop</button>
                    </div>

                    <div>
                        <div class="flex justify-between text-[10px] font-bold text-slate-400 mb-1">
                            <span>Avbl</span>
                            <span class="text-white"><span x-text="balance.toFixed(2)"></span> USDT</span>
                        </div>
                        <div class="relative flex items-center bg-[#2b3139] rounded border border-[#2b3139] hover:border-[#f0b90b] transition-colors h-10">
                            <span class="pl-3 text-xs font-bold text-slate-500">Price</span>
                            <input type="text" disabled value="Market" class="flex-grow bg-transparent text-right pr-3 text-sm font-bold text-white outline-none">
                            <span class="pr-3 text-xs font-bold text-slate-500">USDT</span>
                        </div>
                    </div>

                    <div>
                         <div class="relative flex items-center bg-[#2b3139] rounded border border-[#2b3139] hover:border-[#f0b90b] transition-colors h-10">
                            <span class="pl-3 text-xs font-bold text-slate-500">Total</span>
                            <input type="number" x-model.number="betAmount" class="flex-grow bg-transparent text-right pr-3 text-sm font-bold text-white outline-none font-mono">
                            <span class="pr-3 text-xs font-bold text-slate-500">USDT</span>
                        </div>
                    </div>
                    
                    <!-- Slider -->
                    <div>
                        <input type="range" class="w-full h-1 bg-slate-600 rounded appearance-none cursor-pointer accent-[#f0b90b]">
                        <div class="flex justify-between mt-1 px-1">
                            <span class="w-1 h-1 bg-slate-500 rounded-full"></span>
                            <span class="w-1 h-1 bg-slate-500 rounded-full"></span>
                            <span class="w-1 h-1 bg-slate-500 rounded-full"></span>
                            <span class="w-1 h-1 bg-slate-500 rounded-full"></span>
                            <span class="w-1 h-1 bg-slate-500 rounded-full"></span>
                        </div>
                    </div>

                    <div class="mt-2 grid grid-cols-2 gap-3">
                        <button @click="placeOrder('buy')" :disabled="phase!=='open' || myPosition"
                             class="h-10 rounded bg-[#0ecb81] hover:bg-[#0da86b] text-white font-bold text-sm shadow transition-all disabled:opacity-50">
                             Buy Long
                        </button>
                         <button @click="placeOrder('sell')" :disabled="phase!=='open' || myPosition"
                             class="h-10 rounded bg-[#f6465d] hover:bg-[#d93a4e] text-white font-bold text-sm shadow transition-all disabled:opacity-50">
                             Sell Short
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Result Popup (Minimalist) -->
        <div x-show="showResult" style="display: none;" 
             class="absolute inset-0 z-[100] flex items-center justify-center bg-black/40 backdrop-blur-sm pointer-events-none">
            <div class="bg-[#1e2329] p-8 rounded-xl border border-[#474d57] text-center shadow-2xl transform scale-100 animate-bounce">
                <div class="text-4xl mb-2" x-text="lastWin ? '✅' : '❌'"></div>
                <div class="text-2xl font-black text-white" x-text="lastWin ? 'Win' : 'Loss'"></div>
                <div class="text-xl font-mono mt-2" 
                     :class="lastWin ? 'text-[#0ecb81]' : 'text-[#f6465d]'"
                     x-text="(lastWin ? '+' : '') + '$' + Math.abs(lastPnL).toFixed(2)"></div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            // Expose game state to window for header access
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
                maxCandles: 50,

                initTrader() {
                    window.userBalance = this.balance;
                    window.game = this; // Link for header
                    
                    this.canvas = document.getElementById('tradeCanvas');
                    this.fillHistory();
                    this.generateOrderBook();
                    
                    new ResizeObserver(() => { this.setupCanvas(); this.draw(); }).observe(document.getElementById('chartContainer'));

                    this.startInternalLoops();
                    requestAnimationFrame(() => this.renderLoop());
                },
                
                // ... [Standard Canvas & Game Logic - Same as before but adapted for layout] ...
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
                        let c = o + (Math.random() - 0.5) * 40;
                        let h = Math.max(o,c) + Math.random() * 8;
                        let l = Math.min(o,c) - Math.random() * 8;
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
                    let vol = this.phase === 'locked' ? 4 : 1;
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
                       let profit = this.myPosition.amount * 0.82;
                       this.lastPnL = profit;
                       this.balance += (this.myPosition.amount + profit);
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
                    this.asks = Array.from({length: 12}, (_, i) => ({ id: 'a'+i, price: this.lastPrice + (i*2) + Math.random(), amount: Math.random() }));
                    this.bids = Array.from({length: 12}, (_, i) => ({ id: 'b'+i, price: this.lastPrice - (i*2) - Math.random(), amount: Math.random() }));
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
                    ctx.beginPath();
                    for(let x=w%80; x<w; x+=80) { ctx.moveTo(x,0); ctx.lineTo(x,h); }
                    for(let y=h%80; y<h; y+=80) { ctx.moveTo(0,y); ctx.lineTo(w,y); }
                    ctx.stroke();

                    // Candles
                    let min = Math.min(...this.candles.map(c=>c.l)) - 20;
                    let max = Math.max(...this.candles.map(c=>c.h)) + 20;
                    let range = max - min;
                    if(range<1) range=1;

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
                        
                        ctx.beginPath();
                        ctx.moveTo(x + candleW/2, yH);
                        ctx.lineTo(x + candleW/2, yL);
                        ctx.stroke();
                        
                        let top = Math.min(yO, yC);
                        let height = Math.abs(yO - yC);
                        if(height < 1) height = 1;
                        ctx.fillRect(x, top, candleW, height);
                        
                        // Volume Bar (at bottom)
                        let volHeight = (Math.abs(c.c - c.o) / range) * 100 + 5; 
                        ctx.globalAlpha = 0.3;
                        ctx.fillRect(x, h - volHeight, candleW, volHeight);
                        ctx.globalAlpha = 1.0;
                    });
                    
                    // Price Line
                    let priceY = h - ((this.lastPrice - min) / range) * h;
                    ctx.strokeStyle = '#f0b90b';
                    ctx.setLineDash([2, 4]);
                    ctx.beginPath();
                    ctx.moveTo(0, priceY);
                    ctx.lineTo(w, priceY);
                    ctx.stroke();
                    ctx.setLineDash([]);
                    
                    ctx.fillStyle = '#f0b90b';
                    ctx.fillRect(w - 60, priceY - 10, 60, 20);
                    ctx.fillStyle = '#000';
                    ctx.font = 'bold 11px sans-serif';
                    ctx.fillText(this.lastPrice.toFixed(2), w - 55, priceY + 4);

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
                },
                
                updateCrosshair(e) {
                    const rect = this.canvas.getBoundingClientRect();
                    this.crosshair.x = e.clientX - rect.left;
                    this.crosshair.y = e.clientY - rect.top;
                    this.crosshair.visible = true;
                },
                hideCrosshair() { this.crosshair.visible = false; }
            }));
        });
    </script>
</body>
</html>