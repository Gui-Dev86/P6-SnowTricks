$(function () {
    $("div.trick").slice(0, 10).show();
    $("#loadMoreTrick").on("click", function (e) {
        e.preventDefault();
        $("div.trick:hidden").slice(0, 10).slideDown();
        if ($("div.trick:hidden").length === 0) {
            $("#loadMoreTrick").hide("slow");
            $("#loadLessTrick").show("slow");
        }
    });
    $("#loadLessTrick").on("click", function (e) {
        e.preventDefault();
        $("div.trick").slice(10, $("div.trick").length).hide();
        $("#loadLessTrick").hide("slow");
        $("#loadMoreTrick").show("slow");

    });
});

$(function () {
    $("div.comment").slice(0, 10).show();
    $("#loadMoreComment").on("click", function (e) {
        e.preventDefault();
        $("div.comment:hidden").slice(0, 10).slideDown();
        if ($("div.comment:hidden").length === 0) {
            $("#loadMoreComment").hide("slow");
            $("#loadLessComment").show("slow");
        }
    });
});