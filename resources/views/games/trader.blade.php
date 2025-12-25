<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Crypto Trader Panic</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .neon-text-glow {
            text-shadow: 0 0 10px rgba(16, 185, 129, 0.5);
        }

        [x-cloak] {
            display: none !important;
        }

        /* Custom Font for numbers */
        @font-face {
            font-family: 'Mono';
            src: local('Courier New');
        }
    </style>
</head>

<body
    class="font-sans antialiased bg-[#0e1018] text-white selection:bg-emerald-500 selection:text-white overflow-hidden">

    <!-- Game Container -->
    <div x-data="candleGame()" x-init="setTimeout(() => initGame(), 100)" x-cloak
        class="relative min-h-screen flex flex-col items-center justify-center p-4">

        <!-- Top Bar -->
        <div class="w-full max-w-[1400px] mb-4 z-50 flex justify-between items-center px-4">
            <!-- Back Button -->
            <a href="{{ route('homepage') }}"
                class="flex items-center gap-2 text-slate-400 hover:text-white transition-colors bg-white/5 px-4 py-2 rounded-full hover:bg-white/10 border border-white/5 backdrop-blur-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="font-bold text-sm">EXIT</span>
            </a>

            <!-- Balance Pill -->
            <div
                class="bg-[#1e222d] px-6 py-2 rounded-2xl border border-slate-700/50 flex items-center gap-4 shadow-lg">
                <div class="flex flex-col items-end leading-tight">
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Real Account</span>
                    <span class="text-xl font-mono font-black text-emerald-400">$<span
                            x-text="formatMoney(balance)"></span></span>
                </div>
                <div
                    class="w-8 h-8 rounded-full bg-emerald-500/20 flex items-center justify-center border border-emerald-500">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Main Trading Card -->
        <div
            class="w-full max-w-[1400px] bg-[#131722] rounded-[24px] overflow-hidden shadow-2xl relative border border-slate-800 h-[80vh] min-h-[600px] flex">

            <!-- Chart Area (Full Background) -->
            <div class="absolute inset-0 z-0 bg-[#131722]">
                <!-- Grid Background -->
                <div class="absolute inset-0 opacity-[0.05]"
                    style="background-image: linear-gradient(#363c4e 1px, transparent 1px), linear-gradient(90deg, #363c4e 1px, transparent 1px); background-size: 60px 60px;">
                </div>
                <canvas id="candleChart" class="w-full h-full cursor-crosshair relative z-10"></canvas>
            </div>

            <!-- Start Overlay -->
            <div x-show="phase === 'idle'"
                class="absolute inset-0 z-50 flex flex-col items-center justify-center bg-[#0a0e17]/80 backdrop-blur-sm transition-all">
                <div
                    class="text-center p-10 bg-[#1e222d] rounded-3xl border border-slate-700 shadow-2xl max-w-lg w-full transform hover:scale-[1.01] transition-transform">
                    <div class="w-20 h-20 bg-emerald-500/10 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <h2 class="text-4xl font-black mb-2 text-white tracking-tight">Binary Market</h2>
                    <p class="text-slate-400 mb-8 text-sm font-medium">Predict the next candle color.<br>Green for UP,
                        Red for DOWN.</p>

                    <button @click="startCycle()"
                        class="w-full py-4 bg-emerald-500 hover:bg-emerald-400 rounded-xl font-bold text-[#0a0e17] text-lg transition-all shadow-[0_0_20px_rgba(16,185,129,0.3)]">
                        Start Session
                    </button>
                    <div class="mt-4 text-xs text-slate-500 font-bold uppercase tracking-widest">Initial Balance:
                        $1,000.00</div>
                </div>
            </div>

            <!-- Game Over Overlay -->
            <div x-show="phase === 'gameover'"
                class="absolute inset-0 z-50 flex flex-col items-center justify-center bg-[#0a0e17]/95 backdrop-blur-md transition-all">
                <div class="text-6xl mb-4">💀</div>
                <h2 class="text-5xl font-black mb-2 text-white">Account Blown</h2>
                <p class="text-slate-400 mb-8">You lost all your capital.</p>
                <div class="flex gap-4 mt-4">
                    <button @click="fullReset()"
                        class="px-8 py-3 bg-white text-black rounded-xl font-bold hover:bg-gray-200 transition-colors">Refill
                        Account</button>
                    <a href="{{ route('homepage') }}"
                        class="px-8 py-3 border border-slate-700 text-slate-300 rounded-xl font-bold hover:bg-slate-800 transition-colors">Exit</a>
                </div>
            </div>

            <!-- Left HUD (Price & Asset) -->
            <div class="absolute top-6 left-6 z-20 pointer-events-none flex flex-col gap-3">
                <!-- Asset -->
                <div
                    class="bg-[#1e222d] border border-slate-700/50 p-3 rounded-xl shadow-xl flex items-center gap-3 w-max">
                    <div class="w-8 h-8 rounded-full bg-[#f7931a] flex items-center justify-center shadow-sm">
                        <span class="text-white font-black text-xs">₿</span>
                    </div>
                    <div>
                        <div class="text-white font-bold text-sm leading-none">Bitcoin</div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase mt-1">BTC/USD 82%</div>
                    </div>
                    <div class="h-8 w-[1px] bg-slate-700 mx-1"></div>
                    <div class="text-emerald-400 font-bold text-sm">+0.05%</div>
                </div>

                <!-- Live Price -->
                <div class="bg-[#1e222d] border border-slate-700/50 p-4 rounded-xl shadow-xl w-64">
                    <span class="text-[10px] text-slate-500 font-bold uppercase block mb-1">Current Price</span>
                    <div class="text-3xl font-mono font-black text-white tracking-tighter flex items-center gap-2">
                        <span x-text="price.toFixed(2)"></span>
                        <span class="text-sm font-bold bg-white/10 px-2 py-0.5 rounded text-white"
                            :class="lastClose >= lastOpen ? 'bg-emerald-500/20 text-emerald-400' : 'bg-rose-500/20 text-rose-400'">
                            <span x-text="lastClose >= lastOpen ? '▲' : '▼'"></span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Right Control Panel (Floating Glass) -->
            <div
                class="absolute right-6 top-6 bottom-6 w-[340px] bg-[#1e222d]/95 backdrop-blur-xl border border-slate-700/50 rounded-2xl z-30 shadow-2xl flex flex-col overflow-hidden">

                <!-- Header -->
                <div class="p-4 border-b border-white/5 bg-white/5">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Trading Panel</span>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full"
                                :class="phase === 'watching' ? 'bg-orange-400 animate-pulse' : 'bg-emerald-400'"></div>
                            <span class="text-[10px] font-bold text-white uppercase"
                                x-text="phase === 'watching' ? 'WAIT' : 'TRADE'"></span>
                        </div>
                    </div>
                </div>

                <!-- Timer Section -->
                <div class="p-8 flex flex-col items-center justify-center flex-grow relative">
                    <!-- Progress Ring -->
                    <div class="relative w-48 h-48">
                        <svg class="w-full h-full transform -rotate-90">
                            <!-- Background Circle -->
                            <circle cx="96" cy="96" r="88" stroke="#2a2e39" stroke-width="12" fill="none"></circle>
                            <!-- Progress Circle -->
                            <circle cx="96" cy="96" r="88" :stroke="phase === 'watching' ? '#f59e0b' : '#10b981'"
                                stroke-width="12" fill="none" stroke-dasharray="552"
                                :stroke-dashoffset="552 - (552 * timer / (phase === 'watching' ? 20 : 10))"
                                stroke-linecap="round"
                                class="transition-all duration-1000 ease-linear shadow-[0_0_15px_currentColor]">
                            </circle>
                        </svg>

                        <!-- Center Text -->
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-[10px] uppercase font-bold text-slate-400 mb-1"
                                x-text="phase === 'watching' ? 'Next Candle' : 'Time Left'"></span>
                            <span class="text-6xl font-black text-white font-mono tracking-tighter"
                                x-text="timer"></span>
                            <span class="text-sm font-bold text-slate-500 mt-1">seconds</span>
                        </div>
                    </div>

                    <!-- Notification Pill -->
                    <div class="absolute bottom-6 px-4 py-2 rounded-full bg-white/5 border border-white/10 backdrop-blur-md text-xs font-bold text-slate-300"
                        x-show="phase === 'watching'" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0">
                        Please wait for freeze...
                    </div>
                    <div class="absolute bottom-6 px-4 py-2 rounded-full bg-emerald-500/20 border border-emerald-500/50 backdrop-blur-md text-xs font-bold text-emerald-400 animate-pulse"
                        x-show="phase === 'deciding'" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0">
                        MARKET OPEN - TRADE NOW
                    </div>
                </div>

                <!-- Controls Section -->
                <div class="p-5 bg-[#171b26] border-t border-white/5">

                    <div class="flex justify-between items-center mb-4 px-1">
                        <span class="text-xs text-slate-400 font-bold">Investment</span>
                        <div class="bg-[#2a2e39] px-3 py-1 rounded text-white font-mono font-bold text-sm">$<span
                                x-text="Math.floor(balance * 0.5)"></span></div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <button @click="placeOrder('buy')" :disabled="balance <= 0 || phase !== 'deciding'"
                            class="h-16 bg-[#0faf59] hover:bg-[#12c463] disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-xl shadow-[0_4px_0_#065f32] active:shadow-none active:translate-y-[4px] transition-all flex flex-col items-center justify-center group overflow-hidden relative">
                            <span class="text-sm font-black uppercase z-10">UP</span>
                            <div
                                class="absolute inset-0 bg-white/10 translate-y-full group-hover:translate-y-0 transition-transform">
                            </div>
                        </button>

                        <button @click="placeOrder('sell')" :disabled="balance <= 0 || phase !== 'deciding'"
                            class="h-16 bg-[#ff444f] hover:bg-[#ff5761] disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-xl shadow-[0_4px_0_#99232a] active:shadow-none active:translate-y-[4px] transition-all flex flex-col items-center justify-center group overflow-hidden relative">
                            <span class="text-sm font-black uppercase z-10">DOWN</span>
                            <div
                                class="absolute inset-0 bg-white/10 translate-y-full group-hover:translate-y-0 transition-transform">
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Result Toast (Floating Center) -->
            <div x-show="showResult"
                class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-40 px-8 py-4 rounded-2xl backdrop-blur-xl border shadow-2xl flex flex-col items-center"
                :class="lastWin ? 'bg-emerald-500/10 border-emerald-500/50' : 'bg-rose-500/10 border-rose-500/50'"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
                <span class="text-3xl mb-1" x-text="lastWin ? '🎉' : '💸'"></span>
                <span class="text-2xl font-black" :class="lastWin ? 'text-emerald-400' : 'text-rose-400'"
                    x-text="lastWin ? 'PROFIT' : 'LOSS'"></span>
                <span class="font-mono font-bold text-white text-lg"
                    x-text="(lastWin ? '+' : '-') + '$' + lastResultAmt"></span>
            </div>

        </div>
    </div>

    <!-- Logic -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('candleGame', () => ({
                phase: 'idle', // idle, watching, deciding, gameover
                balance: 1000,
                price: 43250.00,
                candles: [], // {o,h,l,c}
                timer: 0,
                gameInterval: null,
                sessionLevel: 1,
                showResult: false,
                lastWin: false,
                lastResultAmt: 0,
                
                // Canvas vars
                canvas: null,
                ctx: null,
                
                // Helper accessors
                get lastOpen() { return this.candles.length ? this.candles[this.candles.length-1].o : this.price },
                get lastClose() { return this.candles.length ? this.candles[this.candles.length-1].c : this.price },

                initGame() {
                    this.canvas = document.getElementById('candleChart');
                    this.setupCanvas();
                    // Generate initial history
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
                    
                    // Phase 1: Watching (20s)
                    this.phase = 'watching';
                    this.timer = 20;
                    
                    if(this.gameInterval) clearInterval(this.gameInterval);
                    
                    this.gameInterval = setInterval(() => {
                        this.tickMarket();
                        this.timer--;
                        
                        if(this.timer <= 0) {
                            this.enterDecisionPhase();
                        }
                    }, 1000); 
                },

                tickMarket() {
                    // Create volatile market movement
                    let o = this.price;
                    let c = o + (Math.random() - 0.5) * 35; // Volatility
                    let h = Math.max(o, c) + Math.random() * 10;
                    let l = Math.min(o, c) - Math.random() * 10;
                    
                    this.candles.push({o, h, l, c});
                    if(this.candles.length > 70) this.candles.shift(); // Limit history
                    this.price = c;
                    this.drawCandles();
                },

                enterDecisionPhase() {
                    this.phase = 'deciding';
                    this.timer = 10;
                    
                    clearInterval(this.gameInterval);
                    this.gameInterval = setInterval(() => {
                        this.timer--;
                        if(this.timer <= 0) {
                             // Timeout -> Restart loop
                             this.startCycle(); 
                        }
                    }, 1000);
                },

                placeOrder(type) {
                    if(this.phase !== 'deciding') return;
                    clearInterval(this.gameInterval);
                    
                    // 1. Generate Result Candle
                    let o = this.price;
                    // Force a definitive move for clear result
                    let move = (Math.random() - 0.5) * 80;
                    if(Math.abs(move) < 15) move = move > 0 ? 30 : -30;
                    
                    let c = o + move;
                    let h = Math.max(o, c) + Math.random() * 10;
                    let l = Math.min(o, c) - Math.random() * 10;
                    
                    this.candles.push({o, h, l, c});
                    if(this.candles.length > 70) this.candles.shift();
                    this.price = c;
                    this.drawCandles();

                    // 2. Logic
                    let isGreen = c > o;
                    let won = (type === 'buy' && isGreen) || (type === 'sell' && !isGreen);
                    let bet = Math.floor(this.balance * 0.5); // 50% bet
                    
                    this.lastResultAmt = won ? Math.floor(bet * 0.82) : bet;
                    this.lastWin = won;
                    
                    if(won) {
                        this.balance += this.lastResultAmt;
                    } else {
                        this.balance -= this.lastResultAmt;
                    }
                    
                    if(this.balance < 1) this.balance = 0;
                    
                    this.showResult = true;
                    
                    // 3. Next Cycle
                    setTimeout(() => {
                         this.startCycle();
                    }, 2000); 
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
                     // Add padding
                    let padding = (max - min) * 0.15;
                    min -= padding;
                    max += padding;
                    let range = max - min;
                    
                    // Layout calculations
                    let candleW = (w / 70) * 0.6; 
                    let spacing = (w / 70) * 0.4;
                    
                    // Draw Grid Lines (Horizontal)
                    ctx.strokeStyle = '#2a2e39'; // Dark grid
                    ctx.lineWidth = 1;
                    ctx.beginPath();
                    for(let i=1; i<5; i++) {
                        let y = i * (h/5);
                        ctx.moveTo(0, y);
                        ctx.lineTo(w, y);
                    }
                    ctx.stroke();

                    // Draw Candles
                    this.candles.forEach((c, i) => {
                        let isGreen = c.c >= c.o;
                        // Binomo Colors: Green #0faf59, Red #ff444f
                        ctx.fillStyle = isGreen ? '#0faf59' : '#ff444f';
                        ctx.strokeStyle = isGreen ? '#0faf59' : '#ff444f';
                        
                        let x = i * (candleW + spacing) + spacing;
                        
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
                    ctx.roundRect(w - 70, lastY - 12, 60, 24, 4);
                    ctx.fill();
                    
                    ctx.fillStyle = '#000';
                    ctx.font = 'bold 11px Inter';
                    ctx.textAlign = 'center';
                    ctx.fillText(this.price.toFixed(2), w - 40, lastY + 4);
                }
            }));
        });
    </script>
</body>

</html>