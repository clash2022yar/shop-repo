{{--
    Digino icon set — every icon in the project is an inline SVG so it inherits
    `currentColor` and can be recoloured with a Tailwind text-* class.
    Usage:  <x-icon name="cart" class="h-5 w-5 text-brand-500" />
            {!! svg_icon('cart', 'h-4 w-4') !!}
--}}
@props(['name' => 'circle', 'class' => 'h-5 w-5'])

@php
    $stroke = 'fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"';
@endphp

<svg viewBox="0 0 24 24" class="{{ $class }}" aria-hidden="true" focusable="false" {!! $attributes->toHtml() !!}>
@switch($name)

{{-- ─────────────────────────────  navigation  ───────────────────────────── --}}
@case('search')
    <circle cx="11" cy="11" r="7" {!! $stroke !!}/><path d="M20 20l-3.5-3.5" {!! $stroke !!}/>
    @break
@case('menu')
    <path d="M4 6h16M4 12h16M4 18h16" {!! $stroke !!}/>
    @break
@case('grid-menu')
    <path d="M4 7h16M4 12h16M4 17h10" {!! $stroke !!}/>
    @break
@case('close')
    <path d="M6 6l12 12M18 6L6 18" {!! $stroke !!}/>
    @break
@case('chevron-left')
    <path d="M15 5l-7 7 7 7" {!! $stroke !!}/>
    @break
@case('chevron-right')
    <path d="M9 5l7 7-7 7" {!! $stroke !!}/>
    @break
@case('chevron-down')
    <path d="M5 9l7 7 7-7" {!! $stroke !!}/>
    @break
@case('chevron-up')
    <path d="M5 15l7-7 7 7" {!! $stroke !!}/>
    @break
@case('arrow-left')
    <path d="M19 12H5M11 18l-6-6 6-6" {!! $stroke !!}/>
    @break
@case('arrow-right')
    <path d="M5 12h14M13 6l6 6-6 6" {!! $stroke !!}/>
    @break
@case('arrow-up')
    <path d="M12 19V5M6 11l6-6 6 6" {!! $stroke !!}/>
    @break
@case('arrow-down')
    <path d="M12 5v14M18 13l-6 6-6-6" {!! $stroke !!}/>
    @break
@case('external')
    <path d="M14 4h6v6M20 4l-8 8M18 14v5a1 1 0 01-1 1H5a1 1 0 01-1-1V7a1 1 0 011-1h5" {!! $stroke !!}/>
    @break

{{-- ────────────────────────────────  shop  ──────────────────────────────── --}}
@case('cart')
    <path d="M3 4h2.2l2 11.2A2 2 0 009.16 17h7.9a2 2 0 001.96-1.6L20.5 7.5H6" {!! $stroke !!}/>
    <circle cx="9.5" cy="20" r="1.4" {!! $stroke !!}/><circle cx="17.5" cy="20" r="1.4" {!! $stroke !!}/>
    @break
@case('bag')
    <path d="M6 8h12l-1 12H7L6 8z" {!! $stroke !!}/><path d="M9.5 8V6.5a2.5 2.5 0 015 0V8" {!! $stroke !!}/>
    @break
@case('shopping-bag')
    <path d="M5.5 8h13l-1.1 11.2a2 2 0 01-2 1.8H8.6a2 2 0 01-2-1.8L5.5 8z" {!! $stroke !!}/>
    <path d="M9 8V6a3 3 0 016 0v2" {!! $stroke !!}/>
    @break
@case('store')
    <path d="M4 10v9a1 1 0 001 1h14a1 1 0 001-1v-9" {!! $stroke !!}/>
    <path d="M3 6.5L4.5 4h15L21 6.5a3 3 0 01-6 0 3 3 0 01-6 0 3 3 0 01-6 0z" {!! $stroke !!}/>
    <path d="M9.5 20v-5h5v5" {!! $stroke !!}/>
    @break
@case('tag')
    <path d="M3.5 11.6V4.5a1 1 0 011-1h7.1a1 1 0 01.7.3l8 8a1 1 0 010 1.4l-7.1 7.1a1 1 0 01-1.4 0l-8-8a1 1 0 01-.3-.7z" {!! $stroke !!}/>
    <circle cx="8" cy="8" r="1.4" {!! $stroke !!}/>
    @break
@case('percent')
    <path d="M18 6L6 18" {!! $stroke !!}/><circle cx="8" cy="8" r="2.2" {!! $stroke !!}/><circle cx="16" cy="16" r="2.2" {!! $stroke !!}/>
    @break
@case('gift')
    <path d="M4 11h16v9a1 1 0 01-1 1H5a1 1 0 01-1-1v-9zM3 7.5h18V11H3z" {!! $stroke !!}/>
    <path d="M12 7.5V21M12 7.5S10.5 3.5 8 3.5a2 2 0 100 4h4zM12 7.5s1.5-4 4-4a2 2 0 110 4h-4z" {!! $stroke !!}/>
    @break
@case('box')
    <path d="M20.5 8.2v7.6a1.5 1.5 0 01-.8 1.3l-6.9 3.7a1.5 1.5 0 01-1.6 0l-6.9-3.7a1.5 1.5 0 01-.8-1.3V8.2" {!! $stroke !!}/>
    <path d="M3.9 7.4l7.3-3.9a1.5 1.5 0 011.6 0l7.3 3.9-8.1 4.4L3.9 7.4z" {!! $stroke !!}/><path d="M12 11.8V20" {!! $stroke !!}/>
    @break
@case('package')
    <path d="M4 7.5l8-4 8 4v9l-8 4-8-4v-9z" {!! $stroke !!}/><path d="M4 7.5l8 4 8-4M12 11.5V20M8 5.5l8 4" {!! $stroke !!}/>
    @break
