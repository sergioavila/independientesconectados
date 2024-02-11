jQuery(document).ready(function () {
  console.log("main.js");
  const tooltipTriggerList = document.querySelectorAll(
    '[data-bs-toggle="tooltip"]'
  );
  const tooltipList = [...tooltipTriggerList].map(
    (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
  );
  jQuery(document).on("click", "#login-form #submit", function () {
    jQuery("#login-form #submit").prop("value", "Buscando...");
    jQuery("#login-form #submit").prop("disabled", true);
    jQuery("#login-error").html("");
    jQuery.ajax({
      url: ajaxurl,
      type: "POST",
      data: {
        action: "get_quimico",
        rut: jQuery("#login-form #user_login").val(),
      },
      success: function (response) {
        if (response.success) {
          window.location.reload();
        } else {
          jQuery("#login-form #submit").prop("value", "Buscar");
          jQuery("#login-form #submit").prop("disabled", false);
          jQuery("#login-error").html(response.message).show();
        }
      },
      error: function (response) {
        console.log("error", response);
        jQuery("#login-form #submit").prop("value", "Buscar");
        jQuery("#login-form #submit").prop("disabled", false);
        jQuery("#login-error").show();
      },
    });
    return false;
  });
  //form
  function validaDosPalabras(palabra) {
    if (palabra.trim().indexOf(" ") == -1) {
      return false;
    }
    return true;
  }
  function validarRut(rut) {
    rut = rut.replace(/[^k0-9]/gi, "");
    var dv = rut.slice(-1);
    var numero = rut.slice(0, -1);
    var i = 2;
    var suma = 0;
    numero
      .split("")
      .reverse()
      .forEach(function (v) {
        if (i == 8) i = 2;
        suma += parseInt(v) * i;
        i++;
      });
    var dvr = 11 - (suma % 11);

    if (dvr == 11) {
      dvr = 0;
    }
    if (dvr == 10) {
      dvr = "K";
    }

    if (dvr.toString().toUpperCase() === dv.toUpperCase()) {
      return true;
    } else {
      return false;
    }
  }
  jQuery.validator.addMethod(
    "validateRut",
    function (value, element) {
      return this.optional(element) || validarRut(value);
    },
    "Debes ingresar un RUT válido"
  );
  jQuery.validator.addMethod(
    "validaDosPalabras",
    function (value, element) {
      return this.optional(element) || validaDosPalabras(value);
    },
    "Debes ingresar dos palabras"
  );
  jQuery("#updateForm").validate({
    rules: {
      rut: { validateRut: true },
      name: { validaDosPalabras: true },
      lastname: { validaDosPalabras: true },
      correo: { required: true },
      fecha: { required: true },
      direccion: { required: true },
      rutfarmacia: { validateRut: true },
    },
    messages: {
      rut: "Ingresa un RUT válido.",
      name: "Ingresa tu nombre completo.",
      lastname: "Ingresa tus apellidos.",
      fecha: "Ingresa una fecha válida.",
      correo: "Ingresa un correo electrónico válido.",
      direccion: "Ingresa la dirección de tu farmacia",
      rutfarmacia: "Ingresa el RUT de tu farmacia.",
    },
    submitHandler: function (form) {
      console.log("form", form);
    },
  });
});
