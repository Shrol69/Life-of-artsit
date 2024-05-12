const navbar = document.querySelector(".overlay"); // Select the navbar element

window.addEventListener("scroll", function () {
  const scrollY = window.scrollY; // Get scroll position
  const threshold = 50; // Adjust this value to define scroll distance for color change

  if (scrollY > threshold) {
    navbar.classList.add("scrolled"); // Add 'scrolled' class when scrolled past threshold
  } else {
    navbar.classList.remove("scrolled"); // Remove 'scrolled' class when scrolled back up
  }
});
