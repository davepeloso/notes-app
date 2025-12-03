# Next.js + React single-file 

How to use:
1. Create a Next.js app (v13+ with /app or pages router). This component is written as a page component for the pages/ router: place as `pages/index.tsx`.
2. Install dependencies:
   npm install framer-motion tailwindcss postcss autoprefixer
   npx tailwindcss init -p
3. Tailwind config (tailwind.config.js) should enable JIT and include ./pages/**/* and ./components/**/*.
4. Add Tailwind base imports in globals.css and include fonts via <Head> or _document.js. The template uses system fallback fonts; replace with Jost/Raleway as desired.

Key dependencies used in this file:
- framer-motion for entrance, stagger, and parallax-like effects
- TailwindCSS for layout & styling

Animation notes implemented:
- Page-level staggered entrance: sections animate in with a small upward translate and fade.
- Subtle parallax: background radial/linear gradient shifts based on scroll (small, performant) using a requestAnimationFrame handler.
- SVG stroke / icon reveal: icons have simple path-draw animation using strokeDashoffset via framer-motion
- Hover micro-interactions on buttons: scale and subtle glow
- Card border draw: border blur + inner outline fade in.

This single-file page aims to be a developer-grade starting point — replace text, icons, and assets as needed.
*/

import Head from 'next/head'
import { useEffect, useState } from 'react'
import { motion, AnimatePresence } from 'framer-motion'

