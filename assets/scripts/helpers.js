export function buildRefs (el, multiple = false, customRefs = {}) {
  const QS = multiple ? 'querySelectorAll' : 'querySelector'
  const DA = multiple ? 'data-refs' : 'data-ref'
  return new Proxy(
    {},
    {
      get (target, prop) {
        if (!target[prop]) {
          const selector = customRefs[prop] ?? `[${DA}="${prop}"]`
          target[prop] = el[QS](selector)
          if (!target[prop]) {
            if (process.env.NODE_ENV !== 'production') {
              console.warn(`ref ${prop} not found.`)
            }
          }
        }
        return target[prop]
      }
    }
  )
}

export function getJSON (node, selector = 'script[type="application/json"]', property = 'textContent') {
  let data = {}
  try {
    data = JSON.parse(node.querySelector(selector)[property])
  } catch (e) { }
  return data
}

export function fadeElements(refs, faderType) {
  // Determine the active index based on pagination
  const realActiveIndex = Array.from(refs.pagination.children).indexOf(
    refs.pagination.querySelector('.swiper-pagination-bullet-active')
  )
  // Get the specified fader (either 'textFader' or 'ctaFader') and iterate over its children
  Array.from(refs[faderType].children).forEach((child, index) => {
    const isCurrentCta = parseInt(child.dataset.index) == realActiveIndex
    const isCurrentText = index === realActiveIndex
    const fadeCondition = faderType == 'ctaFader' ? isCurrentCta : isCurrentText

    if (fadeCondition) {
      // Apply active classes to the child with matching index
      showElement(child)
    } else {
      // Apply inactive classes to every other child
      hideElement(child)
    }
  })
}

export function showElement(child) {
  child.classList.add('opacity-100', 'z-10')
  child.classList.remove(
    'opacity-0',
    'z-0',
    'pointer-events-none',
    'cursor-default'
  )
}
export function hideElement(child) {
  child.classList.add(
    'opacity-0',
    'z-0',
    'pointer-events-none',
    'cursor-default'
  )
  child.classList.remove('opacity-100', 'z-10')
}