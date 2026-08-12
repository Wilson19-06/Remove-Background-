<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cutting mat · background remover</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
  :root{
    --mat: #3E5641;
    --mat-dark: #33482F;
    --mat-line: rgba(220, 235, 210, 0.16);
    --mat-line-strong: rgba(220, 235, 210, 0.30);
    --paper: #F1EDE1;
    --paper-dark: #E4DFCF;
    --ink: #23281F;
    --ink-soft: #55594E;
    --ruler: #E8A33D;
    --ruler-dark: #C4852A;
    --blade: #C7CCC3;
    --danger: #C4523A;
    --font-display: 'Space Grotesk', sans-serif;
    --font-body: 'Inter', sans-serif;
    --font-mono: 'IBM Plex Mono', monospace;
  }

  *{ box-sizing: border-box; }

  body{
    margin: 0;
    font-family: var(--font-body);
    background: var(--mat);
    background-image:
      linear-gradient(var(--mat-line) 1px, transparent 1px),
      linear-gradient(90deg, var(--mat-line) 1px, transparent 1px);
    background-size: 24px 24px;
    color: var(--paper);
    min-height: 100vh;
    padding: 48px 20px 80px;
  }

  .wrap{
    max-width: 860px;
    margin: 0 auto;
  }

  .ruler-top{
    display: flex;
    justify-content: space-between;
    font-family: var(--font-mono);
    font-size: 11px;
    color: var(--mat-line-strong);
    letter-spacing: 0.05em;
    margin-bottom: 6px;
    padding: 0 2px;
    user-select: none;
  }

  header{
    margin-bottom: 40px;
  }

  .eyebrow{
    font-family: var(--font-mono);
    font-size: 12px;
    letter-spacing: 0.12em;
    color: var(--ruler);
    text-transform: uppercase;
    margin: 0 0 10px;
  }

  h1{
    font-family: var(--font-display);
    font-weight: 700;
    font-size: clamp(28px, 5vw, 40px);
    line-height: 1.15;
    margin: 0 0 12px;
    color: var(--paper);
  }

  .sub{
    font-size: 15px;
    line-height: 1.6;
    color: rgba(241, 237, 225, 0.72);
    max-width: 52ch;
    margin: 0;
  }

  .stage{
    background: var(--paper);
    border-radius: 4px;
    padding: 28px;
    position: relative;
    box-shadow: 0 1px 0 rgba(0,0,0,0.15), 0 12px 32px rgba(0,0,0,0.25);
  }

  .stage::before{
    content: '';
    position: absolute;
    inset: 0;
    border: 1px dashed rgba(35,40,31,0.15);
    margin: 10px;
    pointer-events: none;
    border-radius: 2px;
  }

  #dropzone{
    border: 2px dashed rgba(35,40,31,0.28);
    border-radius: 3px;
    padding: 56px 24px;
    text-align: center;
    cursor: pointer;
    transition: border-color 0.15s ease, background 0.15s ease;
    position: relative;
  }

  #dropzone.drag{
    border-color: var(--ruler-dark);
    background: rgba(232,163,61,0.08);
  }

  #dropzone .blade-icon{
    width: 40px;
    height: 40px;
    margin: 0 auto 18px;
    color: var(--ink-soft);
  }

  #dropzone p.main{
    font-family: var(--font-display);
    font-weight: 600;
    font-size: 17px;
    color: var(--ink);
    margin: 0 0 6px;
  }

  #dropzone p.hint{
    font-size: 13px;
    color: var(--ink-soft);
    margin: 0;
  }

  #fileInput{ display: none; }

  .workspace{ display: none; }
  .workspace.active{ display: block; }

  .panels{
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-top: 4px;
  }

  @media (max-width: 620px){
    .panels{ grid-template-columns: 1fr; }
  }

  .panel{
    background: var(--paper-dark);
    border-radius: 3px;
    padding: 14px;
  }

  .panel-label{
    font-family: var(--font-mono);
    font-size: 11px;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--ink-soft);
    margin: 0 0 10px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .canvas-holder{
    border-radius: 2px;
    overflow: hidden;
    aspect-ratio: 1 / 1;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #d8d3c2;
    position: relative;
  }

  .canvas-holder.checker{
    background-image:
      linear-gradient(45deg, #c9c4b1 25%, transparent 25%),
      linear-gradient(-45deg, #c9c4b1 25%, transparent 25%),
      linear-gradient(45deg, transparent 75%, #c9c4b1 75%),
      linear-gradient(-45deg, transparent 75%, #c9c4b1 75%);
    background-size: 20px 20px;
    background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
    background-color: #ede9dc;
  }

  .canvas-holder img, .canvas-holder canvas{
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    display: block;
  }

  .placeholder-note{
    font-size: 12px;
    color: var(--ink-soft);
    text-align: center;
    padding: 0 12px;
  }

  .controls{
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-top: 20px;
    flex-wrap: wrap;
  }

  .fileinfo{
    font-family: var(--font-mono);
    font-size: 12px;
    color: var(--ink-soft);
  }

  .btn-row{
    display: flex;
    gap: 10px;
  }

  button{
    font-family: var(--font-display);
    font-weight: 600;
    font-size: 13px;
    letter-spacing: 0.02em;
    border-radius: 2px;
    padding: 11px 18px;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: transform 0.08s ease, opacity 0.15s ease;
  }

  button:active{ transform: scale(0.97); }
  button:disabled{ opacity: 0.45; cursor: default; }
  button:disabled:active{ transform: none; }

  .btn-primary{
    background: var(--ink);
    color: var(--paper);
  }
  .btn-primary:hover:not(:disabled){ background: #1a1e15; }

  .btn-ruler{
    background: var(--ruler);
    color: #3a2705;
  }
  .btn-ruler:hover:not(:disabled){ background: var(--ruler-dark); }

  .btn-ghost{
    background: transparent;
    color: var(--ink-soft);
    border: 1px solid rgba(35,40,31,0.25);
  }
  .btn-ghost:hover:not(:disabled){ border-color: rgba(35,40,31,0.5); color: var(--ink); }

  .progress-wrap{
    margin-top: 18px;
    display: none;
  }
  .progress-wrap.active{ display: block; }

  .progress-label{
    font-family: var(--font-mono);
    font-size: 11px;
    color: var(--ink-soft);
    display: flex;
    justify-content: space-between;
    margin-bottom: 6px;
  }

  .progress-track{
    height: 5px;
    background: rgba(35,40,31,0.12);
    border-radius: 3px;
    overflow: hidden;
  }

  .progress-fill{
    height: 100%;
    width: 0%;
    background: var(--ruler);
    transition: width 0.2s ease;
  }

  .error-msg{
    display: none;
    margin-top: 16px;
    padding: 12px 14px;
    background: rgba(196,82,58,0.1);
    border-left: 3px solid var(--danger);
    border-radius: 2px;
    font-size: 13px;
    color: #6e2a1c;
  }
  .error-msg.active{ display: block; }

  footer{
    margin-top: 28px;
    text-align: center;
    font-family: var(--font-mono);
    font-size: 11px;
    color: rgba(241, 237, 225, 0.4);
    letter-spacing: 0.04em;
  }
</style>
</head>
<body>

<div class="wrap">
  <div class="ruler-top" id="rulerTop"></div>

  <header>
    <p class="eyebrow">Cutting mat / background remover</p>
    <h1>Cut the background away cleanly</h1>
    <p class="sub">Upload a photo and an AI model traces the subject's edge and makes the background transparent. Everything runs locally in your browser — nothing is uploaded to a server.</p>
  </header>

  <div class="stage">
    <div id="dropzone">
      <svg class="blade-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
        <path d="M4 20L14 10M14 10L20 4M14 10L20 16M4 20L4 14L10 14" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      <p class="main">Drag a photo here, or click to choose a file</p>
      <p class="hint">JPG, PNG or WEBP · ideally under 20MB</p>
      <input type="file" id="fileInput" accept="image/*">
    </div>

    <div class="workspace" id="workspace">
      <div class="panels">
        <div class="panel">
          <p class="panel-label"><span>Original</span><span id="dimsLabel"></span></p>
          <div class="canvas-holder" id="beforeHolder">
            <img id="beforeImg" alt="Original photo">
          </div>
        </div>
        <div class="panel">
          <p class="panel-label"><span>Cutout</span><span id="resultStatus">Not started</span></p>
          <div class="canvas-holder" id="afterHolder">
            <p class="placeholder-note" id="afterPlaceholder">Click "Remove background" below to see the result</p>
            <img id="afterImg" alt="Cutout result" style="display:none">
          </div>
        </div>
      </div>

      <div class="progress-wrap" id="progressWrap">
        <div class="progress-label">
          <span id="progressText">Loading model…</span>
          <span id="progressPct">0%</span>
        </div>
        <div class="progress-track"><div class="progress-fill" id="progressFill"></div></div>
      </div>

      <div class="error-msg" id="errorMsg"></div>

      <div class="controls">
        <span class="fileinfo" id="fileInfo"></span>
        <div class="btn-row">
          <button class="btn-ghost" id="resetBtn">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 3v5h5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Choose another
          </button>
          <button class="btn-ruler" id="processBtn">Remove background</button>
          <button class="btn-primary" id="downloadBtn" disabled>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12m0 0l-4-4m4 4l4-4M4 20h16" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Download PNG
          </button>
        </div>
      </div>
    </div>
  </div>

  <footer>Everything inside the cut line stays · all processing happens on your device</footer>
</div>

<script>
  const rulerTop = document.getElementById('rulerTop');
  for(let i=0;i<=20;i++){
    const mark = document.createElement('span');
    mark.textContent = i % 5 === 0 ? i : '·';
    rulerTop.appendChild(mark);
  }

  const dropzone = document.getElementById('dropzone');
  const fileInput = document.getElementById('fileInput');
  const workspace = document.getElementById('workspace');
  const beforeImg = document.getElementById('beforeImg');
  const afterImg = document.getElementById('afterImg');
  const afterHolder = document.getElementById('afterHolder');
  const afterPlaceholder = document.getElementById('afterPlaceholder');
  const dimsLabel = document.getElementById('dimsLabel');
  const resultStatus = document.getElementById('resultStatus');
  const fileInfo = document.getElementById('fileInfo');
  const processBtn = document.getElementById('processBtn');
  const downloadBtn = document.getElementById('downloadBtn');
  const resetBtn = document.getElementById('resetBtn');
  const progressWrap = document.getElementById('progressWrap');
  const progressFill = document.getElementById('progressFill');
  const progressText = document.getElementById('progressText');
  const progressPct = document.getElementById('progressPct');
  const errorMsg = document.getElementById('errorMsg');

  let currentFile = null;
  let currentFileUrl = null;
  let resultBlobUrl = null;
  let segmenterPromise = null;

  function fmtSize(bytes){
    if(bytes < 1024*1024) return (bytes/1024).toFixed(0) + ' KB';
    return (bytes/1024/1024).toFixed(1) + ' MB';
  }

  function showError(msg){
    errorMsg.textContent = msg;
    errorMsg.classList.add('active');
  }
  function clearError(){
    errorMsg.classList.remove('active');
    errorMsg.textContent = '';
  }

  function loadFile(file){
    if(!file || !file.type.startsWith('image/')){
      showError('Please choose an image file.');
      return;
    }
    clearError();
    currentFile = file;
    currentFileUrl = URL.createObjectURL(file);
    beforeImg.src = currentFileUrl;
    beforeImg.onload = () => {
      dimsLabel.textContent = beforeImg.naturalWidth + ' × ' + beforeImg.naturalHeight;
    };
    fileInfo.textContent = file.name + ' · ' + fmtSize(file.size);

    afterImg.style.display = 'none';
    afterHolder.classList.remove('checker');
    afterPlaceholder.style.display = 'block';
    afterPlaceholder.textContent = 'Click "Remove background" below to see the result';
    resultStatus.textContent = 'Not started';
    downloadBtn.disabled = true;
    progressWrap.classList.remove('active');
    if(resultBlobUrl){ URL.revokeObjectURL(resultBlobUrl); resultBlobUrl = null; }

    dropzone.style.display = 'none';
    workspace.classList.add('active');
  }

  dropzone.addEventListener('click', () => fileInput.click());
  fileInput.addEventListener('change', e => {
    if(e.target.files[0]) loadFile(e.target.files[0]);
  });

  ['dragenter','dragover'].forEach(evt => {
    dropzone.addEventListener(evt, e => {
      e.preventDefault();
      dropzone.classList.add('drag');
    });
  });
  ['dragleave','drop'].forEach(evt => {
    dropzone.addEventListener(evt, e => {
      e.preventDefault();
      dropzone.classList.remove('drag');
    });
  });
  dropzone.addEventListener('drop', e => {
    const file = e.dataTransfer.files[0];
    if(file) loadFile(file);
  });

  resetBtn.addEventListener('click', () => {
    currentFile = null;
    if(currentFileUrl){ URL.revokeObjectURL(currentFileUrl); currentFileUrl = null; }
    fileInput.value = '';
    if(resultBlobUrl){ URL.revokeObjectURL(resultBlobUrl); resultBlobUrl = null; }
    workspace.classList.remove('active');
    dropzone.style.display = 'block';
    clearError();
  });

  async function getSegmenter(){
    if(segmenterPromise) return segmenterPromise;
    segmenterPromise = (async () => {
      const { pipeline } = await import('https://cdn.jsdelivr.net/npm/@huggingface/transformers@3.8.0');
      return pipeline('background-removal', 'briaai/RMBG-1.4', {
        dtype: 'fp32',
        progress_callback: (p) => {
          if(p && p.status === 'progress'){
            const pct = Math.round(p.progress || 0);
            progressFill.style.width = pct + '%';
            progressPct.textContent = pct + '%';
            progressText.textContent = 'Downloading model files…';
          } else if(p && p.status === 'ready'){
            progressText.textContent = 'Model ready';
          }
        }
      });
    })();
    return segmenterPromise;
  }

  processBtn.addEventListener('click', async () => {
    if(!currentFile) return;
    clearError();
    processBtn.disabled = true;
    resetBtn.disabled = true;
    downloadBtn.disabled = true;
    progressWrap.classList.add('active');
    progressFill.style.width = '3%';
    progressText.textContent = 'Loading model…';
    progressPct.textContent = '0%';
    resultStatus.textContent = 'Processing…';

    try{
      let segmenter;
      try{
        segmenter = await getSegmenter();
      }catch(loadErr){
        console.error(loadErr);
        segmenterPromise = null;
        const detail = loadErr && loadErr.message ? loadErr.message : String(loadErr);
        throw new Error('Could not load the AI engine (' + detail + '). This needs access to cdn.jsdelivr.net and huggingface.co — if you are viewing this inside a chat preview panel, download the file and open it in a regular browser instead.');
      }

      progressFill.style.width = '100%';
      progressPct.textContent = '';
      progressText.textContent = 'Analyzing image…';

      const output = await segmenter(currentFileUrl);
      const blob = await output[0].toBlob();

      resultBlobUrl = URL.createObjectURL(blob);
      afterImg.src = resultBlobUrl;
      afterImg.style.display = 'block';
      afterHolder.classList.add('checker');
      afterPlaceholder.style.display = 'none';
      resultStatus.textContent = 'Done';
      downloadBtn.disabled = false;
      progressText.textContent = 'Done';
    }catch(err){
      console.error(err);
      resultStatus.textContent = 'Failed';
      showError('Background removal failed: ' + (err && err.message ? err.message : 'unknown error') + '. Try another photo.');
    }finally{
      processBtn.disabled = false;
      resetBtn.disabled = false;
      setTimeout(() => progressWrap.classList.remove('active'), 600);
    }
  });

  downloadBtn.addEventListener('click', () => {
    if(!resultBlobUrl) return;
    const a = document.createElement('a');
    a.href = resultBlobUrl;
    const base = currentFile ? currentFile.name.replace(/\.[^.]+$/, '') : 'image';
    a.download = base + '-cutout.png';
    document.body.appendChild(a);
    a.click();
    a.remove();
  });
</script>

</body>
</html>