@case('truck')
    <path d="M2.5 6.5h11v9h-11z" {!! $stroke !!}/><path d="M13.5 9.5h3.7l3.3 3.2v2.8h-7z" {!! $stroke !!}/>
    <circle cx="6.5" cy="17.5" r="1.8" {!! $stroke !!}/><circle cx="17" cy="17.5" r="1.8" {!! $stroke !!}/>
    @break
@case('shield')
@case('shield-check')
    <path d="M12 3l7.5 3v5.6c0 4.3-3 8.2-7.5 9.4-4.5-1.2-7.5-5.1-7.5-9.4V6L12 3z" {!! $stroke !!}/>
    <path d="M8.8 12.2l2.2 2.2 4.2-4.4" {!! $stroke !!}/>
    @break
@case('rotate-left')
    <path d="M4 9h6M4 9V3.5" {!! $stroke !!}/>
    <path d="M4.6 9.4A8 8 0 1112 20a8 8 0 01-6.9-4" {!! $stroke !!}/>
    @break
@case('headset')
@case('support')
    <path d="M4 13v-1a8 8 0 1116 0v1" {!! $stroke !!}/>
    <path d="M4 13h2.5a1 1 0 011 1v3.5a1 1 0 01-1 1H5.5A1.5 1.5 0 014 17V13zM20 13h-2.5a1 1 0 00-1 1v3.5a1 1 0 001 1h1A1.5 1.5 0 0020 17V13z" {!! $stroke !!}/>
    <path d="M18.5 18.5V19a2.5 2.5 0 01-2.5 2.5h-2.5" {!! $stroke !!}/>
    @break
@case('wallet')
    <path d="M3.5 7.5A2 2 0 015.5 5.5h11a2 2 0 012 2v1h-13" {!! $stroke !!}/>
    <path d="M3.5 7.5v9a2 2 0 002 2h13a2 2 0 002-2v-7a2 2 0 00-2-2" {!! $stroke !!}/>
    <circle cx="17" cy="13" r="1.1" fill="currentColor" stroke="none"/>
    @break
@case('credit-card')
    <rect x="2.5" y="5.5" width="19" height="13" rx="2.2" {!! $stroke !!}/><path d="M2.5 10h19" {!! $stroke !!}/>
    <path d="M6 14.5h3" {!! $stroke !!}/>
    @break
@case('receipt')
    <path d="M6 3.5h12v17l-2-1.4-2 1.4-2-1.4-2 1.4-2-1.4-2 1.4v-17z" {!! $stroke !!}/>
    <path d="M9 8.5h6M9 12.5h6" {!! $stroke !!}/>
    @break
@case('scale')
@case('compare')
    <path d="M12 4v16M7 20h10M4 9l3-4 3 4a3 3 0 01-6 0zM14 9l3-4 3 4a3 3 0 01-6 0z" {!! $stroke !!}/>
    @break

{{-- ───────────────────────────────  user  ──────────────────────────────── --}}
@case('user')
    <circle cx="12" cy="8" r="3.6" {!! $stroke !!}/><path d="M4.5 20a7.5 7.5 0 0115 0" {!! $stroke !!}/>
    @break
@case('users')
    <circle cx="9" cy="8" r="3.2" {!! $stroke !!}/><path d="M2.8 19.5a6.2 6.2 0 0112.4 0" {!! $stroke !!}/>
    <path d="M16 5.4a3.2 3.2 0 010 5.2M17.5 13.6a6.2 6.2 0 013.7 5.9" {!! $stroke !!}/>
    @break
@case('user-plus')
    <circle cx="9.5" cy="8" r="3.4" {!! $stroke !!}/><path d="M3 20a6.5 6.5 0 0113 0" {!! $stroke !!}/>
    <path d="M18 7.5v5M20.5 10h-5" {!! $stroke !!}/>
    @break
@case('logout')
    <path d="M14.5 8V6a2 2 0 00-2-2h-6a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2v-2" {!! $stroke !!}/>
    <path d="M19.5 12H9.5M12.5 8.5L9 12l3.5 3.5" {!! $stroke !!}/>
    @break
@case('login')
    <path d="M9.5 8V6a2 2 0 012-2h6a2 2 0 012 2v12a2 2 0 01-2 2h-6a2 2 0 01-2-2v-2" {!! $stroke !!}/>
    <path d="M4.5 12h10M11.5 8.5L15 12l-3.5 3.5" {!! $stroke !!}/>
    @break
@case('lock')
    <rect x="4.5" y="10.5" width="15" height="10" rx="2" {!! $stroke !!}/>
    <path d="M8 10.5V7.5a4 4 0 018 0v3" {!! $stroke !!}/><circle cx="12" cy="15.5" r="1.2" fill="currentColor" stroke="none"/>
    @break
@case('key')
    <circle cx="8" cy="12" r="3.5" {!! $stroke !!}/><path d="M11.5 12H21M18 12v3M15 12v2.2" {!! $stroke !!}/>
    @break
@case('eye')
    <path d="M2.5 12S6 5.8 12 5.8 21.5 12 21.5 12 18 18.2 12 18.2 2.5 12 2.5 12z" {!! $stroke !!}/>
    <circle cx="12" cy="12" r="2.8" {!! $stroke !!}/>
    @break
@case('eye-off')
    <path d="M4 4l16 16" {!! $stroke !!}/>
    <path d="M9.9 6.2A9.9 9.9 0 0112 6c6 0 9.5 6 9.5 6a17 17 0 01-3.3 3.9M6.4 8.3A16.6 16.6 0 002.5 12S6 18 12 18a9.6 9.6 0 003.6-.7" {!! $stroke !!}/>
    <path d="M10.2 10.4a2.8 2.8 0 003.9 3.9" {!! $stroke !!}/>
    @break

