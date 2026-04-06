// import './bootstrap';
import Alpine from 'alpinejs';
import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';
import $ from 'jquery';
import GLightbox from 'glightbox';
import 'glightbox/dist/css/glightbox.min.css';


import toastr from 'toastr';
import 'toastr/build/toastr.min.css';
import Swal from 'sweetalert2';
// import '../css/app.css';



window.$ = window.jQuery = $;
window.toastr = toastr; // Make toastr globally available

window.Alpine = Alpine;
Alpine.start();

window.Swal = Swal;

// âœ… Categories Swiper
new Swiper("#category-swiper", {
    loop: true,
    autoplay: {
        delay: 2000,
        disableOnInteraction: false,
    },
    slidesPerView: 2,
    spaceBetween: 20,
    breakpoints: {
        640: { slidesPerView: 3 }, // Tablets
        1024: { slidesPerView: 4 }, // Laptops
        1280: {
            slidesPerView: 6

        }  // Large Screens
    },

});

const thumbSwiper = new Swiper('.product-thumbs-swiper', {
    spaceBetween: 10,
    slidesPerView: 4,
    freeMode: true,
    watchSlidesProgress: true,
});

const mainSwiper = new Swiper('.product-images-swiper', {
    spaceBetween: 10,
    thumbs: {
        swiper: thumbSwiper,
    },
});


document.addEventListener("DOMContentLoaded", function () {
    new Swiper('.testimonials-swiper', {
        loop: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        slidesPerView: 1,
        spaceBetween: 30,
        navigation: {
            nextEl: '.next',
            prevEl: '.prev',
        },
    });
});


document.addEventListener('DOMContentLoaded', function () {
    var menu = [];
    jQuery(".swiper-slide").each(function (index) {
        menu.push(jQuery(this).find(".slide-inner").attr("data-text"));
    });
    var interleaveOffset = 0.5;
    var swiperOptions = {
        loop: !1,
        speed: 1000,
        slidePerview: 1,
        parallax: !0,
        autoplay: { delay: 20000, disableOnInteraction: !1 },
        watchSlidesProgress: !0,
        pagination: { el: ".swiper-pagination", clickable: !0 },
        navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
        on: {
            progress: function () {
                var swiper = this;
                for (var i = 0; i < swiper.slides.length; i++) {
                    var slideProgress = swiper.slides[i].progress;
                    var innerOffset = swiper.width * interleaveOffset;
                    var innerTranslate = slideProgress * innerOffset;
                    swiper.slides[i].querySelector(".slide-inner").style.transform =
                        "translate3d(" + innerTranslate + "px, 0, 0)";
                }
            },
            touchStart: function () {
                var swiper = this;
                for (var i = 0; i < swiper.slides.length; i++) {
                    swiper.slides[i].style.transition = "";
                }
            },
            setTransition: function (speed) {
                var swiper = this;
                for (var i = 0; i < swiper.slides.length; i++) {
                    swiper.slides[i].style.transition = speed + "ms";
                    swiper.slides[i].querySelector(".slide-inner").style.transition =
                        speed + "ms";
                }
            },
        },
    };
    var swiper = new Swiper(".swiper-container", swiperOptions);

});

// product details svript

$(window).scroll(function () {
    const header = $('#main-header');
    if ($(this).scrollTop() > 50) {
        // STYLE WHEN STICKY
        header.addClass('bg-[#0b0b0b] shadow-xl border-b border-[#1E2529]')
            .removeClass('bg-transparent');
    } else {
        // ORIGINAL STYLE
        header.addClass('bg-transparent')
            .removeClass('bg-[#0b0b0b] shadow-xl border-b border-[#1E2529]');
    }
});

// 1. Define the function globally immediately
window.toggleModal = function () {
    const overlay = document.getElementById('modal-overlay');
    const container = document.getElementById('modal-container');

    if (!overlay || !container) {
        console.error("Modal elements not found in DOM!");
        return;
    }

    const isHidden = overlay.classList.contains('hidden');

    if (isHidden) {
        console.log("Opening Modal...");
        overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        // Small delay to trigger Tailwind transitions
        setTimeout(() => {
            overlay.classList.add('opacity-100');
            container.classList.add('scale-100');
            container.classList.remove('scale-95');
        }, 10);
    } else {
        console.log("Closing Modal...");
        overlay.classList.remove('opacity-100');
        container.classList.add('scale-95');
        container.classList.remove('scale-100');
        setTimeout(() => {
            overlay.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }, 300);
    }
};

// 2. Attach the click listener safely
document.addEventListener('click', function (e) {
    const overlay = document.getElementById('modal-overlay');
    const container = document.getElementById('modal-container');

    // Check if the user clicked EXACTLY the overlay (the dark part)
    // and NOT the container (the white/dark box)
    if (e.target === overlay && !container.contains(e.target)) {
        window.toggleModal();
    }
});


// Cart page Specification and warranty popups
document.addEventListener('DOMContentLoaded', () => {
    // --- SPECIFICATION MODAL LOGIC ---
    window.toggleSpecModal = function (btn) {
        const parent = btn.closest('.cart-box');
         const sOverlay = parent.querySelector('.spec-modal-overlay');
        const sContainer = parent.querySelector('.spec-modal-container');

        if (!sOverlay || !sContainer) return;

        const isHidden = sOverlay.classList.contains('hidden');
        
        // const isHidden = sOverlay.classList.contains('hidden');
        if (isHidden) {
            sOverlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                sOverlay.classList.add('opacity-100');
                sContainer.classList.add('scale-100');
                sContainer.classList.remove('scale-95');
            }, 10);
        } else {
            sOverlay.classList.remove('opacity-100');
            sContainer.classList.add('scale-95');
            sContainer.classList.remove('scale-100');
            setTimeout(() => {
                sOverlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 300);
        }
    }

    // --- WARRANTY MODAL LOGIC ---
window.toggleWarrantyModal = function (btn) {

    // find the parent product/cart block
    const parent = btn.closest('.cart-box');

    const wOverlay = parent.querySelector('.warranty-modal-overlay');
    const wContainer = parent.querySelector('.warranty-modal-container');

    if (!wOverlay || !wContainer) return;

    const isHidden = wOverlay.classList.contains('hidden');

    if (isHidden) {
        wOverlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => {
            wOverlay.classList.add('opacity-100');
            wContainer.classList.add('scale-100');
            wContainer.classList.remove('scale-95');
        }, 10);

    } else {

        wOverlay.classList.remove('opacity-100');
        wContainer.classList.add('scale-95');
        wContainer.classList.remove('scale-100');

        setTimeout(() => {
            wOverlay.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }, 300);
    }
};

