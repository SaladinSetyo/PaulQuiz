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
    </style>
</head>

<body class="flex flex-col h-screen bg-[#0b0e11] text-[#eaecef] select-none text-xs overflow-hidden">

    <!-- HEADER -->
    <header class="h-12 bg-[#181a20] border-b border-[#2b3139] flex items-center px-4 shrink-0 justify-between z-50">
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
                    <span class="text-[12px] font-bold text-emerald-400" x-data
                        x-text="window.game?.lastPrice?.toFixed(2) ?? '---'"></span>
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
            <div class="flex items-center gap-2 bg-[#2b3139] px-3 py-1.5 rounded-md" x-data>
                <span class="w-2 h-2 rounded-full"
                    :class="window.game?.phase === 'open' ? 'bg-emerald-500 animate-pulse' : 'bg-amber-500'"></span>
                <span class="font-bold text-white uppercase text-[10px] tracking-wide"
                    x-text="window.game?.phase === 'open' ? 'TRADING' : 'LOCKED'"></span>
                <span class="font-mono font-bold text-[#f0b90b] text-sm ml-2"
                    x-text="window.game?.formatTimer() ?? '00:00'"></span>
            </div>

            <div class="flex flex-col items-end leading-none">
                <span class="text-[10px] text-slate-500 font-bold uppercase mb-0.5">Wallet</span>
                <span class="font-mono font-bold text-white text-[13px]"
                    x-text="'$' + (window.userBalance?.toLocaleString('en-US') ?? '1,000')"></span>
            </div>

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
    <div x-data="proTrader()" x-init="initTrader()" x-cloak
        class="flex-grow flex flex-col lg:grid lg:grid-cols-[1fr_280px_300px] overflow-y-auto lg:overflow-hidden bg-[#0b0e11]">

        <!-- COL 1: CHART (Main) -->
        <div
            class="flex flex-col min-w-0 min-h-[400px] lg:min-h-0 bg-[#0b0e11] relative border-r border-[#2b3139] overflow-hidden">
            <!-- Chart Toolbar -->
            <div
                class="h-9 border-b border-[#2b3139] flex items-center px-4 gap-4 text-[11px] font-bold text-slate-500 bg-[#0b0e11]">
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

            <!-- Canvas Container -->
            <div id="chartContainer" class="flex-grow relative w-full h-full bg-[#0b0e11] crs-crosshair min-h-0"
                @mousemove="updateCrosshair" @mouseleave="hideCrosshair">
                <canvas id="tradeCanvas" class="absolute inset-0 w-full h-full block"></canvas>

                <!-- Crosshair Label -->
                <div x-show="crosshair.visible"
                    class="absolute bg-[#1e2329] text-white text-[10px] font-mono px-1.5 py-1 rounded pointer-events-none z-30 border border-[#474d57] shadow-lg"
                    :style="`left: ${crosshair.x + 15}px; top: ${crosshair.y - 12}px;`">
                    <span x-text="crosshair.price"></span>
                </div>
            </div>

            <!-- Bottom Tabs: Positions & History -->
            <div class="h-[200px] border-t border-[#2b3139] bg-[#0b0e11] flex flex-col shrink-0"
                x-data="{ activeTab: 'positions' }">
                <div class="h-9 flex items-center px-4 gap-6 border-b border-[#2b3139] bg-[#14161b]">
                    <span @click="activeTab = 'positions'"
                        :class="activeTab === 'positions' ? 'text-[#f0b90b] border-b-2 border-[#f0b90b]' : 'text-slate-500 hover:text-white'"
                        class="text-[11px] font-bold h-full flex items-center px-1 cursor-pointer transition-colors">Positions
                        <span x-text="myPosition ? '(1)' : '(0)'"></span></span>
                    <span @click="activeTab = 'history'"
                        :class="activeTab === 'history' ? 'text-[#f0b90b] border-b-2 border-[#f0b90b]' : 'text-slate-500 hover:text-white'"
                        class="text-[11px] font-bold h-full flex items-center px-1 cursor-pointer transition-colors">Trade
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
                                        <button @click="settle()"
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
            class="flex flex-col border-r border-[#2b3139] bg-[#14161b] min-w-0 h-[300px] lg:h-auto order-3 lg:order-none border-t lg:border-t-0 border-[#2b3139] overflow-hidden">
            <div class="h-9 flex items-center px-3 border-b border-[#2b3139] bg-[#181a20]">
                <span class="text-[11px] font-bold text-white">Order Book</span>
            </div>

            <div class="px-3 py-1.5 flex justify-between text-[10px] font-bold text-slate-500 bg-[#14161b]">
                <span>Price</span>
                <span>Amount</span>
                <span>Total</span>
            </div>

            <div class="flex-1 overflow-y-auto font-mono text-[10px] relative"
                style="scrollbar-width: thin; scrollbar-color: #2b3139 #14161b;">
                <!-- Sells -->
                <div class="flex-1 overflow-hidden flex flex-col-reverse justify-start">
                    <template x-for="ask in asks" :key="ask.id">
                        <div
                            class="flex justify-between px-3 py-[1px] relative hover:bg-[#2b3139] cursor-pointer group h-[18px] items-center">
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
                            class="flex justify-between px-3 py-[1px] relative hover:bg-[#2b3139] cursor-pointer group h-[18px] items-center">
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
        <div class="flex flex-col bg-[#1e2329] min-w-0 border-l border-[#2b3139]">
            <!-- Tabs -->
            <div class="flex bg-[#181a20] text-[11px] font-bold border-b border-[#2b3139] shrink-0">
                <button class="flex-1 py-3 text-[#f0b90b] border-t-2 border-[#f0b90b] bg-[#1e2329]">Spot</button>
                <button class="flex-1 py-3 text-slate-500 hover:text-white transition-colors">Cross 3x</button>
                <button class="flex-1 py-3 text-slate-500 hover:text-white transition-colors">Iso 10x</button>
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
                    <div class="flex justify-between text-[11px] font-bold text-slate-400">
                        <span>Avbl Balance</span>
                        <span class="text-white"><span x-text="Math.floor(balance)"></span> USDT</span>
                    </div>

                    <!-- Price Input -->
                    <div
                        class="flex items-center bg-[#2b3139] rounded border border-[#2b3139] h-10 hover:border-[#f0b90b] transition-colors group">
                        <span
                            class="pl-3 text-[11px] font-bold text-slate-400 w-16 group-hover:text-slate-300">Price</span>
                        <input type="text" disabled value="Market Price"
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
                        class="h-11 rounded bg-[#0ecb81] hover:bg-[#0da86b] disabled:opacity-50 disabled:cursor-not-allowed text-white text-[13px] font-bold shadow-lg transition-all active:scale-[0.98]">
                        Buy / Long
                    </button>
                    <button @click="placeOrder('sell')" :disabled="window.game?.phase!=='open' || myPosition"
                        class="h-11 rounded bg-[#f6465d] hover:bg-[#d93a4e] disabled:opacity-50 disabled:cursor-not-allowed text-white text-[13px] font-bold shadow-lg transition-all active:scale-[0.98]">
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

        <!-- MAIN LOGIC -->
        <script>
            document.addEventListener('alpine:init', () => {
                window.game = {};
                Alpine.data('proTrader', () => ({
                    // Game States
                    phase: 'open',
                    timer: 20,
                    gameOver: false,
                    showGameOverModal: false,
                    showTutorial: false,
                    tutorialStep: 0,

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

                    // Canvas
                    crosshair: { x: 0, y: 0, visible: false, price: 0 },
                    canvas: null,
                    ctx: null,
                    maxCandles: 50,
                    resizeObserver: null,

                    initTrader() {
                        // Load saved game or start fresh
                        this.loadGame();

                        window.userBalance = this.balance;
                        window.game = this;
                        this.canvas = document.getElementById('tradeCanvas');
                        this.fillHistory();
                        this.generateOrderBook();

                        // Robust Resize Observer
                        this.resizeObserver = new ResizeObserver(entries => {
                            for (let entry of entries) {
                                if (entry.contentRect.width > 0 && entry.contentRect.height > 0) {
                                    this.setupCanvas();
                                    this.draw();
                                }
                            }
                        });
                        this.resizeObserver.observe(document.getElementById('chartContainer'));

                        this.startInternalLoops();

                        // Show tutorial for first-time users
                        if (!this.tutorialCompleted) {
                            setTimeout(() => this.showTutorial = true, 1000);
                        }
                    },

                    setupCanvas() {
                        if (!this.canvas) return;
                        const container = this.canvas.parentElement;
                        const dpr = window.devicePixelRatio || 1;
                        // Ensure container has dimensions
                        if (container.clientWidth === 0 || container.clientHeight === 0) return;

                        this.canvas.width = container.clientWidth * dpr;
                        this.canvas.height = container.clientHeight * dpr;
                        this.ctx = this.canvas.getContext('2d');
                        this.ctx.scale(dpr, dpr);
                    },

                    fillHistory() {
                        this.candles = [];
                        let p = this.lastPrice;
                        for (let i = 0; i < this.maxCandles; i++) {
                            let o = p; let c = o + (Math.random() - 0.5) * 40;
                            let h = Math.max(o, c) + Math.random() * 8; let l = Math.min(o, c) - Math.random() * 8;
                            this.candles.push({ o, h, l, c }); p = c;
                        }
                        this.lastPrice = p;
                    },

                    startInternalLoops() {
                        setInterval(() => {
                            if (this.gameOver) return; // Stop timer if game over

                            this.timer--;
                            if (this.phase === 'open' && this.timer <= 0) {
                                this.phase = 'locked';
                                this.timer = 10; // Changed: now 10 seconds for locked
                            }
                            else if (this.phase === 'locked' && this.timer <= 0) {
                                this.settle();
                                this.phase = 'open';
                                this.timer = 20; // Changed: now 20 seconds for open
                            }
                        }, 1000);

                        // Update price every 1 second (instead of 60 FPS)
                        setInterval(() => {
                            if (this.gameOver) return;

                            this.prevPrice = this.lastPrice;
                            // Reduced volatility: 0.1% - 0.3% per second
                            let volatility = this.phase === 'locked' ? 0.003 : 0.001;
                            let change = (Math.random() - 0.5) * 2 * this.lastPrice * volatility;
                            this.lastPrice += change;

                            let lc = this.candles[this.candles.length - 1];
                            lc.c = this.lastPrice;
                            if (this.lastPrice > lc.h) lc.h = this.lastPrice;
                            if (this.lastPrice < lc.l) lc.l = this.lastPrice;

                            this.draw();
                        }, 1000); // 1 second update

                        setInterval(() => {
                            let o = this.lastPrice; this.candles.push({ o, h: o, l: o, c: o });
                            if (this.candles.length > this.maxCandles) this.candles.shift();
                        }, 2000);
                        setInterval(() => this.generateOrderBook(), 1000);
                    },

                    // Removed renderLoop - now using setInterval in startInternalLoops

                    placeOrder(type) {
                        if (this.balance < this.betAmount) return;
                        this.balance -= this.betAmount; window.userBalance = this.balance;
                        this.myPosition = { type: type, entry: this.lastPrice, amount: this.betAmount };
                    },

                    settle() {
                        if (!this.myPosition) return;

                        let diff = this.lastPrice - this.myPosition.entry;
                        let win = (this.myPosition.type === 'buy' && diff > 0) || (this.myPosition.type === 'sell' && diff < 0);
                        this.lastWin = win;

                        // Calculate PnL
                        let pnl = 0;
                        if (win) {
                            pnl = this.myPosition.amount * 0.82;
                            this.lastPnL = pnl;
                            this.balance += this.myPosition.amount + pnl;
                        } else {
                            pnl = -this.myPosition.amount;
                            this.lastPnL = pnl;
                        }

                        // Track statistics
                        this.totalTrades++;
                        if (win) {
                            this.winningTrades++;
                            this.survivalStreak++;
                            if (this.survivalStreak > this.bestStreak) {
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
                        window.userBalance = this.balance;

                        // Save game state
                        this.saveGame();

                        // Check for game over
                        if (this.balance <= 0) {
                            this.gameOver = true;
                            this.showGameOverModal = true;
                            this.saveGame(); // Final save with game over state
                        }
                    },

                    // localStorage Methods
                    loadGame() {
                        try {
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

                    saveGame() {
                        try {
                            const data = {
                                balance: this.balance,
                                totalTrades: this.totalTrades,
                                winningTrades: this.winningTrades,
                                losingTrades: this.losingTrades,
                                bestStreak: this.bestStreak,
                                totalProfit: this.totalProfit,
                                tradeHistory: this.tradeHistory,
                                tutorialCompleted: this.tutorialCompleted || false,
                                lastSaved: new Date().toISOString()
                            };
                            localStorage.setItem('cryptoTradingPanic', JSON.stringify(data));
                        } catch (e) {
                            console.error('Error saving game:', e);
                        }
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
                        this.asks = Array.from({ length: 15 }, (_, i) => ({ id: 'a' + i, price: price + 0.5 + (i * 1.5) + Math.random(), amount: Math.random() }));
                        this.bids = Array.from({ length: 15 }, (_, i) => ({ id: 'b' + i, price: price - 0.5 - (i * 1.5) - Math.random(), amount: Math.random() }));

                        if (this.asks.length > 0 && this.bids.length > 0) {
                            let bestAsk = this.asks[0].price;
                            let bestBid = this.bids[0].price;
                            this.spreadPercent = (((bestAsk - bestBid) / bestAsk) * 100).toFixed(3);
                        }
                    },

                    draw() {
                        if (!this.ctx || !this.canvas) return;
                        const w = this.canvas.width / (window.devicePixelRatio || 1);
                        const h = this.canvas.height / (window.devicePixelRatio || 1);
                        const ctx = this.ctx;

                        ctx.clearRect(0, 0, w, h);

                        // Background
                        ctx.fillStyle = '#0b0e11'; ctx.fillRect(0, 0, w, h);

                        // Grid
                        ctx.strokeStyle = '#2b3139'; ctx.lineWidth = 1; ctx.beginPath();
                        for (let x = w % 80; x < w; x += 80) { ctx.moveTo(x, 0); ctx.lineTo(x, h); }
                        for (let y = h % 80; y < h; y += 80) { ctx.moveTo(0, y); ctx.lineTo(w, y); }
                        ctx.stroke();

                        let min = Math.min(...this.candles.map(c => c.l)) - 20;
                        let max = Math.max(...this.candles.map(c => c.h)) + 20;
                        let range = max - min; if (range < 1) range = 1;

                        // Draw
                        let unitW = w / this.maxCandles; let candleW = unitW * 0.7; let spacing = unitW * 0.3;

                        this.candles.forEach((c, i) => {
                            let isGreen = c.c >= c.o; let color = isGreen ? '#0ecb81' : '#f6465d';
                            let x = i * unitW + spacing / 2;
                            let yH = h - ((c.h - min) / range) * h; let yL = h - ((c.l - min) / range) * h;
                            let yO = h - ((c.o - min) / range) * h; let yC = h - ((c.c - min) / range) * h;

                            ctx.fillStyle = color; ctx.strokeStyle = color;
                            ctx.beginPath(); ctx.moveTo(x + candleW / 2, yH); ctx.lineTo(x + candleW / 2, yL); ctx.stroke();
                            let top = Math.min(yO, yC); let height = Math.abs(yO - yC); if (height < 1) height = 1;
                            ctx.fillRect(x, top, candleW, height);

                            // Vol
                            let volHeight = (Math.abs(c.c - c.o) / range) * h * 0.5 + 5;
                            ctx.globalAlpha = 0.2; ctx.fillRect(x, h - volHeight, candleW, volHeight); ctx.globalAlpha = 1.0;
                        });

                        // Price Line
                        let priceY = h - ((this.lastPrice - min) / range) * h;
                        ctx.strokeStyle = '#f0b90b'; ctx.setLineDash([2, 2]); ctx.beginPath(); ctx.moveTo(0, priceY); ctx.lineTo(w, priceY); ctx.stroke(); ctx.setLineDash([]);
                        ctx.fillStyle = '#f0b90b'; ctx.fillRect(w - 60, priceY - 9, 60, 18);
                        ctx.fillStyle = '#000'; ctx.font = 'bold 10px sans-serif'; ctx.fillText(this.lastPrice.toFixed(2), w - 55, priceY + 3);

                        // Crosshair
                        if (this.crosshair.visible) {
                            ctx.strokeStyle = '#fff'; ctx.setLineDash([4, 4]);
                            ctx.beginPath(); ctx.moveTo(0, this.crosshair.y); ctx.lineTo(w, this.crosshair.y);
                            ctx.moveTo(this.crosshair.x, 0); ctx.lineTo(this.crosshair.x, h); ctx.stroke();
                            this.crosshair.price = (min + ((h - this.crosshair.y) / h) * range).toFixed(2);
                        }
                    },
                    updateCrosshair(e) {
                        const r = this.canvas.getBoundingClientRect();
                        this.crosshair.x = e.clientX - r.left; this.crosshair.y = e.clientY - r.top; this.crosshair.visible = true;
                    },
                    hideCrosshair() { this.crosshair.visible = false; }
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
                        <h2 class="text-4xl font-black text-[#f6465d] mb-2 uppercase">GAME OVER</h2>
                        <p class="text-slate-400 mb-6">Balance habis! Survival streak berakhir.</p>

                        <div class="bg-[#14161b] rounded-xl p-4 mb-6 space-y-2 text-left">
                            <div class="flex justify-between">
                                <span class="text-slate-400 text-sm">Total Trades:</span>
                                <span class="text-white font-bold" x-text="window.game?.totalTrades || 0"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400 text-sm">Win Rate:</span>
                                <span class="text-emerald-400 font-bold"
                                    x-text="window.game?.totalTrades > 0 ? ((window.game.winningTrades / window.game.totalTrades) * 100).toFixed(1) + '%' : '0%'"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400 text-sm">Best Streak:</span>
                                <span class="text-[#f0b90b] font-bold" x-text="window.game?.bestStreak || 0"></span>
                            </div>
                            <div class="flex justify-between border-t border-[#2b3139] pt-2">
                                <span class="text-slate-400 text-sm">Total P&L:</span>
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
                        <div class="bg-[#181a20] px-6 py-4 border-b border-[#2b3139] flex justify-between items-center">
                            <div>
                                <h3 class="text-xl font-bold text-white">📚 Crypto Trading Panic Tutorial</h3>
                                <p class="text-xs text-slate-500 mt-1">Step <span
                                        x-text="(window.game?.tutorialStep || 0) + 1"></span> of 6</p>
                            </div>
                            <button
                                @click="window.game.showTutorial = false; window.game.tutorialCompleted = true; window.game.saveGame();"
                                class="text-slate-500 hover:text-white text-2xl font-bold">×</button>
                        </div>

                        <!-- Progress Bar -->
                        <div class="bg-[#0b0e11] h-1.5">
                            <div class="bg-gradient-to-r from-[#f0b90b] to-[#f8d12f] h-full transition-all"
                                :style="`width: ${(((window.game?.tutorialStep || 0) + 1) / 6) * 100}%`"></div>
                        </div>

                        <!-- Content -->
                        <div class="overflow-y-auto flex-1 p-6">
                            <div x-show="(window.game?.tutorialStep || 0) === 0" class="space-y-4">
                                <div class="text-center mb-6">
                                    <div class="text-6xl mb-4">🎮</div>
                                    <h2 class="text-3xl font-black text-white mb-2">Selamat Datang!</h2>
                                    <p class="text-slate-300">Game survival trading edukatif</p>
                                </div>
                                <div class="bg-[#14161b] p-5 rounded-xl space-y-3">
                                    <p class="text-white font-bold">⏱️ Mekanik Game:</p>
                                    <p class="text-slate-300 text-sm">• 20 detik Open Market</p>
                                    <p class="text-slate-300 text-sm">• 10 detik Locked Phase</p>
                                    <p class="text-slate-300 text-sm">• Balance $0 = GAME OVER</p>
                                </div>
                            </div>

                            <div x-show="(window.game?.tutorialStep || 0) === 1" class="space-y-4">
                                <div class="text-center mb-6">
                                    <div class="text-6xl mb-4">📊</div>
                                    <h2 class="text-3xl font-black text-white mb-2">Candlestick Chart</h2>
                                </div>
                                <div class="bg-[#14161b] p-5 rounded-xl space-y-3">
                                    <p class="text-emerald-400 font-bold">🟢 Candle Hijau = Harga NAIK</p>
                                    <p class="text-[#f6465d] font-bold">🔴 Candle Merah = Harga TURUN</p>
                                </div>
                            </div>

                            <div x-show="(window.game?.tutorialStep || 0) === 2" class="space-y-4">
                                <div class="text-center mb-6">
                                    <div class="text-6xl mb-4">⚔️</div>
                                    <h2 class="text-3xl font-black text-white mb-2">Long vs Short</h2>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-emerald-500/10 border-2 border-emerald-500/30 p-4 rounded-xl">
                                        <p class="text-emerald-400 font-bold mb-2">📈 LONG</p>
                                        <p class="text-slate-300 text-sm">Bet harga NAIK ⬆️</p>
                                    </div>
                                    <div class="bg-rose-500/10 border-2 border-rose-500/30 p-4 rounded-xl">
                                        <p class="text-[#f6465d] font-bold mb-2">📉 SHORT</p>
                                        <p class="text-slate-300 text-sm">Bet harga TURUN ⬇️</p>
                                    </div>
                                </div>
                            </div>

                            <div x-show="(window.game?.tutorialStep || 0) === 3" class="space-y-4">
                                <div class="text-center mb-6">
                                    <div class="text-6xl mb-4">🛡️</div>
                                    <h2 class="text-3xl font-black text-white mb-2">Risk Management</h2>
                                </div>
                                <div class="bg-[#f6465d]/10 border-2 border-[#f6465d] p-5 rounded-xl">
                                    <p class="text-[#f6465d] font-black text-2xl mb-2">🚨 JANGAN BET SEMUA!</p>
                                    <p class="text-slate-300 text-sm">Satu loss = Game Over</p>
                                </div>
                                <div class="bg-emerald-500/10 border border-emerald-500/30 p-4 rounded-xl">
                                    <p class="text-emerald-400 font-bold">✅ Bet 10-20% Balance</p>
                                    <p class="text-slate-300 text-sm">Agar bisa survive</p>
                                </div>
                            </div>

                            <div x-show="(window.game?.tutorialStep || 0) === 4" class="space-y-4">
                                <div class="text-center mb-6">
                                    <div class="text-6xl mb-4">📖</div>
                                    <h2 class="text-3xl font-black text-white mb-2">Order Book</h2>
                                </div>
                                <div class="bg-[#14161b] p-5 rounded-xl space-y-3">
                                    <p class="text-[#f6465d] font-bold">🔴 Asks = Sell Orders</p>
                                    <p class="text-emerald-400 font-bold">🟢 Bids = Buy Orders</p>
                                </div>
                            </div>

                            <div x-show="(window.game?.tutorialStep || 0) === 5" class="space-y-4">
                                <div class="text-center mb-6">
                                    <div class="text-6xl mb-4">🚀</div>
                                    <h2 class="text-3xl font-black text-white mb-2">Siap Trading!</h2>
                                </div>
                                <div class="bg-emerald-500/10 border border-emerald-500/30 p-4 rounded-xl">
                                    <p class="text-emerald-400 font-bold mb-2">Yang Sudah Dipelajari:</p>
                                    <p class="text-slate-300 text-sm">✅ Candlestick Chart</p>
                                    <p class="text-slate-300 text-sm">✅ Long vs Short</p>
                                    <p class="text-slate-300 text-sm">✅ Risk Management</p>
                                    <p class="text-slate-300 text-sm">✅ Order Book</p>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation -->
                        <div class="bg-[#181a20] px-6 py-4 border-t border-[#2b3139] flex justify-between items-center">
                            <button @click="if((window.game?.tutorialStep || 0) > 0) window.game.tutorialStep--"
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

</body>

</html>