{{-- ─────────────────────────────  feedback  ────────────────────────────── --}}
@case('heart')
    <path d="M12 20s-7.5-4.6-7.5-9.6A4.4 4.4 0 0112 7.6a4.4 4.4 0 017.5 2.8C19.5 15.4 12 20 12 20z" {!! $stroke !!}/>
    @break
@case('star')
    <path d="M12 3.6l2.6 5.3 5.9.9-4.3 4.1 1 5.8-5.2-2.7-5.2 2.7 1-5.8L3.5 9.8l5.9-.9L12 3.6z" fill="currentColor" stroke="none"/>
    @break
@case('star-outline')
    <path d="M12 3.6l2.6 5.3 5.9.9-4.3 4.1 1 5.8-5.2-2.7-5.2 2.7 1-5.8L3.5 9.8l5.9-.9L12 3.6z" {!! $stroke !!}/>
    @break
@case('star-half')
    <defs><linearGradient id="dgHalf"><stop offset="50%" stop-color="currentColor"/><stop offset="50%" stop-color="transparent"/></linearGradient></defs>
    <path d="M12 3.6l2.6 5.3 5.9.9-4.3 4.1 1 5.8-5.2-2.7-5.2 2.7 1-5.8L3.5 9.8l5.9-.9L12 3.6z" fill="url(#dgHalf)" stroke="currentColor" stroke-width="1.2"/>
    @break
@case('check')
    <path d="M5 12.5l4.5 4.5L19 7.5" {!! $stroke !!}/>
    @break
@case('check-circle')
    <circle cx="12" cy="12" r="8.5" {!! $stroke !!}/><path d="M8.4 12.3l2.4 2.4 4.8-5" {!! $stroke !!}/>
    @break
@case('x-circle')
    <circle cx="12" cy="12" r="8.5" {!! $stroke !!}/><path d="M9.2 9.2l5.6 5.6M14.8 9.2l-5.6 5.6" {!! $stroke !!}/>
    @break
@case('info')
    <circle cx="12" cy="12" r="8.5" {!! $stroke !!}/><path d="M12 11v5" {!! $stroke !!}/>
    <circle cx="12" cy="8" r="1" fill="currentColor" stroke="none"/>
    @break
@case('alert')
    <path d="M12 4.5l8.5 15h-17l8.5-15z" {!! $stroke !!}/><path d="M12 10v4" {!! $stroke !!}/>
    <circle cx="12" cy="16.6" r="1" fill="currentColor" stroke="none"/>
    @break
@case('question')
    <circle cx="12" cy="12" r="8.5" {!! $stroke !!}/>
    <path d="M9.7 9.4a2.4 2.4 0 114.1 1.7c-.9.9-1.8 1.3-1.8 2.6" {!! $stroke !!}/>
    <circle cx="12" cy="16.6" r="1" fill="currentColor" stroke="none"/>
    @break
@case('chat')
    <path d="M20 12.5c0 3.8-3.6 6.9-8 6.9a9.4 9.4 0 01-2.7-.4L4.5 20.5l1.2-3.4A6.5 6.5 0 014 12.5c0-3.8 3.6-6.9 8-6.9s8 3.1 8 6.9z" {!! $stroke !!}/>
    @break
@case('thumb-up')
    <path d="M7 10.5v9H4.5a1 1 0 01-1-1v-7a1 1 0 011-1H7z" {!! $stroke !!}/>
    <path d="M7 10.5l3.8-6.4a1.6 1.6 0 012.9 1.2L13 9h5.2a1.8 1.8 0 011.8 2.2l-1.3 6a1.8 1.8 0 01-1.8 1.3H7" {!! $stroke !!}/>
    @break
@case('thumb-down')
    <path d="M17 13.5v-9h2.5a1 1 0 011 1v7a1 1 0 01-1 1H17z" {!! $stroke !!}/>
    <path d="M17 13.5l-3.8 6.4a1.6 1.6 0 01-2.9-1.2l.7-3.7H5.8A1.8 1.8 0 014 12.8l1.3-6a1.8 1.8 0 011.8-1.3H17" {!! $stroke !!}/>
    @break
@case('bell')
    <path d="M6.5 10a5.5 5.5 0 1111 0c0 4 1.5 5.5 1.5 5.5H5S6.5 14 6.5 10z" {!! $stroke !!}/>
    <path d="M10.2 19a2 2 0 003.6 0" {!! $stroke !!}/>
    @break

{{-- ────────────────────────────  admin / ui  ───────────────────────────── --}}
@case('dashboard')
    <rect x="3.5" y="3.5" width="7" height="7" rx="1.6" {!! $stroke !!}/>
    <rect x="13.5" y="3.5" width="7" height="7" rx="1.6" {!! $stroke !!}/>
    <rect x="3.5" y="13.5" width="7" height="7" rx="1.6" {!! $stroke !!}/>
    <rect x="13.5" y="13.5" width="7" height="7" rx="1.6" {!! $stroke !!}/>
    @break
@case('layers')
    <path d="M12 3.5l8.5 4.3-8.5 4.3-8.5-4.3L12 3.5z" {!! $stroke !!}/>
    <path d="M3.5 12.2l8.5 4.3 8.5-4.3M3.5 16.2l8.5 4.3 8.5-4.3" {!! $stroke !!}/>
    @break
