import $ from 'jquery'

class CtaDonate extends window.HTMLElement {
  constructor (...args) {
    const self = super(...args)
    self.init()
    return self
  }

  init () {
    this.$ = $(this)
    this.resolveElements()
    this.bindFunctions()
    this.bindEvents()
  }

  resolveElements () {
    this.$button = $('#donate', this)
  }

  bindFunctions () {
    this.customDonate = this.customDonate.bind(this)
  }

  bindEvents () {
    this.$.on('click', '#donate', this.customDonate)
  }

  connectedCallback () {}

  customDonate (e) {
    var ctaLink = this.$button[0].getAttribute('data-cta-link')
    var linkOrFallback = ctaLink ? ctaLink + '?amount=' : '/spenden/?amount='
    var customdonation = linkOrFallback + document.getElementById('donation').value
    window.location.href = customdonation
  }
}

window.customElements.define('flynt-cta-donate', CtaDonate, {
  extends: 'div'
})
