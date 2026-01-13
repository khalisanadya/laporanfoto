<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Kegiatan</title>

  <style>
    @page { margin: 25px 25px; }

    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 12px;
      color: #1e293b;
      line-height: 1.5;
    }

    /* Header */
    .header-box {
      background-color: #0369a1;
      color: #ffffff;
      padding: 20px 25px;
      margin: -25px -25px 30px -25px;
      text-align: center;
      border-bottom: 4px solid #0c4a6e;
    }

    .header-title {
      font-size: 24px;
      font-weight: 800;
      letter-spacing: 2px;
      margin-bottom: 6px;
    }

    .header-subtitle {
      font-size: 12px;
      opacity: 0.9;
    }

    /* Tables */
    table { width: 100%; border-collapse: collapse; }

    .info-section {
      margin-bottom: 28px;
    }

    .info-title {
      font-size: 15px;
      font-weight: 800;
      color: #0369a1;
      margin-bottom: 12px;
      padding-bottom: 8px;
      border-bottom: 3px solid #0369a1;
    }

    .info td {
      border: 1px solid #cbd5e1;
      padding: 12px 14px;
      vertical-align: top;
      font-size: 12px;
    }
    .info td:first-child {
      width: 30%;
      font-weight: 700;
      background-color: #e0f2fe;
      color: #0c4a6e;
    }
    .info td:last-child {
      background-color: #ffffff;
    }

    .section { margin-top: 30px; } 
    .section-title {
      font-weight: 800;
      font-size: 15px;
      margin-bottom: 14px;
      color: #0369a1;
      padding-bottom: 8px;
      border-bottom: 3px solid #0369a1;
    }

    .check th, .check td { 
      border: 1px solid #cbd5e1; 
      padding: 12px 10px; 
      font-size: 12px;
    }
    .check th {
      background-color: #0369a1;
      color: #ffffff;
      font-weight: 700;
      text-align: center;
      font-size: 12px;
    }
    .check td { 
      vertical-align: middle; 
      background-color: #ffffff;
    }
    .check tr:nth-child(even) td {
      background-color: #f8fafc;
    }

    .center { text-align: center; }
    .no { 
      width: 40px; 
      text-align: center; 
      font-weight: 700;
      color: #0369a1;
    }
    
    .desc { width: 35%; }
    .cond { 
      width: 80px; 
      text-align: center; 
      font-size: 16px; 
    } 
    .note { width: 38%; font-size: 12px; } 

    .cond-baik {
      color: #059669;
      font-weight: 800;
    }
    .cond-problem {
      color: #dc2626;
      font-weight: 800;
    }

    .footer { 
      margin-top: 35px; 
      font-size: 10px; 
      color: #64748b; 
      text-align: right;
      padding-top: 14px;
      border-top: 1px solid #e2e8f0;
    }

    /* FOTO */
    .photo-section-title {
      font-weight: 800;
      font-size: 15px;
      margin-bottom: 14px;
      color: #0369a1;
      padding-bottom: 8px;
      border-bottom: 3px solid #0369a1;
    }

    .photo-grid { width: 100%; border-collapse: collapse; margin-top: 12px; }
    .photo-cell {
      border: 1px solid #cbd5e1;
      padding: 14px;
      vertical-align: top;
      background-color: #ffffff;
    }

    .photo-wrap{
      height: 220px;
      display: table;
      width: 100%;
      background-color: #f8fafc;
      border-radius: 4px;
    }
    .photo-wrap-inner{
      display: table-cell;
      vertical-align: middle;
      text-align: center;
    }

    .photo-img{
      max-width: 100%;
      max-height: 200px;
      width: auto;
      height: auto;
      display: inline-block;
      border-radius: 4px;
    }

    .cap {
      margin-top: 10px;
      font-size: 11px;
      font-weight: 600;
      text-align: center;
      color: #475569;
      padding: 8px;
      background-color: #f1f5f9;
      border-radius: 4px;
    }

    .badge {
      display: inline-block;
      padding: 3px 8px;
      border-radius: 4px;
      font-size: 10px;
      font-weight: 700;
    }
    .badge-info {
      background-color: #dbeafe;
      color: #1e40af;
    }
  </style>
</head>

<body>

  {{-- HEADER --}}
  <div class="header-box">
    <div class="header-title">REPORT KEGIATAN</div>
    <div class="header-subtitle">Sistem Pelaporan Kegiatan</div>
  </div>

  {{-- INFO --}}
  <div class="info-section">
    <div class="info-title">Informasi Kegiatan</div>
    <table class="info">
      <tr>
        <td>Nama Kegiatan</td>
        <td>{{ $report->nama_kegiatan ?? '-' }}</td>
      </tr>
      <tr>
        <td>Waktu Kegiatan</td>
        <td>{{ $report->waktu_kegiatan ?? '-' }}</td>
      </tr>
      <tr>
        <td>Jenis Kegiatan</td>
        <td>{{ $report->jenis_kegiatan ?? '-' }}</td>
      </tr>
      <tr>
        <td>Lokasi Kegiatan</td>
        <td>{{ $report->lokasi_kegiatan ?? '-' }}</td>
      </tr>
    </table>
  </div>

  {{-- CHECKLIST --}}
  <div class="section">
    <div class="section-title">Checklist Kondisi</div>

    <table class="check">
      <thead>
        <tr>
          <th rowspan="2" class="no">No</th>
          <th rowspan="2">Deskripsi</th>
          <th colspan="2">Kondisi</th>
          <th rowspan="2">Catatan</th>
        </tr>
        <tr>
          <th class="cond">Baik</th>
          <th class="cond">Problem</th>
        </tr>
      </thead>

      <tbody>
        @forelse($report->items as $item)
          <tr>
            <td class="no">{{ $item->no }}</td>
            <td class="desc">{{ $item->deskripsi ?? '-' }}</td>

            <td class="cond center">{{ $item->kondisi === 'baik' ? '√' : '' }}</td>
            <td class="cond center">{{ $item->kondisi === 'problem' ? '√' : '' }}</td>

            <td class="note">{{ $item->catatan ?? '-' }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="center" style="padding: 20px; color: #64748b;">Tidak ada checklist.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- DOKUMENTASI --}}
  @if($report->photos && $report->photos->count())
  
    <div class="section">
      <div class="photo-section-title">Dokumentasi</div>

      <table class="photo-grid">
        @foreach($report->photos->chunk(2) as $row)
          <tr>
            @foreach($row as $photo)
              <td class="photo-cell" style="width:50%;">
                <div class="photo-wrap">
                  <div class="photo-wrap-inner">
                    @php
                      $imgSrc = isset($forPdf) && $forPdf 
                        ? public_path('storage/'.$photo->photo_path) 
                        : asset('storage/'.$photo->photo_path);
                    @endphp
                    <img
                      class="photo-img"
                      src="{{ $imgSrc }}"
                      alt="foto"
                    >
                  </div>
                </div>

                <div class="cap">
                  {{ $photo->caption ?? 'Dokumentasi' }}
                </div>
              </td>
            @endforeach

            @if($row->count() === 1)
              <td class="photo-cell" style="width:50%; border:0; background: transparent;"></td>
            @endif
          </tr>
        @endforeach
      </table>
    </div>
  @endif

  <div class="footer">
    Generated: {{ $downloadedAt ?? now()->timezone('Asia/Jakarta')->format('d M Y H:i').' WIB' }} | Report System
  </div>

</body>
</html> 