@case('list')
    <path d="M8 6.5h12M8 12h12M8 17.5h12" {!! $stroke !!}/>
    <circle cx="4.3" cy="6.5" r="1" fill="currentColor" stroke="none"/>
    <circle cx="4.3" cy="12" r="1" fill="currentColor" stroke="none"/>
    <circle cx="4.3" cy="17.5" r="1" fill="currentColor" stroke="none"/>
    @break
@case('grid')
    <rect x="3.5" y="3.5" width="7.5" height="7.5" rx="1.4" {!! $stroke !!}/>
    <rect x="13" y="3.5" width="7.5" height="7.5" rx="1.4" {!! $stroke !!}/>
    <rect x="3.5" y="13" width="7.5" height="7.5" rx="1.4" {!! $stroke !!}/>
    <rect x="13" y="13" width="7.5" height="7.5" rx="1.4" {!! $stroke !!}/>
    @break
@case('filter')
    <path d="M3.5 5.5h17l-6.5 7.6v5.4l-4 2v-7.4L3.5 5.5z" {!! $stroke !!}/>
    @break
@case('sort')
    <path d="M7 4.5v15M7 19.5L4 16.5M7 19.5l3-3M17 19.5v-15M17 4.5l-3 3M17 4.5l3 3" {!! $stroke !!}/>
    @break
@case('sliders')
    <path d="M4 7h9M17 7h3M4 12h3M11 12h9M4 17h7M15 17h5" {!! $stroke !!}/>
    <circle cx="15" cy="7" r="2" {!! $stroke !!}/><circle cx="9" cy="12" r="2" {!! $stroke !!}/><circle cx="13" cy="17" r="2" {!! $stroke !!}/>
    @break
@case('settings')
    <circle cx="12" cy="12" r="3" {!! $stroke !!}/>
    <path d="M19.4 14.5a1.6 1.6 0 00.3 1.8l.1.1a2 2 0 11-2.8 2.8l-.1-.1a1.6 1.6 0 00-1.8-.3 1.6 1.6 0 00-1 1.5v.2a2 2 0 11-4 0v-.1a1.6 1.6 0 00-1-1.5 1.6 1.6 0 00-1.8.3l-.1.1a2 2 0 11-2.8-2.8l.1-.1a1.6 1.6 0 00.3-1.8 1.6 1.6 0 00-1.5-1H3a2 2 0 010-4h.1a1.6 1.6 0 001.5-1 1.6 1.6 0 00-.3-1.8l-.1-.1a2 2 0 112.8-2.8l.1.1a1.6 1.6 0 001.8.3H9a1.6 1.6 0 001-1.5V3a2 2 0 014 0v.1a1.6 1.6 0 001 1.5 1.6 1.6 0 001.8-.3l.1-.1a2 2 0 112.8 2.8l-.1.1a1.6 1.6 0 00-.3 1.8V9a1.6 1.6 0 001.5 1h.2a2 2 0 010 4h-.1a1.6 1.6 0 00-1.5 1z" {!! $stroke !!}/>
    @break
@case('edit')
    <path d="M4 20h4l10-10a2.8 2.8 0 10-4-4L4 16v4z" {!! $stroke !!}/><path d="M13.5 6.5l4 4" {!! $stroke !!}/>
    @break
@case('trash')
    <path d="M4.5 6.5h15M9.5 6.5V4.8a1.3 1.3 0 011.3-1.3h2.4a1.3 1.3 0 011.3 1.3v1.7" {!! $stroke !!}/>
    <path d="M6.5 6.5l.9 12.2a1.6 1.6 0 001.6 1.5h6a1.6 1.6 0 001.6-1.5l.9-12.2" {!! $stroke !!}/>
    <path d="M10.2 10.5v6M13.8 10.5v6" {!! $stroke !!}/>
    @break
@case('plus')
    <path d="M12 5v14M5 12h14" {!! $stroke !!}/>
    @break
@case('minus')
    <path d="M5 12h14" {!! $stroke !!}/>
    @break
@case('copy')
    <rect x="8.5" y="8.5" width="12" height="12" rx="2" {!! $stroke !!}/>
    <path d="M15.5 5.5v-1a1 1 0 00-1-1h-9a2 2 0 00-2 2v9a1 1 0 001 1h1" {!! $stroke !!}/>
    @break
@case('save')
    <path d="M4.5 5.5A1.5 1.5 0 016 4h10.2L20 7.8V18a1.5 1.5 0 01-1.5 1.5h-13A1.5 1.5 0 014 18V5.5z" {!! $stroke !!}/>
    <path d="M8 4v5h7V4M8 19.5v-5h8v5" {!! $stroke !!}/>
    @break
@case('upload')
    <path d="M12 16V4M8 8l4-4 4 4" {!! $stroke !!}/><path d="M4 15v3.5A1.5 1.5 0 005.5 20h13a1.5 1.5 0 001.5-1.5V15" {!! $stroke !!}/>
    @break
@case('download')
    <path d="M12 4v12M8 12l4 4 4-4" {!! $stroke !!}/><path d="M4 16v2.5A1.5 1.5 0 005.5 20h13a1.5 1.5 0 001.5-1.5V16" {!! $stroke !!}/>
    @break
@case('image')
    <rect x="3.5" y="4.5" width="17" height="15" rx="2" {!! $stroke !!}/>
    <circle cx="8.6" cy="9.6" r="1.6" {!! $stroke !!}/><path d="M4 17l4.8-4.6 3.4 3.2 3-2.8L20.5 17" {!! $stroke !!}/>
    @break
@case('printer')
    <path d="M7 8.5V4h10v4.5" {!! $stroke !!}/>
    <path d="M5 8.5h14a2 2 0 012 2v5h-4v4H7v-4H3v-5a2 2 0 012-2z" {!! $stroke !!}/>
    @break
