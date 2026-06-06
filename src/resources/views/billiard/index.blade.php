<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan Meja Billiard</title>
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #0f172a;
            color: #e5e7eb;
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 32px 20px;
        }

        .hero { margin-bottom: 28px; }
        .hero h1 { margin: 0 0 8px; font-size: 36px; }
        .hero p { color: #cbd5e1; margin: 0; line-height: 1.6; }

        .floor-card {
            background: linear-gradient(180deg, #0f172a 0%, #111827 100%);
            border: 1px solid #334155;
            border-radius: 22px;
            padding: 24px;
            margin-bottom: 28px;
            box-shadow: 0 12px 30px rgba(0,0,0,.18);
        }

        .floor-head {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: start;
            flex-wrap: wrap;
        }

        .floor-title {
            margin: 0 0 8px;
            font-size: 28px;
        }

        .muted {
            color: #cbd5e1;
            line-height: 1.6;
            margin: 0;
        }

        .badge {
            background: #1e293b;
            color: #e2e8f0;
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 13px;
            border: 1px solid #475569;
        }

        .spec-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
            margin: 20px 0;
        }

        .spec-box {
            background: #020617;
            border: 1px solid #1e293b;
            border-radius: 16px;
            padding: 18px;
            color: #cbd5e1;
        }

        .spec-box strong {
            display: block;
            color: #f8fafc;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .spec-box p {
            margin: 0;
            line-height: 1.7;
        }

        .table-link {
            text-decoration: none;
            color: inherit;
        }

        .map-wrapper {
            margin-top: 18px;
            background: #020617;
            border: 2px dashed #475569;
            border-radius: 20px;
            padding: 18px;
        }

        .map-title {
            color: #e2e8f0;
            font-size: 17px;
            margin-bottom: 16px;
        }

        /* =========================================================
        LANTAI 1 & 2 - REGULAR MAP
        ========================================================= */
        .regular-map {
            position: relative;
            width: 100%;
            min-height: 650px;
            background: #e5e7eb;
            border: 8px solid #1e293b;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: inset 0 0 0 4px #cbd5e1;
        }

        /* Pintu khusus lantai 1 */
        .entry-door {
            position: absolute;
            left: -8px;
            top: 26px;
            width: 120px;
            height: 250px;
            z-index: 5;
            pointer-events: none;
        }

        /* celah pintu / bukaan tembok */
        .entry-door .door-gap {
            position: absolute;
            left: 0;
            top: 34px;
            width: 18px;
            height: 150px;
            background: #e5e7eb;
            z-index: 2;
        }

        /* daun pintu atas */
        .entry-door .door-top {
            position: absolute;
            left: 0;
            top: 34px;
            width: 105px;
            height: 5px;
            background: #111827;
            transform: rotate(-34deg);
            transform-origin: left center;
            border-radius: 999px;
        }

        /* daun pintu bawah */
        .entry-door .door-bottom {
            position: absolute;
            left: 0;
            top: 154px;
            width: 105px;
            height: 5px;
            background: #111827;
            transform: rotate(58deg);
            transform-origin: left center;
            border-radius: 999px;
        }

        .cashier {
            position: absolute;
            left: 18px;
            bottom: 18px;
            width: 132px;
            height: 86px;
            border: 5px solid #0f172a;
            background: linear-gradient(180deg, #ffffff 0%, #f1f5f9 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            box-shadow: 0 8px 20px rgba(15, 23, 42, .18);
            z-index: 3;
        }

        .map-table {
            position: absolute;
            width: 300px;
            height: 170px;
            display: block;
            transition: .2s ease;
            z-index: 4;
        }

        .map-table:hover {
            transform: translateY(-4px) scale(1.02);
        }

        .pool-table {
            position: relative;
            width: 100%;
            height: 100%;
            border-radius: 22px;
            background: linear-gradient(180deg, #8b4513 0%, #6b3410 100%);
            padding: 12px;
            box-shadow:
                0 14px 24px rgba(0,0,0,.22),
                inset 0 2px 0 rgba(255,255,255,.12);
        }

        .pool-felt {
            width: 100%;
            height: 100%;
            border-radius: 16px;
            background: linear-gradient(180deg, #1f8a43 0%, #166534 100%);
            border: 4px solid #34d399;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #f8fafc;
            padding: 10px;
            box-shadow: inset 0 0 18px rgba(255,255,255,.08);
        }

        .pool-felt strong {
            display: block;
            font-size: 22px;
            margin-bottom: 6px;
        }

        .pool-felt small {
            font-size: 16px;
            color: #dcfce7;
        }

        .pocket {
            position: absolute;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #111827;
            box-shadow: inset 0 2px 3px rgba(255,255,255,.08);
        }

        .pocket.tl { top: 8px; left: 8px; }
        .pocket.tc { top: 8px; left: calc(50% - 9px); }
        .pocket.tr { top: 8px; right: 8px; }
        .pocket.bl { bottom: 8px; left: 8px; }
        .pocket.bc { bottom: 8px; left: calc(50% - 9px); }
        .pocket.br { bottom: 8px; right: 8px; }

        /* posisi meja lantai 1 dan 2 */
        .regular-map .table-1 { top: 80px; left: 320px; }
        .regular-map .table-2 { top: 80px; right: 80px; }
        .regular-map .table-3 { bottom: 80px; left: 320px; }
        .regular-map .table-4 { bottom: 80px; right: 70px; }

        /* =========================================================
        LANTAI 3 - VIP MAP
        ========================================================= */
        .vip-map {
            position: relative;
            width: 100%;
            min-height: 720px;
            background: #e5e7eb;
            border: 8px solid #1e293b;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: inset 0 0 0 4px #cbd5e1;
        }

        .vip-sidebar {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 150px;
            border-right: 6px solid #1f2937;
            background: #e5e7eb;
        }

        /* koridor tengah */
        .vip-corridor {
            position: absolute;
            left: 150px;
            right: 0;
            top: calc(50% - 60px);
            height: 120px;
            border-top: 6px solid #1f2937;
            border-bottom: 6px solid #1f2937;
            background: #e5e7eb;
            z-index: 1;
        }

        /* garis pembatas atas: antara VIP 1 dan VIP 2 */
        .vip-divider-top {
            position: absolute;
            top: 210px;
            left: 150px;
            right: 0;
            height: 6px;
            background: #1f2937;
            z-index: 2;
        }

        /* garis pembatas bawah: antara VIP 3 dan VIP 4 */
        .vip-divider-bottom {
            position: absolute;
            bottom: 210px;
            left: 150px;
            right: 0;
            height: 6px;
            background: #1f2937;
            z-index: 2;
        }

        .vip-room-link {
            position: absolute;
            width: 350px;
            height: 190px;
            background: #f8fafc;
            border: 6px solid #1f2937;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: .2s ease;
            z-index: 3;
            box-shadow: 0 12px 24px rgba(0,0,0,.12);
        }

        .vip-room-link:hover {
            transform: translateY(-4px) scale(1.02);
            background: #eef2ff;
        }

        .vip-room-label {
            position: absolute;
            top: 14px;
            left: 16px;
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
        }

        .vip-room-link .pool-table {
            width: 180px;
            height: 105px;
            padding: 8px;
            border-radius: 18px;
        }

        .vip-room-link .pool-felt {
            border-radius: 12px;
            border-width: 3px;
        }

        /* KHUSUS VIP: hilangkan pocket / bulatan hitam */
        .vip-room-link .pocket {
            display: none;
        }

        /* posisi ruangan */
        .vip-room-link.table-1 { top: 60px; left: 210px; }
        .vip-room-link.table-2 { top: 60px; right: 90px; }
        .vip-room-link.table-3 { bottom: 60px; left: 210px; }
        .vip-room-link.table-4 { bottom: 60px; right: 90px; }

        @media (max-width: 1100px) {
            .regular-map,
            .vip-map {
                min-height: auto;
                padding: 20px;
                display: grid;
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .regular-map::before,
            .regular-map::after,
            .vip-sidebar,
            .vip-corridor,
            .door-line,
            .cashier {
                display: none;
            }

            .map-table,
            .vip-room-link {
                position: static;
                width: 100%;
                height: auto;
            }

            .pool-table {
                height: 160px;
            }

            .vip-room-link {
                padding: 20px 16px;
            }

            .vip-room-link .pool-table {
                width: 100%;
                height: 140px;
            }

            .vip-room-label {
                position: static;
                margin-bottom: 10px;
            }

            .spec-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="hero">
        <h1>Pemesanan Meja Billiard</h1>
        <p>Pilih lantai, klik meja/ruangan yang diinginkan, lalu cek jadwal booking per jam.</p>
    </div>

    @foreach ($floors as $floor)
        <section class="floor-card" id="lantai-{{ $floor->floor_number }}">
            <div class="floor-head">
                <div>
                    <h2 class="floor-title">{{ $floor->name }}</h2>
                    <p class="muted">{{ $floor->description }}</p>
                </div>
                @if ($floor->is_vip)
                    <span class="badge">VIP Area</span>
                @else
                    <span class="badge">Regular Area</span>
                @endif
            </div>

            <div class="spec-grid">
                <div class="spec-box">
                    <strong>Spesifikasi Meja</strong>
                    <p>{{ $floor->table_specification }}</p>
                </div>
                <div class="spec-box">
                    <strong>Spesifikasi Stick</strong>
                    <p>{{ $floor->cue_specification }}</p>
                </div>
            </div>

            @if ($floor->is_vip)
                <div class="map-wrapper">
                    <div class="map-title">
                        Gambaran: 4 ruangan VIP terpisah, masing-masing berisi 1 meja billiard
                    </div>

                    <div class="vip-map">
                        <div class="vip-sidebar"></div>
                        <div class="vip-corridor"></div>

                        <div class="door-line d1"></div>
                        <div class="door-line d2"></div>
                        <div class="door-line d3"></div>
                        <div class="door-line d4"></div>

                        @foreach ($floor->billiardTables as $table)
                            <a class="table-link vip-room-link table-{{ $loop->iteration }}"
                            href="{{ route('billiard.table', $table) }}">
                                <div class="vip-room-label">
                                    {{ $table->room_name ?: 'Ruangan VIP ' . $loop->iteration }}
                                </div>

                                <div class="pool-table">
                                    <span class="pocket tl"></span>
                                    <span class="pocket tc"></span>
                                    <span class="pocket tr"></span>
                                    <span class="pocket bl"></span>
                                    <span class="pocket bc"></span>
                                    <span class="pocket br"></span>

                                    <div class="pool-felt">
                                        <strong>{{ $table->name }}</strong>
                                        <small>Klik untuk lihat jadwal</small>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="map-wrapper">
                    <div class="map-title">
                        @if ($floor->floor_number == 1)
                            Gambaran: 1 ruangan besar berisi 4 meja billiard dan area kasir
                        @else
                            Gambaran: 1 ruangan besar berisi 4 meja billiard
                        @endif
                    </div>

                    <div class="regular-map {{ $floor->floor_number == 1 ? 'has-entry' : '' }}">
                        @if ($floor->floor_number == 1)
                            <div class="cashier">Kasir</div>
                        @endif

                        @foreach ($floor->billiardTables as $table)
                            <a class="table-link map-table table-{{ $loop->iteration }}"
                            href="{{ route('billiard.table', $table) }}">
                                <div class="pool-table">
                                    <span class="pocket tl"></span>
                                    <span class="pocket tc"></span>
                                    <span class="pocket tr"></span>
                                    <span class="pocket bl"></span>
                                    <span class="pocket bc"></span>
                                    <span class="pocket br"></span>

                                    <div class="pool-felt">
                                        <strong>{{ $table->name }}</strong>
                                        <small>{{ $table->position_label }}</small>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </section>
    @endforeach
</div>
</body>
</html>