// --- CLOSE WARRANTY MODAL ---
window.closeWarrantyModal = function (overlay) {

    const container = overlay.querySelector('.warranty-modal-container');

    overlay.classList.remove('opacity-100');
    container.classList.add('scale-95');
    container.classList.remove('scale-100');

    setTimeout(() => {
        overlay.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }, 300);
}

// --- CLOSE SPEC MODAL ---
window.closeSpecModal = function (overlay) {

    const container = overlay.querySelector('.spec-modal-container');

    overlay.classList.remove('opacity-100');
    container.classList.add('scale-95');
    container.classList.remove('scale-100');

    setTimeout(() => {
        overlay.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }, 300);
}


// --- OUTSIDE CLICK CLOSE ---
window.addEventListener('click', (e) => {
    // if (e.target === sOverlay) toggleSpecModal();

    if (e.target.classList.contains('warranty-modal-overlay')) {
        closeWarrantyModal(e.target);
    }

    if (e.target.classList.contains('spec-modal-overlay')) {
        closeSpecModal(e.target);
    }

});


// --- WARRANTY SELECT ---
window.selectWarranty = async function (selectedElement) {

    // scope only inside the current modal
    const modal = selectedElement.closest('.warranty-modal-overlay');
    const cards = modal.querySelectorAll('.warranty-card');

    const cartId = selectedElement.dataset.cartid;
    let warrantyId = selectedElement.dataset.warrantyid;

    // Check if already selected
    const isAlreadySelected = selectedElement.classList.contains('border-2');

    // reset all cards
    cards.forEach(card => {
        card.classList.remove('border-2','border-[#2A7CFF]','bg-[#161B22]');
        card.classList.add('border','border-gray-800','bg-[#282B3450]');
        const icon = card.querySelector('.check-icon');
        if (icon) icon.classList.add('hidden');
    });
    
    if (isAlreadySelected) {
        warrantyId = ''; // remove warranty
    } else {
        // activate selected
        selectedElement.classList.add('border-2','border-[#2A7CFF]','bg-[#161B22]');
        selectedElement.classList.remove('border','border-gray-800','bg-[#282B3450]');

        const activeIcon = selectedElement.querySelector('.check-icon');
        if (activeIcon) activeIcon.classList.remove('hidden');
    }

    // update warranty
    const response = await fetch(`/updateProductWarranty?cartId=${cartId}&warrantyId=${warrantyId}`);
    const data = await response.json();

    if (data.status) {
        updateCartSummary();
        // Update the link text dynamically
        const warrantyLink = document.querySelector(`a[data-cartid="${cartId}"]`); // add data-cartid to your link
        if (warrantyLink) {
            if (warrantyId) {
                // find warranty name from the selected element
                console.log(selectedElement.dataset);
                const warrantyName = selectedElement.dataset.warrantyname || 'Warranty'; 
                warrantyLink.innerHTML = `<i class="h-[20px] w-[20px] rounded-full block bg-[#262B35] flex flex-center items-center text-center justify-center text-[14px] tracking-[1px] cursor-pointer">+</i> Warranty: ${warrantyName}`;
            } else {
                warrantyLink.innerHTML = `<i class="h-[20px] w-[20px] rounded-full block bg-[#262B35] flex flex-center items-center text-center justify-center text-[14px] tracking-[1px] cursor-pointer">+</i> Choose Your Warranty Plan`;
            }
        }
    }
};
});

// Warranty script end.

// Cart page script




document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('billing-toggle');
    const list = document.getElementById('address-list-container');
    const overlay = document.getElementById('addr-modal-overlay');
    const modal = document.getElementById('addr-modal-container');

    // Stop if not on this page
    if (!toggle || !list || !overlay || !modal) {
        return;
    }


    if (toggle) {
        toggle.addEventListener('change', () => {
            list.classList.toggle('hidden', toggle.checked);
        });
    }

    window.openModal = () => {
        overlay.classList.remove('hidden');
        // FIX: Lock the body to prevent double scrollbars
        document.body.style.overflow = 'hidden';

        setTimeout(() => {
            overlay.classList.add('opacity-100');
            modal.classList.add('scale-100', 'opacity-100');
        }, 10);
    };

    window.closeModal = () => {
        overlay.classList.remove('opacity-100');
        modal.classList.remove('scale-100', 'opacity-100');
        setTimeout(() => {
            overlay.classList.add('hidden');
            // FIX: Restore scroll when closed
            document.body.style.overflow = 'auto';
        }, 300);
    };

    document.addEventListener('click', (e) => {
        if (e.target.closest('#open-address-modal')) openModal();
        if (e.target.closest('#close-modal-x') || e.target.closest('#discard-btn') || e.target === overlay) closeModal();
    });
});

