/** Compile a single view and report the first JavaScript syntax error. */
'use strict';
const fs = require('fs');
const path = require('path');
const { Blade } = require('./blade');

const view = process.argv[2];
const b = new Blade({ viewPath: path.join(__dirname, '../resources/views'), helpers: {} });
const file = path.join(__dirname, '../resources/views', view.replace(/\./g, '/') + '.blade.php');
const body = b.compile(fs.readFileSync(file, 'utf8'), view);
const out = `function __tpl(__scope){with(__scope){\n${body}\n}}`;
fs.writeFileSync('/tmp/dg-view.js', out);
try {
    new Function('__scope', `with(__scope){${body}}`);
    console.log('OK — compiles');
} catch (e) {
    console.log('ERROR:', e.message);
    console.log('written to /tmp/dg-view.js');
}