@case('refresh')
    <path d="M20 12a8 8 0 11-2.4-5.7" {!! $stroke !!}/><path d="M20 4v4.5h-4.5" {!! $stroke !!}/>
    @break
@case('chart')
    <path d="M4 20V4M4 20h16" {!! $stroke !!}/><path d="M8 16.5v-5M12 16.5v-9M16 16.5v-3.5M20 16.5v-7" {!! $stroke !!}/>
    @break
@case('trend-up')
    <path d="M3.5 16.5l6-6 3.5 3.5 7-7" {!! $stroke !!}/><path d="M15 7h5.5v5.5" {!! $stroke !!}/>
    @break
@case('trend-down')
    <path d="M3.5 7.5l6 6 3.5-3.5 7 7" {!! $stroke !!}/><path d="M15 17h5.5v-5.5" {!! $stroke !!}/>
    @break
@case('clock')
    <circle cx="12" cy="12" r="8.5" {!! $stroke !!}/><path d="M12 7.5V12l3 2" {!! $stroke !!}/>
    @break
@case('calendar')
    <rect x="3.5" y="5.5" width="17" height="15" rx="2" {!! $stroke !!}/>
    <path d="M3.5 10h17M8 3.5v4M16 3.5v4" {!! $stroke !!}/>
    @break
@case('warehouse')
    <path d="M3.5 20V9l8.5-4.5L20.5 9v11" {!! $stroke !!}/><path d="M7.5 20v-6h9v6M7.5 17h9" {!! $stroke !!}/>
    @break
@case('file')
    <path d="M6 3.5h7l5 5V20a1 1 0 01-1 1H6a1 1 0 01-1-1V4.5a1 1 0 011-1z" {!! $stroke !!}/>
    <path d="M13 3.5v5h5M8.5 13h7M8.5 16.5h5" {!! $stroke !!}/>
    @break
@case('newspaper')
    <path d="M3.5 6.5A1 1 0 014.5 5.5h12a1 1 0 011 1V18a2 2 0 002 2h-14a2 2 0 01-2-2V6.5z" {!! $stroke !!}/>
    <path d="M17.5 9h2a1 1 0 011 1v8a2 2 0 01-2 2M6.5 9h8M6.5 12.5h8M6.5 16h5" {!! $stroke !!}/>
    @break
@case('ticket')
    <path d="M3.5 9.5V7a1.5 1.5 0 011.5-1.5h14A1.5 1.5 0 0120.5 7v2.5a2.5 2.5 0 000 5V17a1.5 1.5 0 01-1.5 1.5H5A1.5 1.5 0 013.5 17v-2.5a2.5 2.5 0 000-5z" {!! $stroke !!}/>
    <path d="M13 6v2M13 11v2M13 16v2" {!! $stroke !!}/>
    @break
@case('flag')
    <path d="M5 21V4M5 4.8h11l-2 3.4 2 3.4H5" {!! $stroke !!}/>
    @break
@case('spinner')
    <path d="M12 3.5v3.2M12 17.3v3.2M20.5 12h-3.2M6.7 12H3.5M18 6l-2.3 2.3M8.3 15.7L6 18M18 18l-2.3-2.3M8.3 8.3L6 6" {!! $stroke !!}/>
    @break

{{-- ─────────────────────────  contact / social  ────────────────────────── --}}
@case('phone')
    <path d="M6.6 3.5h3l1.5 3.8-2 1.4a12 12 0 006.2 6.2l1.4-2 3.8 1.5v3a1.6 1.6 0 01-1.8 1.6C11.6 18.2 5.8 12.4 5 4.9A1.6 1.6 0 016.6 3.5z" {!! $stroke !!}/>
    @break
@case('mail')
    <rect x="3" y="5.5" width="18" height="13" rx="2" {!! $stroke !!}/><path d="M3.6 7l8.4 6 8.4-6" {!! $stroke !!}/>
    @break
@case('map-pin')
    <path d="M12 21s7-5.6 7-11a7 7 0 10-14 0c0 5.4 7 11 7 11z" {!! $stroke !!}/><circle cx="12" cy="10" r="2.6" {!! $stroke !!}/>
    @break
@case('instagram')
    <rect x="3.5" y="3.5" width="17" height="17" rx="4.6" {!! $stroke !!}/>
    <circle cx="12" cy="12" r="4" {!! $stroke !!}/><circle cx="17" cy="7" r="1.1" fill="currentColor" stroke="none"/>
    @break
@case('telegram')
    <path d="M21 4.5L2.8 11.4a.5.5 0 00.05.95l4.6 1.3 1.75 5.3a.5.5 0 00.87.16l2.4-2.7 4.6 3.4a.6.6 0 00.94-.34L21.4 5.2a.6.6 0 00-.4-.7z" {!! $stroke !!}/>
    <path d="M7.45 13.65L18 7.2l-8.2 8v4.1" {!! $stroke !!}/>
    @break
@case('linkedin')
    <rect x="3.5" y="3.5" width="17" height="17" rx="3" {!! $stroke !!}/>
    <path d="M8 10.5v6M8 7.6v.1M11.6 16.5v-6M11.6 13a2.4 2.4 0 014.8 0v3.5" {!! $stroke !!}/>
    @break
@case('twitter')
    <path d="M4 4l7 9.2M20 20l-7.4-9.5M4.5 20L11 13M13 11L19.5 4" {!! $stroke !!}/>
    @break
@case('link')
    <path d="M10 13.5a3.5 3.5 0 005 0l3-3a3.5 3.5 0 00-5-5l-1.4 1.4" {!! $stroke !!}/>
    <path d="M14 10.5a3.5 3.5 0 00-5 0l-3 3a3.5 3.5 0 005 5l1.4-1.4" {!! $stroke !!}/>
    @break
