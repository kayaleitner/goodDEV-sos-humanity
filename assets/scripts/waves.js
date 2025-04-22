import $ from 'jquery'

$(document).ready(function () {

  if ($('.infovisual-footer').length) {
    // background waves
    const waveWidth = 1920
    const waveHeight = 400

    class Wave {
      constructor (element, args) {
        this.element = element

        // Array for all x values
        this.xs = []
        for (var i = 0; i <= waveWidth; i++) {
          this.xs.push(i)
        }

        // Set initial delta_x
        this.delta_x = 0 + args.offset_x
        this.delta_x_step = args.delta_x_step

        this.delta_t_step = args.delta_t_step || 0.025

        this.delta_y_step = args.delta_y_step || 0.025

        if (args.is_last) {
          // t: The max and min value for the log() parameter of the wave amplitude
          this.t_max = 10
          this.t_min = 1

          // Set initial value and direction
          this.t = this.t_min + args.offset_t

          // increase t initially
          this.inc_t = true
          this.amplitude_is_positive = true

          // absolute maximum of the amplitude value calculated via log(t)
          // delta_t is the amplitude
          this.delta_t_max = 3
          this.delta_t = this.delta_t_max * Math.log(this.t)

          // Set parameters for movement in y-direction
          // y = [1..10] so that delta_y = [log(1) .. log(10) = 1]
          this.y_max = 1
          this.y_min = 1
          this.y = this.y_min
          this.inc_y = true
          this.y_move_is_positive = true
          this.delta_y_max = 0
          this.delta_y = this.delta_y_max * Math.log(this.y)
          this.offset_y = 100
        } else {
          // t: The max and min value for the log() parameter of the wave amplitude
          this.t_max = 10
          this.t_min = 1

          // Set initial value and direction
          this.t = this.t_min + args.offset_t

          // increase t initially
          this.inc_t = true
          this.amplitude_is_positive = true

          // absolute maximum of the amplitude value calculated via log(t)
          // delta_t is the amplitude
          this.delta_t_max = 5
          this.delta_t = this.delta_t_max * Math.log(this.t)

          // Set parameters for movement in y-direction
          // y = [1..10] so that delta_y = [log(1) .. log(10) = 1]
          this.y_max = 10
          this.y_min = 1
          this.y = this.y_min
          this.inc_y = true
          this.y_move_is_positive = true
          this.delta_y_max = 25
          this.delta_y = this.delta_y_max * Math.log(this.y)
          this.offset_y = args.offset_y
        }

        this.animate()
      }

      setDeltaXStep (newDeltaXstep) {
        this.delta_x_step = newDeltaXstep
      }

      setDeltaTStep (newDeltaTstep) {
        this.delta_t_step = newDeltaTstep
      }

      setDeltaYStep (newDeltaYstep) {
        this.delta_y_step = newDeltaYstep
      }

      setDeltaTMax (newDeltaTmax) {
        this.delta_t_max = newDeltaTmax
      }

      setOffsetY (newOffsetY) {
        this.offset_y = newOffsetY
      }

      animate () {
        const points = this.xs.map(x => {
          const y = (200 + this.offset_y + this.delta_y) + this.delta_t * Math.sin((x + this.delta_x) / 100)
          return [x, y]
        })

        let path = 'M' + points.map(p => {
          return p[0] + ',' + p[1]
        }).join(' L')

        path += ' L ' + waveWidth + ' ' + waveHeight + 'L 0 ' + waveHeight

        this.element.querySelector('path').setAttribute('d', path)

        if (this.amplitude_is_positive) {
          this.delta_t = this.delta_t_max * Math.log(this.t)
        } else {
          this.delta_t = -this.delta_t_max * Math.log(this.t)
        }

        if (this.inc_t) {
          this.t += this.delta_t_step
        } else {
          this.t -= this.delta_t_step
        }

        if (this.t >= this.t_max) {
          this.inc_t = false
        }
        if (this.t <= this.t_min) {
          this.inc_t = true
          this.amplitude_is_positive = !this.amplitude_is_positive
        }

        if (this.inc_y) {
          this.y += this.delta_y_step
        } else {
          this.y -= this.delta_y_step
        }

        if (this.y >= this.y_max) {
          this.inc_y = false
        }
        if (this.y <= this.y_min) {
          this.inc_y = true
          this.y_move_is_positive = !this.y_move_is_positive
        }
        if (this.y_move_is_positive) {
          this.delta_y = this.delta_y_max * Math.log(this.y)
        } else {
          this.delta_y = -this.delta_y_max * Math.log(this.y)
        }

        this.delta_x -= this.delta_x_step

        requestAnimationFrame(this.animate.bind(this))
      }
    }

    // Generate waves
    var waves = document.querySelectorAll('.wave-container svg')
    var waveObjs = []
    for (var i = 0; i < waves.length; i++) {
      let args = {}
      if (i < waves.length - 1) {
        args = {
          offset_x: i * 100,
          offset_t: i * 10,
          offset_y: Math.floor(Math.random() * 40) + 1,
          delta_x_step: 1
        }
      } else {
        args = {
          offset_x: i * 100,
          offset_t: i * 10,
          offset_y: 100,
          is_last: true,
          delta_x_step: 1.25
        }
      }
      const wave = new Wave(waves[i], args)
      waveObjs.push(wave)
    }

    // don't know why we are asking here for a variable that is never in use across the whole repo,
    // but I'm just throwing this in to avoid error messages

    if (typeof isHeader === 'undefined') {
      var isHeader = window.isHeader || false;  
    }

    if (isHeader) {
      // Get weather data
      const data = {
        action: 'sostheme_get_weatherdata',
        nonce: sostheme_ajax_obj.nonce
      }

      // Keep track of the form element
      const _this = $(this)

      $.ajax({
        type: 'POST',
        url: sostheme_ajax_obj.ajax_url,
        data: data,
        success: function (response) {
          const rsp = $.parseJSON(response)
          // console.log(rsp);

          if (rsp.error) {
            // Inform the user of the error
            $('.infovisual-header div.error').text('Sorry, there was an error retrieving the live data.').fadeIn()
          } else {
            // Display coordinates
            $('.coordinates a', _this).attr('href', rsp.location.link)
            $('.loader.coordinates', _this).replaceWith(rsp.location.lat + ', ' + rsp.location.lng)

            // Display last update
            $('.loader.update', _this).replaceWith(rsp.meta.update_day_string + ' ' + rsp.meta.update_time_string)

            // Display weather data
            $('.loader.airtemperature', _this).replaceWith(rsp.weather.air_temperature)
            $('.loader.windspeed', _this).replaceWith(rsp.weather.wind_speed_knots)
            $('.loader.cloudcover', _this).replaceWith(rsp.weather.cloudcover_string)
            $('.loader.watertemperature', _this).replaceWith(rsp.weather.water_temperature)
            $('.loader.waveheight', _this).replaceWith(rsp.weather.wave_height)
            $('.loader.swell', _this).replaceWith(rsp.weather.swell)

            // Set icons according to weather data:
            // Get the base url for images
            let iconsBaseurl = $('.weathericons img.windspeed_icon').attr('r')
            // console.log("baseurl with filename", iconsBaseurl);

            // Strip the filename
            iconsBaseurl = iconsBaseurl.substring(0, iconsBaseurl.lastIndexOf('/'))
            // console.log("baseurl", iconsBaseurl);

            /* Set wind icon:
                00: Windstille (0 kn)
                01: leichter Wind (1-11kn)
                02: mäßiger Wind (12-28kn)
                03: starker Wind (29-61kn)
                04: Sturm (62kn und mehr)
            */
            const windIconFilenameBase = 'icon_wind_'

            // Set "no wind" icon as default
            let windIconFilenameSuffix = '00.svg'
            if (rsp.weather.wind_speed_knots > 61) {
              windIconFilenameSuffix = '04.svg'
            } else if (rsp.weather.wind_speed_knots > 28) {
              windIconFilenameSuffix = '03.svg'
            } else if (rsp.weather.wind_speed_knots > 11) {
              windIconFilenameSuffix = '02.svg'
            } else if (rsp.weather.wind_speed_knots > 1) {
              windIconFilenameSuffix = '01.svg'
            }

            // Set the src of the wind icon image
            $('.weathericons img.windspeed_icon').attr('src', iconsBaseurl + '/' + windIconFilenameBase + windIconFilenameSuffix)

            /* Set weather icon:
                00: wolkenlos/sonnig
                01: heiter/leicht bewölkt
                02: wolkig/bewölkt
                03: stark bewölkt/bedeckt
            */

            const cloudcoverIconFilenameBase = 'icon_cloudcover_'

            // Set "clear" icon as default
            let cloudcoverIconFilenameSuffix = '00.svg'
            if (rsp.weather.cloudcover >= 75) {
              cloudcoverIconFilenameSuffix = '03.svg'
            } else if (rsp.weather.cloudcover >= 50) {
              cloudcoverIconFilenameSuffix = '02.svg'
            } else if (rsp.weather.cloudcover >= 25) {
              cloudcoverIconFilenameSuffix = '01.svg'
            }

            // Set the src of the weather icon image
            $('.weathericons img.cloudcover_icon').attr('src', iconsBaseurl + '/' + cloudcoverIconFilenameBase + cloudcoverIconFilenameSuffix)

            for (var i = 0; i < wave_objs.length; i++) {
              const animWindSpeed = rsp.weather.wind_speed_knots
              const animWaveHeight = rsp.weather.wave_height
              const animSwell = rsp.weather.swell

              // Dummy data for testing purposes
              // animWindSpeed = 30;
              // animWaveHeight = 18;
              // animSwell = 9;

              // Base value for delta_x_step is ({wind_speed[knots]} / 6) because there is slow movement with a delta_x_step of 1
              const baseDeltaXStep = animWindSpeed / 6 * (1 - (0.35 * animWindSpeed) / animWindSpeed) // =1 at 6 knots
              const baseDeltaTStep = animWindSpeed / 240 // =0.025 at 6 knots wind speed
              const baseDeltaYStep = animSwell / 150 // =0.013 at 2 swell

              const deltaTMax = 5 * (animWaveHeight / 1.5) * (1 - (0.5 * animWaveHeight) / animWaveHeight) // =5 at 1.5m wave height
              const offsetY = Math.floor(Math.random() * 80) - (animWaveHeight / 0.2)

              if (i < wave_objs.length - 1) {
                wave_objs[i].setDeltaXStep(baseDeltaXStep + Math.random())
                wave_objs[i].setDeltaTMax(deltaTMax)
                wave_objs[i].setOffsetY(offsetY)
              } else {
                wave_objs[i].setDeltaXStep(baseDeltaXStep)
              }

              wave_objs[i].setDeltaTStep(baseDeltaTStep)
              wave_objs[i].setDeltaYStep(baseDeltaYStep)
            }
          }
        }
      })
    }
  }
})
