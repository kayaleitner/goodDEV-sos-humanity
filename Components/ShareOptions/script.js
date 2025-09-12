function initShareOptions(el) {
  const copyBtn = el.querySelector('.share-options__button--copy')
  if (copyBtn) {
    const labelEl = copyBtn.querySelector('.share-options__button__label')

    let revertTimer = null

    copyBtn.addEventListener('click', async (e) => {
      e.preventDefault()
      const url = copyBtn.getAttribute('data-share-url') || window.location.href

      const copied = await copyToClipboard(url)

      if (copied) {
        copyBtn.classList.add('is-copied')
        const copiedText = copyBtn.getAttribute('data-label-copied') || 'Copied'
        copyBtn.setAttribute('data-copy-button-text', copiedText)
        if (labelEl) {
          labelEl.setAttribute('aria-live', 'polite')
          labelEl.setAttribute('aria-atomic', 'true')
        }
      }

      clearTimeout(revertTimer)
      revertTimer = setTimeout(() => {
        copyBtn.classList.remove('is-copied')
      }, 2000)
    })
  }
}

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
    return false
  }
}

function ready(fn) {
  if (document.readyState !== 'loading') {
    fn()
  } else {
    document.addEventListener('DOMContentLoaded', fn)
  }
}

ready(() => {
  document.querySelectorAll('[is="flynt-share-options"]').forEach(initShareOptions)
})
