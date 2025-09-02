<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Shellfolio — {{ config('app.name') }}</title>
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <style>
    :root {
      --bg: #0b0e14;
      --fg: #e6e6e6;
      --dim: #9aa0a6;
      --green: #7ee787;
      --blue: #79c0ff;
      --accent: #ffcc66;
    }
    * { box-sizing: border-box; }
    html, body { height: 100%; }
    body {
      margin: 0;
      background: var(--bg);
      color: var(--fg);
      font: 14px/1.5 ui-monospace, SFMono-Regular, Menlo, Consolas, "Liberation Mono", monospace;
      -webkit-font-smoothing: antialiased;
    }
    .wrap { max-width: 1000px; margin: 0 auto; padding: 24px; }
    .terminal {
      border: 1px solid #1e2430; border-radius: 8px;
      box-shadow: 0 10px 30px rgba(0,0,0,.35);
      overflow: hidden;
    }
    .bar { background: #121722; padding: 10px; display: flex; gap: 8px; align-items: center; }
    .dot { width: 12px; height: 12px; border-radius: 50%; }
    .r { background: #ff5f56; } .y { background: #ffbd2e; } .g { background: #27c93f; }
    .screen { height: 70vh; padding: 16px; overflow-y: auto; white-space: pre-wrap; }
    .line { margin: 0 0 2px; }
    .prompt { color: var(--green); }
    a { color: var(--blue); text-decoration: none; }
    a:hover { text-decoration: underline; }
    .input {
      display: flex; gap: 8px; padding: 12px 16px; border-top: 1px solid #1e2430;
      align-items: center;
    }
    input[type="text"] {
      flex: 1; background: transparent; border: none; outline: none; color: var(--fg);
      font: inherit;
    }
    .hint { color: var(--dim); }
  </style>
</head>
<body>
<div class="wrap" x-data="terminalApp()">
  <div class="terminal">
    <div class="bar">
      <div class="dot r"></div><div class="dot y"></div><div class="dot g"></div>
      <div class="hint">shellfolio</div>
    </div>
    <div class="screen" x-ref="screen">
      <template x-for="item in output" :key="item.id">
        <div class="line" x-html="item.html"></div>
      </template>
    </div>
    <div class="input" @click="$refs.cmd.focus()">
      <span class="prompt" x-text="prompt"></span>
      <input type="text" x-ref="cmd" x-model="input"
             @keydown.enter.prevent="run()"
             @keydown.arrow-up.prevent="prevHistory()"
             @keydown.arrow-down.prevent="nextHistory()"
             placeholder='type "help" and press Enter'>
    </div>
  </div>
</div>

<script>
function terminalApp() {
  return {
    prompt: 'visitor@shellfolio:~$',
    input: '',
    output: [],
    history: [],
    idx: 0,
    idseq: 0,
    init() {
      this.banner();
      this.print('Type <span class="hint">help</span> to get started.');
      this.$nextTick(() => this.$refs.cmd.focus());
    },
    print(html) {
      this.output.push({ id: ++this.idseq, html });
      this.$nextTick(() => {
        const s = this.$refs.screen;
        s.scrollTop = s.scrollHeight;
      });
    },
    echo(cmd) {
      this.print('<span class="prompt">' + this.prompt + '</span> ' + this.escape(cmd));
    },
    escape(s) {
      const d = document.createElement('div');
      d.innerText = s;
      return d.innerHTML;
    },
    async run() {
      const cmd = this.input.trim();
      if (!cmd) return;
      this.echo(cmd);
      this.history.push(cmd);
      this.idx = this.history.length;
      this.input = '';

      try {
        const res = await fetch('{{ route('cmd') }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
          },
          body: JSON.stringify({ cmd })
        });
        const data = await res.json();
        if (data.clear) {
          this.output = [];
          this.banner();
          return;
        }
        if (data.output) this.print(this.escapeLines(data.output));
      } catch (e) {
        this.print('<span style="color:#ff6b6b">Error:</span> ' + this.escape(String(e)));
      }
    },
    prevHistory() {
      if (this.history.length === 0) return;
      this.idx = Math.max(0, this.idx - 1);
      this.input = this.history[this.idx] || '';
      this.$nextTick(() => this.moveCursorToEnd());
    },
    nextHistory() {
      if (this.history.length === 0) return;
      this.idx = Math.min(this.history.length, this.idx + 1);
      this.input = this.history[this.idx] || '';
      this.$nextTick(() => this.moveCursorToEnd());
    },
    moveCursorToEnd() {
      const el = this.$refs.cmd;
      el.selectionStart = el.selectionEnd = el.value.length;
    },
    escapeLines(text) {
      // Convert plain text newlines to <br>, but keep <a> tags returned by backend
      // Strategy: split by \n and escape each line, then unescape links.
      return text.split('\n').map(line => {
        // keep links: very simple allowlist for <a ...>...</a>
        const linkMatch = line.match(/<a\s+[^>]*>.*?<\/a>/i);
        if (linkMatch) {
          // replace link with a placeholder, escape the rest, then put link back
          const placeholder = '__LINK__';
          const replaced = line.replace(linkMatch[0], placeholder);
          const escaped = this.escape(replaced);
          return escaped.replace(placeholder, linkMatch[0]);
        }
        return this.escape(line);
      }).join('<br>');
    },
    banner() {
  const art = [
    '███████╗ █████╗  ██████╗██╗  ██╗ █  ███████╗    ██████╗  ██████╗ ██████╗ ████████╗███████╗ ██████╗ ██╗     ██╗ ██████╗ ',
    '╚══███╔╝██╔══██╗██╔════╝██║ ██╔╝    ██╔════╝    ██╔══██╗██╔═══██╗██╔══██╗╚══██╔══╝██╔════╝██╔═══██╗██║     ██║██╔═══██╗',
    '  ███╔╝ ███████║██║     █████╔╝     ███████╗    ██████╔╝██║   ██║██████╔╝   ██║   █████╗  ██║   ██║██║     ██║██║   ██║',
    ' ███╔╝  ██╔══██║██║     ██╔═██╗     ╚════██║    ██╔═══╝ ██║   ██║██╔══██╗   ██║   ██╔══╝  ██║   ██║██║     ██║██║   ██║',
    '███████╗██║  ██║╚██████╗██║  ██╗    ███████║    ██║     ╚██████╔╝██║  ██║   ██║   ██║     ╚██████╔╝███████╗██║╚██████╔╝',
    '╚══════╝╚═╝  ╚═╝ ╚═════╝╚═╝  ╚═╝    ╚══════╝    ╚═╝      ╚═════╝ ╚═╝  ╚═╝   ╚═╝   ╚═╝      ╚═════╝ ╚══════╝╚═╝ ╚═════╝ '
  ].join('\n');

  this.print(
    '<span style="color: var(--accent); font-weight: bold; font-size: 14px; line-height: 1.4; display: block; white-space: pre;">' +
      this.escape(art) +
      '</span>'
  );
}


  }
}
</script>
</body>
</html>