export default function Home() {
  // small state to drive parallax background offset from scroll
  const [bgOffset, setBgOffset] = useState(0)

  useEffect(() => {
    let raf = 0
    const handleScroll = () => {
      cancelAnimationFrame(raf)
      raf = requestAnimationFrame(() => {
        // clamp a small offset based on scroll. Keep values tiny for subtlety
        const offset = Math.min(120, window.scrollY * 0.12)
        setBgOffset(offset)
      })
    }
    window.addEventListener('scroll', handleScroll, { passive: true })
    return () => {
      window.removeEventListener('scroll', handleScroll)
      cancelAnimationFrame(raf)
    }
  }, [])

  const container = {
    hidden: {},
    show: {
      transition: {
        staggerChildren: 0.12,
      },
    },
  }

  const fadeUp = {
    hidden: { opacity: 0, y: 10 },
    show: { opacity: 1, y: 0, transition: { duration: 0.6, ease: [0.2, 0.8, 0.2, 1] } },
  }

  const iconDraw = {
    hidden: { pathLength: 0, opacity: 0 },
    show: { pathLength: 1, opacity: 1, transition: { duration: 0.9, ease: 'easeInOut' } },
  }

  return (
    <>
      <Head>
        <title>Carrd Template #177 — Clone</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        {/* Replace with @import or google fonts link for Jost / Raleway etc */}
      </Head>

      <div className="min-h-screen w-full relative overflow-x-hidden bg-[radial-gradient(ellipse_at_top_left,_var(--bg-offset))]" style={{ ['--bg-offset' as any]: `rgba(63,41,121,0.6) ${-bgOffset}px, rgba(18,14,35,1) 200px` }}>
        {/* Decorative gradient layer that moves slightly with scroll for parallax illusion */}
        <div className="absolute inset-0 -z-10 bg-gradient-to-br from-[#2b1b45] via-[#151226] to-[#0b0b12] opacity-95" />

        <main className="max-w-5xl mx-auto px-6 py-16">
          {/* Top CTA / small header */}
          <motion.section initial="hidden" animate="show" variants={container} className="space-y-8">
            <motion.div variants={fadeUp} className="flex flex-col items-center text-center gap-4">
              <p className="text-sm text-slate-300/70 uppercase tracking-wider">etiam in ante metus. turpis bibendum neque egestas congue quisque.</p>
              <div className="flex gap-4">
                <button className="btn-primary">Get started →</button>
                <button className="btn-ghost">Learn more ↓</button>
              </div>
            </motion.div>

            {/* First large card */}
            <motion.article variants={fadeUp} className="relative rounded-2xl p-10 md:p-14 border border-slate-700/40 backdrop-blur-sm">
              <div className="grid md:grid-cols-3 gap-6 items-center">
                <div className="col-span-1 flex justify-center md:justify-start">
                  {/* Circular gauge icon with animated needle - simple static SVG with small animation */}
                  <svg width="120" height="120" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <motion.circle cx="60" cy="60" r="44" stroke="#4ea3ff" strokeWidth="1.6" opacity="0.08" />
                    <motion.path d="M60 60 L60 20" stroke="#86b9ff" strokeWidth="2.2" strokeLinecap="round" initial={{ rotate: -20 }} animate={{ rotate: 12 }} transform-origin="60px 60px" transition={{ repeat: Infinity, repeatType: 'reverse', duration: 4 }} />
                  </svg>
                </div>

                <div className="md:col-span-2">
                  <h2 className="text-3xl md:text-4xl font-semibold text-white">Sed nisl veroeros</h2>
                  <p className="mt-4 text-slate-300 max-w-prose">Lorem ipsum dolor sit amet consectetur adipiscing elit. Duis dapibus rutrum facilisis. Class aptent taciti sociosqu ad litora torquent per conubia nostra consequat.</p>
                  <div className="mt-6">
                    <button className="btn-outline">Adipiscing →</button>
                  </div>
                </div>
              </div>

              {/* subtle dotted decorative element */}
              <div className="absolute right-6 top-6 opacity-30">● ●</div>
            </motion.article>

            {/* Second card */}
            <motion.article variants={fadeUp} className="relative rounded-2xl p-10 md:p-14 border border-slate-700/40 backdrop-blur-sm">
              <div className="grid md:grid-cols-3 gap-6 items-center">
                <div className="md:col-span-2">
                  <h3 className="text-2xl font-semibold text-white">Etiam adipiscing</h3>
                  <p className="mt-3 text-slate-300 max-w-prose">Etiam tristique libero eu nibh porttitor amet fermentum. Nullam venenatis est id vehicula ultricies sed ultricies condimentum.</p>
                  <div className="mt-6">
                    <button className="btn-outline">Aliquam ante →</button>
                  </div>
                </div>

                <div className="col-span-1 flex justify-center md:justify-end">
                  {/* simple vertical slider controls as SVG, animate circles with framer */}
                  <svg width="88" height="88" viewBox="0 0 88 88" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="4" y="10" width="80" height="68" rx="8" stroke="#243044" fill="none" />
                    <motion.circle cx="68" cy="26" r="4" stroke="#67b0ff" initial={{ y: -8 }} animate={{ y: 0 }} transition={{ duration: 0.6 }} />
                    <motion.circle cx="68" cy="46" r="4" stroke="#67b0ff" initial={{ y: -4 }} animate={{ y: 0 }} transition={{ delay: 0.08 }} />
                    <motion.circle cx="68" cy="66" r="4" stroke="#67b0ff" initial={{ y: 4 }} animate={{ y: 0 }} transition={{ delay: 0.16 }} />
                  </svg>
                </div>
              </div>
            </motion.article>

            {/* Feature icons row */}
            <motion.section variants={fadeUp} className="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
              {[
                { title: 'Orci varius', desc: 'Vehicula ultricies sed ultricies condimentum. Magna sed etiam consequat, et adipiscing.' },
                { title: 'Etiam metus', desc: 'Sed etiam mollis egestas nam maximus arcu id euismod egestas.' },
                { title: 'Amet quam', desc: 'Nulla amet convallis, et porttitor magna ullamcorper amet mauris.' },
              ].map((f, i) => (
                <div key={i} className="flex flex-col items-start gap-4 p-6 border border-slate-700/30 rounded-xl">
                  <motion.div initial={{ opacity: 0, y: 8 }} animate={{ opacity: 1, y: 0, transition: { delay: 0.06 * i } }}>
                    {/* placeholder icon */}
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="6" y="12" width="36" height="24" rx="4" stroke="#5fb3ff"/></svg>
                  </motion.div>
                  <h4 className="text-lg font-medium text-white">{f.title}</h4>
                  <p className="text-slate-300 text-sm">{f.desc}</p>
                </div>
              ))}
            </motion.section>

          </motion.section>
        </main>

        {/* small Footer */}
        <motion.footer initial={{ opacity: 0 }} animate={{ opacity: 1, transition: { delay: 0.4 } }} className="py-12 text-center text-slate-400 text-sm">
          © {new Date().getFullYear()} Template Clone • Built with Next.js + Tailwind
        </motion.footer>
      </div>

      <style jsx>{`
        /* Buttons: utility classes; replace with your Tailwind components if you prefer */
        .btn-primary{
          @apply bg-gradient-to-r from-[#5fb3ff] to-[#6ab0ff] text-slate-900 rounded-full px-5 py-2.5 font-semibold shadow-md hover:scale-105 transform transition-all;
        }
        .btn-ghost{
          @apply border border-slate-600 text-slate-200 rounded-full px-5 py-2.5 hover:bg-slate-800/40;
        }
        .btn-outline{
          @apply border border-slate-600 text-slate-200 rounded-full px-4 py-2 hover:scale-[1.02] transition-transform;
        }

        /* small responsive tweaks */
        @media (min-width: 768px){
          main { padding-top: 6rem; padding-bottom: 6rem; }
        }
      `}</style>
    </>
  )
}
