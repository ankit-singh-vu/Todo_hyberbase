$(function () {
  $('[data-toggle="tooltip"]').tooltip();

  // Default functionality

  $(".accordion-first").accordion({
    collapsible: true,
    heightStyle: "content",
  });

  $(".accordion").accordion({
    active: 1,
    collapsible: true,
    heightStyle: "content",
  });

  // console.log("active section", $("#active-section")[0]);

  if ($("#active-section")[0]) {
    // console.log($("#active-section").attr("id"));
    let elemId = $("#active-section").attr("ind");
    $(".accordion-first").accordion({
      active: parseInt(elemId),
      collapsible: true,
      heightStyle: "content",
    });
  }

  $(".sign-contract").click(function () {
    console.log("hey man");
    $(this).prop("disabled", true);
    $(this).html("Loading...");
    $.get("/docsign/checkdoc", function (data) {
      $("#docu-sign-modal").modal();
      $(".sign-contract").prop("disabled", false);
      $(".sign-contract").html("Unlock by signing the contract");
      var $iframe = $("#docusign-section");
      if ($iframe.length) {
        $iframe.attr("src", data);
        return false;
      }
    });
  });
});

function signContract() {
  $(".sign-contract").prop("disabled", true);
  $(".sign-contract").html("Loading...");
  $.get("/docsign/checkdoc", function (data) {
    $("#docu-sign-modal").modal();
    $(".sign-contract").prop("disabled", false);
    $(".sign-contract").html("Sign Now");
    var $iframe = $("#docusign-section");
    if ($iframe.length) {
      $iframe.attr("src", data);
      return false;
    }
  });
}
