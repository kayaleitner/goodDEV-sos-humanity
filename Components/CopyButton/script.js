export default function (component) {
  const root = component instanceof Element ? component : document
  const buttons = root.querySelectorAll('.copyButton')
  if (!buttons.length) return

  buttons.forEach((btn) => {
    const labelEl = btn.querySelector('.copyButton__label')

    let revertTimer = null

    btn.addEventListener('click', async (e) => {
      e.preventDefault()
      const text = btn.getAttribute('data-clipboard-text') || ''
      if (!text) return

      const copied = await copyToClipboard(text)

      if (copied) {
        // Toggle class so CSS can fade icons and use currentColor
        btn.classList.add('is-copied')
        // Optional: brief accessible feedback using aria-live on the label
        const copiedText = btn.getAttribute('data-copied-text') || 'Copied'
        // Expose text for optional tooltip styles via CSS (uses :after content)
        btn.setAttribute('data-copy-button-text', copiedText)
        if (labelEl) {
          labelEl.setAttribute('aria-live', 'polite')
          labelEl.setAttribute('aria-atomic', 'true')
        }
      }

      clearTimeout(revertTimer)
      revertTimer = setTimeout(() => {
        btn.classList.remove('is-copied')
      }, 2000)
    })
  })

  async function copyToClipboard(text) {
    try {
      if (navigator.clipboard && typeof navigator.clipboard.writeText === 'function') {
        await navigator.clipboard.writeText(text)
        return true
      }
    } catch (e) {
      // ignore and try fallback
    }

    // Fallback for older browsers
    try {
      const ta = document.createElement('textarea')
      ta.value = text
      ta.setAttribute('readonly', '')
      ta.style.position = 'fixed'
      ta.style.top = '-9999px'
      document.body.appendChild(ta)
      ta.select()
      document.execCommand('copy')
      document.body.removeChild(ta)
      return true
    } catch (e) {
      // failed
      return false
    }
  }
}
