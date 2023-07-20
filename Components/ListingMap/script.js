import DottedMap from 'dotted-map'
import mapDesktop from '../../assets/images/mapDesktop.json'
import mapMobile from '../../assets/images/mapMobile.json'

export default function (listingMap) {
  // find the project which is the highest and set the min height of the map to that
  const projectList = listingMap.getElementsByClassName('project-list')[0]
  const projects = listingMap.getElementsByClassName('project')
  const projectArray = Array.from(projects)

  const heightArray = []
  heightArray.push(projectArray[0].offsetHeight)
  for (let i = 1; i < projects.length; i++) {
    projectArray[i].style.display = 'block'
    heightArray.push(projectArray[i].offsetHeight)
    projectArray[i].style.display = 'none'
  }

  const maxHeight = Math.max(...heightArray)

  projectList.style.height = `${maxHeight}px`

  // TODO: on resize, update the height of the map
  // possibly reload complete component or js on resize, because dots on map are not responsive

  // const map = new DottedMap({
  //   height: wrapper.offsetHeight / resolution,
  //   width: wrapper.offsetWidth / resolution,
  //   grid: 'vertical'
  // })

  const isMobile = window.innerWidth < 1025

  const map = new DottedMap(isMobile ? mapMobile : mapDesktop)
  const mapWidth = isMobile ? 72 : 108
  const mapHeight = isMobile ? (72 * 9) / 16 : (108 * 9) / 16

  const svgMap = map.getSVG({
    radius: 0.36,
    color: '#aaaaaa',
    shape: 'circle',
  })

  document.getElementById('dottedMap').innerHTML = svgMap

  const pinRefs = document.querySelectorAll('[data-ref="pin"]')

  pinRefs.forEach((pin) => {
    const { latitude, longitude } = pin.dataset

    if (latitude && longitude) {
      const pinCoords = map.getPin({
        lat: parseFloat(latitude),
        lng: parseFloat(longitude),
      })

      pin.style.left = `${(pinCoords.x * 100) / mapWidth}%`
      pin.style.top = `${(pinCoords.y * 100) / mapHeight}%`
    } else {
      pin.style.opacity = 0
    }
  })
}
