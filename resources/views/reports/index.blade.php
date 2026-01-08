<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Buat Report</title>

  <style>
  :root{
    --bg:#f9fafb;
    --card:#ffffff;
    --border:#e5e7eb;
    --text:#111827;
    --muted:#6b7280;
    --muted2:#9ca3af;

    --danger-bg:#fef2f2;
    --danger-border:#fecaca;

    --focus:#2563eb;
    --focus-ring: rgba(37,99,235,.15);

    --btn:#2563eb;
    --btn-hover:#1d4ed8;

    --soft:#f3f4f6;
    --shadow: 0 1px 2px rgba(0,0,0,.05);
    --shadow-hover: 0 8px 24px rgba(17,24,39,.08);
    --radius: 16px;
  }

  *{box-sizing:border-box}

  body{
    margin:0;
    font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial;
    background: var(--bg);
    color:var(--text);
    min-height:100vh;
    padding:28px 16px;
  }

  .wrap{max-width:1200px;margin:0 auto}
  .top{display:flex;justify-content:space-between;gap:12px;align-items:flex-start;margin-bottom:14px}

  h1{
    margin:0;
    font-size:22px;
    letter-spacing:.1px;
    font-weight:800;
    color:var(--text);
  }

  .subtitle{
    margin:8px 0 0;
    color:var(--muted);
    line-height:1.55;
    font-size:14px;
    max-width:900px;
  }

  .card{
    background:var(--card);
    border:1px solid var(--border);
    border-radius: calc(var(--radius) + 4px);
    padding:18px;
    box-shadow: var(--shadow);
    margin-bottom:14px;
  }

  .grid-2{display:grid;grid-template-columns:1fr;gap:14px}
  @media (min-width: 920px){ .grid-2{grid-template-columns:1fr 1fr} }

  .section-title{
    font-weight:800;
    margin:0 0 10px;
    font-size:15px;
    letter-spacing:.1px;
    color:var(--text);
  }

  .field{display:grid;gap:8px;margin-bottom:12px}

  label{
    font-weight:700;
    font-size:13px;
    color:var(--text);
  }

  input, select, textarea{
    width:100%;
    padding:11px 12px;
    border-radius:12px;
    border:1px solid var(--border);
    background:#fff;
    color:var(--text);
    outline:none;
    box-shadow: 0 1px 0 rgba(0,0,0,.02);
  }

  input::placeholder, textarea::placeholder{color:var(--muted2)}
  select{color:var(--text); background:#fff}

  input:focus, select:focus, textarea:focus{
    border-color: var(--focus);
    box-shadow:0 0 0 4px var(--focus-ring);
  }

  textarea{resize:vertical;min-height:86px}

  .muted{
    color:var(--muted);
    font-size:12px;
    line-height:1.5;
    margin-top:4px;
  }

  .err{
    background:var(--danger-bg);
    border:1px solid var(--danger-border);
    padding:12px 14px;
    border-radius:14px;
    margin-bottom:12px;
  }

  .err ul{margin:0;padding-left:18px;color:#7f1d1d;font-size:13px;line-height:1.55}

  /* checklist rows */
  .rows{display:grid;gap:12px;margin-top:10px}

  .row{
    border:1px solid var(--border);
    border-radius: var(--radius);
    padding:14px;
    background:#fff;
    box-shadow: var(--shadow);
    transition: box-shadow .18s ease, transform .18s ease, border-color .18s ease;
  }
  .row:hover{
    box-shadow: var(--shadow-hover);
    border-color: #dbeafe;
  }

  .row-head{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:10px;
    margin-bottom:10px
  }

  .row-title{
    font-weight:800;
    color:var(--text);
  }

  .mini-btn{
    cursor:pointer;
    border:1px solid var(--border);
    background:#fff;
    color:var(--muted);
    border-radius:12px;
    padding:8px 10px;
    font-weight:700;
    transition: background .15s ease, border-color .15s ease, color .15s ease;
  }
  .mini-btn:hover{
    background: var(--soft);
    border-color: #d1d5db;
    color: #374151;
  }

  .row-grid{
    display:grid;
    grid-template-columns: 1fr;
    gap:12px;
    align-items:start;
  }

  @media (min-width: 980px){
    .row-grid{
      grid-template-columns: 1.2fr .8fr;
    }

  
    .row-right{
      align-self: center;
    }
  }

  .row-left{display:grid;gap:10px}

  .row-right{
    border:1px dashed #cbd5e1;
    border-radius:14px;
    padding:12px;
    background: #f8fafc;
  }

  .two{
    display:grid;
    grid-template-columns: 1fr;
    gap:10px;
  }
  @media(min-width:720px){ .two{ grid-template-columns: 1fr 1fr; } }

  .btn-row{display:flex;gap:10px;flex-wrap:wrap;margin-top:12px}

  .btn{
    cursor:pointer;
    border:0;
    border-radius:12px;
    padding:12px 14px;
    font-weight:800;
    color:#ffffff;
    background: var(--btn);
    box-shadow: var(--shadow);
    transition: background .15s ease, box-shadow .15s ease, transform .05s ease;
  }
  .btn:hover{
    background: var(--btn-hover);
    box-shadow: var(--shadow-hover);
  }
  .btn:active{transform: translateY(1px);}

  .link{
    color: var(--text);
    text-decoration:none;
    border:1px solid var(--border);
    padding:10px 12px;
    border-radius:12px;
    background:#fff;
    box-shadow: var(--shadow);
    transition: background .15s ease, box-shadow .15s ease;
  }
  .link:hover{
    background: var(--soft);
    box-shadow: var(--shadow-hover);
  }

  .preview-grid{display:flex;flex-wrap:wrap;gap:10px;margin-top:10px}

  .preview{
    width:140px;
    border:1px solid var(--border);
    border-radius:14px;
    overflow:hidden;
    background:#fff;
    box-shadow: var(--shadow);
  }

  .preview img{width:100%;display:block}

  .preview .meta{
    padding:8px;
    display:grid;
    gap:6px;
    background:#fff;
  }

  .preview .meta input,
  .preview .meta select{
    padding:8px;
    border-radius:10px;
    border:1px solid var(--border);
    background:#fff;
  }

  .pill{
    display:inline-block;
    font-size:11px;
    font-weight:800;
    padding:6px 10px;
    border-radius:999px;
    border:1px solid #dbeafe;
    background:#eff6ff;
    color:#1d4ed8;
  }

  .hint{font-size:11px;color:var(--muted);margin-top:6px}
  .sep{height:1px;background:var(--border);margin:10px 0}
  </style>
</head>

<body>
<div class="wrap">
  <div class="top">
    <div>
      <h1>Form Report Kegiatan</h1>
    </div>
  </div>

  @if ($errors->any())
    <div class="err">
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
      <div class="section-title">Info Kegiatan</div>

      <div class="grid-2">
        <div>
          <div class="field">
            <label>Nama Kegiatan</label>
            <input name="nama_kegiatan" value="{{ old('nama_kegiatan') }}" required>
          </div>

          <div class="field">
            <label>Waktu Kegiatan</label>
            <input name="waktu_kegiatan" value="{{ old('waktu_kegiatan') }}" required>
          </div>
        </div>

        <div>
          <div class="field">
            <label>Jenis Kegiatan</label>
            <input name="jenis_kegiatan" value="{{ old('jenis_kegiatan') }}" required>
          </div>

          <div class="field">
            <label>Lokasi Kegiatan</label>
            <input name="lokasi_kegiatan" value="{{ old('lokasi_kegiatan') }}" required>
          </div>
        </div>
      </div>
    </div>

    {{-- CHECKLIST + FOTO PER ITEM --}}
    <div class="card">
      <div style="display:flex;justify-content:space-between;gap:10px;align-items:center">
        <div>
          <div class="section-title" style="margin-bottom:4px">Checklist Kondisi</div>
        </div>
        <button type="button" id="addRowBtn" class="btn" style="padding:10px 12px">+ Tambah Checklist</button>
      </div>

      <div class="rows" id="rows">
       
        @php $initial = old('items') ?? array_fill(0, 4, ['deskripsi'=>'','kondisi'=>'','catatan'=>'']); @endphp

        @foreach($initial as $idx => $row)
          <div class="row" data-row="{{ $idx }}">
            <div class="row-head">
              <div class="row-title">Item #<span class="rowNo">{{ $idx+1 }}</span></div>
              <button type="button" class="mini-btn removeRowBtn">Hapus</button>
            </div>

            <div class="row-grid">
            
              <div class="row-left">
                <div class="field">
                  <label>Deskripsi</label>
              
                  <input name="items[{{ $idx }}][deskripsi]"
                         value="{{ old("items.$idx.deskripsi", $row['deskripsi'] ?? '') }}"
                         placeholder="Isi deskripsi...">
                </div>

                <div class="two">
                  <div class="field">
                    <label>Kondisi</label>
                    <select name="items[{{ $idx }}][kondisi]">
                      <option value="">-</option>
                      <option value="baik" {{ old("items.$idx.kondisi", $row['kondisi'] ?? '')=='baik'?'selected':'' }}>Baik</option>
                      <option value="problem" {{ old("items.$idx.kondisi", $row['kondisi'] ?? '')=='problem'?'selected':'' }}>Problem</option>
                    </select>
                  </div>

                  <div class="field">
                    <label>Catatan</label>
                    <input name="items[{{ $idx }}][catatan]"
                           value="{{ old("items.$idx.catatan", $row['catatan'] ?? '') }}"
                           placeholder="Catatan...">
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

      <div class="btn-row" style="margin-top:14px">
        <button class="btn" type="submit">Submit Report</button>
      </div>
    </div>
  </form>
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
        <div class="row-title">Item #<span class="rowNo">${idx+1}</span></div>
        <button type="button" class="mini-btn removeRowBtn">Hapus</button>
      </div>

      <div class="row-grid">
        <div class="row-left">
          <div class="field">
            <label>Deskripsi</label>
           
            <input name="items[${idx}][deskripsi]" value="" placeholder="Isi deskripsi...">
          </div>

          <div class="two">
            <div class="field">
              <label>Kondisi</label>
              <select name="items[${idx}][kondisi]">
                <option value="">-</option>
                <option value="baik">Baik</option>
                <option value="problem">Problem</option>
              </select>
            </div>

            <div class="field">
              <label>Catatan</label>
              <input name="items[${idx}][catatan]" value="" placeholder="Catatan...">
            </div>
          </div>
        </div>

        <div class="row-right">
          <label>Dokumentasi Foto (Item #<span class="rowNo2">${idx+1}</span>)</label>
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
