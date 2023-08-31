// Execute when the DOM is fully loaded
document.addEventListener("DOMContentLoaded", function() {
    // Initialize arrays to store random X and Y coordinates for each layer
    var randX = [];
    var randY = [];
    var rotation = 0;

    // Function to animate the layers' movement
    function move() {
        for (var x = 0; x < 2; x++) {
            var chemin = ".layer" + x;
            $(chemin).animate({ left: randX[x] + "%", top: randY[x] + "%" }, 10000, randomImage);
        }
    }

    // Function to generate random X and Y coordinates for each layer
    function randomImage() {
        for (var i = 0; i < 2; i++) {
            randX[i] = Math.floor(Math.random() * 100);
            randY[i] = Math.floor(Math.random() * 100);
            if (randX[i] > 80) { randX[i] = 80; }
            if (randY[i] > 80) { randY[i] = 80; }
            if (randX[i] < 20) { randX[i] = 20; }
            if (randY[i] < 20) { randY[i] = 20; }
        }
        move();
    }

    // Function to rotate the layers
    function rotationImage() {
        rotation++;
        for (var i = 0; i < 2; i++) {
            var chemin = ".layer" + i;
            var rotationLien = "rotate(" + rotation + "deg)";
            $(chemin).css("transform", rotationLien);
        }
        if (rotation == 360) { rotation = 0; }
    }

    // Call the rotationImage 
    setInterval(function() {
        rotationImage();
    }, 20);

    // Initial random placement of layers
    randomImage();
});

// Slide home
const sliders = document.querySelectorAll('.service');
let currentSlide = 0;

// Function to slide to the specified service index
function slideService(slideIndex) {
  for (let i = 0; i < sliders.length; i++) {
    sliders[i].classList.add('none');
  }
  sliders[slideIndex].classList.remove('none');
  sliders[slideIndex].style.opacity = 0;
  setTimeout(() => {
    sliders[slideIndex].style.opacity = 1;
  }, 200);
  currentSlide = slideIndex;
}

// Function to move to the next slide
function nextSlide() {
    currentSlide = (currentSlide + 1) % sliders.length;
    slideService(currentSlide);
}

// Call the initial slide and set interval for sliding every 10000ms (10 seconds)
slideService(currentSlide);  
setInterval(nextSlide, 10000);

// Menu burger
const burger = document.querySelector('.menuBurger')
const slideMenu = document.querySelector('.MenuNavResponsive')

// Toggle the burger and slide menu when burger is clicked
burger.onclick = () => {
  burger.classList.toggle("open");
  slideMenu.classList.toggle("openSlide")
};

// Close the slide menu when a menu item is clicked
const slideMenuUl = document.querySelector('.MenuNavResponsive ul')
slideMenuUl.onclick = () => {
  burger.classList.toggle("open");
  slideMenu.classList.toggle("openSlide")
};

// Close the slide menu when the logo is clicked
const logo = document.querySelector('.logo')
logo.onclick = () => {
  burger.classList.toggle("open");
  slideMenu.classList.toggle("openSlide")
};