// FILTER BLOCK JS
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('brand-search');
    const categorySelect = document.getElementById('category-select');
    const brandItems = document.querySelectorAll('.brand-item');

    if (!searchInput && !categorySelect && brandItems.length === 0) return;

    const runFilters = () => {
        const query = searchInput.value.toLowerCase();
        const selectedCat = categorySelect ? categorySelect.value : 'all';

        brandItems.forEach(item => {
            const name = item.getAttribute('data-name').toLowerCase();
            const cat = item.getAttribute('data-category');

            const searchMatch = name.includes(query);
            const catMatch = (selectedCat === 'all' || cat === selectedCat);

            if (searchMatch && catMatch) {
                item.style.display = 'flex';
                item.classList.add('animate-fade-in');
            } else {
                item.style.display = 'none';
            }
        });
    };
    if (searchInput) {
        searchInput.addEventListener('input', runFilters);
    }
    if (categorySelect) {
        categorySelect.addEventListener('change', runFilters);
    }
});
// FILTER BLOCK JS ends

// /*******************Product variant selection script starts**************************************/
document.addEventListener('DOMContentLoaded', () => {
    const swiperInstance = document.querySelector('.singleprdswiper')?.swiper;
    document.querySelectorAll('.variant-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const attributeId = btn.dataset.attrId;
            const attributeValueId = btn.dataset.valueId;
            const productId = btn.dataset.productId;
            const clickedIndex = parseInt(btn.dataset.attrIndex);

            // Activate clicked button
            btn.classList.remove('border', 'border-[#ffffff10]', 'bg-[#161B22]', 'text-gray-400', 'hover:border-[#ffffff30]');
            btn.classList.add('active', 'border-1', 'border-[#2A7CFF]', 'bg-[#2A7CFF]/10', 'text-white', 'font-medium');

            // Deactivate siblings
            const siblings = btn.parentElement.querySelectorAll('.variant-btn');
            siblings.forEach(function (sibling) {
                if (sibling !== btn) {
                    sibling.classList.remove('active', 'border-1', 'border-[#2A7CFF]', 'bg-[#2A7CFF]/10', 'text-white', 'font-medium');
                    sibling.classList.add('border', 'border-[#ffffff10]', 'bg-[#161B22]', 'text-gray-400', 'hover:border-[#ffffff30]');
                }
            });

            // Disable all lower levels
            document.querySelectorAll('.variant-btn').forEach(function (button) {
                const btnIndex = parseInt(button.dataset.attrIndex);
                if (btnIndex > clickedIndex) {
                    button.disabled = true;
                    button.classList.remove('active', 'border-1', 'border-[#2A7CFF]', 'bg-[#2A7CFF]/10', 'text-white', 'font-medium');
                    button.classList.add('border', 'border-[#ffffff10]', 'bg-[#161B22]', 'text-gray-400', 'hover:border-[#ffffff30]');
                    button.style.opacity = '0.3';
                    button.style.cursor = 'not-allowed';
                }
            });

            fetch(`/get-variants-by-value?attribute_id=${attributeId}&value_id=${attributeValueId}&product_id=${productId}&selectedAttributes=${encodeURIComponent(JSON.stringify(getSelectedAttributes()))}`)
                .then(response => response.json())
                .then(response => {

                    if (response.data && response.data.length > 0) {

                        let grouped = {};

                        response.data.forEach(function (v) {
                            const selector = `.variant-btn[data-attr-id="${v.attribute_id}"][data-value-id="${v.value_id}"]`;
                            const button = document.querySelector(selector);
                            const btnIndex = button.dataset.attrIndex;

                            if (!grouped[btnIndex]) grouped[btnIndex] = [];
                            grouped[btnIndex].push(button);
                        });

                        Object.keys(grouped).forEach(function (level) {

                            const buttons = grouped[level];

                            const hasActive = buttons.some(b => b.classList.contains('active'));

                            if (!hasActive && buttons.length > 0) {
                                const firstBtn = buttons[0];
                                firstBtn.classList.add('active', 'border-1', 'border-[#2A7CFF]', 'bg-[#2A7CFF]/10', 'text-white', 'font-medium');
                                firstBtn.classList.remove('border', 'border-[#ffffff10]', 'bg-[#161B22]', 'text-gray-400', 'hover:border-[#ffffff30]');
                            }

                            buttons.forEach(function (b) {
                                b.disabled = false;
                                b.style.opacity = '1';
                                b.style.cursor = 'pointer';
                            });

                        });
                    }

                    document.querySelectorAll('.variant-btn.active').forEach(function (activeBtn) {
                        const btnIndex = parseInt(activeBtn.dataset.attrIndex);
                        if (btnIndex > clickedIndex) {
                            activeBtn.click();
                        }
                    });

                    getVarientDetails(productId);
                })
                .catch(err => console.error('AJAX error:', err));
        });

    });

    // Get Variant Details
    function getVarientDetails(productId) {
        let selectedAttributes = {};
        document.querySelectorAll('.variant-list .variant-btn.active').forEach(function (btn) {
            selectedAttributes[btn.dataset.attrId] = btn.dataset.valueId;
        });
        fetch(`/getVarientDetails?productId=${productId}&selectedAttributes=${encodeURIComponent(JSON.stringify(selectedAttributes))}`)
            .then(response => response.json())
            .then(response => {
                if (response.success) {
                    if (response.data.image && swiperInstance) {
                        const newUrl = `/product/${response.data.slug}/${response.data.variant_sku}`;
                        // window.history.replaceState({}, '', newUrl);
                        window.location.href = "/product/" +response.data.slug + "/" +response.data.variant_sku;
                    }

                    const mainPrice = document.querySelector('.price span.main-price');
                    if(mainPrice) mainPrice.textContent = response.data.price;
                    document.querySelector('.offer-price').textContent = response.data.offer_price;
                    document.querySelector('.variant-title').textContent = response.data.title;
                    document.getElementById('stock_id').value = response.data.variant_id;

                    if (response.data.availableQty <= 0) {
                        // document.querySelector(".out-of-stock-block").style.display = 'grid';
                        // document.querySelector(".add-to-cart-block").style.display = 'none';
                    } else {
                        // document.querySelector(".out-of-stock-block").style.display = 'none';
                        // document.querySelector(".add-to-cart-block").style.display = 'grid';
                        if(response.data.cartQty > 0){
                            document.querySelector(".add-to-cart").classList.add('hidden');
                            document.querySelector(".counter-wrapper").classList.remove('hidden');
                            const qtyInput = document.querySelector('.qty-input');
                            if (qtyInput) qtyInput.value = response.data.cartQty;
                        }else{
                            document.querySelector(".counter-wrapper").classList.add('hidden');
                            document.querySelector(".add-to-cart").classList.remove('hidden');
                        }
                    }

                    updateWishlistUI(productId, response.data.variant_id);
                }
            });
    }

    // Update wishlist button UI based on current variant selection
    function updateWishlistUI(productId, stockId) {
        fetch(`/wishlist/check?product_id=${productId}&stock_id=${stockId}`)
            .then(res => res.json())
            .then(data => {
                const btn = document.getElementById('wishlist-button');
                const svg = btn.querySelector('svg');

                if (data.status) {
                    btn.classList.add('text-red-500');
                    btn.classList.remove('text-white');

                    svg.setAttribute('fill', 'currentColor');
                } else {
                    btn.classList.remove('text-red-500');
                    btn.classList.add('text-white');

                    svg.setAttribute('fill', 'none');
                }

                btn.setAttribute('data-stock-id', stockId);
            });
    }

    // Get selected attributes
    function getSelectedAttributes() {
        let selected = {};
        document.querySelectorAll('.variant-btn.active').forEach(function (btn) {
            selected[btn.dataset.attrId] = btn.dataset.valueId;
        });
        return selected;
    }

    // Load default variant
    const mainProductInput = document.getElementById('main_product_id');
    if (mainProductInput) {
        // getVarientDetails(mainProductInput.value);
    }
    
    // select and trigger the click event default selected variant
    const stockInput = document.getElementById('stock_id');
    const selectedStockId = stockInput ? stockInput.value : null;

    const firstBtn = document.querySelector('.variant-group .variant-btn');
    const activeBtn = document.querySelector('.variant-group .variant-btn.active');
    

    /*if (selectedStockId && activeBtn) {
        activeBtn.click();
    } else if (firstBtn) {
        firstBtn.click();
    }*/
});

