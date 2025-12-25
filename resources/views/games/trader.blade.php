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
    </style>
</head>

<body
    class="font-sans antialiased bg-slate-900 text-white selection:bg-emerald-500 selection:text-white overflow-hidden">

    <!-- Game Container (Full Screen) -->
    <div x-data="binaryGame()" x-init="setTimeout(() => initGame(), 100)" x-cloak
        class="relative min-h-screen flex flex-col items-center justify-center p-4">

        <!-- Back Button (Static Flow) -->
        <div class="w-full max-w-5xl mb-6 z-50">
            <a href="{{ route('homepage') }}"
                class="inline-flex items-center gap-2 text-slate-400 hover:text-white transition-colors bg-slate-800/50 px-4 py-2 rounded-full border border-slate-700/50 hover:bg-slate-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                <span class="font-bold tracking-wider text-sm">EXIT GAME</span>
            </a>
        </div>

        <!-- Header -->
        <div class="w-full max-w-5xl flex justify-between items-end mb-6 z-10">
            <div>
                <h1 class="text-4xl font-extrabold tracking-tight mb-1 text-emerald-400 neon-text-glow">
                    BINARY TRADER</h1>
                <p class="text-slate-400 font-medium">Predict the market: UP or DOWN.</p>
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
            class="w-full max-w-5xl bg-slate-800/50 backdrop-blur-xl border border-slate-700/50 rounded-3xl p-1 shadow-2xl relative overflow-hidden neon-box-glow">

            <!-- Chart Area -->
            <div class="relative h-[400px] w-full bg-slate-900 rounded-[20px] overflow-hidden">
                <div class="absolute inset-0 opacity-20"
                    style="background-image: linear-gradient(#334155 1px, transparent 1px), linear-gradient(90deg, #334155 1px, transparent 1px); background-size: 40px 40px;">
                </div>

                <canvas id="tradeChart" class="relative z-10 w-full h-full"></canvas>

                <!-- Status HUD -->
                <div class="absolute top-4 left-6 z-20">
                    <div x-show="phase === 'betting'"
                        class="bg-cyan-900/80 backdrop-blur border border-cyan-500/50 px-4 py-2 rounded-xl animate-pulse">
                        <span class="text-cyan-400 font-bold uppercase text-xs tracking-wider">Phase</span>
                        <div class="text-xl font-black text-white">PLACE YOUR BET</div>
                    </div>
                    <div x-show="phase === 'trading'"
                        class="bg-slate-900/80 backdrop-blur border border-slate-500/50 px-4 py-2 rounded-xl">
                        <span class="text-slate-400 font-bold uppercase text-xs tracking-wider">Position</span>
                        <div class="text-xl font-black flex items-center gap-2"
                            :class="prediction === 'up' ? 'text-emerald-400' : 'text-rose-400'">
                            <span x-text="prediction === 'up' ? 'LONG (▲)' : 'SHORT (▼)'"></span>
                            <span class="text-sm text-white opacity-60">@ $<span
                                    x-text="entryPrice.toFixed(2)"></span></span>
                        </div>
                    </div>
                </div>

                <!-- Price HUD -->
                <div
                    class="absolute top-4 right-4 bg-slate-800/90 backdrop-blur border border-slate-600 px-4 py-2 rounded-xl z-20 flex flex-col items-end">
                    <span class="text-xs text-slate-400 font-bold uppercase">Live Price</span>
                    <div class="text-2xl font-mono font-bold flex items-center gap-2"
                        :class="lastCandle === 'up' ? 'text-emerald-400' : 'text-rose-400'">
                        <span x-text="lastCandle === 'up' ? '▲' : '▼'"></span>
                        $<span x-text="price.toFixed(2)"></span>
                    </div>
                </div>

                <!-- Start Overlay -->
                <div x-show="phase === 'idle'" style="display: none;"
                    class="absolute inset-0 z-50 flex items-center justify-center bg-slate-900 transition-opacity">
                    <div class="text-center">
                        <h2 class="text-5xl font-black mb-6 text-white tracking-tighter">READY TO TRADE?</h2>
                        <button @click="startRound()"
                            style="background-color: #10b981; box-shadow: 0 0 30px rgba(16, 185, 129, 0.4);"
                            class="px-16 py-6 rounded-full font-black text-2xl text-white hover:scale-105 transition-all transform border-4 border-emerald-900">
                            START SESSION
                        </button>
                        <p class="mt-6 text-slate-500 font-medium">Initial Balance: $1,000.00</p>
                    </div>
                </div>

                <!-- Result Overlay (Round End) -->
                <div x-show="phase === 'result'" style="display: none;"
                    class="absolute inset-0 z-40 flex flex-col items-center justify-center bg-slate-900/95 backdrop-blur-md transition-all">
                    <div class="text-center transform transition-all"
                        x-bind:class="roundWon ? 'scale-100' : 'scale-100'">
                        <div class="text-6xl mb-4" x-text="roundWon ? '🤑' : '💸'"></div>
                        <h2 class="text-5xl font-black mb-2" :class="roundWon ? 'text-emerald-400' : 'text-rose-400'"
                            x-text="roundWon ? 'PROFIT!' : 'LOSS'"></h2>
                        <p class="text-2xl text-white font-mono font-bold mb-8">
                            <span x-text="roundWon ? '+' : '-'"></span>$<span
                                x-text="formatMoney(roundWon ? betAmount * 0.8 : betAmount)"></span>
                        </p>
                        <button @click="startRound()"
                            class="px-10 py-4 bg-white text-slate-900 rounded-full font-black text-lg hover:bg-gray-200 hover:scale-105 transition-all">
                            NEXT ROUND
                        </button>
                    </div>
                </div>

                <!-- Game Over Overlay -->
                <div x-show="phase === 'gameover'" style="display: none;"
                    class="absolute inset-0 z-50 flex flex-col items-center justify-center bg-slate-900 transition-opacity">
                    <h2 class="text-6xl font-black mb-4 text-rose-500">BANKRUPT</h2>
                    <p class="text-xl text-slate-400 mb-8 max-w-md text-center">You blew the entire account. The market
                        waits for no one.</p>
                    <div class="flex gap-4">
                        <button @click="fullReset()"
                            class="px-8 py-3 bg-emerald-500 text-white rounded-full font-bold hover:bg-emerald-400 transition-colors">
                            Refill Account ($1000)
                        </button>
                        <a href="{{ route('homepage') }}"
                            class="px-8 py-3 bg-slate-800 border border-slate-700 text-white rounded-full font-bold hover:bg-slate-700 transition-colors">
                            Exit
                        </a>
                    </div>
                </div>
            </div>

            <!-- Controls Area -->
            <div class="bg-slate-800 p-2">
                <!-- Betting Phase Controls -->
                <div x-show="phase === 'betting'" class="grid grid-cols-2 gap-2 h-32">
                    <button @click="placeBet('up')"
                        class="relative group bg-emerald-500 hover:bg-emerald-400 rounded-xl transition-all flex flex-col items-center justify-center overflow-hidden">
                        <div
                            class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 mix-blend-overlay">
                        </div>
                        <span
                            class="text-3xl font-black text-emerald-950 uppercase relative z-10 flex items-center gap-2">
                            PREDICT UP <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                            </svg>
                        </span>
                        <span class="text-xs font-bold text-emerald-900/80 mt-1 relative z-10">Profit 80%</span>
                    </button>
                    <button @click="placeBet('down')"
                        class="relative group bg-rose-500 hover:bg-rose-400 rounded-xl transition-all flex flex-col items-center justify-center overflow-hidden">
                        <div
                            class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 mix-blend-overlay">
                        </div>
                        <span class="text-3xl font-black text-rose-950 uppercase relative z-10 flex items-center gap-2">
                            PREDICT DOWN <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </span>
                        <span class="text-xs font-bold text-rose-900/80 mt-1 relative z-10">Profit 80%</span>
                    </button>
                </div>

                <!-- Trading Phase Info (Locked) -->
                <div x-show="phase === 'trading'"
                    class="h-32 bg-slate-900 rounded-xl flex items-center justify-around border border-slate-700 relative overflow-hidden">
                    <div class="absolute inset-x-0 bottom-0 h-1 bg-slate-800">
                        <div class="h-full bg-blue-500 transition-all duration-1000 ease-linear"
                            :style="'width: ' + (timer / 30 * 100) + '%'"></div>
                    </div>

                    <div class="text-center">
                        <div class="text-xs text-slate-500 uppercase font-bold mb-1">Entry Price</div>
                        <div class="text-2xl font-mono font-bold text-white">$<span
                                x-text="entryPrice.toFixed(2)"></span></div>
                    </div>

                    <!-- Dynamic Status -->
                    <div class="text-center px-8 py-2 rounded-lg"
                        :class="(prediction === 'up' && price > entryPrice) || (prediction === 'down' && price < entryPrice) ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-rose-500/20 text-rose-400 border border-rose-500/30'">
                        <div class="text-xs uppercase font-bold mb-1">Status</div>
                        <div class="text-3xl font-black"
                            x-text="(prediction === 'up' && price > entryPrice) || (prediction === 'down' && price < entryPrice) ? 'WINNING' : 'LOSING'">
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="text-xs text-slate-500 uppercase font-bold mb-1">Contract</div>
                        <div class="text-2xl font-black text-white" x-text="'$' + betAmount"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timer/ProgressBar -->
        <div class="w-full max-w-5xl mt-6">
            <div class="flex justify-between items-end mb-2">
                <div class="flex flex-col">
                    <span class="text-xs font-bold text-slate-500 uppercase">Phase Timer</span>
                    <span class="text-2xl font-black text-white" x-text="timer + 's'"></span>
                </div>
                <div class="text-right flex flex-col items-end">
                    <span class="text-xs font-bold text-slate-500 uppercase">Bet Amount</span>
                    <span class="text-xl font-bold text-white" x-text="'$' + betAmount"></span>
                </div>
            </div>

            <div class="h-4 bg-slate-800 rounded-full overflow-hidden border border-slate-700">
                <!-- Betting Phase Bar -->
                <div x-show="phase === 'betting'" class="h-full bg-cyan-500 transition-all duration-1000 ease-linear"
                    :style="'width: ' + (timer / 15 * 100) + '%'"></div>
                <!-- Trading Phase Bar (Hidden handled in inner div above but kept here for consistecy if needed) -->
                <div x-show="phase === 'trading'" class="h-full bg-slate-700 w-full relative">
                    <div class="absolute inset-y-0 left-0 bg-blue-500 transition-all duration-1000 ease-linear"
                        :style="'width: ' + (timer / 30 * 100) + '%'"></div>
                </div>
            </div>
        </div>

    </div>

    <!-- Logic -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('binaryGame', () => ({
                phase: 'idle', // idle, betting, trading, result, gameover
                balance: 1000,
                price: 1540.50, // Higher starting price
                history: [],
                betAmount: 0,
                prediction: null, // 'up' or 'down'
                entryPrice: 0,
                timer: 0,
                interval: null,
                marketInterval: null,
                lastCandle: 'up',
                canvas: null,
                ctx: null,
                roundWon: false,

                initGame() {
                    this.history = new Array(150).fill(this.price);
                    this.canvas = document.getElementById('tradeChart');
                    // Hi-DPI setup
                    setTimeout(() => {
                        const dpr = window.devicePixelRatio || 1;
                        if(this.canvas) {
                            const rect = this.canvas.getBoundingClientRect();
                            this.canvas.width = rect.width * dpr;
                            this.canvas.height = rect.height * dpr;
                            this.ctx = this.canvas.getContext('2d');
                            this.ctx.scale(dpr, dpr);
                            this.draw();
                            this.startMarketSim();
                        }
                    }, 200);
                },

                formatMoney(val) {
                    return val.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                },

                // Core Loop
                startMarketSim() {
                    this.marketInterval = setInterval(() => {
                        // Random Walk
                        const move = (Math.random() - 0.5) * 3; // Volatility
                        this.price += move;
                        this.lastCandle = move >= 0 ? 'up' : 'down';
                        this.history.push(this.price);
                        if(this.history.length > 200) this.history.shift();
                        
                        this.draw();
                    }, 50); // 20FPS update
                },

                draw() {
                    if(!this.ctx) return;
                    const ctx = this.ctx;
                    const width = this.canvas.width / (window.devicePixelRatio || 1);
                    const height = this.canvas.height / (window.devicePixelRatio || 1);

                    ctx.clearRect(0,0,width,height);

                    // Calc Scaling
                    const min = Math.min(...this.history) * 0.9995;
                    const max = Math.max(...this.history) * 1.0005;
                    const range = max - min;

                    // Draw Entry Line (if trading)
                    if(this.phase === 'trading') {
                        const yEntry = height - ((this.entryPrice - min) / range) * height;
                        ctx.beginPath();
                        ctx.strokeStyle = '#94a3b8'; // Slate 400
                        ctx.lineWidth = 1;
                        ctx.setLineDash([5, 5]);
                        ctx.moveTo(0, yEntry);
                        ctx.lineTo(width, yEntry);
                        ctx.stroke();
                        ctx.setLineDash([]);
                    }

                    // Draw Line
                    ctx.beginPath();
                    ctx.strokeStyle = '#10b981';
                    ctx.lineWidth = 3;
                    ctx.lineJoin = 'round';
                    
                    this.history.forEach((p, i) => {
                        const x = (i / (this.history.length - 1)) * width;
                        const y = height - ((p - min) / range) * height;
                        if(i===0) ctx.moveTo(x,y);
                        else ctx.lineTo(x,y);
                    });
                    ctx.stroke();

                    // Fill Gradient
                    ctx.lineTo(width, height);
                    ctx.lineTo(0, height);
                    const grad = ctx.createLinearGradient(0,0,0,height);
                    grad.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
                    grad.addColorStop(1, 'rgba(16, 185, 129, 0)');
                    ctx.fillStyle = grad;
                    ctx.fill();

                    // Cursor glow
                    const lastP = this.history[this.history.length-1];
                    const lastY = height - ((lastP - min) / range) * height;
                    ctx.beginPath();
                    ctx.arc(width, lastY, 5, 0, Math.PI*2);
                    ctx.fillStyle = '#fff';
                    ctx.fill();
                    ctx.shadowColor = '#10b981';
                    ctx.shadowBlur = 15;
                    ctx.stroke();
                    ctx.shadowBlur = 0;
                },

                startRound() {
                    if(this.balance <= 0) {
                        this.phase = 'gameover';
                        return;
                    }
                    this.phase = 'betting';
                    this.timer = 15;
                    // Auto-calc bet (20% of balance, min $10)
                    this.betAmount = Math.max(10, Math.floor(this.balance * 0.2));
                    
                    if(this.interval) clearInterval(this.interval);
                    this.interval = setInterval(() => {
                        this.timer--;
                        if(this.timer <= 0) {
                            // Timeout - restart betting to induce panic/urgency
                            this.timer = 15; 
                        }
                    }, 1000);
                },

                placeBet(dir) {
                    this.prediction = dir;
                    this.entryPrice = this.price;
                    this.phase = 'trading';
                    this.timer = 30; // 30s outcome
                    
                    if(this.interval) clearInterval(this.interval);
                    this.interval = setInterval(() => {
                        this.timer--;
                        if(this.timer <= 0) {
                            this.resolveRound();
                        }
                    }, 1000);
                },

                resolveRound() {
                    clearInterval(this.interval);
                    
                    const winUp = (this.prediction === 'up' && this.price > this.entryPrice);
                    const winDown = (this.prediction === 'down' && this.price < this.entryPrice);
                    
                    this.roundWon = winUp || winDown;
                    
                    if(this.roundWon) {
                        // Win: profit 80%
                        this.balance += (this.betAmount * 0.8);
                    } else {
             // Lose: lose bet
                        this.balance -= this.betAmount;
                    }

                    if(this.balance <= 1) this.balance = 0; // cleanup decimals

                    this.phase = 'result';
                    
                    if(this.balance <= 0) {
                        setTimeout(() => this.phase = 'gameover', 3000);
                    }
                },

                fullReset() {
                    this.balance = 1000;
                    this.startRound();
                }

            }));
        });
    </script>
</body>

</html>