<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Report Kegiatan</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
  :root{
    --bg: linear-gradient(135deg, #f0f4f8 0%, #e2e8f0 100%);
    --card:#ffffff;
    --border:#e2e8f0;
    --text:#1e293b;
    --muted:#64748b;
    --muted2:#94a3b8;

    --primary:#0369a1;
    --primary-light:#0ea5e9;
    --primary-dark:#075985;
    --primary-bg:#f0f9ff;
    --primary-border:#bae6fd;

    --accent:#f59e0b;
    --accent-light:#fbbf24;

    --danger-bg:#fef2f2;
    --danger-border:#fecaca;

    --success:#10b981;
    --success-bg:#ecfdf5;

    --focus:#0ea5e9;
    --focus-ring: rgba(14,165,233,.2);

    --btn:#0369a1;
    --btn-hover:#075985;
    --btn-gradient: linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%);

    --soft:#f8fafc;
    --shadow: 0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.06);
    --shadow-md: 0 4px 6px -1px rgba(0,0,0,.1), 0 2px 4px -1px rgba(0,0,0,.06);
    --shadow-lg: 0 10px 25px -5px rgba(0,0,0,.1), 0 8px 10px -6px rgba(0,0,0,.06);
    --shadow-hover: 0 20px 40px -10px rgba(3,105,161,.15);
    --radius: 12px;
  }

  *{box-sizing:border-box; margin:0; padding:0;}

  body{
    font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif;
    background: var(--bg);
    background-attachment: fixed;
    color:var(--text);
    min-height:100vh;
    padding:0;
  }

  /* Header Bar */
  .header-bar{
    background: linear-gradient(135deg, #0369a1 0%, #0c4a6e 100%);
    padding: 20px 24px;
    color: #fff;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: 0 4px 20px rgba(3,105,161,.3);
  }

  .header-inner{
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
  }

  .logo-section{
    display: flex;
    align-items: center;
    gap: 14px;
  }

  .logo-icon{
    width: 44px;
    height: 44px;
    background: rgba(255,255,255,.15);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    backdrop-filter: blur(10px);
  }

  .header-title{
    font-size: 20px;
    font-weight: 700;
    letter-spacing: -0.3px;
  }

  .header-subtitle{
    font-size: 12px;
    opacity: 0.8;
    font-weight: 500;
    margin-top: 2px;
  }

  .header-badge{
    background: rgba(255,255,255,.2);
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    backdrop-filter: blur(10px);
  }

  .main-content{
    max-width: 1200px;
    margin: 0 auto;
    padding: 28px 20px 40px;
  }

  .page-intro{
    margin-bottom: 24px;
  }

  .page-intro h1{
    font-size: 26px;
    font-weight: 800;
    color: var(--text);
    letter-spacing: -0.5px;
    margin-bottom: 6px;
  }

  .page-intro p{
    color: var(--muted);
    font-size: 14px;
    line-height: 1.6;
  }

  .card{
    background:var(--card);
    border:1px solid var(--border);
    border-radius: 16px;
    padding: 24px;
    box-shadow: var(--shadow);
    margin-bottom: 20px;
    transition: box-shadow .2s ease, transform .2s ease;
  }

  .card:hover{
    box-shadow: var(--shadow-md);
  }

  .card-header{
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 2px solid #f1f5f9;
  }

  .card-icon{
    width: 40px;
    height: 40px;
    background: var(--primary-bg);
    border: 1px solid var(--primary-border);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
  }

  .section-title{
    font-weight: 700;
    margin: 0;
    font-size: 16px;
    letter-spacing: -0.2px;
    color: var(--text);
  }

  .section-desc{
    font-size: 13px;
    color: var(--muted);
    margin-top: 2px;
  }

  .grid-2{display:grid;grid-template-columns:1fr;gap:16px}
  @media (min-width: 920px){ .grid-2{grid-template-columns:1fr 1fr} }

  .field{display:grid;gap:6px;margin-bottom:16px}

  label{
    font-weight: 600;
    font-size: 13px;
    color: var(--text);
    display: flex;
    align-items: center;
    gap: 6px;
  }

  label .req{
    color: #ef4444;
    font-size: 14px;
  }

  input, select, textarea{
    width:100%;
    padding: 12px 14px;
    border-radius: 10px;
    border: 1.5px solid var(--border);
    background:#fff;
    color:var(--text);
    outline:none;
    font-size: 14px;
    font-family: inherit;
    transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
  }

  input::placeholder, textarea::placeholder{color:var(--muted2)}
  select{color:var(--text); background:#fff; cursor: pointer;}

  input:hover, select:hover, textarea:hover{
    border-color: #cbd5e1;
    background: #fafbfc;
  }

  input:focus, select:focus, textarea:focus{
    border-color: var(--focus);
    box-shadow: 0 0 0 4px var(--focus-ring);
    background: #fff;
  }

  textarea{resize:vertical;min-height:86px}

  .muted{
    color:var(--muted);
    font-size:12px;
    line-height:1.5;
    margin-top:4px;
  }

  .err{
    background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    border: 1px solid #fecaca;
    padding: 16px 18px;
    border-radius: 12px;
    margin-bottom: 20px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
  }

  .err-icon{
    width: 24px;
    height: 24px;
    background: #ef4444;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 14px;
    flex-shrink: 0;
  }

  .err ul{margin:0;padding-left:16px;color:#991b1b;font-size:13px;line-height:1.7}

  /* Checklist Section */
  .checklist-header{
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
  }

  .rows{display:grid;gap:16px;margin-top:16px}

  .row{
    border: 1.5px solid var(--border);
    border-radius: 14px;
    padding: 18px;
    background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
    box-shadow: var(--shadow);
    transition: all .25s ease;
    position: relative;
    overflow: hidden;
  }

  .row::before{
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: linear-gradient(180deg, var(--primary-light) 0%, var(--primary) 100%);
    opacity: 0;
    transition: opacity .2s ease;
  }

  .row:hover{
    box-shadow: var(--shadow-lg);
    border-color: var(--primary-border);
    transform: translateY(-2px);
  }

  .row:hover::before{
    opacity: 1;
  }

  .row-head{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:10px;
    margin-bottom: 14px;
  }

  .row-title{
    font-weight: 700;
    color: var(--text);
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .row-num{
    width: 26px;
    height: 26px;
    background: var(--btn-gradient);
    color: #fff;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
  }

  .mini-btn{
    cursor:pointer;
    border: 1.5px solid #fecaca;
    background: #fef2f2;
    color: #dc2626;
    border-radius: 8px;
    padding: 6px 12px;
    font-size: 12px;
    font-weight: 600;
    font-family: inherit;
    transition: all .15s ease;
  }
  .mini-btn:hover{
    background: #fee2e2;
    border-color: #fca5a5;
  }

  .row-grid{
    display:grid;
    grid-template-columns: 1fr;
    gap: 16px;
    align-items:start;
  }

  @media (min-width: 980px){
    .row-grid{
      grid-template-columns: 1.3fr .7fr;
    }
    .row-right{
      align-self: stretch;
    }
  }

  .row-left{display:grid;gap:12px}

  .row-right{
    border: 2px dashed #e2e8f0;
    border-radius: 12px;
    padding: 16px;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    transition: border-color .2s ease, background .2s ease;
  }

  .row-right:hover{
    border-color: var(--primary-border);
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
  }

  .row-right label{
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 10px;
  }

  .upload-icon{
    font-size: 16px;
  }

  .two{
    display:grid;
    grid-template-columns: 1fr;
    gap:12px;
  }
  @media(min-width:720px){ .two{ grid-template-columns: 1fr 1fr; } }

  .btn-row{
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 2px solid #f1f5f9;
  }

  .btn{
    cursor:pointer;
    border:0;
    border-radius: 10px;
    padding: 14px 24px;
    font-weight: 700;
    font-size: 14px;
    font-family: inherit;
    color:#ffffff;
    background: var(--btn-gradient);
    box-shadow: 0 4px 14px rgba(3,105,161,.3);
    transition: all .2s ease;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .btn:hover{
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(3,105,161,.35);
  }
  .btn:active{
    transform: translateY(0);
  }

  .btn-secondary{
    background: #fff;
    color: var(--primary);
    border: 2px solid var(--primary);
    box-shadow: none;
  }

  .btn-secondary:hover{
    background: var(--primary-bg);
    box-shadow: 0 4px 14px rgba(3,105,161,.15);
  }

  .btn-add{
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    box-shadow: 0 4px 14px rgba(16,185,129,.3);
  }

  .btn-add:hover{
    box-shadow: 0 8px 25px rgba(16,185,129,.35);
  }

  .preview-grid{display:flex;flex-wrap:wrap;gap:10px;margin-top:12px}

  .preview{
    width: 120px;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    overflow:hidden;
    background:#fff;
    box-shadow: var(--shadow);
    transition: all .2s ease;
  }

  .preview:hover{
    transform: scale(1.02);
    box-shadow: var(--shadow-md);
  }

  .preview img{width:100%;height: 80px;object-fit: cover;display:block}

  .preview .meta{
    padding: 8px;
    display:grid;
    gap:6px;
    background:#fff;
  }

  .preview .meta input{
    padding: 6px 8px;
    border-radius: 6px;
    border: 1px solid var(--border);
    background:#fff;
    font-size: 11px;
  }

  .hint{font-size:11px;color:var(--muted);margin-top:6px}

  /* Footer */
  .page-footer{
    text-align: center;
    padding: 20px;
    color: var(--muted);
    font-size: 12px;
  }

  /* File input styling */
  input[type="file"]{
    padding: 10px;
    background: #fff;
    cursor: pointer;
  }

  input[type="file"]::file-selector-button{
    background: var(--btn-gradient);
    color: #fff;
    border: none;
    padding: 8px 14px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 12px;
    cursor: pointer;
    margin-right: 10px;
    transition: all .2s ease;
  }

  input[type="file"]::file-selector-button:hover{
    opacity: 0.9;
  }
  </style>
</head>

<body>

<!-- Header Bar -->
<div class="header-bar">
  <div class="header-inner">
    <div class="logo-section">
      <div class="logo-icon">RS</div>
      <div>
        <div class="header-title">Report System</div>
        <div class="header-subtitle">Sistem Pelaporan Kegiatan</div>
      </div>
    </div>
    <div class="header-badge">{{ now()->timezone('Asia/Jakarta')->format('d M Y') }}</div>
  </div>
</div>

<div class="main-content">
  <div class="page-intro">
    <h1>Form Report Kegiatan</h1>
  </div>

  @if ($errors->any())
    <div class="err">
      <div class="err-icon">!</div>
      <ul>
        @foreach ($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- INFO --}}
    <div class="card">
      <div class="card-header">
        <div class="card-icon">i</div>
        <div>
          <div class="section-title">Informasi Kegiatan</div>
        </div>
      </div>

      <div class="grid-2">
        <div>
          <div class="field">
            <label>Nama Kegiatan <span class="req">*</span></label>
            <input name="nama_kegiatan" value="{{ old('nama_kegiatan') }}" placeholder="Contoh: Maintenance Server Bulanan" required>
          </div>

          <div class="field">
            <label>Waktu Kegiatan <span class="req">*</span></label>
            <input name="waktu_kegiatan" value="{{ old('waktu_kegiatan') }}" placeholder="Contoh: 12 Januari 2026, 09:00 WIB" required>
          </div>
        </div>

        <div>
          <div class="field">
            <label>Jenis Kegiatan <span class="req">*</span></label>
            <input name="jenis_kegiatan" value="{{ old('jenis_kegiatan') }}" placeholder="Contoh: Preventive Maintenance" required>
          </div>

          <div class="field">
            <label>Lokasi Kegiatan <span class="req">*</span></label>
            <input name="lokasi_kegiatan" value="{{ old('lokasi_kegiatan') }}" placeholder="Contoh: Data Center Lt. 2" required>
          </div>
        </div>
      </div>
    </div>

    {{-- CHECKLIST + FOTO PER ITEM --}}
    <div class="card">
      <div class="card-header" style="border-bottom: none; margin-bottom: 0; padding-bottom: 0;">
        <div class="card-icon">✓</div>
        <div style="flex:1">
          <div class="checklist-header">
            <div>
              <div class="section-title">Checklist Kondisi</div>
            </div>
            <button type="button" id="addRowBtn" class="btn btn-add">+ Tambah Item</button>
          </div>
        </div>
      </div>

      <div class="rows" id="rows">
       
        @php $initial = old('items') ?? array_fill(0, 4, ['deskripsi'=>'','kondisi'=>'','catatan'=>'']); @endphp

        @foreach($initial as $idx => $row)
          <div class="row" data-row="{{ $idx }}">
            <div class="row-head">
              <div class="row-title">
                <span class="row-num"><span class="rowNo">{{ $idx+1 }}</span></span>
                Item Checklist
              </div>
              <button type="button" class="mini-btn removeRowBtn">Hapus</button>
            </div>

            <div class="row-grid">
            
              <div class="row-left">
                <div class="field">
                  <label>Deskripsi</label>
              
                  <input name="items[{{ $idx }}][deskripsi]"
                         value="{{ old("items.$idx.deskripsi", $row['deskripsi'] ?? '') }}"
                         placeholder="Jelaskan item yang dicek...">
                </div>

                <div class="two">
                  <div class="field">
                    <label>Kondisi</label>
                    <select name="items[{{ $idx }}][kondisi]">
                      <option value="">-- Pilih Kondisi --</option>
                      <option value="baik" {{ old("items.$idx.kondisi", $row['kondisi'] ?? '')=='baik'?'selected':'' }}>Baik</option>
                      <option value="problem" {{ old("items.$idx.kondisi", $row['kondisi'] ?? '')=='problem'?'selected':'' }}>Problem</option>
                    </select>
                  </div>

                  <div class="field">
                    <label>Catatan</label>
                    <input name="items[{{ $idx }}][catatan]"
                           value="{{ old("items.$idx.catatan", $row['catatan'] ?? '') }}"
                           placeholder="Catatan tambahan...">
                  </div>
                </div>
              </div>

              
              <div class="row-right">
                <label>Dokumentasi Foto</label>
                <input class="photosInput" type="file" name="item_photos[{{ $idx }}][]" multiple accept="image/*">
                <div class="preview-grid previews"></div>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <div class="btn-row">
        <button class="btn" type="submit">Submit Report</button>
      </div>
    </div>
  </form>

  <div class="page-footer">
    &copy; {{ date('Y') }} Report System — Sistem Pelaporan Kegiatan
  </div>
</div>

<script>
  const rowsEl = document.getElementById('rows');
  const addBtn = document.getElementById('addRowBtn');

  function renumber() {
    [...rowsEl.querySelectorAll('.row')].forEach((row, i) => {
      row.dataset.row = i;
      row.querySelectorAll('.rowNo, .rowNo2').forEach(el => el.textContent = (i+1));

      
      row.querySelectorAll('input, select, textarea').forEach(inp => {
        const name = inp.getAttribute('name');
        if (!name) return;

        inp.setAttribute('name', name
          .replace(/items\[\d+\]/g, `items[${i}]`)
          .replace(/item_photos\[\d+\]/g, `item_photos[${i}]`)
          .replace(/photo_captions\[\d+\]\[\d+\]/g, (m) => m.replace(/\[\d+\]/, `[${i}]`))
          .replace(/photo_sections\[\d+\]\[\d+\]/g, (m) => m.replace(/\[\d+\]/, `[${i}]`))
        );
      });
    });
  }

  function makePreviewCard(fileUrl, rowIndex, photoIndex) {
    const div = document.createElement('div');
    div.className = 'preview';
    div.innerHTML = `
      <img src="${fileUrl}" alt="preview">
      <div class="meta">
        <input name="photo_captions[${rowIndex}][${photoIndex}]" placeholder="Caption foto...">
      </div>
    `;
    return div;
  }

  function hookRow(row) {
    
    row.querySelector('.removeRowBtn').addEventListener('click', () => {
      row.remove();
      renumber();
    });

    
    const input = row.querySelector('.photosInput');
    const previews = row.querySelector('.previews');

    input.addEventListener('change', () => {
      const files = [...(input.files || [])];
      previews.innerHTML = '';
      if (!files.length) return;

      const rowIndex = [...rowsEl.querySelectorAll('.row')].indexOf(row);

      files.forEach((f, photoIndex) => {
        const url = URL.createObjectURL(f);
        previews.appendChild(makePreviewCard(url, rowIndex, photoIndex));
      });
    });
  }


  [...rowsEl.querySelectorAll('.row')].forEach(hookRow);

 
  document.querySelector('form').addEventListener('submit', () => {
    const rows = [...document.querySelectorAll('#rows .row')];

    rows.forEach(row => {
      const des = row.querySelector('input[name*="[deskripsi]"]')?.value?.trim() || '';
      const kon = row.querySelector('select[name*="[kondisi]"]')?.value?.trim() || '';
      const cat = row.querySelector('input[name*="[catatan]"]')?.value?.trim() || '';
      const fileInput = row.querySelector('.photosInput');
      const hasFiles = fileInput && fileInput.files && fileInput.files.length > 0;

      const isEmpty = !des && !kon && !cat && !hasFiles;

      if (isEmpty) {
        row.querySelectorAll('input, select, textarea').forEach(el => el.disabled = true);
      }
    });
  });

  addBtn.addEventListener('click', () => {
    const idx = rowsEl.querySelectorAll('.row').length;

    const row = document.createElement('div');
    row.className = 'row';
    row.dataset.row = idx;
    row.innerHTML = `
      <div class="row-head">
        <div class="row-title">
          <span class="row-num"><span class="rowNo">${idx+1}</span></span>
          Item Checklist
        </div>
        <button type="button" class="mini-btn removeRowBtn">Hapus</button>
      </div>

      <div class="row-grid">
        <div class="row-left">
          <div class="field">
            <label>Deskripsi</label>
           
            <input name="items[${idx}][deskripsi]" value="" placeholder="Jelaskan item yang dicek...">
          </div>

          <div class="two">
            <div class="field">
              <label>Kondisi</label>
              <select name="items[${idx}][kondisi]">
                <option value="">-- Pilih Kondisi --</option>
                <option value="baik">Baik</option>
                <option value="problem">Problem</option>
              </select>
            </div>

            <div class="field">
              <label>Catatan</label>
              <input name="items[${idx}][catatan]" value="" placeholder="Catatan tambahan...">
            </div>
          </div>
        </div>

        <div class="row-right">
          <label>Dokumentasi Foto</label>
          <input class="photosInput" type="file" name="item_photos[${idx}][]" multiple accept="image/*">
          <div class="preview-grid previews"></div>
        </div>
      </div>
    `;

    rowsEl.appendChild(row);
    hookRow(row);
    renumber();
  });
</script>
</body>
</html>