// /**************************************Product variant selection script ends**************************************/ //

// theme-script.js starts
const trigger = document.getElementById("menuTrigger");
const overlay = document.getElementById("megaOverlay");
if (trigger && overlay) {
    trigger.addEventListener("click", () => {
        trigger.classList.toggle("active");
        overlay.classList.toggle("open");

        if (overlay.classList.contains("open")) {
            document.body.style.overflow = "hidden";
        } else {
            document.body.style.overflow = "auto";
        }
    });
}

var swiper = new Swiper(".cateswiper", {
    slidesPerView: 7,
    spaceBetween: 50,
    grabCursor: true,
    pagination: {
        enabled: true,
        el: ".swiper-pagination",
        dynamicBullets: true,
    },
    loop: true,
    speed: 5000,
    parallax: false,
    navigation: {
        prevEl: ".swiper-button-prev",
        nextEl: ".swiper-button-next",
    },
    autoplay: { delay: 3000, disableOnInteraction: false },
    allowTouchMove: true,
    breakpoints: {
        320: { slidesPerView: 3, spaceBetween: 15 },
        640: { slidesPerView: 3, spaceBetween: 15 },
        768: { slidesPerView: 5, spaceBetween: 15 },
        1024: { slidesPerView: 7, spaceBetween: 15 },
        1280: { slidesPerView: 5, spaceBetween: 15 },
        1300: { slidesPerView: 6, spaceBetween: 15 },
        1366: { slidesPerView: 7, spaceBetween: 15 },
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
        320: { slidesPerView: 1, spaceBetween: 15 },
        640: { slidesPerView: 2, spaceBetween: 15 },
        768: { slidesPerView: 2, spaceBetween: 15 },
        1024: { slidesPerView: 3, spaceBetween: 15 },
        1280: { slidesPerView: 3, spaceBetween: 15 },
        1300: { slidesPerView: 3, spaceBetween: 15 },
        1366: { slidesPerView: 3, spaceBetween: 15 },
        1400: { slidesPerView: 4, spaceBetween: 15 },
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
    loop: true,
    speed: 5000,
    navigation: {
        prevEl: ".swiper-button-prev",
        nextEl: ".swiper-button-next",
    },
    autoplay: { delay: 3000, disableOnInteraction: false },
    allowTouchMove: true,
    breakpoints: {
        320: { slidesPerView: 2, spaceBetween: 5 },
        640: { slidesPerView: 2, spaceBetween: 15 },
        768: { slidesPerView: 2, spaceBetween: 15 },
        1024: { slidesPerView: 3, spaceBetween: 15 },
        1280: { slidesPerView: 3, spaceBetween: 15 },
        1300: { slidesPerView: 3, spaceBetween: 15 },
        1366: { slidesPerView: 3, spaceBetween: 15 },
        1400: { slidesPerView: 5, spaceBetween: 15 },
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
        320: { slidesPerView: 1, spaceBetween: 15 },
        640: { slidesPerView: 1, spaceBetween: 15 },
        768: { slidesPerView: 2, spaceBetween: 15 },
        1024: { slidesPerView: 3, spaceBetween: 15 },
        1280: { slidesPerView: 3, spaceBetween: 15 },
        1300: { slidesPerView: 3, spaceBetween: 15 },
        1366: { slidesPerView: 3, spaceBetween: 15 },
        1400: { slidesPerView: 4, spaceBetween: 15 },
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
        320: { slidesPerView: 1, spaceBetween: 15 },
        640: { slidesPerView: 1, spaceBetween: 15 },
        768: { slidesPerView: 2, spaceBetween: 15 },
        1024: { slidesPerView: 2, spaceBetween: 15 },
        1280: { slidesPerView: 2, spaceBetween: 15 },
        1300: { slidesPerView: 2, spaceBetween: 15 },
        1366: { slidesPerView: 2, spaceBetween: 15 },
        1400: { slidesPerView: 2, spaceBetween: 15 },
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

// 1. Initialize Lightbox
const lightbox = GLightbox({
    selector: '.glightbox',
    touchNavigation: true,
});

document.addEventListener('DOMContentLoaded', function () {
    // 2. Identify your background player
    const bgPlayer = document.getElementById('bgPlayer');

    // 3. Listen for Lightbox Opening
    lightbox.on('open', () => {
        // Send 'pauseVideo' command to the YouTube Iframe
        bgPlayer.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
    });

    // 4. Listen for Lightbox Closing
    lightbox.on('close', () => {
        const bgPlayer = document.getElementById('bgPlayer');

        if (bgPlayer && bgPlayer.contentWindow) {
            // Send 'playVideo' command to resume background playback
            bgPlayer.contentWindow.postMessage('{"event":"command","func":"playVideo","args":""}', '*');
        }
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
    if (activeSlide) {
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
                // console.log("Avatar updated to:", firstLetter); // Check your console (F12)
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



window.toggleBilling = function() {
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

window.closeAllMobileSystems = function() {
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

window.togglePassword = function(buttonElement) {
    // Find the relative container (the 'group' div)
    const container = buttonElement.closest(".relative");
    const input = container.querySelector("input");
    const eyeIcon = container.querySelector(".eye-icon");
    const eyeOffIcon = container.querySelector(".eye-off-icon");

    const isPassword = input.type === "password";

    // Toggle Type
    input.type = isPassword ? "text" : "password";

    // Toggle Icons
    if (isPassword) {
        eyeIcon.classList.add("hidden");
        eyeOffIcon.classList.remove("hidden");
    } else {
        eyeIcon.classList.remove("hidden");
        eyeOffIcon.classList.add("hidden");
    }
}

window.toggleMobileMenu = function() {
    const panel = document.getElementById("mobile-side-panel");
    const overlay = document.getElementById("mobile-menu-overlay");
    const isOpen = panel.classList.contains("translate-x-0");

    if (!isOpen) {
        panel.classList.replace("-translate-x-full", "translate-x-0");
        overlay.classList.replace("opacity-0", "opacity-100");
        overlay.classList.replace("pointer-events-none", "pointer-events-auto");
        document.body.style.overflow = "hidden";
    } else {
        panel.classList.replace("translate-x-0", "-translate-x-full");
        overlay.classList.replace("opacity-100", "opacity-0");
        overlay.classList.replace("pointer-events-auto", "pointer-events-none");
        document.body.style.overflow = "";
    }
}

window.toggleMobileWidget = function(panelId, event) {
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
document.addEventListener('click', function (e) {
    // Check if the clicked element is the overlay or a panel's backdrop
    if (e.target.id === 'sidebar-overlay' || e.target.getAttribute('data-backdrop') === 'true') {
        closeAllMobileSystems();
    }
});



window.handleSortClick = function (selectedBtn) {
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
// theme-script ends


    async function addToCart(productId, variantId, quantity, mode="set") {
        try {
            const res = await fetch(`/addProductToCart?productId=${productId}&variantId=${variantId}&quantity=${quantity}&mode=${mode}`);
            const response = await res.json();

            if (response.success) {
                toastr.success(response.message, 'Success');
                document.getElementById('total-cart-count-top').innerText = response.totalCartItemsCount;
                
            } else {
                toastr.error(response.message, 'Error');
            }

            const outStockBlocks = document.querySelectorAll('.out-of-stock-block');
            const addCartBlocks = document.querySelectorAll('.add-to-cart-block');

            if (response.availableQty <= 0) {
                if(outStockBlocks) outStockBlocks.forEach(el => el.classList.remove('!hidden'));
                if(addCartBlocks) addCartBlocks.forEach(el => el.classList.add('!hidden'));
            } else {
                if(outStockBlocks) outStockBlocks.forEach(el => el.classList.add('!hidden'));
                if(addCartBlocks) addCartBlocks.forEach(el => el.classList.remove('!hidden'));

                const addToCartBtn = document.querySelector(".add-to-cart");
                const counterWrapper = document.querySelector(".counter-wrapper");
                if(!counterWrapper){
                    location.reload();
                }

                if(response.cartQty > 0){
                    if (addToCartBtn) addToCartBtn.classList.add('hidden');
                    if (counterWrapper) counterWrapper.classList.remove('hidden');
                    const qtyInput = counterWrapper?.querySelector('.qty-input');
                    if (qtyInput) qtyInput.value = response.cartQty;
                    if (counterWrapper) updateQtyIcons(counterWrapper, response.cartQty);

                }else{
                    if (addToCartBtn) addToCartBtn.classList.remove('hidden');
                    if (counterWrapper) counterWrapper.classList.add('hidden');
                }
            }
            return response; // return the full response
        } catch (error) {
            toastr.error(error, 'Error');
            return { success: false, availableQty: 0 };
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
    $('.add-to-cart').on('click', function () {
        const productId = document.getElementById('main_product_id')?.value;
        const variantId = document.getElementById('stock_id')?.value;
        addToCart(productId, variantId, 1, 'increment');
    });

    // toggle minus and trash icon
     document.querySelectorAll('.product-item').forEach(container => {
        const input = container.querySelector('.qty-input');
        const iconWrapper = container.querySelector('.icon-wrapper');

        let minusBtn = null;
        let trashBtn = null;

        if(iconWrapper) {
            minusBtn = iconWrapper.querySelector('.minus-btn');
            trashBtn = iconWrapper.querySelector('.trash-btn');
        }

        const decrementBtn = container.querySelector('.decrement-btn');

        if(input){
            let currentVal = parseInt(input.value);

            if (currentVal === 1) {
                if(minusBtn) minusBtn.classList.add('hidden');
                if(trashBtn) trashBtn.classList.remove('hidden');
                decrementBtn.classList.add('hover:text-red-500', 'hover:bg-red-500/10');
                decrementBtn.classList.remove('hover:bg-[#2A7CFF]', 'hover:text-white');

            } else if (currentVal > 1) {
                trashBtn.classList.add('hidden');
                minusBtn.classList.remove('hidden');

                decrementBtn.classList.remove('hover:text-red-500', 'hover:bg-red-500/10');
                decrementBtn.classList.add('hover:bg-[#2A7CFF]', 'hover:text-white');
            }
        }
    });

    // Add items to cart
    window.updateMultiQty = async function (btn, change) {
        // Find the container for THIS specific product
        const container = btn.closest('.product-item');
        const input = container.querySelector('.qty-input');
        const iconWrapper = container.querySelector('.icon-wrapper');
        const minusBtn = iconWrapper.querySelector('.minus-btn');
        const trashBtn = iconWrapper.querySelector('.trash-btn');
        const decrementBtn = container.querySelector('.decrement-btn');
        const productId = container.dataset.productId;
        const variantId = container.dataset.variantId;
        const cartId = container.dataset.cartId;
        const cartItemBox = btn.closest('.product-cart-item');
        const cartPrice = cartItemBox ? cartItemBox.querySelector('.cart_price') : null;
        const cartOfferPrice = cartItemBox ? cartItemBox.querySelector('.cart_offer_price') : null;
        const addCartBtn = document.querySelector(".add-to-cart");
        const outStockBlocks = document.querySelectorAll('.out-of-stock-block');
        const addCartBlocks = document.querySelectorAll('.add-to-cart-block');
        
        let currentVal = parseInt(input.value);

        // DELETE LOGIC: If qty is 1 and user clicks minus
        if (currentVal === 1 && change === -1) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Are you sure you want to remove this item from the cart?',
                icon: 'warning',
                width: '320px', 
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'
            }).then(async(result) => {
                if (!result.isConfirmed) {
                    return;
                }
                try {
                    const res = await fetch(`/removeCartItem/${cartId}`);
                    const response = await res.json();

                    if (response.status) {
                        if(cartItemBox){
                            cartItemBox.remove();
                        }
                        container.remove();
                        if(addCartBtn) addCartBtn.classList.remove("hidden");
                        toastr.success(response.message, 'Success');
                        updateCartSummary();
                        // $("#price-btn-block").load(location.href + " #price-btn-block>*", "");
                        $("#main-cart-section").load(location.href + " #main-cart-section>*", "", function () {
                            document.querySelectorAll('.product-item').forEach(container => {
                                const input = container.querySelector('.qty-input');
                                const qty = parseInt(input.value) || 1;
                                updateQtyIcons(container, qty);
                            });
                        });
                    } else {
                        toastr.error(response.message, 'Error');
                    }
                } catch (error) {
                    toastr.error('Something went wrong', 'Error');
                }
            });
            return;
        }
        
        let newVal = currentVal + change;
        if (newVal < 1) newVal = 1;

        const response = await addToCart(productId, variantId, newVal, 'set');
        if (response.success) {
            input.value = newVal;
            updateCartSummary();

            if(cartOfferPrice) cartOfferPrice.textContent = response.offerPrice;
            if(cartPrice) cartPrice.textContent = response.price;

            updateQtyIcons(container, newVal);

            // Pulse animation
            input.classList.add('text-[#2A7CFF]', 'scale-110');
            setTimeout(() => input.classList.remove('text-[#2A7CFF]', 'scale-110'), 150);

            if(response.availableQty == 0) {
                if(outStockBlocks) outStockBlocks.forEach(el => el.classList.remove('!hidden'));
                if(addCartBlocks) addCartBlocks.forEach(el => el.classList.add('!hidden'));
            }
        }
    };


    window.updateQtyIcons = function(container, qty) {
        const iconWrapper = container.querySelector('.icon-wrapper');
        const minusBtn = iconWrapper.querySelector('.minus-btn');
        const trashBtn = iconWrapper.querySelector('.trash-btn');
        const decrementBtn = container.querySelector('.decrement-btn');

        if (qty <= 1) {
            minusBtn.classList.add('hidden');
            trashBtn.classList.remove('hidden');

            decrementBtn.classList.add('hover:text-red-500', 'hover:bg-red-500/10');
            decrementBtn.classList.remove('hover:bg-[#2A7CFF]', 'hover:text-white');
        } else {
            trashBtn.classList.add('hidden');
            minusBtn.classList.remove('hidden');

            decrementBtn.classList.remove('hover:text-red-500', 'hover:bg-red-500/10');
            decrementBtn.classList.add('hover:bg-[#2A7CFF]', 'hover:text-white');
        }
    }

    window.updateCartSummary = async function () {
        try {
            const response = await fetch('/getCartSummary');
            const data = await response.json();
            if (data.status) {
                if(document.getElementById('cart-subtotal')) document.getElementById('cart-subtotal').innerText = formatPrice(data.sub_total);
                if(document.getElementById('cart-discount')) document.getElementById('cart-discount').innerText = formatPrice(data.discount_sum);
                if(document.getElementById('cart-tax')) document.getElementById('cart-tax').innerText = formatPrice(data.tax);
                if(document.getElementById('cart-total')) document.getElementById('cart-total').innerText = formatPrice(data.total);
                if(document.getElementById('cart-count')) document.getElementById('cart-count').innerText =  `(${data.cart_count || 0})`;
                let shippingElement = document.getElementById('cart-shipping');
                if(shippingElement){
                document.getElementById('cart-shipping').innerText = formatPrice(data.shipping);
                }
                
                let warrantyElement = document.getElementById('cart-warranty');
                if(warrantyElement) {
                    document.getElementById('cart-warranty').innerText = formatPrice(data.warranty_sum);
                }
                
                document.getElementById('total-cart-count-top').innerText = data.cart_count;
                let couponDiscountElement = document.getElementById('coupon_discount');
                if(couponDiscountElement) {
                document.getElementById('coupon_discount').innerHTML = formatPrice(data.couponDiscount);
                }

                const warrantySection = document.getElementById('warranty-section');
                if (data.warranty_sum > 0) {
                    if (warrantySection) {
                        warrantySection.style.display = 'list-item'; // or 'block' depending on your layout
                    } else {
                        // optionally create the li dynamically if it doesn't exist
                    }
                } else if (warrantySection) {
                    warrantySection.style.display = 'none';
                }
                
            }
        } catch (err) {
            console.error('Error fetching cart summary', err);
        }
    }
});

let originalValues = {};
window.toggleEditMode = function() {
    const inputs = document.querySelectorAll(".profile-input");
    const saveContainer = document.getElementById("save-button-container");
    const editTexts = document.querySelectorAll(".edit-text-sync");
    const editIcons = document.querySelectorAll(".edit-icon-sync");
    const editButtons = document.querySelectorAll('[onclick="toggleEditMode()"]');

    // Check current state based on first input
    const isReadOnly = inputs[0].readOnly;

    if (isReadOnly) {
        inputs.forEach((input) => {
            originalValues[input.name] = input.value;
        });
    }

    inputs.forEach((input) => {
        input.readOnly = !isReadOnly;
        if (isReadOnly) {
            input.classList.remove("read-only:opacity-70", "cursor-default");
            input.classList.add("cursor-text");
        } else {
            input.classList.add("read-only:opacity-70", "cursor-default");
            input.classList.remove("cursor-text");
        }
    });

    if (isReadOnly) {
        // --- ENTERING EDIT MODE ---
        saveContainer.classList.remove("hidden");
        saveContainer.classList.add("flex");

        editTexts.forEach((el) => (el.innerText = "DISCARD CHANGES"));

        // Change Icons to "X" (Close)
        editIcons.forEach((el) => {
            el.innerHTML =
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />';
        });

        // Add a red "Warning" style to the buttons
        editButtons.forEach((btn) => {
            btn.classList.add("text-red-500", "border-[#c0392b80]", "bg-[#c0392b50]");
            btn.classList.remove("bg-[#252B31]");
        });

        inputs[0].focus();
    } else {
        
        inputs.forEach((input) => {
            if (originalValues[input.name] !== undefined) {
                input.value = originalValues[input.name];
            }
        });
        
        saveContainer.classList.add("hidden");
        saveContainer.classList.remove("flex");

        editTexts.forEach((el) => (el.innerText = "EDIT PROFILE"));

        // Change Icons back to "Pencil" (Edit)
        editIcons.forEach((el) => {
            el.innerHTML =
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />';
        });

        // Revert styles
        editButtons.forEach((btn) => {
            btn.classList.remove("text-red-500", "border-[#c0392b80]", "bg-[#c0392b50]");
            btn.classList.add("bg-[#252B31]");
        });
    }
}

window.toggleSidePanel = function () {
    const panel = document.getElementById("account-side-panel");
    const overlay = document.getElementById("side-panel-overlay");

    const isOpen = panel.classList.contains("translate-x-0");

    if (!isOpen) {
        // Open
        panel.classList.remove("translate-x-full");
        panel.classList.add("translate-x-0");
        overlay.classList.remove("opacity-0", "pointer-events-none");
        overlay.classList.add("opacity-100", "pointer-events-auto");
        document.body.style.overflow = "hidden"; // Prevent background scroll
    } else {
        // Close
        panel.classList.add("translate-x-full");
        panel.classList.remove("translate-x-0");
        overlay.classList.add("opacity-0", "pointer-events-none");
        overlay.classList.remove("opacity-100", "pointer-events-auto");
        document.body.style.overflow = "";
    }
}

window.toggleSearch = function () {
    const menu = document.getElementById("search-mega-menu");
    const overlay = document.getElementById("search-overlay");
    const input = document.getElementById("mega-search-input");

    const isHidden = menu.classList.contains("-translate-y-full");

    if (isHidden) {
        // Show
        menu.classList.remove("-translate-y-full", "invisible");
        menu.classList.add("translate-y-0");
        overlay.classList.remove("opacity-0", "pointer-events-none");
        overlay.classList.add("opacity-100", "pointer-events-auto");

        // Focus the input with a slight delay for animation
        setTimeout(() => input.focus(), 400);
        document.body.style.overflow = "hidden";
    } else {
        // Hide
        menu.classList.add("-translate-y-full");
        menu.classList.remove("translate-y-0");
        setTimeout(() => menu.classList.add("invisible"), 500);
        overlay.classList.add("opacity-0", "pointer-events-none");
        overlay.classList.remove("opacity-100", "pointer-events-auto");
        document.body.style.overflow = "";
    }
}

/* mobile menu */



window.toggleSubMenu = function (id) {
    const sub = document.getElementById(id);
    const caret = document.getElementById("shop-caret");

    if (sub.classList.contains("hidden")) {
        sub.classList.remove("hidden");
        caret.classList.add("rotate-180");
    } else {
        sub.classList.add("hidden");
        caret.classList.remove("rotate-180");
    }
}


// coupon script

const applyCouponButton = document.getElementById('apply_coupon');
const removeCouponButton = document.getElementById('remove_coupon');

window.applyCouponCode = function() {
    const couponCode = document.getElementById('coupon_code').value;
    
    fetch('/apply_coupon_code', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ coupon: couponCode })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            console.log(data);
            toastr.success('Coupon applied!' || 'Success');
            applyCouponButton.classList.add('hidden');
            removeCouponButton.classList.remove('hidden');
            document.getElementById('coupon_discount').innerHTML = data.coupon_discount;
            updateCartSummary();
        } else {
            toastr.error(data.message || 'Something went wrong');
        }
    });
};

window.removeCouponCode = function() {
    const couponCode = document.getElementById('coupon_code').value;
    
    fetch('/remove_coupon_code', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ coupon: couponCode })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            toastr.success('Coupon Removed!' || 'Success');
            removeCouponButton.classList.add('hidden');
            applyCouponButton.classList.remove('hidden');
            document.getElementById('coupon_code').value = '';
            document.getElementById('coupon_discount').innerHTML = '0.00';
            updateCartSummary();
        } else {
            toastr.error(data.message || 'Something went wrong');
        }
    });
};


function formatPrice(amount) {
    if (isNaN(amount)) amount = 0;
    return Number(amount)
        .toFixed(2)           // always 2 decimals
        .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}


// Address map section script

let map;
let marker;

window.initMap = function() {
    let defaultLocation = { lat: 25.2048, lng: 55.2708 }; // Dubai
    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 13,
        center: defaultLocation,
    });

    marker = new google.maps.Marker({
        position: defaultLocation,
        map: map,
        draggable: true
    });

    updateLatLng(defaultLocation);

    // click map
    map.addListener("click", function(event){
        marker.setPosition(event.latLng);
        updateLatLng(event.latLng);
    });

    // drag marker
    marker.addListener("dragend", function(event){
        updateLatLng(event.latLng);
    });
}

