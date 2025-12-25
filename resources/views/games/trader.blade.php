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
    </style>
</head>

<body
    class="font-sans antialiased bg-slate-900 text-white selection:bg-emerald-500 selection:text-white overflow-hidden">

    <!-- Game Container (Full Screen) -->
    <div x-data="traderGame()" x-init="setTimeout(() => initGame(), 100)"
        class="relative min-h-screen flex flex-col items-center justify-center p-4">

        <!-- Back Button -->
        <a href="{{ route('homepage') }}"
            class="absolute top-6 left-6 flex items-center gap-2 text-gray-400 hover:text-white transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            <span class="font-bold tracking-wider">EXIT GAME</span>
        </a>

        <!-- Header Status -->
        <div class="w-full max-w-5xl flex justify-between items-end mb-6 z-10">
            <div>
                <h1
                    class="text-4xl font-extrabold tracking-tight mb-1 text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-cyan-400 neon-text-glow">
                    CRYPTO TRADER PANIC</h1>
                <p class="text-slate-400 font-medium">Buy Low. Sell High. Don't Panic.</p>
            </div>
            <div class="text-right">
                <div class="text-sm text-slate-400 uppercase tracking-widest font-bold mb-1">Net Worth</div>
                <div class="text-4xl font-black font-mono tracking-tight"
                    :class="netWorth >= 1000 ? 'text-emerald-400' : 'text-rose-400'">
                    $<span x-text="formatMoney(netWorth)"></span>
                </div>
            </div>
        </div>

        <!-- Main Game Board -->
        <div
            class="w-full max-w-5xl bg-slate-800/50 backdrop-blur-xl border border-slate-700/50 rounded-3xl p-1 shadow-2xl relative overflow-hidden neon-box-glow">

            <!-- Game Canvas -->
            <div class="relative h-[400px] w-full bg-slate-900 rounded-[20px] overflow-hidden">
                <!-- Grid Lines (CSS based for simplicity or Canvas) -->
                <div class="absolute inset-0 opacity-20"
                    style="background-image: linear-gradient(#334155 1px, transparent 1px), linear-gradient(90deg, #334155 1px, transparent 1px); background-size: 40px 40px;">
                </div>

                <canvas id="tradeChart" class="relative z-10 w-full h-full"></canvas>

                <!-- Live Price Indicator -->
                <div
                    class="absolute top-4 right-4 bg-slate-800/90 backdrop-blur border border-slate-600 px-4 py-2 rounded-xl z-20 flex flex-col items-end">
                    <span class="text-xs text-slate-400 font-bold uppercase">Current Price</span>
                    <div class="text-2xl font-mono font-bold flex items-center gap-2"
                        :class="lastCandle === 'up' ? 'text-emerald-400' : 'text-rose-400'">
                        <span x-text="lastCandle === 'up' ? '▲' : '▼'"></span>
                        $<span x-text="price.toFixed(2)"></span>
                    </div>
                </div>

                <!-- Timer Overlay (if game not started) -->
                <div x-show="!gameActive && !gameOver"
                    class="absolute inset-0 z-30 flex items-center justify-center bg-slate-900/80 backdrop-blur-sm transition-opacity">
                    <button @click="startGame()"
                        style="background-color: #10b981; box-shadow: 0 4px 14px 0 rgba(16, 185, 129, 0.39);"
                        class="px-12 py-5 rounded-full font-black text-2xl text-white hover:scale-105 transition-all transform flex items-center gap-3 border-2 border-emerald-400/50">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        START TRADING
                    </button>
                </div>

                <!-- Game Over Overlay -->
                <div x-show="gameOver" style="display: none;"
                    class="absolute inset-0 z-40 flex flex-col items-center justify-center bg-slate-900/90 backdrop-blur-md transition-opacity">
                    <h2 class="text-5xl font-black mb-2 text-white">MARKET CLOSED</h2>
                    <p class="text-xl text-slate-400 mb-8">Final Net Worth: <span class="text-white font-bold"
                            x-text="'$' + formatMoney(netWorth)"></span></p>

                    <div class="flex gap-4">
                        <button @click="resetGame()"
                            class="px-8 py-3 bg-white text-slate-900 rounded-full font-bold hover:bg-gray-200 transition-colors">
                            Try Again
                        </button>
                        <a href="{{ route('homepage') }}"
                            class="px-8 py-3 border border-white/20 text-white rounded-full font-bold hover:bg-white/10 transition-colors">
                            Back Home
                        </a>
                    </div>
                </div>
            </div>

            <!-- Controls -->
            <div class="grid grid-cols-12 gap-1 p-1 bg-slate-800">
                <!-- Info Panel -->
                <div class="col-span-12 md:col-span-4 bg-slate-900 rounded-2xl p-6 flex flex-col justify-center">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-slate-400 font-bold text-sm uppercase">Cash Balance</span>
                        <span class="font-mono text-xl font-bold text-white">$<span
                                x-text="formatMoney(wallet)"></span></span>
                    </div>
                    <div class="p-4 bg-slate-800 rounded-xl border border-slate-700">
                        <span class="text-slate-500 font-bold text-xs uppercase block mb-1">Asset Holdings</span>
                        <div class="flex items-baseline gap-2">
                            <span class="text-2xl font-bold text-cyan-400" x-text="shares.toFixed(4)"></span>
                            <span class="text-xs text-slate-400 font-bold">COINS</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="col-span-6 md:col-span-4 p-2">
                    <button @click="buy()" :disabled="!gameActive || wallet < price"
                        class="w-full h-full rounded-xl bg-emerald-500 hover:bg-emerald-400 disabled:opacity-50 disabled:cursor-not-allowed transition-all flex flex-col items-center justify-center group relative overflow-hidden">
                        <div
                            class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform">
                        </div>
                        <span class="text-3xl font-black text-emerald-950 uppercase relative z-10">BUY</span>
                        <span class="text-xs font-bold text-emerald-900/70 relative z-10">Long Position</span>
                    </button>
                </div>
                <div class="col-span-6 md:col-span-4 p-2">
                    <button @click="sell()" :disabled="!gameActive || shares <= 0"
                        class="w-full h-full rounded-xl bg-rose-500 hover:bg-rose-400 disabled:opacity-50 disabled:cursor-not-allowed transition-all flex flex-col items-center justify-center group relative overflow-hidden">
                        <div
                            class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform">
                        </div>
                        <span class="text-3xl font-black text-rose-950 uppercase relative z-10">SELL</span>
                        <span class="text-xs font-bold text-rose-900/70 relative z-10">Short Position</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Timer Bar -->
        <div class="w-full max-w-5xl mt-6">
            <div class="flex justify-between text-xs font-bold text-slate-500 uppercase mb-2">
                <span>Time Remaining</span>
                <span x-text="timeLeft + 's'"></span>
            </div>
            <div class="h-2 bg-slate-800 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-emerald-500 to-cyan-500 transition-all duration-1000 ease-linear"
                    :style="'width: ' + (timeLeft / 60 * 100) + '%'"></div>
            </div>
        </div>

    </div>

    <!-- Game Logic -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('traderGame', () => ({
                wallet: 1000,
                shares: 0,
                price: 100,
                history: new Array(100).fill(100), // Initial flat line
                gameActive: false,
                gameOver: false,
                timeLeft: 60,
                timerInterval: null,
                marketInterval: null,
                lastCandle: 'up',
                canvas: null,
                ctx: null,

                initGame() {
                    this.canvas = document.getElementById('tradeChart');
                    if (!this.canvas) return;

                    // Resize canvas for high DPI
                    const dpr = window.devicePixelRatio || 1;
                    const rect = this.canvas.getBoundingClientRect();
                    this.canvas.width = rect.width * dpr;
                    this.canvas.height = rect.height * dpr;
                    this.ctx = this.canvas.getContext('2d');
                    this.ctx.scale(dpr, dpr);

                    this.drawMarket();
                },

                get netWorth() {
                    return this.wallet + (this.shares * this.price);
                },

                formatMoney(val) {
                    return val.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                },

                startGame() {
                    this.resetState();
                    this.gameActive = true;
                    this.gameOver = false;

                    // Clear any existing intervals just in case
                    if (this.marketInterval) clearInterval(this.marketInterval);
                    if (this.timerInterval) clearInterval(this.timerInterval);

                    // Market Loop (Updates 10 times per second for smoothness)
                    this.marketInterval = setInterval(() => {
                        this.updateMarket();
                    }, 100);

                    // Timer Loop
                    this.timerInterval = setInterval(() => {
                        this.timeLeft--;
                        if (this.timeLeft <= 0) {
                            this.endGame();
                        }
                    }, 1000);
                },

                resetState() {
                    this.wallet = 1000;
                    this.shares = 0;
                    this.price = 100;
                    this.history = new Array(100).fill(100);
                    this.timeLeft = 60;
                },

                resetGame() {
                    this.resetState();
                    this.gameActive = false;
                    this.gameOver = false;
                    if (this.marketInterval) clearInterval(this.marketInterval);
                    if (this.timerInterval) clearInterval(this.timerInterval);
                    this.drawMarket();
                },

                endGame() {
                    this.gameActive = false;
                    this.gameOver = true;
                    clearInterval(this.timerInterval);
                    clearInterval(this.marketInterval);

                    // Force final sell to consolidate net worth into cash? 
                    // No, usually net worth is (cash + assets). We display net worth.
                },

                updateMarket() {
                    // Random Walk Logic
                    const volatility = 2.5;
                    const change = (Math.random() - 0.5) * volatility;

                    this.price += change;
                    if (this.price < 1) this.price = 1;

                    this.lastCandle = change >= 0 ? 'up' : 'down';

                    // Update History
                    this.history.push(this.price);
                    if (this.history.length > 200) this.history.shift();

                    this.drawMarket();
                },

                buy() {
                    if (this.wallet > 0) {
                        const amountToBuy = this.wallet / this.price;
                        this.shares += amountToBuy;
                        this.wallet = 0;
                    }
                },

                sell() {
                    if (this.shares > 0) {
                        const amountToSell = this.shares * this.price;
                        this.wallet += amountToSell;
                        this.shares = 0;
                    }
                },

                drawMarket() {
                    if (!this.ctx) return;
                    const ctx = this.ctx;
                    const width = this.canvas.width / getInputDevicePixelRatio();
                    const height = this.canvas.height / getInputDevicePixelRatio();

                    function getInputDevicePixelRatio() {
                        return window.devicePixelRatio || 1;
                    }

                    // Clear
                    ctx.clearRect(0, 0, width, height);

                    // Find Range
                    const min = Math.min(...this.history) * 0.95;
                    const max = Math.max(...this.history) * 1.05;
                    const range = max - min;

                    // Draw Path
                    ctx.beginPath();
                    ctx.strokeStyle = '#10b981'; // Emerald 500
                    ctx.lineWidth = 3;
                    ctx.lineJoin = 'round';

                    // Gradient Fill
                    const gradient = ctx.createLinearGradient(0, 0, 0, height);
                    gradient.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
                    gradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

                    // Plot points
                    this.history.forEach((p, i) => {
                        const x = (i / (this.history.length - 1)) * width;
                        const y = height - ((p - min) / range) * height;
                        if (i === 0) {
                            ctx.moveTo(x, y);
                        } else {
                            ctx.lineTo(x, y);
                        }
                    });

                    ctx.stroke();

                    // Close path for fill
                    ctx.lineTo(width, height);
                    ctx.lineTo(0, height);
                    ctx.fillStyle = gradient;
                    ctx.fill();

                    // Draw Cursor Dot
                    const lastPrice = this.history[this.history.length - 1];
                    const lastY = height - ((lastPrice - min) / range) * height;

                    ctx.beginPath();
                    ctx.arc(width, lastY, 6, 0, Math.PI * 2);
                    ctx.fillStyle = '#fff';
                    ctx.fill();
                    ctx.shadowColor = '#10b981';
                    ctx.shadowBlur = 15;
                    ctx.stroke();
                    ctx.shadowBlur = 0; // Reset
                }
            }));
        });
    </script>
</body>

</html>