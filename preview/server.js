/**
 * Static file server for the rendered preview.
 *
 * Serves preview/dist first (the generated HTML pages) and falls back to
 * public/ for the compiled CSS, JavaScript, fonts and imagery, so the preview
 * runs against exactly the same assets Laravel would serve.
 *
 *   node preview/server.js  →  http://0.0.0.0:3000
 */
'use strict';

const fs = require('fs');
const http = require('http');
const path = require('path');

const ROOT = path.resolve(__dirname, '..');
const ROOTS = [path.join(ROOT, 'preview/dist'), path.join(ROOT, 'public')];
const PORT = Number(process.env.PORT || 3000);

const TYPES = {
    '.html': 'text/html; charset=utf-8',
    '.css': 'text/css; charset=utf-8',
    '.js': 'text/javascript; charset=utf-8',
    '.json': 'application/json; charset=utf-8',
    '.svg': 'image/svg+xml',
    '.png': 'image/png',
    '.jpg': 'image/jpeg',
    '.jpeg': 'image/jpeg',
    '.webp': 'image/webp',
    '.gif': 'image/gif',
    '.ico': 'image/x-icon',
    '.woff2': 'font/woff2',
    '.woff': 'font/woff',
    '.txt': 'text/plain; charset=utf-8',
};

function resolve(urlPath) {
    let rel = decodeURIComponent(urlPath.split('?')[0]).replace(/^\/+/, '');
    if (rel === '') rel = 'index.html';

    const candidates = [rel];
    if (!path.extname(rel)) candidates.push(`${rel}.html`, path.join(rel, 'index.html'));

    for (const base of ROOTS) {
        for (const candidate of candidates) {
            const full = path.join(base, candidate);
            if (full.startsWith(base) && fs.existsSync(full) && fs.statSync(full).isFile()) return full;
        }
    }

    return null;
}

const server = http.createServer((req, res) => {
    const file = resolve(req.url);

    if (!file) {
        const notFound = path.join(ROOT, 'preview/dist/404.html');
        const body = fs.existsSync(notFound) ? fs.readFileSync(notFound) : 'Not found';
        res.writeHead(404, { 'Content-Type': 'text/html; charset=utf-8' });
        res.end(body);
        return;
    }

    res.writeHead(200, {
        'Content-Type': TYPES[path.extname(file).toLowerCase()] || 'application/octet-stream',
        'Cache-Control': 'no-store',
        'X-Frame-Options': 'ALLOWALL',
    });
    fs.createReadStream(file).pipe(res);
});

server.listen(PORT, '0.0.0.0', () => {
    console.log(`DigiNo preview → http://0.0.0.0:${PORT}`);
});
