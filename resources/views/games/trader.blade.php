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
        [x-cloak] {
            display: none !important;
        }

        body {
            background-color: #0b0e11;
            color: #eaecef;
            font-family: 'Inter', sans-serif;
        }

        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }

        ::-webkit-scrollbar-track {
            background: #0b0e11;
        }

        ::-webkit-scrollbar-thumb {
            background: #2b3139;
            border-radius: 2px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #474d57;
        }

        .order-book-row:hover {
            background-color: #1e2329;
        }

        /* Animations */
        @keyframes pulse-green {
            0% {
                box-shadow: 0 0 0 0 rgba(14, 203, 129, 0.4);
            }

            70% {
                box-shadow: 0 0 0 6px rgba(14, 203, 129, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(14, 203, 129, 0);
            }
        }

        @keyframes pulse-red {
            0% {
                box-shadow: 0 0 0 0 rgba(246, 70, 93, 0.4);
            }

            70% {
                box-shadow: 0 0 0 6px rgba(246, 70, 93, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(246, 70, 93, 0);
            }
        }

        .animate-pulse-green {
            animation: pulse-green 2s infinite;
        }

        .animate-pulse-red {
            animation: pulse-red 2s infinite;
        }
    </style>
</head>

<body class="flex flex-col h-screen overflow-hidden bg-[#0b0e11] text-[#eaecef]">

    <!-- NAVBAR -->
    <nav class="h-14 bg-[#181a20] border-b border-[#2b3139] flex items-center px-4 justify-between shrink-0 z-50">
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-2">
                <!-- Game Logo -->
                <div
                    class="w-8 h-8 rounded bg-emerald-500 text-[#0b0e11] font-black flex items-center justify-center text-lg shadow-lg shadow-emerald-500/20">
                    C</div>
                <span class="font-black text-lg tracking-tight text-white">Crypto Trader Panic</span>
            </div>

            <div class="hidden md:flex flex-col border-l border-[#2b3139] pl-6">
                <div class="flex items-center gap-2">
                    <span class="font-bold text-sm text-white">BTC/USDT</span>
                    <span
                        class="text-[10px] font-bold text-emerald-400 bg-emerald-400/10 px-1.5 py-0.5 rounded">+2.45%</span>
                </div>
                <span class="text-[10px] text-slate-500 font-bold uppercase">Perpetual</span>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="bg-[#2b3139] px-4 py-1.5 rounded flex items-center gap-3 border border-[#474d57]">
                <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Balance</span>
                <span class="font-mono font-bold text-white text-sm">$<span x-data
                        x-text="window.userBalance?.toLocaleString('en-US', {minimumFractionDigits: 2}) ?? '1,000.00'"></span></span>
            </div>
            <a href="{{ route('homepage') }}"
                class="text-slate-400 hover:text-white px-3 py-1.5 hover:bg-[#2b3139] rounded text-xs font-bold transition-colors uppercase tracking-wider">Exit
                Game</a>
        </div>
    </nav>

    <!-- CONTENT -->
    <div x-data="proTrader()" x-init="initTrader()" x-cloak class="flex-grow flex overflow-hidden relative">

        <!-- LEFT: CHART AREA -->
        <div class="flex-grow flex flex-col relative bg-[#0b0e11]">
            <!-- Chart Toolbar -->
            <div
                class="h-10 border-b border-[#2b3139] flex items-center px-4 gap-4 text-[11px] font-bold text-slate-500 bg-[#0b0e11]">
                <span class="text-slate-300">Time</span>
                <span class="cursor-pointer text-[#f0b90b]">1s</span>
                <span class="cursor-pointer hover:text-white transition-colors">15m</span>
                <span class="cursor-pointer hover:text-white transition-colors">1H</span>
                <span class="cursor-pointer hover:text-white transition-colors">4H</span>
                <div class="w-[1px] h-3 bg-[#2b3139]"></div>
                <span class="cursor-pointer hover:text-white transition-colors">Indicators</span>
                <span class="cursor-pointer hover:text-white transition-colors">Display</span>
            </div>

            <!-- Canvas Container -->
            <div class="flex-grow relative w-full h-full cursor-crosshair bg-[#0b0e11]">
                <canvas id="tradeCanvas" class="absolute inset-0 w-full h-full"></canvas>

                <!-- Helper Grid Background (CSS Grid) -->
                <div class="absolute inset-0 pointer-events-none opacity-[0.02]"
                    style="background-image: linear-gradient(#fff 1px, transparent 1px), linear-gradient(90deg, #fff 1px, transparent 1px); background-size: 100px 100px;">
                </div>

                <!-- Game Phase HUD overlay on Chart (Top Center) -->
                <div
                    class="absolute top-6 left-1/2 transform -translate-x-1/2 flex flex-col items-center pointer-events-none z-10">

                    <!-- Phase Badge -->
                    <div class="backdrop-blur-md border px-8 py-3 rounded-full shadow-2xl flex items-center gap-4 transition-all duration-300"
                        :class="phase === 'open' ? 'bg-[#1e2329]/90 border-emerald-500/30 shadow-emerald-500/10' : 'bg-[#1e2329]/90 border-rose-500/30 shadow-rose-500/10'">

                        <div class="flex flex-col items-end">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Status</span>
                            <span class="font-black text-sm tracking-tight"
                                :class="phase === 'open' ? 'text-emerald-400' : 'text-rose-400'"
                                x-text="phase === 'open' ? 'TRADING OPEN' : 'MARKET LOCKED'"></span>
                        </div>

                        <div class="w-[1px] h-8 bg-[#474d57]"></div>

                        <div class="flex flex-col items-start w-16">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Timer</span>
                            <span class="font-mono font-bold text-2xl text-white leading-none"
                                x-text="formatTimer()"></span>
                        </div>
                    </div>

                    <!-- Notification Toast -->
                    <div x-show="phase === 'locked'" x-transition:enter="transition ease-out duration-300 transform"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="mt-4 bg-[#0b0e11]/80 backdrop-blur px-4 py-2 rounded text-rose-400 text-xs font-bold border border-rose-500/20 shadow-lg flex items-center gap-2">
                        <div class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></div>
                        Wait for settlement...
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT: ORDER PANEL + ORDER BOOK -->
        <div class="w-[300px] 2xl:w-[340px] bg-[#181a20] border-l border-[#2b3139] flex flex-col shrink-0 z-20">

            <!-- Order Book (Top Half) -->
            <div class="flex-grow flex flex-col min-h-0 border-b border-[#2b3139]">
                <div
                    class="h-8 flex items-center px-4 text-[11px] font-bold text-slate-500 justify-between bg-[#181a20]">
                    <span>Price(USDT)</span>
                    <span>Amount(BTC)</span>
                </div>
                <!-- Dynamic Order List -->
                <div class="overflow-hidden relative flex-grow text-[11px] font-mono bg-[#0b0e11]">
                    <!-- Sells (Red) -->
                    <div class="flex flex-col-reverse justify-end h-[45%] overflow-hidden pb-1">
                        <template x-for="ask in asks" :key="ask.id">
                            <div
                                class="flex justify-between px-4 py-[2px] cursor-pointer order-book-row hover:bg-[#1e2329] transition-colors relative">
                                <span class="text-[#f6465d]" x-text="ask.price.toFixed(2)"></span>
                                <span class="text-slate-400" x-text="ask.amount.toFixed(4)"></span>
                                <!-- Volume Bar -->
                                <div class="absolute right-0 top-0 bottom-0 bg-[#f6465d]/10 z-0"
                                    :style="'width: ' + (ask.amount * 20) + '%'"></div>
                            </div>
                        </template>
                    </div>

                    <!-- Current Price Middle -->
                    <div
                        class="h-12 flex items-center justify-center border-y border-[#2b3139] bg-[#181a20] gap-2 z-10 relative shadow-lg">
                        <span class="text-2xl font-bold font-mono tracking-tight"
                            :class="lastPrice >= prevPrice ? 'text-[#0ecb81]' : 'text-[#f6465d]'"
                            x-text="lastPrice.toFixed(2)"></span>
                        <div class="text-[10px] font-bold text-slate-500 flex flex-col leading-none">
                            <span>$</span>
                            <span x-text="lastPrice.toFixed(2)"></span>
                        </div>
                    </div>

                    <!-- Buys (Green) -->
                    <div class="h-[45%] overflow-hidden pt-1">
                        <template x-for="bid in bids" :key="bid.id">
                            <div
                                class="flex justify-between px-4 py-[2px] cursor-pointer order-book-row hover:bg-[#1e2329] transition-colors relative">
                                <span class="text-[#0ecb81]" x-text="bid.price.toFixed(2)"></span>
                                <span class="text-slate-400" x-text="bid.amount.toFixed(4)"></span>
                                <!-- Volume Bar -->
                                <div class="absolute right-0 top-0 bottom-0 bg-[#0ecb81]/10 z-0"
                                    :style="'width: ' + (bid.amount * 20) + '%'"></div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Trading Panel (Bottom Half) -->
            <div class="p-4 bg-[#1e2329]">
                <!-- Type Selector -->
                <div class="flex bg-[#0b0e11] p-[2px] rounded mb-4 border border-[#2b3139]">
                    <button
                        class="flex-1 py-1.5 rounded text-xs font-bold bg-[#2b3139] text-white shadow">Market</button>
                    <button
                        class="flex-1 py-1.5 rounded text-xs font-bold text-slate-500 hover:text-white transition-colors">Limit</button>
                </div>

                <!-- Inputs -->
                <div class="space-y-3 mb-5">
                    <div>
                        <div class="flex justify-between text-[10px] font-bold text-slate-400 mb-1">
                            <span>Avbl Balance</span>
                            <span class="text-white font-mono"><span x-text="balance.toFixed(2)"></span> USDT</span>
                        </div>
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-3 flex items-center text-xs font-bold text-slate-500 group-hover:text-slate-300">
                                Amount</div>
                            <input type="number" x-model="betAmount"
                                class="w-full bg-[#0b0e11] border border-[#2b3139] group-hover:border-[#474d57] rounded h-10 pl-16 pr-10 text-right text-sm font-bold text-white focus:outline-none focus:border-[#f0b90b] transition-colors font-mono">
                            <div class="absolute inset-y-0 right-3 flex items-center text-xs font-bold text-white">USDT
                            </div>
                        </div>
                    </div>

                    <!-- Amount Slider -->
                    <div class="px-1">
                        <input type="range" min="10" max="1000" step="10" x-model="betAmount"
                            class="w-full h-1 bg-[#2b3139] rounded-lg appearance-none cursor-pointer accent-[#f0b90b]">
                        <div class="flex justify-between mt-1 text-[10px] text-slate-600 font-bold">
                            <span>10</span>
                            <span>500</span>
                            <span>1000</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <button @click="placeOrder('buy')" :disabled="phase !== 'open' || myPosition"
                        class="h-12 bg-[#0ecb81] hover:bg-[#0da86b] disabled:opacity-50 disabled:cursor-not-allowed rounded text-white font-bold text-sm transition-all flex flex-col items-center justify-center leading-none shadow-[0_4px_0_#0aa86b] active:shadow-none active:translate-y-[4px] relative overflow-hidden group">
                        <span class="relative z-10 text-base">Long</span>
                        <span class="relative z-10 text-[10px] opacity-80 uppercase">Buy Green</span>
                    </button>

                    <button @click="placeOrder('sell')" :disabled="phase !== 'open' || myPosition"
                        class="h-12 bg-[#f6465d] hover:bg-[#d93a4e] disabled:opacity-50 disabled:cursor-not-allowed rounded text-white font-bold text-sm transition-all flex flex-col items-center justify-center leading-none shadow-[0_4px_0_#d13045] active:shadow-none active:translate-y-[4px] relative overflow-hidden group">
                        <span class="relative z-10 text-base">Short</span>
                        <span class="relative z-10 text-[10px] opacity-80 uppercase">Sell Red</span>
                    </button>
                </div>

                <!-- Footer Info -->
                <div class="text-center">
                    <div class="text-[10px] text-slate-500 font-bold">Fee: 0% | Est. Return: 82%</div>
                </div>

                <!-- Active Position Card -->
                <div x-show="myPosition"
                    class="mt-4 p-3 bg-[#0b0e11] rounded border border-[#2b3139] relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1"
                        :class="myPosition?.type === 'buy' ? 'bg-[#0ecb81]' : 'bg-[#f6465d]'"></div>
                    <div class="flex justify-between text-[11px] mb-1 pl-2">
                        <span class="text-slate-400 font-bold">Entry Price</span>
                        <span class="font-mono text-white" x-text="myPosition?.entry.toFixed(2)"></span>
                    </div>
                    <div class="flex justify-between text-[11px] pl-2">
                        <span class="text-slate-400 font-bold">Unrealized PnL</span>
                        <span class="font-mono font-bold"
                            :class="(lastPrice - myPosition?.entry) * (myPosition?.type==='buy'?1:-1) > 0 ? 'text-[#0ecb81]' : 'text-[#f6465d]'">
                            <span
                                x-text="(lastPrice - myPosition?.entry) * (myPosition?.type==='buy'?1:-1) > 0 ? '+' : ''"></span>
                            <span
                                x-text="((lastPrice - myPosition?.entry) * (myPosition?.type==='buy'?1:-1)).toFixed(2)"></span>
                        </span>
                    </div>
                </div>

            </div>
        </div>

        <!-- Result Overlay -->
        <div x-show="showResult" style="display: none;"
            class="absolute inset-0 z-50 flex items-center justify-center pointer-events-none backdrop-blur-sm bg-black/10">
            <div class="bg-[#1e2329] border-2 p-8 rounded-2xl shadow-2xl text-center transform scale-100 animate-bounce min-w-[300px]"
                :class="lastWin ? 'border-[#0ecb81] shadow-[#0ecb81]/20' : 'border-[#f6465d] shadow-[#f6465d]/20'">
                <div class="text-6xl mb-2" x-text="lastWin ? '💎' : '💸'"></div>
                <h2 class="text-4xl font-black mb-1" :class="lastWin ? 'text-[#0ecb81]' : 'text-[#f6465d]'"
                    x-text="lastWin ? 'PROFIT' : 'LOSS'"></h2>
                <div class="text-white font-mono font-bold text-2xl mt-2 p-2 bg-black/20 rounded"
                    x-text="(lastWin ? '+' : '-') + '$' + Math.abs(lastPnL).toFixed(2)"></div>
            </div>
        </div>

    </div>

    <!-- AUDIO (Optional) -->
    <!-- <audio id="sound-tick" src="..."></audio> -->

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('proTrader', () => ({
                phase: 'open',
                timer: 10,
                balance: 1000,
                lastPrice: 43500.00,
                prevPrice: 43500.00,
                betAmount: 100,
                
                candles: [],
                asks: [],
                bids: [],
                
                myPosition: null,
                showResult: false,
                lastWin: false,
                lastPnL: 0,
                
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
                    for(let i=0; i<80; i++) {
                        let o = p;
                        let c = o + (Math.random() - 0.5) * 40;
                        let h = Math.max(o, c) + Math.random() * 10;
                        let l = Math.min(o, c) - Math.random() * 10;
                        this.candles.push({t, o, h, l, c});
                        p = c;
                    }
                    this.lastPrice = p;
                },

                generateOrderBook() {
                    this.asks = [];
                    this.bids = [];
                    for(let i=0; i<12; i++) { // More rows
                        this.asks.push({ id: 'a'+i, price: this.lastPrice + (i*1.5) + Math.random(), amount: Math.random() * 1.5 });
                        this.bids.push({ id: 'b'+i, price: this.lastPrice - (i*1.5) - Math.random(), amount: Math.random() * 1.5 });
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
                    
                    // Faster UI updates for order book flashing
                    setInterval(() => {
                        this.generateOrderBook();
                    }, 2000);
                },

                tick() {
                    this.timer--;
                    
                    // Phase Transition
                    if(this.phase === 'open' && this.timer <= 0) {
                        this.phase = 'locked';
                        this.timer = 20;
                    } else if(this.phase === 'locked' && this.timer <= 0) {
                        this.settle();
                        this.phase = 'open';
                        this.timer = 10;
                    }

                    // Price Movement
                    this.prevPrice = this.lastPrice;
                    // More volatility during 'locked' phase for drama
                    let vol = this.phase === 'locked' ? 30 : 15;
                    let move = (Math.random() - 0.5) * vol;
                    this.lastPrice += move;
                    
                    // Candle Update
                    let lastCandle = this.candles[this.candles.length-1];
                    lastCandle.c = this.lastPrice;
                    if(this.lastPrice > lastCandle.h) lastCandle.h = this.lastPrice;
                    if(this.lastPrice < lastCandle.l) lastCandle.l = this.lastPrice;
                    
                    // Add new candle periodically (every 5s)
                    if(this.timer % 5 === 0) {
                        let o = this.lastPrice;
                        this.candles.push({t: Date.now(), o, h:o, l:o, c:o});
                        if(this.candles.length > 80) this.candles.shift();
                    }

                    this.draw();
                },

                placeOrder(type) {
                    if(this.phase !== 'open') return;
                    if(this.balance < this.betAmount) return;
                    
                    let amount = parseInt(this.betAmount);
                    this.balance -= amount;
                    window.userBalance = this.balance;
                    
                    this.myPosition = {
                        type: type,
                        entry: this.lastPrice,
                        amount: amount
                    };
                },

                settle() {
                    if(!this.myPosition) {
                         // Just reset
                         return;
                    }
                    
                    let diff = this.lastPrice - this.myPosition.entry;
                    let win = false;
                    
                    if(this.myPosition.type === 'buy' && diff > 0) win = true;
                    if(this.myPosition.type === 'sell' && diff < 0) win = true;
                    
                    this.lastWin = win;
                    if(win) {
                        let profit = this.myPosition.amount * 1.82; 
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
                    const ctx = this.ctx;
                    const w = this.canvas.width / (window.devicePixelRatio || 1);
                    const h = this.canvas.height / (window.devicePixelRatio || 1);
                    
                    ctx.clearRect(0, 0, w, h);
                              // Background & Grid
                    ctx.fillStyle = '#0b0e11';
                    ctx.fillRect(0,0,w,h);
                    
                    ctx.strokeStyle = '#2b3139';
                    ctx.lineWidth = 1;
                    ctx.beginPath();
                    for(let x=0; x<w; x+=80) { ctx.moveTo(x, 0); ctx.lineTo(x, h); }
                    for(let y=0; y<h; y+=80) { ctx.moveTo(0, y); ctx.lineTo(w, y); }
                    ctx.stroke();
                    
                    // Scale
                    let min = Infinity, max = -Infinity;
                    this.candles.forEach(c => {
                        if(c.l < min) min = c.l;
                        if(c.h > max) max = c.h;
                    });
                     // Safety pad
                    let pad = (max - min) * 0.2;
                    if(pad === 0) pad = 10;
                    min -= pad; max += pad;
                    let range = max - min;
                    
                    // Draw Candles
                    let candleW = (w / 90) * 0.7;
                    let spacing = (w / 90) * 0.3;
                    
                    this.candles.forEach((c, i) => {
                        let isGreen = c.c >= c.o;
                        // Binance/Pintu colors
                        ctx.fillStyle = isGreen ? '#0ecb81' : '#f6465d';
                        ctx.strokeStyle = isGreen ? '#0ecb81' : '#f6465d';
                        
                        let x = i * (candleW + spacing) + spacing + 20; // offset
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
                    ctx.strokeStyle = '#f0b90b';
                    ctx.lineWidth = 1;
                    ctx.setLineDash([4, 4]);
                    ctx.beginPath();
                    ctx.moveTo(0, lastY);
                    ctx.lineTo(w, lastY);
                    ctx.stroke();
                    ctx.setLineDash([]);
                    
                     // Price Bubble
                    ctx.fillStyle = '#f0b90b';
                    ctx.fillRect(w - 70, lastY - 10, 70, 20);
                    ctx.fillStyle = '#1e2329';
                    ctx.font = 'bold 11px sans-serif';
                    ctx.fillText(this.lastPrice.toFixed(2), w - 60, lastY + 4);
                    
                    // Entry Line (if active)
                    if(this.myPosition) {
                        let entryY = h - ((this.myPosition.entry - min) / range) * h;
                        ctx.strokeStyle = '#3b82f6'; // Blue
                        ctx.lineWidth = 2;
                        ctx.beginPath();
                        ctx.moveTo(0, entryY);
                        ctx.lineTo(w, entryY);
                        ctx.stroke();
                        
                        // Label
                        ctx.fillStyle = '#3b82f6';
                        ctx.fillRect(w - 120, entryY - 22, 50, 20);
                        ctx.fillStyle = '#fff';
                        ctx.fillText('ENTRY', w - 110, entryY - 8);
                    }
                }
            }));
        });
    </script>
</body>

</html>