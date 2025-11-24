<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

    <title>Web-GIS with Geoserver and Leaflet</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #ffe3f1;
        }

        #map {
            width: 100%;
            height: 100vh;
            border: 6px solid #ff9ccc;
            box-sizing: border-box;
        }

        /* Pink controls */
        .leaflet-control-layers {
            background: #ffdaf0 !important;
            border: 2px solid #ff8fcf !important;
            border-radius: 8px !important;
            box-shadow: 0 0 10px rgba(255, 100, 180, 0.3);
        }

        /* Legend Kecamatan */
        .legend-box {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #ffe6f5;
            padding: 12px 15px;
            border-radius: 10px;
            border: 2px solid #ff8fcf;
            width: 180px;
            max-height: 300px;
            overflow-y: auto;
            box-shadow: 0 0 10px rgba(255, 100, 180, 0.3);
            font-family: Arial, sans-serif;

            /* Agar tampil di atas peta, tapi di bawah kontrol Leaflet */
            z-index: 500;
        }


        /* FIX AGAR CONTROLS LEAFLET TIDAK MENUTUPI LEGEND */
        .leaflet-control-container {
            z-index: 1 !important;
        }

        .legend-box h4 {
            margin: 0;
            margin-bottom: 10px;
            color: #d81b77;
            font-size: 15px;
            font-weight: bold;
        }

        .legend-content div {
            display: flex;
            align-items: center;
            margin-bottom: 6px;
            font-size: 13px;
        }

        .legend-content .color {
            width: 16px;
            height: 16px;
            border: 2px solid #00000033;
            margin-right: 8px;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div id="map"></div>

    <!-- LEGEND PETA -->
    <!-- LEGEND KECAMATAN DENGAN WARNA -->
    <div class="legend-box">
        <h4>Legenda Kecamatan</h4>
        <div class="legend-content">
            <div><span class="color" style="background:#f1eef6;"></span> Banguntapan</div>
            <div><span class="color" style="background:#ede6f2;"></span> Berbah</div>
            <div><span class="color" style="background:#eadeed;"></span> Cangkringan</div>
            <div><span class="color" style="background:#e6d6e9;"></span> Danurejan</div>
            <div><span class="color" style="background:#e2cde5;"></span> Dlingo</div>
            <div><span class="color" style="background:#dec5e1;"></span> Gamping</div>
            <div><span class="color" style="background:#dbbddc;"></span> Gantiwarno</div>
            <div><span class="color" style="background:#d7b5d8;"></span> Goden</div>
            <div><span class="color" style="background:#d8aad2;"></span> Gondokusuman</div>
            <div><span class="color" style="background:#d99ecd;"></span> Kalasan</div>
            <div><span class="color" style="background:#da93c7;"></span> Kalibawang</div>
            <div><span class="color" style="background:#dc87c1;"></span> Mantrijeron</div>
            <div><span class="color" style="background:#dd7cbb;"></span> Minggir</div>
            <div><span class="color" style="background:#de70b6;"></span> Mlati</div>
            <div><span class="color" style="background:#df65b0;"></span> Moyudan</div>
            <div><span class="color" style="background:#df5ba8;"></span> Nanggulan</div>
            <div><span class="color" style="background:#de50a0;"></span> Ngaglik</div>
            <div><span class="color" style="background:#de4698;"></span> Ngluwar</div>
            <div><span class="color" style="background:#de3b8f;"></span> Pinyungan</div>
            <div><span class="color" style="background:#de3187;"></span> Prambanan</div>
            <div><span class="color" style="background:#dd267f;"></span> Sedayu</div>
            <div><span class="color" style="background:#dd1c77;"></span> Selo</div>
            <div><span class="color" style="background:#d31870;"></span> Sentolo</div>
            <div><span class="color" style="background:#c91468;"></span> Seyegan</div>
            <div><span class="color" style="background:#bf1061;"></span> Srumbung</div>
            <div><span class="color" style="background:#b60c59;"></span> Tegalrejo</div>
            <div><span class="color" style="background:#ac0852;"></span> Tempel</div>
            <div><span class="color" style="background:#a2044a;"></span> Umbulharjo</div>
        </div>

        <!-- LEGENDA TAMBAHAN UNTUK GARIS & TITIK -->
        <br>
        <div style="font-weight:bold; margin-top:10px; color:#d81b77;">Legenda Garis</div>

        <div style="display:flex; align-items:center; margin-bottom:6px;">
            <span style="
        width:30px;
        height:4px;
        background:#007bff;   /* BIRU */
        border-radius:2px;
        display:inline-block;
        margin-right:8px;
    "></span> Sungai
        </div>

        <div style="display:flex; align-items:center; margin-bottom:6px;">
            <span style="
        width:30px;
        height:4px;
        background:#ff0000;   /* MERAH */
        border-radius:2px;
        display:inline-block;
        margin-right:8px;
    "></span> Jalan
        </div>


        <br>
        <div style="font-weight:bold; margin-top:10px; color:#d81b77;">Legenda Titik</div>
        <div style="display:flex; align-items:center; margin-bottom:6px;">
            <span style="
        width:14px;
        height:14px;
        background:#ff2e98;
        border:2px solid white;
        border-radius:50%;
        display:inline-block;
        margin-right:8px;
    "></span> Titik Lokasi
        </div>

    </div>


    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <script>
        var map = L.map("map").setView([-7.732521, 110.402376], 11);

        var osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            maxZoom: 19,
            attribution: "© OpenStreetMap contributors",
        }).addTo(map);

        var desa = L.tileLayer.wms("http://localhost:8080/geoserver/pgweb/wms", {
            layers: "pgweb:ADMINISTRASIDESA_AR_25K",
            format: "image/png",
            transparent: true
        }).addTo(map);

        var jalan = L.tileLayer.wms("http://localhost:8080/geoserver/pgweb/wms", {
            layers: "pgweb:JALAN_LN_25K",
            format: "image/png",
            transparent: true
        }).addTo(map);

        var sungai = L.tileLayer.wms("http://localhost:8080/geoserver/pgweb/wms", {
            layers: "pgweb:SUNGAI_LN_25K",
            format: "image/png",
            transparent: true
        }).addTo(map);

        /* LAYER KECAMATAN SLEMAN */
        var kecamatan = L.tileLayer.wms("http://localhost:8080/geoserver/pgweb/wms", {
            layers: "pgweb:penduduk_sleman_view",
            format: "image/png",
            transparent: true
        }).addTo(map);

        var overlayLayers = {
            "Administrasi Desa": desa,
            "Jalan": jalan,
            "Sungai": sungai,
            "Kecamatan Sleman": kecamatan
        };

        L.control.layers(null, overlayLayers).addTo(map);
    </script>
</body>

</html>