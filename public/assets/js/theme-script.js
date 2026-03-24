const trigger = document.getElementById("menuTrigger");
const overlay = document.getElementById("megaOverlay");

trigger.addEventListener("click", () => {
  // Toggle the Morphing Icon
  trigger.classList.toggle("active");

  // Toggle the Mega Menu Visibility
  overlay.classList.toggle("open");

  // Prevent scrolling when menu is open
  if (overlay.classList.contains("open")) {
    document.body.style.overflow = "hidden";
  } else {
    document.body.style.overflow = "auto";
  }
});

var swiper = new Swiper(".categoryswiper", {
  slidesPerView: 7,
  spaceBetween: 50,
  grabCursor: true,
  pagination: false,
  loop: false,
  speed: 5000,
  parallax: false,
  navigation: {
    prevEl: ".swiper-button-prev",
    nextEl: ".swiper-button-next",
  },
  autoplay: { delay: 3000, disableOnInteraction: false },
  allowTouchMove: true,
  breakpoints: {
    640: { slidesPerView: 3, spaceBetween: 15 },
    768: { slidesPerView: 3, spaceBetween: 15 },
    1024: { slidesPerView: 4, spaceBetween: 30 },
    1280: { slidesPerView: 4, spaceBetween: 30 },
  },
});

var swiper = new Swiper(".gamepcswiper", {
  slidesPerView: 4,
  spaceBetween: 15,
  grabCursor: true,
  pagination: {
    el: ".swiper-pagination",
    dynamicBullets: true,
  },
  loop: false,
  speed: 5000,
  freeMode: false,
  parallax: true,
  navigation: {
    prevEl: ".swiper-button-prev",
    nextEl: ".swiper-button-next",
  },
  autoplay: { delay: 3000, disableOnInteraction: false },
  allowTouchMove: true,
  breakpoints: {
    640: { slidesPerView: 1, spaceBetween: 15 },
    768: { slidesPerView: 2, spaceBetween: 15 },
    1024: { slidesPerView: 4, spaceBetween: 15 },
    1280: { slidesPerView: 4, spaceBetween: 15 },
  },
});

var swiper = new Swiper(".productswiper", {
  slidesPerView: 5,
  spaceBetween: 15,
  grabCursor: true,
  pagination: {
    el: ".swiper-pagination",
    dynamicBullets: true,
  },
  dots: true,
  loop: true,
  speed: 5000,
  freeMode: false,
  parallax: true,
  navigation: {
    prevEl: ".swiper-button-prev",
    nextEl: ".swiper-button-next",
  },
  autoplay: { delay: 3000, disableOnInteraction: false },
  allowTouchMove: true,
  breakpoints: {
    640: { slidesPerView: 1.1, spaceBetween: 15, centeredSlides: true },
    768: { slidesPerView: 2, spaceBetween: 15 },
    1024: { slidesPerView: 3, spaceBetween: 15 },
    1280: { slidesPerView: 4, spaceBetween: 15 },
  },
});

var swiper = new Swiper(".equipmentswiper", {
  slidesPerView: 4,
  spaceBetween: 15,
  grabCursor: true,
  pagination: {
    el: ".swiper-pagination",
    dynamicBullets: true,
  },
  dots: true,
  loop: false,
  speed: 5000,
  freeMode: false,
  parallax: true,
  navigation: false,
  autoplay: { delay: 3000, disableOnInteraction: false },
  allowTouchMove: true,
  breakpoints: {
    640: { slidesPerView: 1.1, spaceBetween: 15, centeredSlides: true },
    768: { slidesPerView: 2, spaceBetween: 15 },
    1024: { slidesPerView: 3, spaceBetween: 15 },
    1280: { slidesPerView: 4, spaceBetween: 15 },
  },
});

