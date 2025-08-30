/*
Template Name: Urbix - Admin & Dashboard Template
Author: Pixeleyez
Website: https://pixeleyez.com/
File: Auth init js
*/

// Initialize password toggle functionality
document.addEventListener("DOMContentLoaded", function () {
  initPasswordToggle();
});

function initPasswordToggle() {
  const toggles = document.querySelectorAll(".toggle-password");
  
  if (!toggles.length) return;

  toggles.forEach((toggle) => {
    toggle.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      
      const targetId = this.getAttribute("data-target");
      if (!targetId) return;

      const targetInput = document.getElementById(targetId);
      if (!targetInput) return;

      const icon = this.querySelector("i");
      if (!icon) return;

      if (targetInput.type === "password") {
        targetInput.type = "text";
        icon.classList.remove("ri-eye-off-line");
        icon.classList.add("ri-eye-line");
      } else {
        targetInput.type = "password";
        icon.classList.remove("ri-eye-line");
        icon.classList.add("ri-eye-off-line");
      }
    });
  });
}