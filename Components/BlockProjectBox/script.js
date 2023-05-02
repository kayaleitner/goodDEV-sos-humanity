import DottedMap, { getMapJSON } from 'dotted-map'

const jsonMap = getMapJSON({
  height: 72 * 9 / 16,
  width: 72,
  grid: 'vertical'
})

export default function (projectMap) {
  const map = new DottedMap(JSON.parse(jsonMap))

  const svgMap = map.getSVG({
    radius: 0.3,
    color: '#7b838d',
    shape: 'circle'
  })

  document.getElementById('dottedMap').innerHTML = svgMap

  const pinRefs = document.querySelectorAll('[data-ref="pin"]')

  pinRefs.forEach(pin => {
    const { latitude, longitude } = pin.dataset

    if (latitude && longitude) {
      const pinCoords = map.getPin({
        lat: parseFloat(latitude),
        lng: parseFloat(longitude)
      })

      pin.style.left = pinCoords.x * 100 / 72 + '%'
      pin.style.top = pinCoords.y * 100 / 72 / 9 * 16 + '%'
    }
  })
}
