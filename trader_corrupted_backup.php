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
        [x-cloak] {
            display: none !important;
        }

        body {
            background-color: #0b0e11;
            color: #eaecef;
            font-family: 'Inter', sans-serif;
            overflow: hidden;
        }

        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }

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

        .crs-crosshair {
            cursor: crosshair;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* ===== SMOOTH ANIMATION SYSTEM ===== */
        /* Base smooth transitions */
        .smooth-transition {
            transition: all 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);
        }

        .smooth-transition-fast {
            transition: all 0.15s cubic-bezier(0.4, 0.0, 0.2, 1);
        }

        .smooth-color {
            transition: color 0.2s ease-in-out, background-color 0.2s ease-in-out;
        }

        .smooth-transform {
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .smooth-opacity {
            transition: opacity 0.2s ease-in-out;
        }

        /* Price Flash Animations (Pintu Pro Style) */
        @keyframes flashUp {
            0% {
                background-color: rgba(14, 203, 129, 0);
            }

            30% {
                background-color: rgba(14, 203, 129, 0.25);
            }

            100% {
                background-color: rgba(14, 203, 129, 0);
            }
        }

        @keyframes flashDown {
            0% {
                background-color: rgba(246, 70, 93, 0);
            }

            30% {
                background-color: rgba(246, 70, 93, 0.25);
            }

            100% {
                background-color: rgba(246, 70, 93, 0);
            }
        }

        .price-flash-up {
            animation: flashUp 0.6s ease-out;
        }

        .price-flash-down {
            animation: flashDown 0.6s ease-out;
        }

        /* Smooth number transitions */
        .number-update {
            transition: transform 0.2s ease-out;
        }

        .number-update:active {
            transform: scale(1.05);
        }

        /* Smooth hover effects */
        .hover-lift {
            transition: transform 0.2s ease-out, box-shadow 0.2s ease-out;
        }

        .hover-lift:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        /* Fade in animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        /* Slide in from right */
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .slide-in-right {
            animation: slideInRight 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);
        }

        /* Custom scrollbar smooth */
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #2b3139 #14161b;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #14161b;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #2b3139;
            border-radius: 3px;
            transition: background 0.2s ease;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #3b4149;
        }
    </style>
</head>

