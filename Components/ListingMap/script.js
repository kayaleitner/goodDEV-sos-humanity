import DottedMap from 'dotted-map'
import { buildRefs } from '@/assets/scripts/helpers.js'

const resolution = 10

export default function (listingMap) {
  const refs = buildRefs(listingMap)
  const wrapper = refs.mapWrapper

  const map = new DottedMap({
    height: wrapper.offsetHeight / resolution,
    width: wrapper.offsetWidth / resolution,
    grid: 'vertical'
  })

  const svgMap = map.getSVG({
    radius: 0.36,
    color: '#aaaaaa',
    shape: 'circle'
  })

  document.getElementById('dottedMap').innerHTML = svgMap

  const pinRefs = document.querySelectorAll('[data-ref="pin"]')
  console.log(pinRefs)

  pinRefs.forEach(pin => {
    const { latitude, longitude } = pin.dataset
    console.log(latitude, longitude)
    const pinCoords = map.getPin({
      lat: parseFloat(latitude),
      lng: parseFloat(longitude)
    })
    pin.style.left = pinCoords.x * resolution + 'px'
    pin.style.top = pinCoords.y * resolution + 'px'
  })
}
