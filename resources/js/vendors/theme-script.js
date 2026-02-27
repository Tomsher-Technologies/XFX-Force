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
  pagination: false,
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
  pagination: true,
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




var swiper = new Swiper(".g-testimonials", {
  slidesPerView: 1,
  spaceBetween: 50,
  grabCursor: true,
  pagination: true,
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