<body class="flex flex-col h-screen bg-[#0b0e11] text-[#eaecef] select-none text-xs overflow-hidden"
    x-data="proTrader()" x-cloak>

    <!-- HEADER -->
    <header
        class="h-12 bg-[#161a1e]/90 backdrop-blur-md border-b border-[#2b3139] flex items-center px-4 shrink-0 justify-between z-50 sticky top-0 shadow-lg shadow-black/20">
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2">
                <div
                    class="w-6 h-6 rounded bg-[#f0b90b] text-[#0b0e11] font-black flex items-center justify-center text-[11px]">
                    C</div>
                <span class="font-bold text-sm text-white">BTCUSDT</span>
                <span
                    class="text-[10px] bg-[#2b3139] px-2 py-0.5 rounded text-emerald-400 font-bold border border-emerald-500/20">Perp</span>
            </div>

            <div class="hidden md:flex items-center gap-6 border-l border-[#2b3139] pl-6 h-full">
                <div class="flex flex-col justify-center h-full">
                    <span class="text-[12px] font-bold smooth-color"
                        :class="lastPrice >= prevPrice ? 'text-emerald-400' : 'text-rose-400'"
                        x-text="lastPrice?.toFixed(2) ?? '---'"></span>
                    <span class="text-[10px] text-slate-500 font-medium">Mark Price</span>
                </div>
                <div class="flex flex-col justify-center h-full">
                    <span class="text-[12px] text-slate-300">67,450.00</span>
                    <span class="text-[10px] text-slate-500 font-medium">24h High</span>
                </div>
                <div class="flex flex-col justify-center h-full">
                    <span class="text-[12px] text-slate-300">98.2M</span>
                    <span class="text-[10px] text-slate-500 font-medium">24h Vol</span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2 bg-[#2b3139] px-3 py-1.5 rounded-md">
                <span class="w-2 h-2 rounded-full"
                    :class="phase === 'open' ? 'bg-emerald-500 animate-pulse' : 'bg-amber-500'"></span>
                <span class="font-bold text-white uppercase text-[10px] tracking-wide"
                    x-text="phase === 'open' ? 'TRADING' : 'LOCKED'"></span>
                <span class="font-mono font-bold text-[#f0b90b] text-sm ml-2" x-text="formatTimer() ?? '00:00'"></span>
            </div>

            <!-- ENHANCED BALANCE DISPLAY -->
            <div class="flex items-center gap-2 px-3 py-1.5 bg-[#2b3139] rounded-lg">
                <svg class="w-4 h-4 text-[#f0b90b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                    </path>
                </svg>
                <span class="text-[11px] font-bold text-white" x-data="{ currentBalance: window.userBalance || 1000 }"
                    @balance-update.window="currentBalance = $event.detail"
                    x-text="'$' + (currentBalance.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}))">
                    $1,000.00
                </span>
                <!-- Reset Button -->
                <button @click="resetProgress()"
                    class="ml-2 w-6 h-6 flex items-center justify-center rounded hover:bg-[#3b4149] text-slate-400 hover:text-white transition-colors"
                    title="Reset Progress">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Leaderboard Button -->
            <button @click="toggleLeaderboard()"
                class="w-8 h-8 flex items-center justify-center rounded hover:bg-[#2b3139] text-[#f0b90b] hover:text-white transition-colors"
                title="Leaderboard">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                    </path>
                </svg>
            </button>

            <a href="{{ route('homepage') }}"
                class="flex items-center justify-center w-8 h-8 rounded hover:bg-[#2b3139] text-slate-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                    </path>
                </svg>
            </a>
        </div>
    </header>

    <!-- MAIN GRID LAYOUT -->
    <div class="flex-1 h-full min-h-0 flex flex-col lg:grid lg:grid-cols-[1fr_280px_300px] bg-[#0b0e11] relative">
        <!-- Neon Glow Effect behind Chart -->
        <div
            class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-emerald-500/50 to-transparent opacity-50 pointer-events-none">
        </div>

        <!-- Mobile Tabs (Only on Mobile) -->
        <div class="lg:hidden flex border-b border-[#2b3139] bg-[#14161b]">
            <button @click="window.mobileTab = 'chart'"
                :class="window.mobileTab === 'chart' ? 'border-b-2 border-[#f0b90b] text-white' : 'text-slate-500'"
                class="flex-1 py-3 text-sm font-bold smooth-transition-fast">
                📈 Chart
            </button>
            <button @click="window.mobileTab = 'trade'"
                :class="window.mobileTab === 'trade' ? 'border-b-2 border-[#f0b90b] text-white' : 'text-slate-500'"
                class="flex-1 py-3 text-sm font-bold smooth-transition-fast">
                💰 Trade
            </button>
            <button @click="window.mobileTab = 'positions'"
                :class="window.mobileTab === 'positions' ? 'border-b-2 border-[#f0b90b] text-white' : 'text-slate-500'"
                class="flex-1 py-3 text-sm font-bold smooth-transition-fast">
                📊 Position
            </button>
        </div>
        <!-- COL 1: CHART (Main) -->
        <div class="flex flex-col h-full min-w-0 min-h-[400px] lg:min-h-0 bg-[#0b0e11] relative border-r border-[#2b3139] overflow-hidden"
            style="min-height: 0;">
            <!-- Chart Toolbar -->
            <div
                class="h-9 border-b border-[#2b3139] flex items-center px-4 gap-4 text-[11px] font-bold text-slate-500 bg-[#0b0e11] shrink-0">
                <span class="text-white hover:bg-[#2b3139] px-2 py-0.5 rounded cursor-pointer">Time</span>
                <span class="text-[#f0b90b] bg-[#2b3139] px-2 py-0.5 rounded cursor-pointer">1s</span>
                <span
                    class="hover:bg-[#2b3139] px-2 py-0.5 rounded cursor-pointer text-slate-500 hover:text-white transition-colors">15m</span>
                <span class="hover:bg-[#2b3139] px-2 py-0.5 rounded cursor-pointer">1H</span>
                <span class="hover:bg-[#2b3139] px-2 py-0.5 rounded cursor-pointer">4H</span>
                <div class="w-[1px] h-3 bg-[#2b3139]"></div>
                <span class="hover:text-white cursor-pointer">Indicators</span>
                <div class="flex-grow"></div>
                <span class="text-emerald-500 text-[10px] flex items-center gap-1">● Live</span>
            </div>

            <div id="chartContainer" class="flex-1 relative w-full bg-[#0b0e11] crs-crosshair min-h-0">
                <canvas x-ref="tradeCanvas" id="tradeCanvas" class="absolute inset-0 w-full h-full block"></canvas>

                <!-- Crosshair Label -->
                <div x-show="crosshair.visible"
                    class="absolute bg-[#1e2329] text-white text-[10px] font-mono px-1.5 py-1 rounded pointer-events-none z-30 border border-[#474d57] shadow-lg"
                    :style="`left: ${crosshair.x + 15}px; top: ${crosshair.y - 12}px;`">
                    <span x-text="crosshair.price"></span>
                </div>
            </div>

            <!-- Bottom Tabs: Positions & History -->
            <div class="border-t border-[#2b3139] bg-[#0b0e11] flex flex-col shrink-0"
                style="height: min(300px, calc(100vh - 500px)); min-height: 180px;" x-data="{ activeTab: 'positions' }">
                <div class="h-9 flex items-center px-4 gap-6 border-b border-[#2b3139] bg-[#14161b]">
                    <span @click="activeTab = 'positions'"
                        :class="activeTab === 'positions' ? 'text-[#f0b90b] border-b-2 border-[#f0b90b]' : 'text-slate-500 hover:text-white'"
                        class="text-[11px] font-bold h-full flex items-center px-1 cursor-pointer smooth-transition-fast">Positions
                        <span x-text="myPosition ? '(1)' : '(0)'"></span></span>
                    <span @click="activeTab = 'history'"
                        :class="activeTab === 'history' ? 'text-[#f0b90b] border-b-2 border-[#f0b90b]' : 'text-slate-500 hover:text-white'"
                        class="text-[11px] font-bold h-full flex items-center px-1 cursor-pointer smooth-transition-fast">Trade
                        History
                        <span x-text="'(' + tradeHistory.length + ')'"></span></span>
                </div>

                <div class="flex-grow overflow-auto custom-scrollbar relative bg-[#0b0e11]">
                    <!-- Positions Tab -->
                    <div x-show="activeTab === 'positions'">
                        <table class="w-full text-left font-mono text-[11px]" x-show="myPosition">
                            <thead class="text-slate-500 bg-[#0b0e11] sticky top-0 h-8">
                                <tr class="border-b border-[#2b3139]">
                                    <th class="px-4 font-normal">Symbol</th>
                                    <th class="px-4 font-normal">Side</th>
                                    <th class="px-4 font-normal text-right">Size</th>
                                    <th class="px-4 font-normal text-right">Entry Price</th>
                                    <th class="px-4 font-normal text-right">Mark Price</th>
                                    <th class="px-4 font-normal text-right">PnL (ROE%)</th>
                                    <th class="px-4 font-normal text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-white">
                                <tr
                                    class="bg-[#1e2329]/20 border-b border-[#2b3139] hover:bg-[#1e2329]/40 transition-colors group">
                                    <td class="px-4 py-2 font-bold text-[#f0b90b]">BTCUSDT Perp</td>
                                    <td class="px-4 py-2 font-bold"
                                        :class="myPosition?.type==='buy' ? 'text-emerald-400' : 'text-rose-400'"
                                        x-text="myPosition?.type==='buy'?'Long':'Short'"></td>
                                    <td class="px-4 py-2 text-right" x-text="myPosition?.amount"></td>
                                    <td class="px-4 py-2 text-right text-slate-300"
                                        x-text="myPosition?.entry.toFixed(2)">
                                    </td>
                                    <td class="px-4 py-2 text-right text-slate-300" x-text="lastPrice.toFixed(2)"></td>
                                    <td class="px-4 py-2 font-bold text-right"
                                        :class="(lastPrice - myPosition?.entry)*(myPosition?.type==='buy'?1:-1) > 0 ? 'text-emerald-400' : 'text-rose-400'">
                                        <span
                                            x-text="((lastPrice - myPosition?.entry)*(myPosition?.type==='buy'?1:-1)).toFixed(2)"></span>
                                        USDT
                                    </td>
                                    <td class="px-4 py-2 text-right">
                                        <button @click="settle()" x-show="myPosition"
                                            class="bg-[#2b3139] hover:bg-[#474d57] text-white px-2 py-1 rounded text-[10px] font-bold transition-colors">Close</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div x-show="!myPosition"
                            class="absolute inset-0 flex flex-col items-center justify-center text-slate-600 gap-2">
                            <svg class="w-12 h-12 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                </path>
                            </svg>
                            <span class="text-xs font-bold opacity-50">No Active Positions</span>
                        </div>
                    </div>

                    <!-- Trade History Tab -->
                    <div x-show="activeTab === 'history'">
                        <table class="w-full text-left font-mono text-[10px]" x-show="tradeHistory.length > 0">
                            <thead class="text-slate-500 bg-[#0b0e11] sticky top-0 h-8">
                                <tr class="border-b border-[#2b3139]">
                                    <th class="px-4 font-normal">Time</th>
                                    <th class="px-4 font-normal">Side</th>
                                    <th class="px-4 font-normal text-right">Entry</th>
                                    <th class="px-4 font-normal text-right">Exit</th>
                                    <th class="px-4 font-normal text-right">P&L</th>
                                    <th class="px-4 font-normal text-right">ROE%</th>
                                </tr>
                            </thead>
                            <tbody class="text-white">
                                <template x-for="trade in tradeHistory" :key="trade.timestamp">
                                    <tr class="border-b border-[#2b3139] hover:bg-[#1e2329]/20 transition-colors">
                                        <td class="px-4 py-2 text-slate-400"
                                            x-text="new Date(trade.timestamp).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit', second: '2-digit'})">
                                        </td>
                                        <td class="px-4 py-2 font-bold"
                                            :class="trade.side === 'buy' ? 'text-emerald-400' : 'text-rose-400'"
                                            x-text="trade.side === 'buy' ? 'Long' : 'Short'"></td>
                                        <td class="px-4 py-2 text-right text-slate-300" x-text="trade.entry.toFixed(2)">
                                        </td>
                                        <td class="px-4 py-2 text-right text-slate-300" x-text="trade.exit.toFixed(2)">
                                        </td>
                                        <td class="px-4 py-2 text-right font-bold"
                                            :class="trade.pnl >= 0 ? 'text-emerald-400' : 'text-rose-400'"
                                            x-text="(trade.pnl >= 0 ? '+' : '') + trade.pnl.toFixed(2)"></td>
                                        <td class="px-4 py-2 text-right font-bold"
                                            :class="trade.pnl >= 0 ? 'text-emerald-400' : 'text-rose-400'"
                                            x-text="(trade.pnl >= 0 ? '+' : '') + trade.roe + '%'"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>

                        <div x-show="tradeHistory.length === 0"
                            class="absolute inset-0 flex flex-col items-center justify-center text-slate-600 gap-2">
                            <svg class="w-12 h-12 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            <span class="text-xs font-bold opacity-50">Belum Ada Trade History</span>
                            <span class="text-[10px] opacity-30">Mulai trading untuk melihat riwayat</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- COL 2: ORDER BOOK (Fixed 280px on Desktop) -->
        <div
            class="flex flex-col border-r border-[#2b3139] bg-[#14161b] min-w-0 h-[300px] lg:h-full order-3 lg:order-none border-t lg:border-t-0 border-[#2b3139] overflow-hidden">
            <div class="h-9 flex items-center px-3 border-b border-[#2b3139] bg-[#181a20]">
                <span class="text-[11px] font-bold text-white">Order Book</span>
            </div>

            <div class="px-3 py-1.5 flex justify-between text-[10px] font-bold text-slate-500 bg-[#14161b]">
                <span>Price</span>
                <span>Amount</span>
                <span>Total</span>
            </div>

            <div class="flex-1 h-full flex flex-col overflow-y-auto font-mono text-[10px] relative pb-10"
                style="scrollbar-width: thin; scrollbar-color: #2b3139 #14161b;">
                <!-- Sells -->
                <div class="flex-1 overflow-hidden flex flex-col-reverse justify-start">
                    <template x-for="ask in asks" :key="ask.id">
                        <div
                            class="flex justify-between px-3 py-[1px] relative hover:bg-[#2b3139] cursor-pointer group h-[18px] items-center bg-gradient-to-l from-transparent via-[#f6465d]/5 to-transparent">
                            <span class="text-[#f6465d] group-hover:text-white z-10"
                                x-text="ask.price.toFixed(2)"></span>
                            <span class="text-slate-400 z-10" x-text="ask.amount.toFixed(3)"></span>
                            <span class="text-slate-600 z-10"
                                x-text="(ask.price * ask.amount/1000).toFixed(0)+'k'"></span>
                            <div class="absolute right-0 top-0 bottom-0 bg-[#f6465d]/10 transition-all"
                                :style="'width: '+ (ask.amount*30) +'%'"></div>
                        </div>
                    </template>
                </div>

                <!-- Ticker & Spread -->
                <div class="flex flex-col justify-center border-y border-[#2b3139] bg-[#0b0e11] shrink-0 my-1 py-1">
                    <div class="flex items-center justify-center gap-2">
                        <span class="text-lg font-bold tracking-tight"
                            :class="lastPrice >= prevPrice ? 'text-[#0ecb81]' : 'text-[#f6465d]'"
                            x-text="lastPrice.toFixed(2)"></span>
                        <svg class="w-3 h-3 transition-transform duration-300"
                            :class="lastPrice >= prevPrice ? 'rotate-180 text-[#0ecb81]' : 'text-[#f6465d]'"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5 10l5 5 5-5H5z" />
                        </svg>
                    </div>
                    <div class="flex justify-center items-center gap-2 text-[10px]">
                        <span class="text-slate-500 font-bold">$65,102.50</span> <!-- Mark Price -->
                        <span class="text-slate-600 text-[9px]">Spread <span
                                x-text="spreadPercent + '%'">0.01%</span></span>
                    </div>
                </div>

                <!-- Buys -->
                <div class="flex-1 overflow-hidden">
                    <template x-for="bid in bids" :key="bid.id">
                        <div
                            class="flex justify-between px-3 py-[1px] relative hover:bg-[#2b3139] cursor-pointer group h-[18px] items-center bg-gradient-to-l from-transparent via-[#0ecb81]/5 to-transparent">
                            <span class="text-[#0ecb81] group-hover:text-white z-10"
                                x-text="bid.price.toFixed(2)"></span>
                            <span class="text-slate-400 z-10" x-text="bid.amount.toFixed(3)"></span>
                            <span class="text-slate-600 z-10"
                                x-text="(bid.price * bid.amount/1000).toFixed(0)+'k'"></span>
                            <div class="absolute right-0 top-0 bottom-0 bg-[#0ecb81]/10 transition-all"
                                :style="'width: '+ (bid.amount*30) +'%'"></div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- COL 3: TRADE FORM (Fixed 300px) -->
        <div class="h-full flex flex-col bg-[#1e2329] min-w-0 border-l border-[#2b3139] overflow-hidden">
            <!-- Tabs -->
            <div class="flex bg-[#181a20] text-[11px] font-bold border-b border-[#2b3139] shrink-0">
                <button @click="activeLeverage = 'spot'"
                    :class="activeLeverage === 'spot' ? 'text-[#f0b90b] border-t-2 border-[#f0b90b] bg-[#1e2329]' : 'text-slate-500 hover:text-white'"
                    class="flex-1 py-3 transition-colors">Spot</button>
                <button @click="activeLeverage = 'cross 3x'"
                    :class="activeLeverage === 'cross 3x' ? 'text-[#f0b90b] border-t-2 border-[#f0b90b] bg-[#1e2329]' : 'text-slate-500 hover:text-white'"
                    class="flex-1 py-3 transition-colors">Cross 3x</button>
                <button @click="activeLeverage = 'iso 10x'"
                    :class="activeLeverage === 'iso 10x' ? 'text-[#f0b90b] border-t-2 border-[#f0b90b] bg-[#1e2329]' : 'text-slate-500 hover:text-white'"
                    class="flex-1 py-3 transition-colors">Iso 10x</button>
            </div>

            <div class="p-4 flex flex-col gap-5 overflow-y-auto">
                <div class="flex bg-[#2b3139] rounded p-[2px] shrink-0">
                    <button
                        class="flex-1 py-1.5 rounded text-[10px] font-bold bg-[#474d57] text-white shadow-sm">Limit</button>
                    <button
                        class="flex-1 py-1.5 rounded text-[10px] font-bold text-slate-400 hover:text-white transition-colors">Market</button>
                    <button
                        class="flex-1 py-1.5 rounded text-[10px] font-bold text-slate-400 hover:text-white transition-colors">Stop</button>
                </div>

                <div class="space-y-4">
                    <!-- Price Input -->
                    <div
                        class="flex items-center bg-[#2b3139] rounded border border-[#2b3139] h-10 hover:border-[#f0b90b] transition-colors group">
                        <span
                            class="pl-3 text-[11px] font-bold text-slate-400 w-16 group-hover:text-slate-300">Price</span>
                        <input type="text" disabled placeholder="Market Price"
                            class="flex-grow bg-transparent text-right pr-3 text-xs font-bold text-white outline-none cursor-not-allowed opacity-70">
                        <span class="pr-3 text-[11px] font-bold text-slate-500">USDT</span>
                    </div>

                    <!-- Amount Input -->
                    <div
                        class="flex items-center bg-[#2b3139] rounded border border-[#2b3139] h-10 hover:border-[#f0b90b] transition-colors group focus-within:border-[#f0b90b]">
                        <span
                            class="pl-3 text-[11px] font-bold text-slate-400 w-16 group-hover:text-slate-300 group-focus-within:text-[#f0b90b]">Amount</span>
                        <input type="number" x-model.number="betAmount"
                            class="flex-grow bg-transparent text-right pr-3 text-sm font-bold text-white outline-none font-mono">
                        <span class="pr-3 text-[11px] font-bold text-slate-500">USDT</span>
                    </div>

                    <!-- Slider -->
                    <div class="py-2">
                        <input type="range" min="10" max="1000" step="10" x-model.number="betAmount"
                            class="w-full h-1 bg-slate-600 rounded-lg appearance-none cursor-pointer accent-[#f0b90b]">
                        <div class="flex justify-between mt-2 px-1">
                            <div class="w-1.5 h-1.5 rounded-full bg-slate-600"></div>
                            <div class="w-1.5 h-1.5 rounded-full bg-slate-600"></div>
                            <div class="w-1.5 h-1.5 rounded-full bg-slate-600"></div>
                            <div class="w-1.5 h-1.5 rounded-full bg-slate-600"></div>
                            <div class="w-1.5 h-1.5 rounded-full bg-slate-600"></div>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="bg-[#14161b] p-3 rounded border border-[#2b3139] space-y-1">
                        <div class="flex justify-between text-[10px] items-center">
                            <span class="text-slate-500 font-bold">Est. Cost</span>
                            <span class="text-white font-mono font-bold" x-text="betAmount + ' USDT'"></span>
                        </div>
                        <div class="flex justify-between text-[10px] items-center">
                            <span class="text-slate-500 font-bold">Est. ROI</span>
                            <span class="text-emerald-400 font-mono font-bold">+82%</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-2 gap-3 mt-auto mb-4">
                    <button @click="placeOrder('buy')" :disabled="window.game?.phase!=='open' || myPosition"
                        class="h-11 rounded bg-[#0ecb81] hover:bg-[#0da86b] disabled:opacity-50 disabled:cursor-not-allowed text-white text-[13px] font-bold shadow-lg shadow-[#0ecb81]/20 transition-all active:scale-[0.98] border border-[#0ecb81]/50">
                        Buy / Long
                    </button>
                    <button @click="placeOrder('sell')" :disabled="window.game?.phase!=='open' || myPosition"
                        class="h-11 rounded bg-[#f6465d] hover:bg-[#d93a4e] disabled:opacity-50 disabled:cursor-not-allowed text-white text-[13px] font-bold shadow-lg shadow-[#f6465d]/20 transition-all active:scale-[0.98] border border-[#f6465d]/50">
                        Sell / Short
                    </button>
                </div>
            </div>
        </div>

        <!-- RESULT POPUP -->
        <div x-show="showResult" style="display: none;"
            class="absolute inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-[2px] pointer-events-none"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100">

            <div
                class="bg-[#1e2329] p-8 rounded-2xl border border-[#474d57] text-center shadow-2xl min-w-[320px] transform hover:scale-105 transition-transform duration-300">
                <div class="text-6xl mb-4" x-text="lastWin ? '💰' : '💸'"></div>
                <h2 class="text-3xl font-black text-white mb-1 uppercase tracking-tighter"
                    x-text="lastWin ? 'Take Profit' : 'Stop Loss'"></h2>
                <div class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-6">Trade Result</div>

                <div class="bg-[#0b0e11] p-4 rounded-xl border border-[#2b3139]">
                    <div class="flex justify-between text-[11px] font-bold text-slate-500 mb-1">
                        <span>Total PnL</span>
                        <span>Date</span>
                    </div>
                    <div class="flex justify-between items-end">
                        <div class="text-3xl font-mono font-bold tracking-tight"
                            :class="lastWin ? 'text-[#0ecb81]' : 'text-[#f6465d]'"
                            x-text="(lastWin ? '+' : '') + '$' + Math.abs(lastPnL).toFixed(2)"></div>
                        <div class="text-[10px] text-slate-500 font-mono" x-text="new Date().toLocaleTimeString()">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- GAME OVER MODAL -->
        <div x-show="showGameOverModal" style="display: none;"
            class="fixed inset-0 z-[998] flex items-center justify-center"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100">

            <!-- 100% SOLID BLACK OVERLAY -->
            <div class="absolute inset-0 bg-black"></div>

            <!-- 100% SOLID MODAL BOX -->
            <div
                class="relative bg-[#1e2329] p-8 rounded-2xl border-2 border-[#f6465d] text-center shadow-2xl max-w-md mx-4 z-10">
                <div class="text-7xl mb-4">💀</div>
                <h2 class="text-4xl font-black text-[#f6465d] mb-2 uppercase tracking-tighter">GAME OVER</h2>
                <p class="text-slate-400 mb-6">Balance habis! Survival streak kamu berakhir.</p>

                <!-- Stats Grid -->
                <div class="bg-[#0b0e11] p-4 rounded-xl border border-[#2b3139] mb-6 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500 font-bold">Total Trades:</span>
                        <span class="text-white font-mono" x-text="totalTrades"></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500 font-bold">Win Rate:</span>
                        <span class="text-white font-mono"
                            x-text="totalTrades > 0 ? ((winningTrades/totalTrades)*100).toFixed(1) + '%' : '0%'"></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500 font-bold">Best Streak:</span>
                        <span class="text-emerald-400 font-mono font-bold" x-text="bestStreak + ' trades'"></span>
                    </div>
                    <div class="flex justify-between text-sm border-t border-[#2b3139] pt-2">
                        <span class="text-slate-500 font-bold">Total P&L:</span>
                        <span class="font-mono font-bold text-lg"
                            :class="totalProfit >= 0 ? 'text-emerald-400' : 'text-[#f6465d]'"
                            x-text="(totalProfit >= 0 ? '+' : '') + '$' + totalProfit.toFixed(2)"></span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <button @click="restartGame()"
                        class="flex-1 bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-3 rounded-lg font-bold text-sm transition-all active:scale-95">
                        🔄 Main Lagi
                    </button>
                    <a href="{{ route('homepage') }}"
                        class="flex-1 bg-[#2b3139] hover:bg-[#474d57] text-white px-6 py-3 rounded-lg font-bold text-sm transition-all active:scale-95 flex items-center justify-center">
                        🏠 Home
                    </a>
                </div>
            </div>
        </div>

        <!-- TUTORIAL MODAL -->
        <div x-show="showTutorial" style="display: none;"
            class="fixed inset-0 z-[999] flex items-center justify-center p-4" @click.self="showTutorial = false">

            <!-- 100% SOLID BLACK OVERLAY -->
            <div class="absolute inset-0 bg-black"></div>

            <!-- 100% SOLID MODAL BOX -->
            <div
                class="relative bg-[#1e2329] rounded-2xl border border-[#474d57] shadow-[0_20px_60px_0_rgba(0,0,0,0.9)] max-w-2xl w-full max-h-[90vh] overflow-hidden z-10">
                <!-- Tutorial Header SOLID -->
                <div class="bg-[#181a20] px-6 py-4 border-b border-[#2b3139] flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold text-white">📚 Crypto Trading Panic Tutorial</h3>
                        <p class="text-xs text-slate-500 mt-1">Step <span x-text="tutorialStep + 1"></span> of 6</p>
                    </div>
                    <button @click="showTutorial = false; tutorialCompleted = true; saveGame();"
                        class="text-slate-500 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Progress Bar -->
                <div class="bg-[#0b0e11]/80 h-1.5">
                    <div class="bg-gradient-to-r from-[#f0b90b] to-[#f8d12f] h-full transition-all duration-300 shadow-[0_0_10px_rgba(240,185,11,0.5)]"
                        :style="`width: ${((tutorialStep + 1) / 6) * 100}%`"></div>
                </div>

                <!-- Scrollable Content -->
                <div class="overflow-y-auto max-h-[calc(90vh-180px)] custom-scrollbar">
                    <div class="p-6">
                        <!-- Step 0: Welcome -->
                        <div x-show="tutorialStep === 0" class="space-y-4">
                            <div class="text-center mb-6">
                                <div class="text-6xl mb-4">🎮</div>
                                <h4 class="text-2xl font-bold text-white mb-2">Selamat Datang!</h4>
                                <p class="text-slate-400">Di Crypto Trading Panic - Game survival trading yang edukatif
                                </p>
                            </div>

                            <div class="bg-[#0b0e11] p-4 rounded-lg border border-[#2b3139] space-y-3">
                                <div class="flex items-start gap-3">
                                    <span class="text-2xl">⏱️</span>
                                    <div>
                                        <p class="font-bold text-white">Mekanik Game:</p>
                                        <ul class="text-sm text-slate-400 mt-1 space-y-1">
                                            <li>• 20 detik untuk trading (Open Market)</li>
                                            <li>• 10 detik untuk settlement (Locked)</li>
                                            <li>• Balance $0 = <span class="text-[#f6465d] font-bold">GAME OVER</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <span class="text-2xl">🎯</span>
                                    <div>
                                        <p class="font-bold text-white">Tujuan:</p>
                                        <p class="text-sm text-slate-400 mt-1">Profit untuk bertahan hidup & belajar
                                            trading
                                            crypto!</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 1: Chart -->
                        <div x-show="tutorialStep === 1" class="space-y-4">
                            <h4 class="text-xl font-bold text-white">📊 Memahami Chart</h4>
                            <div class="bg-[#0b0e11] p-4 rounded-lg">
                                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='100' viewBox='0 0 400 100'%3E%3Crect x='20' y='30' width='8' height='40' fill='%230ecb81'/%3E%3Cline x1='24' y1='20' x2='24' y2='80' stroke='%230ecb81' stroke-width='1'/%3E%3Crect x='80' y='50' width='8' height='30' fill='%23f6465d'/%3E%3Cline x1='84' y1='40' x2='84' y2='85' stroke='%23f6465d' stroke-width='1'/%3E%3C/svg%3E"
                                    alt="Candles" class="w-full">
                            </div>
                            <div class="text-sm text-slate-300 space-y-2">
                                <p><span class="text-emerald-400 font-bold">■ Candle Hijau</span> = Harga NAIK (Close >
                                    Open)</p>
                                <p><span class="text-[#f6465d] font-bold">■ Candle Merah</span> = Harga TURUN (Close <
                                        Open)</p>
                                        <p class="mt-3 text-slate-500">💡 <strong>Tips:</strong> Perhatikan pola candle
                                            untuk prediksi harga selanjutnya!</p>
                            </div>
                        </div>

                        <!-- Step 2: Long vs Short -->
                        <div x-show="tutorialStep === 2" class="space-y-4">
                            <h4 class="text-xl font-bold text-white">📈 Long vs Short</h4>

                            <div class="bg-[#0b0e11] p-4 rounded-lg border-l-4 border-emerald-400 space-y-2">
                                <p class="font-bold text-emerald-400">LONG (Buy) = Bet harga NAIK ⬆️</p>
                                <p class="text-xs text-slate-400">Contoh: Entry $65,000 → Exit $65,500 = <span
                                        class="text-emerald-400">+$41 profit!</span></p>
                            </div>

                            <div class="bg-[#0b0e11] p-4 rounded-lg border-l-4 border-[#f6465d] space-y-2">
                                <p class="font-bold text-[#f6465d]">SHORT (Sell) = Bet harga TURUN ⬇️</p>
                                <p class="text-xs text-slate-400">Contoh: Entry $65,000 → Exit $64,500 = <span
                                        class="text-emerald-400">+$41 profit!</span></p>
                            </div>

                            <div class="bg-amber-500/10 border border-amber-500/30 p-3 rounded-lg">
                                <p class="text-xs text-amber-200">⚠️ ROE = 82% dari bet amount jika WIN!</p>
                            </div>
                        </div>

                        <!-- Step 3: Risk Management -->
                        <div x-show="tutorialStep === 3" class="space-y-4">
                            <h4 class="text-xl font-bold text-white">⚖️ Risk Management</h4>

                            <div class="bg-[#f6465d]/10 border-2 border-[#f6465d] p-4 rounded-lg">
                                <p class="font-bold text-[#f6465d] mb-2">🚨 JANGAN BET SEMUA!</p>
                                <p class="text-sm text-slate-300">Jika kalah 1x, balance langsung $0 = GAME OVER</p>
                            </div>

                            <div class="bg-[#0b0e11] p-4 rounded-lg border border-emerald-500/30">
                                <p class="font-bold text-emerald-400 mb-3">✅ Rekomendasi: Bet 10-20% per trade</p>
                                <div class="text-xs text-slate-400 space-y-1 font-mono">
                                    <p>Balance: $1,000</p>
                                    <p>Bet Amount: $100-200 (10-20%)</p>
                                    <p>= Bisa bertahan 5-10 trades meski kalah!</p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Order Book -->
                        <div x-show="tutorialStep === 4" class="space-y-4">
                            <h4 class="text-xl font-bold text-white">📖 Membaca Order Book</h4>

                            <div class="bg-[#0b0e11] p-3 rounded-lg text-xs font-mono space-y-1">
                                <div class="flex justify-between text-[#f6465d]">
                                    <span>65,105.50</span><span class="text-slate-500">0.245</span><span
                                        class="text-slate-600">15k</span>
                                </div>
                                <div class="flex justify-between text-[#f6465d]">
                                    <span>65,103.20</span><span class="text-slate-500">0.892</span><span
                                        class="text-slate-600">58k</span>
                                </div>
                                <div class="border-t border-b border-[#2b3139] py-2 text-center text-white font-bold">
                                    65,100.00</div>
                                <div class="flex justify-between text-emerald-400">
                                    <span>65,098.50</span><span class="text-slate-500">0.534</span><span
                                        class="text-slate-600">34k</span>
                                </div>
                                <div class="flex justify-between text-emerald-400">
                                    <span>65,096.20</span><span class="text-slate-500">1.203</span><span
                                        class="text-slate-600">78k</span>
                                </div>
                            </div>

                            <div class="text-sm text-slate-300 space-y-2">
                                <p><span class="text-[#f6465d] font-bold">Red (Asks)</span> = Sell orders
                                    (Resistance/Tekanan jual)</p>
                                <p><span class="text-emerald-400 font-bold">Green (Bids)</span> = Buy orders
                                    (Support/Tekanan beli)</p>
                                <p class="text-slate-500 text-xs mt-3">💡 Spread kecil = Likuiditas tinggi = Lebih mudah
                                    masuk/keluar posisi</p>
                            </div>
                        </div>

                        <!-- Step 5: Ready to Trade -->
                        <div x-show="tutorialStep === 5" class="space-y-4">
                            <div class="text-center">
                                <div class="text-6xl mb-4">🚀</div>
                                <h4 class="text-2xl font-bold text-white mb-2">Siap Trading!</h4>
                                <p class="text-slate-400">Kamu sudah siap untuk memulai Crypto Trading Panic</p>
                            </div>

                            <div class="bg-emerald-500/10 border border-emerald-500/30 p-4 rounded-lg">
                                <p class="font-bold text-emerald-400 mb-2">Yang Sudah Kamu Pelajari:</p>
                                <ul class="text-sm text-slate-300 space-y-1">
                                    <li>✅ Cara baca candlestick chart</li>
                                    <li>✅ Perbedaan Long vs Short</li>
                                    <li>✅ Risk management (bet 10-20%)</li>
                                    <li>✅ Membaca order book</li>
                                </ul>
                            </div>

                            <div class="bg-[#0b0e11] p-4 rounded-lg border border-[#f0b90b]/30">
                                <p class="text-xs text-slate-400 mb-2">🎯 <strong class="text-white">Goal:</strong></p>
                                <p class="text-sm text-slate-300">Profit untuk bertahan hidup & capai high score!</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tutorial Navigation SOLID -->
                <div class="bg-[#181a20] px-6 py-4 border-t border-[#2b3139] flex justify-between items-center">
                    <button @click="if(tutorialStep > 0) tutorialStep--" :disabled="tutorialStep === 0"
                        :class="tutorialStep === 0 ? 'opacity-30 cursor-not-allowed' : 'hover:bg-[#2b3139]/50'"
                        class="px-4 py-2 rounded-lg font-bold text-sm transition-all text-slate-400">
                        ← Previous
                    </button>

                    <div class="flex gap-2">
                        <template x-for="i in 6" :key="i">
                            <div class="h-2 rounded-full transition-all duration-300"
                                :class="tutorialStep === i-1 ? 'bg-gradient-to-r from-[#f0b90b] to-[#f8d12f] w-8' : 'bg-slate-700 w-2'">
                            </div>
                        </template>
                    </div>

                    <button
                        @click="if(tutorialStep < 5) { tutorialStep++ } else { showTutorial = false; tutorialCompleted = true; saveGame(); }"
                        class="px-6 py-3 rounded-lg font-bold text-sm transition-all bg-gradient-to-r from-[#f0b90b] to-[#f8d12f] hover:from-[#f8d12f] hover:to-[#f0b90b] text-[#0b0e11] shadow-lg">
                        <span x-text="tutorialStep === 5 ? 'Mulai Trading! 🚀' : 'Next →'"></span>
                    </button>
                </div>
            </div>

        </div>



        <!-- LEADERBOARD MODAL -->
        <div x-show="window.game?.showLeaderboard" style="display: none;"
            class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
            @click.self="window.game.showLeaderboard = false">

            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm"></div>

            <div
                class="relative bg-[#1e2329] rounded-2xl border border-[#474d57] shadow-xl max-w-md w-full overflow-hidden flex flex-col max-h-[80vh]">
                <div
                    class="bg-[#181a20] px-6 py-4 border-b border-[#2b3139] flex justify-between items-center shrink-0">
                    <h3 class="text-xl font-bold text-white flex items-center gap-2">
                        🏆 Top Traders
                    </h3>
                    <button @click="window.game.showLeaderboard = false"
                        class="text-slate-500 hover:text-white text-2xl font-bold">×</button>
                </div>

                <div class="flex-1 overflow-y-auto custom-scrollbar p-0">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-[#14161b] text-slate-500 text-xs font-bold uppercase sticky top-0">
                            <tr>
                                <th class="px-6 py-3">Rank</th>
                                <th class="px-6 py-3">Trader</th>
                                <th class="px-6 py-3 text-right">Balance</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#2b3139]">
                            <template x-for="(entry, index) in window.game?.leaderboardData" :key="index">
                                <tr class="hover:bg-[#2b3139]">
                                    <td class="px-6 py-3 font-mono font-bold"
                                        :class="index === 0 ? 'text-[#f0b90b] text-lg' : (index === 1 ? 'text-slate-300' : (index === 2 ? 'text-amber-700' : 'text-slate-500'))">
                                        <span x-text="'#' + (index + 1)"></span>
                                    </td>
                                    <td class="px-6 py-3">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-full bg-[#2b3139] flex items-center justify-center font-bold text-xs text-white uppercase overflow-hidden">
                                                <img x-show="entry.avatar" :src="entry.avatar"
                                                    class="w-full h-full object-cover">
                                                <span x-show="!entry.avatar" x-text="entry.name.substring(0, 2)"></span>
                                            </div>
                                            <div class="font-bold text-white" x-text="entry.name"></div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 text-right font-mono font-bold text-emerald-400">
                                        <span
                                            x-text="'$' + parseFloat(entry.balance).toLocaleString('en-US', {minimumFractionDigits: 2})"></span>
                                    </td>
                                </tr>
                            </template>

                            <tr x-show="!window.game?.leaderboardData.length">
                                <td colspan="3" class="px-6 py-8 text-center text-slate-500">
                                    Loading rankings...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- MAIN LOGIC -->
        <script>
            document.addEventListener('alpine:init', () => {
                window.game = {};
                Alpine.data('proTrader', () => ({
                    // ===== GAME STATE =====
                    phase: 'open',
                    timer: 20,
                    gameOver: false,
                    showGameOverModal: false,
                    showTutorial: false,
                    tutorialCompleted: localStorage.getItem('traderTutorialCompleted') === 'true',
                    tutorialStep: 0,
                    showLeaderboard: false,
                    leaderboardData: [],

                    // ===== PLAYER STATS =====
                    balance: 1000,
                    totalTrades: 0,
                    winningTrades: 0,
                    losingTrades: 0,
                    survivalStreak: 0,
                    bestStreak: 0,
                    totalProfit: 0,

                    // ===== TRADING =====
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
                    tradeHistory: [],
                    activeLeverage: 'spot',

                    // ===== CANVAS =====
                    canvas: null,
                    ctx: null,
                    maxCandles: 150,
                    crosshair: { x: 0, y: 0, visible: false, price: 0 },

                    // ===== INITIALIZATION =====
                    initTrader() {
                        console.log('🚀 Trader Initializing...');
                        this.loadGame();
                        window.userBalance = this.balance;
                        window.game = this;

                        setTimeout(() => {
                            this.canvas = this.$refs.tradeCanvas || document.getElementById('tradeCanvas');
                            if (!this.canvas) {
                                console.error('Canvas not found!');
                                return;
                            }

                            this.setupCanvas();
                            this.fillHistory();
                            this.generateOrderBook();
                            this.startLoops();
                            this.setupEvents();
                            this.draw();

                            console.log('✅ Trader Initialized');
                        }, 100);

                        this.updateHeaderBalance();
                    },

                    // ===== CANVAS SETUP =====
                    setupCanvas() {
                        const container = this.canvas.parentElement;
                        const dpr = window.devicePixelRatio || 1;

                        this.canvas.style.width = container.clientWidth + 'px';
                        this.canvas.style.height = container.clientHeight + 'px';
                        this.canvas.width = container.clientWidth * dpr;
                        this.canvas.height = container.clientHeight * dpr;

                        this.ctx = this.canvas.getContext('2d');
                        this.ctx.scale(dpr, dpr);
                    },

                    // ===== CANDLE GENERATION =====
                    fillHistory() {
                        this.candles = [];
                        let p = 65000;

                        for (let i = 0; i < this.maxCandles; i++) {
                            const seed = Date.now() - (this.maxCandles - i) * 1000;
                            const random = Math.sin(seed) * 10000 - Math.floor(Math.sin(seed) * 10000);

                            const o = p;
                            const change = (random - 0.5) * 40;
                            const c = o + change;
                            const h = Math.max(o, c) + random * 8;
                            const l = Math.min(o, c) - random * 8;

                            this.candles.push({ o, h, l, c });
                            p = c;
                        }

                        this.lastPrice = p;
                        this.prevPrice = p;
                    },

                    // ===== MAIN LOOPS =====
                    startLoops() {
                        // Phase timer (30s cycle: 20s open, 10s locked)
                        setInterval(() => {
                            if (this.gameOver) return;

                            const now = Math.floor(Date.now() / 1000);
                            const cycle = now % 30;

                            if (cycle < 20) {
                                if (this.phase === 'locked') {
                                    this.settle();
                                    this.phase = 'open';
                                }
                                this.timer = 20 - cycle;
                            } else {
                                this.phase = 'locked';
                                this.timer = 30 - cycle;
                            }
                        }, 1000);

                        // Price & candle updates
                        setInterval(() => {
                            if (this.gameOver || !this.candles.length) return;

                            const now = Math.floor(Date.now() / 1000);
                            const random = Math.sin(now) * 10000 - Math.floor(Math.sin(now) * 10000);

                            // Update price
                            this.prevPrice = this.lastPrice;
                            const volatility = this.phase === 'locked' ? 0.008 : 0.004;
                            const change = (random - 0.5) * 200 * volatility * (this.lastPrice / 20000);
                            this.lastPrice += change;

                            // Update current candle
                            const lc = this.candles[this.candles.length - 1];
                            if (lc) {
                                lc.c = this.lastPrice;
                                if (this.lastPrice > lc.h) lc.h = this.lastPrice;
                                if (this.lastPrice < lc.l) lc.l = this.lastPrice;
                            }

                            // Create new candle every 3 seconds
                            if (now % 3 === 0 && (!this._lastCandleTime || this._lastCandleTime !== now)) {
                                this._lastCandleTime = now;

                                const o = this.lastPrice;
                                let candleVol = (random - 0.5) * 80;
                                if (Math.abs(candleVol) < 15) candleVol = candleVol >= 0 ? 15 : -15;

                                const c = o + candleVol;
                                const wickSpread = Math.abs(candleVol) * 0.6;
                                const h = Math.max(o, c) + wickSpread * (0.3 + random * 0.7);
                                const l = Math.min(o, c) - wickSpread * (0.3 + random * 0.7);

                                this.candles.push({ o, h, l, c });
                                if (this.candles.length > this.maxCandles) this.candles.shift();
                            }

                            this.draw();
                        }, 1000);

                        // Order book updates
                        setInterval(() => this.generateOrderBook(), 1000);
                    },

                    // ===== EVENT HANDLERS =====
                    setupEvents() {
                        const chartContainer = document.getElementById('chartContainer');
                        if (!chartContainer) return;

                        chartContainer.addEventListener('mousemove', (e) => {
                            const r = this.canvas.getBoundingClientRect();
                            this.crosshair.x = e.clientX - r.left;
                            this.crosshair.y = e.clientY - r.top;
                            this.crosshair.visible = true;
                            requestAnimationFrame(() => this.draw());
                        });

                        chartContainer.addEventListener('mouseleave', () => {
                            this.crosshair.visible = false;
                            requestAnimationFrame(() => this.draw());
                        });
                    },

                    // ===== CHART DRAWING =====
                    draw() {
                        if (!this.ctx || !this.canvas) return;

                        try {
                            const w = this.canvas.width / (window.devicePixelRatio || 1);
                            const h = this.canvas.height / (window.devicePixelRatio || 1);
                            const ctx = this.ctx;

                            // Clear
                            ctx.clearRect(0, 0, w, h);
                            ctx.fillStyle = '#0b0e11';
                            ctx.fillRect(0, 0, w, h);

                            // Grid
                            ctx.strokeStyle = '#2b3139';
                            ctx.lineWidth = 1;
                            ctx.beginPath();
                            for (let x = w % 80; x < w; x += 80) {
                                ctx.moveTo(x, 0);
                                ctx.lineTo(x, h);
                            }
                            for (let y = h % 80; y < h; y += 80) {
                                ctx.moveTo(0, y);
                                ctx.lineTo(w, y);
                            }
                            ctx.stroke();

                            // Calculate visible candles and Y-axis range
                            const candlesPerScreen = 60;
                            const startIdx = Math.max(0, this.candles.length - candlesPerScreen);
                            const visibleCandles = this.candles.slice(startIdx);

                            if (!visibleCandles.length) return;

                            // Auto-scale Y-axis
                            let visibleMin = Infinity, visibleMax = -Infinity;
                            visibleCandles.forEach(c => {
                                if (c.l < visibleMin) visibleMin = c.l;
                                if (c.h > visibleMax) visibleMax = c.h;
                            });

                            const vRange = visibleMax - visibleMin;
                            const min = visibleMin - (vRange * 0.1);
                            const max = visibleMax + (vRange * 0.1);
                            const range = max - min;

                            // Draw candles
                            const candleWidth = 12;
                            const candleSpacing = 3;
                            const totalCandleWidth = candleWidth + candleSpacing;

                            visibleCandles.forEach((c, i) => {
                                const isGreen = c.c >= c.o;
                                const bodyColor = isGreen ? '#0ecb81' : '#f6465d';
                                const wickColor = isGreen ? '#0aa06a' : '#d63a4f';

                                const x = w - (visibleCandles.length - i) * totalCandleWidth + candleSpacing;

                                const yH = h - ((c.h - min) / range) * h;
                                const yL = h - ((c.l - min) / range) * h;
                                const yO = h - ((c.o - min) / range) * h;
                                const yC = h - ((c.c - min) / range) * h;

                                const bodyTop = Math.min(yO, yC);
                                const bodyBottom = Math.max(yO, yC);
                                let bodyHeight = bodyBottom - bodyTop;
                                if (bodyHeight < 3) bodyHeight = 3;

                                // Wicks
                                ctx.strokeStyle = wickColor;
                                ctx.lineWidth = 1.5;
                                ctx.beginPath();
                                ctx.moveTo(x + candleWidth / 2, yH);
                                ctx.lineTo(x + candleWidth / 2, bodyTop);
                                ctx.stroke();
                                ctx.beginPath();
                                ctx.moveTo(x + candleWidth / 2, bodyBottom);
                                ctx.lineTo(x + candleWidth / 2, yL);
                                ctx.stroke();

                                // Body with glow
                                ctx.shadowColor = bodyColor;
                                ctx.shadowBlur = 3;
                                ctx.fillStyle = bodyColor;
                                ctx.fillRect(x, bodyTop, candleWidth, bodyHeight);
                                ctx.shadowBlur = 0;

                                // Border
                                ctx.strokeStyle = wickColor;
                                ctx.lineWidth = 0.5;
                                ctx.strokeRect(x, bodyTop, candleWidth, bodyHeight);

                                // Volume
                                const volHeight = Math.min((Math.abs(c.c - c.o) / range) * h * 0.15 + 3, h * 0.18);
                                ctx.globalAlpha = 0.12;
                                ctx.fillStyle = bodyColor;
                                ctx.fillRect(x, h - volHeight, candleWidth, volHeight);
                                ctx.globalAlpha = 1.0;
                            });

                            // Position line
                            if (this.myPosition) {
                                const posY = Math.round(h - ((this.myPosition.entry - min) / range) * h);
                                const color = this.myPosition.type === 'buy' ? '#0ecb81' : '#f6465d';

                                ctx.strokeStyle = color;
                                ctx.setLineDash([5, 3]);
                                ctx.lineWidth = 1;
                                ctx.beginPath();
                                ctx.moveTo(0, posY);
                                ctx.lineTo(w, posY);
                                ctx.stroke();
                                ctx.setLineDash([]);

                                ctx.fillStyle = color;
                                ctx.fillRect(w - 60, posY - 10, 60, 20);
                                ctx.fillStyle = '#fff';
                                ctx.font = 'bold 12px Figtree, sans-serif';
                                ctx.textAlign = 'center';
                                ctx.textBaseline = 'middle';
                                ctx.fillText(this.myPosition.entry.toFixed(2), w - 30, posY);
                            }

                            // Price line
                            const priceY = Math.round(h - ((this.lastPrice - min) / range) * h);
                            ctx.shadowColor = '#f0b90b';
                            ctx.shadowBlur = 10;
                            ctx.strokeStyle = '#f0b90b';
                            ctx.setLineDash([2, 2]);
                            ctx.beginPath();
                            ctx.moveTo(0, priceY);
                            ctx.lineTo(w, priceY);
                            ctx.stroke();
                            ctx.setLineDash([]);
                            ctx.shadowBlur = 0;

                            ctx.fillStyle = '#f0b90b';
                            ctx.fillRect(w - 60, priceY - 10, 60, 20);
                            ctx.fillStyle = '#000';
                            ctx.font = 'bold 12px Figtree, sans-serif';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            ctx.fillText(this.lastPrice.toFixed(2), w - 30, priceY);

                            // Crosshair
                            if (this.crosshair.visible) {
                                const cx = Math.round(this.crosshair.x);
                                const cy = Math.round(this.crosshair.y);

                                ctx.strokeStyle = 'rgba(255, 255, 255, 0.5)';
                                ctx.setLineDash([4, 4]);
                                ctx.beginPath();
                                ctx.moveTo(0, cy);
                                ctx.lineTo(w, cy);
                                ctx.moveTo(cx, 0);
                                ctx.lineTo(cx, h);
                                ctx.stroke();
                                ctx.setLineDash([]);

                                const price = (min + ((h - cy) / h) * range).toFixed(2);
                                ctx.fillStyle = '#374151';
                                ctx.fillRect(w - 60, cy - 10, 60, 20);
                                ctx.fillStyle = '#fff';
                                ctx.textAlign = 'center';
                                ctx.textBaseline = 'middle';
                                ctx.fillText(price, w - 30, cy);
                            }

                            ctx.textAlign = 'start';
                            ctx.textBaseline = 'alphabetic';

                        } catch (e) {
                            console.error('Draw error:', e);
                        }
                    },

                    // ===== TRADING LOGIC =====
                    placeOrder(type) {
                        const bet = parseFloat(this.betAmount);
                        if (this.balance < bet) return;

                        this.balance -= bet;
                        this.updateHeaderBalance();

                        this.myPosition = {
                            type: type,
                            entry: this.lastPrice,
                            amount: bet,
                            leverage: this.activeLeverage
                        };
                    },

                    settle() {
                        if (!this.myPosition) return;

                        const exitPrice = this.lastPrice;
                        const entry = this.myPosition.entry;
                        const amount = this.myPosition.amount;
                        const leverage = this.getCurrentLeverageMultiplier();

                        let priceDiff = this.myPosition.type === 'buy' ? (exitPrice - entry) : (entry - exitPrice);
                        let profitPercent = (priceDiff / entry) * 100;
                        let pnl = (amount * profitPercent * leverage) / 100;

                        const isWin = pnl >= 0;
                        this.balance = parseFloat(this.balance) + parseFloat(pnl);

                        this.totalTrades++;
                        if (isWin) {
                            this.winningTrades++;
                            this.survivalStreak++;
                            if (this.survivalStreak > this.bestStreak) this.bestStreak = this.survivalStreak;
                        } else {
                            this.losingTrades++;
                            this.survivalStreak = 0;
                        }

                        this.totalProfit += pnl;
                        this.lastWin = isWin;
                        this.lastPnL = pnl;
                        this.showResult = true;

                        this.tradeHistory.unshift({
                            timestamp: Date.now(),
                            side: this.myPosition.type,
                            entry: entry,
                            exit: exitPrice,
                            pnl: pnl,
                            roe: (profitPercent * leverage).toFixed(2)
                        });

                        this.myPosition = null;
                        this.updateHeaderBalance();
                        this.saveGame();

                        setTimeout(() => this.showResult = false, 3000);

                        if (this.balance <= 0) {
                            this.gameOver = true;
                            this.showGameOverModal = true;
                        }
                    },

                    getCurrentLeverageMultiplier() {
                        if (this.activeLeverage === 'cross 3x') return 3;
                        if (this.activeLeverage === 'iso 10x') return 10;
                        return 1;
                    },

                    generateOrderBook() {
                        const price = this.lastPrice;
                        this.asks = Array.from({ length: 30 }, (_, i) => ({
                            id: 'a' + i,
                            price: price + 0.5 + (i * 1.5) + Math.random(),
                            amount: Math.random()
                        }));
                        this.bids = Array.from({ length: 30 }, (_, i) => ({
                            id: 'b' + i,
                            price: price - 0.5 - (i * 1.5) - Math.random(),
                            amount: Math.random()
                        }));
                    },

                    // ===== UTILITIES =====
                    formatTimer() {
                        return `00:${this.timer.toString().padStart(2, '0')}`;
                    },

                    updateHeaderBalance() {
                        window.dispatchEvent(new CustomEvent('balance-update', { detail: this.balance }));
                    },

                    saveGame() {
                        const gameState = {
                            balance: this.balance,
                            totalTrades: this.totalTrades,
                            winningTrades: this.winningTrades,
                            losingTrades: this.losingTrades,
                            survivalStreak: this.survivalStreak,
                            bestStreak: this.bestStreak,
                            totalProfit: this.totalProfit,
                            tradeHistory: this.tradeHistory.slice(0, 50)
                        };
                        localStorage.setItem('proTraderGame', JSON.stringify(gameState));
                    },

                    loadGame() {
                        const saved = localStorage.getItem('proTraderGame');
                        if (saved) {
                            const state = JSON.parse(saved);
                            Object.assign(this, state);
                        }
                    },

                    resetProgress() {
                        if (confirm('Reset all progress? This cannot be undone!')) {
                            localStorage.removeItem('proTraderGame');
                            location.reload();
                        }
                    },

                    toggleLeaderboard() {
                        this.showLeaderboard = !this.showLeaderboard;
                    },

                    // Tutorial methods (stub)
                    nextTutorialStep() { this.tutorialStep++; },
                    prevTutorialStep() { if (this.tutorialStep > 0) this.tutorialStep--; },
                    skipTutorial() { this.showTutorial = false; localStorage.setItem('traderTutorialCompleted', 'true'); },
                    completeTutorial() { this.skipTutorial(); }
                }));
            });
        </script>

        <!-- MODALS - AT BODY LEVEL FOR PROPER Z-INDEX -->
        showGameOverModal: false,
        showTutorial: false,
        tutorialStep: 0,
        showLeaderboard: false,
        leaderboardData: [],

        // Player Stats
        balance: 1000,
        totalTrades: 0,
        winningTrades: 0,
        losingTrades: 0,
        survivalStreak: 0,
        bestStreak: 0,
        totalProfit: 0,

        // Trading
        lastPrice: 65000.00,
        prevPrice: 65000.00,
        spreadPercent: '0.01',
        betAmount: 100,
        candles: [],
        asks: [],
        bids: [],
        myPosition: null,
        showResult: false,
        lastWin: false,
        lastPnL: 0,
        tradeHistory: [],
        activeLeverage: 'spot', // NEW: Track current leverage mode (spot/cross/iso)

        // Canvas
        crosshair: { x: 0, y: 0, visible: false, price: 0 },
        canvas: null,
        ctx: null,
        maxCandles: 150, // INCREASED from 50 to 150 to ensure full screen fill
        resizeObserver: null,
        chartMin: 64800,
        chartMax: 65200,

        initTrader() {
        // Load saved game or start fresh
        this.loadGame();

        window.userBalance = this.balance;
        window.game = this;

        // Use setTimeout to ensure DOM is fully rendered and containers have width
        setTimeout(() => {
        this.canvas = this.$refs.tradeCanvas || document.getElementById('tradeCanvas');

        // Initialize ResizeObserver AFTER canvas is ready
        this.resizeObserver = new ResizeObserver(entries => {
        for (let entry of entries) {
        if (entry.contentRect.width > 0 && entry.contentRect.height > 0) {
        this.setupCanvas();
        this.draw();
        }
        }
        });
        this.resizeObserver.observe(document.getElementById('chartContainer'));

        this.setupCanvas();
        this.fillHistory();
        this.generateOrderBook();
        this.startInternalLoops();
        this.draw();

        // Setup crosshair events
        const chartContainer = document.getElementById('chartContainer');
        if (chartContainer) {
        chartContainer.addEventListener('mousemove', (e) => {
        const r = this.canvas.getBoundingClientRect();
        this.crosshair.x = e.clientX - r.left;
        this.crosshair.y = e.clientY - r.top;
        this.crosshair.visible = true;
        window.requestAnimationFrame(() => this.draw());
        });

        chartContainer.addEventListener('mouseleave', () => {
        this.crosshair.visible = false;
        window.requestAnimationFrame(() => this.draw());
        });
        }
        }, 100);

        // Show tutorial for first-time users
        if (!this.tutorialCompleted) {
        setTimeout(() => this.showTutorial = true, 1000);
        }

        // Init Balance in Header
        this.updateHeaderBalance();
        },

        setupCanvas() {
        if (!this.canvas) return;
        const container = this.canvas.parentElement;
        const dpr = window.devicePixelRatio || 1;
        // Ensure container has dimensions
        if (container.clientWidth === 0 || container.clientHeight === 0) return;

        // Set display size (css pixels).
        this.canvas.style.width = container.clientWidth + "px";
        this.canvas.style.height = container.clientHeight + "px";

        // Set actual size in memory (scaled to account for extra pixel density).
        this.canvas.width = container.clientWidth * dpr;
        this.canvas.height = container.clientHeight * dpr;

        this.ctx = this.canvas.getContext('2d');
        this.ctx.scale(dpr, dpr);
        },

        getCurrentLeverageMultiplier() {
        if (this.activeLeverage === 'cross 3x') return 3;
        if (this.activeLeverage === 'iso 10x') return 10;
        return 1;
        },

        seededRandom(seed) {
        var x = Math.sin(seed++) * 10000;
        return x - Math.floor(x);
        },

        fillHistory() {
        this.candles = [];
        // Seed based on active timeframe to ensure consistency (simulated)
        let nowSec = Math.floor(Date.now() / 1000);
        let p = 65000.00; // Base price

        // Generate history deterministically-ish (just use simple random for history init)
        for (let i = 0; i < this.maxCandles; i++) { let seed=nowSec - ((this.maxCandles - i) * 60); // 1 min simulation
            let random=this.seededRandom(seed); let o=p; let change=(random - 0.5) * 40; let c=o + change; let
            h=Math.max(o, c) + this.seededRandom(seed + 1) * 8; let l=Math.min(o, c) - this.seededRandom(seed + 2) * 8;
            this.candles.push({ o, h, l, c }); p=c; } this.lastPrice=p; this.prevPrice=p; // Calculate fixed chart range
            for consistent Y-axis (percentage-based padding) let rawMin=Math.min(...this.candles.map(c=> c.l));
            let rawMax = Math.max(...this.candles.map(c => c.h));
            let rawRange = rawMax - rawMin;
            let padding = rawRange * 0.2; // 20% padding top and bottom
            if (padding < 50) padding=50; // Minimum 50 point padding this.chartMin=rawMin - padding;
                this.chartMax=rawMax + padding; }, startInternalLoops() { // 1. PHASE SYNCHRONIZATION LOOP (server-time
                logic) setInterval(()=> {
                if (this.gameOver) return;

                const now = Math.floor(Date.now() / 1000);
                const cycle = now % 30; // 30 second cycle

                if (cycle < 20) { // Open Phase (0-19) if (this.phase==='locked' ) { this.settle(); // Cycle finished
                    this.phase='open' ; } this.timer=20 - cycle; } else { // Locked Phase (20-29) this.phase='locked' ;
                    this.timer=30 - cycle; } }, 1000); // 2. PRICE UPDATE LOOP (Deterministic) setInterval(()=> {
                    if (this.gameOver || !this.candles || this.candles.length === 0) return;

                    const now = Math.floor(Date.now() / 1000);
                    const seed = now; // Shared seed for all players
                    const random = this.seededRandom(seed);

                    this.prevPrice = this.lastPrice;

                    // INCREASED Volatility for more dynamic price movement
                    let volatility = this.phase === 'locked' ? 0.008 : 0.004; // 2-4x higher
                    let change = (random - 0.5) * 200 * volatility * (this.lastPrice / 20000); // INCREASED volatility

                    // Add some noise to make it feel alive
                    this.lastPrice += change;

                    // Update current candle (with safety check)
                    let lc = this.candles[this.candles.length - 1];
                    if (lc) {
                    lc.c = this.lastPrice;
                    if (this.lastPrice > lc.h) lc.h = this.lastPrice;
                    if (this.lastPrice < lc.l) lc.l=this.lastPrice; } // Create NEW candle every 3 seconds (use modulo
                        check with flag to prevent duplicates) const candleInterval=3; const shouldCreateCandle=now %
                        candleInterval===0; // Only create if we haven't already in this second if (shouldCreateCandle
                        && (!this._lastCandleTime || this._lastCandleTime !==now)) { this._lastCandleTime=now; let
                        o=this.lastPrice; // MUCH MORE dramatic volatility for VISIBLE candles let
                        candleVolatility=(random - 0.5) * 80; // ±40 points (was ±20) // Ensure minimum spread for
                        visible body if (Math.abs(candleVolatility) < 15) { candleVolatility=candleVolatility>= 0 ? 15 :
                        -15;
                        }

                        let c = o + candleVolatility;
                        let wickSpread = Math.abs(candleVolatility) * 0.6; // Wick spread relative to body
                        let h = Math.max(o, c) + wickSpread * (0.3 + random * 0.7);
                        let l = Math.min(o, c) - wickSpread * (0.3 + random * 0.7);

                        this.candles.push({ o, h, l, c });
                        if (this.candles.length > this.maxCandles) this.candles.shift();
                        }

                        this.draw();
                        }, 1000);



                        // 4. ORDER BOOK GENERATION (Synced Seed)
                        setInterval(() => this.generateOrderBook(), 1000);

                        // Canvas events are now handled via Alpine.js directives (@mousemove, @mouseleave)
                        // in the chartContainer div, using the updateCrosshair() and hideCrosshair() methods.

                        },

                        // Removed renderLoop - now using setInterval in startInternalLoops

                        placeOrder(type) {
                        const bet = parseFloat(this.betAmount);
                        if (this.balance < bet) return; const oldBalance=this.balance; this.balance -=bet; //
                            Subtraction always casts to number this.updateHeaderBalance(); console.log(`💰 Balance:
                            $${oldBalance} → $${this.balance} (deducted $${bet})`); this.myPosition={ type: type, entry:
                            this.lastPrice, amount: bet, // Store as number leverage: this.activeLeverage }; }, settle()
                            { if (!this.myPosition) return; let diff=this.lastPrice - this.myPosition.entry; let
                            win=(this.myPosition.type==='buy' && diff> 0) || (this.myPosition.type === 'sell' && diff <
                                0); this.lastWin=win; // Calculate PnL let pnl=0; const oldBalance=this.balance;
                                console.log(`🔍 SETTLE START - Balance: $${oldBalance}, Entry: ${this.myPosition.entry},
                                Exit: ${this.lastPrice}, Type: ${this.myPosition.type}`); if (win) { // Apply leverage
                                multiplier to profit const leverageMultiplier=this.getCurrentLeverageMultiplier();
                                pnl=this.myPosition.amount * 0.82 * leverageMultiplier; this.lastPnL=pnl; // Ensure
                                numeric addition this.balance=parseFloat(this.balance) +
                                parseFloat(this.myPosition.amount) + parseFloat(pnl); console.log(`✅ WIN! Balance:
                                $${oldBalance.toFixed(2)} → $${this.balance.toFixed(2)}
                                (+$${(parseFloat(this.myPosition.amount) + parseFloat(pnl)).toFixed(2)})`);
                                console.log(` Bet: $${this.myPosition.amount}, Profit: $${pnl.toFixed(2)}, Leverage:
                                ${leverageMultiplier}x`); } else { pnl=-this.myPosition.amount; this.lastPnL=pnl;
                                console.log(`❌ LOSS! Balance: $${oldBalance.toFixed(2)} remains same (lost bet of
                                $${this.myPosition.amount})`); } // Track statistics this.totalTrades++; if (win) {
                                this.winningTrades++; this.survivalStreak++; if (this.survivalStreak> this.bestStreak) {
                                this.bestStreak = this.survivalStreak;
                                }
                                } else {
                                this.losingTrades++;
                                this.survivalStreak = 0;
                                }
                                this.totalProfit += pnl;

                                // Save to history
                                this.tradeHistory.unshift({
                                timestamp: new Date().toISOString(),
                                side: this.myPosition.type,
                                entry: this.myPosition.entry,
                                exit: this.lastPrice,
                                pnl: pnl,
                                roe: ((pnl / this.myPosition.amount) * 100).toFixed(2)
                                });

                                // Keep only last 50 trades
                                if (this.tradeHistory.length > 50) this.tradeHistory.pop();

                                this.myPosition = null;
                                this.showResult = true;
                                setTimeout(() => this.showResult = false, 3000);

                                // FORCE update header balance
                                this.updateHeaderBalance();
                                console.log(`💰 Final Balance after settlement: $${this.balance.toFixed(2)}`);

                                // Save game state
                                this.saveGame();

                                // Check for game over
                                if (this.balance <= 0) { this.gameOver=true; this.showGameOverModal=true;
                                    this.saveGame(); // Final save with game over state } }, updateHeaderBalance() {
                                    window.userBalance=this.balance; window.dispatchEvent(new
                                    CustomEvent('balance-update', { detail: this.balance })); }, // localStorage Methods
                                    // SAVE GAME TO DB saveGame() { const data={ balance: this.balance, totalTrades:
                                    this.totalTrades, winningTrades: this.winningTrades, bestStreak: this.bestStreak,
                                    totalProfit: this.totalProfit, }; // Optimistic Local Save
                                    localStorage.setItem('cryptoTradingPanic', JSON.stringify({ ...data, losingTrades:
                                    this.losingTrades, tradeHistory: this.tradeHistory, tutorialCompleted:
                                    this.tutorialCompleted })); // Background Sync to Server (with null check for CSRF
                                    token) const csrfToken=document.querySelector('meta[name="csrf-token" ]'); if
                                    (csrfToken) { fetch('/api/games/trader/save', { method: 'POST' , headers:
                                    { 'Content-Type' : 'application/json' , 'X-CSRF-TOKEN' :
                                    csrfToken.getAttribute('content') }, body: JSON.stringify(data) }).catch(err=>
                                    console.error('Auto-save failed:', err));
                                    }
                                    },

                                    // LOAD GAME FROM DB OR LOCAL
                                    loadGame(serverData) {
                                    try {
                                    // Prioritize Server Data
                                    if (serverData && serverData.balance) {
                                    this.balance = parseFloat(serverData.balance);
                                    this.totalTrades = parseInt(serverData.total_trades || 0);
                                    this.winningTrades = parseInt(serverData.winning_trades || 0);
                                    this.bestStreak = parseInt(serverData.best_streak || 0);
                                    this.totalProfit = parseFloat(serverData.total_profit || 0);
                                    // Note: tradeHistory is not in seeded DB yet, so we try load from local or init
                                    empty
                                    const saved = localStorage.getItem('cryptoTradingPanic');
                                    if (saved) {
                                    const localData = JSON.parse(saved);
                                    this.tradeHistory = localData.tradeHistory || [];
                                    this.tutorialCompleted = localData.tutorialCompleted || false;
                                    }
                                    return;
                                    }

                                    // Fallback to LocalStorage
                                    const saved = localStorage.getItem('cryptoTradingPanic');
                                    if (saved) {
                                    const data = JSON.parse(saved);
                                    this.balance = data.balance || 1000;
                                    this.totalTrades = data.totalTrades || 0;
                                    this.winningTrades = data.winningTrades || 0;
                                    this.losingTrades = data.losingTrades || 0;
                                    this.bestStreak = data.bestStreak || 0;
                                    this.totalProfit = data.totalProfit || 0;
                                    this.tradeHistory = data.tradeHistory || [];
                                    this.tutorialCompleted = data.tutorialCompleted || false;
                                    }
                                    } catch (e) {
                                    console.error('Error loading game:', e);
                                    }
                                    },

                                    init() {
                                    window.game = this;
                                    // Use setTimeout to ensure DOM is fully rendered and containers have width
                                    setTimeout(() => {
                                    this.canvas = this.$refs.tradeCanvas;
                                    this.setupCanvas();
                                    this.draw(); // Force initial draw
                                    }, 100);
                                    window.addEventListener('resize', () => this.setupCanvas());

                                    // Inject Server Data
                                    const serverData = @json($gameStat ?? null);
                                    this.loadGame(serverData);

                                    this.fillHistory();
                                    this.startInternalLoops();
                                    this.generateOrderBook();

                                    // Init Balance in Header
                                    this.updateHeaderBalance();
                                    },

                                    resetProgress() {
                                    if (!confirm('Reset game progress? Balance will reset to $1,000.')) return;

                                    // Reset Alpine State
                                    this.balance = 1000;
                                    this.totalTrades = 0;
                                    this.winningTrades = 0;
                                    this.losingTrades = 0;
                                    this.bestStreak = 0;
                                    this.totalProfit = 0;
                                    this.survivalStreak = 0;
                                    this.tradeHistory = [];
                                    this.myPosition = null;
                                    this.gameOver = false;
                                    this.phase = 'open';

                                    // Sync Global
                                    this.updateHeaderBalance();

                                    // Save to Server (using the new saveGame method defined above)
                                    this.saveGame();

                                    alert('Game has been reset to $1,000!');
                                    },

                                    toggleLeaderboard() {
                                    this.showLeaderboard = !this.showLeaderboard;
                                    if (this.showLeaderboard) {
                                    this.fetchLeaderboard();
                                    }
                                    },

                                    fetchLeaderboard() {
                                    fetch('/api/games/trader/leaderboard')
                                    .then(res => res.json())
                                    .then(data => {
                                    this.leaderboardData = data;
                                    })
                                    .catch(err => console.error(err));
                                    },

                                    restartGame() {
                                    // Reset all game states
                                    this.balance = 1000;
                                    this.totalTrades = 0;
                                    this.winningTrades = 0;
                                    this.losingTrades = 0;
                                    this.survivalStreak = 0;
                                    this.totalProfit = 0;
                                    this.tradeHistory = [];
                                    this.myPosition = null;
                                    this.gameOver = false;
                                    this.showGameOverModal = false;
                                    this.phase = 'open';
                                    this.timer = 20;
                                    window.userBalance = this.balance;
                                    this.saveGame();
                                    },

                                    formatTimer() { return `00:${this.timer.toString().padStart(2, '0')}`; },

                                    generateOrderBook() {
                                    const price = this.lastPrice;
                                    this.asks = Array.from({ length: 30 }, (_, i) => ({ id: 'a' + i, price: price + 0.5
                                    + (i * 1.5) + Math.random(), amount: Math.random() }));
                                    this.bids = Array.from({ length: 30 }, (_, i) => ({ id: 'b' + i, price: price - 0.5
                                    - (i * 1.5) - Math.random(), amount: Math.random() }));

                                    if (this.asks.length > 0 && this.bids.length > 0) {
                                    let bestAsk = this.asks[0].price;
                                    let bestBid = this.bids[0].price;
                                    this.spreadPercent = (((bestAsk - bestBid) / bestAsk) * 100).toFixed(3);
                                    }
                                    },

                                    draw() {
                                    try {
                                    if (!this.ctx || !this.canvas) return;
                                    const w = this.canvas.width / (window.devicePixelRatio || 1);
                                    const h = this.canvas.height / (window.devicePixelRatio || 1);
                                    const ctx = this.ctx;

                                    ctx.clearRect(0, 0, w, h);

                                    // RESET CONTEXT STATE (Critical for frame-to-frame consistency)
                                    ctx.shadowBlur = 0;
                                    ctx.shadowColor = 'transparent';
                                    ctx.setLineDash([]);
                                    ctx.globalAlpha = 1.0;
                                    ctx.lineWidth = 1;
                                    ctx.textAlign = 'start';
                                    ctx.textBaseline = 'alphabetic';

                                    // Background
                                    ctx.fillStyle = '#0b0e11'; ctx.fillRect(0, 0, w, h);

                                    // Grid
                                    ctx.strokeStyle = '#2b3139'; ctx.lineWidth = 1; ctx.beginPath();
                                    for (let x = w % 80; x < w; x +=80) { ctx.moveTo(x, 0); ctx.lineTo(x, h); } for (let
                                        y=h % 80; y < h; y +=80) { ctx.moveTo(0, y); ctx.lineTo(w, y); } ctx.stroke();
                                        //=====FIXED: SCROLLING CANDLESTICK CHART (RIGHT-ALIGNED)=====// Show LATEST
                                        candles on the right, old candles scroll left const candlesPerScreen=60; //
                                        Increased density slightly const candleWidth=12; const candleSpacing=3; const
                                        totalCandleWidth=candleWidth + candleSpacing; // Calculate visible candles const
                                        startIdx=Math.max(0, this.candles.length - candlesPerScreen); const
                                        visibleCandles=this.candles.slice(startIdx); // AUTO-SCALE Y-AXIS based on
                                        VISIBLE candles only // This ensures candles always look "tall" and readable let
                                        visibleMin=Infinity; let visibleMax=-Infinity; visibleCandles.forEach(c=> {
                                        if (c.l < visibleMin) vi sibleMin=c.l; if (c.h> visibleMax) visibleMax = c.h;
                                            });

                                            // Add dynamic padding (10% top/bottom)
                                            let vRange = visibleMax - visibleMin;
                                            if (vRange < 10) vRange=10; // Minimum range let min=visibleMin - (vRange *
                                                0.1); let max=visibleMax + (vRange * 0.1); let range=max - min; // Start
                                                drawing from the RIGHT side with PREMIUM effects
                                                visibleCandles.forEach((c, i)=> {
                                                const isGreen = c.c >= c.o;
                                                const bodyColor = isGreen ? '#0ecb81' : '#f6465d';
                                                const wickColor = isGreen ? '#0aa06a' : '#d63a4f'; // Darker wicks

                                                // Calculate X position from RIGHT to LEFT
                                                const x = w - (visibleCandles.length - i) * totalCandleWidth +
                                                candleSpacing;

                                                // Calculate Y positions
                                                const yH = h - ((c.h - min) / range) * h;
                                                const yL = h - ((c.l - min) / range) * h;
                                                const yO = h - ((c.o - min) / range) * h;
                                                const yC = h - ((c.c - min) / range) * h;

                                                // Body dimensions
                                                const bodyTop = Math.min(yO, yC);
                                                const bodyBottom = Math.max(yO, yC);
                                                let bodyHeight = bodyBottom - bodyTop;
                                                if (bodyHeight < 3) bodyHeight=3; // Minimum 3px for visibility // Draw
                                                    upper wick (only to body top) ctx.strokeStyle=wickColor;
                                                    ctx.lineWidth=1.5; ctx.beginPath(); ctx.moveTo(x + candleWidth / 2,
                                                    yH); ctx.lineTo(x + candleWidth / 2, bodyTop); ctx.stroke(); // Draw
                                                    lower wick (only from body bottom) ctx.beginPath(); ctx.moveTo(x +
                                                    candleWidth / 2, bodyBottom); ctx.lineTo(x + candleWidth / 2, yL);
                                                    ctx.stroke(); // Draw body with subtle glow
                                                    ctx.shadowColor=bodyColor; ctx.shadowBlur=3;
                                                    ctx.fillStyle=bodyColor; ctx.fillRect(x, bodyTop, candleWidth,
                                                    bodyHeight); ctx.shadowBlur=0; // Add subtle border for depth
                                                    ctx.strokeStyle=isGreen ? '#0aa06a' : '#d63a4f' ; ctx.lineWidth=0.5;
                                                    ctx.strokeRect(x, bodyTop, candleWidth, bodyHeight); // Volume bars
                                                    with gradient effect const volHeight=Math.min((Math.abs(c.c - c.o) /
                                                    range) * h * 0.15 + 3, h * 0.18); ctx.globalAlpha=0.12;
                                                    ctx.fillStyle=bodyColor; ctx.fillRect(x, h - volHeight, candleWidth,
                                                    volHeight); ctx.globalAlpha=1.0; }); ctx.textBaseline='alphabetic' ;
                                                    // Reset ctx.textAlign='start' ; // Reset // Position Line (Draw
                                                    this FIRST so Price Line covers it) if (this.myPosition) { let
                                                    posPrice=this.myPosition.entry; let posY=Math.round(h - ((posPrice -
                                                    min) / range) * h); let color=this.myPosition.type==='buy'
                                                    ? '#0ecb81' : '#f6465d' ; // Line ctx.strokeStyle=color;
                                                    ctx.setLineDash([5, 3]); ctx.lineWidth=1; ctx.beginPath();
                                                    ctx.moveTo(0, posY + 0.5); ctx.lineTo(w, posY + 0.5); ctx.stroke();
                                                    ctx.setLineDash([]); // Reset // Label ctx.fillStyle=color;
                                                    ctx.fillRect(w - 60, posY - 10, 60, 20); ctx.fillStyle='#fff' ;
                                                    ctx.textAlign='center' ; ctx.textBaseline='middle' ;
                                                    ctx.font='bold 12px Figtree, sans-serif' ;
                                                    ctx.fillText(posPrice.toFixed(2), w - 30, posY);
                                                    ctx.textAlign='start' ; ctx.textBaseline='alphabetic' ; } // Price
                                                    Line let priceY=Math.round(h - ((this.lastPrice - min) / range) *
                                                    h); // If PriceY and PosY collide, we prioritize PriceY. // (Which
                                                    happens naturally since we draw it afterwards) // Glow Effect
                                                    ctx.shadowColor='#f0b90b' ; ctx.shadowBlur=10;
                                                    ctx.strokeStyle='#f0b90b' ; ctx.setLineDash([2, 2]);
                                                    ctx.beginPath(); ctx.moveTo(0, priceY + 0.5); // 0.5 offset for
                                                    sharp lines ctx.lineTo(w, priceY + 0.5); ctx.stroke();
                                                    ctx.setLineDash([]); ctx.shadowBlur=0; // Reset shadow // Label
                                                    ctx.fillStyle='#f0b90b' ; ctx.fillRect(w - 60, priceY - 10, 60, 20);
                                                    // Slightly larger box ctx.fillStyle='#000' ;
                                                    ctx.font='bold 12px Figtree, sans-serif' ; ctx.textAlign='center' ;
                                                    ctx.textBaseline='middle' ; ctx.fillText(this.lastPrice.toFixed(2),
                                                    w - 30, priceY); // Removed +4 offset due to baseline middle
                                                    ctx.textBaseline='alphabetic' ; // Reset ctx.textAlign='start' ; //
                                                    Reset // Crosshair if (this.crosshair.visible) { const
                                                    cx=Math.round(this.crosshair.x) + 0.5; const
                                                    cy=Math.round(this.crosshair.y) + 0.5;
                                                    ctx.strokeStyle='rgba(255, 255, 255, 0.5)' ; ctx.setLineDash([4,
                                                    4]); ctx.beginPath(); ctx.moveTo(0, cy); ctx.lineTo(w, cy);
                                                    ctx.moveTo(cx, 0); ctx.lineTo(cx, h); ctx.stroke(); // Crosshair
                                                    Label const price=(min + ((h - this.crosshair.y) / h) *
                                                    range).toFixed(2); this.crosshair.price=price; // Draw label for
                                                    crosshair too ctx.fillStyle='#374151' ; ctx.fillRect(w - 60, cy -
                                                    10, 60, 20); ctx.fillStyle='#fff' ; ctx.fillText(price, w - 30, cy +
                                                    4); } // Crosshair if (this.crosshair.visible) { // ... (existing
                                                    crosshair redraw logic handled by loop, but ensuring block closure)
                                                    } } catch (e) { console.error("Chart Draw Error:", e); } },
                                                    updateCrosshair(e) { const r=this.canvas.getBoundingClientRect();
                                                    this.crosshair.x=e.clientX - r.left; this.crosshair.y=e.clientY -
                                                    r.top; this.crosshair.visible=true;
                                                    window.requestAnimationFrame(()=> this.draw());
                                                    },
                                                    hideCrosshair() {
                                                    this.crosshair.visible = false;
                                                    window.requestAnimationFrame(() => this.draw());
                                                    }
                                                    }));
                                                    });
                                                    </script>

                                                    <!-- MODALS - AT BODY LEVEL FOR PROPER Z-INDEX -->
                                                    <div x-data>
                                                        <!-- GAME OVER MODAL -->
                                                        <div x-show="window.game?.showGameOverModal"
                                                            style="display: none; position: fixed; inset: 0; z-index: 9999999 !important;">

                                                            <!-- SEMI-TRANSPARENT BACKGROUND - Show game behind -->
                                                            <div
                                                                style="position: absolute; inset: 0; background-color: rgba(0, 0, 0, 0.85) !important; backdrop-filter: blur(8px); z-index: 9999998 !important;">
                                                            </div>

                                                            <!-- MODAL CONTENT -->
                                                            <div style="position: relative; z-index: 9999999 !important;"
                                                                class="flex items-center justify-center min-h-screen p-4">
                                                                <div
                                                                    class="bg-[#1e2329] p-8 rounded-2xl border-2 border-[#f6465d] text-center shadow-2xl max-w-md w-full">
                                                                    <div class="text-7xl mb-4">💀</div>
                                                                    <h2
                                                                        class="text-4xl font-black text-[#f6465d] mb-2 uppercase">
                                                                        GAME OVER</h2>
                                                                    <p class="text-slate-400 mb-6">Balance habis!
                                                                        Survival streak berakhir.</p>

                                                                    <div
                                                                        class="bg-[#14161b] rounded-xl p-4 mb-6 space-y-2 text-left">
                                                                        <div class="flex justify-between">
                                                                            <span class="text-slate-400 text-sm">Total
                                                                                Trades:</span>
                                                                            <span class="text-white font-bold"
                                                                                x-text="window.game?.totalTrades || 0"></span>
                                                                        </div>
                                                                        <div class="flex justify-between">
                                                                            <span class="text-slate-400 text-sm">Win
                                                                                Rate:</span>
                                                                            <span class="text-emerald-400 font-bold"
                                                                                x-text="window.game?.totalTrades > 0 ? ((window.game.winningTrades / window.game.totalTrades) * 100).toFixed(1) + '%' : '0%'"></span>
                                                                        </div>
                                                                        <div class="flex justify-between">
                                                                            <span class="text-slate-400 text-sm">Best
                                                                                Streak:</span>
                                                                            <span class="text-[#f0b90b] font-bold"
                                                                                x-text="window.game?.bestStreak || 0"></span>
                                                                        </div>
                                                                        <div
                                                                            class="flex justify-between border-t border-[#2b3139] pt-2">
                                                                            <span class="text-slate-400 text-sm">Total
                                                                                P&L:</span>
                                                                            <span class="font-bold"
                                                                                :class="(window.game?.totalProfit || 0) >= 0 ? 'text-emerald-400' : 'text-[#f6465d]'"
                                                                                x-text="'$' + ((window.game?.totalProfit || 0).toFixed(2))"></span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="flex gap-3">
                                                                        <button @click="window.game?.restartGame()"
                                                                            class="flex-1 bg-gradient-to-r from-[#f0b90b] to-[#f8d12f] hover:from-[#f8d12f] hover:to-[#f0b90b] text-[#0b0e11] font-bold py-3 rounded-lg">
                                                                            Restart Game
                                                                        </button>
                                                                        <a href="{{ route('homepage') }}"
                                                                            class="flex-1 bg-[#2b3139] hover:bg-[#474d57] text-white font-bold py-3 rounded-lg text-center flex items-center justify-center">
                                                                            Home
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- TUTORIAL MODAL -->
                                                        <div x-show="window.game?.showTutorial"
                                                            style="display: none; position: fixed; inset: 0; z-index: 9999999 !important;"
                                                            @click.self="window.game.showTutorial = false">

                                                            <!-- SEMI-TRANSPARENT BACKGROUND - Show game behind -->
                                                            <div
                                                                style="position: absolute; inset: 0; background-color: rgba(0, 0, 0, 0.85) !important; backdrop-filter: blur(8px); z-index: 9999998 !important;">
                                                            </div>

                                                            <!-- MODAL CONTENT -->
                                                            <div style="position: relative; z-index: 9999999 !important;"
                                                                class="flex items-center justify-center min-h-screen p-4">
                                                                <div
                                                                    class="bg-[#1e2329] rounded-2xl border border-[#474d57] shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col">
                                                                    <!-- Header -->
                                                                    <div
                                                                        class="bg-[#181a20] px-6 py-4 border-b border-[#2b3139] flex justify-between items-center">
                                                                        <div>
                                                                            <h3 class="text-xl font-bold text-white">📚
                                                                                Crypto Trading Panic Tutorial</h3>
                                                                            <p class="text-xs text-slate-500 mt-1">Step
                                                                                <span
                                                                                    x-text="(window.game?.tutorialStep || 0) + 1"></span>
                                                                                of 6</p>
                                                                        </div>
                                                                        <button
                                                                            @click="window.game.showTutorial = false; window.game.tutorialCompleted = true; window.game.saveGame();"
                                                                            class="text-slate-500 hover:text-white text-2xl font-bold">×</button>
                                                                    </div>

                                                                    <!-- Progress Bar -->
                                                                    <div class="bg-[#0b0e11] h-1.5">
                                                                        <div class="bg-gradient-to-r from-[#f0b90b] to-[#f8d12f] h-full transition-all"
                                                                            :style="`width: ${(((window.game?.tutorialStep || 0) + 1) / 6) * 100}%`">
                                                                        </div>
                                                                    </div>

                                                                    <!-- Content -->
                                                                    <div class="overflow-y-auto flex-1 p-6">
                                                                        <div x-show="(window.game?.tutorialStep || 0) === 0"
                                                                            class="space-y-4">
                                                                            <div class="text-center mb-6">
                                                                                <div class="text-6xl mb-4">🎮</div>
                                                                                <h2
                                                                                    class="text-3xl font-black text-white mb-2">
                                                                                    Selamat Datang!</h2>
                                                                                <p class="text-slate-300">Game survival
                                                                                    trading edukatif</p>
                                                                            </div>
                                                                            <div
                                                                                class="bg-[#14161b] p-5 rounded-xl space-y-3">
                                                                                <p class="text-white font-bold">⏱️
                                                                                    Mekanik Game:</p>
                                                                                <p class="text-slate-300 text-sm">• 20
                                                                                    detik Open Market</p>
                                                                                <p class="text-slate-300 text-sm">• 10
                                                                                    detik Locked Phase</p>
                                                                                <p class="text-slate-300 text-sm">•
                                                                                    Balance $0 = GAME OVER</p>
                                                                            </div>
                                                                        </div>

                                                                        <div x-show="(window.game?.tutorialStep || 0) === 1"
                                                                            class="space-y-4">
                                                                            <div class="text-center mb-6">
                                                                                <div class="text-6xl mb-4">📊</div>
                                                                                <h2
                                                                                    class="text-3xl font-black text-white mb-2">
                                                                                    Candlestick Chart</h2>
                                                                            </div>
                                                                            <div
                                                                                class="bg-[#14161b] p-5 rounded-xl space-y-3">
                                                                                <p class="text-emerald-400 font-bold">🟢
                                                                                    Candle Hijau = Harga NAIK</p>
                                                                                <p class="text-[#f6465d] font-bold">🔴
                                                                                    Candle Merah = Harga TURUN</p>
                                                                            </div>
                                                                        </div>

                                                                        <div x-show="(window.game?.tutorialStep || 0) === 2"
                                                                            class="space-y-4">
                                                                            <div class="text-center mb-6">
                                                                                <div class="text-6xl mb-4">⚔️</div>
                                                                                <h2
                                                                                    class="text-3xl font-black text-white mb-2">
                                                                                    Long vs Short</h2>
                                                                            </div>
                                                                            <div class="grid grid-cols-2 gap-4">
                                                                                <div
                                                                                    class="bg-emerald-500/10 border-2 border-emerald-500/30 p-4 rounded-xl">
                                                                                    <p
                                                                                        class="text-emerald-400 font-bold mb-2">
                                                                                        📈 LONG</p>
                                                                                    <p class="text-slate-300 text-sm">
                                                                                        Bet harga NAIK ⬆️</p>
                                                                                </div>
                                                                                <div
                                                                                    class="bg-rose-500/10 border-2 border-rose-500/30 p-4 rounded-xl">
                                                                                    <p
                                                                                        class="text-[#f6465d] font-bold mb-2">
                                                                                        📉 SHORT</p>
                                                                                    <p class="text-slate-300 text-sm">
                                                                                        Bet harga TURUN ⬇️</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div x-show="(window.game?.tutorialStep || 0) === 3"
                                                                            class="space-y-4">
                                                                            <div class="text-center mb-6">
                                                                                <div class="text-6xl mb-4">🛡️</div>
                                                                                <h2
                                                                                    class="text-3xl font-black text-white mb-2">
                                                                                    Risk Management</h2>
                                                                            </div>
                                                                            <div
                                                                                class="bg-[#f6465d]/10 border-2 border-[#f6465d] p-5 rounded-xl">
                                                                                <p
                                                                                    class="text-[#f6465d] font-black text-2xl mb-2">
                                                                                    🚨 JANGAN BET SEMUA!</p>
                                                                                <p class="text-slate-300 text-sm">Satu
                                                                                    loss = Game Over</p>
                                                                            </div>
                                                                            <div
                                                                                class="bg-emerald-500/10 border border-emerald-500/30 p-4 rounded-xl">
                                                                                <p class="text-emerald-400 font-bold">✅
                                                                                    Bet 10-20% Balance</p>
                                                                                <p class="text-slate-300 text-sm">Agar
                                                                                    bisa survive</p>
                                                                            </div>
                                                                        </div>

                                                                        <div x-show="(window.game?.tutorialStep || 0) === 4"
                                                                            class="space-y-4">
                                                                            <div class="text-center mb-6">
                                                                                <div class="text-6xl mb-4">📖</div>
                                                                                <h2
                                                                                    class="text-3xl font-black text-white mb-2">
                                                                                    Order Book</h2>
                                                                            </div>
                                                                            <div
                                                                                class="bg-[#14161b] p-5 rounded-xl space-y-3">
                                                                                <p class="text-[#f6465d] font-bold">🔴
                                                                                    Asks = Sell Orders</p>
                                                                                <p class="text-emerald-400 font-bold">🟢
                                                                                    Bids = Buy Orders</p>
                                                                            </div>
                                                                        </div>

                                                                        <div x-show="(window.game?.tutorialStep || 0) === 5"
                                                                            class="space-y-4">
                                                                            <div class="text-center mb-6">
                                                                                <div class="text-6xl mb-4">🚀</div>
                                                                                <h2
                                                                                    class="text-3xl font-black text-white mb-2">
                                                                                    Siap Trading!</h2>
                                                                            </div>
                                                                            <div
                                                                                class="bg-emerald-500/10 border border-emerald-500/30 p-4 rounded-xl">
                                                                                <p
                                                                                    class="text-emerald-400 font-bold mb-2">
                                                                                    Yang Sudah Dipelajari:</p>
                                                                                <p class="text-slate-300 text-sm">✅
                                                                                    Candlestick Chart</p>
                                                                                <p class="text-slate-300 text-sm">✅ Long
                                                                                    vs Short</p>
                                                                                <p class="text-slate-300 text-sm">✅ Risk
                                                                                    Management</p>
                                                                                <p class="text-slate-300 text-sm">✅
                                                                                    Order Book</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Navigation -->
                                                                    <div
                                                                        class="bg-[#181a20] px-6 py-4 border-t border-[#2b3139] flex justify-between items-center">
                                                                        <button
                                                                            @click="if((window.game?.tutorialStep || 0) > 0) window.game.tutorialStep--"
                                                                            :disabled="(window.game?.tutorialStep || 0) === 0"
                                                                            :class="(window.game?.tutorialStep || 0) === 0 ? 'opacity-30' : 'hover:bg-[#2b3139]/50'"
                                                                            class="px-4 py-2 rounded-lg font-bold text-sm text-slate-400">
                                                                            ← Previous
                                                                        </button>

                                                                        <div class="flex gap-2">
                                                                            <template x-for="i in 6" :key="i">
                                                                                <div class="h-2 rounded-full transition-all"
                                                                                    :class="(window.game?.tutorialStep || 0) === i-1 ? 'bg-gradient-to-r from-[#f0b90b] to-[#f8d12f] w-8' : 'bg-slate-700 w-2'">
                                                                                </div>
                                                                            </template>
                                                                        </div>

                                                                        <button
                                                                            @click="if((window.game?.tutorialStep || 0) < 5) { window.game.tutorialStep++ } else { window.game.showTutorial = false; window.game.tutorialCompleted = true; window.game.saveGame(); }"
                                                                            class="px-6 py-3 rounded-lg font-bold text-sm bg-gradient-to-r from-[#f0b90b] to-[#f8d12f] hover:from-[#f8d12f] hover:to-[#f0b90b] text-[#0b0e11]">
                                                                            <span
                                                                                x-text="(window.game?.tutorialStep || 0) === 5 ? 'Mulai Trading! 🚀' : 'Next →'"></span>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Leaderboard Toggle Button (Bottom-Right) -->
                                                    <div x-data="{ showLeaderboard: false, leaders: [] }" x-init="
        fetch('/api/games/trader/leaderboard')
            .then(r => r.json())
            .then(data => leaders = data)
            .catch(err => console.error('Leaderboard error:', err));
     " class="fixed right-0 top-12 h-[calc(100vh-3rem)] w-80 bg-[#1e2329] border-l border-[#2b3139] transform transition-transform duration-300 z-50"
                                                        :class="showLeaderboard ? 'translate-x-0' : 'translate-x-full'">

                                                        <!-- Toggle Button -->
                                                        <button @click="showLeaderboard = !showLeaderboard"
                                                            class="absolute left-0 bottom-20 -translate-x-full bg-[#f0b90b] px-2 py-4 rounded-l text-2xl hover:bg-[#d9a009] transition-colors">
                                                            🏆
                                                        </button>

                                                        <!-- Leaderboard Content -->
                                                        <div class="p-4 h-full overflow-y-auto">
                                                            <div class="flex items-center justify-between mb-4">
                                                                <h3 class="text-lg font-bold text-white">Leaderboard
                                                                </h3>
                                                                <span class="text-xs text-slate-500">Live</span>
                                                            </div>

                                                            <template x-if="leaders.length === 0">
                                                                <div class="text-center text-slate-500 py-8">
                                                                    <div class="text-4xl mb-2">👤</div>
                                                                    <p>No traders yet!</p>
                                                                </div>
                                                            </template>

                                                            <div class="space-y-2">
                                                                <template x-for="(leader, index) in leaders"
                                                                    :key="leader.id">
                                                                    <div
                                                                        class="bg-[#0b0e11] p-3 rounded-lg border border-[#2b3139]">
                                                                        <div class="flex items-center justify-between">
                                                                            <div class="flex items-center gap-2">
                                                                                <span
                                                                                    class="text-[#f0b90b] font-bold text-lg"
                                                                                    x-text="'#' + (index + 1)"></span>
                                                                                <span class="text-white font-medium"
                                                                                    x-text="leader.username"></span>
                                                                            </div>
                                                                            <span
                                                                                class="text-emerald-400 font-mono text-sm"
                                                                                x-text="'

</html> + leader.total_profit.toLocaleString()"></span>
                                                                        </div>
                                                                        <div class="text-xs text-slate-500 mt-1"
                                                                            x-text="leader.total_trades + ' trades'">
                                                                        </div>
                                                                    </div>
                                                                </template>
                                                            </div>
                                                        </div>
                                                    </div>
</body>

</html>