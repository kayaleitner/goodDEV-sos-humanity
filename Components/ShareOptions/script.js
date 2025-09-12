function initShareOptions(el) {
  const copyBtn = el.querySelector('.share-options__button--copy')
  if (copyBtn) {
    copyBtn.addEventListener('click', async () => {
      const url = copyBtn.getAttribute('data-share-url') || window.location.href
      try {
        await navigator.clipboard.writeText(url)
        const prev = copyBtn.textContent
        const copied = copyBtn.getAttribute('data-label-copied') || 'Copied!'
        copyBtn.textContent = copied
        copyBtn.disabled = true
        setTimeout(() => {
          copyBtn.textContent = prev
          copyBtn.disabled = false
        }, 1500)
      } catch (e) {
        // Fallback
        const input = document.createElement('input')
        input.value = url
        document.body.appendChild(input)
        input.select()
        document.execCommand('copy')
        document.body.removeChild(input)
      }
    })
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
