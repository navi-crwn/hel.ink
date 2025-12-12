<!DOCTYPE html>
<html lang="en" x-data="previewApp()" x-init="init()">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bio Page Preview</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|poppins:400,500,600,700|roboto:400,500,700|montserrat:400,500,600,700|nunito:400,500,600,700|open-sans:400,500,600,700|lato:400,700|raleway:400,500,600,700|oswald:400,500,600,700|playfair-display:400,500,600,700|merriweather:400,700|source-sans-pro:400,600,700|ubuntu:400,500,700|quicksand:400,500,600,700|work-sans:400,500,600,700|dm-sans:400,500,700|space-grotesk:400,500,600,700|manrope:400,500,600,700|plus-jakarta-sans:400,500,600,700|lexend:400,500,600,700" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        :root {
            --bg-color: #f8fafc;
            --text-color: #1e293b;
            --bio-color: #64748b;
            --button-bg: #6366f1;
            --button-text: #ffffff;
            --button-radius: 12px;
            --button-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        /* Custom Scrollbar - Hidden */
        ::-webkit-scrollbar {
            width: 0;
            height: 0;
            display: none;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: transparent;
        }
        * {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg-color);
            color: var(--text-color);
            min-height: 100vh;
            padding: 12px;
        }
        
        .bio-container {
            max-width: 100%;
            margin: 0 auto;
            padding: 8px;
        }
        
        /* Profile Section */
        .profile-section {
            text-align: center;
            margin-bottom: 16px;
        }
        
        .avatar-wrapper {
            width: 72px;
            height: 72px;
            margin: 0 auto 12px;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .avatar-wrapper.shape-circle {
            border-radius: 50%;
        }
        
        .avatar-wrapper.shape-rounded {
            border-radius: 16px;
        }
        
        .avatar-wrapper.shape-square {
            border-radius: 0;
        }
        
        .avatar-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .profile-badge {
            position: absolute;
            bottom: -2px;
            right: -2px;
            width: 28px;
            height: 28px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        }
        
        .profile-badge svg {
            width: 20px;
            height: 20px;
        }
        
        .avatar-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom right, #3b82f6, #4f46e5);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            font-weight: 700;
        }
        
        .display-name {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 6px;
            color: var(--title-color, var(--text-color));
            display: inline-flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
        }
        
        .title-badge {
            display: inline-flex;
            flex-shrink: 0;
        }
        
        .title-badge svg {
            width: 14px;
            height: 14px;
        }
        
        .bio-text {
            font-size: 12px;
            line-height: 1.4;
            color: var(--bio-color, inherit);
            opacity: 0.9;
        }
        
        /* Social Icons */
        .social-icons {
            display: flex;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }
        
        .social-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .social-icon:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        
        .social-icon img {
            width: 16px;
            height: 16px;
            filter: brightness(0) invert(1);
        }
        
        /* Link Blocks */
        .blocks-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        /* Ensure all block types are properly spaced */
        .blocks-container > * {
            margin: 0;
        }
        
        .link-block {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px 14px;
            background: var(--button-bg);
            color: var(--button-text);
            border-radius: var(--button-radius);
            box-shadow: var(--button-shadow);
            text-decoration: none;
            font-weight: 500;
            font-size: 13px;
            transition: transform 0.2s, box-shadow 0.2s;
            position: relative;
            overflow: hidden;
        }
        
        .link-block:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }
        
        .link-block.has-thumbnail {
            justify-content: flex-start;
        }
        
        .block-thumbnail {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            object-fit: cover;
            margin-right: 10px;
            flex-shrink: 0;
        }
        
        .block-icon {
            width: 20px;
            height: 20px;
            margin-right: 10px;
            flex-shrink: 0;
        }
        
        .block-title {
            flex: 1;
            text-align: center;
        }
        
        .has-thumbnail .block-title,
        .has-icon .block-title {
            text-align: left;
        }
        
        /* Text Block */
        .text-block {
            background: transparent;
            box-shadow: none;
            padding: 16px;
            text-align: center;
        }
        
        .text-block:hover {
            transform: none;
            box-shadow: none;
        }
        
        /* Text Block Styled (with frame) */
        .text-block-styled {
            width: 100%;
            border-radius: 12px;
            border: 1px solid rgba(148, 163, 184, 0.3);
            overflow: hidden;
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(4px);
        }
        
        .text-block-header {
            padding: 6px 10px;
            border-bottom: 1px solid rgba(148, 163, 184, 0.3);
            background: rgba(241, 245, 249, 0.5);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .text-block-expand {
            font-size: 14px;
            font-weight: bold;
            color: #64748b;
            cursor: pointer;
        }
        
        .text-block-dots {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .text-block-dots .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }
        
        .text-block-dots .dot.red { background: #f87171; }
        .text-block-dots .dot.yellow { background: #fbbf24; }
        .text-block-dots .dot.green { background: #4ade80; }
        
        .text-block-content {
            padding: 10px 14px;
            font-size: 12px;
            line-height: 1.5;
            color: inherit;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        .text-block-content.truncated {
            max-height: 80px;
            overflow: hidden;
            position: relative;
        }
        
        .text-block-content.truncated::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 30px;
            background: linear-gradient(transparent, var(--text-block-bg, rgba(255,255,255,0.9)));
        }
        
        .text-block-styled {
            cursor: pointer;
        }
        
        /* Divider Block */
        .divider-block {
            height: 1px;
            background: currentColor;
            opacity: 0.2;
            margin: 8px 0;
        }
        
        /* Video Block */
        .video-block {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            padding: 8px;
            margin-bottom: 8px;
        }
        
        .video-embed {
            aspect-ratio: 16/9;
            border-radius: 8px;
            overflow: hidden;
            max-height: 180px;
        }
        
        .video-embed iframe {
            width: 100%;
            height: 100%;
            border: 0;
        }
        
        /* Music Block */
        .music-block {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            padding: 8px;
            margin-bottom: 8px;
        }
        
        .music-embed {
            overflow: hidden;
        }
        
        .music-embed iframe {
            width: 100%;
            height: 152px;
            border-radius: 8px;
            border: 0;
        }
        
        /* VCard Block */
        .vcard-block {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            margin-bottom: 8px;
        }
        
        .vcard-avatar {
            width: 80px;
            height: 80px;
            margin: 0 auto 12px;
            background: var(--button-bg, #6366f1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .vcard-icon {
            width: 40px;
            height: 40px;
            color: white;
        }
        
        .vcard-name {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 4px;
        }
        
        .vcard-company {
            font-size: 14px;
            opacity: 0.7;
            margin-bottom: 12px;
        }
        
        .vcard-details {
            margin-bottom: 16px;
        }
        
        .vcard-item {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: 13px;
            opacity: 0.8;
            margin-bottom: 4px;
        }
        
        .vcard-item-icon {
            width: 14px;
            height: 14px;
        }
        
        .vcard-save-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 20px;
            background: var(--button-bg, #6366f1);
            color: var(--button-text, #fff);
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .vcard-save-btn:hover {
            opacity: 0.9;
            transform: scale(1.02);
        }
        
        .vcard-save-btn svg {
            width: 16px;
            height: 16px;
        }
        
        /* HTML Block */
        .html-block {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 8px;
        }
        
        .html-content {
            font-size: 14px;
            line-height: 1.6;
        }
        
        /* Countdown Block */
        .countdown-block {
            /* Background is set via inline style from getCountdownBg() */
            border-radius: 16px;
            padding: 16px;
            text-align: center;
            color: white;
            margin-bottom: 8px;
            overflow: hidden;
            width: 100%;
            box-sizing: border-box;
        }
        
        .countdown-label {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 12px;
            opacity: 0.9;
        }
        
        .countdown-timer {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 4px;
            flex-wrap: wrap;
        }
        
        .countdown-unit {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 36px;
        }
        
        .countdown-value {
            font-size: 20px;
            font-weight: 700;
            line-height: 1;
        }
        
        .countdown-name {
            font-size: 9px;
            text-transform: uppercase;
            opacity: 0.8;
            margin-top: 2px;
        }
        
        .countdown-separator {
            font-size: 18px;
            font-weight: 700;
            opacity: 0.6;
        }
        
        /* Map Block */
        .map-block {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            padding: 12px;
            margin-bottom: 8px;
        }
        
        .map-embed {
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 8px;
        }
        
        .map-address {
            font-size: 13px;
            opacity: 0.8;
            text-align: center;
        }
        
        /* FAQ Block */
        .faq-block {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 8px;
        }
        
        .faq-items {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .faq-item {
            background: rgba(255, 255, 255, 0.5);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .faq-question {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 16px;
            background: none;
            border: none;
            font-size: 14px;
            font-weight: 500;
            text-align: left;
            cursor: pointer;
            color: inherit;
        }
        
        .faq-question.active {
            background: rgba(99, 102, 241, 0.1);
        }
        
        .faq-arrow {
            width: 16px;
            height: 16px;
            transition: transform 0.2s;
            flex-shrink: 0;
        }
        
        .faq-answer {
            padding: 0 16px 12px;
            font-size: 13px;
            line-height: 1.5;
            opacity: 0.8;
        }
        
        /* Block Title Header */
        .block-title-header {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 12px;
            opacity: 0.9;
        }
        
        /* Image Block */
        .image-block {
            width: 100%;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .image-block-img {
            width: 100%;
            height: auto;
            display: block;
        }
        
        /* Header Block */
        .header-block {
            background: transparent;
            box-shadow: none;
            padding: 12px 0;
            font-size: 18px;
            font-weight: 600;
        }
        
        .header-block:hover {
            transform: none;
            box-shadow: none;
        }
        
        /* Brand Button Styles */
        @foreach(config('brands', []) as $brandId => $brand)
        .block-{{ $brandId }} {
            background: {{ $brand['bgColor'] ?? '#000000' }};
            color: {{ $brand['textColor'] ?? '#ffffff' }};
        }
        @endforeach
        
        /* Button Shapes */
        .shape-rounded {
            --button-radius: 12px;
        }
        
        .shape-pill {
            --button-radius: 9999px;
        }
        
        .shape-square {
            --button-radius: 0;
        }
        
        .shape-soft {
            --button-radius: 6px;
        }
        
        /* Button Shadows - Applied to all block types */
        .shadow-none .link-block,
        .shadow-none .text-block-styled,
        .shadow-none .image-block,
        .shadow-none .countdown-block {
            box-shadow: none !important;
        }
        
        .shadow-sm .link-block,
        .shadow-sm .text-block-styled,
        .shadow-sm .image-block,
        .shadow-sm .countdown-block {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08) !important;
        }
        
        .shadow-md .link-block,
        .shadow-md .text-block-styled,
        .shadow-md .image-block,
        .shadow-md .countdown-block {
            box-shadow: 0 4px 8px -1px rgba(0, 0, 0, 0.12) !important;
        }
        
        .shadow-lg .link-block,
        .shadow-lg .text-block-styled,
        .shadow-lg .image-block,
        .shadow-lg .countdown-block {
            box-shadow: 0 10px 20px -3px rgba(0, 0, 0, 0.15) !important;
        }
        
        .shadow-xl .link-block,
        .shadow-xl .text-block-styled,
        .shadow-xl .image-block,
        .shadow-xl .countdown-block {
            box-shadow: 0 25px 35px -5px rgba(0, 0, 0, 0.2) !important;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            margin-top: 32px;
            padding-top: 16px;
            opacity: 0.5;
            font-size: 12px;
        }
        
        .footer a {
            color: inherit;
            text-decoration: none;
        }
        
        /* Font Families */
        .font-inter { font-family: 'Inter', system-ui, sans-serif; }
        .font-poppins { font-family: 'Poppins', system-ui, sans-serif; }
        .font-roboto { font-family: 'Roboto', system-ui, sans-serif; }
        .font-montserrat { font-family: 'Montserrat', system-ui, sans-serif; }
        .font-nunito { font-family: 'Nunito', system-ui, sans-serif; }
        .font-open-sans { font-family: 'Open Sans', system-ui, sans-serif; }
        .font-lato { font-family: 'Lato', system-ui, sans-serif; }
        .font-raleway { font-family: 'Raleway', system-ui, sans-serif; }
        .font-oswald { font-family: 'Oswald', system-ui, sans-serif; }
        .font-playfair-display { font-family: 'Playfair Display', Georgia, serif; }
        .font-merriweather { font-family: 'Merriweather', Georgia, serif; }
        .font-source-sans-pro { font-family: 'Source Sans Pro', system-ui, sans-serif; }
        .font-ubuntu { font-family: 'Ubuntu', system-ui, sans-serif; }
        .font-quicksand { font-family: 'Quicksand', system-ui, sans-serif; }
        .font-work-sans { font-family: 'Work Sans', system-ui, sans-serif; }
        .font-dm-sans { font-family: 'DM Sans', system-ui, sans-serif; }
        .font-space-grotesk { font-family: 'Space Grotesk', system-ui, sans-serif; }
        .font-manrope { font-family: 'Manrope', system-ui, sans-serif; }
        .font-plus-jakarta-sans { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
        .font-lexend { font-family: 'Lexend', system-ui, sans-serif; }
        
        /* Background Types */
        .bg-gradient-purple {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .bg-gradient-blue {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .bg-gradient-pink {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }
        
        .bg-gradient-green {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
        
        .bg-gradient-dark {
            background: linear-gradient(135deg, #232526 0%, #414345 100%);
        }
        
        /* Share Button */
        .share-button {
            position: fixed;
            top: 16px;
            right: 16px;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            z-index: 50;
        }
        
        .share-button:hover {
            background: white;
            transform: scale(1.05);
        }
        
        .share-button svg {
            width: 20px;
            height: 20px;
            color: #475569;
        }
        
        /* Share Tooltip */
        .share-tooltip {
            position: fixed;
            top: 60px;
            right: 16px;
            z-index: 60;
        }
        
        .share-tooltip-content {
            background: white;
            border-radius: 12px;
            padding: 16px 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            text-align: center;
            min-width: 180px;
        }
        
        /* ============================================
           ANIMATION STYLES
           ============================================ */
        
        /* Hover Effects - Theme-aware using CSS custom properties */
        .hover-scale .link-block:hover {
            transform: scale(1.03) !important;
        }
        
        .hover-glow .link-block:hover {
            box-shadow: 0 0 20px 5px var(--hover-glow-color, rgba(99, 102, 241, 0.4)) !important;
        }
        
        .hover-lift .link-block:hover {
            transform: translateY(-4px) !important;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2) !important;
        }
        
        .hover-glossy .link-block {
            position: relative;
            overflow: hidden;
        }
        .hover-glossy .link-block::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s ease;
        }
        .hover-glossy .link-block:hover::before {
            left: 100%;
        }
        
        .hover-color-shift .link-block {
            transition: filter 0.3s ease, transform 0.2s ease !important;
        }
        .hover-color-shift .link-block:hover {
            filter: hue-rotate(var(--hover-hue-shift, 30deg)) saturate(1.2) !important;
        }
        
        :root {
            --hover-glow-color: rgba(99, 102, 241, 0.4);
            --hover-hue-shift: 30deg;
        }
        
        /* Entrance Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes popIn {
            0% { opacity: 0; transform: scale(0.8); }
            70% { transform: scale(1.05); }
            100% { opacity: 1; transform: scale(1); }
        }
        
        @keyframes bounceIn {
            0% { opacity: 0; transform: translateY(-30px); }
            50% { transform: translateY(10px); }
            70% { transform: translateY(-5px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes flipIn {
            from { opacity: 0; transform: perspective(400px) rotateX(-90deg); }
            to { opacity: 1; transform: perspective(400px) rotateX(0deg); }
        }
        
        .entrance-fade { animation: fadeIn 0.5s ease-out forwards; }
        .entrance-slide-up { animation: slideUp 0.5s ease-out forwards; }
        .entrance-slide-down { animation: slideDown 0.5s ease-out forwards; }
        .entrance-pop { animation: popIn 0.4s ease-out forwards; }
        .entrance-bounce { animation: bounceIn 0.6s ease-out forwards; }
        .entrance-flip { animation: flipIn 0.5s ease-out forwards; }
        .entrance-stagger { animation: slideUp 0.5s ease-out forwards; }
        
        /* Attention Animations */
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-3px); }
            40%, 80% { transform: translateX(3px); }
        }
        
        @keyframes attentionGlow {
            0%, 100% { box-shadow: 0 0 5px rgba(99, 102, 241, 0.3); }
            50% { box-shadow: 0 0 20px rgba(99, 102, 241, 0.6); }
        }
        
        @keyframes wiggle {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-2deg); }
            75% { transform: rotate(2deg); }
        }
        
        @keyframes heartbeat {
            0%, 100% { transform: scale(1); }
            14% { transform: scale(1.03); }
            28% { transform: scale(1); }
            42% { transform: scale(1.03); }
            70% { transform: scale(1); }
        }
        
        @keyframes rainbow {
            0% { filter: hue-rotate(0deg); }
            100% { filter: hue-rotate(360deg); }
        }
        
        @keyframes attentionBounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-3px); }
        }
        
        .attention-pulse { animation: pulse 2s ease-in-out infinite; }
        .attention-shake { animation: shake 0.5s ease-in-out infinite; animation-delay: 2s; }
        .attention-glow { animation: attentionGlow 2s ease-in-out infinite; }
        .attention-wiggle { animation: wiggle 1s ease-in-out infinite; }
        .attention-heartbeat { animation: heartbeat 1.5s ease-in-out infinite; }
        .attention-rainbow { animation: rainbow 3s linear infinite; }
        .attention-bounce { animation: attentionBounce 1s ease-in-out infinite; }
        
        /* Background Animations */
        .bg-animation-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }
        
        .bg-particle {
            position: absolute;
            pointer-events: none;
        }
        
        @keyframes fall {
            0% { transform: translateY(-10vh) rotate(0deg); opacity: 1; }
            100% { transform: translateY(110vh) rotate(360deg); opacity: 0.3; }
        }
        
        @keyframes twinkle {
            0%, 100% { opacity: 0.3; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.2); }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
    </style>
</head>
<body :class="[
    bodyClasses,
    'font-' + (design.fontFamily || 'inter'),
    design.hoverEffect && design.hoverEffect !== 'none' ? 'hover-' + design.hoverEffect : ''
]" :style="{
    '--bg-color': design.backgroundColor || '#f8fafc',
    '--text-color': getEffectiveTitleColor(),
    '--title-color': getEffectiveTitleColor(),
    '--bio-color': getEffectiveBioColor(),
    '--button-bg': getEffectiveButtonBg(),
    '--button-text': getEffectiveButtonText(),
    '--button-radius': buttonRadius,
    color: getEffectiveTitleColor(),
    background: getEffectiveBackground()
}">
    <!-- Background Animation Container -->
    <div class="bg-animation-container" 
         x-ref="bgAnimContainer"
         x-init="$watch('design.backgroundAnimation', (val) => initBackgroundAnimation(val)); $watch('design.theme', () => initBackgroundAnimation(design.backgroundAnimation))">
    </div>

    <!-- Loading State -->
    <div x-show="!isLoaded" class="fixed inset-0 bg-white flex items-center justify-center z-50">
        <div class="text-center">
            <div class="w-8 h-8 border-2 border-slate-200 border-t-blue-500 rounded-full animate-spin mx-auto"></div>
        </div>
    </div>
    
    <!-- Share Button -->
    <button x-show="isLoaded" class="share-button" @click="showShareTooltip = true" title="Share">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
        </svg>
    </button>
    
    <!-- Share Tooltip -->
    <div x-show="showShareTooltip" @click.away="showShareTooltip = false" class="share-tooltip">
        <div class="share-tooltip-content">
            <svg class="w-5 h-5 text-blue-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm font-medium text-slate-700">Preview Only</p>
            <p class="text-xs editor-text-muted mt-1">Share will work on the live page</p>
        </div>
    </div>
    
    <div x-show="isLoaded" class="bio-container">
        <!-- Profile Section -->
        <div class="profile-section">
            <div class="avatar-wrapper" :class="'shape-' + (design.avatarShape || 'circle')">
                <template x-if="profile.avatar">
                    <img :src="profile.avatar" :alt="profile.displayName">
                </template>
                <template x-if="!profile.avatar">
                    <div class="avatar-placeholder" x-text="getInitials(profile.displayName)"></div>
                </template>
                
            </div>
            <div class="display-name-row" style="text-align: center;">
                <h1 class="display-name" style="margin-bottom: 0; justify-content: center;">
                    <span x-text="profile.displayName || 'Your Name'"></span>
                    <!-- Badge next to title -->
                    <template x-if="profile.badge">
                        <span class="title-badge" :style="{ color: profile.badgeColor || '#3b82f6' }">
                            <template x-if="profile.badge === 'verified'">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd"/>
                                </svg>
                            </template>
                            <template x-if="profile.badge === 'star'">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd"/>
                                </svg>
                            </template>
                            <template x-if="profile.badge === 'crown'">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M2 17l2-11 4 5 4-7 4 7 4-5 2 11H2z"/>
                                    <rect x="3" y="18" width="18" height="3" rx="1"/>
                                </svg>
                            </template>
                            <template x-if="profile.badge === 'fire'">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path fill-rule="evenodd" d="M12.963 2.286a.75.75 0 00-1.071-.136 9.742 9.742 0 00-3.539 6.177A7.547 7.547 0 016.648 6.61a.75.75 0 00-1.152.082A9 9 0 1015.68 4.534a7.46 7.46 0 01-2.717-2.248zM15.75 14.25a3.75 3.75 0 11-7.313-1.172c.628.465 1.35.81 2.133 1a5.99 5.99 0 011.925-3.545 3.75 3.75 0 013.255 3.717z" clip-rule="evenodd"/>
                                </svg>
                            </template>
                        </span>
                    </template>
                </h1>
            </div>
            <p class="bio-text" x-text="decodeHtmlEntities(profile.bio) || 'Your bio goes here'"></p>
        </div>
        
        <!-- Social Icons (Top) -->
        <template x-if="socials.position === 'top' && socials.items && socials.items.length > 0">
            <div class="social-icons">
                <template x-for="social in socials.items" :key="social.id">
                    <a :href="social.url || '#'" class="social-icon" :style="{
                        background: getBrandColor(social.platform)
                    }" target="_blank">
                        <img :src="'/images/brands/' + social.platform + '.svg?v=20251212'" :alt="social.platform">
                    </a>
                </template>
            </div>
        </template>
        
        <!-- Link Blocks -->
        <div class="blocks-container" :class="'shape-' + (design.buttonShape || 'rounded') + ' shadow-' + (design.buttonShadow || 'md')" x-ref="blocksContainer">
            <template x-for="(block, blockIndex) in blocks.filter(b => b.is_active)" :key="block.id">
                <div>
                    <!-- Link Block -->
                    <template x-if="block.type === 'link'">
                        <a :href="block.url || '#'" class="link-block" :class="[
                            block.thumbnail ? 'has-thumbnail' : '',
                            (block.brand || block.custom_icon) ? 'has-icon' : '',
                            block.brand ? 'block-' + block.brand : '',
                            block.entrance_animation && block.entrance_animation !== 'none' ? 'entrance-' + block.entrance_animation : '',
                            block.attention_animation && block.attention_animation !== 'none' ? 'attention-' + block.attention_animation : ''
                        ]" :style="{
                            background: block.btn_bg_color || getEffectiveButtonBg(),
                            color: block.btn_text_color || getEffectiveButtonText(),
                            border: block.btn_border_color ? '1px solid ' + block.btn_border_color : getEffectiveBorder(),
                            animationDelay: block.entrance_animation === 'stagger' ? (blockIndex * 0.1) + 's' : '0s'
                        }" target="_blank">
                            <template x-if="block.thumbnail">
                                <img :src="block.thumbnail" class="block-thumbnail" :alt="block.title" :key="'thumb-'+block.id">
                            </template>
                            <template x-if="(block.brand || block.custom_icon) && !block.thumbnail">
                                <img :src="block.custom_icon || '/images/brands/' + block.brand + '.svg?v=20251212'" 
                                     class="block-icon" :alt="block.brand || 'icon'"
                                     :key="'icon-'+block.id+'-'+(block.brand||'custom')"
                                     :style="{ 
                                         filter: block.btn_icon_invert 
                                             ? 'brightness(0) invert(1)' 
                                             : ( (!block.custom_icon && isLightColor(block.btn_bg_color || getEffectiveButtonBg())) 
                                                 ? 'brightness(0)' 
                                                 : 'brightness(0) invert(1)' 
                                             ) 
                                     }">
                            </template>
                            <span class="block-title" x-text="block.title || 'Untitled Link'"></span>
                        </a>
                    </template>
                    
                    <!-- Text Block -->
                    <template x-if="block.type === 'text'">
                        <div class="text-block-styled" x-data="{ expanded: false }" @click="expanded = !expanded" :style="{
                            background: getContentBlockBg(),
                            borderColor: getContentBlockBorder(),
                            color: isDarkTheme() ? '#f1f5f9' : '#1e293b',
                            '--text-block-bg': getContentBlockBg()
                        }">
                            <div class="text-block-header" :style="{
                                background: getContentBlockHeaderBg(),
                                borderColor: getContentBlockBorder()
                            }">
                                <div class="text-block-dots">
                                    <span class="dot red"></span>
                                    <span class="dot yellow"></span>
                                    <span class="dot green"></span>
                                </div>
                                <span class="text-block-expand" :style="{ color: getEffectiveBioColor() }" x-text="expanded ? '−' : '+'"></span>
                            </div>
                            <div class="text-block-content" :class="{ 'truncated': !expanded }" x-html="(block.content || 'Text content').replace(/\n/g, '<br>')"></div>
                        </div>
                    </template>
                    
                    <!-- Header Block -->
                    <template x-if="block.type === 'header'">
                        <div class="header-block" x-text="block.title || 'Header'"></div>
                    </template>
                    
                    <!-- Image Block -->
                    <template x-if="block.type === 'image'">
                        <div class="image-block">
                            <img :src="block.content || block.thumbnail_url" :alt="block.title || 'Image'" class="image-block-img">
                        </div>
                    </template>
                    
                    <!-- Divider Block -->
                    <template x-if="block.type === 'divider'">
                        <div class="divider-block"></div>
                    </template>
                    
                    <!-- Video Block -->
                    <template x-if="block.type === 'video'">
                        <div class="video-block" :key="'video-' + block.id + '-' + (block._embedKey || block.embed_url || '')">
                            <div class="block-title-header" x-text="block.title || 'Video'" x-show="block.title"></div>
                            <div class="video-embed" x-html="getVideoEmbed(block.embed_url)"></div>
                        </div>
                    </template>
                    
                    <!-- Music Block -->
                    <template x-if="block.type === 'music'">
                        <div class="music-block" :key="'music-' + block.id + '-' + (block._embedKey || block.embed_url || '')">
                            <div class="block-title-header" x-text="block.title || 'Music'" x-show="block.title"></div>
                            <div class="music-embed" x-html="getMusicEmbed(block.embed_url)"></div>
                        </div>
                    </template>
                    
                    <!-- YouTube Block -->
                    <template x-if="block.type === 'youtube'">
                        <div class="video-block" :key="'yt-' + block.id + '-' + (block._embedKey || block.embed_url || '')">
                            <div class="block-title-header" x-text="block.title || 'Video'" x-show="block.title"></div>
                            <div class="video-embed" x-html="getVideoEmbed(block.embed_url)"></div>
                        </div>
                    </template>
                    
                    <!-- Spotify Block -->
                    <template x-if="block.type === 'spotify'">
                        <div class="music-block" :key="'spotify-' + block.id + '-' + (block._embedKey || block.embed_url || '')">
                            <div class="block-title-header" x-text="block.title || 'Music'" x-show="block.title"></div>
                            <div class="music-embed" x-html="getMusicEmbed(block.embed_url)"></div>
                        </div>
                    </template>
                    
                    <!-- SoundCloud Block -->
                    <template x-if="block.type === 'soundcloud'">
                        <div class="music-block" :key="'sc-' + block.id + '-' + (block._embedKey || block.embed_url || '')">
                            <div class="block-title-header" x-text="block.title || 'Music'" x-show="block.title"></div>
                            <div class="music-embed" x-html="getMusicEmbed(block.embed_url)"></div>
                        </div>
                    </template>
                    
                    <!-- VCard Block -->
                    <template x-if="block.type === 'vcard'">
                        <div class="vcard-block">
                            <div class="vcard-avatar">
                                <svg class="vcard-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div class="vcard-info">
                                <div class="vcard-name" x-text="block.vcard_name || 'Contact Name'"></div>
                                <div class="vcard-details">
                                    <div x-show="block.vcard_company" x-text="block.vcard_company" class="vcard-company"></div>
                                    <div x-show="block.vcard_phone" class="vcard-item">
                                        <svg class="vcard-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        <span x-text="block.vcard_phone"></span>
                                    </div>
                                    <div x-show="block.vcard_email" class="vcard-item">
                                        <svg class="vcard-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        <span x-text="block.vcard_email"></span>
                                    </div>
                                </div>
                            </div>
                            <button class="vcard-save-btn" @click="downloadVCard(block)">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Save Contact
                            </button>
                        </div>
                    </template>
                    
                    <!-- HTML Block -->
                    <template x-if="block.type === 'html'">
                        <div class="html-block">
                            <div class="block-title-header" x-text="block.title" x-show="block.title"></div>
                            <div class="html-content" x-html="sanitizeHtml(block.html_content)"></div>
                        </div>
                    </template>
                    
                    <!-- Countdown Block -->
                    <template x-if="block.type === 'countdown'">
                        <div class="countdown-block" :style="{
                            background: getCountdownBg(),
                            color: getEffectiveButtonText()
                        }">
                            <div class="countdown-label" x-text="block.countdown_label || 'Countdown'"></div>
                            <div class="countdown-timer" x-data="{ remaining: calculateCountdown(block.countdown_date) }" x-init="setInterval(() => remaining = calculateCountdown(block.countdown_date), 1000)">
                                <div class="countdown-unit">
                                    <span class="countdown-value" x-text="remaining.days"></span>
                                    <span class="countdown-name">Days</span>
                                </div>
                                <div class="countdown-separator">:</div>
                                <div class="countdown-unit">
                                    <span class="countdown-value" x-text="remaining.hours"></span>
                                    <span class="countdown-name">Hours</span>
                                </div>
                                <div class="countdown-separator">:</div>
                                <div class="countdown-unit">
                                    <span class="countdown-value" x-text="remaining.minutes"></span>
                                    <span class="countdown-name">Min</span>
                                </div>
                                <div class="countdown-separator">:</div>
                                <div class="countdown-unit">
                                    <span class="countdown-value" x-text="remaining.seconds"></span>
                                    <span class="countdown-name">Sec</span>
                                </div>
                            </div>
                        </div>
                    </template>
                    
                    <!-- Map Block -->
                    <template x-if="block.type === 'map'">
                        <div class="map-block" :key="'map-' + block.id + '-' + (block._embedKey || block.map_address || '')">
                            <div class="block-title-header" x-text="block.title || 'Location'" x-show="block.title"></div>
                            <div class="map-embed">
                                <iframe 
                                    :src="'https://maps.google.com/maps?q=' + encodeURIComponent(block.map_address || '') + '&z=' + (block.map_zoom || 15) + '&output=embed'"
                                    width="100%" 
                                    height="200" 
                                    style="border:0; border-radius: 12px;" 
                                    allowfullscreen="" 
                                    loading="lazy">
                                </iframe>
                            </div>
                            <div class="map-address" x-text="block.map_address"></div>
                        </div>
                    </template>
                    
                    <!-- FAQ Block -->
                    <template x-if="block.type === 'faq'">
                        <div class="faq-block">
                            <div class="block-title-header" x-text="block.title || 'FAQ'" x-show="block.title"></div>
                            <div class="faq-items">
                                <template x-for="(item, idx) in (block.faq_items || [])" :key="idx">
                                    <div class="faq-item" x-data="{ open: false }">
                                        <button class="faq-question" @click="open = !open" :class="{ 'active': open }">
                                            <span x-text="item.question || 'Question'"></span>
                                            <svg :class="{ 'rotate-180': open }" class="faq-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </button>
                                        <div class="faq-answer" x-show="open" x-collapse x-text="item.answer || 'Answer'"></div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
        </div>
        
        <!-- Social Icons (Bottom) -->
        <template x-if="socials.position === 'bottom' && socials.items && socials.items.length > 0">
            <div class="social-icons" style="margin-top: 24px;">
                <template x-for="social in socials.items" :key="social.id">
                    <a :href="social.url || '#'" class="social-icon" :style="{
                        background: getBrandColor(social.platform)
                    }" target="_blank">
                        <img :src="'/images/brands/' + social.platform + '.svg?v=20251212'" :alt="social.platform">
                    </a>
                </template>
            </div>
        </template>
        
        <!-- Footer -->
        <template x-if="!settings.hideBranding">
            <div class="footer">
                <a href="/">Powered by Hel.ink</a>
            </div>
        </template>
    </div>
    
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        // Brand colors from config
        const brandColors = @json(collect(config('brands.platforms', []))->mapWithKeys(fn($b, $k) => [$k => $b['color'] ?? '#000000']));
        
        function previewApp() {
            return {
                isLoaded: false,
                showShareTooltip: false,
                profile: {
                    displayName: '',
                    bio: '',
                    avatar: null,
                    badge: null,
                    badgeColor: '#3b82f6'
                },
                blocks: [],
                socials: {
                    position: 'top',
                    items: []
                },
                design: {
                    backgroundColor: '#f8fafc',
                    textColor: '#1e293b',
                    titleColor: '#1e293b',
                    bioColor: '#1e293b',
                    buttonBgColor: '#6366f1',
                    buttonTextColor: '#ffffff',
                    buttonShape: 'rounded',
                    buttonShadow: 'medium',
                    avatarShape: 'circle',
                    fontFamily: 'inter',
                    backgroundType: 'solid',
                    backgroundGradient: null,
                    theme: 'none'
                },
                themes: {
                    'light': { background: '#ffffff', text: '#1e293b', bio: '#64748b', link_bg: '#f1f5f9', link_text: '#1e293b' },
                    'dark': { background: 'linear-gradient(135deg, #1e293b 0%, #0f172a 100%)', text: '#f1f5f9', bio: 'rgba(241,245,249,0.8)', link_bg: 'rgba(51, 65, 85, 0.9)', link_text: '#f1f5f9' },
                    'modern': { background: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)', text: '#ffffff', bio: 'rgba(255,255,255,0.8)', link_bg: 'rgba(255, 255, 255, 0.2)', link_text: '#ffffff' },
                    'classic': { background: 'linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%)', text: '#1e293b', bio: '#64748b', link_bg: '#ffffff', link_text: '#1e293b' },
                    'minimal-light': { background: '#fafafa', text: '#171717', bio: '#525252', link_bg: '#ffffff', link_text: '#171717' },
                    'minimal-dark': { background: '#171717', text: '#fafafa', bio: '#a3a3a3', link_bg: '#262626', link_text: '#fafafa' },
                    'ocean': { background: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)', text: '#ffffff', bio: 'rgba(255,255,255,0.8)', link_bg: 'rgba(255, 255, 255, 0.2)', link_text: '#ffffff' },
                    'sunset': { background: 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)', text: '#ffffff', bio: 'rgba(255,255,255,0.9)', link_bg: 'rgba(255, 255, 255, 0.25)', link_text: '#ffffff' },
                    'forest': { background: 'linear-gradient(135deg, #134e5e 0%, #71b280 100%)', text: '#ffffff', bio: 'rgba(255,255,255,0.85)', link_bg: 'rgba(255, 255, 255, 0.2)', link_text: '#ffffff' },
                    'galaxy': { background: 'linear-gradient(135deg, #0c0a3e 0%, #2d1b69 50%, #7b2cbf 100%)', text: '#ffffff', bio: '#e0aaff', link_bg: 'rgba(255,255,255,0.15)', link_text: '#ffffff' },
                    'neon': { background: '#0a0a0a', text: '#39ff14', bio: '#00ff88', link_bg: 'rgba(57, 255, 20, 0.15)', link_text: '#39ff14', link_border: '#39ff14' },
                    'pastel': { background: 'linear-gradient(180deg, #ffecd2 0%, #fcb69f 100%)', text: '#5c4742', bio: '#8b7355', link_bg: '#ffffff', link_text: '#5c4742' },
                    'cyberpunk': { background: 'linear-gradient(135deg, #1a1a2e 0%, #16213e 100%)', text: '#00fff5', bio: '#ff006e', link_bg: 'rgba(0,255,245,0.1)', link_text: '#00fff5' },
                    'aurora': { background: 'linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%)', text: '#b8b8d1', bio: '#7f7f9a', link_bg: 'rgba(88,86,214,0.3)', link_text: '#ffffff' },
                    'cherry': { background: 'linear-gradient(180deg, #ffc0cb 0%, #ffb6c1 50%, #ff69b4 100%)', text: '#4a1942', bio: '#722f37', link_bg: '#ffffff', link_text: '#4a1942' },
                    'midnight': { background: 'linear-gradient(180deg, #0f0f23 0%, #1a1a3e 100%)', text: '#e8e8ff', bio: '#8888aa', link_bg: 'rgba(99,102,241,0.2)', link_text: '#a5b4fc' },
                    'retro': { background: '#f4e4ba', text: '#2d1b00', bio: '#5c4033', link_bg: '#e07a5f', link_text: '#ffffff' },
                    'ice': { background: 'linear-gradient(180deg, #e0f7fa 0%, #b2ebf2 50%, #80deea 100%)', text: '#006064', bio: '#00838f', link_bg: '#ffffff', link_text: '#00838f' },
                    'lavender': { background: 'linear-gradient(135deg, #e6e6fa 0%, #d8bfd8 50%, #dda0dd 100%)', text: '#4b0082', bio: '#663399', link_bg: '#ffffff', link_text: '#4b0082' },
                    'matrix': { background: '#000000', text: '#00ff00', bio: '#00cc00', link_bg: 'rgba(0,255,0,0.1)', link_text: '#00ff00' }
                },
                settings: {
                    hideBranding: false
                },
                
                init() {
                    // Listen for messages from parent window
                    window.addEventListener('message', (event) => {
                        if (event.data.type === 'preview-update') {
                            this.updatePreview(event.data.data);
                            this.isLoaded = true;
                            // Update theme-aware hover colors
                            this.updateHoverColors();
                        }
                    });
                    
                    // Signal ready
                    window.parent.postMessage({ type: 'preview-ready' }, '*');
                },
                
                // Update CSS custom properties for theme-aware hover effects
                updateHoverColors() {
                    const themeGlowColors = {
                        'default': 'rgba(99, 102, 241, 0.4)',
                        'light': 'rgba(99, 102, 241, 0.4)',
                        'dark': 'rgba(96, 165, 250, 0.5)',
                        'modern': 'rgba(139, 92, 246, 0.5)',
                        'classic': 'rgba(71, 85, 105, 0.4)',
                        'minimal-light': 'rgba(23, 23, 23, 0.3)',
                        'minimal-dark': 'rgba(250, 250, 250, 0.3)',
                        'ocean': 'rgba(118, 75, 162, 0.5)',
                        'sunset': 'rgba(245, 87, 108, 0.5)',
                        'forest': 'rgba(113, 178, 128, 0.5)',
                        'galaxy': 'rgba(123, 44, 191, 0.5)',
                        'neon': 'rgba(57, 255, 20, 0.5)',
                        'pastel': 'rgba(252, 182, 159, 0.5)',
                        'cyberpunk': 'rgba(0, 255, 245, 0.5)',
                        'aurora': 'rgba(88, 86, 214, 0.5)',
                        'cherry': 'rgba(255, 105, 180, 0.5)',
                        'midnight': 'rgba(165, 180, 252, 0.5)',
                        'retro': 'rgba(224, 122, 95, 0.5)',
                        'ice': 'rgba(0, 131, 143, 0.5)',
                        'lavender': 'rgba(75, 0, 130, 0.4)',
                        'matrix': 'rgba(0, 255, 0, 0.5)',
                    };
                    const themeHueShifts = {
                        'default': '30deg',
                        'neon': '60deg',
                        'cyberpunk': '-40deg',
                        'matrix': '120deg',
                        'sunset': '-30deg',
                        'ocean': '40deg',
                        'galaxy': '45deg',
                        'aurora': '50deg',
                        'cherry': '-20deg',
                        'ice': '20deg',
                        'retro': '-25deg',
                    };
                    const theme = this.design.theme || 'default';
                    document.documentElement.style.setProperty('--hover-glow-color', themeGlowColors[theme] || 'rgba(99, 102, 241, 0.4)');
                    document.documentElement.style.setProperty('--hover-hue-shift', themeHueShifts[theme] || '30deg');
                },
                
                updatePreview(data) {
                    if (data.profile) this.profile = { ...this.profile, ...data.profile };
                    if (data.blocks) this.updateBlocksSmart(data.blocks);
                    if (data.socials) this.socials = { ...this.socials, ...data.socials };
                    if (data.design) this.design = { ...this.design, ...data.design };
                    if (data.settings) this.settings = { ...this.settings, ...data.settings };
                },
                
                // Smart block update to prevent embed flickering
                updateBlocksSmart(newBlocks) {
                    // Create a map of existing blocks by ID
                    const existingMap = new Map(this.blocks.map(b => [b.id, b]));
                    const newMap = new Map(newBlocks.map(b => [b.id, b]));
                    
                    // Embed block types that shouldn't re-render unless embed_url changes
                    const embedTypes = ['youtube', 'spotify', 'soundcloud', 'video', 'music', 'map'];
                    
                    // Check if block order changed
                    const orderChanged = newBlocks.length !== this.blocks.length ||
                        newBlocks.some((b, i) => this.blocks[i]?.id !== b.id);
                    
                    if (orderChanged) {
                        // Order changed - need to rebuild array but preserve embed states
                        const updatedBlocks = newBlocks.map(newBlock => {
                            const existingBlock = existingMap.get(newBlock.id);
                            if (existingBlock && embedTypes.includes(newBlock.type)) {
                                // For embeds, only update if embed_url changed
                                if (existingBlock.embed_url === newBlock.embed_url &&
                                    existingBlock.map_address === newBlock.map_address) {
                                    // Keep existing block reference to prevent re-render
                                    return { ...existingBlock, ...newBlock, _embedKey: existingBlock._embedKey || newBlock.id };
                                }
                            }
                            return { ...newBlock, _embedKey: newBlock.embed_url || newBlock.map_address || newBlock.id };
                        });
                        this.blocks = updatedBlocks;
                    } else {
                        // Same order - update each block in place
                        newBlocks.forEach((newBlock, index) => {
                            const existingBlock = this.blocks[index];
                            if (!existingBlock) return;
                            
                            // For embed types, check if embed content changed
                            if (embedTypes.includes(newBlock.type)) {
                                const embedChanged = existingBlock.embed_url !== newBlock.embed_url ||
                                    existingBlock.map_address !== newBlock.map_address;
                                
                                if (!embedChanged) {
                                    // Only update non-embed properties
                                    Object.keys(newBlock).forEach(key => {
                                        if (key !== 'embed_url' && key !== 'map_address' && key !== '_embedKey') {
                                            this.blocks[index][key] = newBlock[key];
                                        }
                                    });
                                    return;
                                }
                            }
                            
                            // Update all properties
                            Object.assign(this.blocks[index], newBlock);
                        });
                    }
                },
                
                get bodyClasses() {
                    let classes = [];
                    if (this.design.backgroundType === 'gradient' && this.design.backgroundPreset) {
                        classes.push('bg-gradient-' + this.design.backgroundPreset);
                    }
                    return classes.join(' ');
                },
                
                get buttonRadius() {
                    const shapes = {
                        'rounded': '12px',
                        'pill': '9999px',
                        'square': '0',
                        'soft': '6px'
                    };
                    return shapes[this.design.buttonShape] || '12px';
                },
                
                // Get effective theme data
                get activeTheme() {
                    const themeName = this.design.theme;
                    if (themeName && themeName !== 'none' && themeName !== 'default' && this.themes[themeName]) {
                        return this.themes[themeName];
                    }
                    return null;
                },
                
                // Get effective button background color (respects theme)
                getEffectiveButtonBg() {
                    if (this.activeTheme) {
                        return this.activeTheme.link_bg;
                    }
                    // Use custom color or neutral default (not purple)
                    return this.design.buttonBgColor || '#f1f5f9';
                },
                
                // Get effective button text color (respects theme)
                getEffectiveButtonText() {
                    if (this.activeTheme) {
                        return this.activeTheme.link_text;
                    }
                    return this.design.buttonTextColor || '#1e293b';
                },
                
                // Get effective button border (for themes like neon)
                getEffectiveBorder() {
                    if (this.activeTheme && this.activeTheme.link_border) {
                        return '1px solid ' + this.activeTheme.link_border;
                    }
                    return 'none';
                },
                
                // Get effective background
                getEffectiveBackground() {
                    // Custom background image takes priority
                    if (this.design.backgroundType === 'image' && this.design.backgroundImage) {
                        return 'url(' + this.design.backgroundImage + ') center/cover fixed';
                    }
                    // If theme is active, use theme background
                    if (this.activeTheme) {
                        return this.activeTheme.background;
                    }
                    // Custom gradient or color
                    if (this.design.backgroundType === 'gradient' && this.design.backgroundGradient) {
                        return this.design.backgroundGradient;
                    }
                    return this.design.backgroundColor || '#f8fafc';
                },
                
                // Get effective title color
                getEffectiveTitleColor() {
                    if (this.activeTheme) {
                        return this.activeTheme.text;
                    }
                    return this.design.titleColor || this.design.textColor || '#1e293b';
                },
                
                // Get effective bio color
                getEffectiveBioColor() {
                    if (this.activeTheme) {
                        return this.activeTheme.bio;
                    }
                    return this.design.bioColor || '#64748b';
                },
                
                // Get content block colors (text, countdown) based on theme darkness
                isDarkTheme() {
                    const darkThemes = ['dark', 'minimal-dark', 'galaxy', 'neon', 'cyberpunk', 'aurora', 'midnight', 'matrix'];
                    return darkThemes.includes(this.design.theme);
                },
                
                getContentBlockBg() {
                    return this.isDarkTheme() ? 'rgba(255, 255, 255, 0.08)' : 'rgba(255, 255, 255, 0.7)';
                },
                
                getContentBlockBorder() {
                    return this.isDarkTheme() ? 'rgba(255, 255, 255, 0.15)' : 'rgba(0, 0, 0, 0.1)';
                },
                
                getContentBlockHeaderBg() {
                    return this.isDarkTheme() ? 'rgba(255, 255, 255, 0.05)' : 'rgba(241, 245, 249, 0.5)';
                },
                
                getCountdownBg() {
                    const linkBg = this.getEffectiveButtonBg();
                    return `linear-gradient(135deg, ${linkBg}, ${linkBg}cc)`;
                },
                
                getInitials(name) {
                    if (!name) return '?';
                    return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
                },
                
                getBrandColor(platform) {
                    return brandColors[platform] || '#6b7280';
                },

                isLightColor(color) {
                    if (!color) return true;
                    
                    // Handle hex
                    if (color.startsWith('#')) {
                        const hex = color.replace('#', '');
                        // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
                        const fullHex = hex.length === 3 ? hex.split('').map(c => c + c).join('') : hex;
                        
                        const r = parseInt(fullHex.substr(0, 2), 16);
                        const g = parseInt(fullHex.substr(2, 2), 16);
                        const b = parseInt(fullHex.substr(4, 2), 16);
                        const brightness = ((r * 299) + (g * 587) + (b * 114)) / 1000;
                        return brightness > 155;
                    }
                    
                    // Handle rgb/rgba
                    if (color.startsWith('rgb')) {
                        const rgb = color.match(/\d+/g);
                        if (rgb && rgb.length >= 3) {
                            const brightness = ((parseInt(rgb[0]) * 299) + (parseInt(rgb[1]) * 587) + (parseInt(rgb[2]) * 114)) / 1000;
                            return brightness > 155;
                        }
                    }
                    
                    return false;
                },
                
                // Video embed helper - more versatile
                getVideoEmbed(url) {
                    if (!url) return '<div class="text-center opacity-50">No video URL</div>';
                    
                    // Already an embed URL - extract and use
                    if (url.includes('/embed/') || url.includes('youtube.com/embed')) {
                        const embedMatch = url.match(/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/);
                        if (embedMatch) {
                            return `<iframe src="https://www.youtube.com/embed/${embedMatch[1]}?rel=0" style="width:100%;aspect-ratio:16/9;border-radius:12px;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
                        }
                    }
                    
                    // YouTube - various URL formats
                    const ytMatch = url.match(/(?:youtube\.com\/(?:watch\?v=|shorts\/|live\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/);
                    if (ytMatch) {
                        return `<iframe src="https://www.youtube.com/embed/${ytMatch[1]}?rel=0" style="width:100%;aspect-ratio:16/9;border-radius:12px;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
                    }
                    
                    // YouTube shorts/live/playlist
                    const ytPlaylistMatch = url.match(/youtube\.com\/playlist\?list=([a-zA-Z0-9_-]+)/);
                    if (ytPlaylistMatch) {
                        return `<iframe src="https://www.youtube.com/embed/videoseries?list=${ytPlaylistMatch[1]}" style="width:100%;aspect-ratio:16/9;border-radius:12px;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
                    }
                    
                    // Vimeo
                    const vimeoMatch = url.match(/vimeo\.com\/(\d+)/);
                    if (vimeoMatch) {
                        return `<iframe src="https://player.vimeo.com/video/${vimeoMatch[1]}?dnt=1" style="width:100%;aspect-ratio:16/9;border-radius:12px;" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>`;
                    }
                    
                    // TikTok embed
                    const tiktokMatch = url.match(/tiktok\.com\/@[\w.]+\/video\/(\d+)/);
                    if (tiktokMatch) {
                        return `<iframe src="https://www.tiktok.com/embed/${tiktokMatch[1]}" style="width:100%;min-height:400px;border-radius:12px;" allow="autoplay; encrypted-media" allowfullscreen></iframe>`;
                    }
                    
                    return '<div class="text-center opacity-50 p-4">Paste YouTube, Vimeo, or TikTok URL</div>';
                },
                
                // Music embed helper - more versatile
                getMusicEmbed(url) {
                    if (!url) return '<div class="text-center opacity-50">No music URL</div>';
                    
                    // Spotify - handle various formats including embed URLs
                    if (url.includes('spotify.com')) {
                        // Already an embed URL
                        if (url.includes('/embed/')) {
                            const embedMatch = url.match(/spotify\.com\/embed\/(track|album|playlist|artist|episode|show)\/([a-zA-Z0-9]+)/);
                            if (embedMatch) {
                                return `<iframe src="https://open.spotify.com/embed/${embedMatch[1]}/${embedMatch[2]}?utm_source=generator&theme=0" style="width:100%;border-radius:12px;" height="152" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>`;
                            }
                        }
                        // Regular Spotify URLs
                        const spotifyMatch = url.match(/spotify\.com\/(track|album|playlist|artist|episode|show)\/([a-zA-Z0-9]+)/);
                        if (spotifyMatch) {
                            const height = ['album', 'playlist'].includes(spotifyMatch[1]) ? '380' : '152';
                            return `<iframe src="https://open.spotify.com/embed/${spotifyMatch[1]}/${spotifyMatch[2]}?utm_source=generator&theme=0" style="width:100%;border-radius:12px;" height="${height}" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>`;
                        }
                    }
                    
                    // SoundCloud - handle various formats
                    if (url.includes('soundcloud.com')) {
                        // Get track/playlist from URL
                        const isPlaylist = url.includes('/sets/');
                        const height = isPlaylist ? '300' : '166';
                        return `<iframe style="width:100%;border-radius:12px;" height="${height}" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url=${encodeURIComponent(url)}&color=%230ea5e9&auto_play=false&hide_related=true&show_comments=false&show_user=true&show_reposts=false&show_teaser=false&visual=${isPlaylist}"></iframe>`;
                    }
                    
                    // Apple Music
                    if (url.includes('music.apple.com')) {
                        const appleMatch = url.match(/music\.apple\.com\/([a-z]{2})\/(album|playlist|song)\/[^\/]+\/(\d+)/i);
                        if (appleMatch) {
                            const type = appleMatch[2] === 'song' ? 'song' : appleMatch[2];
                            return `<iframe allow="autoplay *; encrypted-media *; fullscreen *; clipboard-write" frameborder="0" height="175" style="width:100%;max-width:660px;overflow:hidden;border-radius:10px;" sandbox="allow-forms allow-popups allow-same-origin allow-scripts allow-storage-access-by-user-activation allow-top-navigation-by-user-activation" src="https://embed.music.apple.com/${appleMatch[1]}/${type}/${appleMatch[3]}"></iframe>`;
                        }
                    }
                    
                    // YouTube Music - redirect to regular YouTube embed
                    if (url.includes('music.youtube.com')) {
                        const ytMusicMatch = url.match(/watch\?v=([a-zA-Z0-9_-]+)/);
                        if (ytMusicMatch) {
                            return `<iframe src="https://www.youtube.com/embed/${ytMusicMatch[1]}?rel=0" style="width:100%;aspect-ratio:16/9;border-radius:12px;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
                        }
                    }
                    
                    return '<div class="text-center opacity-50 p-4">Paste Spotify, SoundCloud, or Apple Music URL</div>';
                },
                
                // Countdown calculator
                calculateCountdown(targetDate) {
                    if (!targetDate) return { days: '00', hours: '00', minutes: '00', seconds: '00' };
                    
                    const target = new Date(targetDate);
                    const now = new Date();
                    const diff = target - now;
                    
                    if (diff <= 0) {
                        return { days: '00', hours: '00', minutes: '00', seconds: '00' };
                    }
                    
                    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((diff % (1000 * 60)) / 1000);
                    
                    return {
                        days: String(days).padStart(2, '0'),
                        hours: String(hours).padStart(2, '0'),
                        minutes: String(minutes).padStart(2, '0'),
                        seconds: String(seconds).padStart(2, '0')
                    };
                },
                
                // Sanitize HTML (basic)
                sanitizeHtml(html) {
                    if (!html) return '';
                    // Remove script tags and event handlers
                    return html
                        .replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '')
                        .replace(/on\w+\s*=\s*["'][^"']*["']/gi, '')
                        .replace(/on\w+\s*=\s*[^\s>]+/gi, '');
                },
                
                // Decode HTML entities
                decodeHtmlEntities(text) {
                    if (!text) return '';
                    const textarea = document.createElement('textarea');
                    textarea.innerHTML = text;
                    return textarea.value;
                },
                
                // Download VCard
                downloadVCard(block) {
                    const vcard = `BEGIN:VCARD
VERSION:3.0
FN:${block.vcard_name || ''}
ORG:${block.vcard_company || ''}
TEL:${block.vcard_phone || ''}
EMAIL:${block.vcard_email || ''}
END:VCARD`;
                    
                    const blob = new Blob([vcard], { type: 'text/vcard' });
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `${(block.vcard_name || 'contact').replace(/\s+/g, '_')}.vcf`;
                    a.click();
                    URL.revokeObjectURL(url);
                },
                
                // Initialize background animation - React-Snowfall inspired
                initBackgroundAnimation(type) {
                    const container = this.$refs.bgAnimContainer;
                    if (!container) return;
                    
                    // Debounce rapid animation changes
                    if (this._animDebounce) {
                        clearTimeout(this._animDebounce);
                    }
                    
                    // Cancel any existing animation frames immediately
                    if (container._animationId) {
                        cancelAnimationFrame(container._animationId);
                        container._animationId = null;
                    }
                    if (container._matrixId) {
                        cancelAnimationFrame(container._matrixId);
                        container._matrixId = null;
                    }
                    // Clear droplet interval if exists
                    if (container._dropletInterval) {
                        clearInterval(container._dropletInterval);
                        container._dropletInterval = null;
                    }
                    
                    // Clear existing content
                    container.innerHTML = '';
                    
                    // Debounce the animation start
                    this._animDebounce = setTimeout(() => {
                        this._startAnimation(container, type);
                    }, 100);
                },
                
                _startAnimation(container, type) {
                    // Check for matrix theme - add matrix background only if matrix animation selected
                    const theme = this.design.theme;
                    if ((theme === 'neon' || theme === 'matrix') && type === 'matrix') {
                        this.initMatrixEffect(container);
                        return; // Matrix is the only animation for this selection
                    }
                    
                    if (!type || type === 'none') return;
                    
                    // Detect light themes for visibility adjustments
                    const lightThemes = ['default', 'light', 'classic', 'minimal-light', 'pastel', 'cherry', 'ice', 'lavender', 'retro'];
                    const isLightTheme = lightThemes.includes(theme) || !theme;
                    
                    const config = {
                        'snow': { 
                            chars: ['❄', '❅', '❆', '✦', '•'],
                            count: 40,
                            speed: { min: 0.8, max: 2.5 },
                            wind: { min: -0.3, max: 0.5 },
                            radius: { min: 8, max: 18 },
                            color: isLightTheme ? '#6b7280' : '#dee4fd',
                            opacity: { min: 0.5, max: 1 }
                        },
                        'leaves': { 
                            chars: ['🍂', '🍁', '🍃'],
                            count: 25,
                            speed: { min: 1, max: 2.5 },
                            wind: { min: -0.8, max: 1 },
                            radius: { min: 16, max: 26 },
                            rotate: true,
                            opacity: { min: 0.7, max: 1 }
                        },
                        'rain': { 
                            isRain: true,
                            isLightTheme: isLightTheme,
                            count: 120,
                            speed: { min: 10, max: 18 },
                            wind: { min: 0, max: 0.4 },
                            length: { min: 15, max: 40 },
                            width: { min: 1, max: 1.5 },
                            opacity: { min: 0.1, max: 0.4 },
                            windowDroplets: true
                        },
                        'stars': { 
                            chars: ['✦', '✧', '★', '☆'],
                            count: 30,
                            twinkle: true,
                            radius: { min: 8, max: 14 },
                            color: isLightTheme ? '#f59e0b' : '#ffd700',
                            opacity: { min: 0.3, max: 1 }
                        },
                        'hearts': { 
                            chars: ['❤', '💕', '💗', '♥'],
                            count: 25,
                            speed: { min: 0.6, max: 1.4 },
                            wind: { min: -0.4, max: 0.4 },
                            radius: { min: 14, max: 22 },
                            opacity: { min: 0.6, max: 1 }
                        },
                        'confetti': { 
                            chars: ['■', '●', '▲', '◆'],
                            count: 35,
                            speed: { min: 1.2, max: 2.8 },
                            wind: { min: -0.8, max: 0.8 },
                            radius: { min: 8, max: 14 },
                            multicolor: true,
                            isLightTheme: isLightTheme,
                            rotate: true,
                            opacity: { min: 0.7, max: 1 }
                        },
                        'particles': { 
                            chars: ['●', '○'],
                            count: 30,
                            speed: { min: 0.4, max: 1 },
                            wind: { min: -0.2, max: 0.2 },
                            radius: { min: 4, max: 10 },
                            glow: true,
                            multicolor: true,
                            isLightTheme: isLightTheme,
                            opacity: { min: 0.5, max: 0.9 }
                        }
                    };
                    
                    const c = config[type];
                    if (!c) return;
                    
                    // Create canvas
                    const canvas = document.createElement('canvas');
                    canvas.style.cssText = 'position:absolute;top:0;left:0;width:100%;height:100%;';
                    container.appendChild(canvas);
                    const ctx = canvas.getContext('2d');
                    
                    const resize = () => {
                        canvas.width = container.offsetWidth || 375;
                        canvas.height = container.offsetHeight || 667;
                    };
                    resize();
                    
                    // Particle class
                    class Particle {
                        constructor(initial = false) {
                            this.reset(initial);
                        }
                        
                        reset(initial = false) {
                            this.x = Math.random() * canvas.width;
                            this.y = initial ? Math.random() * canvas.height : -50;
                            this.speed = c.speed ? c.speed.min + Math.random() * (c.speed.max - c.speed.min) : 0;
                            this.wind = c.wind ? c.wind.min + Math.random() * (c.wind.max - c.wind.min) : 0;
                            
                            // For rain, use length/width; for others use radius
                            if (c.isRain) {
                                this.length = c.length.min + Math.random() * (c.length.max - c.length.min);
                                this.width = c.width.min + Math.random() * (c.width.max - c.width.min);
                            } else {
                                this.radius = c.radius.min + Math.random() * (c.radius.max - c.radius.min);
                                this.length = c.length ? c.length.min + Math.random() * (c.length.max - c.length.min) : this.radius;
                            }
                            
                            this.opacity = c.opacity.min + Math.random() * (c.opacity.max - c.opacity.min);
                            this.char = c.chars ? c.chars[Math.floor(Math.random() * c.chars.length)] : null;
                            this.rotation = Math.random() * 360;
                            this.rotationSpeed = c.rotate ? (Math.random() - 0.5) * 3 : 0;
                            this.wobble = Math.random() * Math.PI * 2;
                            this.wobbleSpeed = 0.02 + Math.random() * 0.02;
                            
                            if (c.multicolor) {
                                // Use darker, more saturated colors on light themes for visibility
                                if (c.isLightTheme) {
                                    this.color = `hsl(${Math.random() * 360}, 85%, 45%)`;
                                } else {
                                    this.color = `hsl(${Math.random() * 360}, 70%, 60%)`;
                                }
                            } else if (c.color) {
                                this.color = c.color;
                            } else {
                                this.color = '#ffffff';
                            }
                            
                            if (c.twinkle) {
                                this.twinklePhase = Math.random() * Math.PI * 2;
                                this.twinkleSpeed = 0.02 + Math.random() * 0.02;
                            }
                        }
                        
                        update() {
                            if (c.twinkle) {
                                this.twinklePhase += this.twinkleSpeed;
                                this.opacity = c.opacity.min + (Math.sin(this.twinklePhase) + 1) / 2 * (c.opacity.max - c.opacity.min);
                                return;
                            }
                            
                            this.wobble += this.wobbleSpeed;
                            this.y += this.speed;
                            this.x += this.wind + Math.sin(this.wobble) * 0.3;
                            this.rotation += this.rotationSpeed;
                            
                            if (this.y > canvas.height + 20 || this.x < -30 || this.x > canvas.width + 30) {
                                this.reset();
                            }
                        }
                        
                        draw() {
                            ctx.save();
                            ctx.globalAlpha = this.opacity;
                            
                            if (c.isRain) {
                                // Realistic rain streak - thin, semi-transparent line
                                const gradient = ctx.createLinearGradient(
                                    this.x, this.y,
                                    this.x + this.wind * 2, this.y + this.length
                                );
                                
                                // Use darker colors on light themes for visibility
                                if (c.isLightTheme) {
                                    gradient.addColorStop(0, 'rgba(100, 130, 180, 0)');
                                    gradient.addColorStop(0.1, 'rgba(80, 120, 170, 0.5)');
                                    gradient.addColorStop(0.9, 'rgba(60, 100, 150, 0.7)');
                                    gradient.addColorStop(1, 'rgba(60, 100, 150, 0)');
                                } else {
                                    gradient.addColorStop(0, 'rgba(200, 220, 255, 0)');
                                    gradient.addColorStop(0.1, 'rgba(200, 220, 255, 0.6)');
                                    gradient.addColorStop(0.9, 'rgba(255, 255, 255, 0.8)');
                                    gradient.addColorStop(1, 'rgba(255, 255, 255, 0)');
                                }
                                
                                ctx.strokeStyle = gradient;
                                ctx.lineWidth = this.width;
                                ctx.lineCap = 'round';
                                ctx.beginPath();
                                ctx.moveTo(this.x, this.y);
                                ctx.lineTo(this.x + this.wind * 2, this.y + this.length);
                                ctx.stroke();
                            } else {
                                ctx.translate(this.x, this.y);
                                ctx.rotate(this.rotation * Math.PI / 180);
                                
                                if (c.glow) {
                                    ctx.shadowColor = this.color;
                                    ctx.shadowBlur = 8;
                                }
                                
                                ctx.fillStyle = this.color;
                                ctx.font = `${this.radius}px sans-serif`;
                                ctx.textAlign = 'center';
                                ctx.textBaseline = 'middle';
                                ctx.fillText(this.char, 0, 0);
                            }
                            
                            ctx.restore();
                        }
                    }
                    
                    const particles = [];
                    for (let i = 0; i < c.count; i++) {
                        particles.push(new Particle(true));
                    }
                    
                    // Window droplets for rain effect (dynamic drops on "glass")
                    let windowDroplets = [];
                    let dropletInterval = null;
                    if (c.windowDroplets) {
                        // Preview uses smaller screen so fewer droplets
                        const screenArea = canvas.width * canvas.height;
                        const baseDroplets = Math.floor(screenArea / 15000);
                        const maxDroplets = Math.min(45, Math.max(15, baseDroplets));
                        const initialDroplets = Math.floor(maxDroplets * 0.6);
                        
                        class WindowDroplet {
                            constructor(startFromTop = false) {
                                this.reset(startFromTop);
                            }
                            
                            reset(startFromTop = false) {
                                this.x = Math.random() * canvas.width;
                                this.y = startFromTop ? -5 : Math.random() * canvas.height;
                                this.radius = 1.5 + Math.random() * 5;
                                this.baseOpacity = 0.12 + Math.random() * 0.18;
                                this.opacity = this.baseOpacity;
                                this.life = 100 + Math.random() * 200;
                                this.age = 0;
                                this.sliding = false;
                                this.slideSpeed = 0;
                                this.wobblePhase = Math.random() * Math.PI * 2;
                                this.wobbleSpeed = 0.025 + Math.random() * 0.03;
                                this.wobbleAmount = 0.08 + Math.random() * 0.15;
                            }
                            
                            update() {
                                this.age++;
                                this.wobblePhase += this.wobbleSpeed;
                                this.opacity = this.baseOpacity + Math.sin(this.wobblePhase) * 0.04;
                                
                                if (this.age > this.life && !this.sliding) {
                                    this.sliding = true;
                                    this.slideSpeed = 0.12 + Math.random() * 0.3;
                                }
                                if (this.sliding) {
                                    this.y += this.slideSpeed;
                                    this.x += Math.sin(this.wobblePhase) * this.wobbleAmount;
                                    this.slideSpeed += 0.012;
                                    this.radius *= 0.997;
                                    this.opacity *= 0.985;
                                }
                                if (this.y > canvas.height + 10 || this.radius < 0.8 || this.opacity < 0.02) {
                                    this.reset(true);
                                }
                            }
                            
                            draw() {
                                ctx.save();
                                ctx.globalAlpha = this.opacity;
                                const gradient = ctx.createRadialGradient(
                                    this.x - this.radius * 0.3, this.y - this.radius * 0.3, 0,
                                    this.x, this.y, this.radius
                                );
                                gradient.addColorStop(0, 'rgba(255, 255, 255, 0.8)');
                                gradient.addColorStop(0.25, 'rgba(210, 230, 250, 0.45)');
                                gradient.addColorStop(0.6, 'rgba(160, 190, 220, 0.2)');
                                gradient.addColorStop(1, 'rgba(100, 150, 200, 0.06)');
                                ctx.beginPath();
                                ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
                                ctx.fillStyle = gradient;
                                ctx.fill();
                                ctx.beginPath();
                                ctx.arc(this.x - this.radius * 0.25, this.y - this.radius * 0.25, this.radius * 0.25, 0, Math.PI * 2);
                                ctx.fillStyle = 'rgba(255, 255, 255, 0.55)';
                                ctx.fill();
                                ctx.restore();
                            }
                        }
                        for (let i = 0; i < initialDroplets; i++) {
                            windowDroplets.push(new WindowDroplet(false));
                        }
                        dropletInterval = setInterval(() => {
                            if (windowDroplets.length < maxDroplets) {
                                const d = new WindowDroplet(true);
                                d.y = Math.random() * canvas.height * 0.25;
                                windowDroplets.push(d);
                            }
                            if (windowDroplets.length > initialDroplets && Math.random() < 0.1) {
                                const oldestSliding = windowDroplets.findIndex(d => d.sliding);
                                if (oldestSliding > -1) windowDroplets.splice(oldestSliding, 1);
                            }
                        }, 350 + Math.random() * 300);
                    }
                    
                    let animationId;
                    const animate = () => {
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                        windowDroplets.forEach(d => { d.update(); d.draw(); });
                        particles.forEach(p => { p.update(); p.draw(); });
                        animationId = requestAnimationFrame(animate);
                    };
                    animate();
                    
                    // Store animation ID and interval for cleanup
                    container._animationId = animationId;
                    container._dropletInterval = dropletInterval;
                },
                
                // Matrix effect for neon/matrix themes
                initMatrixEffect(container) {
                    const canvas = document.createElement('canvas');
                    canvas.style.cssText = 'position:absolute;top:0;left:0;width:100%;height:100%;opacity:0.35;';
                    container.appendChild(canvas);
                    const ctx = canvas.getContext('2d');
                    
                    canvas.width = container.offsetWidth || 375;
                    canvas.height = container.offsetHeight || 667;
                    
                    const chars = 'アイウエオカキクケコサシスセソタチツテト0123456789ABCDEF<>{}[]';
                    const fontSize = 14;
                    const columns = Math.floor(canvas.width / fontSize);
                    const drops = [];
                    
                    for (let i = 0; i < columns; i++) {
                        drops[i] = Math.random() * -50;
                    }
                    
                    let frameCount = 0;
                    const drawMatrix = () => {
                        frameCount++;
                        // Only update every 2nd frame for performance
                        if (frameCount % 2 === 0) {
                            ctx.fillStyle = 'rgba(0, 0, 0, 0.08)';
                            ctx.fillRect(0, 0, canvas.width, canvas.height);
                            
                            ctx.font = `bold ${fontSize}px monospace`;
                            
                            for (let i = 0; i < drops.length; i++) {
                                const char = chars[Math.floor(Math.random() * chars.length)];
                                const x = i * fontSize;
                                const y = drops[i] * fontSize;
                                
                                // Brighter green with better visibility
                                const brightness = Math.random() * 0.4 + 0.6;
                                ctx.fillStyle = `rgba(0, ${Math.floor(255 * brightness)}, ${Math.floor(50 * brightness)}, ${brightness})`;
                                ctx.fillText(char, x, y);
                                
                                // Add glow effect for head of stream
                                if (Math.random() > 0.95) {
                                    ctx.fillStyle = '#ffffff';
                                    ctx.fillText(char, x, y);
                                }
                                
                                if (y > canvas.height && Math.random() > 0.975) {
                                    drops[i] = 0;
                                }
                                drops[i] += 0.5 + Math.random() * 0.4;
                            }
                        }
                        
                        container._matrixId = requestAnimationFrame(drawMatrix);
                    };
                    drawMatrix();
                }
            };
        }
    </script>
    
    <!-- Alpine Collapse Plugin for FAQ -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.directive('collapse', (el, { expression }, { effect, evaluateLater }) => {
                let show = evaluateLater(expression || 'true');
                
                effect(() => {
                    show(value => {
                        if (value) {
                            el.style.height = 'auto';
                            el.style.overflow = 'visible';
                        } else {
                            el.style.height = '0';
                            el.style.overflow = 'hidden';
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>
