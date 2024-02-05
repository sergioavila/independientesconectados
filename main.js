jQuery(document).ready(function () {
  const tooltipTriggerList = document.querySelectorAll(
    '[data-bs-toggle="tooltip"]'
  );
  const tooltipList = [...tooltipTriggerList].map(
    (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
  );
  jQuery(document).on("click", "#login-form #submit", function () {
    jQuery("#login-form #submit").prop("value", "Buscando...");
    jQuery("#login-form #submit").prop("disabled", true);
    jQuery.ajax({
      url: ajaxurl,
      type: "POST",
      data: {
        action: "get_quimico",
        rut: jQuery("#login-form #user_login").val(),
      },
      success: function (response) {
        if (response.success) {
          console.log("success", response);
          window.location.reload();
        } else {
          console.log("error", response);
          jQuery("#login-form #submit").prop("value", "Buscar");
          jQuery("#login-form #submit").prop("disabled", false);
          jQuery("#login-form #login-error").show();
        }
      },
      error: function (response) {
        console.log("error", response);
        jQuery("#login-form #submit").prop("value", "Buscar");
        jQuery("#login-form #submit").prop("disabled", false);
        jQuery("#login-form #login-error").show();
      },
    });
    return false;
  });
});
