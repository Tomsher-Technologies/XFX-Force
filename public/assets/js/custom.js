function title_update(e) {
    title = e.value;
    title = title.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '')
    $('#slug').val(title)
}



// remove variant
$(document).on("click", ".remove-variant", function () {
    let container = $("#variants-container");
    let variantCount = container.find(".variant-box").length;
    if (variantCount > 1) {
        $(this).closest(".variant-box").remove();
        reindexVariants();
    } else {
        alert("At least one variant is required.");
    }
});

// reindex after delete
function reindexVariants() {
    $(".variant-box").each(function (i) {
        $(this).attr("data-index", i);
        $(this).find("h6").text("Product Variant " + (i + 1));
        $(this).find("input, select").each(function () {
            let name = $(this).attr("name");
            if (name) {
                name = name.replace(/variants\[\d+\]/, "variants[" + i + "]");
                $(this).attr("name", name);
            }
        });
    });
}