@case('share')
    <circle cx="18" cy="6" r="2.5" {!! $stroke !!}/><circle cx="6" cy="12" r="2.5" {!! $stroke !!}/><circle cx="18" cy="18" r="2.5" {!! $stroke !!}/>
    <path d="M8.2 10.8l7.6-3.6M8.2 13.2l7.6 3.6" {!! $stroke !!}/>
    @break
@case('apple')
    <path d="M15.8 12.4c0-2.3 1.9-3.4 2-3.5-1.1-1.6-2.8-1.8-3.4-1.8-1.4-.2-2.8.8-3.5.8s-1.9-.8-3-.8c-1.6 0-3 .9-3.8 2.3-1.6 2.8-.4 7 1.2 9.3.8 1.1 1.7 2.4 2.9 2.3 1.2 0 1.6-.7 3-.7s1.8.7 3 .7 2-1.1 2.8-2.2a9.2 9.2 0 001.3-2.6c-.1 0-2.5-1-2.5-3.8z" {!! $stroke !!}/>
    <path d="M13.6 5.3A3.6 3.6 0 0014.4 2a3.9 3.9 0 00-2.5 1.3 3.4 3.4 0 00-.9 3.2 3.2 3.2 0 002.6-1.2z" {!! $stroke !!}/>
    @break
@case('android')
    <path d="M6 10.5h12v6a2 2 0 01-2 2H8a2 2 0 01-2-2v-6z" {!! $stroke !!}/>
    <path d="M6 10.5a6 6 0 0112 0M9 18.5v2M15 18.5v2M3.5 11.5v4M20.5 11.5v4M8.5 5.5l-1-2M15.5 5.5l1-2" {!! $stroke !!}/>
    <circle cx="9.5" cy="8" r=".8" fill="currentColor" stroke="none"/><circle cx="14.5" cy="8" r=".8" fill="currentColor" stroke="none"/>
    @break

{{-- ─────────────────────────  product specs  ───────────────────────────── --}}
@case('screen')
    <rect x="6.5" y="2.5" width="11" height="19" rx="2.2" {!! $stroke !!}/><path d="M10.5 19h3" {!! $stroke !!}/>
    @break
@case('cpu')
    <rect x="7" y="7" width="10" height="10" rx="1.6" {!! $stroke !!}/><rect x="10" y="10" width="4" height="4" rx=".8" {!! $stroke !!}/>
    <path d="M10 4v3M14 4v3M10 17v3M14 17v3M4 10h3M4 14h3M17 10h3M17 14h3" {!! $stroke !!}/>
    @break
@case('memory')
    <rect x="3" y="7.5" width="18" height="9" rx="1.6" {!! $stroke !!}/>
    <path d="M7 11v2M10.3 11v2M13.6 11v2M17 11v2M6 16.5v2M18 16.5v2" {!! $stroke !!}/>
    @break
@case('camera')
    <path d="M3.5 8.5A1.5 1.5 0 015 7h2.2l1.2-2h7.2l1.2 2H19a1.5 1.5 0 011.5 1.5v9A1.5 1.5 0 0119 19H5a1.5 1.5 0 01-1.5-1.5v-9z" {!! $stroke !!}/>
    <circle cx="12" cy="12.5" r="3.4" {!! $stroke !!}/>
    @break
@case('battery')
    <rect x="2.5" y="7.5" width="17" height="9" rx="2" {!! $stroke !!}/><path d="M21.5 10.5v3" {!! $stroke !!}/>
    <rect x="5" y="10" width="8" height="4" rx="1" fill="currentColor" stroke="none"/>
    @break
@case('sim')
    <path d="M5.5 4.5A1 1 0 016.5 3.5h7l5 5v11a1 1 0 01-1 1h-11a1 1 0 01-1-1v-15z" {!! $stroke !!}/>
    <rect x="8.5" y="11" width="7" height="6" rx="1" {!! $stroke !!}/>
    @break
@case('headphones')
    <path d="M4.5 15v-3a7.5 7.5 0 1115 0v3" {!! $stroke !!}/>
    <path d="M4.5 14.5h2a1 1 0 011 1v3.5a1 1 0 01-1 1h-1a1.5 1.5 0 01-1.5-1.5v-4zM19.5 14.5h-2a1 1 0 00-1 1v3.5a1 1 0 001 1h1a1.5 1.5 0 001.5-1.5v-4z" {!! $stroke !!}/>
    @break
@case('watch')
    <circle cx="12" cy="12" r="5.5" {!! $stroke !!}/><path d="M12 9.5V12l1.8 1.2" {!! $stroke !!}/>
    <path d="M9 6.8L9.4 3h5.2l.4 3.8M9 17.2l.4 3.8h5.2l.4-3.8" {!! $stroke !!}/>
    @break
@case('laptop')
    <rect x="4.5" y="5" width="15" height="10" rx="1.6" {!! $stroke !!}/><path d="M2.5 18h19" {!! $stroke !!}/>
    @break
@case('gamepad')
    <path d="M7.5 8.5h9a4.5 4.5 0 014.4 5.4l-.5 2.4a2.4 2.4 0 01-4.2 1.1L15 15.5H9l-1.2 1.9a2.4 2.4 0 01-4.2-1.1l-.5-2.4A4.5 4.5 0 017.5 8.5z" {!! $stroke !!}/>
    <path d="M7.2 11.3v2.4M6 12.5h2.4" {!! $stroke !!}/>
    <circle cx="16" cy="12" r=".9" fill="currentColor" stroke="none"/><circle cx="17.8" cy="13.8" r=".9" fill="currentColor" stroke="none"/>
    @break
