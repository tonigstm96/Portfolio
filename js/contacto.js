document.addEventListener("DOMContentLoaded", () => {
  const showSuccessAlert = (onConfirm) => {
    Swal.fire({
      title: "¡Mensaje enviado!",
      text: "Gracias por ponerte en contacto. Te responderé lo antes posible.",
      icon: "success",
      confirmButtonColor: "#05F26C",
      iconColor: "#05F26C",
      background: "#132426",
      color: "#ffffff",
    }).then(() => {
      if (typeof onConfirm === "function") {
        onConfirm();
      }
    });
  };

  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.get("success") === "1") {
    showSuccessAlert();
  }

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

      const FORMSPREE_ID = "xgobqlqj";

      const isLocal =
        window.location.hostname === "localhost" ||
        window.location.hostname === "127.0.0.1";

      if (isLocal) {
        showSuccessAlert(() => {
          const jsValInput = document.getElementById("js_validated");
          if (jsValInput) jsValInput.value = "1";
          form.submit();
        });
      } else {
        const submitBtn = form.querySelector(".boton-enviar");
        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.textContent = "Enviando...";
        }

        fetch(`https://formspree.io/f/${FORMSPREE_ID}`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
          },
          body: JSON.stringify({
            nombre: inputs.nombre.value,
            apellido: inputs.apellido.value,
            correo: inputs.correo.value,
            telefono: inputs.telefono.value,
            mensaje: inputs.mensaje.value,
          }),
        })
          .then((response) => {
            if (response.ok) {
              showSuccessAlert(() => {
                form.reset();
                if (submitBtn) {
                  submitBtn.disabled = false;
                  submitBtn.textContent = "Enviar";
                }
              });
            } else {
              throw new Error("Error en el envío.");
            }
          })
          .catch((error) => {
            console.error(error);
            Swal.fire({
              title: "Error",
              text: "Hubo un problema al enviar tu mensaje. Por favor, inténtalo de nuevo.",
              icon: "error",
              confirmButtonColor: "#ff4444",
              background: "#132426",
              color: "#ffffff",
            });
            if (submitBtn) {
              submitBtn.disabled = false;
              submitBtn.textContent = "Enviar";
            }
          });
      }
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
