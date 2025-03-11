import { buildRefs } from '@/assets/scripts/helpers'
import jQuery from 'jquery'

export default function (el) {

    const refs = buildRefs(el)
    const { modal, backdrop, closeButton, modalContent } = refs

    const initModal = () => {


        const modalButtons = document.querySelectorAll('[data-refs="modalLink"]');

        Array.from(modalButtons).forEach((modalLink) => {
            modalLink.addEventListener('click', (e) => {
                e.preventDefault()
                modalLink.getAttribute('href') && history.pushState(null, null, modalLink.getAttribute('href'))
                handleQueryParameters()
            })

        })

        modal.dataset.modalOpen === 'true' ? openModal() : null

        closeButton.addEventListener('click', () => {
            closeModal()
        })

        backdrop.addEventListener('click', () => {
            closeModal()
        })

        setupModalObserver()
        handleQueryParameters()
    }

    const handleQueryParameters = () => {
        const urlParams = new URLSearchParams(window.location.search);
        const modalParam = urlParams.get('modal');
        const slugParam = urlParams.get('slug');

        if (modalParam) {
            switch (modalParam) {
                case 'people':
                    jQuery.ajax({
                        type: 'POST',
                        url: '/wp-admin/admin-ajax.php',
                        dataType: 'html',
                        data: {
                            action: 'get_single_people',
                            slug: slugParam
                        }
                    })
                        .then(
                            (res) => {
                                modalContent.innerHTML = ''
                                modalContent.insertAdjacentHTML('beforeend', res)
                                openModal()
                            }
                        )
                    break
                case 'form':
                    // we load the form markup itself already hidden in the footer
                    // in order for WPForms to load properly - unfortunately it doesn't work with AJAX
                    modalContent.innerHTML = ''
                    modalContent.appendChild(document.querySelector(`#${slugParam}`))
                    modalContent.querySelector('div').style.display = 'block'
                    openModal()
                    break
                default:
                    break
            }
        } else {
            console.log('No modal parameter found');
        }
        return
    }

    const setupModalObserver = () => {
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'attributes' && mutation.attributeName === 'data-modal-open') {
                    const modalOpen = modal.getAttribute('data-modal-open')
                    if (modalOpen === 'true') {
                        _openModal()
                    } else {
                        _closeModal()
                    }
                }
            })
        })

        observer.observe(modal, {
            attributes: true
        })
    }

    const _closeModal = () => {
        // Logic to close the modal

        const url = new URL(window.location);
        url.searchParams.delete('modal');
        url.searchParams.delete('slug');
        window.history.replaceState({}, document.title, url);

        const isDesktop = window.matchMedia('(min-width: 1180px)').matches

        if (isDesktop) {
            modal.style.transform = '';
            modal.style.opacity = 0;
        } else {
            modal.style.transform = 'translateY(100%)';
        }

        setTimeout(() => {
            document.body.style.overflow = 'auto'
            el.style.display = 'none';
        }, 500)
    }

    const closeModal = () => {
        modal.setAttribute('data-modal-open', 'false')
    }

    const _openModal = () => {
        // Logic to open the modal
        el.style.display = 'block';

        const isDesktop = window.matchMedia('(min-width: 1180px)').matches

        setTimeout(() => {
            if (isDesktop) {
                modal.style.opacity = 1;
            } else {
                modal.style.transform = 'translateY(0)';
            }
        }, 50)


        document.body.style.overflow = 'hidden'
    }

    const openModal = () => {
        modal.setAttribute('data-modal-open', 'true')
    }

    initModal()
}