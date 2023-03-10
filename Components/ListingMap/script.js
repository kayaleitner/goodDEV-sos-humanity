import DottedMap from 'dotted-map'

const map = new DottedMap({
  height: 90,
  width: 100,
  grid: 'diagonal'
})

map.addPin({
  lat: 40.73061,
  lng: -73.935242,
  svgOptions: { color: '#169b83', radius: 0.4 }
})
map.addPin({
  lat: 48.8534,
  lng: 2.3488,
  svgOptions: { color: '#169b83', radius: 0.4 }
})

console.log(map)

const svgMap = map.getSVG({
  radius: 0.22,
  color: '#423B38',
  shape: 'circle'
})

document.getElementById('dottedMap').innerHTML = svgMap
