import DottedMap from 'dotted-map'
import { buildRefs } from '@/assets/scripts/helpers.js'
import { getMapJSON } from 'dotted-map'

const mapDesktop = getMapJSON({
  height: 108  * 9 / 16,
  width: 108,
  grid: 'vertical'
})

const mapMobile = getMapJSON({
  height: 72  * 9 / 16,
  width: 72,
  grid: 'vertical'
})

export default function (listingMap) {
  const refs = buildRefs(listingMap)
  const wrapper = refs.mapWrapper

  // find the project which is the highest and set the min height of the map to that
  const projectList = listingMap.getElementsByClassName('project-list')[0]
  const projects = listingMap.getElementsByClassName('project')
  const maxHeight = Math.max(...Array.from(projects).map(project => project.offsetHeight))
  projectList.style.height = maxHeight + 'px'

  // TODO: on resize, update the height of the map
  // possibly reload complete component or js on resize, because dots on map are not responsive

  

  // const map = new DottedMap({
  //   height: wrapper.offsetHeight / resolution,
  //   width: wrapper.offsetWidth / resolution,
  //   grid: 'vertical'
  // })

  const isMobile = window.innerWidth < 1025

  console.log('isMobile', isMobile, window.innerWidth)

  const map = new DottedMap(JSON.parse(isMobile ? mapMobile : mapDesktop))
  const mapWidth = isMobile ? 72 : 108
  const mapHeight = isMobile ? 72 * 9 / 16 : 108 * 9 / 16

  console.log('wrapper', wrapper.offsetWidth, wrapper.offsetHeight)
  console.log(map)

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
    
    if (latitude && longitude) {

    const pinCoords = map.getPin({
      lat: parseFloat(latitude),
      lng: parseFloat(longitude)
    })

    pin.style.left = pinCoords.x * 100 / mapWidth + '%'
    pin.style.top = pinCoords.y * 100 / mapHeight + '%'
    }
    else {
      pin.style.opacity = 0
    }
  })
}
