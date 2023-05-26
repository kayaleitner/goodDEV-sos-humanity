import { buildRefs, getJSON } from "../../assets/scripts/helpers"
import { ScrollTrigger } from "gsap/ScrollTrigger"
import jQuery from "jquery"

export default function (component) {
  const refs = buildRefs(component, true)
  const data = getJSON(component)
  const c = initComponent(refs, data)
  return () => c.destroy && c.destroy() 
}

function initComponent(refs, data) {

  console.log(refs, data)

  const d = {
    taxonomies: [],
    query: refs.listing[0].dataset.query,
    post_types: refs.listing[0].dataset.postTypes,
    order: refs.listing[0].dataset.order,
    orderby: refs.listing[0].dataset.orderby,
    maxPosts: refs.listing[0].dataset.maxPosts,
    labels: refs.listing[0].dataset.labels,
  }

  Array.from(refs.select).forEach(select => {

    select.addEventListener('change', e => {

      select.dataset.value = e.target.value


      const t = []

      Array.from(refs.select).forEach(select => {
        select.dataset.value && (select.dataset.value != '*') && t.push({
          "taxonomy": select.name,
          "field": "term_id",
          "terms": [select.dataset.value],
        })
      })

      jQuery.ajax({
        type: 'POST',
        url: '/wp-admin/admin-ajax.php',
        dataType: 'html',
        data: {
          action: 'apply_filters_posts',
          taxonomies: t,
          tax_query: t,
          post_types: d.post_types,
          order: d.order,
          orderby: d.orderby,
          maxPosts: d.maxPosts,
          labels: d.labels,
        },
      }).then(
        res => {
          refs.listing[0].innerHTML = res
          ScrollTrigger && ScrollTrigger.refresh()
        },
        err => {
          console.log(err);
        }
      );
    })
  })
}
