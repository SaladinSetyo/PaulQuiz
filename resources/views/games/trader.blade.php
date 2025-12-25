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
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: #0b0e11; }
        ::-webkit-scrollbar-thumb { background: #2b3139; border-radius: 2px; }
        
        .crs-crosshair { cursor: crosshair; }
        
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    </style>
</head>

<body class="flex flex-col h-screen bg-[#0b0e11] text-[#eaecef]">

    <!-- NAVBAR -->
    <nav class="h-12 bg-[#181a20] border-b border-[#2b3139] flex items-center px-4 justify-between shrink-0 z-50">
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded bg-emerald-500 text-[#0b0e11] font-black flex items-center justify-center text-lg shadow-[0_0_10px_rgba(16,185,129,0.4)]">C</div>
                <div class="flex flex-col leading-none">
                    <span class="font-black text-sm tracking-tight text-white hidden md:block">Crypto Trader Panic</span>
                </div>
            </div>
            
            <div class="hidden md:flex items-center gap-3 border-l border-[#2b3139] pl-4 ml-2">
                <span class="font-bold text-xs text-white">BTC/USDT</span>
                <span class="text-[10px] font-bold text-emerald-400 bg-emerald-400/10 px-1.5 py-0.5 rounded border border-emerald-400/20">+2.45%</span>
            </div>
        </div>

        <div class="flex items-center gap-3">
             <div class="flex flex-col items-end leading-none mr-2">
                <span class="text-[9px] text-slate-400 uppercase font-bold text-right">Balance</span>
                <span class="font-mono font-bold text-white text-sm" x-data x-text="'$' + (window.userBalance?.toLocaleString('en-US', {minimumFractionDigits: 2}) ?? '1,000.00')"></span>
            </div>
             <a href="{{ route('homepage') }}" class="text-slate-400 hover:text-white px-3 py-1.5 hover:bg-[#2b3139] rounded text-[10px] font-bold transition-colors uppercase border border-slate-700">Exit</a>
        </div>
    </nav>

    <!-- CONTENT -->
    <div x-data="proTrader()" x-init="initTrader()" x-cloak class="flex-grow flex overflow-hidden relative">

        <!-- LEFT: CHART AREA (Expanded) -->
        <div class="flex-grow flex flex-col relative bg-[#0b0e11] min-w-0">
            <!-- Toolbar -->
            <div class="h-8 border-b border-[#2b3139] flex items-center px-4 gap-4 text-[10px] font-bold text-slate-500 bg-[#0b0e11] shrink-0">
                <span class="text-white cursor-pointer">Time</span>
                <span class="text-[#f0b90b] bg-[#f0b90b]/10 px-2 py-0.5 rounded cursor-pointer">1s</span>
                <span>15m</span>
                <span>4H</span>
                <div class="w-[1px] h-3 bg-[#2b3139]"></div>
                <span class="cursor-pointer hover:text-white">Indicators</span>
                <div class="flex-grow"></div>
                <span class="text-emerald-500 animate-pulse">● Live Data</span>
            </div>

            <!-- Canvas Container -->
            <div id="chartContainer" class="flex-grow relative w-full h-full bg-[#0b0e11] crs-crosshair" 
                 @mousemove="updateCrosshair" 
                 @mouseleave="hideCrosshair">
                
                <canvas id="tradeCanvas" class="absolute inset-0 w-full h-full block"></canvas>
                
                <!-- HUD: Phase Status -->
                <div class="absolute top-4 left-1/2 transform -translate-x-1/2 flex flex-col items-center pointer-events-none z-20 w-auto">
                    <div class="backdrop-blur-xl border px-6 py-2 rounded-lg shadow-2xl flex items-center gap-4 transition-all duration-300"
                         :class="phase === 'open' ? 'bg-[#1e2329]/90 border-emerald-500/40' : 'bg-[#1e2329]/90 border-amber-500/40'">
                        
                        <div class="flex flex-col items-end">
                            <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">Status</span>
                             <span class="font-black text-xs tracking-tight whitespace-nowrap" 
                                      :class="phase === 'open' ? 'text-emerald-400' : 'text-amber-400'"
                                      x-text="phase === 'open' ? 'TRADING OPEN' : 'LOCKED'"></span>
                        </div>
                        
                        <div class="w-[1px] h-6 bg-[#474d57]/50"></div>
                        
                        <div class="flex flex-col items-start min-w-[40px]">
                            <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">Time</span>
                            <span class="font-mono font-bold text-xl text-white leading-none" x-text="formatTimer()"></span>
                        </div>
                    </div>
                </div>

                <!-- Crosshair Label -->
                <div x-show="crosshair.visible" 
                     class="absolute bg-[#1e2329] text-white text-[10px] font-mono px-2 py-1 rounded pointer-events-none border border-[#474d57] z-30 shadow-lg"
                     :style="`left: ${crosshair.x + 15}px; top: ${crosshair.y - 12}px;`">
                    <span x-text="crosshair.price"></span>
                </div>
            </div>
        </div>

        <!-- RIGHT: ORDER PANEL -->
        <div class="w-[300px] 2xl:w-[340px] bg-[#181a20] border-l border-[#2b3139] flex flex-col shrink-0 z-30 relative shadow-[-5px_0_20px_rgba(0,0,0,0.5)]">
            
            <!-- Visual Order Book -->
            <div class="h-[35vh] flex flex-col border-b border-[#2b3139]">
                <div class="h-7 flex items-center px-4 text-[9px] font-bold text-slate-500 justify-between bg-[#181a20]">
                    <span>Price</span>
                    <span>Amount</span>
                </div>
                
                <div class="flex-grow relative bg-[#0b0e11] overflow-hidden flex flex-col text-[10px] font-mono">
                    <div class="flex-1 overflow-hidden flex flex-col-reverse justify-start pb-1">
                        <template x-for="ask in asks" :key="ask.id">
                            <div class="flex justify-between px-4 py-[1px] relative items-center hover:bg-[#1e2329] transition-colors">
                                <span class="text-[#f6465d] z-10 font-medium" x-text="ask.price.toFixed(2)"></span>
                                <span class="text-slate-500 z-10" x-text="ask.amount.toFixed(4)"></span>
                                <div class="absolute right-0 top-0 bottom-0 bg-[#f6465d]/10" :style="'width: ' + (ask.amount * 50) + '%'"></div>
                            </div>
                        </template>
                    </div>
                    
                    <div class="h-10 flex items-center justify-between px-4 border-y border-[#2b3139] bg-[#14161b] shrink-0 z-20">
                        <span class="text-xl font-bold font-mono tracking-tight" 
                              :class="lastPrice >= prevPrice ? 'text-[#0ecb81]' : 'text-[#f6465d]'"
                              x-text="lastPrice.toFixed(2)"></span>
                        <span class="text-[10px] font-bold" :class="lastPrice >= prevPrice ? 'text-emerald-500' : 'text-rose-500'">●</span>
                    </div>

                    <div class="flex-1 overflow-hidden pt-1">
                        <template x-for="bid in bids" :key="bid.id">
                             <div class="flex justify-between px-4 py-[1px] relative items-center hover:bg-[#1e2329] transition-colors">
                                <span class="text-[#0ecb81] z-10 font-medium" x-text="bid.price.toFixed(2)"></span>
                                <span class="text-slate-500 z-10" x-text="bid.amount.toFixed(4)"></span>
                                <div class="absolute right-0 top-0 bottom-0 bg-[#0ecb81]/10" :style="'width: ' + (bid.amount * 50) + '%'"></div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Controls -->
            <div class="flex-grow p-4 bg-[#1e2329] flex flex-col gap-3 overflow-y-auto">
                <div>
                    <div class="flex justify-between text-[10px] font-bold text-slate-400 mb-1">
                        <span>Amount (USDT)</span>
                        <span class="text-slate-200">Avbl: <span x-text="Math.floor(balance)"></span></span>
                    </div>
                    <div class="relative">
                        <input type="number" x-model.number="betAmount" class="w-full bg-[#0b0e11] border border-[#2b3139] rounded h-10 pl-3 pr-3 text-white font-mono font-bold text-base focus:border-[#f0b90b] transition-colors outline-none text-right">
                        <div class="absolute left-3 top-0 bottom-0 flex items-center text-slate-500 text-xs font-bold">$</div>
                    </div>
                    
                    <div class="grid grid-cols-4 gap-2 mt-2">
                        <button @click="betAmount = 10" class="bg-[#2b3139] border border-[#2b3139] hover:border-[#474d57] text-slate-300 text-[10px] font-bold py-1 rounded transition-colors">10</button>
                        <button @click="betAmount = 50" class="bg-[#2b3139] border border-[#2b3139] hover:border-[#474d57] text-slate-300 text-[10px] font-bold py-1 rounded transition-colors">50</button>
                        <button @click="betAmount = 100" class="bg-[#2b3139] border border-[#2b3139] hover:border-[#474d57] text-slate-300 text-[10px] font-bold py-1 rounded transition-colors">100</button>
                        <button @click="betAmount = 250" class="bg-[#2b3139] border border-[#2b3139] hover:border-[#474d57] text-white text-[10px] font-bold py-1 rounded transition-colors">250</button>
                    </div>
                </div>

                <div class="mt-auto">
                    <div class="flex justify-between items-center mb-2">
                         <div class="text-[10px] font-bold text-slate-400">Profit</div>
                         <div class="text-lg font-black text-emerald-400">82%</div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <button @click="placeOrder('buy')" 
                                :disabled="phase !== 'open' || myPosition"
                                class="h-12 bg-[#0ecb81] hover:bg-[#0da86b] disabled:opacity-40 disabled:cursor-not-allowed rounded text-white font-black text-sm shadow-[0_3px_0_#065f3d] active:shadow-none active:translate-y-[3px] flex flex-col items-center justify-center leading-none transition-all">
                            <span>LONG</span>
                        </button>
                        
                        <button @click="placeOrder('sell')" 
                                :disabled="phase !== 'open' || myPosition"
                                class="h-12 bg-[#f6465d] hover:bg-[#d93a4e] disabled:opacity-40 disabled:cursor-not-allowed rounded text-white font-black text-sm shadow-[0_3px_0_#8e1626] active:shadow-none active:translate-y-[3px] flex flex-col items-center justify-center leading-none transition-all">
                            <span>SHORT</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- RESULT POPUP -->
        <div x-show="showResult" 
             style="display: none;"
             class="absolute inset-0 z-[60] flex items-center justify-center bg-black/50 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100">
            <div class="bg-[#1e2329] p-6 rounded-2xl shadow-2xl text-center transform border-2 min-w-[280px]"
                 :class="lastWin ? 'border-[#0ecb81]' : 'border-[#f6465d]'">
                
                <div class="text-5xl mb-2 animate-bounce" x-text="lastWin ? '🤑' : '📉'"></div>
                <h2 class="text-3xl font-black mb-1 text-white" x-text="lastWin ? 'PROFIT' : 'LOSS'"></h2>
                <div class="font-mono font-bold text-2xl" 
                     :class="lastWin ? 'text-[#0ecb81]' : 'text-[#f6465d]'"
                     x-text="(lastWin ? '+' : '') + '$' + Math.abs(lastPnL + (lastWin ? myPosition?.amount : 0)).toFixed(2)"></div>
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
                crosshair: { x:0, y:0, visible:false, price:0 },
                
                canvas: null,
                ctx: null,
                maxCandles: 45, // Reduced from 70 -> Bigger Candles

                initTrader() {
                    window.userBalance = this.balance;
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
                        let c = o + (Math.random() - 0.5) * 30; // Random walk
                        let h = Math.max(o,c) + Math.random() * 5;
                        let l = Math.min(o,c) - Math.random() * 5;
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
                    let vol = this.phase === 'locked' ? 2 : 1;
                    this.prevPrice = this.lastPrice;
                    this.lastPrice += (Math.random() - 0.5) * vol;
                    
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
                    
                    // Gradient BG
                    let gradient = ctx.createLinearGradient(0, 0, 0, h);
                    gradient.addColorStop(0, '#0b0e11');
                    gradient.addColorStop(1, '#13161c');
                    ctx.fillStyle = gradient;
                    ctx.fillRect(0,0,w,h);
                    
                    // Gird
                    ctx.strokeStyle = '#2b3139';
                    ctx.beginPath();
                    for(let x=w%80; x<w; x+=80) { ctx.moveTo(x,0); ctx.lineTo(x,h); }
                    for(let y=h%80; y<h; y+=80) { ctx.moveTo(0,y); ctx.lineTo(w,y); }
                    ctx.stroke();

                    // Range Calc
                    let min = Math.min(...this.candles.map(c=>c.l)) - 10;
                    let max = Math.max(...this.candles.map(c=>c.h)) + 10;
                    let range = max - min;
                    if(range<1) range=1;

                    // Draw Large Candles
                    let unitW = w / this.maxCandles;
                    let candleW = unitW * 0.75; // 75% width
                    let spacing = unitW * 0.25;

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
                        ctx.lineWidth = 1.5;
                        
                        ctx.beginPath();
                        ctx.moveTo(x + candleW/2, yH);
                        ctx.lineTo(x + candleW/2, yL);
                        ctx.stroke();
                        
                        let top = Math.min(yO, yC);
                        let height = Math.abs(yO - yC);
                        if(height < 2) height = 2; // Min height for visibility
                        ctx.fillRect(x, top, candleW, height);
                    });
                    
                    // Price Line
                    let priceY = h - ((this.lastPrice - min) / range) * h;
                    ctx.beginPath();
                    ctx.strokeStyle = 'rgba(255, 255, 255, 0.8)';
                    ctx.lineWidth = 1;
                    ctx.setLineDash([2, 2]);
                    ctx.moveTo(0, priceY);
                    ctx.lineTo(w, priceY);
                    ctx.stroke();
                    ctx.setLineDash([]);
                    
                    // Pulser
                    ctx.beginPath();
                    ctx.arc(w - 10, priceY, 3, 0, Math.PI*2);
                    ctx.fillStyle = '#fff';
                    ctx.fill();
                    
                    // Tag
                    ctx.fillStyle = this.lastPrice >= this.prevPrice ? '#0ecb81' : '#f6465d';
                    ctx.fillRect(w - 70, priceY - 11, 70, 22);
                    ctx.fillStyle = '#fff';
                    ctx.font = 'bold 12px JetBrains Mono';
                    ctx.fillText(this.lastPrice.toFixed(2), w - 64, priceY + 5);

                    // Crosshair
                    if(this.crosshair.visible) {
                        let cy = this.crosshair.y;
                        let cx = this.crosshair.x;
                        
                        ctx.strokeStyle = 'rgba(255, 255, 255, 0.3)';
                        ctx.setLineDash([4, 4]);
                        ctx.beginPath();
                        ctx.moveTo(0, cy);
                        ctx.lineTo(w, cy);
                        ctx.moveTo(cx, 0);
                        ctx.lineTo(cx, h);
                        ctx.stroke();
                        ctx.setLineDash([]);
                        
                        let priceAtMouse = min + ((h - cy)/h) * range;
                        this.crosshair.price = priceAtMouse.toFixed(2);
                    }
                    
                    // Active Trade Line
                    if(this.myPosition) {
                         let entryY = h - ((this.myPosition.entry - min) / range) * h;
                         ctx.strokeStyle = '#3b82f6';
                         ctx.lineWidth = 2;
                         ctx.beginPath();
                         ctx.moveTo(0, entryY);
                         ctx.lineTo(w, entryY);
                         ctx.stroke();
                         
                         ctx.fillStyle = '#3b82f6';
                         ctx.fillText("ENTRY", 10, entryY - 5);
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
                   this.showResult = true;
                   this.myPosition = null;
                   setTimeout(() => this.showResult = false, 3000);
                },

                formatTimer() {
                    return `00:${this.timer.toString().padStart(2, '0')}`;
                },
                
                generateOrderBook() {
                    this.asks = Array.from({length: 10}, (_, i) => ({ id: 'a'+i, price: this.lastPrice + (i*1.5) + Math.random(), amount: Math.random() * 2 }));
                    this.bids = Array.from({length: 10}, (_, i) => ({ id: 'b'+i, price: this.lastPrice - (i*1.5) - Math.random(), amount: Math.random() * 2 }));
                }
            }));
        });
    </script>
</body>
</html>