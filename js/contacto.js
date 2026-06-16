document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector(".formulario-contacto");
  if (!form) return;

  const inputs = {
    nombre: document.getElementById("nombre"),
    apellido: document.getElementById("apellido"),
    correo: document.getElementById("correo"),
    telefono: document.getElementById("telefono"),
    mensaje: document.getElementById("mensaje"),
  };

  form.addEventListener("submit", (event) => {
    let isValid = true;
    clearErrors();

    if (inputs.nombre.value.trim().length < 2) {
      showError(inputs.nombre, "El nombre debe tener al menos 2 caracteres.");
      isValid = false;
    } else if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(inputs.nombre.value.trim())) {
      showError(inputs.nombre, "El nombre solo puede contener letras.");
      isValid = false;
    }

    if (inputs.apellido.value.trim().length < 2) {
      showError(
        inputs.apellido,
        "El apellido debe tener al menos 2 caracteres.",
      );
      isValid = false;
    } else if (
      !/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(inputs.apellido.value.trim())
    ) {
      showError(inputs.apellido, "El apellido solo puede contener letras.");
      isValid = false;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(inputs.correo.value.trim())) {
      showError(inputs.correo, "Introduce un correo electrónico válido.");
      isValid = false;
    }

    const telValue = inputs.telefono.value.trim();
    if (telValue !== "" && !/^[6789]\d{8}$/.test(telValue)) {
      showError(
        inputs.telefono,
        "El teléfono debe ser un número válido de 9 dígitos.",
      );
      isValid = false;
    }

    if (inputs.mensaje.value.trim().length < 10) {
      showError(
        inputs.mensaje,
        "El mensaje debe tener al menos 10 caracteres.",
      );
      isValid = false;
    }

    if (!isValid) {
      event.preventDefault();
    } else {
      event.preventDefault();
      Swal.fire({
        title: "¡Mensaje enviado!",
        text: "Gracias por ponerte en contacto. Te responderé lo antes posible.",
        icon: "success",
        confirmButtonColor: "#05F26C",
        iconColor: "#05F26C",
        background: "#132426",
        color: "#ffffff",
      }).then(() => {
        form.submit();
      });
    }
  });

  function showError(inputElement, message) {
    inputElement.classList.add("error");
    const parent = inputElement.parentElement;
    const errorSpan = document.createElement("span");
    errorSpan.className = "error-text";
    errorSpan.textContent = message;
    parent.appendChild(errorSpan);
  }

  function clearErrors() {
    const errorInputs = form.querySelectorAll(".error");
    errorInputs.forEach((input) => input.classList.remove("error"));

    const errorSpans = form.querySelectorAll(".error-text");
    errorSpans.forEach((span) => span.remove());
  }
});
