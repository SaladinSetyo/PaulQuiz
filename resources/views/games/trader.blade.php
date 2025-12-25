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
        body { background-color: #0f172a; color: white; font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="font-sans antialiased bg-slate-900 text-white overflow-hidden selection:bg-emerald-500 selection:text-white">

    <!-- Game Container -->
    <div x-data="candleGame()" x-init="setTimeout(() => initGame(), 100)" x-cloak
        class="relative min-h-screen flex flex-col items-center justify-center p-4 bg-[#0f172a]">

        <!-- HEADER (Always Visible) -->
        <div class="absolute top-0 left-0 right-0 p-6 z-50 flex justify-between items-start pointer-events-none">
            <!-- Back Button (Pointer Events Enabled) -->
            <a href="{{ route('homepage') }}"
                class="pointer-events-auto flex items-center gap-2 text-slate-400 hover:text-white transition-colors bg-slate-800/80 backdrop-blur px-4 py-2 rounded-full border border-white/5 hover:bg-slate-700/80 shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="font-bold text-sm">EXIT</span>
            </a>

            <!-- Balance (Pointer Events Enabled) -->
            <div class="pointer-events-auto bg-slate-800/90 backdrop-blur px-6 py-2 rounded-2xl border border-slate-700/50 flex items-center gap-4 shadow-xl">
                <div class="flex flex-col items-end leading-tight">
                     <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Live Account</span>
                     <span class="text-xl font-mono font-black text-emerald-400 tracking-tight">$<span x-text="formatMoney(balance)"></span></span>
                </div>
                <div class="w-10 h-10 rounded-full bg-[#10b98120] flex items-center justify-center border border-emerald-500/30">
                    <span class="font-bold text-emerald-500 text-lg">$</span>
                </div>
            </div>
        </div>

        <!-- MAIN CONTENT AREA -->
        <div class="relative w-full max-w-[1600px] h-[85vh] bg-[#131722] rounded-[32px] overflow-hidden shadow-2xl border border-slate-800 flex flex-col justify-center items-center">
            
            <!-- BACKGROUND: CHART (Always rendered but blurred in lobby) -->
            <div class="absolute inset-0 z-0">
                 <!-- Grid Pattern -->
                 <div class="absolute inset-0 opacity-[0.03]"
                    style="background-image: linear-gradient(#fff 1px, transparent 1px), linear-gradient(90deg, #fff 1px, transparent 1px); background-size: 60px 60px;">
                </div>
                <canvas id="candleChart" class="w-full h-full cursor-crosshair"></canvas>
            </div>

            <!-- STATE: LOBBY (Start Screen) -->
            <div x-show="phase === 'idle'"
                 class="absolute inset-0 z-40 bg-[#0f172a]/80 backdrop-blur-md flex flex-col items-center justify-center transition-opacity duration-500">
                
                <div class="bg-[#1e293b] p-10 rounded-[40px] shadow-2xl border border-slate-700/50 text-center max-w-md mx-4 transform transition-all hover:scale-105 duration-300">
                    <div class="w-24 h-24 bg-emerald-500/10 rounded-full mx-auto mb-6 flex items-center justify-center border-4 border-emerald-500/20">
                        <svg class="w-12 h-12 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                    <h1 class="text-4xl font-black text-white mb-2 tracking-tight">Trader Panic</h1>
                    <p class="text-slate-400 mb-8 font-medium">Binary Options Simulator.<br>Predict the next candle color.</p>
                    
                    <button @click="startCycle()" 
                        class="w-full py-4 bg-emerald-500 hover:bg-emerald-400 text-[#0f172a] font-black text-xl rounded-2xl shadow-[0_10px_40px_-10px_rgba(16,185,129,0.5)] transition-all transform hover:-translate-y-1">
                        Start Trading
                    </button>
                </div>
            </div>

            <!-- STATE: GAME OVER -->
            <div x-show="phase === 'gameover'"
                 class="absolute inset-0 z-50 bg-[#0f172a]/95 backdrop-blur-xl flex flex-col items-center justify-center text-center"
                 style="display: none;">
                <div class="text-8xl mb-4 animate-bounce">💀</div>
                <h2 class="text-6xl font-black text-white mb-2">LIQUIDATED</h2>
                <p class="text-xl text-slate-400 mb-8 font-mono">Balance: $0.00</p>
                <div class="flex gap-4">
                    <button @click="fullReset()" class="px-8 py-4 bg-emerald-500 hover:bg-emerald-400 text-white font-bold rounded-xl shadow-lg transition-transform hover:scale-105">Re-Deposit $1,000</button>
                    <a href="{{ route('homepage') }}" class="px-8 py-4 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-xl border border-slate-700 transition-colors">Quit Game</a>
                </div>
            </div>

            <!-- HUD LAYERS (Visible only when Active) -->
            <div x-show="phase !== 'idle' && phase !== 'gameover'" class="absolute inset-0 z-10 pointer-events-none" style="display: none;">
                
                <!-- Left: Asset Info -->
                <div class="absolute top-8 left-8 pointer-events-auto">
                    <div class="bg-[#1e293b]/90 backdrop-blur-md border border-slate-700/50 p-4 rounded-2xl shadow-xl flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-[#F7931A] flex items-center justify-center shadow-inner">
                            <span class="text-white font-black text-sm">₿</span>
                        </div>
                        <div>
                            <div class="text-white font-bold leading-none">Bitcoin / USD</div>
                            <div class="text-xs text-slate-400 font-bold mt-1">Payout 82%</div>
                        </div>
                    </div>
                    
                    <div class="mt-4 bg-[#1e293b]/90 backdrop-blur-md border border-slate-700/50 p-5 rounded-2xl shadow-xl w-64">
                         <div class="text-xs text-slate-500 font-bold uppercase mb-1">Market Price</div>
                         <div class="text-3xl font-mono font-black text-white flex items-center gap-2">
                            <span x-text="price.toFixed(2)"></span>
                         </div>
                         <div class="mt-2 flex items-center gap-2 text-sm font-bold p-1 rounded bg-black/20 w-max px-2" :class="lastClose >= lastOpen ? 'text-emerald-400' : 'text-rose-400'">
                             <span x-text="lastClose >= lastOpen ? '▲' : '▼'"></span>
                             <span x-text="Math.abs((lastClose - lastOpen) / lastOpen * 100).toFixed(2) + '%'"></span>
                         </div>
                    </div>
                </div>

                <!-- Right: Control Panel -->
                <div class="absolute right-8 top-8 bottom-8 w-[360px] pointer-events-auto flex flex-col">
                    <div class="flex-grow bg-[#1e293b]/95 backdrop-blur-xl border border-slate-700/50 rounded-[30px] shadow-[0_20px_50px_rgba(0,0,0,0.5)] flex flex-col overflow-hidden relative">
                        
                        <!-- Top Gradient Line -->
                        <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-emerald-500 via-yellow-500 to-emerald-500 opacity-50"></div>

                        <!-- Phase Indicator -->
                        <div class="p-6 text-center border-b border-slate-700/30">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border"
                                :class="phase === 'watching' ? 'bg-sky-500/10 text-sky-400 border-sky-500/20' : 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20'">
                                <span class="w-2 h-2 rounded-full animate-pulse" :class="phase === 'watching' ? 'bg-sky-400' : 'bg-yellow-400'"></span>
                                <span x-text="phase === 'watching' ? 'DETECTING TREND' : 'ORDER WINDOW OPEN'"></span>
                            </div>
                        </div>

                        <!-- Main Timer -->
                        <div class="flex-grow flex flex-col items-center justify-center p-6 relative">
                             <!-- Timer Circle -->
                             <div class="relative w-48 h-48 flex items-center justify-center">
                                 <svg class="w-full h-full transform -rotate-90">
                                     <circle cx="96" cy="96" r="88" stroke="#334155" stroke-width="6" fill="none"></circle>
                                     <circle cx="96" cy="96" r="88" 
                                        :stroke="phase === 'watching' ? '#38bdf8' : '#eab308'" 
                                        stroke-width="6" fill="none"
                                        stroke-dasharray="553"
                                        :stroke-dashoffset="553 - (553 * timer / (phase === 'watching' ? 20 : 10))"
                                        stroke-linecap="round"
                                        class="transition-all duration-1000 ease-linear shadow-[0_0_20px_currentColor]"></circle>
                                 </svg>
                                 <div class="absolute inset-0 flex flex-col items-center justify-center">
                                     <span class="text-6xl font-black text-white font-mono tracking-tighter leading-none" x-text="timer"></span>
                                     <span class="text-xs font-bold text-slate-500 uppercase mt-1 tracking-widest">Seconds</span>
                                 </div>
                             </div>

                             <div class="mt-8 text-center px-4">
                                 <p x-show="phase === 'watching'" class="text-slate-400 text-sm font-medium animate-pulse">Wait for price freeze...</p>
                                 <p x-show="phase === 'deciding'" class="text-yellow-400 text-lg font-black animate-bounce">PLACE BET NOW!</p>
                             </div>
                        </div>

                        <!-- Control Buttons -->
                        <div class="p-6 bg-[#0f172a]/50 border-t border-slate-700/50">
                            <div class="grid grid-cols-2 gap-4">
                                <button @click="placeOrder('buy')" :disabled="phase !== 'deciding'"
                                    class="relative h-20 bg-emerald-600 hover:bg-emerald-500 disabled:opacity-30 disabled:cursor-not-allowed rounded-2xl shadow-lg group overflow-hidden transition-all active:scale-95">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                                    <span class="relative z-10 block text-2xl font-black text-white">UP</span>
                                    <span class="relative z-10 block text-[10px] font-bold text-emerald-200 uppercase mt-[-2px]">Green</span>
                                </button>

                                <button @click="placeOrder('sell')" :disabled="phase !== 'deciding'"
                                    class="relative h-20 bg-rose-600 hover:bg-rose-500 disabled:opacity-30 disabled:cursor-not-allowed rounded-2xl shadow-lg group overflow-hidden transition-all active:scale-95">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                                    <span class="relative z-10 block text-2xl font-black text-white">DOWN</span>
                                    <span class="relative z-10 block text-[10px] font-bold text-rose-200 uppercase mt-[-2px]">Red</span>
                                </button>
                            </div>
                            <div class="mt-4 flex justify-between items-center text-xs font-bold text-slate-500">
                                <span>Investment: $<span x-text="Math.floor(balance/2)"></span></span>
                                <span>Return: 182%</span>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Floating Result Notification (Center) -->
                <div x-show="showResult" 
                     class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50 pointer-events-none"
                     style="display: none;"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-50"
                     x-transition:enter-end="opacity-100 scale-100">
                     <div class="bg-[#1e293b] border-2 px-10 py-8 rounded-[30px] shadow-[0_0_50px_rgba(0,0,0,0.5)] text-center"
                          :class="lastWin ? 'border-emerald-500 shadow-emerald-500/20' : 'border-rose-500 shadow-rose-500/20'">
                        <div class="text-6xl mb-2" x-text="lastWin ? '🚀' : '📉'"></div>
                        <h2 class="text-4xl font-black text-white mb-1" x-text="lastWin ? 'PROFIT!' : 'LOSS'"></h2>
                        <p class="text-2xl font-mono font-bold" :class="lastWin ? 'text-emerald-400' : 'text-rose-400'" x-text="(lastWin ? '+' : '-') + '$' + lastResultAmt"></p>
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
                price: 15500.00,
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
                    this.generateInitialCandles(60);
                    this.drawCandles();
                    
                    // Live resize
                    window.addEventListener('resize', () => {
                        this.setupCanvas();
                        this.drawCandles();
                    });
                },

                setupCanvas() {
                    if(!this.canvas) return;
                    const dpr = window.devicePixelRatio || 1;
                    const rect = this.canvas.parentElement.getBoundingClientRect(); // Use parent size
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
                        let c = o + (Math.random() - 0.5) * 30;
                        let h = Math.max(o, c) + Math.random() * 10;
                        let l = Math.min(o, c) - Math.random() * 10;
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
                    let c = o + (Math.random() - 0.5) * 25; // Volatility
                    let h = Math.max(o, c) + Math.random() * 8;
                    let l = Math.min(o, c) - Math.random() * 8;
                    
                    this.candles.push({o, h, l, c});
                    if(this.candles.length > 80) this.candles.shift();
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
                    let move = (Math.random() - 0.5) * 60;
                    if(Math.abs(move) < 10) move = move > 0 ? 20 : -20;
                    
                    let c = o + move;
                    let h = Math.max(o, c) + Math.random() * 5;
                    let l = Math.min(o, c) - Math.random() * 5;
                    
                    this.candles.push({o, h, l, c});
                    if(this.candles.length > 80) this.candles.shift();
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
                    this.generateInitialCandles(60);
                    this.drawCandles();
                    this.startCycle();
                },

                drawCandles() {
                    if(!this.ctx || !this.canvas) return;
                    const ctx = this.ctx;
                    // Always use offsetWidth/Height for canvas size calc in loop
                    const dpr = window.devicePixelRatio || 1;
                    const w = this.canvas.width / dpr;
                    const h = this.canvas.height / dpr;
                    
                    ctx.clearRect(0, 0, w, h);
                    
                    let min = Infinity, max = -Infinity;
                    this.candles.forEach(c => {
                        if(c.l < min) min = c.l;
                        if(c.h > max) max = c.h;
                    });
                    let pad = (max - min) * 0.2;
                    min -= pad; max += pad;
                    let range = max - min;
                    
                    let candleW = (w / 80) * 0.6;
                    let spacing = (w / 80) * 0.4;
                    
                    // Grid
                    ctx.strokeStyle = '#334155';
                    ctx.lineWidth = 1;
                    ctx.beginPath();
                    for(let i=1; i<6; i++) {
                       let y = i * (h/6);
                       ctx.moveTo(0, y);
                       ctx.lineTo(w, y);
                    }
                    for(let i=1; i<10; i++) {
                       let x = i * (w/10);
                       ctx.moveTo(x, 0);
                       ctx.lineTo(x, h);
                    }
                    ctx.globalAlpha = 0.2;
                    ctx.stroke();
                    ctx.globalAlpha = 1.0;

                    // Candles
                    this.candles.forEach((c, i) => {
                        let isGreen = c.c >= c.o;
                        // Use CSS variable style colors
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
                    ctx.roundRect(w - 60, lastY - 11, 55, 22, 4);
                    ctx.fill();
                    
                    ctx.fillStyle = '#0f172a';
                    ctx.font = 'bold 10px Inter';
                    ctx.textAlign = 'center';
                    ctx.fillText(this.price.toFixed(2), w - 32, lastY + 4);
                }
            }));
        });
    </script>
</body>
</html>