#!/usr/bin/env python3
"""
Convert the literal PHP array returned by database/data/catalog.php into JSON so
the Node preview renderer can seed itself from exactly the same dataset the
Laravel seeders use.

Usage:  python3 preview/tools/php-array-to-json.py database/data/catalog.php preview/data/catalog.json
"""
import json
import re
import sys


class Parser:
    def __init__(self, text):
        self.s = text
        self.i = 0

    # ---------------------------------------------------------------- utils
    def ws(self):
        while self.i < len(self.s):
            c = self.s[self.i]
            if c in ' \t\r\n':
                self.i += 1
            elif self.s.startswith('//', self.i):
                self.i = self.s.find('\n', self.i) + 1 or len(self.s)
            elif self.s.startswith('#', self.i):
                self.i = self.s.find('\n', self.i) + 1 or len(self.s)
            elif self.s.startswith('/*', self.i):
                end = self.s.find('*/', self.i)
                self.i = end + 2 if end != -1 else len(self.s)
            else:
                return

    def expect(self, ch):
        self.ws()
        if self.s[self.i] != ch:
            raise ValueError(f'expected {ch!r} at {self.i}: {self.s[self.i:self.i + 40]!r}')
        self.i += 1

    # --------------------------------------------------------------- values
    def value(self):
        self.ws()
        c = self.s[self.i]

        if c == '[':
            return self.array()
        if self.s.startswith('array(', self.i):
            self.i += 5
            return self.array(paren=True)
        if c in '"\'':
            return self.string()
        if self.s.startswith('true', self.i):
            self.i += 4
            return True
        if self.s.startswith('false', self.i):
            self.i += 5
            return False
        if self.s.startswith('null', self.i):
            self.i += 4
            return None
        return self.number()

    def string(self):
        quote = self.s[self.i]
        self.i += 1
        out = []
        while self.i < len(self.s):
            c = self.s[self.i]
            if c == '\\':
                nxt = self.s[self.i + 1]
                out.append({'n': '\n', 't': '\t', 'r': '\r'}.get(nxt, nxt))
                self.i += 2
                continue
            if c == quote:
                self.i += 1
                return ''.join(out)
            out.append(c)
            self.i += 1
        raise ValueError('unterminated string')

    def number(self):
        m = re.match(r'-?\d[\d_]*(\.\d+)?([eE][+-]?\d+)?', self.s[self.i:])
        if not m:
            raise ValueError(f'bad value at {self.i}: {self.s[self.i:self.i + 40]!r}')
        raw = m.group(0).replace('_', '')
        self.i += len(m.group(0))
        return float(raw) if ('.' in raw or 'e' in raw.lower()) else int(raw)

    def array(self, paren=False):
        self.expect('(' if paren else '[')
        close = ')' if paren else ']'
        items = []
        pairs = {}
        keyed = False

        while True:
            self.ws()
            if self.s[self.i] == close:
                self.i += 1
                break

            first = self.value()
            self.ws()

            if self.s.startswith('=>', self.i):
                self.i += 2
                keyed = True
                pairs[str(first)] = self.value()
            else:
                items.append(first)

            self.ws()
            if self.s[self.i] == ',':
                self.i += 1

        return pairs if keyed else items


def main():
    src, dest = sys.argv[1], sys.argv[2]
    text = open(src, encoding='utf-8').read()
    start = text.index('return') + len('return')
    parser = Parser(text[start:])
    data = parser.value()

    with open(dest, 'w', encoding='utf-8') as fh:
        json.dump(data, fh, ensure_ascii=False, indent=1)

    print(f'{dest}: ' + ', '.join(f'{k}={len(v)}' for k, v in data.items()))


if __name__ == '__main__':
    main()