window.updateLatLng = function(location){
    let lat = typeof location.lat === "function" ? location.lat() : location.lat;
    let lng = typeof location.lng === "function" ? location.lng() : location.lng;

    document.getElementById("latitude").value = lat;
    document.getElementById("longitude").value = lng;

    console.log("Latitude:", lat);
    console.log("Longitude:", lng);
}


window.getCurrentLocation = function(){
    if(navigator.geolocation){
        navigator.geolocation.getCurrentPosition(function(position){
            let pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            map.setCenter(pos);
            marker.setPosition(pos);
            document.getElementById("latitude").value = pos.lat;
            document.getElementById("longitude").value = pos.lng;
        });
    }
}


// Always scroll to top on page reload
if ('scrollRestoration' in history) {
    history.scrollRestoration = 'manual';
}

window.addEventListener('load', function () {
    if (!window.isAjaxNavigation) {
        window.scrollTo(0, 0);
    }
});

function generateInvoice() {
    var printContents = document.getElementById('invoice-section').innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;

    location.reload();
}

document.addEventListener('DOMContentLoaded', () => {

    const searchInput = document.getElementById('mega-search-input');
    const resultsContainer = document.getElementById('mega-search-results');

    if (!searchInput || !resultsContainer) return;

    let timeout;

    searchInput.addEventListener('input', (e) => {
        const query = e.target.value.trim();

        clearTimeout(timeout);

        if (!query) {
            resultsContainer.innerHTML = '';
            return;
        }

        // Delay to avoid too many requests
        timeout = setTimeout(() => {

            fetch(`/search-products?query=${encodeURIComponent(query)}`, {
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {

                // Clear previous results
                resultsContainer.innerHTML = '';

                if (data.length === 0) {
                    resultsContainer.innerHTML = `<p class="text-white/50 px-4 py-2">No results found</p>`;
                    return;
                }

                data.forEach(product => {
                    const a = document.createElement('a');
                    a.href = product.url; // Product detail URL
                    a.className = "group flex items-center justify-between py-2 px-4 border-b border-white/10 hover:bg-white/[0.02] transition-all";
                    a.innerHTML = `
                        <div class="flex items-center gap-4">
                            <svg class="w-4 h-4 text-gray-600 group-hover:text-[#2A7CFF] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2.5"/></svg>
                            <span class="text-white text-sm font-medium group-hover:text-[#2A7CFF] transition-all">${product.name}</span>
                        </div>
                    `;
                    resultsContainer.appendChild(a);
                });

            })
            .catch(err => console.error('Mega search error:', err));

        }, 300); // 300ms debounce
    });

    // Handle Enter key
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            const query = this.value.trim();
            if (query) {
                // redirect to full products page with search query
                window.location.href = `/products?search=${encodeURIComponent(query)}`;
            }
        }
    });

    window.buyNow = async function(button) {
        const productId = document.getElementById('main_product_id')?.value;
        const variantId = document.getElementById('stock_id')?.value;
        const response = await addToCart(productId, variantId, 1, 'increment');

        if(response.success){
            window.location.href = "/checkout";
        }
    }

});

