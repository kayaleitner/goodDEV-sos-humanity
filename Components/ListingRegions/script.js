import DottedMap from 'dotted-map'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import { gsap } from 'gsap'
import mapDesktop from '../../assets/images/mapDesktop.json'
import mapMobile from '../../assets/images/mapMobile.json'

gsap.registerPlugin(ScrollTrigger)

export default function (listingRegions) {
  // find the project which is the highest and set the min height of the map to that
  const projectList = listingRegions.getElementsByClassName('region-list')[0]
  const projects = listingRegions.getElementsByClassName('region')
  const projectArray = Array.from(projects)

  const heightArray = []
  heightArray.push(projectArray[0].offsetHeight)
  for (let i = 1; i < projects.length; i++) {
    heightArray.push(projectArray[i].offsetHeight)
  }

  const maxHeight = Math.max(...heightArray)

  projectList.style.height = `${maxHeight}px`

  // TODO: on resize, update the height of the map
  // possibly reload complete component or js on resize, because dots on map are not responsive

  const isMobile = window.innerWidth < 1025

  const baseMap = new DottedMap(isMobile ? mapMobile : mapDesktop)
  const mapWidth = isMobile ? 72 : 108
  const mapHeight = isMobile ? (72 * 9) / 16 : (108 * 9) / 16
  const mapWrapper = listingRegions.querySelector('#regionsMapWrapper')

  const svgMap = baseMap.getSVG({
    radius: 0.36,
    color: 'rgba(255, 255, 255, 0.3)',
    shape: 'circle',
  })

  listingRegions.querySelector('#dottedMap').innerHTML = svgMap

  const setRegionsMap = (countries) => {
    const map = new DottedMap({
      height: mapHeight,
      width: mapWidth,
      countries,
      region: { lat: { min: -56, max: 71 }, lng: { min: -179, max: 179 } },
      grid: 'vertical',
    })

    const regionsMap = map.getSVG({
      radius: 0.36,
      color: '#ffffff',
      shape: 'circle',
    })

    listingRegions.querySelector('#regionsMap').innerHTML = regionsMap
  }

  setRegionsMap(mapWrapper.dataset.region.split(',').filter((e) => e))

  // mutation observer for mapWrapper on data-region
  const observer = new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
      if (mutation.type === 'attributes') {
        const region = mutation.target.dataset.region
          .split(',')
          .filter((e) => e)
        setRegionsMap(region)
      }
    })
  })

  observer.observe(mapWrapper, {
    attributes: true,
  })

  ScrollTrigger?.refresh()
}

// const regions = {
//   mena: [
//     "ARE", // UAE
//     "JOR", // Jordan
//     "LBN", // Lebanon
//     "IRQ", // Iraq
//     "PSE", // Palestine
//     "SAU", // Saudi Arabia
//     "AFG", // Afghanistan
//     "ISR", // Israel
//     "MAR", // Morocco
//     "EGY", // Egypt
//     "TUN", // Tunisia
//     "DZA", // Algeria
//   ],
//   sa: [
//     "IND", // India
//     "LKA", // Sri Lanka
//     "NPL", // Nepal
//     "BGD", // Bangladesh
//   ],
//   sea: [
//     "THA", // Thailand
//     "KHM", // Cambodia
//     "VNM", // Vietnam
//     "LAO", // Laos
//     "IDN", // Indonesia
//   ],
//   europe: [
//     "MDA", // Moldova
//     "UKR", // Ukraine
//     "GEO", // Georgia
//     "ARM", // Armenia
//   ],
//   centralAsia: [
//     "KAZ", // Kazakhstan
//     "KGZ", // Kyrgyzstan
//     "TJK", // Tajikistan
//     "TKM", // Turkmenistan
//     "UZB", // Uzbekistan
//   ],
//   latam: [
//     "SLV", // El Salvador
//     "GTM", // Guatemala
//     "MEX", // Mexico
//     "COL", // Colombia
//     "HTI", // Haiti
//     "BHS", // Bahamas
//     "BRA", // Brazil
//   ],
//   ssa: [
//     "SEN", // Senegal
//     "GIN", // Guinea
//     "GMB", // Gambia
//     "GHA", // Ghana
//     "CIV", // Ivory Coast
//     "TGO", // Togo
//     "BEN", // Benin
//     "NGA", // Nigeria
//     "MLI", // Mali
//     "NER", // Niger
//     "TCD", // Chad
//     "COD", // Democratic Republic of the Congo
//     "COG", // Republic of the Congo
//     "CMR", // Cameroon
//     "GNQ", // Equatorial Guinea
//     "UGA", // Uganda
//     "RWA", // Rwanda
//     "TZA", // Tanzania
//     "KEN", // Kenya
//     "ETH", // Ethiopia
//     "SSD", // South Sudan
//     "MOZ", // Mozambique
//     "MRT", // Mauritania
//     "ZMB", // Zambia
//     "MWI", // Malawi
//     "ZAF", // South Africa
//     "ZWE", // Zimbabwe
//     "NAM", // Namibia
//     "SLE", // Sierra Leone
//     "MDG", // Madagascar
//   ]
// }

// const regions = {
//   mena: ["ARE","JOR","LBN","IRQ","PSE","SAU","AFG","ISR","MAR","EGY","TUN","DZA"],
//   sa: ["IND","LKA","NPL","BGD"],
//   sea: ["THA","KHM","VNM","LAO","IDN"],
//   europe: ["MDA","UKR","GEO","ARM"],
//   centralAsia: ["KAZ","KGZ","TJK","TKM","UZB"],
//   latam: ["SLV","GTM","MEX","COL","HTI","BHS","BRA",],
//   ssa: ["SEN","GIN","GMB","GHA","CIV","TGO","BEN","NGA","MLI","NER","TCD","COD","COG","CMR","GNQ","UGA","RWA","TZA","KEN","ETH","SSD","MOZ","MRT","ZMB","MWI","ZAF","ZWE","NAM","SLE","MDG"]
// }