var swiper = new Swiper(".adswipertwo", {
  slidesPerView: 2,
  spaceBetween: 15,
  grabCursor: true,
  pagination: false,
  loop: true,
  speed: 5000,
  freeMode: false,
  parallax: true,
  navigation: false,
  autoplay: { delay: 3000, disableOnInteraction: false },
  allowTouchMove: true,
  breakpoints: {
    640: { slidesPerView: 1, spaceBetween: 15 },
    768: { slidesPerView: 1, spaceBetween: 30 },
    1024: { slidesPerView: 2, spaceBetween: 30 },
    1280: { slidesPerView: 2, spaceBetween: 30 },
  },
});

var swiper = new Swiper(".adswiperone", {
  slidesPerView: 1,
  spaceBetween: 15,
  grabCursor: true,
  pagination: false,
  loop: true,
  speed: 5000,
  freeMode: false,
  parallax: true,
  navigation: false,
  autoplay: { delay: 3000, disableOnInteraction: false },
  allowTouchMove: true,
});

var swiper = new Swiper(".promobnrswiper", {
  slidesPerView: 1,
  spaceBetween: 15,
  grabCursor: true,
  pagination: false,
  loop: true,
  speed: 5000,
  freeMode: false,
  parallax: true,
  navigation: false,
  autoplay: { delay: 3000, disableOnInteraction: false },
  allowTouchMove: true,
  breakpoints: {
    640: { slidesPerView: 2, spaceBetween: 15 },
    768: { slidesPerView: 2, spaceBetween: 30 },
    1024: { slidesPerView: 4, spaceBetween: 30 },
    1280: { slidesPerView: 4, spaceBetween: 30 },
  },
});

// const buttons = document.querySelectorAll('.tab-btn');

// buttons.forEach(btn => {
//   btn.addEventListener('click', (e) => {
//     // Remove active styles from all
//     buttons.forEach(b => b.className = 'tab-btn border rounded-full transition-all duration-300 border-[#ffffff30] bg-transparent text-[#ffffff30] text-[14px] uppercase px-[30px] py-[15px] font-medium cursor-pointer hover:bg-[white] hover:text-[black] focus:bg-[white] focus:text-[black] active:bg-[white] active:text-[black]');

//     // Add active styles to clicked
//     e.target.className = 'tab-btn border rounded-full transition-all duration-300 border-[#ffffff30] bg-white text-black text-[14px] uppercase px-[30px] py-[15px] font-medium cursor-pointer hover:bg-[white] hover:text-[black] focus:bg-[white] focus:text-[black] active:bg-[white] active:text-[black]';
//   });
// });

// Select all buttons with the class 'tab-btn'
const tabButtons = document.querySelectorAll(".tab-btn");

const inactiveClasses =
  "tab-btn border rounded-full transition-all duration-300 border-[#ffffff30] bg-transparent text-[#ffffff30] text-[13px] uppercase px-[30px] py-[15px] font-medium cursor-pointer hover:bg-[white] hover:text-[black]";
const activeClasses =
  "tab-btn border rounded-full transition-all duration-300 border-[#ffffff30] bg-white text-black text-[13px] uppercase px-[30px] py-[15px] font-medium cursor-pointer hover:bg-[white] hover:text-[black]";

tabButtons.forEach((btn) => {
  btn.addEventListener("click", function (e) {
    // 1. Find the parent container of the clicked button
    const parentSection = this.closest(".flex"); // or use a specific class like '.tab-container'

    // 2. Only find buttons within THIS specific section
    const sectionButtons = parentSection.querySelectorAll(".tab-btn");

    // 3. Reset only these buttons
    sectionButtons.forEach((b) => {
      b.className = inactiveClasses;
      b.setAttribute("data-active", "false");
    });

    // 4. Set the clicked button to active
    this.className = activeClasses;
    this.setAttribute("data-active", "true");
  });
});


