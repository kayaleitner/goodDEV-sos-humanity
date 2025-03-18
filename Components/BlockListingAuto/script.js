import gsap from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import jQuery from 'jquery'
import { buildRefs, getJSON } from '../../assets/scripts/helpers'

export default function (component) {
  const refs = buildRefs(component, true)
  const data = getJSON(component)
  const c = initComponent(refs, data)
  return () => c.destroy && c.destroy()
}

function initComponent(refs, data) {

  const d = {
    filterQuery: refs.listing[0].dataset.query,
    post_types: refs.listing[0].dataset.postTypes,
    order: refs.listing[0].dataset.order,
    orderby: refs.listing[0].dataset.orderby,
    maxPosts: refs.listing[0].dataset.maxPosts,
    labels: refs.listing[0].dataset.labels
  }

  Array.from(refs.select).forEach((select) => {
    select.addEventListener('change', (e) => {
      select.dataset.value = e.target.value
      const userQuery = []

      Array.from(refs.select).forEach((select) => {
        select.dataset.value &&
          select.dataset.value !== '*' &&
          userQuery.push({
            taxonomy: select.name,
            field: 'term_id',
            terms: [select.dataset.value]
          })
      })

      jQuery
        .ajax({
          type: 'POST',
          url: '/wp-admin/admin-ajax.php',
          dataType: 'html',
          data: {
            action: 'apply_filters_posts',
            tax_query: userQuery.length ? userQuery : [],
            filter_query: d.filterQuery,
            post_types: d.post_types,
            order: d.order,
            orderby: d.orderby,
            maxPosts: d.maxPosts,
            labels: d.labels
          }
        })
        .then(
          (res) => {

            const textListItem = refs.listing[0].querySelector('.text-list-item')

            if (textListItem) {
              refs.listing[0].innerHTML = ''
              refs.listing[0].appendChild(textListItem)
              refs.listing[0].innerHTML += res
            } else {
              refs.listing[0].innerHTML = res
            }
            document.querySelectorAll('.anim-fade-in').forEach((item) => {
              gsap.to(item, { opacity: 1, stagger: 0.2, duration: 0.5 })
            })
            ScrollTrigger.refresh()
          },
          (err) => {
            console.log(err)
          }
        )
    })
  })

  Array.from(refs.loadMore).forEach((loadMore) => {
    loadMore.addEventListener('click', (e) => {
      const userQuery = []

      Array.from(refs.select).forEach((select) => {
        select.dataset.value &&
          select.dataset.value !== '*' &&
          userQuery.push({
            taxonomy: select.name,
            field: 'term_id',
            terms: [select.dataset.value]
          })
      })

      jQuery
        .ajax({
          type: 'POST',
          url: '/wp-admin/admin-ajax.php',
          dataType: 'html',
          data: {
            action: 'load_more_posts',
            tax_query: userQuery.length ? userQuery : 'null',
            filter_query: d.filterQuery,
            post_types: d.post_types,
            order: d.order,
            orderby: d.orderby,
            maxPosts: d.maxPosts,
            labels: d.labels,
            count: refs.listing[0].querySelectorAll('li').length
          }
        })
        .then((res) => {
          refs.listing[0].innerHTML = refs.listing[0].innerHTML + res
          if ((res.match(/<li /g) || []).length < d.maxPosts) {
            loadMore.style.display = 'none'
          }
          document.querySelectorAll('.anim-fade-in').forEach((item) => {
            gsap.to(item, { opacity: 1, stagger: 0.2, duration: 0.5 })
          })
          ScrollTrigger.refresh()
        })
    })
  })
}
