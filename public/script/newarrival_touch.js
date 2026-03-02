// Enhance touch scrolling for new arrivals section
document.addEventListener('DOMContentLoaded', function() {
  const scrollContainer = document.getElementById('newArrivalsContainer');
  const prevButton = document.getElementById('newArrivalsPrev');
  const nextButton = document.getElementById('newArrivalsNext');
  let isDown = false;
  let startX;
  let scrollLeft;

  if (scrollContainer) {
    // Mouse events for desktop
    scrollContainer.addEventListener('mousedown', (e) => {
      isDown = true;
      scrollContainer.classList.add('active:cursor-grabbing');
      startX = e.pageX - scrollContainer.offsetLeft;
      scrollLeft = scrollContainer.scrollLeft;
    });

    scrollContainer.addEventListener('mouseleave', () => {
      isDown = false;
      scrollContainer.classList.remove('active:cursor-grabbing');
    });

    scrollContainer.addEventListener('mouseup', () => {
      isDown = false;
      scrollContainer.classList.remove('active:cursor-grabbing');
    });

    scrollContainer.addEventListener('mousemove', (e) => {
      if (!isDown) return;
      e.preventDefault();
      const x = e.pageX - scrollContainer.offsetLeft;
      const walk = (x - startX) * 2;
      scrollContainer.scrollLeft = scrollLeft - walk;
    });

    // Touch events for mobile
    let touchStartX = 0;
    let touchScrollLeft = 0;

    scrollContainer.addEventListener('touchstart', (e) => {
      touchStartX = e.touches[0].clientX;
      touchScrollLeft = scrollContainer.scrollLeft;
    });

    scrollContainer.addEventListener('touchmove', (e) => {
      if (!touchStartX) return;
      const touchX = e.touches[0].clientX;
      const walk = (touchStartX - touchX) * 1.5;
      scrollContainer.scrollLeft = touchScrollLeft + walk;
    });

    scrollContainer.addEventListener('touchend', () => {
      touchStartX = 0;
    });

    // Navigation button functionality
    if (prevButton && nextButton) {
      // Show/hide buttons based on scroll position
      function updateButtonVisibility() {
        const maxScroll = scrollContainer.scrollWidth - scrollContainer.clientWidth;
        
        // Show both buttons if there's overflow
        if (maxScroll > 0) {
          prevButton.style.opacity = scrollContainer.scrollLeft > 0 ? '1' : '0';
          nextButton.style.opacity = scrollContainer.scrollLeft < maxScroll ? '1' : '0';
        } else {
          // Hide both buttons if no overflow
          prevButton.style.opacity = '0';
          nextButton.style.opacity = '0';
        }
      }

      // Scroll functions
      prevButton.addEventListener('click', () => {
        const scrollAmount = scrollContainer.clientWidth * 0.8; // Scroll 80% of container width
        scrollContainer.scrollBy({
          left: -scrollAmount,
          behavior: 'smooth'
        });
      });

      nextButton.addEventListener('click', () => {
        const scrollAmount = scrollContainer.clientWidth * 0.8; // Scroll 80% of container width
        scrollContainer.scrollBy({
          left: scrollAmount,
          behavior: 'smooth'
        });
      });

      // Update button visibility on scroll
      scrollContainer.addEventListener('scroll', updateButtonVisibility);
      
      // Initial visibility check
      setTimeout(updateButtonVisibility, 100);
      
      // Update on window resize
      window.addEventListener('resize', updateButtonVisibility);
    }
  }
});
