jQuery(document).ready(function () {
  console.log("main.js");
  const tooltipTriggerList = document.querySelectorAll(
    '[data-bs-toggle="tooltip"]'
  );
  const tooltipList = [...tooltipTriggerList].map(
    (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
  );

  jQuery("#login-form").validate({
    rules: {
      user_login: { validateRut: true },
    },
    messages: {
      user_login: "Ingresa un RUT válido.",
    },
    submitHandler: function (form) {
      jQuery("#login-form #submit").prop("value", "Buscando...");
      jQuery("#login-form #submit").prop("disabled", true);
      jQuery("#user_login-error").html("");
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
            jQuery("#user_login-error").html(response).show();
          }
        },
        error: function () {
          jQuery("#login-form #submit").prop("value", "Buscar");
          jQuery("#login-form #submit").prop("disabled", false);
          jQuery("#user_login-error")
            .show()
            .html("Ha ocurrido un error, intenta nuevamente");
        },
      });
      return false;
    },
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
});
