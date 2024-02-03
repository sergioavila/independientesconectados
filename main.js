jQuery(document).ready(function () {
  console.log("ready");
  jQuery("#wppb-login-wrap #wppb-submit").prop("value", "Buscar");
  jQuery("#wppb-login-wrap #user_login").prop("placeholder", "RUT");

  jQuery("#wppb-login-wrap #user_login").change(function () {
    jQuery("#wppb-login-wrap #user_pass").val(jQuery(this).val());
  });
  const tooltipTriggerList = document.querySelectorAll(
    '[data-bs-toggle="tooltip"]'
  );
  const tooltipList = [...tooltipTriggerList].map(
    (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
  );
});
