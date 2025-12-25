<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>Trader Panic - Pro</title>
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
        input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    </style>
</head>

<body class="flex flex-col h-screen bg-[#0b0e11] text-[#eaecef] select-none text-xs">

    <!-- HEADER -->
    <header class="h-10 bg-[#181a20] border-b border-[#2b3139] flex items-center px-4 shrink-0 justify-between z-50">
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2">
                <div class="w-5 h-5 rounded bg-[#f0b90b] text-[#0b0e11] font-black flex items-center justify-center text-[10px]">C</div>
                <span class="font-bold text-sm text-white">BTCUSDT</span>
                <span class="text-[10px] bg-[#2b3139] px-1.5 py-0.5 rounded text-emerald-400 font-bold border border-emerald-500/20">Perp</span>
            </div>
            
            <div class="hidden md:flex items-center gap-4 border-l border-[#2b3139] pl-4">
                 <div class="flex flex-col gap-0.5">
                    <span class="text-[9px] font-bold text-emerald-400" x-data x-text="window.game?.lastPrice?.toFixed(2) ?? '---'"></span>
                    <span class="text-[9px] text-slate-500">Mark</span>
                 </div>
                 <div class="flex flex-col gap-0.5">
                     <span class="text-[9px] text-slate-300">67,450.00</span>
                     <span class="text-[9px] text-slate-500">24h High</span>
                 </div>
                 <div class="flex flex-col gap-0.5">
                     <span class="text-[9px] text-slate-300">98.2M</span>
                     <span class="text-[9px] text-slate-500">24h Vol</span>
                 </div>
            </div>
        </div>

        <div class="flex items-center gap-4">
             <div class="flex items-center gap-2" x-data>
                 <span class="w-1.5 h-1.5 rounded-full" :class="window.game?.phase === 'open' ? 'bg-emerald-500 animate-pulse' : 'bg-amber-500'"></span>
                 <span class="font-mono font-bold text-white" x-text="window.game?.phase === 'open' ? 'TRADING' : 'LOCKED'"></span>
                 <span class="font-mono font-bold text-[#f0b90b] bg-[#2b3139] px-1.5 rounded" x-text="window.game?.formatTimer() ?? '00:00'"></span>
             </div>
             
             <div class="h-6 w-[1px] bg-[#2b3139]"></div>

             <div class="flex flex-col items-end leading-none">
                  <span class="text-[9px] text-slate-500 font-bold uppercase">Balance</span>
                  <span class="font-mono font-bold text-white" x-text="'$' + (window.userBalance?.toLocaleString('en-US') ?? '1,000')"></span>
             </div>
             
             <a href="{{ route('homepage') }}" class="text-slate-400 hover:text-white"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></a>
        </div>
    </header>

    <!-- MAIN GRID: 3 Columns -->
    <div x-data="proTrader()" x-init="initTrader()" x-cloak class="flex-grow flex overflow-hidden">
        
        <!-- COL 1: CHART (Flexible Width) -->
        <div class="flex-1 flex flex-col min-w-0 bg-[#0b0e11] relative border-r border-[#2b3139]">
            <!-- Chart Toolbar -->
            <div class="h-8 border-b border-[#2b3139] flex items-center px-4 gap-3 text-[10px] font-bold text-slate-500 bg-[#0b0e11]">
                <span class="text-white hover:bg-[#2b3139] px-2 py-0.5 rounded cursor-pointer">Time</span>
                <span class="text-[#f0b90b] hover:bg-[#2b3139] px-2 py-0.5 rounded cursor-pointer">1s</span>
                <span class="hover:bg-[#2b3139] px-2 py-0.5 rounded cursor-pointer">15m</span>
                <span class="hover:bg-[#2b3139] px-2 py-0.5 rounded cursor-pointer">1H</span>
                <span class="hover:bg-[#2b3139] px-2 py-0.5 rounded cursor-pointer">4H</span>
                <div class="flex-grow"></div>
                <span class="text-emerald-500">Original</span>
            </div>

            <!-- Canvas -->
            <div id="chartContainer" class="flex-grow relative w-full h-full bg-[#0b0e11] crs-crosshair"
                 @mousemove="updateCrosshair" 
                 @mouseleave="hideCrosshair">
                <canvas id="tradeCanvas" class="absolute inset-0 w-full h-full block"></canvas>
                <!-- Crosshair Label -->
                <div x-show="crosshair.visible" 
                     class="absolute bg-[#1e2329] text-white text-[9px] font-mono px-1 py-0.5 rounded pointer-events-none z-30 border border-[#474d57]"
                     :style="`left: ${crosshair.x + 8}px; top: ${crosshair.y - 10}px;`">
                    <span x-text="crosshair.price"></span>
                </div>
            </div>

            <!-- Positions Tabs (Collapsible-ish) -->
            <div class="h-[180px] border-t border-[#2b3139] bg-[#0b0e11] flex flex-col">
                <div class="h-8 flex items-center px-4 gap-4 border-b border-[#2b3139] bg-[#14161b]">
                    <span class="text-[10px] font-bold text-[#f0b90b] border-b-2 border-[#f0b90b] h-full flex items-center px-1">Positions <span x-text="myPosition ? '(1)' : '(0)'"></span></span>
                    <span class="text-[10px] font-bold text-slate-500 h-full flex items-center hover:text-white px-1">Open Orders</span>
                    <span class="text-[10px] font-bold text-slate-500 h-full flex items-center hover:text-white px-1">History</span>
                </div>
                
                <div class="flex-grow overflow-auto custom-scrollbar relative">
                    <div x-show="!myPosition" class="absolute inset-0 flex flex-col items-center justify-center opacity-30">
                        <span class="text-[9px] font-bold">No Open Positions</span>
                    </div>
                    
                    <table class="w-full text-left font-mono text-[10px]" x-show="myPosition">
                         <thead class="text-slate-500 bg-[#0b0e11] sticky top-0">
                            <tr>
                                <th class="px-4 py-1.5 font-normal">Symbol</th>
                                <th class="px-4 py-1.5 font-normal">Side</th>
                                <th class="px-4 py-1.5 font-normal text-right">Size</th>
                                <th class="px-4 py-1.5 font-normal text-right">Entry</th>
                                <th class="px-4 py-1.5 font-normal text-right">PnL</th>
                            </tr>
                        </thead>
                        <tbody class="text-white">
                            <tr class="bg-[#1e2329]/30 border-b border-[#2b3139]">
                                <td class="px-4 py-1.5 font-bold text-[#f0b90b]">BTCUSDT</td>
                                <td class="px-4 py-1.5 font-bold" :class="myPosition?.type==='buy' ? 'text-emerald-400' : 'text-rose-400'" x-text="myPosition?.type==='buy'?'Long':'Short'"></td>
                                <td class="px-4 py-1.5 text-right" x-text="myPosition?.amount"></td>
                                <td class="px-4 py-1.5 text-right" x-text="myPosition?.entry.toFixed(2)"></td>
                                <td class="px-4 py-1.5 text-right font-bold w-24" :class="(lastPrice - myPosition?.entry)*(myPosition?.type==='buy'?1:-1) > 0 ? 'text-emerald-400' : 'text-rose-400'">
                                    <span x-text="((lastPrice - myPosition?.entry)*(myPosition?.type==='buy'?1:-1)).toFixed(2)"></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- COL 2: ORDER BOOK (Fixed Width) -->
        <div class="w-[260px] flex flex-col border-r border-[#2b3139] bg-[#14161b] shrink-0">
            <div class="h-8 flex items-center px-3 border-b border-[#2b3139]">
                <span class="text-[10px] font-bold text-white">Order Book</span>
            </div>
            <div class="px-3 py-1 flex justify-between text-[9px] font-bold text-slate-500">
                <span>Price</span>
                <span>Amount</span>
                <span>Total</span>
            </div>
            
            <div class="flex-grow overflow-hidden flex flex-col font-mono text-[9px]">
                <!-- Sells -->
                <div class="flex-1 overflow-hidden flex flex-col-reverse justify-start">
                    <template x-for="ask in asks" :key="ask.id">
                        <div class="flex justify-between px-3 py-[0.5px] relative hover:bg-[#2b3139] cursor-pointer">
                            <span class="text-[#f6465d] z-10" x-text="ask.price.toFixed(2)"></span>
                            <span class="text-slate-400 z-10" x-text="ask.amount.toFixed(3)"></span>
                            <span class="text-slate-600 z-10" x-text="(ask.price * ask.amount/1000).toFixed(0)+'k'"></span>
                            <div class="absolute right-0 top-0 bottom-0 bg-[#f6465d]/10" :style="'width: '+ (ask.amount*30) +'%'"></div>
                        </div>
                    </template>
                </div>
                <!-- Middle -->
                <div class="h-8 flex items-center px-3 justify-center border-y border-[#2b3139] bg-[#0b0e11]">
                    <span class="text-lg font-bold" :class="lastPrice >= prevPrice ? 'text-[#0ecb81]' : 'text-[#f6465d]'" x-text="lastPrice.toFixed(2)"></span>
                </div>
                <!-- Buys -->
                <div class="flex-1 overflow-hidden pt-0.5">
                    <template x-for="bid in bids" :key="bid.id">
                         <div class="flex justify-between px-3 py-[0.5px] relative hover:bg-[#2b3139] cursor-pointer">
                            <span class="text-[#0ecb81] z-10" x-text="bid.price.toFixed(2)"></span>
                            <span class="text-slate-400 z-10" x-text="bid.amount.toFixed(3)"></span>
                            <span class="text-slate-600 z-10" x-text="(bid.price * bid.amount/1000).toFixed(0)+'k'"></span>
                            <div class="absolute right-0 top-0 bottom-0 bg-[#0ecb81]/10" :style="'width: '+ (bid.amount*30) +'%'"></div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- COL 3: TRADE FORM (Fixed Width) -->
        <div class="w-[280px] flex flex-col bg-[#1e2329] shrink-0">
             <div class="flex bg-[#0b0e11] text-[10px] font-bold border-b border-[#2b3139]">
                <button class="flex-1 py-2 text-[#f0b90b] border-t-2 border-[#f0b90b]">Spot</button>
                <button class="flex-1 py-2 text-slate-500 hover:text-white">Cross 3x</button>
                <button class="flex-1 py-2 text-slate-500 hover:text-white">Iso 10x</button>
            </div>
            
            <div class="p-4 flex flex-col gap-4">
                 <div class="flex bg-[#2b3139] rounded-sm p-[2px]">
                    <button class="flex-1 py-1 rounded-sm text-[10px] font-bold bg-[#474d57] text-white">Limit</button>
                    <button class="flex-1 py-1 rounded-sm text-[10px] font-bold text-slate-400 hover:text-white">Market</button>
                    <button class="flex-1 py-1 rounded-sm text-[10px] font-bold text-slate-400 hover:text-white">Stop</button>
                </div>

                <div class="space-y-3">
                     <div class="flex justify-between text-[10px] font-bold text-slate-400">
                         <span>Avbl</span>
                         <span class="text-white"><span x-text="Math.floor(balance)"></span> USDT</span>
                    </div>

                     <!-- Price -->
                    <div class="flex items-center bg-[#2b3139] rounded border border-[#2b3139] h-8">
                        <span class="pl-3 text-[10px] font-bold text-slate-400 w-12">Price</span>
                        <input type="text" disabled value="Market" class="flex-grow bg-transparent text-right pr-3 text-xs font-bold text-white outline-none">
                        <span class="pr-2 text-[10px] font-bold text-slate-500">USDT</span>
                    </div>
                    
                    <!-- Amount -->
                    <div class="flex items-center bg-[#2b3139] rounded border border-[#2b3139] h-8 hover:border-[#f0b90b]">
                        <span class="pl-3 text-[10px] font-bold text-slate-400 w-12">Total</span>
                        <input type="number" x-model.number="betAmount" class="flex-grow bg-transparent text-right pr-3 text-xs font-bold text-white outline-none font-mono">
                        <span class="pr-2 text-[10px] font-bold text-slate-500">USDT</span>
                    </div>
                    
                    <!-- Slider -->
                    <div>
                         <input type="range" class="w-full h-1 bg-slate-600 rounded appearance-none cursor-pointer accent-[#f0b90b]">
                         <div class="flex justify-between mt-1 px-1">
                             <div class="w-1 h-1 rounded-full bg-slate-500"></div>
                             <div class="w-1 h-1 rounded-full bg-slate-500"></div>
                             <div class="w-1 h-1 rounded-full bg-slate-500"></div>
                             <div class="w-1 h-1 rounded-full bg-slate-500"></div>
                             <div class="w-1 h-1 rounded-full bg-slate-500"></div>
                         </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="grid grid-cols-2 gap-2 mt-2">
                    <button @click="placeOrder('buy')" :disabled="window.game?.phase!=='open' || myPosition"
                        class="py-2 rounded bg-[#0ecb81] hover:bg-[#0da86b] disabled:opacity-50 text-white text-[11px] font-bold">
                        Buy Long
                    </button>
                     <button @click="placeOrder('sell')" :disabled="window.game?.phase!=='open' || myPosition"
                        class="py-2 rounded bg-[#f6465d] hover:bg-[#d93a4e] disabled:opacity-50 text-white text-[11px] font-bold">
                        Sell Short
                    </button>
                </div>
            </div>
        </div>
        
        <!-- RESULT POPUP -->
         <div x-show="showResult" style="display: none;" 
             class="absolute inset-0 z-[100] flex items-center justify-center bg-black/40 backdrop-blur-sm pointer-events-none">
            <div class="bg-[#1e2329] p-6 rounded-xl border border-[#474d57] text-center shadow-2xl animate-bounce">
                <div class="text-4xl mb-2" x-text="lastWin ? '✅' : '❌'"></div>
                <div class="text-xl font-black text-white" x-text="lastWin ? 'WIN' : 'LOSS'"></div>
                <div class="text-lg font-mono" :class="lastWin ? 'text-[#0ecb81]' : 'text-[#f6465d]'"
                     x-text="(lastWin ? '+' : '') + '$' + Math.abs(lastPnL).toFixed(2)"></div>
            </div>
        </div>

    </div>

    <!-- SCRIPT (Retaining Logic) -->
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
                candles: [], asks: [], bids: [],
                myPosition: null, showResult: false, lastWin: false, lastPnL: 0,
                crosshair: { x:0, y:0, visible:false, price:0 },
                canvas: null, ctx: null, maxCandles: 50, // Balanced Zoom

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
                        let o = p; let c = o + (Math.random() - 0.5) * 40;
                        let h = Math.max(o,c) + Math.random() * 8; let l = Math.min(o,c) - Math.random() * 8;
                        this.candles.push({o, h, l, c}); p = c;
                    }
                    this.lastPrice = p;
                },
                startInternalLoops() {
                    setInterval(() => {
                        this.timer--;
                        if(this.phase === 'open' && this.timer <= 0) { this.phase = 'locked'; this.timer = 20; }
                        else if(this.phase === 'locked' && this.timer <= 0) { this.settle(); this.phase = 'open'; this.timer = 10; }
                    }, 1000);
                    setInterval(() => {
                        let o = this.lastPrice; this.candles.push({o, h:o, l:o, c:o});
                        if(this.candles.length > this.maxCandles) this.candles.shift();
                    }, 2000);
                    setInterval(() => this.generateOrderBook(), 1000);
                },
                renderLoop() {
                    this.prevPrice = this.lastPrice;
                    this.lastPrice += (Math.random() - 0.5) * (this.phase==='locked'?5:1);
                    let lc = this.candles[this.candles.length-1]; lc.c = this.lastPrice;
                    if(this.lastPrice>lc.h) lc.h=this.lastPrice; if(this.lastPrice<lc.l) lc.l=this.lastPrice;
                    this.draw(); requestAnimationFrame(() => this.renderLoop());
                },
                placeOrder(type) {
                    if(this.balance < this.betAmount) return;
                    this.balance -= this.betAmount; window.userBalance = this.balance;
                    this.myPosition = { type: type, entry: this.lastPrice, amount: this.betAmount };
                },
                settle() {
                   if(!this.myPosition) return;
                   let diff = this.lastPrice - this.myPosition.entry;
                   let win = (this.myPosition.type === 'buy' && diff > 0) || (this.myPosition.type === 'sell' && diff < 0);
                   this.lastWin = win;
                   if(win) { let p = this.myPosition.amount*0.82; this.lastPnL=p; this.balance+=this.myPosition.amount+p; }
                   else { this.lastPnL=-this.myPosition.amount; }
                   this.myPosition = null; this.showResult = true; setTimeout(()=>this.showResult=false, 3000); window.userBalance = this.balance;
                },
                formatTimer() { return `00:${this.timer.toString().padStart(2, '0')}`; },
                generateOrderBook() {
                    this.asks = Array.from({length: 15}, (_, i) => ({ id: 'a'+i, price: this.lastPrice + (i*1.5) + Math.random(), amount: Math.random() }));
                    this.bids = Array.from({length: 15}, (_, i) => ({ id: 'b'+i, price: this.lastPrice - (i*1.5) - Math.random(), amount: Math.random() }));
                },
                draw() {
                    if(!this.ctx) return;
                    const w = this.canvas.width / (window.devicePixelRatio||1);
                    const h = this.canvas.height / (window.devicePixelRatio||1);
                    const ctx = this.ctx;
                    ctx.clearRect(0,0,w,h);
                    ctx.fillStyle='#0b0e11'; ctx.fillRect(0,0,w,h);
                    
                    ctx.strokeStyle='#2b3139'; ctx.beginPath();
                    for(let x=w%80; x<w; x+=80) { ctx.moveTo(x,0); ctx.lineTo(x,h); }
                    for(let y=h%80; y<h; y+=80) { ctx.moveTo(0,y); ctx.lineTo(w,y); }
                    ctx.stroke();

                    let min = Math.min(...this.candles.map(c=>c.l))-20;
                    let max = Math.max(...this.candles.map(c=>c.h))+20;
                    let range=max-min; if(range<1) range=1;

                    let unitW = w/this.maxCandles; let candleW = unitW*0.7; let spacing = unitW*0.3;

                    this.candles.forEach((c, i) => {
                        let isGreen = c.c>=c.o; let color = isGreen?'#0ecb81':'#f6465d';
                        let x = i*unitW + spacing/2;
                        let yH = h - ((c.h-min)/range)*h; let yL = h - ((c.l-min)/range)*h;
                        let yO = h - ((c.o-min)/range)*h; let yC = h - ((c.c-min)/range)*h;
                        ctx.fillStyle=color; ctx.strokeStyle=color;
                        ctx.beginPath(); ctx.moveTo(x+candleW/2, yH); ctx.lineTo(x+candleW/2, yL); ctx.stroke();
                        let top = Math.min(yO, yC); let height = Math.abs(yO-yC); if(height<1) height=1;
                        ctx.fillRect(x, top, candleW, height);
                        
                        let volHeight = (Math.abs(c.c - c.o) / range) * h * 0.5 + 5;
                        ctx.globalAlpha = 0.2; ctx.fillRect(x, h-volHeight, candleW, volHeight); ctx.globalAlpha = 1.0;
                    });
                    
                    let priceY = h - ((this.lastPrice-min)/range)*h;
                    ctx.strokeStyle='#f0b90b'; ctx.setLineDash([2,2]); ctx.beginPath(); ctx.moveTo(0,priceY); ctx.lineTo(w,priceY); ctx.stroke(); ctx.setLineDash([]);
                    ctx.fillStyle='#f0b90b'; ctx.fillRect(w-60, priceY-9, 60, 18);
                    ctx.fillStyle='#000'; ctx.font='bold 10px sans-serif'; ctx.fillText(this.lastPrice.toFixed(2), w-55, priceY+3);
                    
                    if(this.crosshair.visible) {
                        ctx.strokeStyle='#fff'; ctx.setLineDash([4,4]);
                        ctx.beginPath(); ctx.moveTo(0, this.crosshair.y); ctx.lineTo(w, this.crosshair.y);
                        ctx.moveTo(this.crosshair.x, 0); ctx.lineTo(this.crosshair.x, h); ctx.stroke();
                        let p = min + ((h-this.crosshair.y)/h)*range;
                        this.crosshair.price = p.toFixed(2);
                    }
                },
                updateCrosshair(e) {
                    const r = this.canvas.getBoundingClientRect();
                    this.crosshair.x = e.clientX - r.left; this.crosshair.y = e.clientY - r.top; this.crosshair.visible=true;
                },
                hideCrosshair() { this.crosshair.visible=false; }
            }));
        });
    </script>
</body>
</html>