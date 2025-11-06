<?php
// PHP can be used here for dynamic includes, but for now, we just open the tag.
// The file MUST be saved as index.php for the server to execute the save_entries.php endpoint.
?>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes">
<title>BNI - Success Lives Here</title>
<style>
  *{box-sizing:border-box;-webkit-tap-highlight-color:transparent}
  :root{
    --bg:#101317;--panel:#161a20;--panel-2:#1c2129;--text:#e6e9ef;--muted:#9aa3b2;
    --accent:#ff6b6b;--good:#4df5a1;--danger:#ff6b6b;--warn:#ffd166;--shadow:rgba(0,0,0,.35);
  }
  html,body{min-height:100%;margin:0;overflow-x:hidden;overflow-y:auto}
  body{background:var(--bg);color:var(--text);font:16px/1.5 system-ui,sans-serif;display:flex;flex-direction:column}
  
  header{padding:12px 16px;background:var(--panel);box-shadow:0 2px 8px var(--shadow);display:flex;align-items:center;justify-content:space-between;flex-shrink:0;position:sticky;top:0;z-index:10}
  header h1{margin:0;font-size:18px;font-weight:700}
  header button{background:var(--panel-2);color:var(--text);border:none;padding:8px 16px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer}
  
  .wheel-container{flex-shrink:0;display:flex;align-items:center;justify-content:center;position:relative;min-height:400px;padding:20px 0}
  
  .canvas-wrap{position:relative;width:100%;height:100%;display:flex;align-items:center;justify-content:center}
  canvas{display:block;width:85vmin;height:85vmin;max-width:500px;max-height:500px}
  #confetti{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:85vmin;height:85vmin;max-width:500px;max-height:500px;pointer-events:none}
  
  .pointer{position:absolute;top:10%;left:50%;transform:translateX(-50%);width:0;height:0;border-left:16px solid transparent;border-right:16px solid transparent;border-top:28px solid var(--danger);filter:drop-shadow(0 3px 6px var(--shadow));z-index:2}
  
  .spin-btn-wrap{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);z-index:3}
  .spin-btn{width:120px;height:120px;border-radius:50%;background:var(--panel);border:6px solid var(--panel-2);box-shadow:0 8px 24px var(--shadow);display:flex;align-items:center;justify-content:center}
  .spin-btn button{width:90px;height:90px;border-radius:50%;border:none;background:var(--accent);color:white;font-size:28px;font-weight:800;cursor:pointer;touch-action:manipulation}
  .spin-btn button:active{transform:scale(0.95)}
  
  .winner-display{position:absolute;bottom:20px;left:50%;transform:translateX(-50%);z-index:4;max-width:90%;padding:0 10px}
  .winner{display:none;font-size:18px;font-weight:800;padding:12px 20px;border-radius:12px;background:linear-gradient(135deg,var(--good),var(--accent));color:#000;box-shadow:0 8px 24px var(--shadow);text-align:center;word-wrap:break-word;max-width:100%}
  
  .controls{background:var(--panel);border-top:2px solid var(--panel-2);padding:16px;flex-shrink:0}
  
  .section-title{font-size:16px;font-weight:700;margin:0 0 12px 0;color:var(--text)}
  
  textarea{width:100%;min-height:140px;max-height:250px;resize:vertical;background:var(--panel-2);border:1px solid var(--panel-2);border-radius:10px;color:var(--text);padding:12px;font:inherit;margin-bottom:12px}
  textarea:focus{outline:none;border-color:var(--accent)}
  
  .btn-row{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:12px}
  .btn-row.triple{grid-template-columns:1fr 1fr 1fr} /* Adjusted for the new save button */
  .btn{background:var(--panel-2);color:var(--text);border:none;padding:14px;border-radius:10px;font-size:15px;font-weight:600;cursor:pointer;touch-action:manipulation}
  .btn:active{opacity:0.8}
  .btn.primary{background:var(--accent);color:white}
  .btn.warn{background:var(--warn);color:#000}
  .btn.danger{background:var(--danger);color:white}

  /* New style for the status message */
  #statusMessage {
    padding: 10px;
    border-radius: 8px;
    margin-top: 10px;
    text-align: center;
    font-weight: 600;
    min-height: 36px; /* Ensure space is reserved */
  }
  .status-success { background: rgba(77, 245, 161, 0.2); color: var(--good); }
  .status-error { background: rgba(255, 107, 107, 0.2); color: var(--danger); }
  .status-loading { background: rgba(255, 209, 102, 0.2); color: var(--warn); }
  
  .options{display:flex;flex-direction:column;gap:10px;margin-bottom:12px}
  .option{display:flex;align-items:center;gap:10px;background:var(--panel-2);padding:12px;border-radius:10px}
  .option input{width:20px;height:20px;margin:0;cursor:pointer}
  .option label{flex:1;font-size:15px;cursor:pointer}
  
  .hint{font-size:13px;color:var(--muted);text-align:center;padding:12px 0}
  
  .scroll-hint{text-align:center;padding:16px;background:var(--panel-2);border-radius:10px;margin-bottom:12px;font-size:14px;color:var(--accent);font-weight:600}
</style>
</head>
<body>
  <header>
    <h1>🎡 BNI - Success Lives Here</h1>
    <button id="fullscreenBtn" onclick="toggleFullscreen()">⛶ Full</button>
  </header>

  <div class="wheel-container">
    <div class="canvas-wrap">
      <div class="pointer"></div>
      <canvas id="wheel" width="900" height="900"></canvas>
      <div class="spin-btn-wrap">
        <div class="spin-btn">
          <button id="spinBtn">BNI</button>
        </div>
      </div>
      <div class="winner-display">
        <div id="winnerBanner" class="winner">Winner: —</div>
      </div>
      <canvas id="confetti" width="900" height="900"></canvas>
    </div>
  </div>

  <div class="controls" id="controls">
    <div class="scroll-hint">👇 Scroll down for controls 👇</div>
    
    <h2 class="section-title">Entries</h2>
    <textarea id="entries" spellcheck="false" placeholder="One entry per line&#10;Blank lines ignored"></textarea>
    
    <div class="btn-row">
      <button class="btn primary" onclick="applyEntries()">✓ Apply to Wheel</button>
      <button class="btn" onclick="shuffleEntries()">🔀 Shuffle</button>
    </div>
    
    <h2 class="section-title">Options</h2>
    <div class="options">
      <div class="option">
        <input type="checkbox" id="removeOnWin">
        <label for="removeOnWin">Remove winner after spin</label>
      </div>
      <div class="option">
        <input type="checkbox" id="centerLabel" checked>
        <label for="centerLabel">Show winner banner</label>
      </div>
    </div>
    
    <div class="btn-row triple">
      <button class="btn warn" onclick="clearAll()">🗑️ Clear</button>
      <button class="btn" onclick="saveEntriesToServer()">💾 Save to Server</button> <!-- NEW BUTTON -->
      <button class="btn danger" onclick="resetWheel()">↻ Reset</button>
    </div>
    <div id="statusMessage"></div> <!-- NEW STATUS MESSAGE DISPLAY -->
    
    <div class="hint">💡 Tap SPIN or press Space to spin • Press ⛶ for fullscreen</div>
   <div class="hint">Created by Blair Chintella of Hastings Shadmehry Family Law</div>
  </div>

<script>
const TAU = Math.PI * 2;
let slices = [];
let currentRotation = 0;
let spinning = false;
let animFrame = null;
const spinMs = 6000;

const canvas = document.getElementById('wheel');
const ctx = canvas.getContext('2d');
const confettiCanvas = document.getElementById('confetti');
const cctx = confettiCanvas.getContext('2d');
let confettiBits = [];
const entriesTextArea = document.getElementById('entries');
const statusMessageDiv = document.getElementById('statusMessage');

const colors = [
  'hsl(0,70%,55%)', 'hsl(30,75%,55%)', 'hsl(60,75%,50%)', 'hsl(90,65%,50%)',
  'hsl(120,65%,45%)', 'hsl(150,65%,45%)', 'hsl(180,70%,50%)', 'hsl(210,75%,55%)',
  'hsl(240,70%,60%)', 'hsl(270,65%,60%)', 'hsl(300,70%,55%)', 'hsl(330,75%,55%)'
];

function secureRandom(){
  const arr = new Uint32Array(1);
  crypto.getRandomValues(arr);
  return arr[0] / 2**32;
}

function secureRandomInt(n){
  const arr = new Uint32Array(1);
  const max = Math.floor(2**32 / n) * n;
  while(true){
    crypto.getRandomValues(arr);
    if(arr[0] < max) return arr[0] % n;
  }
}

function parseEntries(text){
  const lines = text.split(/\r?\n/).map(s=>s.trim()).filter(Boolean);
  return lines.map((line,i)=>{
    return { id:crypto.randomUUID(), text:line, color:colors[i % colors.length] };
  });
}

function computeAngles(list=slices){
  const count = list.length;
  return list.map((s,i)=>{
    const start = (i/count)*TAU;
    const end = ((i+1)/count)*TAU;
    return {...s, start, end};
  });
}

function drawWheel(){
  const size = Math.min(canvas.clientWidth, canvas.clientHeight);
  const dpr = window.devicePixelRatio || 1;
  canvas.width = canvas.height = size * dpr;
  confettiCanvas.width = confettiCanvas.height = size * dpr;
  
  const r = canvas.width/2;
  ctx.clearRect(0,0,canvas.width,canvas.height);
  ctx.save();
  ctx.translate(r,r);
  ctx.rotate(currentRotation);
  
  const geom = computeAngles();
  geom.forEach(g=>{
    ctx.beginPath();
    ctx.moveTo(0,0);
    ctx.arc(0,0,r-8,g.start,g.end);
    ctx.closePath();
    ctx.fillStyle = g.color;
    ctx.fill();
    
    const mid = (g.start+g.end)/2;
    ctx.save();
    ctx.rotate(mid);
    ctx.translate(r*0.65,0);
    ctx.fillStyle = 'rgba(0,0,0,0.8)';
    ctx.font = `bold ${Math.max(24,r*0.08)}px system-ui`;
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    const maxW = r*0.5;
    let text = g.text;
    while(ctx.measureText(text).width > maxW && text.length > 1){
      text = text.slice(0,-1);
    }
    if(text !== g.text) text += '…';
    ctx.fillText(text, 0, 0);
    ctx.restore();
  });
  
  ctx.beginPath();
  ctx.arc(0,0,r*0.18,0,TAU);
  ctx.fillStyle = '#161a20';
  ctx.fill();
  ctx.restore();
}

function pickWinner(){
  if(slices.length===0) return null;
  const idx = secureRandomInt(slices.length);
  return {winner:slices[idx], index:idx};
}

let audioCtx;
function tick(){
  if(!audioCtx) audioCtx = new (window.AudioContext||window.webkitAudioContext)();
  const o = audioCtx.createOscillator();
  const g = audioCtx.createGain();
  o.frequency.value = 1400;
  g.gain.value = 0.05;
  o.connect(g);
  g.connect(audioCtx.destination);
  o.start();
  o.stop(audioCtx.currentTime + 0.02);
}

let lastSliceIdx = null;

function angleToSlice(angle){
  const geom = computeAngles();
  const a = ((angle - currentRotation) % TAU + TAU) % TAU;
  for(let i=0; i<geom.length; i++){
    if(a >= geom[i].start && a < geom[i].end) return i;
  }
  return geom.length-1;
}

function animateSpin(finalAngle){
  if(spinning) cancelAnimationFrame(animFrame);
  spinning = true;
  const start = performance.now();
  const startRot = currentRotation % TAU;
  const delta = ((finalAngle - startRot + Math.PI) % TAU + TAU) % TAU - Math.PI;
  const extraTurns = 4 + secureRandomInt(3);
  const total = delta + extraTurns * TAU;
  
  lastSliceIdx = angleToSlice(-Math.PI/2);
  
  function frame(now){
    const t = Math.min((now-start)/spinMs, 1);
    const ease = 1 - Math.pow(1-t, 3);
    currentRotation = startRot + total*ease;
    drawWheel();
    
    const idx = angleToSlice(-Math.PI/2);
    if(idx !== lastSliceIdx){
      tick();
      lastSliceIdx = idx;
    }
    
    if(t < 1){
      animFrame = requestAnimationFrame(frame);
    } else {
      spinning = false;
      const winnerIdx = angleToSlice(-Math.PI/2);
      showWinner(slices[winnerIdx], winnerIdx);
    }
  }
  animFrame = requestAnimationFrame(frame);
}

function burstConfetti(){
  const W = confettiCanvas.width, H = confettiCanvas.height;
  confettiBits = [];
  
  for(let i=0; i<300; i++){
    const angle = Math.random() * TAU;
    const speed = 6 + Math.random() * 10;
    confettiBits.push({
      x: W/2,
      y: H/2,
      vx: Math.cos(angle) * speed,
      vy: Math.sin(angle) * speed - 3,
      rot: Math.random() * TAU,
      vr: (Math.random()-0.5) * 0.3,
      life: 150 + Math.random() * 80,
      color: `hsl(${Math.random()*360},90%,60%)`,
      w: 4+Math.random()*8,
      h: 8+Math.random()*12
    });
  }
  
  function frame(){
    cctx.clearRect(0,0,W,H);
    confettiBits.forEach(p=>{
      p.vy += 0.2;
      p.x += p.vx;
      p.y += p.vy;
      p.vx *= 0.99;
      p.rot += p.vr;
      p.life--;
      
      const alpha = p.life < 40 ? p.life/40 : 1;
      cctx.save();
      cctx.translate(p.x, p.y);
      cctx.rotate(p.rot);
      cctx.globalAlpha = alpha;
      cctx.fillStyle = p.color;
      cctx.fillRect(-p.w/2, -p.h/2, p.w, p.h);
      cctx.restore();
    });
    confettiBits = confettiBits.filter(p=>p.life>0 && p.y<H+50);
    if(confettiBits.length>0) requestAnimationFrame(frame);
  }
  requestAnimationFrame(frame);
}

function showWinner(winner, index){
  const banner = document.getElementById('winnerBanner');
  banner.textContent = `Winner: ${winner.text}`;
  if(document.getElementById('centerLabel').checked){
    banner.style.display = 'block';
  }
  
  burstConfetti();
  
  if(document.getElementById('removeOnWin').checked){
    slices.splice(index, 1);
    drawWheel();
  }
}

function computeFinalAngle(winnerIdx){
  const geom = computeAngles();
  const s = geom[winnerIdx];
  const mid = (s.start + s.end) / 2;
  const localAngle = s.start + secureRandom() * (s.end - s.start);
  return ((-Math.PI/2 - localAngle) % TAU + TAU) % TAU;
}

function applyEntries(){
  const list = parseEntries(entriesTextArea.value);
  if(list.length === 0){ 
    statusMessageDiv.textContent = 'ERROR: Add at least one entry.';
    statusMessageDiv.className = 'status-error';
    return;
  }
  slices = list;
  document.getElementById('winnerBanner').style.display = 'none';
  drawWheel();
  statusMessageDiv.textContent = '';
  statusMessageDiv.className = '';
  window.scrollTo({top: 0, behavior: 'smooth'});
}

function shuffleEntries(){
  const lines = entriesTextArea.value.split(/\r?\n/);
  for(let i=lines.length-1; i>0; i--){
    const j = secureRandomInt(i+1);
    [lines[i],lines[j]] = [lines[j],lines[i]];
  }
  entriesTextArea.value = lines.join('\n');
}

function clearAll(){
  entriesTextArea.value = '';
  slices = [];
  drawWheel();
}

function resetWheel(){
  entriesTextArea.value = 'Don\nJoel\nBlair\nAmina\nNyasia\nMide\nLeeza\nDeana\nKeenan\nChristy\nCameron\nOdessa\nDavid\nZoom\nAlicia\nGreg\nJosh\nIrene\nLeslie\nCandice';
  applyEntries();
}

function toggleFullscreen(){
  if(!document.fullscreenElement){
    document.documentElement.requestFullscreen?.();
  } else {
    document.exitFullscreen?.();
  }
}

/**
 * NEW FUNCTION: Sends the entries data to the PHP server endpoint.
 */
async function saveEntriesToServer() {
    const entries = entriesTextArea.value;
    if (entries.trim() === '') {
        statusMessageDiv.textContent = 'Cannot save empty entries.';
        statusMessageDiv.className = 'status-error';
        return;
    }

    statusMessageDiv.textContent = 'Saving entries...';
    statusMessageDiv.className = 'status-loading';

    // FormData prepares the data as key=value pairs for POST request
    const formData = new FormData();
    formData.append('entries', entries);

    try {
        const response = await fetch('save_entries.php', {
            method: 'POST',
            body: formData, // Sends the entries data to the PHP script
        });

        // PHP script sends a JSON response, which we await here
        const result = await response.json();

        if (response.ok && result.status === 'success') {
            statusMessageDiv.textContent = '✅ Entries successfully saved to entries.txt!';
            statusMessageDiv.className = 'status-success';
        } else {
            // Handle errors reported by the PHP script or network
            statusMessageDiv.textContent = `❌ Save failed: ${result.message || 'Server error.'}`;
            statusMessageDiv.className = 'status-error';
        }
    } catch (error) {
        // Handle network errors (e.g., save_entries.php is not found)
        console.error('Network or parsing error:', error);
        statusMessageDiv.textContent = '❌ Failed to connect to server (Is save_entries.php available?).';
        statusMessageDiv.className = 'status-error';
    }
}


document.getElementById('spinBtn').addEventListener('click', ()=>{
  if(spinning) return;
  if(slices.length === 0){ 
    statusMessageDiv.textContent = 'ERROR: Add entries first! Scroll down to add entries.';
    statusMessageDiv.className = 'status-error';
    return;
  }
  statusMessageDiv.textContent = '';
  statusMessageDiv.className = '';
  const result = pickWinner();
  if(!result) return;
  const finalAngle = computeFinalAngle(result.index);
  animateSpin(finalAngle);
});

document.addEventListener('keydown', e=>{
  if(e.code === 'Space'){
    e.preventDefault();
    document.getElementById('spinBtn').click();
  }
});

window.addEventListener('resize', drawWheel);
window.addEventListener('orientationchange', ()=>setTimeout(drawWheel, 100));

resetWheel();
</script>
</body>
</html>