// Mobile filter code
document.addEventListener('DOMContentLoaded', () => {
    const mobileButton = document.querySelector('.mobile-filter-btn'); // mobile button
    const desktopForm = document.querySelector('form.hidden.xl\\:block'); // desktop form
    const mobileDialog = document.querySelector('#mobile-filters div.form-section'); // mobile dialog form container

    if (mobileButton && desktopForm && mobileDialog) {
        mobileButton.addEventListener('click', (e) => {

            const dialog = document.getElementById('mobile-filters');
            if (dialog && typeof dialog.showModal === 'function') {
                dialog.showModal(); // opens as modal
                // dialog.show(); // opens non-modal (optional)
            }

            e.preventDefault(); // prevent default or framework behavior
            console.log('Mobile button clicked!');

            // Clear existing content in mobile form
            mobileDialog.innerHTML = '';

            // Clone desktop form
            const clone = desktopForm.cloneNode(true);

            // Remove the desktop-only hidden class
            clone.classList.remove('hidden', 'xl:block');

            // Append clone to mobile form
            mobileDialog.appendChild(clone);
            // initialize mobile sliders
            clone.querySelectorAll('.price-filter').forEach(initPriceFilter);

            // Show the mobile dialog
            const dialogElement = mobileDialog.closest('dialog');
            if (dialogElement && typeof dialogElement.showModal === 'function') {
                dialogElement.showModal();
            }
        });
    }

    window.continueGuest = function(){
        document.getElementById('checkout-login-box').style.display = 'none';
    }
});