@case('tablet')
    <rect x="4.5" y="2.5" width="15" height="19" rx="2.2" {!! $stroke !!}/><path d="M10.5 18.8h3" {!! $stroke !!}/>
    @break
@case('monitor')
    <rect x="2.5" y="4" width="19" height="12.5" rx="2" {!! $stroke !!}/><path d="M8.5 20h7M12 16.5V20" {!! $stroke !!}/>
    @break
@case('speaker')
    <rect x="6" y="2.5" width="12" height="19" rx="2.4" {!! $stroke !!}/><circle cx="12" cy="15" r="3.2" {!! $stroke !!}/>
    <circle cx="12" cy="6.5" r="1.2" {!! $stroke !!}/>
    @break
@case('home-appliance')
    <rect x="5" y="2.5" width="14" height="19" rx="2.2" {!! $stroke !!}/><path d="M5 9h14" {!! $stroke !!}/>
    <path d="M8 5.5v1M8 12v2" {!! $stroke !!}/>
    @break
@case('car')
    <path d="M4 15.5v3h3v-3M17 15.5v3h3v-3" {!! $stroke !!}/>
    <path d="M3.5 15.5v-3.2l2-4.3A1.6 1.6 0 017 7h10a1.6 1.6 0 011.5 1l2 4.3v3.2h-17z" {!! $stroke !!}/>
    <circle cx="7.2" cy="13" r="1" fill="currentColor" stroke="none"/><circle cx="16.8" cy="13" r="1" fill="currentColor" stroke="none"/>
    @break
@case('shirt')
    <path d="M8.5 3.5L4 6l1.6 4 2.4-.8V20.5h8V9.2l2.4.8L20 6l-4.5-2.5a3.5 3.5 0 01-7 0z" {!! $stroke !!}/>
    @break
@case('palette')
    <path d="M12 3.5a8.5 8.5 0 000 17c1.2 0 1.8-.9 1.8-1.8 0-.5-.2-.9-.5-1.2-.3-.3-.5-.7-.5-1.2 0-1 .8-1.8 1.8-1.8h1.6a4.3 4.3 0 004.3-4.3c0-3.7-3.8-6.7-8.5-6.7z" {!! $stroke !!}/>
    <circle cx="7.5" cy="11" r="1.1" fill="currentColor" stroke="none"/>
    <circle cx="10" cy="7.5" r="1.1" fill="currentColor" stroke="none"/>
    <circle cx="14.5" cy="7.8" r="1.1" fill="currentColor" stroke="none"/>
    @break
@case('cable')
    <path d="M5 3.5v5a3 3 0 003 3 3 3 0 013 3v6" {!! $stroke !!}/>
    <path d="M3.5 3.5h3M13 20.5h6M16 17.5v6" {!! $stroke !!}/>
    @break
@case('sd-card')
    <path d="M6 3.5h7.5L18 8v12.5a1 1 0 01-1 1H7a1 1 0 01-1-1V4.5a1 1 0 011-1z" {!! $stroke !!}/>
    <path d="M9.5 6v3M12 6v3M14.5 7v2" {!! $stroke !!}/>
    @break

@case('badge-check')
    <path d="M12 3.2l2.1 1.5 2.6-.2.8 2.5 2.1 1.5-1 2.4 1 2.4-2.1 1.5-.8 2.5-2.6-.2L12 20.8l-2.1-1.5-2.6.2-.8-2.5L4.4 15.5l1-2.4-1-2.4 2.1-1.5.8-2.5 2.6.2z" {!! $stroke !!}/>
    <path d="M9.2 12.2l1.9 1.9 3.7-4" {!! $stroke !!}/>
    @break
@case('calculator')
    <rect x="5" y="3" width="14" height="18" rx="2" {!! $stroke !!}/>
    <path d="M8 7h8M8.5 12h.01M12 12h.01M15.5 12h.01M8.5 16h.01M12 16h.01M15.5 16h.01" {!! $stroke !!}/>
    @break
@case('help')
    <circle cx="12" cy="12" r="8.5" {!! $stroke !!}/>
    <path d="M9.7 9.5a2.4 2.4 0 114.1 1.7c-.8.7-1.4 1.2-1.4 2.2" {!! $stroke !!}/>
    <circle cx="12" cy="16.6" r=".9" fill="currentColor" stroke="none"/>
    @break
@case('note')
    <path d="M6 3.5h8.5L19 8v12a1.5 1.5 0 01-1.5 1.5h-11A1.5 1.5 0 015 20V5a1.5 1.5 0 011-1.5z" {!! $stroke !!}/>
    <path d="M14 3.5V8h4.5M8.5 12h7M8.5 16h4.5" {!! $stroke !!}/>
    @break

{{-- ─────────────────────────  category glyphs  ───────────────────────── --}}
@case('mobile')
    <rect x="7" y="2.5" width="10" height="19" rx="2.2" {!! $stroke !!}/>
    <path d="M10.6 5.4h2.8M11 18.6h2" {!! $stroke !!}/>
    @break
@case('headphone')
    <path d="M4.5 14v-1.8a7.5 7.5 0 0115 0V14" {!! $stroke !!}/>
    <rect x="3" y="13.4" width="4" height="6.2" rx="1.6" {!! $stroke !!}/>
    <rect x="17" y="13.4" width="4" height="6.2" rx="1.6" {!! $stroke !!}/>
    @break
