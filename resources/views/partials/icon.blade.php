@php
$paths=[
'search'=>'<circle cx="10.5" cy="10.5" r="6.5"/><path d="m16 16 4.5 4.5"/>',
'cart'=>'<path d="M3 3h2l2.2 12h11l2-9H6M9 20h.01M18 20h.01"/><circle cx="9" cy="20" r="1"/><circle cx="18" cy="20" r="1"/>',
'user'=>'<circle cx="12" cy="7" r="3.5"/><path d="M5 21v-3a7 7 0 0 1 14 0v3"/>',
'users'=>'<circle cx="9" cy="8" r="3"/><path d="M3 21v-3a6 6 0 0 1 12 0v3M16 5a3 3 0 0 1 0 6m2 4a5 5 0 0 1 3 5"/>',
'heart'=>'<path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8L12 21l8.8-8.6a5.5 5.5 0 0 0 0-7.8Z"/>',
'menu'=>'<path d="M4 6h16M4 12h16M4 18h16"/>','close'=>'<path d="m6 6 12 12M6 18 18 6"/>',
'left'=>'<path d="m15 5-7 7 7 7"/>','right'=>'<path d="m9 5 7 7-7 7"/>','down'=>'<path d="m6 9 6 6 6-6"/>','arrow'=>'<path d="M20 12H4m6-6-6 6 6 6"/>',
'phone'=>'<rect x="6" y="2" width="12" height="20" rx="2"/><path d="M10 5h4m-2 14h.01"/>','laptop'=>'<path d="M5 16V4h14v12M2 17h20l-2 3H4z"/>',
'headphones'=>'<path d="M3 14v-3a9 9 0 0 1 18 0v3M21 17v2a3 3 0 0 1-3 3h-3"/><rect x="2" y="11" width="4" height="7" rx="2"/><rect x="18" y="11" width="4" height="7" rx="2"/>',
'watch'=>'<rect x="6" y="6" width="12" height="12" rx="3"/><path d="m9 6 1-4h4l1 4m-6 12 1 4h4l1-4m-3-8v3l2 1"/>','game'=>'<path d="M8 7h8c3 0 4 2 5 7s-1 7-4 3l-2-2H9l-2 2c-3 4-5 2-4-3S5 7 8 7Z"/><path d="M8 10v4m-2-2h4m6-1h.01M18 13h.01"/>',
'box'=>'<path d="m12 2 9 5v10l-9 5-9-5V7zM3 7l9 5 9-5M12 12v10M7 4.8l9 5v5"/>','grid'=>'<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>',
'truck'=>'<path d="M1 4h13v13H1zM14 9h4l4 5v3h-8"/><circle cx="5" cy="18" r="3"/><circle cx="18" cy="18" r="3"/>',
'shield'=>'<path d="m12 2 8 4v6c0 5-8 10-8 10S4 17 4 12V6zM8 12l3 3 5-6"/>','return'=>'<path d="M3 10a9 9 0 1 1 1 8M3 3v7h7"/>',
'pin'=>'<path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',
'mail'=>'<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 5 10 8L22 5"/>','call'=>'<path d="m4 3 4-1 3 6-3 2c2 3 3 4 6 6l2-3 6 3-1 4c-1 5-10 0-14-4S-1 4 4 3Z"/>',
'star'=>'<path d="m12 2 3 6 7 1-5 5 1 7-6-3-6 3 1-7-5-5 7-1z"/>','plus'=>'<path d="M12 5v14M5 12h14"/>','minus'=>'<path d="M5 12h14"/>',
'trash'=>'<path d="M3 6h18M9 6V3h6v3M5 6l1 15h12l1-15M10 10v7m4-7v7"/>','check'=>'<path d="m5 12 4 4L19 6"/>','clock'=>'<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
'logout'=>'<path d="M10 3H4v18h6m-1-9h13m-5-5 5 5-5 5"/>','settings'=>'<path d="m9 3-1 3-3 1-2 4 2 2v4l4 3 3-1 3 1 4-3v-4l2-2-2-4-3-1-1-3z"/><circle cx="12" cy="12" r="3"/>',
'ticket'=>'<path d="M3 4h18v6a2 2 0 0 0 0 4v6H3v-6a2 2 0 0 0 0-4zM15 4v3m0 3v4m0 3v3"/>','image'=>'<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8" cy="8" r="2"/><path d="m3 17 5-5 4 4 4-6 5 7"/>','edit'=>'<path d="m15 3 6 6-12 12H3v-6zM12 6l6 6"/>','chart'=>'<path d="M3 3v18h18M7 16v-5m5 5V7m5 9V4"/>','wallet'=>'<rect x="2" y="5" width="20" height="15" rx="2"/><path d="M3 5V3h15v2m4 6h-7v5h7m-4-2h.01"/>','message'=>'<path d="M21 3H3v14h5l4 5 4-5h5zM7 7h10M7 11h7"/>','book'=>'<path d="M12 5C8 2 4 3 2 4v16c4-2 6-1 10 1 4-2 6-3 10-1V4c-4-2-7-1-10 1Zm0 0v16"/>','filter'=>'<path d="M3 5h18l-7 8v7l-4-2v-5z"/>','eye'=>'<path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/>','lock'=>'<rect x="4" y="10" width="16" height="12" rx="2"/><path d="M8 10V6a4 4 0 0 1 8 0v4m-4 5v3"/>','camera'=>'<path d="M3 6h5l2-3h4l2 3h5v15H3z"/><circle cx="12" cy="13" r="4"/>','monitor'=>'<rect x="2" y="3" width="20" height="14" rx="2"/><path d="M12 17v4m-5 0h10"/>','charger'=>'<rect x="5" y="7" width="14" height="15" rx="3"/><path d="M8 7V2m8 5V2m-3 9-3 4h4l-3 4"/>','tablet'=>'<rect x="4" y="2" width="16" height="20" rx="2"/><path d="M11 19h2"/>'
];
@endphp
<svg class="icon {{ $class ?? '' }}" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $paths[$name] ?? $paths['box'] !!}</svg>