var swiper = new Swiper(".video-testimonials", {
  slidesPerView: 1,
  spaceBetween: 0,
  grabCursor: true,
  pagination: {
    el: ".swiper-pagination",
    dynamicBullets: true,
  },
  loop: true,
  speed: 3000,
  freeMode: false,
  parallax: true,
  effect: "fade",
  navigation: false,
  autoplay: { delay: 5000, disableOnInteraction: false },
  allowTouchMove: true,
  breakpoints: {
    640: { slidesPerView: 1 },
    768: { slidesPerView: 1 },
    1024: { slidesPerView: 1 },
    1280: { slidesPerView: 1 },
  },
});

var swiper = new Swiper(".g-testimonials", {
  slidesPerView: 1,
  spaceBetween: 50,
  grabCursor: true,
  pagination: {
    el: ".swiper-pagination",
    dynamicBullets: true,
  },
  loop: true,
  speed: 3000,
  freeMode: false,
  parallax: true,
  navigation: {
    prevEl: ".swiper-button-prev",
    nextEl: ".swiper-button-next",
  },
  autoplay: { delay: 5000, disableOnInteraction: false },
  allowTouchMove: true,
});

var swiper = new Swiper(".aboutswiper", {
  slidesPerView: 1,
  spaceBetween: 50,
  grabCursor: true,
  pagination: true,
  loop: true,
  speed: 3000,
  freeMode: false,
  parallax: true,
  navigation: false,
  autoplay: { delay: 5000, disableOnInteraction: false },
  allowTouchMove: true,
});

var swiper = new Swiper(".singleprdswiper", {
  slidesPerView: 1,
  spaceBetween: 50,
  grabCursor: true,
  pagination: true,
  dots: true,
  loop: true,
  speed: 3000,
  freeMode: false,
  parallax: true,
  navigation: true,
  autoplay: { delay: 5000, disableOnInteraction: false },
  allowTouchMove: true,
});

document.addEventListener('DOMContentLoaded', function () {
    // 1. Initialize Lightbox
    const lightbox = GLightbox({
        selector: '.glightbox',
        touchNavigation: true,
    });

    // 2. Identify your background player
    const bgPlayer = document.getElementById('bgPlayer');

    // 3. Listen for Lightbox Opening
    lightbox.on('open', () => {
        // Send 'pauseVideo' command to the YouTube Iframe
        bgPlayer.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
    });

    // 4. Listen for Lightbox Closing
    lightbox.on('close', () => {
        // Send 'playVideo' command to resume background playback
        bgPlayer.contentWindow.postMessage('{"event":"command","func":"playVideo","args":""}', '*');
    });
});

lightbox.on('open', () => {
    // Pause all iframes on the page to be safe
    const allIframes = document.querySelectorAll('iframe');
    allIframes.forEach(iframe => {
        iframe.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
    });
});

lightbox.on('close', () => {
    // Resume only the one in the visible swiper slide
    const activeSlide = document.querySelector('.swiper-slide-active iframe');
    if(activeSlide) {
        activeSlide.contentWindow.postMessage('{"event":"command","func":"playVideo","args":""}', '*');
    }
});

document.addEventListener('DOMContentLoaded', function () {
const initializeAvatar = () => {
    const nameElement = document.getElementById('userName');
    const avatarElement = document.getElementById('userAvatar');

    if (nameElement && avatarElement) {
        // .innerText is more reliable for visible text than .textContent
        const nameText = nameElement.innerText.trim();
        
        if (nameText && nameText !== "?") {
            const firstLetter = nameText.charAt(0).toUpperCase();
            avatarElement.innerText = firstLetter;
            console.log("Avatar updated to:", firstLetter); // Check your console (F12)
            return true; // Success
        }
    }
    return false; // Not ready yet
};

// Try immediately, on load, and as a fallback interval
window.addEventListener('load', initializeAvatar);

// Fallback: Check every 100ms for 2 seconds (in case of slow loading)
let attempts = 0;
const checkExist = setInterval(() => {
    const success = initializeAvatar();
    attempts++;
    if (success || attempts > 20) clearInterval(checkExist);
}, 100);
});



