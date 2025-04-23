export default function (component) {
  const donateButton = component.querySelector('#donate');
  const donationInput = document.getElementById('donation');

  if (!donateButton || !donationInput) return;

  donateButton.addEventListener('click', () => {
    const ctaLink = donateButton.getAttribute('data-cta-link');
    const linkOrFallback = ctaLink ? `${ctaLink}?amount=` : '/spenden/?amount=';
    const customDonation = linkOrFallback + donationInput.value;
    window.location.href = customDonation;
  });
}
