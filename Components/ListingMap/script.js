import DottedMap from 'dotted-map'
import { buildRefs } from '@/assets/scripts/helpers.js'

const resolution = 10

export default function (listingMap) {
  const refs = buildRefs(listingMap)
  const wrapper = refs.mapWrapper

  // find the project which is the highest and set the min height of the map to that
  const projectList = listingMap.getElementsByClassName('project-list')[0]
  const projects = listingMap.getElementsByClassName('project');
  const maxHeight = Math.max(...Array.from(projects).map(project => project.offsetHeight))
  projectList.style.height = maxHeight + 'px'

  // TODO: on resize, update the height of the map
  // possibly reload complete component or js on resize, because dots on map are not responsive

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
