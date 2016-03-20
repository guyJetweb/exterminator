jQuery(document).ready(function ($) {
    $(".check_fcpt").click(function () {
        if ($(this).is(":checked")) {
            $(this).closest("tr").find(".fcpt_title").removeAttr("disabled");
        }
        else {
            $(this).closest("tr").find(".fcpt_title").attr("disabled","true").val("");
        }
    });
});