<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Crypto Trader Panic</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        body { background-color: #0f172a; color: white; } /* Fallback slate-900 */
        .glass-panel {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>

<body class="font-sans antialiased bg-slate-900 text-white overflow-hidden" style="background-color: #0f172a;">

    <!-- Game Container -->
    <div x-data="candleGame()" x-init="setTimeout(() => initGame(), 100)" x-cloak
        class="relative min-h-screen flex flex-col items-center justify-center p-4">

        <!-- Top Bar -->
        <div class="w-full max-w-7xl mb-4 z-50 flex justify-between items-center px-4">
            <a href="{{ route('homepage') }}"
                class="flex items-center gap-2 text-slate-400 hover:text-white transition-colors bg-white/5 px-4 py-2 rounded-full border border-white/5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="font-bold text-sm">EXIT</span>
            </a>

            <!-- Balance Pill -->
            <div class="bg-slate-800 px-6 py-2 rounded-2xl border border-slate-700 flex items-center gap-4 shadow-lg" style="background-color: #1e293b;">
                <div class="flex flex-col items-end leading-tight">
                     <span class="text-xs text-slate-400 font-bold uppercase tracking-widest">Real Account</span>
                     <span class="text-xl font-mono font-black text-emerald-400">$<span x-text="formatMoney(balance)"></span></span>
                </div>
                <div class="w-8 h-8 rounded-full bg-emerald-500/20 flex items-center justify-center border border-emerald-500">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="w-full max-w-7xl bg-slate-900 rounded-3xl overflow-hidden shadow-2xl relative border border-slate-800 h-[600px] flex" style="background-color: #0f172a; height: 75vh; min-height: 600px;">
            
            <!-- Chart Area (Full) -->
            <div class="absolute inset-0 z-0 bg-slate-900" style="background-color: #0f172a;">
                <!-- Grid -->
                <div class="absolute inset-0 opacity-5"
                    style="background-image: linear-gradient(#ffffff 1px, transparent 1px), linear-gradient(90deg, #ffffff 1px, transparent 1px); background-size: 50px 50px;">
                </div>
                <canvas id="candleChart" class="w-full h-full cursor-crosshair relative z-10"></canvas>
            </div>

            <!-- Start Overlay -->
            <div x-show="phase === 'idle'"
                class="absolute inset-0 z-50 flex flex-col items-center justify-center bg-slate-900/90 transition-all backdrop-blur-sm">
                <div class="text-center p-8 bg-slate-800 rounded-3xl border border-slate-700 shadow-2xl max-w-lg w-full">
                    <div class="w-16 h-16 bg-emerald-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                         <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                    <h2 class="text-3xl font-black mb-2 text-white">Market Ready</h2>
                    <p class="text-slate-400 mb-6">Predict the next candle. Green or Red.</p>
                    <button @click="startCycle()" class="w-full py-4 bg-emerald-500 hover:bg-emerald-400 rounded-xl font-bold text-slate-900 text-lg shadow-lg shadow-emerald-500/20 transition-transform hover:scale-[1.02]">
                        Start Trading
                    </button>
                </div>
            </div>

             <!-- Game Over Overlay -->
             <div x-show="phase === 'gameover'"
                class="absolute inset-0 z-50 flex flex-col items-center justify-center bg-slate-900/95 transition-all backdrop-blur">
                <h2 class="text-5xl font-black mb-4 text-rose-500">LIQUIDATED</h2>
                <div class="flex gap-4">
                    <button @click="fullReset()" class="px-8 py-3 bg-emerald-600 text-white rounded-xl font-bold hover:bg-emerald-500">Restart</button>
                    <a href="{{ route('homepage') }}" class="px-8 py-3 bg-white/10 text-white rounded-xl font-bold hover:bg-white/20">Exit</a>
                </div>
            </div>

            <!-- Left Info (Static) -->
            <div class="absolute top-6 left-6 z-20 pointer-events-none flex flex-col gap-3">
                 <div class="bg-slate-800/90 backdrop-blur p-4 rounded-xl border border-slate-700 shadow-xl w-64" style="background-color: rgba(30, 41, 59, 0.9);">
                     <span class="text-xs text-slate-500 font-bold uppercase block mb-1">Asset Status</span>
                     <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-mono font-black text-white" x-text="price.toFixed(2)"></span>
                     </div>
                     <div class="mt-1 flex items-center gap-2 text-sm font-bold" :class="lastClose >= lastOpen ? 'text-emerald-400' : 'text-rose-400'">
                         <span x-text="lastClose >= lastOpen ? '▲ BULLISH' : '▼ BEARISH'"></span>
                         <span x-text="((lastClose - lastOpen) / lastOpen * 100).toFixed(2) + '%'"></span>
                     </div>
                </div>
            </div>

            <!-- Right Controls (Glass) -->
            <div class="absolute right-6 top-6 bottom-6 w-80 rounded-2xl z-30 shadow-2xl flex flex-col overflow-hidden glass-panel">
                
                <!-- Status Header -->
                <div class="p-4 border-b border-white/10 flex justify-between items-center bg-white/5">
                     <span class="text-xs font-bold text-slate-400 uppercase">Trading Panel</span>
                     <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-white/10 text-white uppercase" x-text="phase"></span>
                </div>

                <!-- Timer -->
                <div class="p-6 flex flex-col items-center justify-center flex-grow">
                    <div class="relative w-40 h-40 flex items-center justify-center bg-slate-800/50 rounded-full border border-slate-700/50">
                         <div class="text-center">
                            <span class="block text-5xl font-black text-white font-mono" x-text="timer"></span>
                            <span class="text-xs font-bold text-slate-500 uppercase">Seconds</span>
                        </div>
                        <svg class="absolute inset-0 w-full h-full transform -rotate-90 pointer-events-none">
                            <circle cx="80" cy="80" r="78" stroke="currentColor" class="text-slate-700" stroke-width="4" fill="none"></circle>
                            <circle cx="80" cy="80" r="78" 
                                :stroke="phase === 'watching' ? '#38bdf8' : '#eab308'" 
                                stroke-width="4" fill="none"
                                stroke-dasharray="490"
                                :stroke-dashoffset="490 - (490 * timer / (phase === 'watching' ? 20 : 10))"
                                class="transition-all duration-1000 ease-linear"></circle>
                        </svg>
                    </div>
                    
                    <div class="mt-6 text-center text-sm font-bold text-slate-300">
                        <span x-show="phase === 'watching'">Wait for market freeze...</span>
                        <span x-show="phase === 'deciding'" class="text-yellow-400 animate-pulse">PLACE YOUR ORDER!</span>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="p-4 bg-slate-800/80 border-t border-white/10 pb-6">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-xs font-bold text-slate-500">Bet Amount</span>
                        <span class="text-sm font-black text-white">$<span x-text="Math.floor(balance * 0.5)"></span></span>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <button @click="placeOrder('buy')" 
                            :disabled="balance <= 0 || phase !== 'deciding'"
                            class="h-14 bg-emerald-600 hover:bg-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg font-black shadow-md shadow-emerald-900/50 transition-all active:scale-95 flex flex-col items-center justify-center">
                            <span>UP</span>
                            <span class="text-[9px] font-bold text-emerald-200 uppercase">Green Candle</span>
                        </button>

                        <button @click="placeOrder('sell')" 
                            :disabled="balance <= 0 || phase !== 'deciding'"
                            class="h-14 bg-rose-600 hover:bg-rose-500 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg font-black shadow-md shadow-rose-900/50 transition-all active:scale-95 flex flex-col items-center justify-center">
                            <span>DOWN</span>
                             <span class="text-[9px] font-bold text-rose-200 uppercase">Red Candle</span>
                        </button>
                    </div>
                </div>

            </div>

             <!-- Result Notification -->
            <div x-show="showResult" 
                class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50 px-8 py-6 rounded-2xl bg-slate-800 border border-slate-600 shadow-2xl flex flex-col items-center text-center"
                style="display: none;"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100">
                <div class="text-4xl mb-2" x-text="lastWin ? '🏆' : '📉'"></div>
                <div class="text-2xl font-black mb-1" :class="lastWin ? 'text-emerald-400' : 'text-rose-400'" x-text="lastWin ? 'WIN' : 'LOSS'"></div>
                <div class="text-white font-mono font-bold" x-text="(lastWin ? '+' : '-') + '$' + lastResultAmt"></div>
            </div>

        </div>
    </div>

    <!-- Logic -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('candleGame', () => ({
                phase: 'idle',
                balance: 1000,
                price: 29500.00,
                candles: [],
                timer: 0,
                gameInterval: null,
                showResult: false,
                lastWin: false,
                lastResultAmt: 0,
                
                // Canvas
                canvas: null,
                ctx: null,
                
                get lastOpen() { return this.candles.length ? this.candles[this.candles.length-1].o : this.price },
                get lastClose() { return this.candles.length ? this.candles[this.candles.length-1].c : this.price },

                initGame() {
                    this.canvas = document.getElementById('candleChart');
                    this.setupCanvas();
                    this.generateInitialCandles(50);
                    this.drawCandles();
                },

                setupCanvas() {
                    const dpr = window.devicePixelRatio || 1;
                    const rect = this.canvas.getBoundingClientRect();
                    this.canvas.width = rect.width * dpr;
                    this.canvas.height = rect.height * dpr;
                    this.ctx = this.canvas.getContext('2d');
                    this.ctx.scale(dpr, dpr);
                },

                formatMoney(val) {
                    return val.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                },

                generateInitialCandles(count) {
                    this.candles = [];
                    let currentPrice = this.price;
                    for(let i=0; i<count; i++) {
                        let o = currentPrice;
                        let c = o + (Math.random() - 0.5) * 40;
                        let h = Math.max(o, c) + Math.random() * 15;
                        let l = Math.min(o, c) - Math.random() * 15;
                        this.candles.push({o, h, l, c});
                        currentPrice = c;
                    }
                    this.price = currentPrice;
                },

                startCycle() {
                    if(this.balance <= 0) {
                         this.phase = 'gameover';
                         return;
                    }
                    this.showResult = false;
                    this.phase = 'watching';
                    this.timer = 20;
                    
                    if(this.gameInterval) clearInterval(this.gameInterval);
                    
                    this.gameInterval = setInterval(() => {
                        this.tickMarket();
                        this.timer--;
                        if(this.timer <= 0) this.enterDecisionPhase();
                    }, 1000); 
                },

                tickMarket() {
                    let o = this.price;
                    let c = o + (Math.random() - 0.5) * 30; // Volatility
                    let h = Math.max(o, c) + Math.random() * 10;
                    let l = Math.min(o, c) - Math.random() * 10;
                    
                    this.candles.push({o, h, l, c});
                    if(this.candles.length > 70) this.candles.shift();
                    this.price = c;
                    this.drawCandles();
                },

                enterDecisionPhase() {
                    this.phase = 'deciding';
                    this.timer = 10;
                    
                    clearInterval(this.gameInterval);
                    this.gameInterval = setInterval(() => {
                        this.timer--;
                        if(this.timer <= 0) this.startCycle();
                    }, 1000);
                },

                placeOrder(type) {
                    if(this.phase !== 'deciding') return;
                    clearInterval(this.gameInterval);
                    
                    let o = this.price;
                    let move = (Math.random() - 0.5) * 80;
                    if(Math.abs(move) < 15) move = move > 0 ? 30 : -30;
                    
                    let c = o + move;
                    let h = Math.max(o, c) + Math.random() * 10;
                    let l = Math.min(o, c) - Math.random() * 10;
                    
                    this.candles.push({o, h, l, c});
                    if(this.candles.length > 70) this.candles.shift();
                    this.price = c;
                    this.drawCandles();

                    let isGreen = c > o;
                    let won = (type === 'buy' && isGreen) || (type === 'sell' && !isGreen);
                    let bet = Math.floor(this.balance * 0.5);
                    this.lastResultAmt = won ? Math.floor(bet * 0.82) : bet;
                    this.lastWin = won;
                    
                    if(won) this.balance += this.lastResultAmt;
                    else this.balance -= this.lastResultAmt;
                    
                    if(this.balance < 1) this.balance = 0;
                    this.showResult = true;
                    
                    setTimeout(() => this.startCycle(), 2000);
                },

                fullReset() {
                    this.balance = 1000;
                    this.generateInitialCandles(50);
                    this.drawCandles();
                    this.startCycle();
                },

                drawCandles() {
                    if(!this.ctx) return;
                    const ctx = this.ctx;
                    const w = this.canvas.width / (window.devicePixelRatio || 1);
                    const h = this.canvas.height / (window.devicePixelRatio || 1);
                    
                    ctx.clearRect(0, 0, w, h);
                    
                    // Scaling
                    let min = Infinity, max = -Infinity;
                    this.candles.forEach(c => {
                        if(c.l < min) min = c.l;
                        if(c.h > max) max = c.h;
                    });
                    let pad = (max - min) * 0.15;
                    min -= pad; max += pad;
                    let range = max - min;
                    
                    let candleW = (w / 70) * 0.6;
                    let spacing = (w / 70) * 0.4;
                    
                    // Grid
                    ctx.strokeStyle = '#334155';
                    ctx.lineWidth = 1;
                    ctx.beginPath();
                    for(let i=1; i<5; i++) {
                       let y = i * (h/5);
                       ctx.moveTo(0, y);
                       ctx.lineTo(w, y);
                    }
                    ctx.stroke();

                    // Candles
                    this.candles.forEach((c, i) => {
                        let isGreen = c.c >= c.o;
                        // Bright Green/Red
                        ctx.fillStyle = isGreen ? '#10b981' : '#f43f5e';
                        ctx.strokeStyle = isGreen ? '#10b981' : '#f43f5e';
                        
                        let x = i * (candleW + spacing) + spacing;
                        let yH = h - ((c.h - min) / range) * h;
                        let yL = h - ((c.l - min) / range) * h;
                        let yO = h - ((c.o - min) / range) * h;
                        let yC = h - ((c.c - min) / range) * h;
                        
                        ctx.beginPath();
                        ctx.moveTo(x + candleW/2, yH);
                        ctx.lineTo(x + candleW/2, yL);
                        ctx.stroke();
                        
                        let bodyTop = Math.min(yO, yC);
                        let bodyH = Math.abs(yO - yC);
                        if(bodyH < 1) bodyH = 1;
                        ctx.fillRect(x, bodyTop, candleW, bodyH);
                    });
                    
                    // Price Line
                    let lastY = h - ((this.price - min) / range) * h;
                    ctx.beginPath();
                    ctx.strokeStyle = '#ffffff';
                    ctx.setLineDash([4, 4]);
                    ctx.moveTo(0, lastY);
                    ctx.lineTo(w, lastY);
                    ctx.stroke();
                    ctx.setLineDash([]);
                    
                     // Price Bubble
                    ctx.fillStyle = '#ffffff';
                    ctx.beginPath();
                    ctx.roundRect(w - 60, lastY - 10, 50, 20, 4);
                    ctx.fill();
                    
                    ctx.fillStyle = '#000';
                    ctx.font = 'bold 10px sans-serif';
                    ctx.textAlign = 'center';
                    ctx.fillText(this.price.toFixed(2), w - 35, lastY + 4);
                }
            }));
        });
    </script>
</body>
</html>