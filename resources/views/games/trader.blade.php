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

        .neon-box-glow {
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.3);
        }

        canvas {
            filter: drop-shadow(0 0 8px rgba(34, 197, 94, 0.4));
        }

        [x-cloak] {
            display: none !important;
        }

        /* Custom scrollbar for game logs if needed */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #0f172a;
        }

        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }
    </style>
</head>

<body
    class="font-sans antialiased bg-slate-900 text-white selection:bg-emerald-500 selection:text-white overflow-hidden">

    <!-- Game Container (Full Screen) -->
    <div x-data="candleGame()" x-init="setTimeout(() => initGame(), 100)" x-cloak
        class="relative min-h-screen flex flex-col items-center justify-center p-4">

        <!-- Back Button (Static Flow) -->
        <div class="w-full max-w-6xl mb-4 z-50 flex justify-between items-center">
            <a href="{{ route('homepage') }}"
                class="inline-flex items-center gap-2 text-slate-400 hover:text-white transition-colors bg-slate-800/50 px-4 py-2 rounded-full border border-slate-700/50 hover:bg-slate-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                <span class="font-bold tracking-wider text-sm">EXIT</span>
            </a>

            <div class="text-xs font-bold text-slate-500 uppercase tracking-widest">
                SESSION #<span x-text="sessionLevel"></span>
            </div>
        </div>

        <!-- Header -->
        <div class="w-full max-w-6xl flex justify-between items-end mb-4 z-10">
            <div>
                <h1 class="text-4xl font-extrabold tracking-tight mb-1 text-emerald-400 neon-text-glow">
                    CANDLE TRADER</h1>
                <p class="text-slate-400 font-medium">Watch the trend. Catch the break.</p>
            </div>
            <div class="text-right">
                <div class="text-sm text-slate-400 uppercase tracking-widest font-bold mb-1">Account Balance</div>
                <div class="text-4xl font-black font-mono tracking-tight"
                    :class="balance >= 1000 ? 'text-emerald-400' : (balance > 0 ? 'text-white' : 'text-rose-400')">
                    $<span x-text="formatMoney(balance)"></span>
                </div>
            </div>
        </div>

        <!-- Main Board -->
        <div
            class="w-full max-w-6xl bg-slate-800/50 backdrop-blur-xl border border-slate-700/50 rounded-3xl p-1 shadow-2xl relative overflow-hidden neon-box-glow flex flex-col md:flex-row gap-1">

            <!-- Chart Area (Left/Top) -->
            <div class="relative h-[500px] flex-grow bg-slate-900 rounded-[20px] overflow-hidden group">
                <!-- Grid -->
                <div class="absolute inset-0 opacity-10"
                    style="background-image: linear-gradient(#334155 1px, transparent 1px), linear-gradient(90deg, #334155 1px, transparent 1px); background-size: 50px 50px;">
                </div>

                <canvas id="candleChart" class="relative z-10 w-full h-full cursor-crosshair"></canvas>

                <!-- Phase HUD -->
                <div class="absolute top-6 left-6 z-20 pointer-events-none">
                    <div x-show="phase === 'watching'" class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-cyan-400 animate-ping"></div>
                        <span class="text-cyan-400 font-bold uppercase tracking-widest text-lg drop-shadow-md">Market
                            Active</span>
                    </div>
                    <div x-show="phase === 'deciding'" class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-yellow-400 animate-pulse"></div>
                        <span class="text-yellow-400 font-bold uppercase tracking-widest text-lg drop-shadow-md">MARKET
                            FROZEN - PLACE ORDER</span>
                    </div>
                </div>

                <!-- Timer HUD -->
                <div class="absolute top-6 right-6 z-20 pointer-events-none text-right">
                    <span class="text-xs text-slate-500 font-bold uppercase block">Phase Timer</span>
                    <span class="text-4xl font-black font-mono text-white" x-text="timer + 's'"></span>
                </div>

                <!-- Start Overlay -->
                <div x-show="phase === 'idle'" style="display: none;"
                    class="absolute inset-0 z-50 flex flex-col items-center justify-center bg-slate-900 transition-opacity">
                    <h2 class="text-5xl font-black mb-6 text-white tracking-tighter">READY TO TRADE?</h2>
                    <p class="text-slate-400 max-w-md text-center mb-8">
                        1. Watch candles form (20s).<br>
                        2. Market freezes (10s).<br>
                        3. Predict the NEXT candle color.<br>
                        Win = +50% | Lose = -50%
                    </p>
                    <button @click="startCycle()"
                        style="background-color: #10b981; box-shadow: 0 0 30px rgba(16, 185, 129, 0.4);"
                        class="px-16 py-6 rounded-full font-black text-2xl text-white hover:scale-105 transition-all transform border-4 border-emerald-900/50">
                        START MARKET
                    </button>
                    <p class="mt-8 text-slate-600 font-bold font-mono">Balance: $1,000.00</p>
                </div>

                <!-- Game Over Overlay -->
                <div x-show="phase === 'gameover'" style="display: none;"
                    class="absolute inset-0 z-50 flex flex-col items-center justify-center bg-slate-900 transition-opacity">
                    <h2 class="text-6xl font-black mb-4 text-rose-500">LIQUIDATED</h2>
                    <p class="text-xl text-slate-400 mb-8">Account Balance: $0.00</p>
                    <div class="flex gap-4">
                        <button @click="fullReset()"
                            class="px-8 py-3 bg-emerald-500 text-white rounded-full font-bold hover:bg-emerald-400 transition-colors">
                            Restart
                        </button>
                        <a href="{{ route('homepage') }}"
                            class="px-8 py-3 bg-slate-800 border border-slate-700 text-white rounded-full font-bold hover:bg-slate-700 transition-colors">
                            Exit
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sidebar Controls (Right/Bottom) -->
            <div class="w-full md:w-80 bg-slate-900/80 p-4 flex flex-col gap-4 relative">

                <!-- Status Bar -->
                <div class="h-2 w-full bg-slate-800 rounded-full overflow-hidden mb-2">
                    <div class="h-full transition-all duration-100 ease-linear"
                        :class="phase === 'watching' ? 'bg-cyan-500' : 'bg-yellow-500'"
                        :style="'width: ' + (timer / (phase === 'watching' ? 20 : 10) * 100) + '%'">
                    </div>
                </div>

                <!-- Last Candle Info -->
                <div class="bg-slate-800 rounded-xl p-4 border border-slate-700">
                    <div class="text-xs text-slate-500 uppercase font-bold mb-2">Current Price</div>
                    <div class="text-3xl font-mono font-bold text-white mb-1" x-text="'$' + price.toFixed(2)"></div>
                    <div class="flex items-center gap-2 text-sm font-bold"
                        :class="lastClose >= lastOpen ? 'text-emerald-400' : 'text-rose-400'">
                        <span x-text="lastClose >= lastOpen ? 'BULLISH ▲' : 'BEARISH ▼'"></span>
                        <span x-text="((lastClose - lastOpen) / lastOpen * 100).toFixed(2) + '%'"></span>
                    </div>
                </div>

                <!-- Controls -->
                <div class="flex-grow flex flex-col justify-end gap-3 relative">
                    <!-- Overlay if not deciding -->
                    <div x-show="phase !== 'deciding'"
                        class="absolute inset-0 bg-slate-900/80 backdrop-blur-[2px] z-10 flex items-center justify-center rounded-xl border border-dashed border-slate-700">
                        <span class="text-slate-500 font-bold uppercase text-xs tracking-wider flex items-center gap-2">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Wait for Freeze
                        </span>
                    </div>

                    <button @click="placeOrder('buy')" :disabled="balance <= 0"
                        class="group relative h-20 bg-emerald-600 hover:bg-emerald-500 rounded-xl transition-all flex items-center justify-between px-6 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/20 to-transparent"></div>
                        <span class="text-2xl font-black text-white z-10">BUY</span>
                        <span
                            class="text-emerald-200 text-xs font-bold uppercase z-10 text-right">Predict<br>GREEN</span>
                        <div class="absolute right-0 top-0 bottom-0 w-2 bg-emerald-400"></div>
                    </button>

                    <button @click="placeOrder('sell')" :disabled="balance <= 0"
                        class="group relative h-20 bg-rose-600 hover:bg-rose-500 rounded-xl transition-all flex items-center justify-between px-6 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-rose-500/20 to-transparent"></div>
                        <span class="text-2xl font-black text-white z-10">SELL</span>
                        <span class="text-rose-200 text-xs font-bold uppercase z-10 text-right">Predict<br>RED</span>
                        <div class="absolute right-0 top-0 bottom-0 w-2 bg-rose-400"></div>
                    </button>

                    <div class="text-center text-xs text-slate-500 font-bold mt-2">
                        Potential Win: <span class="text-emerald-400">+50%</span> | Loss: <span
                            class="text-rose-400">-50%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Logic -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('candleGame', () => ({
                phase: 'idle', // idle, watching, deciding, gameover
                balance: 1000,
                price: 1540.00,
                candles: [], // {o,h,l,c}
                timer: 0,
                gameInterval: null,
                sessionLevel: 1,
                
                // Canvas vars
                canvas: null,
                ctx: null,
                candleWidth: 15,
                spacing: 5,
                
                // Helper accessors
                get lastOpen() { return this.candles.length ? this.candles[this.candles.length-1].o : this.price },
                get lastClose() { return this.candles.length ? this.candles[this.candles.length-1].c : this.price },

                initGame() {
                    this.canvas = document.getElementById('candleChart');
                    this.setupCanvas();
                    // Generate initial history
                    this.generateInitialCandles(40);
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
                        let c = o + (Math.random() - 0.5) * 10;
                        let h = Math.max(o, c) + Math.random() * 5;
                        let l = Math.min(o, c) - Math.random() * 5;
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
                    
                    // Phase 1: Watching (20s)
                    this.phase = 'watching';
                    this.timer = 20;
                    
                    if(this.gameInterval) clearInterval(this.gameInterval);
                    
                    this.gameInterval = setInterval(() => {
                        // Tick timer
                        // Use a sub-interval feel for smooth animation? 
                        // Real logic: every 0.1s update timer UI, every 0.5s add a candle?
                        // Let's keep it simple: Interval 1s.
                        
                        this.tickMarket();
                        this.timer--;
                        
                        if(this.timer <= 0) {
                            this.enterDecisionPhase();
                        }
                    }, 1000); 
                },

                tickMarket() {
                     // Add a new candle to simulate 'live' movement
                    let o = this.price;
                    let c = o + (Math.random() - 0.5) * 8; // Volatility
                    let h = Math.max(o, c) + Math.random() * 2;
                    let l = Math.min(o, c) - Math.random() * 2;
                    
                    this.candles.push({o, h, l, c});
                    if(this.candles.length > 60) this.candles.shift(); // Limit history
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
                             // Timeout: No action = 10% penalty fee? Or just restart loop?
                             // Let's force a 'wait' fee to encourage play, or just restart.
                             // Simple: Just restart watching.
                             this.startCycle(); 
                        }
                    }, 1000);
                },

                placeOrder(type) {
                    if(this.phase !== 'deciding') return;
                    clearInterval(this.gameInterval);
                    
                    // Resolve immediately for snappy feel
                    // 1. Generate RESULT candle
                    let o = this.price;
                    // Determine result based on chance (50/50 for now, house edge?)
                    // Let's make it random but fair
                    let move = (Math.random() - 0.5) * 15;
                    // Force a definitive move
                    if(Math.abs(move) < 2) move = move > 0 ? 5 : -5;
                    
                    let c = o + move;
                    let h = Math.max(o, c) + Math.random() * 2;
                    let l = Math.min(o, c) - Math.random() * 2;
                    
                    // Result Candle
                    this.candles.push({o, h, l, c});
                    if(this.candles.length > 60) this.candles.shift();
                    this.price = c;
                    this.drawCandles();

                    // 2. Calc Win/Loss
                    let isGreen = c > o;
                    let won = (type === 'buy' && isGreen) || (type === 'sell' && !isGreen);
                    
                    let bet = Math.max(10, this.balance * 0.5); // Fixed 50% bet for high stakes
                    
                    if(won) {
                        this.balance += (bet * 0.5); // +50% profit
                        // Visual flare?
                    } else {
                        this.balance -= (bet * 0.5); // -50% loss
                    }
                    
                    if(this.balance < 1) this.balance = 0;
                    
                    // 3. Next Cycle
                    this.sessionLevel++;
                    setTimeout(() => {
                         this.startCycle();
                    }, 1500); // 1.5s pause to see result
                },

                fullReset() {
                    this.balance = 1000;
                    this.sessionLevel = 1;
                    this.generateInitialCandles(40);
                    this.drawCandles();
                    this.startCycle();
                },       drawCandles() {
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
                    let padding = (max - min) * 0.1;
                    min -= padding;
                    max += padding;
                    let range = max - min;
                    
                    let candleW = (w / 65) * 0.7; // Fit ~60 candles
                    let spacing = (w / 65) * 0.3;
                    
                    this.candles.forEach((c, i) => {
                        let isGreen = c.c >= c.o;
                        ctx.fillStyle = isGreen ? '#10b981' : '#f43f5e';
                        ctx.strokeStyle = isGreen ? '#10b981' : '#f43f5e';
                        
                        let x = i * (candleW + spacing) + spacing;
                        
                        // Y coords (flipped because 0 is top)
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
                        if(bodyH < 1) bodyH = 1; // min height
                        
                        ctx.fillRect(x, bodyTop, candleW, bodyH);
                    });
                    
                    // Current Price Line
                    let lastY = h - ((this.price - min) / range) * h;
                    ctx.beginPath();
                    ctx.strokeStyle = '#ffffff';
                    ctx.setLineDash([5, 5]);
                    ctx.moveTo(0, lastY);
                    ctx.lineTo(w, lastY);
                    ctx.stroke();
                    ctx.setLineDash([]);
                }
            }));
        });
    </script>
</body>

</html>