function toggleBilling() {
    const isChecked = document.getElementById('billing-toggle').checked;
    const billingSection = document.getElementById('billing-section');
    
    if (isChecked) {
        billingSection.classList.add('hidden');
    } else {
        billingSection.classList.remove('hidden');
        // Smooth scroll to the billing section for better UX
        billingSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
}


/**
 * MOBILE NAVIGATION CONTROLLER - FINAL STABLE VERSION
 */

function closeAllMobileSystems() {
    // 1. Reset Sidebar
    const sidebar = document.getElementById('mobile-sidebar');
    if (sidebar) {
        sidebar.classList.add('left-[-100%]');
        sidebar.classList.remove('left-0');
    }

    // 2. Reset Main Overlay
    const overlay = document.getElementById('sidebar-overlay');
    if (overlay) {
        overlay.classList.replace('opacity-100', 'opacity-0');
        overlay.classList.replace('pointer-events-auto', 'pointer-events-none');
    }

    // 3. Reset Slide-up Panels
    const panels = ['mobile-filter-panel', 'mobile-search-panel'];
    panels.forEach(id => {
        const p = document.getElementById(id);
        if (p && !p.classList.contains('invisible')) {
            const content = p.querySelector('div:last-child');
            content.classList.replace('translate-y-0', 'translate-y-full');
            setTimeout(() => {
                p.classList.add('invisible', 'pointer-events-none');
            }, 300);
        }
    });

    document.body.style.overflow = 'auto';
}

function toggleMobileMenu(event) {
    if (event) event.stopPropagation();
    const sidebar = document.getElementById('mobile-sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    
    const isOpening = sidebar.classList.contains('left-[-100%]');

    if (isOpening) {
        // Instant close others
        document.querySelectorAll('.slide-up-panel').forEach(p => p.classList.add('invisible'));
        
        sidebar.classList.replace('left-[-100%]', 'left-0');
        overlay.classList.replace('opacity-0', 'opacity-100');
        overlay.classList.replace('pointer-events-none', 'pointer-events-auto');
        document.body.style.overflow = 'hidden';
    } else {
        closeAllMobileSystems();
    }
}

function toggleMobileWidget(panelId, event) {
    if (event) event.stopPropagation();
    
    const panel = document.getElementById(panelId);
    const content = panel.querySelector('div:last-child');

    if (!panel.classList.contains('invisible')) {
        closeAllMobileSystems();
        return;
    }

    // Close everything else first
    closeAllMobileSystems();

    panel.classList.remove('invisible', 'pointer-events-none');
    
    // Smooth timing
    setTimeout(() => {
        content.classList.replace('translate-y-full', 'translate-y-0');
    }, 10);

    document.body.style.overflow = 'hidden';
}

// GLOBAL CLICK LISTENER: This ensures any click on a backdrop closes the system
document.addEventListener('click', function(e) {
    // Check if the clicked element is the overlay or a panel's backdrop
    if (e.target.id === 'sidebar-overlay' || e.target.getAttribute('data-backdrop') === 'true') {
        closeAllMobileSystems();
    }
});



function handleSortClick(selectedBtn) {
    // 1. Find all buttons in the sort container
    const allSortBtns = document.querySelectorAll('.sort-btn');

    // 2. Reset all buttons to "inactive" state
    allSortBtns.forEach(btn => {
        btn.classList.remove('border-[#2A7CFF]', 'text-[#2A7CFF]');
        btn.classList.add('border-white/5', 'text-gray-400');
    });

    // 3. Set the clicked button to "active" state
    selectedBtn.classList.remove('border-white/5', 'text-gray-400');
    selectedBtn.classList.add('border-[#2A7CFF]', 'text-[#2A7CFF]');

    // SEO/Ads Tip: You can trigger your filtering function here 
    // or wait until they hit "Apply Filters"
    console.log("Sorted by: " + selectedBtn.innerText);
}


