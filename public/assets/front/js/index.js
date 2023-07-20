function openClass(evt, className) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(className).style.display = "block";
  evt.currentTarget.className += " active";
}

document.getElementById("defaultOpen").click();

// document.getElementById("Open").click();



document.addEventListener("DOMContentLoaded", function (event) {
  function OTPInput() {
    const inputs = document.querySelectorAll('#otp > *[id]');
    for (let i = 0; i < inputs.length; i++) {
      inputs[i].addEventListener('keydown', function (event) {
        if (event.key === "Backspace") {
          inputs[i].value = ''; if (i !== 0) inputs[i - 1].focus();
        }
        else {
          if (i === inputs.length - 1 && inputs[i].value !== '') {
            return true;
          }
          else if (event.keyCode > 47 && event.keyCode < 58) {
            inputs[i].value = event.key;
            if (i !== inputs.length - 1) inputs[i + 1].focus();
            event.preventDefault();
          }
          else if (event.keyCode > 64 && event.keyCode < 91) {
            inputs[i].value = String.fromCharCode(event.keyCode);
            if (i !== inputs.length - 1) inputs[i + 1].focus(); event.preventDefault();
          }
        }
      });
    }
  } OTPInput();
});

const btn = document.querySelectorAll(".sidecircle");
const toggle = document.querySelector(".sidetoggle");
const sidebox = document.querySelector(".sidebox");
for (let index = 0; index < btn.length; index++) {
  const element = btn[index];
  element.onclick = function () {
    if (toggle.className == "sidetoggle") {
      toggle.className = "sidetoggleOpen";
      sidebox.className = 'sideboxOpen'
    } else {
      toggle.className = "sidetoggle";
      sidebox.className = 'sidebox'

    }
  };

}
