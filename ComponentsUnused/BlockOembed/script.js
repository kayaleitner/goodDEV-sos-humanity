import { buildRefs } from '@/assets/scripts/helpers'

export default function (el) {
  const refs = buildRefs(el, false, {
    iframe: 'iframe',
  })

  if (refs.playButton)
    refs.playButton.addEventListener('click', loadEmbed, { once: true })

  if (refs.embed.dataset.videomode === 'off') loadEmbed()

  function loadEmbed() {
    if (refs.playButton) {
      refs.playIcon.classList.add('hidden')
      refs.loadingSpinner.style.opacity = 1
    }
    refs.iframe.addEventListener('load', afterLoad, { once: true })
    refs.iframe.setAttribute('src', refs.iframe.getAttribute('data-src'))
  }

  function afterLoad() {
    refs.poster.classList.add('hidden')
  }
}
