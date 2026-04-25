<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LEJ</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Press+Start+2P&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind = {
            config: {
                corePlugins: {
                    preflight: false
                },
                theme: {
                    extend: {
                        keyframes: {
                            floaty: {
                                '0%, 100%': {
                                    transform: 'translateY(0)'
                                },
                                '50%': {
                                    transform: 'translateY(-10px)'
                                }
                            },
                            floatySlow: {
                                '0%, 100%': {
                                    transform: 'translateY(0) translateX(0)'
                                },
                                '50%': {
                                    transform: 'translateY(-14px) translateX(8px)'
                                }
                            },
                            pulseGlow: {
                                '0%, 100%': {
                                    boxShadow: '0 0 0 rgba(0, 229, 255, 0.0)'
                                },
                                '50%': {
                                    boxShadow: '0 0 30px rgba(0, 229, 255, 0.35)'
                                }
                            }
                        },
                        animation: {
                            floaty: 'floaty 5s ease-in-out infinite',
                            floatySlow: 'floatySlow 8s ease-in-out infinite',
                            pulseGlow: 'pulseGlow 3.5s ease-in-out infinite'
                        }
                    }
                }
            }
        };
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.165.0/build/three.min.js"></script>
    <style>
        :root {
            --bg: #060612;
            --text: #f0f0ff;
            --text2: rgba(240, 240, 255, 0.6);
            --text3: rgba(240, 240, 255, 0.35);
            --border: rgba(255, 255, 255, 0.08);
            --border2: rgba(255, 255, 255, 0.14);
            --grad1: linear-gradient(135deg, #7c6fff, #00e5ff);
            --danger-bg: rgba(255, 107, 107, 0.1);
            --danger-border: rgba(255, 107, 107, 0.25);
            --danger-text: #ff9090;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            overflow: hidden;
        }

        .colorbends-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: auto;
        }

        .colorbends-canvas {
            width: 100%;
            height: 100%;
            border: none;
            display: block;
            opacity: 1;
            filter: none;
        }

        .orb {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
            filter: blur(80px);
            opacity: 0;
            display: none;
        }

        .orb1 {
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, #7c6fff, transparent 70%);
            top: -10%;
            left: -10%;
            animation: drift1 18s ease-in-out infinite;
        }

        .orb2 {
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, #00e5ff, transparent 70%);
            bottom: -5%;
            right: -5%;
            animation: drift2 22s ease-in-out infinite;
        }

        .orb3 {
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, #ff6bcd, transparent 70%);
            top: 40%;
            left: 40%;
            animation: drift3 16s ease-in-out infinite;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: linear-gradient(rgba(255, 255, 255, 0.015) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, 0.015) 1px, transparent 1px);
            background-size: 48px 48px;
            pointer-events: none;
            z-index: 0;
            opacity: 0;
            display: none;
        }

        .login-page {
            position: fixed;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            padding: 18px;
        }

        .login-wrap {
            position: relative;
            width: 100%;
            max-width: 420px;
            animation: loginIn 0.7s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .login-top-brand {
            position: relative;
            z-index: 15;
            text-align: center;
            margin-bottom: 16px;
        }

        .login-top-brand .focus-container {
            position: relative;
            display: inline-flex;
            gap: 0.35em;
            justify-content: center;
            align-items: center;
            user-select: none;
        }

        .login-top-brand .focus-word {
            position: relative;
            font-family: 'Space Grotesk', sans-serif;
            font-size: clamp(34px, 8vw, 64px);
            font-weight: 700;
            line-height: 1;
            letter-spacing: -0.03em;
            background: var(--grad1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 10px 30px rgba(124, 111, 255, 0.25);
            transition: filter 0.5s ease;
        }

        .login-top-brand .focus-frame {
            position: absolute;
            top: 0;
            left: 0;
            pointer-events: none;
            box-sizing: content-box;
            opacity: 0;
            transition: transform 0.5s ease, width 0.5s ease, height 0.5s ease, opacity 0.35s ease;
        }

        .login-top-brand .corner {
            position: absolute;
            width: 0.9rem;
            height: 0.9rem;
            border: 3px solid #00e5ff;
            filter: drop-shadow(0px 0px 5px rgba(0, 229, 255, 0.65));
            border-radius: 3px;
        }

        .login-top-brand .top-left {
            top: -9px;
            left: -9px;
            border-right: none;
            border-bottom: none;
        }

        .login-top-brand .top-right {
            top: -9px;
            right: -9px;
            border-left: none;
            border-bottom: none;
        }

        .login-top-brand .bottom-left {
            bottom: -9px;
            left: -9px;
            border-right: none;
            border-top: none;
        }

        .login-top-brand .bottom-right {
            bottom: -9px;
            right: -9px;
            border-left: none;
            border-top: none;
        }

        .login-top-brand-sub {
            margin-top: 8px;
            font-size: 12px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--text2);
        }

        .login-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border2);
            backdrop-filter: blur(40px) saturate(180%);
            -webkit-backdrop-filter: blur(40px) saturate(180%);
            border-radius: 24px;
            padding: 40px;
            position: relative;
            overflow: hidden;
            --mouse-x: 50%;
            --mouse-y: 50%;
            --spotlight-color: rgba(0, 229, 255, 0.2);
        }

        .login-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(124, 111, 255, 0.12), rgba(0, 229, 255, 0.08));
            pointer-events: none;
        }

        .login-card::selection {
            background: rgba(0, 229, 255, 0.25);
        }

        .login-card.card-spotlight::before {
            background:
                radial-gradient(circle at var(--mouse-x) var(--mouse-y), var(--spotlight-color), transparent 52%),
                linear-gradient(135deg, rgba(124, 111, 255, 0.12), rgba(0, 229, 255, 0.08));
            opacity: 0.86;
            transition: background 0.18s ease;
        }

        .login-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--grad1);
            opacity: 0.6;
        }

        .login-logo {
            text-align: center;
            margin-bottom: 28px;
            position: relative;
            z-index: 1;
        }

        .reactbits-frame {
            position: relative;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 8px;
            padding: 3px 10px;
            isolation: isolate;
        }

        .reactbits-title {
            display: inline-block;
            margin-bottom: 8px;
            font-family: 'Press Start 2P', monospace;
            font-size: clamp(12px, 1.7vw, 18px);
            font-weight: 400;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #eef3ff;
            text-shadow: 0 0 10px rgba(238, 243, 255, 0.28);
            position: relative;
            overflow: hidden;
            z-index: 3;
            text-align: center;
            line-height: 1.1;
            min-width: 12ch;
        }

        .reactbits-title::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(100deg, transparent 25%, rgba(255, 255, 255, 0.35) 48%, transparent 72%);
            transform: translateX(-120%);
            animation: rbShimmer 3.8s linear infinite;
            pointer-events: none;
        }

        .reactbits-title.reactbits-ghost {
            position: absolute;
            inset: 3px 10px;
            margin: 0;
            overflow: visible;
            text-shadow: none;
            pointer-events: none;
            z-index: 2;
        }

        .reactbits-ghost-a {
            color: rgba(0, 245, 255, 0.55);
            animation: rbGlitchA 2.2s steps(2, end) infinite;
        }

        .reactbits-ghost-b {
            color: rgba(255, 72, 178, 0.52);
            animation: rbGlitchB 2.8s steps(2, end) infinite;
        }

        .reactbits-scanline {
            position: absolute;
            inset: 0;
            border-radius: 8px;
            background: repeating-linear-gradient(to bottom, rgba(255, 255, 255, 0.08) 0px, rgba(255, 255, 255, 0.08) 1px, transparent 2px, transparent 5px);
            mix-blend-mode: soft-light;
            opacity: 0.16;
            pointer-events: none;
            animation: rbScan 7s linear infinite;
            z-index: 1;
        }

        .reactbits-particles {
            position: absolute;
            inset: -8px;
            pointer-events: none;
            z-index: 0;
        }

        .reactbits-particle {
            position: absolute;
            width: 2px;
            height: 2px;
            border-radius: 50%;
            background: rgba(0, 229, 255, 0.9);
            box-shadow: 0 0 8px rgba(0, 229, 255, 0.7);
            animation: rbParticle var(--p-dur, 3s) ease-in-out infinite;
            animation-delay: var(--p-delay, 0s);
            opacity: 0.8;
        }

        .shuffle-parent {
            visibility: hidden;
            will-change: transform;
            white-space: normal;
            word-wrap: break-word;
        }

        .shuffle-parent.is-ready {
            visibility: visible;
        }

        .shuffle-char-wrapper {
            display: inline-block;
            overflow: hidden;
            vertical-align: baseline;
            position: relative;
            line-height: 1;
        }

        .shuffle-strip {
            display: inline-flex;
            white-space: nowrap;
            will-change: transform;
            line-height: 1;
        }

        .shuffle-char {
            line-height: 1;
            display: inline-block;
            text-align: center;
        }

        .login-logo-mark {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 48px;
            font-weight: 700;
            background: var(--grad1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -1px;
            line-height: 1.1;
        }

        .login-logo-line {
            width: 56px;
            height: 2.5px;
            background: var(--grad1);
            margin: 12px auto 16px;
            border-radius: 2px;
        }

        .login-logo p {
            color: var(--text2);
            font-size: 13px;
            letter-spacing: 0.3px;
            margin: 0;
        }

        .alert {
            position: relative;
            z-index: 1;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            margin-bottom: 14px;
        }

        .alert-danger {
            background: var(--danger-bg);
            border: 1px solid var(--danger-border);
            color: var(--danger-text);
        }

        .alert-success {
            background: rgba(0, 255, 178, 0.1);
            border: 1px solid rgba(0, 255, 178, 0.25);
            color: #9bffe0;
        }

        .input-wrap {
            position: relative;
            margin-bottom: 14px;
            z-index: 1;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.4;
            pointer-events: none;
            color: var(--text2);
        }

        .form-inp {
            width: 100%;
            padding: 12px 14px 12px 42px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid var(--border2);
            border-radius: 10px;
            color: var(--text);
            font-size: 14px;
            font-family: inherit;
            outline: none;
            transition: all 0.25s;
        }

        .form-inp:focus {
            border-color: rgba(124, 111, 255, 0.5);
            background: rgba(124, 111, 255, 0.08);
            box-shadow: 0 0 0 3px rgba(124, 111, 255, 0.1);
        }

        .form-inp::placeholder {
            color: var(--text3);
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            background: var(--grad1);
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            letter-spacing: 0.3px;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.15), transparent);
            pointer-events: none;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(124, 111, 255, 0.4);
        }

        @keyframes loginIn {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        @keyframes drift1 {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            50% {
                transform: translate(60px, 40px) scale(1.1);
            }
        }

        @keyframes drift2 {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            50% {
                transform: translate(-50px, -30px) scale(1.15);
            }
        }

        @keyframes drift3 {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            50% {
                transform: translate(30px, -40px) scale(0.9);
            }
        }

        @keyframes rbShimmer {
            0% {
                transform: translateX(-120%);
            }

            100% {
                transform: translateX(120%);
            }
        }

        @keyframes rbGlitchA {

            0%,
            78%,
            100% {
                transform: translate(0, 0);
                opacity: 0;
            }

            80% {
                transform: translate(2px, -1px);
                opacity: 1;
            }

            82% {
                transform: translate(-2px, 1px);
                opacity: 0.75;
            }
        }

        @keyframes rbGlitchB {

            0%,
            62%,
            100% {
                transform: translate(0, 0);
                opacity: 0;
            }

            64% {
                transform: translate(-1px, 1px);
                opacity: 1;
            }

            66% {
                transform: translate(2px, -1px);
                opacity: 0.8;
            }
        }

        @keyframes rbScan {
            0% {
                transform: translateY(-10%);
            }

            100% {
                transform: translateY(10%);
            }
        }

        @keyframes rbParticle {

            0%,
            100% {
                transform: translate(0, 0) scale(0.9);
                opacity: 0.25;
            }

            50% {
                transform: translate(var(--p-x, 6px), var(--p-y, -8px)) scale(1.2);
                opacity: 1;
            }
        }

        @media (max-width: 575.98px) {
            .login-card {
                padding: 28px 22px;
                border-radius: 18px;
            }

            .login-top-brand {
                margin-bottom: 12px;
            }

            .login-top-brand-sub {
                font-size: 11px;
                letter-spacing: 0.1em;
            }

            .login-top-brand .focus-word {
                font-size: clamp(28px, 10vw, 44px);
            }
        }
    </style>
</head>

<body>
    <div class="colorbends-bg" aria-hidden="true">
        <canvas id="colorBendsBg" class="colorbends-canvas"></canvas>
    </div>

    <div class="orb orb1"></div>
    <div class="orb orb2"></div>
    <div class="orb orb3"></div>

    <div class="login-page">
        <div class="login-wrap relative">
            <div class="login-top-brand">
                <div class="focus-container" data-truefocus="toko-lej" data-manual-mode="false" data-blur-amount="5" data-animation-duration="0.8" data-pause-between="1.1">
                    <span class="focus-word">Toko</span>
                    <span class="focus-word">LEJ</span>
                    <div class="focus-frame" aria-hidden="true">
                        <span class="corner top-left"></span>
                        <span class="corner top-right"></span>
                        <span class="corner bottom-left"></span>
                        <span class="corner bottom-right"></span>
                    </div>
                </div>
                <p class="login-top-brand-sub">Sistem Penjualan & Prediksi Stok</p>
            </div>

            <div class="hidden md:flex absolute -top-5 -left-10 px-3 py-2 rounded-full border border-white/20 bg-cyan-400/10 backdrop-blur-xl text-[11px] font-semibold tracking-wide text-cyan-100 animate-floaty z-20">
                Fast POS Mode
            </div>
            <div class="hidden md:flex absolute -bottom-5 -right-8 px-3 py-2 rounded-full border border-fuchsia-300/30 bg-fuchsia-400/10 backdrop-blur-xl text-[11px] font-semibold tracking-wide text-fuchsia-100 animate-floatySlow z-20">
                Smart Stock Insight
            </div>

            <div class="login-card card-spotlight shadow-[0_24px_90px_rgba(4,6,32,0.65)] ring-1 ring-white/10 animate-pulseGlow" data-spotlight-color="rgba(0, 229, 255, 0.2)">
                <div class="login-logo">
                    <div class="reactbits-frame">
                        <div class="reactbits-title" data-reactbits-title="Login"></div>
                        <div class="reactbits-scanline"></div>
                        <div class="reactbits-particles" aria-hidden="true"></div>
                    </div>
                    <div class="login-logo-mark">LEJ</div>
                    <div class="login-logo-line"></div>
                    <p>Sistem Informasi Penjualan dan Prediksi Stok</p>
                </div>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= esc((string) session()->getFlashdata('error')) ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success" role="alert">
                        <?= esc((string) session()->getFlashdata('success')) ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('/login') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="input-wrap">
                        <svg class="input-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" /><circle cx="12" cy="7" r="4" /></svg>
                        <input type="text" class="form-inp" id="username" name="username" placeholder="Username" value="<?= esc((string) old('username')) ?>" autocomplete="off" required>
                    </div>

                    <div class="input-wrap">
                        <svg class="input-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" /><path d="M7 11V7a5 5 0 0110 0v4" /></svg>
                        <input type="password" class="form-inp" id="password" name="password" placeholder="Password" required>
                    </div>

                    <button type="submit" class="btn-login transition duration-300 hover:scale-[1.01] active:scale-[0.99]">Masuk ke Sistem</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const initColorBends = (canvas, {
                rotation = 90,
                autoRotate = 0,
                speed = 0.2,
                colors = ['#ff5c7a', '#8a5cff', '#00ffd1'],
                color = '#0c0ca6',
                transparent = true,
                scale = 1,
                frequency = 1,
                warpStrength = 1,
                mouseInfluence = 1,
                parallax = 0.5,
                noise = 0.15,
                iterations = 1,
                intensity = 1.5,
                bandWidth = 6
            } = {}) => {
                if (!canvas) {
                    return;
                }

                if (typeof THREE === 'undefined') {
                    return;
                }

                const MAX_COLORS = 8;
                const vert = `
varying vec2 vUv;
void main() {
  vUv = uv;
  gl_Position = vec4(position, 1.0);
}
`;

                const frag = `
#define MAX_COLORS ${MAX_COLORS}
uniform vec2 uCanvas;
uniform float uTime;
uniform float uSpeed;
uniform vec2 uRot;
uniform int uColorCount;
uniform vec3 uColors[MAX_COLORS];
uniform int uTransparent;
uniform float uScale;
uniform float uFrequency;
uniform float uWarpStrength;
uniform vec2 uPointer;
uniform float uMouseInfluence;
uniform float uParallax;
uniform float uNoise;
uniform int uIterations;
uniform float uIntensity;
uniform float uBandWidth;
varying vec2 vUv;

void main() {
  float t = uTime * uSpeed;
  vec2 p = vUv * 2.0 - 1.0;
  p += uPointer * uParallax * 0.1;
  vec2 rp = vec2(p.x * uRot.x - p.y * uRot.y, p.x * uRot.y + p.y * uRot.x);
  vec2 q = vec2(rp.x * (uCanvas.x / uCanvas.y), rp.y);
  q /= max(uScale, 0.0001);
  q /= 0.5 + 0.2 * dot(q, q);
  q += 0.2 * cos(t) - 7.56;
  vec2 toward = (uPointer - rp);
  q += toward * uMouseInfluence * 0.2;

  for (int j = 0; j < 5; j++) {
    if (j >= uIterations - 1) break;
    vec2 rr = sin(1.5 * (q.yx * uFrequency) + 2.0 * cos(q * uFrequency));
    q += (rr - q) * 0.15;
  }

  vec3 col = vec3(0.0);
  float a = 1.0;

  if (uColorCount > 0) {
    vec2 s = q;
    vec3 sumCol = vec3(0.0);
    float cover = 0.0;
    for (int i = 0; i < MAX_COLORS; ++i) {
      if (i >= uColorCount) break;
      s -= 0.01;
      vec2 r = sin(1.5 * (s.yx * uFrequency) + 2.0 * cos(s * uFrequency));
      float m0 = length(r + sin(5.0 * r.y * uFrequency - 3.0 * t + float(i)) / 4.0);
      float kBelow = clamp(uWarpStrength, 0.0, 1.0);
      float kMix = pow(kBelow, 0.3);
      float gain = 1.0 + max(uWarpStrength - 1.0, 0.0);
      vec2 disp = (r - s) * kBelow;
      vec2 warped = s + disp * gain;
      float m1 = length(warped + sin(5.0 * warped.y * uFrequency - 3.0 * t + float(i)) / 4.0);
      float m = mix(m0, m1, kMix);
      float w = 1.0 - exp(-uBandWidth / exp(uBandWidth * m));
      sumCol += uColors[i] * w;
      cover = max(cover, w);
    }
    col = clamp(sumCol, 0.0, 1.0);
    a = uTransparent > 0 ? cover : 1.0;
  } else {
    vec2 s = q;
    for (int k = 0; k < 3; ++k) {
      s -= 0.01;
      vec2 r = sin(1.5 * (s.yx * uFrequency) + 2.0 * cos(s * uFrequency));
      float m0 = length(r + sin(5.0 * r.y * uFrequency - 3.0 * t + float(k)) / 4.0);
      float kBelow = clamp(uWarpStrength, 0.0, 1.0);
      float kMix = pow(kBelow, 0.3);
      float gain = 1.0 + max(uWarpStrength - 1.0, 0.0);
      vec2 disp = (r - s) * kBelow;
      vec2 warped = s + disp * gain;
      float m1 = length(warped + sin(5.0 * warped.y * uFrequency - 3.0 * t + float(k)) / 4.0);
      float m = mix(m0, m1, kMix);
      col[k] = 1.0 - exp(-uBandWidth / exp(uBandWidth * m));
    }
    a = uTransparent > 0 ? max(max(col.r, col.g), col.b) : 1.0;
  }

  col *= uIntensity;

  if (uNoise > 0.0001) {
    float n = fract(sin(dot(gl_FragCoord.xy + vec2(uTime), vec2(12.9898, 78.233))) * 43758.5453123);
    col += (n - 0.5) * uNoise;
    col = clamp(col, 0.0, 1.0);
  }

  vec3 rgb = (uTransparent > 0) ? col * a : col;
  gl_FragColor = vec4(rgb, a);
}
`;

                const container = canvas.parentElement;
                if (!container) {
                    return;
                }

                try {
                const scene = new THREE.Scene();
                const camera = new THREE.OrthographicCamera(-1, 1, 1, -1, 0, 1);
                const geometry = new THREE.PlaneGeometry(2, 2);

                const uColorsArray = Array.from({
                    length: MAX_COLORS
                }, () => new THREE.Vector3(0, 0, 0));

                const material = new THREE.ShaderMaterial({
                    vertexShader: vert,
                    fragmentShader: frag,
                    uniforms: {
                        uCanvas: { value: new THREE.Vector2(1, 1) },
                        uTime: { value: 0 },
                        uSpeed: { value: speed },
                        uRot: { value: new THREE.Vector2(1, 0) },
                        uColorCount: { value: 0 },
                        uColors: { value: uColorsArray },
                        uTransparent: { value: transparent ? 1 : 0 },
                        uScale: { value: scale },
                        uFrequency: { value: frequency },
                        uWarpStrength: { value: warpStrength },
                        uPointer: { value: new THREE.Vector2(0, 0) },
                        uMouseInfluence: { value: mouseInfluence },
                        uParallax: { value: parallax },
                        uNoise: { value: noise },
                        uIterations: { value: Math.max(1, Math.min(5, Math.floor(iterations))) },
                        uIntensity: { value: intensity },
                        uBandWidth: { value: bandWidth }
                    },
                    transparent: true,
                    premultipliedAlpha: true
                });

                const mesh = new THREE.Mesh(geometry, material);
                scene.add(mesh);

                const renderer = new THREE.WebGLRenderer({
                    canvas,
                    antialias: false,
                    powerPreference: 'high-performance',
                    alpha: true
                });

                renderer.outputColorSpace = THREE.SRGBColorSpace;
                renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));
                const clearColor = new THREE.Color(color || '#0c0ca6');
                renderer.setClearColor(clearColor, transparent ? 0 : 1);

                const pointerTarget = new THREE.Vector2(0, 0);
                const pointerCurrent = new THREE.Vector2(0, 0);
                let rafId = 0;
                const clock = new THREE.Clock();

                const toVec3 = (hex) => {
                    const h = (hex || '').replace('#', '').trim();
                    const values = h.length === 3
                        ? [parseInt(h[0] + h[0], 16), parseInt(h[1] + h[1], 16), parseInt(h[2] + h[2], 16)]
                        : [parseInt(h.slice(0, 2) || '00', 16), parseInt(h.slice(2, 4) || '00', 16), parseInt(h.slice(4, 6) || '00', 16)];
                    return new THREE.Vector3(values[0] / 255, values[1] / 255, values[2] / 255);
                };

                const palette = (colors || []).filter(Boolean).slice(0, MAX_COLORS).map(toVec3);
                for (let i = 0; i < MAX_COLORS; i++) {
                    const vec = material.uniforms.uColors.value[i];
                    if (i < palette.length) {
                        vec.copy(palette[i]);
                    } else {
                        vec.set(0, 0, 0);
                    }
                }
                material.uniforms.uColorCount.value = palette.length;

                const resize = () => {
                    const w = container.clientWidth || 1;
                    const h = container.clientHeight || 1;
                    renderer.setSize(w, h, false);
                    material.uniforms.uCanvas.value.set(w, h);
                };

                const onPointerMove = (event) => {
                    const rect = container.getBoundingClientRect();
                    const x = ((event.clientX - rect.left) / (rect.width || 1)) * 2 - 1;
                    const y = -(((event.clientY - rect.top) / (rect.height || 1)) * 2 - 1);
                    pointerTarget.set(x, y);
                };

                const loop = () => {
                    const dt = clock.getDelta();
                    const elapsed = clock.elapsedTime;
                    material.uniforms.uTime.value = elapsed;

                    const deg = (rotation % 360) + autoRotate * elapsed;
                    const rad = (deg * Math.PI) / 180;
                    material.uniforms.uRot.value.set(Math.cos(rad), Math.sin(rad));

                    pointerCurrent.lerp(pointerTarget, Math.min(1, dt * 8));
                    material.uniforms.uPointer.value.copy(pointerCurrent);

                    renderer.render(scene, camera);
                    rafId = requestAnimationFrame(loop);
                };

                const ro = 'ResizeObserver' in window ? new ResizeObserver(resize) : null;
                if (ro) {
                    ro.observe(container);
                } else {
                    window.addEventListener('resize', resize);
                }

                container.addEventListener('pointermove', onPointerMove, { passive: true });
                resize();
                rafId = requestAnimationFrame(loop);

                return () => {
                    cancelAnimationFrame(rafId);
                    container.removeEventListener('pointermove', onPointerMove);
                    if (ro) {
                        ro.disconnect();
                    } else {
                        window.removeEventListener('resize', resize);
                    }
                    geometry.dispose();
                    material.dispose();
                    renderer.dispose();
                    renderer.forceContextLoss();
                };
                } catch (error) {
                    return;
                }
            };

            initColorBends(document.getElementById('colorBendsBg'), {
                colors: ['#ff5c7a', '#8a5cff', '#00ffd1'],
                rotation: 90,
                speed: 0.2,
                scale: 1,
                frequency: 1,
                warpStrength: 1,
                mouseInfluence: 1,
                noise: 0.15,
                parallax: 0.5,
                iterations: 1,
                intensity: 1.5,
                bandWidth: 6,
                transparent: true,
                autoRotate: 0,
                color: '#0c0ca6'
            });

            const titleNodes = document.querySelectorAll('[data-reactbits-title]');

            const createTitleGhostAndParticles = (node, text) => {
                const frame = node.closest('.reactbits-frame');
                if (!frame) {
                    return;
                }

                frame.querySelectorAll('.reactbits-ghost').forEach((el) => el.remove());

                const ghostA = document.createElement('div');
                ghostA.className = 'reactbits-title reactbits-ghost reactbits-ghost-a';
                ghostA.setAttribute('aria-hidden', 'true');
                ghostA.textContent = text;

                const ghostB = document.createElement('div');
                ghostB.className = 'reactbits-title reactbits-ghost reactbits-ghost-b';
                ghostB.setAttribute('aria-hidden', 'true');
                ghostB.textContent = text;

                frame.appendChild(ghostA);
                frame.appendChild(ghostB);

                const particles = frame.querySelector('.reactbits-particles');
                if (!particles || particles.children.length > 0) {
                    return;
                }

                const total = 14;
                for (let i = 0; i < total; i++) {
                    const dot = document.createElement('span');
                    dot.className = 'reactbits-particle';
                    dot.style.left = `${Math.random() * 100}%`;
                    dot.style.top = `${Math.random() * 100}%`;
                    dot.style.setProperty('--p-delay', `${(Math.random() * 2.2).toFixed(2)}s`);
                    dot.style.setProperty('--p-dur', `${(2.3 + Math.random() * 2.4).toFixed(2)}s`);
                    dot.style.setProperty('--p-x', `${(-10 + Math.random() * 20).toFixed(1)}px`);
                    dot.style.setProperty('--p-y', `${(-12 + Math.random() * 18).toFixed(1)}px`);
                    particles.appendChild(dot);
                }
            };

            const initShuffleTitle = (node, options = {}) => {
                if (!node) {
                    return;
                }

                const config = {
                    shuffleDirection: options.shuffleDirection || 'right',
                    duration: options.duration || 0.35,
                    animationMode: options.animationMode || 'evenodd',
                    shuffleTimes: Number.isFinite(options.shuffleTimes) ? options.shuffleTimes : 1,
                    ease: options.ease || 'power3.out',
                    stagger: Number.isFinite(options.stagger) ? options.stagger : 0.03,
                    threshold: Number.isFinite(options.threshold) ? options.threshold : 0.1,
                    triggerOnce: options.triggerOnce !== false,
                    triggerOnHover: options.triggerOnHover !== false,
                    respectReducedMotion: options.respectReducedMotion !== false,
                    scrambleCharset: options.scrambleCharset || 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%',
                    colorFrom: options.colorFrom || '#d4f2ff',
                    colorTo: options.colorTo || '#ffffff'
                };

                const text = node.getAttribute('data-reactbits-title') || node.textContent || '';
                const preferReduced = config.respectReducedMotion && window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

                node.classList.add('shuffle-parent');

                const isVertical = config.shuffleDirection === 'up' || config.shuffleDirection === 'down';
                let timeline = null;
                let observer = null;
                let isPlaying = false;
                let hasPlayed = false;

                const measureChar = (char) => {
                    if (char === ' ') {
                        return 10;
                    }

                    const probe = document.createElement('span');
                    probe.textContent = char;
                    probe.style.position = 'absolute';
                    probe.style.visibility = 'hidden';
                    probe.style.whiteSpace = 'pre';
                    probe.style.font = window.getComputedStyle(node).font;
                    document.body.appendChild(probe);
                    const width = probe.getBoundingClientRect().width;
                    document.body.removeChild(probe);
                    return Math.max(width, 8);
                };

                const randomChar = () => {
                    const set = config.scrambleCharset;
                    return set.charAt(Math.floor(Math.random() * set.length)) || 'X';
                };

                const build = () => {
                    if (timeline) {
                        timeline.kill();
                        timeline = null;
                    }

                    node.innerHTML = '';

                    const chars = text.split('');
                    chars.forEach((char) => {
                        const wrapper = document.createElement('span');
                        wrapper.className = 'shuffle-char-wrapper';

                        const strip = document.createElement('span');
                        strip.className = 'shuffle-strip';

                        const width = measureChar(char);
                        const height = node.getBoundingClientRect().height || 24;
                        wrapper.style.width = `${width}px`;
                        wrapper.style.height = isVertical ? `${height}px` : 'auto';

                        const createGlyph = (glyph) => {
                            const cell = document.createElement('span');
                            cell.className = 'shuffle-char';
                            cell.textContent = glyph === ' ' ? '\u00A0' : glyph;
                            cell.style.width = `${width}px`;
                            if (isVertical) {
                                cell.style.display = 'block';
                            }
                            return cell;
                        };

                        const rolls = Math.max(1, Math.floor(config.shuffleTimes));
                        strip.appendChild(createGlyph(char));

                        for (let i = 0; i < rolls; i++) {
                            strip.appendChild(createGlyph(config.scrambleCharset ? randomChar() : char));
                        }

                        const finalGlyph = createGlyph(char);
                        finalGlyph.setAttribute('data-orig', '1');
                        strip.appendChild(finalGlyph);

                        if (config.shuffleDirection === 'right' || config.shuffleDirection === 'down') {
                            const firstCell = strip.firstElementChild;
                            const realCell = strip.lastElementChild;
                            if (realCell) {
                                strip.insertBefore(realCell, strip.firstElementChild);
                            }
                            if (firstCell) {
                                strip.appendChild(firstCell);
                            }
                        }

                        const steps = rolls + 1;
                        let startX = 0;
                        let finalX = 0;
                        let startY = 0;
                        let finalY = 0;

                        if (config.shuffleDirection === 'right') {
                            startX = -steps * width;
                            finalX = 0;
                        } else if (config.shuffleDirection === 'left') {
                            startX = 0;
                            finalX = -steps * width;
                        } else if (config.shuffleDirection === 'down') {
                            startY = -steps * height;
                            finalY = 0;
                        } else if (config.shuffleDirection === 'up') {
                            startY = 0;
                            finalY = -steps * height;
                        }

                        if (isVertical) {
                            gsap.set(strip, {
                                x: 0,
                                y: startY,
                                force3D: true
                            });
                            strip.setAttribute('data-final-y', String(finalY));
                        } else {
                            gsap.set(strip, {
                                x: startX,
                                y: 0,
                                force3D: true
                            });
                            strip.setAttribute('data-final-x', String(finalX));
                        }

                        if (config.colorFrom) {
                            strip.style.color = config.colorFrom;
                        }

                        wrapper.appendChild(strip);
                        node.appendChild(wrapper);
                    });

                    node.classList.add('is-ready');
                };

                const play = () => {
                    const strips = Array.from(node.querySelectorAll('.shuffle-strip'));
                    if (!strips.length) {
                        return;
                    }

                    isPlaying = true;
                    timeline = gsap.timeline({
                        onComplete: () => {
                            isPlaying = false;
                            hasPlayed = true;
                        }
                    });

                    const addTween = (targets, startAt) => {
                        const vars = {
                            duration: config.duration,
                            ease: config.ease,
                            force3D: true,
                            stagger: config.animationMode === 'evenodd' ? config.stagger : 0
                        };

                        if (isVertical) {
                            vars.y = (index, target) => parseFloat(target.getAttribute('data-final-y') || '0');
                        } else {
                            vars.x = (index, target) => parseFloat(target.getAttribute('data-final-x') || '0');
                        }

                        timeline.to(targets, vars, startAt);

                        if (config.colorFrom && config.colorTo) {
                            timeline.to(targets, {
                                color: config.colorTo,
                                duration: config.duration,
                                ease: config.ease
                            }, startAt);
                        }
                    };

                    if (config.animationMode === 'evenodd') {
                        const odd = strips.filter((_, i) => i % 2 === 1);
                        const even = strips.filter((_, i) => i % 2 === 0);
                        const oddTotal = config.duration + Math.max(0, odd.length - 1) * config.stagger;
                        const evenStart = odd.length ? oddTotal * 0.7 : 0;
                        if (odd.length) {
                            addTween(odd, 0);
                        }
                        if (even.length) {
                            addTween(even, evenStart);
                        }
                    } else {
                        strips.forEach((strip) => {
                            const delay = Math.random() * 0.14;
                            addTween(strip, delay);
                        });
                    }
                };

                const runAnimation = () => {
                    build();
                    play();
                    createTitleGhostAndParticles(node, text);
                };

                if (preferReduced) {
                    node.textContent = text;
                    node.classList.add('is-ready');
                    createTitleGhostAndParticles(node, text);
                } else {
                    if ('IntersectionObserver' in window) {
                        observer = new IntersectionObserver((entries) => {
                            entries.forEach((entry) => {
                                if (entry.isIntersecting && (!hasPlayed || !config.triggerOnce)) {
                                    runAnimation();
                                    if (config.triggerOnce && observer) {
                                        observer.disconnect();
                                    }
                                }
                            });
                        }, {
                            threshold: config.threshold
                        });

                        observer.observe(node);
                    } else {
                        runAnimation();
                    }
                }

                if (config.triggerOnHover) {
                    node.addEventListener('mouseenter', () => {
                        if (preferReduced || isPlaying || (!hasPlayed && config.triggerOnce)) {
                            return;
                        }
                        runAnimation();
                    });
                }
            };

            titleNodes.forEach((node) => {
                initShuffleTitle(node, {
                    shuffleDirection: 'right',
                    duration: 0.35,
                    animationMode: 'evenodd',
                    shuffleTimes: 1,
                    ease: 'power3.out',
                    stagger: 0.03,
                    threshold: 0.1,
                    triggerOnce: true,
                    triggerOnHover: true,
                    respectReducedMotion: true
                });
            });

            const spotlightCards = document.querySelectorAll('.card-spotlight');
            spotlightCards.forEach((card) => {
                const spotlightColor = card.getAttribute('data-spotlight-color') || 'rgba(255, 255, 255, 0.25)';
                card.style.setProperty('--spotlight-color', spotlightColor);

                const handleMouseMove = (event) => {
                    const rect = card.getBoundingClientRect();
                    const x = event.clientX - rect.left;
                    const y = event.clientY - rect.top;
                    card.style.setProperty('--mouse-x', `${x}px`);
                    card.style.setProperty('--mouse-y', `${y}px`);
                };

                const handleLeave = () => {
                    card.style.setProperty('--mouse-x', '50%');
                    card.style.setProperty('--mouse-y', '50%');
                };

                card.addEventListener('mousemove', handleMouseMove);
                card.addEventListener('mouseleave', handleLeave);
            });

            const initTrueFocus = (container) => {
                const words = Array.from(container.querySelectorAll('.focus-word'));
                const frame = container.querySelector('.focus-frame');

                if (!words.length || !frame) {
                    return;
                }

                const manualMode = container.getAttribute('data-manual-mode') === 'true';
                const blurAmount = Number(container.getAttribute('data-blur-amount') || 5);
                const animationDuration = Number(container.getAttribute('data-animation-duration') || 0.5);
                const pauseBetween = Number(container.getAttribute('data-pause-between') || 1);

                let currentIndex = 0;
                let lastActiveIndex = 0;

                const updateWordStyles = () => {
                    words.forEach((word, index) => {
                        const isActive = index === currentIndex;
                        word.style.filter = isActive ? 'blur(0px)' : `blur(${blurAmount}px)`;
                        word.style.transition = `filter ${animationDuration}s ease`;
                    });
                };

                const updateFrame = () => {
                    const activeWord = words[currentIndex];
                    if (!activeWord) {
                        return;
                    }

                    const parentRect = container.getBoundingClientRect();
                    const activeRect = activeWord.getBoundingClientRect();
                    const x = activeRect.left - parentRect.left;
                    const y = activeRect.top - parentRect.top;

                    frame.style.transform = `translate(${x}px, ${y}px)`;
                    frame.style.width = `${activeRect.width}px`;
                    frame.style.height = `${activeRect.height}px`;
                    frame.style.opacity = '1';
                    frame.style.transitionDuration = `${animationDuration}s`;
                };

                const setActive = (index) => {
                    currentIndex = index;
                    updateWordStyles();
                    updateFrame();
                };

                setActive(0);

                if (!manualMode && words.length > 1) {
                    const intervalMs = (animationDuration + pauseBetween) * 1000;
                    window.setInterval(() => {
                        setActive((currentIndex + 1) % words.length);
                    }, intervalMs);
                }

                if (manualMode) {
                    words.forEach((word, index) => {
                        word.addEventListener('mouseenter', () => {
                            lastActiveIndex = index;
                            setActive(index);
                        });

                        word.addEventListener('mouseleave', () => {
                            setActive(lastActiveIndex);
                        });
                    });
                }

                window.addEventListener('resize', updateFrame);
            };

            document.querySelectorAll('[data-truefocus]').forEach((el) => {
                initTrueFocus(el);
            });
        })();
    </script>
</body>

</html>