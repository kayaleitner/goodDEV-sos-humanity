import $ from 'jquery'

class ProgressDonation extends window.HTMLHtmlElement {
  constructor (...args) {
    const self = super(...args)
    self.init()
    return self
  }

  init () {
    this.resolveElements()
    this.bindFunctions()
    this.updateProgressHasRun = false
    this.bindEvents()
  }

  resolveElements () {
    this.$ship = $('.ship', this)   
    this.$level = $('.level', this)
    this.$bar = $('.bar', this)
  }

  bindFunctions () {
    this.updateProgress = this.updateProgress.bind(this)
    this.observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.intersectionRatio >= 0.5) {
                // Call the function when the div is in view
                this.updateProgress()
            }
        })
    }, { threshold: 0.5 })
    this.observer.observe(this)
  }

  bindEvents () {
    $(window).on('resize', this.updateProgress)
  }

  updateProgress () {

    if (this.updateProgressHasRun) {
      this.$ship.css('transition', 'unset')
      this.$level.css('transition', 'unset')
    }

    const isMobile = window.matchMedia('(max-width: 1024px)').matches
    
    const goal = this.dataset.goal
    const level = this.dataset.level

    const shipWidth = this.$ship.width()
    const barWidth = this.$bar.width()
    const levelWidth = this.$level.width()

    let progressShip
    let progressLevel

    if (isMobile) {
        progressShip = 24 + level / goal * (barWidth - shipWidth)
        progressLevel = progressShip + (shipWidth - levelWidth) / 2
    } else {
        progressShip = level / goal * (barWidth - shipWidth) 
        progressLevel = progressShip + (shipWidth - levelWidth) / 2
    }

    this.$ship.css('left', progressShip + 'px')
    this.$level.css('left', progressLevel + 'px')

    if (!this.updateProgressHasRun) {
      this.updateProgressHasRun = true
    }
  }
}

window.customElements.define('flynt-progress-donation', ProgressDonation, {
    extends: 'div'
})