@case('home')
    <path d="M4 10.6L12 4l8 6.6" {!! $stroke !!}/>
    <path d="M6.2 9.6V19a1 1 0 001 1h9.6a1 1 0 001-1V9.6" {!! $stroke !!}/>
    <path d="M10 20v-5h4v5" {!! $stroke !!}/>
    @break
@case('fridge')
    <rect x="6" y="2.6" width="12" height="18.8" rx="2.2" {!! $stroke !!}/>
    <path d="M6 10h12M9 6v2.2M9 12.4v2.6" {!! $stroke !!}/>
    @break
@case('washer')
    <rect x="4.5" y="2.6" width="15" height="18.8" rx="2.2" {!! $stroke !!}/>
    <circle cx="12" cy="14" r="4.4" {!! $stroke !!}/>
    <path d="M8 6h3M16 6h.01" {!! $stroke !!}/>
    @break
@case('vacuum')
    <circle cx="9" cy="15.5" r="5.5" {!! $stroke !!}/>
    <circle cx="9" cy="15.5" r="1.8" {!! $stroke !!}/>
    <path d="M13.6 12.4l3.2-5.6a2.4 2.4 0 014.2 2.4l-2 3.4" {!! $stroke !!}/>
    @break
@case('kitchen')
    <path d="M6 3v7.5a2 2 0 002 2h.5V21" {!! $stroke !!}/>
    <path d="M6 3v5M9.5 3v5" {!! $stroke !!}/>
    <path d="M16.5 21v-6.5c2 0 3-1.4 3-4.5S18.6 3 16.5 3s-3 1.5-3 6c0 2.6.9 4.2 3 4.5" {!! $stroke !!}/>
    @break
@case('food')
    <path d="M3.5 11.5h17" {!! $stroke !!}/>
    <path d="M5 11.5a7 7 0 0114 0" {!! $stroke !!}/>
    <path d="M4 15.5h16a3.5 3.5 0 01-3.5 3.5h-9A3.5 3.5 0 014 15.5z" {!! $stroke !!}/>
    @break
@case('cup')
    <path d="M5 6h11v7a5.5 5.5 0 01-11 0z" {!! $stroke !!}/>
    <path d="M16 7.6h1.8a2.6 2.6 0 010 5.2H16" {!! $stroke !!}/>
    <path d="M4 21h13" {!! $stroke !!}/>
    @break
@case('book')
    <path d="M5 4.5A1.5 1.5 0 016.5 3H18v15H6.5A1.5 1.5 0 005 19.5z" {!! $stroke !!}/>
    <path d="M5 19.5A1.5 1.5 0 016.5 18H18v3H6.5A1.5 1.5 0 015 19.5z" {!! $stroke !!}/>
    @break
@case('pen')
    <path d="M4 20l1-4L16.4 4.6a2 2 0 012.9 0l.6.6a2 2 0 010 2.9L8.4 19.5z" {!! $stroke !!}/>
    <path d="M14.8 6.2l3.4 3.4" {!! $stroke !!}/>
    @break
@case('sport')
    <circle cx="12" cy="12" r="8.6" {!! $stroke !!}/>
    <path d="M5.4 6.4c3 2 3.9 8 1.7 12.1M18.6 6.4c-3 2-3.9 8-1.7 12.1" {!! $stroke !!}/>
    @break
@case('shoe')
    <path d="M3 16.5V10h3.4l2.4 2.2 3.1.6 2.7 1.7 5 1.1a2 2 0 011.6 2v1.4H3z" {!! $stroke !!}/>
    <path d="M6.4 10v3M9.8 12.8l1.2-1.8" {!! $stroke !!}/>
    @break
@case('suitcase')
    <rect x="3" y="7.5" width="18" height="12.5" rx="2.2" {!! $stroke !!}/>
    <path d="M9 7.5V5.4A1.4 1.4 0 0110.4 4h3.2A1.4 1.4 0 0115 5.4v2.1M9.5 20v1M14.5 20v1" {!! $stroke !!}/>
    @break
@case('perfume')
    <rect x="7" y="9" width="10" height="12" rx="2.2" {!! $stroke !!}/>
    <path d="M10.4 9V6.4h3.2V9M12 3.4v1.6M15.6 5.4h2.2v2.2" {!! $stroke !!}/>
    @break
@case('beauty')
    <path d="M9 3.5h3.4l1.2 5.2H7.8z" {!! $stroke !!}/>
    <rect x="7.8" y="8.7" width="4.8" height="11.8" rx="1.4" {!! $stroke !!}/>
    <path d="M15 12.5c2.2 0 3.6 1.6 3.6 4S17.2 20.5 15 20.5" {!! $stroke !!}/>
    @break
@case('tools')
    <path d="M14.6 3.4a4.6 4.6 0 015.9 5.9l-9.2 9.2-3.5 1.9 1.9-3.5z" {!! $stroke !!}/>
    <path d="M3.6 12.4l3.6 3.6M13 6.2l4.8 4.8" {!! $stroke !!}/>
    @break
@case('flash')
    <path d="M13.2 2.8L5.6 13.6h5.2l-1 7.6 7.6-10.8h-5.2z" {!! $stroke !!}/>
    @break
@case('rocket')
    <path d="M12 3c3.2 2.1 4.8 5.2 4.8 9l-2 3.4h-5.6l-2-3.4C7.2 8.2 8.8 5.1 12 3z" {!! $stroke !!}/>
    <circle cx="12" cy="9.6" r="1.8" {!! $stroke !!}/>
    <path d="M9.2 15.4L7 20l3.4-1.6M14.8 15.4L17 20l-3.4-1.6" {!! $stroke !!}/>
    @break

@default
    <circle cx="12" cy="12" r="8.5" {!! $stroke !!}/>
@endswitch
</svg>
